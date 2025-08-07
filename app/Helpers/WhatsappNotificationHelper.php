<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class WhatsappNotificationHelper
{
    public static $BARANTUM_URL = "https://api.barantum.com/api/v1/";
    public static $COMPANY_UUID = "136dfe0a-31f7-40fe-a437-8a6d73e4d862";
    // public static $TEMPLATE_INVOICE_NOTIFICATION_UUID = "4cb341a1-08b8-46d2-900a-94c6e4a43c02";
    // public static $TEMPLATE_INVOICE_NOTIFICATION_UUID = "93503d1c-7aeb-47c8-a856-55f64f1901ec";
    public static $TEMPLATE_INVOICE_NOTIFICATION_UUID = "8a60ae74-e3c9-4c2e-ace4-e39b12586f06";
    public static $TEMPLATE_PAYMENT_NOTIFICATION_UUID = "7280cfe1-b320-4364-887f-8c05c5a65ed2"; // "761e46c3-2478-4cd9-9e3f-c1f8fb6761a6";
    public static $WA_BOT_UUID = "70be3004-4335-43ef-9529-0414d27726a8";

    public static function get_bot()
    {
        $http_response = Http::get(self::$BARANTUM_URL . "get_list_bot", [
            'company_uuid' => self::$COMPANY_UUID, 
            'channel' => "wa",
            'type_sort' => "desc",
        ])->throw(function ($response, $e) {
            $response_status = $response->status();
            $response_json = $response->json();
        });

        // $server_response_code = $http_request->getStatusCode();
        $server_response = json_decode($http_response->getBody(), true);
        return $server_response;
        // $server_response_json = json_encode($server_response);
        // dd ($server_response);
    }


    public static function send_message($wa_number, $message) 
    {
        $http_response = Http::post(self::$BARANTUM_URL . "send-message", [
            'chats_users_id' => $wa_number, 
            'chats_message_text' => $message,
            'channel' => "wa",
            "company_uuid" => self::$COMPANY_UUID,          
            'type_sort' => "desc",
            "chats_bot_id" => 1,
        ])->throw(function ($response, $e) {
            $response_status = $response->status();
            $response_json = $response->json();
            echo "ERROR";
            dd($response_json);
        });

        // $server_response_code = $http_request->getStatusCode();
        $server_response = json_decode($http_response->getBody(), true);

        // $server_response_json = json_encode($server_response);
        dd ($server_response);
    }

    public static function send_attachment_message($wa_number, $media, $caption)
    {
        $http_response = Http::post(self::$BARANTUM_URL . "send-message", [
            'chats_users_id' => $wa_number, 
            'channel' => "wa",
            "company_uuid" => self::$COMPANY_UUID,          
            "chats_bot_id" => 1,
            "type" => "media",
            "media" => [
                "link" => "https://media.macphun.com/img/uploads/customer/how-to/608/15542038745ca344e267fb80.28757312.jpg?q=85&w=1340",//"https://s3.ap-southeast-1.amazonaws.com/crm.barantum.document/company_1/2022092701140186037074809d56769a2db8edd43e8058.jpg",
                "caption" => $caption
            ]
        ])->throw(function ($response, $e) {
            $response_status = $response->status();
            $response_json = $response->json();
            echo "ERROR";
            dd($response_json);
        });

        // $server_response_code = $http_request->getStatusCode();
        $server_response = json_decode($http_response->getBody(), true);

        // $server_response_json = json_encode($server_response);
        dd ($server_response);
    }


    public static function get_all_template()
    {
        $http_response = Http::post(self::$BARANTUM_URL . "list-template", [
            "company_uuid" => self::$COMPANY_UUID,
        ])->throw(function ($response, $e) {
            $response_status = $response->status();
            $response_json = $response->json();
            echo "ERROR";
            dd($response_json);
        });

        // $server_response_code = $http_request->getStatusCode();
        $server_response = json_decode($http_response->getBody(), true);

        // $server_response_json = json_encode($server_response);
        dd ($server_response);
    }

    public static function send_invoice_notification_message_template_custom($messages)
    {
        /*
            [
                "user_name" => "dewi",
                "number" => "6282177511334",
                "variabel" => [
                    "{{1}}" => "(text)01 Mei 2024",
                    "{{2}}" => "(text)Tengku Kevin Juldianto",
                    "{{3}}" => "(text)Mei",
                    "{{4}}" => "(text)1. SPP : 300,000\\n2. Ekskul Robotik : 100,000\\n3. Ekskul Karate : 100,000",
                    "{{5}}" => "(text)500,000",
                    "{{6}}" => "(text)13984002394",
                ]
            ]
        */

        $http_response = Http::post(self::$BARANTUM_URL . "send-message-template-custom", [
            "company_uuid" => self::$COMPANY_UUID,
            "contacts" => [$messages],
            "template_uuid" => self::$TEMPLATE_INVOICE_NOTIFICATION_UUID,
            "chat_bot_uuid" => self::$WA_BOT_UUID,
        ])->throw(function ($response, $e) {
            $response_status = $response->status();
            $response_json = $response->json();
            // echo "ERROR";
            // dd($response_json);
            return $response_json;
        });

        // $server_response_code = $http_request->getStatusCode();
        $server_response = json_decode($http_response->getBody(), true);
        // dd($server_response);
        return $server_response;

        // $server_response_json = json_encode($server_response);
        // dd ($server_response);
    }


    public static function send_payment_proof_template_custom($messages)
    {
        $http_response = Http::post(self::$BARANTUM_URL . "send-message-template-custom", [
            "company_uuid" => self::$COMPANY_UUID,
            "contacts" => [$messages],
            "template_uuid" => self::$TEMPLATE_PAYMENT_NOTIFICATION_UUID,
            "chat_bot_uuid" => self::$WA_BOT_UUID,
        ])->throw(function ($response, $e) {
            $response_status = $response->status();
            $response_json = $response->json();
            // echo "ERROR";
            // dd($response_json);
            return $response_json;
        });

        // $server_response_code = $http_request->getStatusCode();
        $server_response = json_decode($http_response->getBody(), true);
        return $server_response;
    }

    public static function send_single_notification($id) {
        $whatsapp_notification_result = self::send_invoice_notification_message_template_custom(
            [
                "user_name" => "dewi",
                "number" => "6282177511334",
                "variabel" => [
                    "{{1}}" => "(text)01 Agustus " . date("Y"),
                    "{{2}}" => "(text)Tengku Kevin Juldianto",
                    "{{3}}" => "(text)Agustus " . date("Y"),
                    "{{4}}" => "https://accounting.sekolahbaptispalembang.com/invoice/Abraham.pdf",
                ],
            ]
        );
    }
    
}