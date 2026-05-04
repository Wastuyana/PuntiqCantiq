<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::create([
            'key' => 'kapasitas_produksi',
            'value' => '300',
            'description' => 'Total kapasitas produksi per batch'
        ]);

        \App\Models\Setting::create([
            'key' => 't_interval',
            'value' => '7',
            'description' => 'Target interval waktu antar produksi (hari)'
        ]);
    }
}
