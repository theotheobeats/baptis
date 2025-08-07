<?php

namespace App\Http\Controllers;

use App\Helpers\UserInfoHelper;
use App\Models\StudentClassroom;
use Illuminate\Http\Request;

class ClassChangeController extends Controller
{
    public static $information = [
        "title" => "Pindah Kelas",
        "route" => "/class-management/class-change",
        "view" => "pages.class-managements.change."
    ];

    // ======================
    // Line View - START
    // ======================

    public function index()
    {
        if (!UserInfoHelper::has_access("class_change", "view")) return abort(404);
        return view(self::$information["view"] . "index", [
            "information" => self::$information
        ]);
    }

    public function create()
    {
        if (!UserInfoHelper::has_access("class_change", "add")) return abort(404);
        return view(self::$information['view'] . 'add', [
            "information" => self::$information
        ]);
    }

    // ======================
    // Line Proses - START
    // ======================

    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("class_change", "add")) return abort(404);
        $result = StudentClassroom::do_change_classroom($request);
        return response()->json($result["client_response"], $result["code"]);
    }

    // public function export_excel(Request $request)
    // {
    //     return response()->json("Test Export");
    // }

    // public function import_excel(Request $request)
    // {
    //     // Excel::import(new StudentImport, request()->file('file-excel'));
    //     // $result = ResponseHelper::response_success('Berhasil', 'Data telah diimport');
    //     // return response()->json($result['client_response'], $result['code']);

    //     return response()->json("Test Import");
    // }
}
