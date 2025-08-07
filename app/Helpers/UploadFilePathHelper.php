<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class UploadFilePathHelper
{
    const WEBSITE_USER = 'http://localhost:8000';

    public const EMPLOYEE_PHOTO_PATH = "uploads/employee/photo";
    public const BRANCH_LOGO_PATH = "uploads/branch/logo";
    public const PRODUCT_PHOTO_PATH = "uploads/product/photo";
    public const MAINTENANCE_PHOTO_PATH = "uploads/maintenance/photo";
    public const FINANCE_CASH_FLOW_PHOTO_HANDOVER_PATH = "uploads/finance_cash_flow/photo";


    public const TYPE_PAYMENT_REQUEST = 'payment_request';
    public const TYPE_FIXED_ASSET = 'fixed_asset';
    public const TYPE_FIXED_ASSET_DISPOSITION = 'fixed_asset_disposition';
    public const TYPE_CUSTOMER_DEBT_LIMIT = 'customer_debt_limit';
    public const TYPE_CUSTOMER_DEPOSIT = 'customer_deposit';
    public const TYPE_SUPPLIER_DEPOSIT = 'supplier_deposit';
    //PROJECT
    public const TYPE_AUCTION = "auction";
    public const TYPE_BUDGET_PLAN = "budget_plan";
    public const TYPE_ESTIMATE_REAL_COST = "estimate_real_cost";

    public static function BASE_URL(){
        return asset('');
    }

    public static function LOCATION_PATH($type, $tag){
        $path = 'attachments/' . $tag;

        if($type == "save"){
            return $path;
        }
        else if($type == "read"){
            return self::BASE_URL() . $path . '/';
        }
    }
}
