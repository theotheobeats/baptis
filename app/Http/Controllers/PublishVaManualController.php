<?php

namespace App\Http\Controllers;

use App\Helpers\UserInfoHelper;
use App\Models\PublishVaManual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class PublishVaManualController extends Controller
{
    public static $information = [
        "title" => "Terbit Tagihan Manual",
        "route" => "/transaction/publish-va-manual",
        "view" => "pages.transactions.publish-va-manual."
    ];


    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("publish_va_manual", "view")) return abort(404);
        if ($request->ajax()) {
            $publish_va_manuals = new PublishVaManual();
            $publish_va_manuals = $publish_va_manuals->join("students", "students.id", "=", "publish_va_manuals.student_id");
            $publish_va_manuals = $publish_va_manuals->select("publish_va_manuals.*", "students.name as student_name");
            return DataTables::of($publish_va_manuals)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view(self::$information['view'] . 'index', [
            "information" => self::$information
        ]);
    }

    public function create()
    {
        if (!UserInfoHelper::has_access("publish_va_manual", "add")) return abort(404);
        return view(self::$information['view'] . 'add', [
            "information" => self::$information
        ]);
    }


    //
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("publish_va_manual", "add")) return abort(404);
        // $decrypt = CryptoHelper::decrypt($request->id);
        // if (!$decrypt->success) return $decrypt->error_response;

        $result = PublishVaManual::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }
}
