<?php

namespace Database\Seeders;

use App\Models\Iso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IsoSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Iso::create([
            'kode' => '9001:2015'
        ]);
    }
}
