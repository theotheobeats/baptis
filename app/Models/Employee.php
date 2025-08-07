<?php

namespace App\Models;

use App\Helpers\HasTrackHistory;
use App\Helpers\ResponseHelper;
use App\Helpers\UploadFilePathHelper;
use App\Helpers\UserInfoHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "employees";

    // [MANUAL CHECKLIST]
    // Cek input, cek ubah, cek hapus [PASSED]
    // Cek siapa yang input, ubah, hapus [PASSED]
    // Cek kesesuaian data yang diinput dengan kolom tabel yang diisi [PASSED]

    public static function do_store(Request $request)
    {
        // Proses validasi
        $request->validate([
            'name' => 'required',
            'position_id' => 'required',
            'photo' => 'file|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Proses input
        try {
            DB::beginTransaction();

            // Proses Input data
            $employee = new Employee;
            $employee->name = $request->name;
            $employee->address = $request->address;
            $employee->phone_number = $request->phone_number;
            $employee->position_id = $request->position_id;
            $employee->photo = "default.png";

            // Handle foto pegawai
            if ($request->hasFile('photo')) {
                if ($request->file('photo')->isValid()) {
                    $employee_photo_path = UploadFilePathHelper::EMPLOYEE_PHOTO_PATH;
                    $employee_photo = $request->file('photo');
                    $employee_photo_name = time() . "_" . $employee_photo->getClientOriginalName();
                    $employee_photo->move($employee_photo_path, $employee_photo_name);
                    $employee->photo = $employee_photo_path . '/' . $employee_photo_name;
                }
            }

            $employee->created_by = UserInfoHelper::employee_id();
            $employee->save();

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
        $request->validate([
            'name' => 'required',
            'photo' => 'file|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Proses update
        try {
            DB::beginTransaction();

            // Proses cari data
            $employee = Employee::find($id);

            // Cek data ada di database
            if ($employee == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");


            // Jika ada, lanjut update data
            $employee->name = $request->name;
            $employee->address = $request->address;
            $employee->phone_number = $request->phone_number;
            $employee->position_id = $request->position_id;

            // Handle foto pegawai
            if ($request->hasFile('photo')) {
                if ($request->file('photo')->isValid()) {
                    $employee_photo_path = UploadFilePathHelper::EMPLOYEE_PHOTO_PATH;
                    $employee_photo = $request->file('photo');
                    $employee_photo_name = time() . "_" . $employee_photo->getClientOriginalName();
                    $employee_photo->move($employee_photo_path, $employee_photo_name);
                    $employee->photo = $employee_photo_path . '/' . $employee_photo_name;
                }
            }

            $employee->updated_by = UserInfoHelper::employee_id();
            $employee->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Ubah Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Ubah Gagal", "Proses ubah data gagal!" . $e);
        }
    }


    public static function do_delete($id)
    {
        try {
            DB::beginTransaction();

            // Proses Input data
            $employee = Employee::find($id);

            // Cek data ada di database
            if ($employee == null) return ResponseHelper::response_error("Proses Hapus Gagal", "Data tidak ditemukan");

            // Jika ada, input data yang hapus
            $employee->deleted_by = UserInfoHelper::employee_id();
            $employee->deleted_at = now();
            $employee->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Hapus Berhasil", "Data telah dihapus");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Hapus Gagal", "Proses hapus data gagal!");
        }
    }

    //update employee via profile page
    public static function do_update_employee($id, Request $request)
    {
        // Proses validasi
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone_number' => 'required'
        ]);

        // Proses update
        try {
            DB::beginTransaction();

            // Proses cari data
            $employee = Employee::find($id);

            // Cek data ada di database
            if ($employee == null) return ResponseHelper::response_error("Proses Ubah Gagal", "Data tidak ditemukan");

            // Jika ada, lanjut update data
            $employee->name = $request->name;
            $employee->address = $request->address;
            $employee->phone_number = $request->phone_number;


            $employee->updated_by = UserInfoHelper::employee_id();
            $employee->save();

            // Setelah input / ubah data lakukan commit agar perubahan tersimpan di database
            DB::commit();
            return ResponseHelper::response_success("Proses Ubah Berhasil", "Data telah disimpan");
        } catch (Exception $e) {
            // Jika terjadi kesalahan waktu input, lakukan aksi rollback sehingga data tidak tersimpan
            DB::rollBack();
            return ResponseHelper::response_error("Proses Ubah Gagal", "Proses input data gagal!");
        }
    }
}
