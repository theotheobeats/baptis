<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoHelper;
use App\Helpers\UserInfoHelper;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(UserInfoHelper::user_id());
        $employee = Employee::find(UserInfoHelper::employee_id());
        //$position = Position::find($employee->position_id);

        return view('pages.profiles.index', [
            "user" => $user,
            "employee" => $employee,
        ]);
    }

    public function update_password($id, Request $request)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = User::do_update_password($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function update_employee($id, Request $request)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Employee::do_update_employee($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }
}
