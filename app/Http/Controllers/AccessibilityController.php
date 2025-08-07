<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoHelper;
use App\Helpers\UserInfoHelper;
use App\Models\Accessibility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class AccessibilityController extends Controller
{
    public static $information = [
        "title" => "Master Akses",
        "route" => "/master/accessibility",
        "view" => "pages.master.accessibility."
    ];

    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("accessibility", "view")) return abort(404);
        if ($request->ajax()) {
            $accessibilities = new Accessibility();
            $accessibilities = $accessibilities->select("accessibilities.*");
            return DataTables::of($accessibilities)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->id);
                    $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_id;
                    $delete_action = 'delete_confirm("' . url(self::$information['route'] . '/delete') . '/' . $encrypted_id . '")';
                    $btn = "<div class='btn-group'>";
                    $btn .= "<a class='btn btn-outline-primary' href='$url' title='Edit Data'><i class='fa fa-pencil'></i></a>";
                    $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$delete_action' title='Hapus Data'><i class='fa fa-trash'></i></a>";
                    $btn .= "</div>";
                    return $btn;
                })
                ->editColumn('created_at', function ($data) {
                    $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->translatedFormat('d F Y - H:i:s');
                    return $formatedDate;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view(self::$information['view'] . 'index', [
            "information" => self::$information
        ]);
    }

    public function create()
    {
        if (!UserInfoHelper::has_access("accessibility", "add")) return abort(404);
        return view(self::$information['view'] . 'add', [
            "information" => self::$information,
        ]);
    }

    public function edit($id, Request $request)
    {
        if (!UserInfoHelper::has_access("accessibility", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $accessibilities = Accessibility::find($decrypt->id);

        $data = json_decode($accessibilities->access);

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "accessibilities" => $accessibilities,
            "data" => $data
        ]);
    }

    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("accessibility", "add")) return abort(404);
        $result = Accessibility::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }

    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        if (!UserInfoHelper::has_access("accessibility", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Accessibility::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses hapus data
    public function destroy($id)
    {
        if (!UserInfoHelper::has_access("accessibility", "delete")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Accessibility::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }
}
