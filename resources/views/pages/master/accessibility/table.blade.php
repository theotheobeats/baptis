
<div class="row" id="access_table">
    <div class="col-12">
        <?php $i = 1; ?>
        <hr>
        <h5>1. Akses Master Data</h5>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th scope="col">No</th>
                    <th style="text-align: left" scope="col">Nama</th>
                    <th scope="col">Aktif</th>
                    <th scope="col">Tambah</th>
                    <th scope="col">Lihat</th>
                    <th scope="col">Ubah</th>
                    <th scope="col">Hapus</th>
                    <th scope="col">Export</th>
                    <th scope="col">Import</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Siswa</td>
                    <td><input type="checkbox" name="student_all" class="check-all check-master check-student" id="check-student_all" value="student_all" onchange="check_all('student')" {{ <?php if ($edit && ($data->student->student_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->student->student_all ?? 0) == 1) echo "check_all('student')"; ?>"}}></td>
                    <td><input type="checkbox" name="student_add" class="check-all check-master check-student" id="check-student_add" value="student_add" {{ <?php if ($edit && ($data->student->student_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="student_view" class="check-all check-master check-student" id="check-student_view" value="student_view" {{ <?php if ($edit && ($data->student->student_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="student_update" class="check-all check-master check-student" id="check-student_update" value="student_update" {{ <?php if ($edit && ($data->student->student_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="student_delete" class="check-all check-master check-student" id="check-student_delete" value="student_delete" {{ <?php if ($edit && ($data->student->student_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="student_export" class="check-all check-master check-student" id="check-student_export" value="student_export" {{ <?php if ($edit && ($data->student->student_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="student_import" class="check-all check-master check-student" id="check-student_import" value="student_import" {{ <?php if ($edit && ($data->student->student_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Kelas</td>
                    <td><input type="checkbox" name="classroom_all" class="check-all check-master check-classroom" id="check-classroom_all" value="classroom_all" onchange="check_all('classroom')" {{ <?php if ($edit && ($data->classroom->classroom_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->classroom->classroom_all ?? 0) == 1) echo "check_all('classroom')"; ?>"}}></td>
                    <td><input type="checkbox" name="classroom_add" class="check-all check-master check-classroom" id="check-classroom_add" value="classroom_add" {{ <?php if ($edit && ($data->classroom->classroom_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="classroom_view" class="check-all check-master check-classroom" id="check-classroom_view" value="classroom_view" {{ <?php if ($edit && ($data->classroom->classroom_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="classroom_update" class="check-all check-master check-classroom" id="check-classroom_update" value="classroom_update" {{ <?php if ($edit && ($data->classroom->classroom_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="classroom_delete" class="check-all check-master check-classroom" id="check-classroom_delete" value="classroom_delete" {{ <?php if ($edit && ($data->classroom->classroom_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="classroom_export" class="check-all check-master check-classroom" id="check-classroom_export" value="classroom_export" {{ <?php if ($edit && ($data->classroom->classroom_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="classroom_import" class="check-all check-master check-classroom" id="check-classroom_import" value="classroom_import" {{ <?php if ($edit && ($data->classroom->classroom_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Iuran</td>
                    <td><input type="checkbox" name="due_all" class="check-all check-master check-due" id="check-due_all" value="due_all" onchange="check_all('due')" {{ <?php if ($edit && ($data->due->due_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->due->due_all ?? 0) == 1) echo "check_all('due')"; ?>"}}></td>
                    <td><input type="checkbox" name="due_add" class="check-all check-master check-due" id="check-due_add" value="due_add" {{ <?php if ($edit && ($data->due->due_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_view" class="check-all check-master check-due" id="check-due_view" value="due_view" {{ <?php if ($edit && ($data->due->due_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_update" class="check-all check-master check-due" id="check-due_update" value="due_update" {{ <?php if ($edit && ($data->due->due_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_delete" class="check-all check-master check-due" id="check-due_delete" value="due_delete" {{ <?php if ($edit && ($data->due->due_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_export" class="check-all check-master check-due" id="check-due_export" value="due_export" {{ <?php if ($edit && ($data->due->due_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_import" class="check-all check-master check-due" id="check-due_import" value="due_import" {{ <?php if ($edit && ($data->due->due_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Tahun Ajaran</td>
                    <td><input type="checkbox" name="school_year_all" class="check-all check-master check-school_year" id="check-school_year_all" value="school_year_all" onchange="check_all('school_year')" {{ <?php if ($edit && ($data->school_year->school_year_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->school_year->school_year_all ?? 0) == 1) echo "check_all('school_year')"; ?>"}}></td>
                    <td><input type="checkbox" name="school_year_add" class="check-all check-master check-school_year" id="check-school_year_add" value="school_year_add" {{ <?php if ($edit && ($data->school_year->school_year_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="school_year_view" class="check-all check-master check-school_year" id="check-school_year_view" value="school_year_view" {{ <?php if ($edit && ($data->school_year->school_year_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="school_year_update" class="check-all check-master check-school_year" id="check-school_year_update" value="school_year_update" {{ <?php if ($edit && ($data->school_year->school_year_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="school_year_delete" class="check-all check-master check-school_year" id="check-school_year_delete" value="school_year_delete" {{ <?php if ($edit && ($data->school_year->school_year_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="school_year_export" class="check-all check-master check-school_year" id="check-school_year_export" value="school_year_export" {{ <?php if ($edit && ($data->school_year->school_year_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="school_year_import" class="check-all check-master check-school_year" id="check-school_year_import" value="school_year_import" {{ <?php if ($edit && ($data->school_year->school_year_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Libur Iuran</td>
                    <td><input type="checkbox" name="due_day_off_all" class="check-all check-master check-due_day_off" id="check-due_day_off_all" value="due_day_off_all" onchange="check_all('due_day_off')" {{ <?php if ($edit && ($data->due_day_off->due_day_off_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->due_day_off->due_day_off_all ?? 0) == 1) echo "check_all('due_day_off')"; ?>"}}></td>
                    <td><input type="checkbox" name="due_day_off_add" class="check-all check-master check-due_day_off" id="check-due_day_off_add" value="due_day_off_add" {{ <?php if ($edit && ($data->due_day_off->due_day_off_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_day_off_view" class="check-all check-master check-due_day_off" id="check-due_day_off_view" value="due_day_off_view" {{ <?php if ($edit && ($data->due_day_off->due_day_off_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_day_off_update" class="check-all check-master check-due_day_off" id="check-due_day_off_update" value="due_day_off_update" {{ <?php if ($edit && ($data->due_day_off->due_day_off_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_day_off_delete" class="check-all check-master check-due_day_off" id="check-due_day_off_delete" value="due_day_off_delete" {{ <?php if ($edit && ($data->due_day_off->due_day_off_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_day_off_export" class="check-all check-master check-due_day_off" id="check-due_day_off_export" value="due_day_off_export" {{ <?php if ($edit && ($data->due_day_off->due_day_off_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_day_off_import" class="check-all check-master check-due_day_off" id="check-due_day_off_import" value="due_day_off_import" {{ <?php if ($edit && ($data->due_day_off->due_day_off_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Pegawai</td>
                    <td><input type="checkbox" name="employee_all" class="check-all check-master check-employee" id="check-employee_all" value="employee_all" onchange="check_all('employee')" {{ <?php if ($edit && ($data->employee->employee_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->employee->employee_all ?? 0) == 1) echo "check_all('employee')"; ?>"}}></td>
                    <td><input type="checkbox" name="employee_add" class="check-all check-master check-employee" id="check-employee_add" value="employee_add" {{ <?php if ($edit && ($data->employee->employee_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="employee_view" class="check-all check-master check-employee" id="check-employee_view" value="employee_view" {{ <?php if ($edit && ($data->employee->employee_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="employee_update" class="check-all check-master check-employee" id="check-employee_update" value="employee_update" {{ <?php if ($edit && ($data->employee->employee_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="employee_delete" class="check-all check-master check-employee" id="check-employee_delete" value="employee_delete" {{ <?php if ($edit && ($data->employee->employee_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="employee_export" class="check-all check-master check-employee" id="check-employee_export" value="employee_export" {{ <?php if ($edit && ($data->employee->employee_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="employee_import" class="check-all check-master check-employee" id="check-employee_import" value="employee_import" {{ <?php if ($edit && ($data->employee->employee_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Jabatan</td>
                    <td><input type="checkbox" name="position_all" class="check-all check-master check-position" id="check-position_all" value="position_all" onchange="check_all('position')" {{ <?php if ($edit && ($data->position->position_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->position->position_all ?? 0) == 1) echo "check_all('position')"; ?>"}}></td>
                    <td><input type="checkbox" name="position_add" class="check-all check-master check-position" id="check-position_add" value="position_add" {{ <?php if ($edit && ($data->position->position_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="position_view" class="check-all check-master check-position" id="check-position_view" value="position_view" {{ <?php if ($edit && ($data->position->position_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="position_update" class="check-all check-master check-position" id="check-position_update" value="position_update" {{ <?php if ($edit && ($data->position->position_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="position_delete" class="check-all check-master check-position" id="check-position_delete" value="position_delete" {{ <?php if ($edit && ($data->position->position_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="position_export" class="check-all check-master check-position" id="check-position_export" value="position_export" {{ <?php if ($edit && ($data->position->position_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="position_import" class="check-all check-master check-position" id="check-position_import" value="position_import" {{ <?php if ($edit && ($data->position->position_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">User</td>
                    <td><input type="checkbox" name="user_all" class="check-all check-master check-user" id="check-user_all" value="user_all" onchange="check_all('user')" {{ <?php if ($edit && ($data->user->user_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->user->user_all ?? 0) == 1) echo "check_all('user')"; ?>"}}></td>
                    <td><input type="checkbox" name="user_add" class="check-all check-master check-user" id="check-user_add" value="user_add" {{ <?php if ($edit && ($data->user->user_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="user_view" class="check-all check-master check-user" id="check-user_view" value="user_view" {{ <?php if ($edit && ($data->user->user_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="user_update" class="check-all check-master check-user" id="check-user_update" value="user_update" {{ <?php if ($edit && ($data->user->user_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="user_delete" class="check-all check-master check-user" id="check-user_delete" value="user_delete" {{ <?php if ($edit && ($data->user->user_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="user_export" class="check-all check-master check-user" id="check-user_export" value="user_export" {{ <?php if ($edit && ($data->user->user_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="user_import" class="check-all check-master check-user" id="check-user_import" value="user_import" {{ <?php if ($edit && ($data->user->user_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Bank</td>
                    <td><input type="checkbox" name="bank_all" class="check-all check-master check-bank" id="check-bank_all" value="bank_all" onchange="check_all('bank')" {{ <?php if ($edit && ($data->bank->bank_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->bank->bank_all ?? 0) == 1) echo "check_all('bank')"; ?>"}}></td>
                    <td><input type="checkbox" name="bank_add" class="check-all check-master check-bank" id="check-bank_add" value="bank_add" {{ <?php if ($edit && ($data->bank->bank_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="bank_view" class="check-all check-master check-bank" id="check-bank_view" value="bank_view" {{ <?php if ($edit && ($data->bank->bank_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="bank_update" class="check-all check-master check-bank" id="check-bank_update" value="bank_update" {{ <?php if ($edit && ($data->bank->bank_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="bank_delete" class="check-all check-master check-bank" id="check-bank_delete" value="bank_delete" {{ <?php if ($edit && ($data->bank->bank_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="bank_export" class="check-all check-master check-bank" id="check-bank_export" value="bank_export" {{ <?php if ($edit && ($data->bank->bank_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="bank_import" class="check-all check-master check-bank" id="check-bank_import" value="bank_import" {{ <?php if ($edit && ($data->bank->bank_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Kelurahan</td>
                    <td><input type="checkbox" name="address_village_all" class="check-all check-master check-address_village" id="check-address_village_all" value="address_village_all" onchange="check_all('address_village')" {{ <?php if ($edit && ($data->address_village->address_village_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->address_village->address_village_all ?? 0) == 1) echo "check_all('address_village')"; ?>"}}></td>
                    <td><input type="checkbox" name="address_village_add" class="check-all check-master check-address_village" id="check-address_village_add" value="address_village_add" {{ <?php if ($edit && ($data->address_village->address_village_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="address_village_view" class="check-all check-master check-address_village" id="check-address_village_view" value="address_village_view" {{ <?php if ($edit && ($data->address_village->address_village_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="address_village_update" class="check-all check-master check-address_village" id="check-address_village_update" value="address_village_update" {{ <?php if ($edit && ($data->address_village->address_village_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="address_village_delete" class="check-all check-master check-address_village" id="check-address_village_delete" value="address_village_delete" {{ <?php if ($edit && ($data->address_village->address_village_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="address_village_export" class="check-all check-master check-address_village" id="check-address_village_export" value="address_village_export" {{ <?php if ($edit && ($data->address_village->address_village_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="address_village_import" class="check-all check-master check-address_village" id="check-address_village_import" value="address_village_import" {{ <?php if ($edit && ($data->address_village->address_village_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Kecamatan</td>
                    <td><input type="checkbox" name="address_district_all" class="check-all check-master check-address_district" id="check-address_district_all" value="address_district_all" onchange="check_all('address_district')" {{ <?php if ($edit && ($data->address_district->address_district_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->address_district->address_district_all ?? 0) == 1) echo "check_all('address_district')"; ?>"}}></td>
                    <td><input type="checkbox" name="address_district_add" class="check-all check-master check-address_district" id="check-address_district_add" value="address_district_add" {{ <?php if ($edit && ($data->address_district->address_district_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="address_district_view" class="check-all check-master check-address_district" id="check-address_district_view" value="address_district_view" {{ <?php if ($edit && ($data->address_district->address_district_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="address_district_update" class="check-all check-master check-address_district" id="check-address_district_update" value="address_district_update" {{ <?php if ($edit && ($data->address_district->address_district_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="address_district_delete" class="check-all check-master check-address_district" id="check-address_district_delete" value="address_district_delete" {{ <?php if ($edit && ($data->address_district->address_district_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="address_district_export" class="check-all check-master check-address_district" id="check-address_district_export" value="address_district_export" {{ <?php if ($edit && ($data->address_district->address_district_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="address_district_import" class="check-all check-master check-address_district" id="check-address_district_import" value="address_district_import" {{ <?php if ($edit && ($data->address_district->address_district_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Hak Akses</td>
                    <td><input type="checkbox" name="accessibility_all" class="check-all check-master check-accessibility" id="check-accessibility_all" value="accessibility_all" onchange="check_all('accessibility')" {{ <?php if ($edit && ($data->accessibility->accessibility_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->accessibility->accessibility_all ?? 0) == 1) echo "check_all('accessibility')"; ?>"}}></td>
                    <td><input type="checkbox" name="accessibility_add" class="check-all check-master check-accessibility" id="check-accessibility_add" value="accessibility_add" {{ <?php if ($edit && ($data->accessibility->accessibility_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="accessibility_view" class="check-all check-master check-accessibility" id="check-accessibility_view" value="accessibility_view" {{ <?php if ($edit && ($data->accessibility->accessibility_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="accessibility_update" class="check-all check-master check-accessibility" id="check-accessibility_update" value="accessibility_update" {{ <?php if ($edit && ($data->accessibility->accessibility_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="accessibility_delete" class="check-all check-master check-accessibility" id="check-accessibility_delete" value="accessibility_delete" {{ <?php if ($edit && ($data->accessibility->accessibility_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="accessibility_export" class="check-all check-master check-accessibility" id="check-accessibility_export" value="accessibility_export" {{ <?php if ($edit && ($data->accessibility->accessibility_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="accessibility_import" class="check-all check-master check-accessibility" id="check-accessibility_import" value="accessibility_import" {{ <?php if ($edit && ($data->accessibility->accessibility_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
            </tbody>
        </table>

        <br>

        <?php $i = 1; ?>
        <hr>
        <h5>2. Akses Manajemen Kelas</h5>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th scope="col">No</th>
                    <th style="text-align: left" scope="col">Nama</th>
                    <th scope="col">Aktif</th>
                    <th scope="col">Tambah</th>
                    <th scope="col">Lihat</th>
                    <th scope="col">Export</th>
                    <th scope="col">Import</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Kenaikan Kelas</td>
                    <td><input type="checkbox" name="class_grade_promotion_all" class="check-all check-master check-class_grade_promotion" id="check-class_grade_promotion_all" value="class_grade_promotion_all" onchange="check_all('class_grade_promotion')" {{ <?php if ($edit && ($data->class_grade_promotion->class_grade_promotion_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->class_grade_promotion->class_grade_promotion_all ?? 0) == 1) echo "check_all('class_grade_promotion')"; ?>"}}></td>
                    <td></td>
                    <td><input type="checkbox" name="class_grade_promotion_view" class="check-all check-master check-class_grade_promotion" id="check-class_grade_promotion_view" value="class_grade_promotion_view" {{ <?php if ($edit && ($data->class_grade_promotion->class_grade_promotion_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="class_grade_promotion_export" class="check-all check-master check-class_grade_promotion" id="check-class_grade_promotion_export" value="class_grade_promotion_export" {{ <?php if ($edit && ($data->class_grade_promotion->class_grade_promotion_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="class_grade_promotion_import" class="check-all check-master check-class_grade_promotion" id="check-class_grade_promotion_import" value="class_grade_promotion_import" {{ <?php if ($edit && ($data->class_grade_promotion->class_grade_promotion_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Pindah Kelas</td>
                    <td><input type="checkbox" name="class_change_all" class="check-all check-master check-class_change" id="check-class_change_all" value="class_change_all" onchange="check_all('class_change')" {{ <?php if ($edit && ($data->class_change->class_change_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->class_change->class_change_all ?? 0) == 1) echo "check_all('class_change')"; ?>"}}></td>
                    <td><input type="checkbox" name="class_change_add" class="check-all check-master check-class_change" id="check-class_change_add" value="class_change_add" {{ <?php if ($edit && ($data->class_change->class_change_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="class_change_view" class="check-all check-master check-class_change" id="check-class_change_view" value="class_change_view" {{ <?php if ($edit && ($data->class_change->class_change_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="class_change_export" class="check-all check-master check-class_change" id="check-class_change_export" value="class_change_export" {{ <?php if ($edit && ($data->class_change->class_change_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="class_change_import" class="check-all check-master check-class_change" id="check-class_change_import" value="class_change_import" {{ <?php if ($edit && ($data->class_change->class_change_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Publish VA Manual</td>
                    <td><input type="checkbox" name="publish_va_manual_all" class="check-all check-master check-publish_va_manual" id="check-publish_va_manual_all" value="publish_va_manual_all" onchange="check_all('publish_va_manual')" {{ <?php if ($edit && ($data->publish_va_manual->publish_va_manual_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->publish_va_manual->publish_va_manual_all ?? 0) == 1) echo "check_all('publish_va_manual')"; ?>"}}></td>
                    <td><input type="checkbox" name="publish_va_manual_add" class="check-all check-master check-publish_va_manual" id="check-publish_va_manual_add" value="publish_va_manual_add" {{ <?php if ($edit && ($data->publish_va_manual->publish_va_manual_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="publish_va_manual_view" class="check-all check-master check-publish_va_manual" id="check-publish_va_manual_view" value="publish_va_manual_view" {{ <?php if ($edit && ($data->publish_va_manual->publish_va_manual_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <br>

        <?php $i = 1; ?>
        <hr>
        <h5>3. Akses Manajemen Data</h5>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th scope="col">No</th>
                    <th style="text-align: left" scope="col">Nama</th>
                    <th scope="col">Aktif</th>
                    <th scope="col">TK</th>
                    <th scope="col">SD</th>
                    <th scope="col">SMP</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Manajemen Data</td>
                    <td><input type="checkbox" name="school_data_all" class="check-all check-master check-school_data" id="check-school_data_all" value="school_data_all" onchange="check_all('school_data')" {{ <?php if ($edit && ($data->school_data->school_data_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->school_data->school_data_all ?? 0) == 1) echo "check_all('school_data')"; ?>"}}></td>
                    <td><input type="checkbox" name="school_data_tk" class="check-all check-master check-school_data" id="check-school_data_tk" value="school_data_tk" {{ <?php if ($edit && ($data->school_data->school_data_tk ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="school_data_sd" class="check-all check-master check-school_data" id="check-school_data_sd" value="school_data_sd" {{ <?php if ($edit && ($data->school_data->school_data_sd ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="school_data_smp" class="check-all check-master check-school_data" id="check-school_data_smp" value="school_data_smp" {{ <?php if ($edit && ($data->school_data->school_data_smp ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
            </tbody>
        </table>

        <br>

        <?php $i = 1; ?>
        <hr>
        <h5>4. Akses Sesuai Peran</h5>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th scope="col">No</th>
                    <th style="text-align: left" scope="col">Nama</th>
                    <th scope="col">Aktif</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Tata Usaha</td>
                    <td><input type="checkbox" name="role_administration" class="check-all check-role_administration" id="check-role_administration" value="role_administration" {{ <?php if ($edit && ($data->role->role_administration ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Kasir</td>
                    <td><input type="checkbox" name="role_cashier" class="check-all check-role_cashier" id="check-role_cashier" value="role_cashier" {{ <?php if ($edit && ($data->role->role_cashier ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
            </tbody>
        </table>

        <br>

        <?php $i = 1; ?>
        <hr>
        <h5>5. Akses Finance</h5>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th scope="col">No</th>
                    <th style="text-align: left" scope="col">Nama</th>
                    <th scope="col">Aktif</th>
                    <th scope="col">Tambah</th>
                    <th scope="col">Lihat</th>
                    <th scope="col">Ubah</th>
                    <th scope="col">Hapus</th>
                    <th scope="col">Export</th>
                    <th scope="col">Import</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">COA</td>
                    <td><input type="checkbox" name="coa_all" class="check-all check-master check-coa" id="check-coa_all" value="coa_all" onchange="check_all('coa')" {{ <?php if ($edit && ($data->coa->coa_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->coa->coa_all ?? 0) == 1) echo "check_all('coa')"; ?>"}}></td>
                    <td><input type="checkbox" name="coa_add" class="check-all check-master check-coa" id="check-coa_add" value="coa_add" {{ <?php if ($edit && ($data->coa->coa_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="coa_view" class="check-all check-master check-coa" id="check-coa_view" value="coa_view" {{ <?php if ($edit && ($data->coa->coa_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="coa_update" class="check-all check-master check-coa" id="check-coa_update" value="coa_update" {{ <?php if ($edit && ($data->coa->coa_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="coa_delete" class="check-all check-master check-coa" id="check-coa_delete" value="coa_delete" {{ <?php if ($edit && ($data->coa->coa_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="coa_export" class="check-all check-master check-coa" id="check-coa_export" value="coa_export" {{ <?php if ($edit && ($data->coa->coa_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="coa_import" class="check-all check-master check-coa" id="check-coa_import" value="coa_import" {{ <?php if ($edit && ($data->coa->coa_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Arus Kas</td>
                    <td><input type="checkbox" name="cashflow_all" class="check-all check-master check-cashflow" id="check-cashflow_all" value="cashflow_all" onchange="check_all('cashflow')" {{ <?php if ($edit && ($data->cashflow->cashflow_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->cashflow->cashflow_all ?? 0) == 1) echo "check_all('cashflow')"; ?>"}}></td>
                    <td><input type="checkbox" name="cashflow_add" class="check-all check-master check-cashflow" id="check-cashflow_add" value="cashflow_add" {{ <?php if ($edit && ($data->cashflow->cashflow_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashflow_view" class="check-all check-master check-cashflow" id="check-cashflow_view" value="cashflow_view" {{ <?php if ($edit && ($data->cashflow->cashflow_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashflow_update" class="check-all check-master check-cashflow" id="check-cashflow_update" value="cashflow_update" {{ <?php if ($edit && ($data->cashflow->cashflow_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashflow_delete" class="check-all check-master check-cashflow" id="check-cashflow_delete" value="cashflow_delete" {{ <?php if ($edit && ($data->cashflow->cashflow_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashflow_export" class="check-all check-master check-cashflow" id="check-cashflow_export" value="cashflow_export" {{ <?php if ($edit && ($data->cashflow->cashflow_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashflow_import" class="check-all check-master check-cashflow" id="check-cashflow_import" value="cashflow_import" {{ <?php if ($edit && ($data->cashflow->cashflow_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
            </tbody>
        </table>

        <br>

        <?php $i = 1; ?>
        <hr>
        <h5>6. Akses Manajemen Iuran</h5>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th scope="col">No</th>
                    <th style="text-align: left" scope="col">Nama</th>
                    <th scope="col">Aktif</th>
                    <th scope="col">Tambah</th>
                    <th scope="col">Lihat</th>
                    <th scope="col">Ubah</th>
                    <th scope="col">Hapus</th>
                    <th scope="col">Export</th>
                    <th scope="col">Import</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Iuran Siswa</td>
                    <td><input type="checkbox" name="due_subscription_all" class="check-all check-master check-due_subscription" id="check-due_subscription_all" value="due_subscription_all" onchange="check_all('due_subscription')" {{ <?php if ($edit && ($data->due_subscription->due_subscription_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->due_subscription->due_subscription_all ?? 0) == 1) echo "check_all('due_subscription')"; ?>"}}></td>
                    <td><input type="checkbox" name="due_subscription_add" class="check-all check-master check-due_subscription" id="check-due_subscription_add" value="due_subscription_add" {{ <?php if ($edit && ($data->due_subscription->due_subscription_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_subscription_view" class="check-all check-master check-due_subscription" id="check-due_subscription_view" value="due_subscription_view" {{ <?php if ($edit && ($data->due_subscription->due_subscription_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_subscription_update" class="check-all check-master check-due_subscription" id="check-due_subscription_update" value="due_subscription_update" {{ <?php if ($edit && ($data->due_subscription->due_subscription_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_subscription_delete" class="check-all check-master check-due_subscription" id="check-due_subscription_delete" value="due_subscription_delete" {{ <?php if ($edit && ($data->due_subscription->due_subscription_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_subscription_export" class="check-all check-master check-due_subscription" id="check-due_subscription_export" value="due_subscription_export" {{ <?php if ($edit && ($data->due_subscription->due_subscription_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_subscription_import" class="check-all check-master check-due_subscription" id="check-due_subscription_import" value="due_subscription_import" {{ <?php if ($edit && ($data->due_subscription->due_subscription_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
            </tbody>
        </table>

        <br>

        <?php $i = 1; ?>
        <hr>
        <h5>7. Akses Transaksi</h5>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th scope="col">No</th>
                    <th style="text-align: left" scope="col">Nama</th>
                    <th scope="col">Aktif</th>
                    <th scope="col">Tambah</th>
                    <th scope="col">Lihat</th>
                    <th scope="col">Ubah</th>
                    <th scope="col">Hapus</th>
                    <th scope="col">Export</th>
                    <th scope="col">Import</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Tagihan</td>
                    <td><input type="checkbox" name="bill_issuance_all" class="check-all check-master check-bill_issuance" id="check-bill_issuance_all" value="bill_issuance_all" onchange="check_all('bill_issuance')" {{ <?php if ($edit && ($data->bill_issuance->bill_issuance_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->bill_issuance->bill_issuance_all ?? 0) == 1) echo "check_all('bill_issuance')"; ?>"}}></td>
                    <td><input type="checkbox" name="bill_issuance_add" class="check-all check-master check-bill_issuance" id="check-bill_issuance_add" value="bill_issuance_add" {{ <?php if ($edit && ($data->bill_issuance->bill_issuance_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="bill_issuance_view" class="check-all check-master check-bill_issuance" id="check-bill_issuance_view" value="bill_issuance_view" {{ <?php if ($edit && ($data->bill_issuance->bill_issuance_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="bill_issuance_update" class="check-all check-master check-bill_issuance" id="check-bill_issuance_update" value="bill_issuance_update" {{ <?php if ($edit && ($data->bill_issuance->bill_issuance_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="bill_issuance_delete" class="check-all check-master check-bill_issuance" id="check-bill_issuance_delete" value="bill_issuance_delete" {{ <?php if ($edit && ($data->bill_issuance->bill_issuance_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="bill_issuance_export" class="check-all check-master check-bill_issuance" id="check-bill_issuance_export" value="bill_issuance_export" {{ <?php if ($edit && ($data->bill_issuance->bill_issuance_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="bill_issuance_import" class="check-all check-master check-bill_issuance" id="check-bill_issuance_import" value="bill_issuance_import" {{ <?php if ($edit && ($data->bill_issuance->bill_issuance_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Bayar Tagihan</td>
                    <td><input type="checkbox" name="due_payment_all" class="check-all check-master check-due_payment" id="check-due_payment_all" value="due_payment_all" onchange="check_all('due_payment')" {{ <?php if ($edit && ($data->due_payment->due_payment_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->due_payment->due_payment_all ?? 0) == 1) echo "check_all('due_payment')"; ?>"}}></td>
                    <td><input type="checkbox" name="due_payment_add" class="check-all check-master check-due_payment" id="check-due_payment_add" value="due_payment_add" {{ <?php if ($edit && ($data->due_payment->due_payment_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_payment_view" class="check-all check-master check-due_payment" id="check-due_payment_view" value="due_payment_view" {{ <?php if ($edit && ($data->due_payment->due_payment_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_payment_update" class="check-all check-master check-due_payment" id="check-due_payment_update" value="due_payment_update" {{ <?php if ($edit && ($data->due_payment->due_payment_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_payment_delete" class="check-all check-master check-due_payment" id="check-due_payment_delete" value="due_payment_delete" {{ <?php if ($edit && ($data->due_payment->due_payment_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_payment_export" class="check-all check-master check-due_payment" id="check-due_payment_export" value="due_payment_export" {{ <?php if ($edit && ($data->due_payment->due_payment_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="due_payment_import" class="check-all check-master check-due_payment" id="check-due_payment_import" value="due_payment_import" {{ <?php if ($edit && ($data->due_payment->due_payment_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Refund Tagihan</td>
                    <td><input type="checkbox" name="payment_refund_all" class="check-all check-master check-payment_refund" id="check-payment_refund_all" value="payment_refund_all" onchange="check_all('payment_refund')" {{ <?php if ($edit && ($data->payment_refund->payment_refund_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->payment_refund->payment_refund_all ?? 0) == 1) echo "check_all('payment_refund')"; ?>"}}></td>
                    <td><input type="checkbox" name="payment_refund_add" class="check-all check-master check-payment_refund" id="check-payment_refund_add" value="payment_refund_add" {{ <?php if ($edit && ($data->payment_refund->payment_refund_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="payment_refund_view" class="check-all check-master check-payment_refund" id="check-payment_refund_view" value="payment_refund_view" {{ <?php if ($edit && ($data->payment_refund->payment_refund_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="payment_refund_update" class="check-all check-master check-payment_refund" id="check-payment_refund_update" value="payment_refund_update" {{ <?php if ($edit && ($data->payment_refund->payment_refund_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="payment_refund_delete" class="check-all check-master check-payment_refund" id="check-payment_refund_delete" value="payment_refund_delete" {{ <?php if ($edit && ($data->payment_refund->payment_refund_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="payment_refund_export" class="check-all check-master check-payment_refund" id="check-payment_refund_export" value="payment_refund_export" {{ <?php if ($edit && ($data->payment_refund->payment_refund_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="payment_refund_import" class="check-all check-master check-payment_refund" id="check-payment_refund_import" value="payment_refund_import" {{ <?php if ($edit && ($data->payment_refund->payment_refund_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Penjualan Kasir</td>
                    <td><input type="checkbox" name="cashier_all" class="check-all check-master check-cashier" id="check-cashier_all" value="cashier_all" onchange="check_all('cashier')" {{ <?php if ($edit && ($data->cashier->cashier_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->cashier->cashier_all ?? 0) == 1) echo "check_all('cashier')"; ?>"}}></td>
                    <td><input type="checkbox" name="cashier_add" class="check-all check-master check-cashier" id="check-cashier_add" value="cashier_add" {{ <?php if ($edit && ($data->cashier->cashier_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_view" class="check-all check-master check-cashier" id="check-cashier_view" value="cashier_view" {{ <?php if ($edit && ($data->cashier->cashier_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_update" class="check-all check-master check-cashier" id="check-cashier_update" value="cashier_update" {{ <?php if ($edit && ($data->cashier->cashier_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_delete" class="check-all check-master check-cashier" id="check-cashier_delete" value="cashier_delete" {{ <?php if ($edit && ($data->cashier->cashier_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_export" class="check-all check-master check-cashier" id="check-cashier_export" value="cashier_export" {{ <?php if ($edit && ($data->cashier->cashier_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_import" class="check-all check-master check-cashier" id="check-cashier_import" value="cashier_import" {{ <?php if ($edit && ($data->cashier->cashier_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Pembayaran</td>
                    <td><input type="checkbox" name="cashier_payment_all" class="check-all check-master check-cashier_payment" id="check-cashier_payment_all" value="cashier_payment_all" onchange="check_all('cashier_payment')" {{ <?php if ($edit && ($data->cashier_payment->cashier_payment_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->cashier_payment->cashier_payment_all ?? 0) == 1) echo "check_all('cashier_payment')"; ?>"}}></td>
                    <td><input type="checkbox" name="cashier_payment_add" class="check-all check-master check-cashier_payment" id="check-cashier_payment_add" value="cashier_payment_add" {{ <?php if ($edit && ($data->cashier_payment->cashier_payment_add ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_payment_view" class="check-all check-master check-cashier_payment" id="check-cashier_payment_view" value="cashier_payment_view" {{ <?php if ($edit && ($data->cashier_payment->cashier_payment_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_payment_update" class="check-all check-master check-cashier_payment" id="check-cashier_payment_update" value="cashier_payment_update" {{ <?php if ($edit && ($data->cashier_payment->cashier_payment_update ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_payment_delete" class="check-all check-master check-cashier_payment" id="check-cashier_payment_delete" value="cashier_payment_delete" {{ <?php if ($edit && ($data->cashier_payment->cashier_payment_delete ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_payment_export" class="check-all check-master check-cashier_payment" id="check-cashier_payment_export" value="cashier_payment_export" {{ <?php if ($edit && ($data->cashier_payment->cashier_payment_export ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_payment_import" class="check-all check-master check-cashier_payment" id="check-cashier_payment_import" value="cashier_payment_import" {{ <?php if ($edit && ($data->cashier_payment->cashier_payment_import ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
            </tbody>
        </table>

        <br>

        <?php $i = 1; ?>
        <hr>
        <h5>8. Akses Laporan</h5>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th scope="col">No</th>
                    <th style="text-align: left" scope="col">Nama</th>
                    <th scope="col">Aktif</th>
                    <th scope="col">Lihat</th>
                    <th scope="col">Export</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Laporan Pembayaran</td>
                    <td><input type="checkbox" name="payment_report_all" class="check-all check-master check-payment_report" id="check-payment_report_all" value="payment_report_all" onchange="check_all('payment_report')" {{ <?php if ($edit && ($data->payment_report->payment_report_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->payment_report->payment_report_all ?? 0) == 1) echo "check_all('payment_report')"; ?>"}}></td>
                    <td><input type="checkbox" name="payment_report_view" class="check-all check-master check-payment_report" id="check-payment_report_view" value="payment_report_view" {{ <?php if ($edit && ($data->payment_report->payment_report_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="payment_report_export" class="check-all check-master check-payment_report" id="check-payment_report_export" value="payment_report_export" {{ <?php if ($edit && ($data->payment_report->payment_report_export ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Laporan Neraca</td>
                    <td><input type="checkbox" name="balance_sheet_report_all" class="check-all check-master check-balance_sheet_report" id="check-balance_sheet_report_all" value="balance_sheet_report_all" onchange="check_all('balance_sheet_report')" {{ <?php if ($edit && ($data->balance_sheet_report->balance_sheet_report_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->balance_sheet_report->balance_sheet_report_all ?? 0) == 1) echo "check_all('balance_sheet_report')"; ?>"}}></td>
                    <td><input type="checkbox" name="balance_sheet_report_view" class="check-all check-master check-balance_sheet_report" id="check-balance_sheet_report_view" value="balance_sheet_report_view" {{ <?php if ($edit && ($data->balance_sheet_report->balance_sheet_report_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="balance_sheet_report_export" class="check-all check-master check-balance_sheet_report" id="check-balance_sheet_report_export" value="balance_sheet_report_export" {{ <?php if ($edit && ($data->balance_sheet_report->balance_sheet_report_export ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Laporan Arus Kas</td>
                    <td><input type="checkbox" name="cashflow_report_all" class="check-all check-master check-cashflow_report" id="check-cashflow_report_all" value="cashflow_report_all" onchange="check_all('cashflow_report')" {{ <?php if ($edit && ($data->cashflow_report->cashflow_report_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->cashflow_report->cashflow_report_all ?? 0) == 1) echo "check_all('cashflow_report')"; ?>"}}></td>
                    <td><input type="checkbox" name="cashflow_report_view" class="check-all check-master check-cashflow_report" id="check-cashflow_report_view" value="cashflow_report_view" {{ <?php if ($edit && ($data->cashflow_report->cashflow_report_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashflow_report_export" class="check-all check-master check-cashflow_report" id="check-cashflow_report_export" value="cashflow_report_export" {{ <?php if ($edit && ($data->cashflow_report->cashflow_report_export ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Laporan Pengeluaran</td>
                    <td><input type="checkbox" name="expense_report_all" class="check-all check-master check-expense_report" id="check-expense_report_all" value="expense_report_all" onchange="check_all('expense_report')" {{ <?php if ($edit && ($data->expense_report->expense_report_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->expense_report->expense_report_all ?? 0) == 1) echo "check_all('expense_report')"; ?>"}}></td>
                    <td><input type="checkbox" name="expense_report_view" class="check-all check-master check-expense_report" id="check-expense_report_view" value="expense_report_view" {{ <?php if ($edit && ($data->expense_report->expense_report_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="expense_report_export" class="check-all check-master check-expense_report" id="check-expense_report_export" value="expense_report_export" {{ <?php if ($edit && ($data->expense_report->expense_report_export ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Laporan Laba Rugi</td>
                    <td><input type="checkbox" name="profit_report_all" class="check-all check-master check-profit_report" id="check-profit_report_all" value="profit_report_all" onchange="check_all('profit_report')" {{ <?php if ($edit && ($data->profit_report->profit_report_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->profit_report->profit_report_all ?? 0) == 1) echo "check_all('profit_report')"; ?>"}}></td>
                    <td><input type="checkbox" name="profit_report_view" class="check-all check-master check-profit_report" id="check-profit_report_view" value="profit_report_view" {{ <?php if ($edit && ($data->profit_report->profit_report_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="profit_report_export" class="check-all check-master check-profit_report" id="check-profit_report_export" value="profit_report_export" {{ <?php if ($edit && ($data->profit_report->profit_report_export ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Laporan Harian Kasir</td>
                    <td><input type="checkbox" name="cashier_report_all" class="check-all check-master check-cashier_report" id="check-cashier_report_all" value="cashier_report_all" onchange="check_all('cashier_report')" {{ <?php if ($edit && ($data->cashier_report->cashier_report_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->cashier_report->cashier_report_all ?? 0) == 1) echo "check_all('cashier_report')"; ?>"}}></td>
                    <td><input type="checkbox" name="cashier_report_view" class="check-all check-master check-cashier_report" id="check-cashier_report_view" value="cashier_report_view" {{ <?php if ($edit && ($data->cashier_report->cashier_report_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="cashier_report_export" class="check-all check-master check-cashier_report" id="check-cashier_report_export" value="cashier_report_export" {{ <?php if ($edit && ($data->cashier_report->cashier_report_export ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Laporan Bank Harian</td>
                    <td><input type="checkbox" name="daily_bank_report_all" class="check-all check-master check-daily_bank_report" id="check-daily_bank_report_all" value="daily_bank_report_all" onchange="check_all('cashier_report')" {{ <?php if ($edit && ($data->daily_bank_report->daily_bank_report_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->daily_bank_report->daily_bank_report_all ?? 0) == 1) echo "check_all('daily_bank_report')"; ?>"}}></td>
                    <td><input type="checkbox" name="daily_bank_report_view" class="check-all check-master check-daily_bank_report" id="check-daily_bank_report_view" value="daily_bank_report_view" {{ <?php if ($edit && ($data->daily_bank_report->daily_bank_report_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="daily_bank_report_export" class="check-all check-master check-daily_bank_report" id="check-daily_bank_report_export" value="daily_bank_report_export" {{ <?php if ($edit && ($data->daily_bank_report->daily_bank_report_export ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Laporan Siswa Belum Bayar</td>
                    <td><input type="checkbox" name="student_not_paid_report_all" class="check-all check-master check-student_not_paid_report" id="check-student_not_paid_report_all" value="student_not_paid_report_all" onchange="check_all('student_not_paid_report')" {{ <?php if ($edit && ($data->student_not_paid_report->student_not_paid_report_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->student_not_paid_report->student_not_paid_report_all ?? 0) == 1) echo "check_all('student_not_paid_report')"; ?>"}}></td>
                    <td><input type="checkbox" name="student_not_paid_report_view" class="check-all check-master check-student_not_paid_report" id="check-student_not_paid_report_view" value="student_not_paid_report_view" {{ <?php if ($edit && ($data->student_not_paid_report->student_not_paid_report_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="student_not_paid_report_export" class="check-all check-master check-student_not_paid_report" id="check-student_not_paid_report_export" value="student_not_paid_report_export" {{ <?php if ($edit && ($data->student_not_paid_report->student_not_paid_report_export ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Laporan Pembayaran Siswa Detail</td>
                    <td><input type="checkbox" name="student_paid_detail_report_all" class="check-all check-master check-student_paid_detail_report" id="check-student_paid_detail_report_all" value="student_paid_detail_report_all" onchange="check_all('student_paid_detail_report')" {{ <?php if ($edit && ($data->student_paid_detail_report->student_paid_detail_report_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->student_paid_detail_report->student_paid_detail_report_all ?? 0) == 1) echo "check_all('student_paid_detail_report')"; ?>"}}></td>
                    <td><input type="checkbox" name="student_paid_detail_report_view" class="check-all check-master check-student_paid_detail_report" id="check-student_paid_detail_report_view" value="student_paid_detail_report_view" {{ <?php if ($edit && ($data->student_paid_detail_report->student_paid_detail_report_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="student_paid_detail_report_export" class="check-all check-master check-student_paid_detail_report" id="check-student_paid_detail_report_export" value="student_paid_detail_report_export" {{ <?php if ($edit && ($data->student_paid_detail_report->student_paid_detail_report_export ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
                <tr class="text-center">
                    <td>{{ $i++ }}</td>
                    <td align="left">Laporan Siswa Lebih Bayar</td>
                    <td><input type="checkbox" name="student_over_paid_report_all" class="check-all check-master check-student_over_paid_report" id="check-student_over_paid_report_all" value="student_over_paid_report_all" onchange="check_all('student_over_paid_report')" {{ <?php if ($edit && ($data->student_over_paid_report->student_over_paid_report_all ?? 0) == 1) echo 'checked'; ?> onchange="<?php if ($edit && ($data->student_over_paid_report->student_over_paid_report_all ?? 0) == 1) echo "check_all('student_over_paid_report')"; ?>"}}></td>
                    <td><input type="checkbox" name="student_over_paid_report_view" class="check-all check-master check-student_over_paid_report" id="check-student_over_paid_report_view" value="student_over_paid_report_view" {{ <?php if ($edit && ($data->student_over_paid_report->student_over_paid_report_view ?? 0) == 1) echo 'checked' ?> }}></td>
                    <td><input type="checkbox" name="student_over_paid_report_export" class="check-all check-master check-student_over_paid_report" id="check-student_over_paid_report_export" value="student_over_paid_report_export" {{ <?php if ($edit && ($data->student_over_paid_report->student_over_paid_report_export ?? 0) == 1) echo 'checked' ?> }}></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
