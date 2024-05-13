<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i < 4; $i++){
            Unit::create([
                'kode' => "RT" . $i,
                'nama_unit' => "Rotogravure Unit" . " " . $i,
            ]);
        }
        Unit::create([
            'kode' => "KTS",
            'nama_unit' => "Unit Kertas",
        ]);
        Unit::create([
            'kode' => "TNTA",
            'nama_unit' => "Unit TINTA",
        ]);
    }
}
