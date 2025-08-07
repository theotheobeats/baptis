<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use RuntimeException;

class CryptoHelper
{
    public static function decrypt($data)
    {
        try {
            $result = Crypt::decrypt($data);
            return (object)["success" => true, "id" => $result];
        } catch (RuntimeException $e) {
            return (object)["success" => true, "error_response" => response()->json(["title" => "Proses Gagal!", "message" => "Data tidak valid", "type" => "error"], 406)];
        }
    }
}
