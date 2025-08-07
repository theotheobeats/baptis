<?php

use App\Helpers\UploadFilePathHelper;
use App\Models\Sales;
use App\Models\StatusProject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

######################################################################
## ENCRYPT AND DECRYPT
######################################################################
## ENCRIPT 2
function enc2($v1 = null, $v2 = null)
{
    if (empty($v1)) Redirect('');
    if (empty($v2)) Redirect('');
    $enc = $v1 . '(cendy)' . $v2;

    $id = str_replace("/", "+gigit+", Crypt::encryptString($enc));
    if (!empty($id) && $id != null && $id != '') return $id;
    else Redirect('');
}

## DECRYPT 2
function dec2($v = null)
{
    if (empty($v)) Redirect('');
    $enc = str_replace("+gigit+", "/", Crypt::decryptString($v));

    $enc = explode("(cendy)", $enc);
    $enc1 = (!empty($enc[0])) ? $enc[0] : '';
    $enc2 = (!empty($enc[1])) ? $enc[1] : '';

    return array($enc1, $enc2);
}

## ENCRIPT
function enc($v = null)
{
    if (empty($v)) Redirect('');
    $id = str_replace("/", "+gigit+", Crypt::encryptString($v));
    if (!empty($id) && $id != null && $id != '') return $id;
    else Redirect('');
}

## DECRYPT 2
function dec($v = null)
{
    if (empty($v)) Redirect('');
    $id =  Crypt::decryptString(str_replace("+gigit+", "/", $v));
    if (!empty($id) && $id != null && $id != '') return $id;
    else Redirect('');
}



######################################################################
## FORMAT
######################################################################
function numberf($data = null)
{
    if (empty($data)) return '0';
    else return number_format($data, 2, ".", ",");
}

function timef($data = null)
{
    if (empty($data)) return '-';
    else return date("H:i", strtotime($data));
}

function timestampf($data = null)
{
    if (empty($data)) return '-';
    else return date("d M Y H:i:s", strtotime($data));
}

function tbtimestampf($data = null)
{
    if (empty($data)) return '-';
    else return date("d M Y", strtotime($data)) . '</br>' . date("H:i:s", strtotime($data));
}

function datef($data = null)
{
    if (empty($data)) return '-';
    else return date("d M Y", strtotime($data));
}

function yearf($data = null)
{
    if (empty($data)) return '-';
    else return date("Y", strtotime($data));
}

function monthf($data = null)
{
    if (empty($data)) return '-';
    else return date("m", strtotime($data));
}

function date_month_f($data = null)
{
    if (empty($data)) return '-';
    else return date("M Y", strtotime($data));
}

function date_pickerf($data = null)
{
    if (empty($data)) return '-';
    return date("m/d/Y", strtotime($data));
}

function date2_pickerf($data = null)
{
    if (empty($data)) return '-';
    return date("dd/mm/yyyy", strtotime($data));
}

function datetime_pickerf($data = null)
{
    if (empty($data)) return '-';
    return date("m/d/Y H:i", strtotime($data));
}

function oracle_datef($date = null)
{
    if (empty($date)) return '-';

    list($day, $month, $year) = explode('-', $date);

    if (substr($month, 0, 1) == 0) $month = substr($month, 1, 1);
    $months = ['', 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

    return $day . '-' . $months[$month] . '-' . $year;
}


function indonesian_datef($date)
{
    $month = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    $date = explode('-', $date);
    return $date[2] . ' ' . $month[(int)$date[1]] . ' ' . $date[0];
}

function list_hour_24()
{
    $hour = array(
        "00:00", "01:00", "02:00",
        "03:00", "04:00", "05:00",
        "06:00", "07:00", "08:00",
        "09:00", "10:00", "11:00",
        "12:00", "13:00", "14:00",
        "15:00", "16:00", "17:00",
        "18:00", "19:00", "20:00",
        "21:00", "22:00", "23:00",
    );

    return $hour;
}

function sql_datef($data = null)
{
    if (empty($data)) return '-';
    return date("Y-m-d", strtotime($data));
}

function sql_timestampf($data = null)
{
    if (empty($data)) return '-';
    return date("Y-m-d H:i:s", strtotime($data));
}

function sql_hoursf($data = null)
{
    if (empty($data)) return '-';
    return date("H:i:s", strtotime($data));
}

function date_difff($old_date = null, $new_date = null)
{
    $diff = date_diff(date_create(sql_datef($old_date)), date_create(sql_datef($new_date)));
    return $diff->days;
}

function is_date_bigger($date = null)
{
    if (!empty($date)) {
        $date = date('Y-m-d', strtotime($date));
        $date_now = date('Y-m-d', strtotime(DATE('Y-m-d')));
        $is_bigger_then = FALSE;

        if ($date >= $date_now) $is_bigger_then = TRUE;

        return $is_bigger_then;
    } else return false;
}


function check_valid_date($date = null, $diff_date = null)
{
    if (!empty($date)) {
        $date = date('Y-m-d', strtotime($date));
        $diff_date = date('Y-m-d', strtotime($diff_date));
        $is_bigger_then = FALSE;

        if ($date > $diff_date) $is_bigger_then = TRUE;

        return $is_bigger_then;
    } else return false;
}


function low($data = null)
{
    return strtolower($data);
}

function upp($data = null)
{
    return strtoupper($data);
}


######################################################################
## REGEX FORMAT
######################################################################
function text_to_slug($text = '')
{
    $text = trim($text);
    if (empty($text)) return '';

    $text = preg_replace("/[^a-zA-Z0-9\-\s]+/", "", $text);
    $text = strtolower(trim($text));
    $text = str_replace(' ', '-', $text);
    $text = $text_ori = preg_replace('/\-{2,}/', '-', $text);

    return $text;
}

function text_to_form($text = '')
{
    $text = trim($text);
    if (empty($text)) return '';

    $text = preg_replace("/[^a-zA-Z0-9\-\s]+/", "", $text);
    $text = strtolower(trim($text));
    $text = str_replace(' ', '_', $text);
    $text = $text_ori = preg_replace('/\-{2,}/', '_', $text);

    return $text;
}


function get_regex_price($text = '')
{
    $regex = "/#([0-9]+)/";
    $hasil = [];
    preg_match_all($regex, $text, $hasil);
    return $hasil;
}


// UBAH FORMAT
function number_mysql($text = '')
{
    $is_negative = false;

    if (substr($text, 0, 1) === '-') {
        $is_negative = true;
        $text = substr($text, 1);
    }

    $text = preg_replace("/[^0-9. ]/", '', $text == null ? 0 : $text);
    return ($is_negative) ? '-' . $text : $text;
}

function percentage($value = null, $total_value = null)
{
    if (empty($value) || empty($total_value)) return 0;
    else return round((($value / $total_value) * 100), 2);
}


######################################################################
## AUTH
######################################################################
function has_access($access_type, $sub_access)
{
    // $accessibility = json_decode(Auth::user()->accessibility, true);

    // if (isset($accessibility[$access_type][$sub_access])) {
    //     if ($accessibility[$access_type][$sub_access] == "1") {
    //         return true;
    //     }
    // }

    // return false;
    return true;
}

function admin_has_access($accessibility, $access_type, $sub_access)
{
    // $accessibility = json_decode($accessibility, true);

    // if (isset($accessibility[$access_type][$sub_access])) {
    //     if ($accessibility[$access_type][$sub_access] == "1") {
    //         return true;
    //     }
    // }

    // return false;
}

######################################################################
## PROJECT
######################################################################
function alphabet($data = null)
{
    if (empty($data)) return '-';
    $alphabet = range('A', 'Z');
    return $alphabet[$data - 1];
}

function conv_dec($data = null)
{
    return preg_replace("/[^0-9. ]/", '', $data == null ? 0 : $data);
}

function response_json($data = null)
{
    if (empty($data)) {
        return '-';
    } else {

        if (array_key_exists('m', $data)) {
            $data['s'] .= ' ' . $data['m'];
        }

        if (array_key_exists('p', $data)) {
            return response()->json(['s' => $data['s'], 'st' => $data['st'], 'p' => $data['p']]);
        } else {
            return response()->json(['s' => $data['s'], 'st' => $data['st']]);
        }
    }
}








function auction_status($status_id = null, $type = null)
{
    $status_id = $status_id;
    if ($type == 'color') {
        if ($status_id == StatusProject::AUCT_CREATED) return '<span class="badge bg-primary">Pengajuan lelang dibuat</span>';
        else if ($status_id == StatusProject::AUCT_RAB_CREATED) return '<span class="badge bg-info">RAB dibuat</span>';
        else if ($status_id == StatusProject::AUCT_RAB_CANCELED) return '<span class="badge bg-warning">RAB dibatalkan</span>';
        else if ($status_id == StatusProject::AUCT_RAB_SUBMITTED) return '<span class="badge bg-secondary">RAB disetujui</span>';
        else if ($status_id == StatusProject::AUCT_WINNED) return '<span class="badge bg-success">Pengajuan lelang dimenangkan</span>';
        else if ($status_id == StatusProject::AUCT_LOSED) return '<span class="badge bg-warning text-dark">Pengajuan lelang kalah</span>';
        else if ($status_id == StatusProject::AUCT_ERC_CREATED) return '<span class="badge bg-info">ERC dibuat</span>';
        else if ($status_id == StatusProject::AUCT_ERC_CANCELED) return '<span class="badge bg-warning">ERC dibatalkan</span>';
        else if ($status_id == StatusProject::AUCT_ERC_SUBMITTED) return '<span class="badge bg-secondary">ERC disetujui</span>';
        else if ($status_id == StatusProject::AUCT_CANCELED) return '<span class="badge bg-danger">Pengajuan lelang dibatalkan</span>';
        else return '';
    } else if ($type == 'text') {
        if ($status_id == StatusProject::AUCT_CREATED) return 'Pengajuan lelang dibuat';
        else if ($status_id == StatusProject::AUCT_RAB_CREATED) return 'RAB dibuat';
        else if ($status_id == StatusProject::AUCT_RAB_CANCELED) return 'RAB dibatalkan';
        else if ($status_id == StatusProject::AUCT_RAB_SUBMITTED) return 'RAB disetujui';
        else if ($status_id == StatusProject::AUCT_WINNED) return 'Pengajuan lelang dimenangkan';
        else if ($status_id == StatusProject::AUCT_LOSED) return 'Pengajuan lelang kalah';
        else if ($status_id == StatusProject::AUCT_ERC_CREATED) return 'ERC dibuat';
        else if ($status_id == StatusProject::AUCT_ERC_CANCELED) return 'ERC dibatalkan';
        else if ($status_id == StatusProject::AUCT_ERC_SUBMITTED) return 'ERC disetujui';
        else if ($status_id == StatusProject::AUCT_CANCELED) return 'Pengajuan lelang dibatalkan';
        else return '';
    } else return '';
}

function auction_item_status($data = null)
{

    if ($data->position === StatusProject::AUCT_CREATED) {
        $color = "primary";
        $icon = '<i class="fas fa-plus-circle text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::AUCT_RAB_CREATED) {
        $color = "info";
        $icon = '<i class="fas fa-clipboard text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::AUCT_RAB_CANCELED) {
        $color = "warning";
        $icon = '<i class="fas fa-sync text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::AUCT_RAB_SUBMITTED) {
        $color = "secondary";
        $icon = '<i class="fas fa-share text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::AUCT_WINNED) {
        $color = "success";
        $icon = '<i class="fas fa-gavel text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::AUCT_LOSED) {
        $color = "warning";
        $icon = '<i class="fas fa-flag text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::AUCT_ERC_CREATED) {
        $color = "info";
        $icon = '<i class="fas fa-clipboard text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::AUCT_ERC_CANCELED) {
        $color = "warning";
        $icon = '<i class="fas fa-sync text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::AUCT_ERC_SUBMITTED) {
        $color = "secondary";
        $icon = '<i class="fas fa-share text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::AUCT_CANCELED) {
        $color = "danger";
        $icon = '<i class="fas fa-times-circle text-'.$color.'"></i>';
    } 

    return '
        <div class="timeline-item">
            <div class="timeline-pin">
                <i class="marker marker-circle text-'.$color.'"></i>
            </div>
            <div class="timeline-content">
                <div class="rich-list-item">
                    <div class="rich-list-prepend">
                        <div class="avatar">
                            <div class="avatar-display">
                                '.$icon.'
                            </div>
                        </div>
                    </div>
                    <div class="rich-list-content">
                        <h4 class="rich-list-title">' . $data->name . '</h4>
                        <p class="rich-list-paragraph">
                            ' . $data->description . ' <br>
                            Dilakukan oleh ' . $data->created_name . '
                        </p>
                        <span class="rich-list-subtitle">' . timestampf($data->created_at) . '</span>
                    </div>
                </div>
            </div>
        </div>
    ';
}

function auction_item_attachment($data = null, $is_edit = false)
{
    $url = UploadFilePathHelper::LOCATION_PATH('read', UploadFilePathHelper::TYPE_AUCTION) . $data->file;

    $delete_action_view = '';
    if($is_edit){
        $delete_action = "'".enc($data->id)."'";
        $delete_action_view = '<a onclick="delete_attachment('.$delete_action.')"><span><i class="fas fa-trash text-danger"></i></span></a>';
    }
    
    return '
        <li><a target="_blank" class="text-primary" href="'.$url.'">'. $data->name .'</a> 
            '. $delete_action_view .'
        </li>
    ';
}






function budget_plan_status($status_id = null, $type = null)
{
    $status_id = $status_id;
    if ($type == 'color') {
        if ($status_id == StatusProject::RAB_CREATED) return '<span class="badge bg-primary">RAB dibuat</span>';
        else if ($status_id == StatusProject::RAB_SUBMITTED) return '<span class="badge bg-info">RAB diajukan</span>';
        else if ($status_id == StatusProject::RAB_RETURNED) return '<span class="badge bg-warning text-dark">RAB dikembalikan</span>';
        else if ($status_id == StatusProject::RAB_APPROVED) return '<span class="badge bg-success">RAB disetujui</span>';
        else if ($status_id == StatusProject::RAB_CANCELED) return '<span class="badge bg-danger">RAB dibatalkan</span>';
        else return '';
    } else if ($type == 'text') {
        if ($status_id == StatusProject::RAB_CREATED) return 'RAB dibuat';
        else if ($status_id == StatusProject::RAB_SUBMITTED) return 'RAB diajukan';
        else if ($status_id == StatusProject::RAB_RETURNED) return 'RAB dikembalikan';
        else if ($status_id == StatusProject::RAB_APPROVED) return 'RAB disetujui';
        else if ($status_id == StatusProject::RAB_CANCELED) return 'RAB dibatalkan';
        else return '';
    } else return '';
}

function budget_plan_item_status($data = null)
{

    if ($data->position === StatusProject::RAB_CREATED) {
        $color = "primary";
        $icon = '<i class="fas fa-plus-circle text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::RAB_SUBMITTED) {
        $color = "info";
        $icon = '<i class="fas fa-clipboard text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::RAB_RETURNED) {
        $color = "warning";
        $icon = '<i class="fas fa-sync text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::RAB_APPROVED) {
        $color = "success";
        $icon = '<i class="fas fa-check-circle text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::RAB_CANCELED) {
        $color = "danger";
        $icon = '<i class="fas fa-times-circle text-'.$color.'"></i>';
    } 

    return '
        <div class="timeline-item">
            <div class="timeline-pin">
                <i class="marker marker-circle text-'.$color.'"></i>
            </div>
            <div class="timeline-content">
                <div class="rich-list-item">
                    <div class="rich-list-prepend">
                        <div class="avatar">
                            <div class="avatar-display">
                                '.$icon.'
                            </div>
                        </div>
                    </div>
                    <div class="rich-list-content">
                        <h4 class="rich-list-title">' . $data->name . '</h4>
                        <p class="rich-list-paragraph">
                            ' . $data->description . ' <br>
                            Dilakukan oleh ' . $data->created_name . '
                        </p>
                        <span class="rich-list-subtitle">' . timestampf($data->created_at) . '</span>
                    </div>
                </div>
            </div>
        </div>
    ';
}

function budget_plan_item_attachment($data = null, $is_edit = false)
{
    $url = UploadFilePathHelper::LOCATION_PATH('read', UploadFilePathHelper::TYPE_BUDGET_PLAN) . $data->file;

    $delete_action_view = '';
    if($is_edit){
        $delete_action = "'".enc($data->id)."'";
        $delete_action_view = '<a onclick="delete_attachment('.$delete_action.')"><span><i class="fas fa-trash text-danger"></i></span></a>';
    }
    
    return '
        <li><a target="_blank" class="text-primary" href="'.$url.'">'. $data->name .'</a> 
            '. $delete_action_view .'
        </li>
    ';
}






function estimate_real_cost_status($status_id = null, $type = null)
{
    $status_id = $status_id;
    if ($type == 'color') {
        if ($status_id == StatusProject::ERC_CREATED) return '<span class="badge bg-primary">ERC dibuat</span>';
        else if ($status_id == StatusProject::ERC_SUBMITTED) return '<span class="badge bg-info">Menunggu Pemeriksaan ERC</span>';
        else if ($status_id == StatusProject::ERC_RETURNED) return '<span class="badge bg-warning text-dark">ERC dikembalikan</span>';
        else if ($status_id == StatusProject::ERC_APPROVAL) return '<span class="badge bg-info">Menunggu Persetujuan ERC</span>';
        else if ($status_id == StatusProject::ERC_RETURNED_APPROVAL) return '<span class="badge bg-warning">Pengembalian Pemeriksaan ERC</span>';
        else if ($status_id == StatusProject::ERC_APPROVED) return '<span class="badge bg-success">ERC disetujui</span>';
        else if ($status_id == StatusProject::ERC_CANCELED) return '<span class="badge bg-danger">ERC dibatalkan</span>';
        else return '';
    } else if ($type == 'text') {
        if ($status_id == StatusProject::ERC_CREATED) return 'ERC dibuat';
        else if ($status_id == StatusProject::ERC_SUBMITTED) return 'Menunggu Pemeriksaan ERC';
        else if ($status_id == StatusProject::ERC_RETURNED) return 'ERC dikembalikan';
        else if ($status_id == StatusProject::ERC_APPROVAL) return 'Menunggu Persetujuan ERC';
        else if ($status_id == StatusProject::ERC_RETURNED_APPROVAL) return 'Pengembalian Pemeriksaan ERC';
        else if ($status_id == StatusProject::ERC_APPROVED) return 'ERC disetujui';
        else if ($status_id == StatusProject::ERC_CANCELED) return 'ERC dibatalkan';
        else return '';
    } else return '';
}

function estimate_real_cost_item_status($data = null)
{

    if ($data->position === StatusProject::ERC_CREATED) {
        $color = "primary";
        $icon = '<i class="fas fa-plus-circle text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::ERC_SUBMITTED) {
        $color = "info";
        $icon = '<i class="fas fa-clipboard text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::ERC_RETURNED) {
        $color = "warning";
        $icon = '<i class="fas fa-sync text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::ERC_APPROVAL) {
        $color = "info";
        $icon = '<i class="fas fa-clipboard-check text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::ERC_RETURNED_APPROVAL) {
        $color = "warning";
        $icon = '<i class="fas fa-sync text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::ERC_APPROVED) {
        $color = "success";
        $icon = '<i class="fas fa-check-circle text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::ERC_CANCELED) {
        $color = "danger";
        $icon = '<i class="fas fa-times-circle text-'.$color.'"></i>';
    } 

    return '
        <div class="timeline-item">
            <div class="timeline-pin">
                <i class="marker marker-circle text-'.$color.'"></i>
            </div>
            <div class="timeline-content">
                <div class="rich-list-item">
                    <div class="rich-list-prepend">
                        <div class="avatar">
                            <div class="avatar-display">
                                '.$icon.'
                            </div>
                        </div>
                    </div>
                    <div class="rich-list-content">
                        <h4 class="rich-list-title">' . $data->name . '</h4>
                        <p class="rich-list-paragraph">
                            ' . $data->description . ' <br>
                            Dilakukan oleh ' . $data->created_name . '
                        </p>
                        <span class="rich-list-subtitle">' . timestampf($data->created_at) . '</span>
                    </div>
                </div>
            </div>
        </div>
    ';
}

function estimate_real_cost_item_attachment($data = null, $is_edit = false)
{
    $url = UploadFilePathHelper::LOCATION_PATH('read', UploadFilePathHelper::TYPE_ESTIMATE_REAL_COST) . $data->file;

    $delete_action_view = '';
    if($is_edit){
        $delete_action = "'".enc($data->id)."'";
        $delete_action_view = '<a onclick="delete_attachment('.$delete_action.')"><span><i class="fas fa-trash text-danger"></i></span></a>';
    }
    
    return '
        <li><a target="_blank" class="text-primary" href="'.$url.'">'. $data->name .'</a> 
            '. $delete_action_view .'
        </li>
    ';
}






function project_status($status_id = null, $type = null)
{
    $status_id = $status_id;
    if ($type == 'color') {
        if ($status_id == StatusProject::PRJ_CREATED) return '<span class="badge bg-primary">Proyek dibuat</span>';
        else if ($status_id == StatusProject::PRJ_CLOSED) return '<span class="badge bg-success">Proyek diclosed</span>';
        else if ($status_id == StatusProject::PRJ_CANCELED) return '<span class="badge bg-danger">Proyek dibatalkan</span>';
        else return '';
    } else if ($type == 'text') {
        if ($status_id == StatusProject::PRJ_CREATED) return 'Proyek dibuat';
        else if ($status_id == StatusProject::PRJ_CLOSED) return 'Proyek diclosed';
        else if ($status_id == StatusProject::PRJ_CANCELED) return 'Proyek dibatalkan';
        else return '';
    } else return '';
}

function project_item_status($data = null)
{

    if ($data->position === StatusProject::PRJ_CREATED) {
        $color = "primary";
        $icon = '<i class="fas fa-plus-circle text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::PRJ_CLOSED) {
        $color = "success";
        $icon = '<i class="fas fa-check-circle text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::PRJ_CANCELED) {
        $color = "danger";
        $icon = '<i class="fas fa-times-circle text-'.$color.'"></i>';
    } 

    return '
        <div class="timeline-item">
            <div class="timeline-pin">
                <i class="marker marker-circle text-'.$color.'"></i>
            </div>
            <div class="timeline-content">
                <div class="rich-list-item">
                    <div class="rich-list-prepend">
                        <div class="avatar">
                            <div class="avatar-display">
                                '.$icon.'
                            </div>
                        </div>
                    </div>
                    <div class="rich-list-content">
                        <h4 class="rich-list-title">' . $data->name . '</h4>
                        <p class="rich-list-paragraph">
                            ' . $data->description . ' <br>
                            Dilakukan oleh ' . $data->created_name . '
                        </p>
                        <span class="rich-list-subtitle">' . timestampf($data->created_at) . '</span>
                    </div>
                </div>
            </div>
        </div>
    ';
}






function project_delivery_status($status_id = null, $type = null)
{
    $status_id = $status_id;
    if ($type == 'color') {
        if ($status_id == StatusProject::PRJ_DLV_CREATED) return '<span class="badge bg-primary">Pengiriman dibuat</span>';
        else if ($status_id == StatusProject::PRJ_DLV_CLOSED) return '<span class="badge bg-success">Pengiriman diclosed</span>';
        else if ($status_id == StatusProject::PRJ_DLV_CANCELED) return '<span class="badge bg-danger">Pengiriman dibatalkan</span>';
        else return '';
    } else if ($type == 'text') {
        if ($status_id == StatusProject::PRJ_DLV_CREATED) return 'Pengiriman dibuat';
        else if ($status_id == StatusProject::PRJ_DLV_CLOSED) return 'Pengiriman diclosed';
        else if ($status_id == StatusProject::PRJ_DLV_CANCELED) return 'Pengiriman dibatalkan';
        else return '';
    } else return '';
}

function project_delivery_item_status($data = null)
{

    if ($data->position === StatusProject::PRJ_CREATED) {
        $color = "primary";
        $icon = '<i class="fas fa-plus-circle text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::PRJ_CLOSED) {
        $color = "success";
        $icon = '<i class="fas fa-check-circle text-'.$color.'"></i>';
    } else if ($data->position === StatusProject::PRJ_CANCELED) {
        $color = "danger";
        $icon = '<i class="fas fa-times-circle text-'.$color.'"></i>';
    } 

    return '
        <div class="timeline-item">
            <div class="timeline-pin">
                <i class="marker marker-circle text-'.$color.'"></i>
            </div>
            <div class="timeline-content">
                <div class="rich-list-item">
                    <div class="rich-list-prepend">
                        <div class="avatar">
                            <div class="avatar-display">
                                '.$icon.'
                            </div>
                        </div>
                    </div>
                    <div class="rich-list-content">
                        <h4 class="rich-list-title">' . $data->name . '</h4>
                        <p class="rich-list-paragraph">
                            ' . $data->description . ' <br>
                            Dilakukan oleh ' . $data->created_name . '
                        </p>
                        <span class="rich-list-subtitle">' . timestampf($data->created_at) . '</span>
                    </div>
                </div>
            </div>
        </div>
    ';
}