<?php

namespace Database\Seeders;

use App\Models\AppConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppConfigSeeder extends Seeder
{
    public function run(): void
    {
        $app_configs = [
            [
                "app_name" => "DPS Baptis Palembang",
                "company_name" => "Sekolah Baptis Palembang",
                "company_address" => "Jl. Jend. Sudirman",
                "company_phone" => "0",
                "company_website" => "-",
            ],
        ];

        foreach ($app_configs as $ac) {
            AppConfig::create([
                'app_name' => $ac['app_name'],
                'company_name' => $ac['company_name'],
                'company_address' => $ac['company_address'],
                'company_phone' => $ac['company_phone'],
                'company_website' => $ac['company_website'],
            ]);
        }
    }
}
