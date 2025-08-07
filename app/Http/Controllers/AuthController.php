<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Helpers\UserInfoHelper;
use App\Models\EmployeeWarehouse;
use App\Models\WarehouseBranch;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\Hash as FacadesHash;

class AuthController extends Controller
{
    public function login() {
        return view('pages.auth.login');
    }

    public function do_login(Request $request) {
        $username = $request->username;
        $password = $request->password;

        $user = User::where("username", "=", $username)->first();


        // Jika username ditemukan
        if ($user != null) {
            if (!empty($user[0]->deleted_at)) {
                return response()->json([
                    "code" => -1,
                    "response" => [
                        'type'      => 'error',
                        'title'     => 'Login gagal!',
                        'message'   => 'Akun tidak dapat diakses'
                    ]
                ]);
            }

            // Jika password yang diinput sama
            if (FacadesHash::check($password, $user->password)) {

                // Waktu saat ini < waktu logout yg akan datang
                // if ($user->logout_time != null && Carbon::now()->lt($user->logout_time)) {
                //     // Jika akun yg login dengan ip yang beda
                //     if ($user->web_ip != null && $user->web_ip != UserInfoHelper::get_user_ip()) {
                //         return response()->json([
                //             "code" => -1,
                //             "response" => [
                //                 'type'      => 'error',
                //                 'title'     => 'Akun sedang digunakan',
                //                 'message'   => 'Harap coba kembali dalam beberapa waktu'
                //             ]
                //         ]);
                //     }
                // }
                // $user = User::find($user->id);
                // $user->web_ip = UserInfoHelper::get_user_ip();
                // $user->logout_time = Carbon::now()->addMinutes(15);
                // $user->save();
                
                // 1. Masukkan data akun
                $request->session()->put('user', $user);

                // 2. Masukkan data employee juga
                $employee = Employee::find($user->employee_id);
                $request->session()->put('employee', $employee);

                // 3. Return berhasil
                return response()->json([
                    "code" => 200,
                    "response" => [
                        'type'      => 'success',
                        'title'     => 'Login Berhasil!',
                        'message'   => 'Kamu akan segera dialihkan ke halaman utama'
                    ]
                ]);
            }
        }

        return response()->json([
            "code" => -1,
            "response" => [
                'type'      => 'error',
                'title'     => 'Login gagal!',
                'message'   => 'Username atau password salah.'
            ]
        ]);
    }

    public function logout(Request $request) {
        $user = User::withTrashed()->find(UserInfoHelper::user_id());
        $user->logout_time = null;
        $user->save();
        $request->session()->forget('user');
        return redirect('/login');
    }
}
