<?php

namespace App\Http\Middleware;

use App\Helpers\UserInfoHelper;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Carbon\Carbon;

class AuthSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->session()->has('user')) {
            // Cek akun disuspend
            return redirect('/login')->with([
                "login_required" => [
                    "title" => "Login gagal!",
                    "message" => "Anda tidak memiliki akses kedalam sistem, harap login terlebih dahulu.",
                    "type" => "error"
                ]
            ]);
        }

        $account = User::findOrFail($request->session()->get('user')->id);

        // Jika akun tidak ada (null)
        if(empty($account)) {
            $request->session()->flush();
            return redirect('/login')->with([
                "login_required" => [
                    "title" => "Login gagal!",
                    "message" => "Akun tidak ditemukan.",
                    "type" => "error"
                ]
            ]);
        }

        // Jika akun telah dihapus / suspend
        if(!empty($account->deleted_at)) {
            $request->session()->flush();
            return redirect('/login')->with([
                "login_required" => [
                    "title" => "Login gagal!",
                    "message" => "Akun anda tidak memiliki akses kedalam sistem lagi",
                    "type" => "error"
                ]
            ]);
        }

        // Untuk cek double login
        // if($account->web_ip != UserInfoHelper::get_user_ip()) {
        //     $request->session()->flush();
        //     return redirect('/login')->with([
        //         "login_required" => [
        //             "title" => "Login gagal!",
        //             "message" => "Akun sedang digunakan pada perangkat lain",
        //             "type" => "error"
        //         ]
        //     ]);
        // }

        // Update data session
        $account_id = $request->session()->get("user")->id;
        $employee_id = $request->session()->get("employee")->id;

        $account = User::find($account_id);
        $account->logout_time = Carbon::now()->addMinutes(15);
        $account->save();
        
        $employee = Employee::find($employee_id);

        $request->session()->put('user', $account);
        $request->session()->put('employee', $employee);
        
        return $next($request);
    }
}
