<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Device::create([
            "name" => "بصامة الرجال ",
            "is_connected" => false,
            "ip" => "192.168.1.201",
        ]);
        Device::create([
            "name" => "بصامة النساء ",
            "is_connected" => false,
            "ip" => "192.168.1.211",
        ]);
    }
}
