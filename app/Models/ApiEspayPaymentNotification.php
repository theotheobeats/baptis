<?php

namespace App\Models;

use App\Helpers\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiEspayPaymentNotification extends Model
{
    use SoftDeletes;
    use HasTrackHistory;
    protected $table = "api_espay_payment_notifications";

    protected $fillable = [
        'success_flag',
        'error_message',
        'reconcile_id',
        'order_id',
        'reconcile_datetime',
        'ss_json',
    ];
}
