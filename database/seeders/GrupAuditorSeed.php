<?php

namespace Database\Seeders;

use App\Models\GrupAuditor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GrupAuditorSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GrupAuditor::create([
            'nama_grup' => '20231A1'
        ]);
        GrupAuditor::create([
            'nama_grup' => '20232A2'
        ]);
    }
}
