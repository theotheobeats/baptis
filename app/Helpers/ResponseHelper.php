<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ResponseHelper
{
    public static function status($type, $title, $message, $code = 200)
    {
        return response()->json([
            "response" => [
                'type'      => $type,
                'title'     => $title,
                'message'   => $message
            ]
        ], $code);
    }

    public static function response_success($title, $text)
    {
        return ["code" => 200, "status" => "success", "message" => "Berhasil", "client_response" => [
            "title" => $title,
            "message" => $text,
            "type" => "success"
        ]];
    }

    public static function response_success_with_data($title, $text, $data)
    {
        return ["code" => 200, "status" => "success", "message" => "Berhasil", "client_response" => [
            "title" => $title,
            "message" => $text,
            "type" => "success",
            "data" => $data
        ]];
    }

    public static function response_error($title, $text)
    {
        return ["code" => 406, "error" => "success", "message" => "Gagal", "client_response" => [
            "title" => $title,
            "message" => $text,
            "type" => "error"
        ]];
    }

    public static function create_response($title, $text, $code = 200)
    {
        switch ($code) {
            case 200:
                $status = "success";
                $message = "Berhasil";
                break;
            case 403:
                $status = "error";
                $message = "Gagal";
                break;
            case 406:
                $status = "error";
                $message = "Gagal";
                break;
            case 500:
                $status = "error";
                $message = "Gagal";
                break;
            default:
                $status = "error";
                $message = "Gagal";
                break;
        }
        return ["code" => $code, "status" => $status, "message" => $message, "client_response" => [
            "title" => $title,
            "message" => $text,
            "type" => $status
        ]];
    }

    public static function create_direct_response($title, $text, $code = 200)
    {
        switch ($code) {
            case 200:
                $status = "success";
                $message = "Berhasil";
                break;
            case 403:
                $status = "error";
                $message = "Gagal";
                break;
            case 406:
                $status = "error";
                $message = "Gagal";
                break;
            case 500:
                $status = "error";
                $message = "Gagal";
                break;
            default:
                $status = "error";
                $message = "Gagal";
                break;
        }

        return response()->json([
            "title" => $title,
            "message" => $text,
            "type" => $status
        ], $code);
    }

    // response helper untuk transaksi
    public static function response_success_transaction($title, $text, $id)
    {
        return ["code" => 200, "status" => "success", "message" => "Berhasil", "client_response" => [
            "title" => $title,
            "message" => $text,
            "type" => "success",
            "id" => $id
        ]];
    }
}
