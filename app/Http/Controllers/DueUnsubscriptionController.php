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
use App\Models\Due;
use App\Models\DueSubscription;
use App\Models\DueUnsubscription;
use App\Models\StudentDue;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class DueUnsubscriptionController extends Controller
{

    public static $information = [
        "title" => "Berhenti Iuran",
        "route" => "/due-management/unsubscription",
        "view" => "pages.due-managements.unsubscriptions."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $due_unsubscriptions = new DueUnsubscription();
            $due_unsubscriptions = $due_unsubscriptions->leftJoin("dues", "dues.id", "=", "due_unsubscriptions.due_id");
            $due_unsubscriptions = $due_unsubscriptions->leftJoin("students", "students.id", "=", "due_unsubscriptions.student_id");
            $due_unsubscriptions = $due_unsubscriptions->select("due_unsubscriptions.*", "dues.name as due_name", "students.name as student_name");
            return DataTables::of($due_unsubscriptions)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->id);
                    $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_id;
                    $delete_action = 'delete_confirm("' . url(self::$information['route'] . '/delete') . '/' . $encrypted_id . '")';
                    $btn = "<div class='btn-group m-0'>";
                    $btn .= "<a class='btn btn-outline-primary' href='$url' title='Edit Data'><i class='fa fa-pencil'></i></a>";
                    $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$delete_action' title='Hapus Data'><i class='fa fa-trash'></i></a>";
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
        $result = DueUnsubscription::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function single_unsubscribe($id, Request $request)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = StudentDue::single_unsubscribe($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }
    
    public function unsubscribe(Request $request)
    {
        $result = DueUnsubscription::unsubscribe($request);
        return response()->json($result["client_response"], $result["code"]);
    }
}
