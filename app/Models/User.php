<?php

namespace App\Models;

use App\Helpers\HasTrackHistory;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class User extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "users";

    // [MANUAL CHECKLIST]
    // Cek input, cek ubah, cek hapus
    // Cek siapa yang input, ubah, hapus
    // Cek kesesuaian data yang diinput dengan kolom tabel yang diisi

    public static function do_store(Request $request)
    {
        // Proses validasi
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'pin' => 'required|min:6',
            'employee_id' => 'required'
        ]);

        // Proses input
        try {
            DB::beginTransaction();

            // Proses Input data
            $user = new User;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->pin = Crypt::encrypt($request->pin);
            $user->employee_id = $request->employee_id;
            $user->access = Accessibility::create_accessibility($request);

            $user->created_by = UserInfoHelper::employee_id();
            $user->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Input Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Input Gagal", "Proses input data gagal! " . $e);
        }
    }

    public static function do_update($id, Request $request)
    {
        // Proses validasi
        $validation = [
            'username' => 'required',
            'email' => 'required',
            'employee_id' => 'required'
        ];

        if ($request->pin != null) {
            $validation['pin'] = 'required|min:6';
        }

        $request->validate($validation);

        // Proses update
        try {
            DB::beginTransaction();

            // Proses cari data
            $user = User::find($id);

            // Cek data ada di database
            if ($user == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");

            // Jika ada, lanjut update data
            $user->email = $request->email;
            $user->username = $request->username;
            if ($request->password != null) {
                $user->password = bcrypt($request->password);
            }
            if ($request->pin != null) {
                $user->pin = Crypt::encrypt($request->pin);
            }
            $user->employee_id = $request->employee_id;
            $user->access = Accessibility::create_accessibility($request);

            $user->updated_by = UserInfoHelper::employee_id();
            $user->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Ubah Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Ubah Gagal", "Proses input data gagal!");
        }
    }

    public static function do_delete($id)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $user = User::find($id);

            // Cek data ada di database
            if ($user == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            // Jika ada, input data yang hapus
            $user->deleted_by = UserInfoHelper::employee_id();
            $user->deleted_at = now();
            $user->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Hapus Berhasil", "Data telah dihapus");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Hapus Gagal", "Proses hapus data gagal!");
        }
    }


    //update password via profile page
    public static function do_update_password($id, Request $request)
    {
        try {
            DB::beginTransaction();

            // Proses cari data
            $user = User::find($id);


            if (Hash::check($request->old_password, $user->password)) {
                if ($request->new_password == $request->confirm_new_password) {
                    $user->password = bcrypt($request->new_password);
                    $user->updated_by = UserInfoHelper::employee_id();
                    $user->save();
                    DB::commit();
                    return ResponseHelper::response_success("Proses Ubah Berhasil", "Data telah disimpan");
                }
                return ResponseHelper::response_error("Proses Ubah Gagal", "Konfirmasi Password Tidak Sama");
            }
            return ResponseHelper::response_error("Proses Ubah Gagal", "Password Lama Salah");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Ubah Gagal", "Proses input data gagal!");
        }
    }

}
