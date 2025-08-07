<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Imports\DueImport;
use App\Imports\PositionImport;
use App\Imports\StudentDueImport;
use App\Models\Classroom;
use App\Models\Due;
use App\Models\DueSubscription;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentDue;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\StudentT;
use Yajra\DataTables\Facades\DataTables;

class DueSubscriptionController extends Controller
{

    public static $information = [
        "title" => "Pendaftaran Iuran",
        "route" => "/due-management/subscription",
        "view" => "pages.due-managements.subscriptions."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("due_subscription", "view")) return abort(404);
        if ($request->ajax()) {
            $due_subscriptions = new StudentDue();
            $due_subscriptions = $due_subscriptions->leftJoin("dues", "dues.id", "=", "student_dues.due_id");
            $due_subscriptions = $due_subscriptions->leftJoin("students", "students.id", "=", "student_dues.student_id");
            $due_subscriptions = $due_subscriptions->select(
                "student_dues.*",
                "dues.name as due_name",
                "dues.can_cancel as due_can_cancel",
                "students.nis as student_nis",
                "students.name as student_name"
            );
            return DataTables::of($due_subscriptions)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->id);
                    $btn = "";
                    $unsubscribe_action = 'unsubscribe_confirm("' . url(self::$information['route'] . '/single-unsubscribe') . '/' . $encrypted_id . '")';
                    $due_price_change_action = 'due_price_modal("' . url(self::$information['route'] . '/due-price-change') . '/' . $encrypted_id . '")';
                    $btn = "<div class='btn-group m-0'>";
                    if ($row->due_can_cancel == 1) {
                        $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$unsubscribe_action' title='Berhenti'><i class='fa fa-ban'></i></a>";
                    }
                    $btn .= "<a class='btn btn-outline-primary' href='#' onclick='$due_price_change_action' title='Berhenti'><i class='fa fa-pencil'></i></a>";
                    $btn .= "</div>";
                    return $btn;
                })
                ->editColumn('updated_at', function ($data) {
                    $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->translatedFormat('d F Y - H:i:s');
                    return $formatedDate;
                })
                ->editColumn('price', function ($data) {
                    return "Rp." . number_format($data->price);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view(self::$information['view'] . 'index', [
            "information" => self::$information
        ]);
    }

    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("due_subscription", "add")) return abort(404);
        $result = StudentDue::subscribe($request);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function student_due_price_change($id, Request $request)
    {
        if (!UserInfoHelper::has_access("due_subscription", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = StudentDue::student_due_price_change($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function export_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("due_subscription", "export")) return abort(404);
        $dues = Due::get();

        $students = Student::whereNull('non_active_at')->get();

        $result = array();
        $result[] = [
            ['text' => 'ID'],
            ['text' => 'NIS'],
            ['text' => 'Nama'],
        ];

        foreach ($dues as $due) {
            $result[0][] = ['text' => $due->name];
        }

        foreach ($students as $student) {
            $due_prices = [];

            $student_dues = StudentDue::where('student_dues.student_id', $student->id)->get();

            foreach ($dues as $due) {
                $price = 0;
                $student_due = $student_dues->where('due_id', $due->id)->first();

                if ($student_due !== null) {
                    $price = $student_due->price;
                }

                $due_prices[] = ['text' => $price];
            }

            $result[] = [
                ['text' => $student->id],
                ['text' => $student->nis],
                ['text' => $student->name],
                ...$due_prices,
            ];
        }
        return response()->json($result);
    }

    public function import_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("due_subscription", "import")) return abort(404);
        ini_set('max_execution_time', 300);
        try {
            DB::beginTransaction();
            Excel::import(new StudentDueImport, request()->file('file-excel'));
            $result = ResponseHelper::response_success('Berhasil', 'Data telah diimport');
            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return response()->json($result['client_response'], $result['code']);
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Import Gagal", "Proses import data gagal!" . $e);
        }
    }

    // Menu Iuran Siswa per Bulan
    public function subscription_per_month_list(Request $request)
    {
        $first_classroom = Classroom::orderBy('id', 'asc')->first();
        $last_school_year = SchoolYear::where('is_active', '=', '1')->orderBy('id', 'desc')->first();
        $payment_for_month = $request->payment_for_month == null ? date("m") : $request->payment_for_month;
        $payment_for_year = $request->payment_for_year == null ? date("Y") : $request->payment_for_year;
        $classroom_id = $request->classroom_id == null ? ($first_classroom != null ? $first_classroom->id : null) : $request->classroom_id;
        $school_year_id = $request->school_year_id == null ? ($last_school_year != null ? $last_school_year->id : null) : $request->school_year_id;

        $selected_classroom = Classroom::find($classroom_id);
        $selected_school_year = SchoolYear::find($school_year_id);

        if ($request->ajax()) {
            $invoice_details = new InvoiceDetail();
            // $invoices = $invoices->join("students", "students.id", "=", "invoices.student_id");
            $invoice_details = $invoice_details->join("students", "students.id", "=", "invoice_details.backtrack_student_id");
            // $invoice_details = $invoice_details->join("invoice_details", "invoice_details.invoice_id", "=", "invoices.id");
            $invoice_details = $invoice_details->join("classrooms", "classrooms.id", "=", "invoice_details.classroom_id");
            $invoice_details = $invoice_details->join("school_years", "school_years.id", "=", "invoice_details.school_year_id");
            $invoice_details = $invoice_details->whereNull("students.non_active_at");
            $invoice_details = $invoice_details->select(
                "invoice_details.invoice_id",
                "invoice_details.payment_for_month",
                "invoice_details.payment_for_year",
                "students.nis as student_nis",
                "students.name as student_name",
                "classrooms.name as classroom_name",
                DB::raw("CONCAT(school_years.name, ' ', school_years.semester) as school_year_name"),
                DB::raw("SUM(invoice_details.price) as total_price"),
                DB::raw("SUM(invoice_details.payed_amount) as total_payed_amount")
            );
            $invoice_details = $invoice_details->groupBy(
                "invoice_details.invoice_id",
                "invoice_details.payment_for_month",
                "invoice_details.payment_for_year",
                "students.nis",
                "students.name",
                "classrooms.name",
                "school_years.name",
                "school_years.semester"
            );
            // $invoice_details = $invoice_details->orderBy("invoice_details.created_at", "desc");

            if ($payment_for_month != null) {
                $invoice_details = $invoice_details->where("invoice_details.payment_for_month", "=", $payment_for_month);
            }

            if ($payment_for_year != null) {
                $invoice_details = $invoice_details->where("invoice_details.payment_for_year", "=", $payment_for_year);
            }

            if ($classroom_id != null) {
                $invoice_details = $invoice_details->where("invoice_details.classroom_id", "=", $classroom_id);
            }

            if ($school_year_id != null) {
                $invoice_details = $invoice_details->where("invoice_details.school_year_id", "=", $school_year_id);
            }

            return DataTables::of($invoice_details)
                ->addIndexColumn()
                ->editColumn('total_price', function ($data) {
                    return "Rp " . number_format($data->total_price);
                })
                ->editColumn('total_payed_amount', function ($data) {
                    return "Rp " . number_format($data->total_payed_amount);
                })
                ->filterColumn('total_price', function($query, $keyword) {
                    $query->havingRaw("total_price LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('total_payed_amount', function($query, $keyword) {
                    $query->havingRaw("total_payed_amount LIKE ?", ["%{$keyword}%"]);
                })
                ->editColumn('payment_for_month', function ($data) {
                    $month_list = [
                        "01" => "Januari",
                        "02" => "Februari",
                        "03" => "Maret",
                        "04" => "April",
                        "05" => "Mei",
                        "06" => "Juni",
                        "07" => "Juli",
                        "08" => "Agustus",
                        "09" => "September",
                        "10" => "Oktober",
                        "11" => "November",
                        "12" => "Desember",
                    ];
                    return $month_list[$data->payment_for_month];
                })
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->invoice_id);
                    $view_detail_action = 'view_paid_invoice_detail("' . $encrypted_id . '", "' . $row->payment_for_month . '", "' . $row->payment_for_year . '")';
                    $btn = "<div class='btn-group m-0'>";
                    $btn .= "<button class='btn btn-outline-primary' type='button' title='Informasi' onclick='$view_detail_action'><i class='fa fa-eye'></i></button>";
                    $btn .= "</div>";

                    return $btn;
                })
                ->make(true);
        }

        return view(self::$information['view'] . 'index-per-month', [
            "information" => self::$information,
            "classroom" => isset($classroom) ? $classroom : null,
            "school_year" => isset($school_year) ? $school_year : null,
            "filter_params" => [
                "payment_for_month" => $payment_for_month,
                "payment_for_year" => $payment_for_year,
                "classroom_id" => $classroom_id,
                "school_year_id" => $school_year_id,
                "selected_classroom" => $selected_classroom,
                "selected_school_year" => $selected_school_year,
            ]
        ]);
    }

}
