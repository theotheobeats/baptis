<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Due;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\Position;
use App\Imports\DueImport;
use App\Models\StudentDue;
use App\Models\BillIssuance;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\DataHelper;
use App\Helpers\InvoicePublisherHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Helpers\WhatsappNotificationHelper;
use App\Imports\PositionImport;
use App\Models\InvoiceDetail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class BillIssuanceController extends Controller
{

    public static $information = [
        "title" => "Penerbitan Tagihan",
        "route" => "/transaction/bill-issuance",
        "view" => "pages.transactions.bill-issuance."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan form input data
    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("bill_issuance", "view")) return abort(404);
        $students = Student::select("id", "name")->get();
        $dues = Due::select("id", "name")->get();

        if ($request->ajax()) {
            $invoices = new Invoice();
            $invoices = $invoices->leftJoin("students", "students.id", "=", "invoices.student_id");
            $invoices = $invoices->select(
                "invoices.*",
                "students.name as student_name",
                "students.nis as student_nis",
                "students.id as student_id"
            );
            return DataTables::of($invoices)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_student_id = Crypt::encrypt($row->student_id);
                    $encrypted_invoice_id = Crypt::encrypt($row->id);
                    $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_student_id;
                    // $publish_action = 'publish_individual_invoice("' . url(self::$information['route'] . '/publish-individual-invoice') . '/' . $encrypted_student_id . '")';
                    $send_invoice_notification = 'send_invoice_notification("' . $encrypted_invoice_id . '")';
                    $btn = "<div class='btn-group m-0'>";
                    // $btn .= "<a class='btn btn-outline-primary' href='#' onclick='$publish_action' title='Terbitkan Tagihan Individu'><i class='fa fa-file'></i> Terbit Tagihan</a>";
                    $btn .= "<a class='btn btn-outline-success btn-sm' href='#' onclick='$send_invoice_notification' title='Kirimkan Notifikasi'><i class='fa fa-file'></i> Kirim Whatsapp</a>";
                    $btn .= "</div>";
                    return $btn;
                })
                ->editColumn('bill_price', function ($data) {
                    return 'Rp. ' . number_format($data->bill_price, 0, ',', '.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view(self::$information['view'] . 'index', [
            "information" => self::$information,
            "students" => $students,
            "dues" => $dues
        ]);
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $invoices = new Invoice();
            $invoices = $invoices->leftJoin("students", "students.id", "=", "invoices.student_id");
            $invoices = $invoices->leftJoin("dues", "dues.id", "=", "invoices.due_id");
            $invoices = $invoices->select(
                "invoices.*",
                "students.name as student_name",
                "students.nis as student_nis",
                "dues.name as due_name"
            );
            return DataTables::of($invoices)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->id);
                    $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_id;
                    // $delete_action = 'delete_confirm("' . url(self::$information['route'] . '/delete') . '/' . $encrypted_id . '")';
                    $btn = "<div class='btn-group m-0'>";
                    $btn .= "<a class='btn btn-outline-primary' href='$url' title='Edit Data'><i class='fa fa-pencil'></i></a>";
                    // $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$delete_action' title='Hapus Data'><i class='fa fa-trash'></i></a>";
                    $btn .= "</div>";
                    return $btn;
                })
                ->editColumn('updated_at', function ($data) {
                    $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->translatedFormat('d F Y - H:i:s');
                    return $formatedDate;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view(self::$information['view'] . 'history', [
            "information" => self::$information
        ]);
    }




    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("bill_issuance", "add")) return abort(404);
        $result = Invoice::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    public function get_student_dues(Request $request)
    {
        $student_id = $request->student_id;
        $student_dues = StudentDue::join("dues", "dues.id", "=", "student_dues.due_id")
            ->where("student_id", "=", $student_id)
            ->select("dues.id as due_id", "dues.name as due_name", "dues.price as due_price")
            ->get();

        return response()->json([
            "student_dues" => $student_dues
        ]);
    }

    public function export_excel(Request $request)
    {
        ini_set('max_execution_time', 300);
        if (!UserInfoHelper::has_access("bill_issuance", "export")) return abort(404);
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $start_month = Carbon::parse($start_date)->format('m');
        $start_year = Carbon::parse($start_date)->format('Y');
        $end_month = Carbon::parse($end_date)->format('m');
        $end_year = Carbon::parse($end_date)->format('Y');

        $month_count = Carbon::parse($start_date)->diffInMonths(Carbon::parse($end_date)) + 1;

        $dues = Due::select("id", "name")->get();

        $month_year_dues = [];
        $temp_month = $start_month;
        $temp_year = $start_year;

        for($i = 0; $i < $month_count; $i++) {
            foreach ($dues as $due) {
                $month_year_dues[] = [
                    "month" => sprintf("%02d", $temp_month),
                    "year" => sprintf("%04d", $temp_year),
                    "due_id" => $due->id,
                    "due_name" => $due->name
                ];
            }
            $month_year_dues[] = [
                "month" => "total",
                "year" => "total",
                "due_id" => "total",
                "due_name" => "total"
            ];

            $month_year_dues[] = [
                "month" => "payed",
                "year" => "payed",
                "due_id" => "payed",
                "due_name" => "payed"
            ];

            if($temp_month > 12) {
                $temp_month = 1;
                $temp_year++;
            }
            else {
                $temp_month++;
            }
        }

        $result[] = [
            ["text" => "No"],
            ["text" => "ID*"],
            ["text" => "NIS"],
            ["text" => "Nama Siswa"],
            ["text" => "Kelas"],
        ];

        $result[1] = [
            ["text" => ""],
            ["text" => ""],
            ["text" => ""],
            ["text" => ""],
            ["text" => ""]
        ];

        for ($i = 0; $i < $month_count; $i++) {
            $this_date = Carbon::parse($start_date)->addMonths($i);
            $this_month = Carbon::parse($this_date)->format('m');
            $this_year = Carbon::parse($this_date)->format('Y');
            $result[0][] = ["text" => $this_month . "/" . $this_year];

            for ($j = 0; $j < count($dues) - 1 ; $j++) {
                $result[0][] = ["text" => ""];
            }
            $result[0][] = ["text" => ""]; // Total
            $result[0][] = ["text" => ""]; // Payed

            foreach ($dues as $due) {
                $result[1][] = ["text" => $due->name];
            }
            $result[1][] = ["text" => "Total"];
            $result[1][] = ["text" => "Dibayar"];
        }

        // $invoice_details = new InvoiceDetail();
        // $invoice_details = $invoice_details->leftJoin("invoices", "invoices.id", "=", "invoice_details.invoice_id");
        // $invoice_details = $invoice_details->where("invoice_details.payment_for_month", '>=', $start_month);
        // $invoice_details = $invoice_details->where("invoice_details.payment_for_year", '>=', $start_year);
        // $invoice_details = $invoice_details->where("invoice_details.payment_for_month", '<=', $end_month);
        // $invoice_details = $invoice_details->where("invoice_details.payment_for_year", '<=', $end_year);
        // $invoice_details = $invoice_details->select(
        //     "invoice_details.*",
        //     "invoices.student_id"
        // );
        // $invoice_details = $invoice_details->get();

        $students = Student::select("id", "nis", "name", "backtrack_current_classroom_name")->whereNull("non_active_at")->get();

        $i = 1;
        $row = 2;
        foreach ($students as $student) {
            $result[$row] = [
                ["text" => $i],
                ["text" => $student->id],
                ["text" => $student->nis],
                ["text" => $student->name],
                ["text" => $student->backtrack_current_classroom_name]
            ];
            $index_column = 5;
            $total = 0;
            $payed = 0;


            $invoice_details = new InvoiceDetail();
            $invoice_details = $invoice_details->leftJoin("invoices", "invoices.id", "=", "invoice_details.invoice_id");
            $invoice_details = $invoice_details->where("invoice_details.payment_for_month", '>=', $start_month);
            $invoice_details = $invoice_details->where("invoice_details.payment_for_year", '>=', $start_year);
            $invoice_details = $invoice_details->where("invoice_details.payment_for_month", '<=', $end_month);
            $invoice_details = $invoice_details->where("invoice_details.payment_for_year", '<=', $end_year);
            $invoice_details = $invoice_details->where("invoices.student_id", '=', $student->id);
            $invoice_details = $invoice_details->select(
                "invoice_details.*",
                "invoices.student_id"
            );
            $invoice_details = $invoice_details->get();
            
            foreach ($month_year_dues as $myd) {

                foreach ($invoice_details as $key => $value) {
                    if ($student->id == $value->student_id
                    && $myd['month'] == $value->payment_for_month
                    && $myd['year'] == $value->payment_for_year
                    && $myd['due_id'] == $value->due_id)
                    {
                        $result[$row][$index_column] = ["text" => $value->price];
                        $total += $value->price;
                        $payed += $value->payed_amount;
                        unset($invoice_details[$key]);
                        break;
                    }

                    // if ($student->id == $invoice_detail->student_id
                    // && $myd['month'] == $invoice_detail->payment_for_month
                    // && $myd['year'] == $invoice_detail->payment_for_year
                    // && $myd['due_id'] == $invoice_detail->due_id)
                    // {
                    //     $result[$row][$index_column] = ["text" => $invoice_detail->price];
                    //     $total += $invoice_detail->price;
                    //     $payed += $invoice_detail->payed_amount;
                    //     $invoice_detail->forget();
                    //     break;
                    // }
                }



                // foreach ($invoice_details as $invoice_detail) {
                //     if ($student->id == $invoice_detail->student_id
                //     && $myd['month'] == $invoice_detail->payment_for_month
                //     && $myd['year'] == $invoice_detail->payment_for_year
                //     && $myd['due_id'] == $invoice_detail->due_id)
                //     {
                //         $result[$row][$index_column] = ["text" => $invoice_detail->price];
                //         $total += $invoice_detail->price;
                //         $payed += $invoice_detail->payed_amount;
                //         break;
                //     }
                // }
                if ($myd['month'] == "total" && $myd['year'] == "total") {
                    $result[$row][$index_column] = ["text" => $total];
                    $total = 0;
                }
                if ($myd['month'] == "payed" && $myd['year'] == "payed") {
                    $result[$row][$index_column] = ["text" => $payed];
                    $payed = 0;
                }
                if (!isset($result[$row][$index_column])) {
                    $result[$row][$index_column] = ["text" => ""];
                }
                $index_column++;
            }
            $i++;
            $row++;
        }
        return response()->json($result);
    }





    public function send_invoice_notification(Request $request)
    {
        $invoice_id = Crypt::decrypt($request->invoice_id);
        $invoice = Invoice::find($invoice_id);

        InvoicePublisherHelper::single_invoice_publish_and_notification($invoice_id);

        // $student = Student::find($invoice->student_id);

        // // Cari daftar tagihan yang aktif
        // $invoice_details = InvoiceDetail::where("invoice_id", "=", $invoice_id)
        //     ->leftJoin("dues", "dues.id", "=", "invoice_details.due_id")
        //     ->whereNull("invoice_details.cancel_reason")
        //     ->where("invoice_details.payed_amount", "=", 0)
        //     ->select(
        //         "invoice_details.*",
        //         "dues.name as due_name",)
        //     ->get();

        // $i = 1;
        // $invoice_information = "";
        // $invoice_total = 0;
        
        // foreach ($invoice_details as $invoice_detail) {
        //     $invoice_information .= $i . ". " . $invoice_detail->due_name . " : Rp" . number_format($invoice_detail->price) . "\\n";
        //     $invoice_total += $invoice_detail->price;
        //     $i++;
        // }

        // echo json_encode([
        //     "user_name" => $student->name,
        //     "number" => $student->backtrack_student_whatsapp_number,
        //     "variabel" => [
        //         "{{1}}" => "(text)" . $invoice->payment_for_month . " " . $invoice->payment_for_year,
        //         "{{2}}" => "(text)" . $student->name,
        //         "{{3}}" => "(text)" . $invoice->payment_for_month,
        //         "{{4}}" => "(text)" . $invoice_information,
        //         "{{5}}" => "(text)" . $invoice_total,
        //         "{{6}}" => "(text)" . "Nomor VA",
        //     ]
        // ]);
        // dd ([
        //     "user_name" => $student->name,
        //     "number" => $student->backtrack_student_whatsapp_number,
        //     "variabel" => [
        //         "{{1}}" => "(text)" . $invoice->payment_for_month . " " . $invoice->payment_for_year,
        //         "{{2}}" => "(text)" . $student->name,
        //         "{{3}}" => "(text)" . $invoice->payment_for_month,
        //         "{{4}}" => "(text)" . $invoice_information,
        //         "{{5}}" => "(text)" . $invoice_total,
        //         "{{6}}" => "(text)" . "Nomor VA",
        //     ]
        //     ]);

        // [
        //     "user_name" => "dewi",
        //     "number" => "6282177511334",
        //     "variabel" => [
        //         "{{1}}" => "(text)01 Mei 2024",
        //         "{{2}}" => "(text)Tengku Kevin Juldianto",
        //         "{{3}}" => "(text)Mei",
        //         "{{4}}" => "(text)1. SPP : 300,000\\n2. Ekskul Robotik : 100,000\\n3. Ekskul Karate : 100,000",
        //         "{{5}}" => "(text)500,000",
        //         "{{6}}" => "(text)13984002394",
        //     ]
        // ]
        // WhatsappNotificationHelper::send_invoice_notification_message_template_custom([
        //     "user_name" => $student->name,
        //     "number" => $student->backtrack_student_whatsapp_number,
        //     "variabel" => [
        //         "{{1}}" => "(text)" . DataHelper::get_month_name($invoice->payment_for_month - 1) . " " . $invoice->payment_for_year,
        //         "{{2}}" => "(text)" . $student->name,
        //         "{{3}}" => "(text)" . DataHelper::get_month_name($invoice->payment_for_month - 1),
        //         "{{4}}" => "(text)" . $invoice_information,
        //         "{{5}}" => "(text)" . "Rp" . number_format($invoice_total),
        //         "{{6}}" => "(text)" . "Nomor VA",
        //     ]
        // ]);

        $result = ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        return response()->json($result["client_response"], $result["code"]);

    }
}
