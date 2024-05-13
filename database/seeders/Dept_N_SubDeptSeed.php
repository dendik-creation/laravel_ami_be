<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\SubDepartemen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Dept_N_SubDeptSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Departemen::create([
            'kode' => 'DSG',
            'ekstensi' => "12-A-01",
            'unit_id' => 1,
            'nama_departemen' => "DESIGN"
        ]);
        Departemen::create([
            'kode' => "CS",
            'ekstensi' => "12-A-02",
            'unit_id' => 1,
            'nama_departemen' => "CS"
        ]);
        Departemen::create([
            'kode' => "SKT",
            'ekstensi' => "12-B-01",
            'unit_id' => 1,
            'nama_departemen' => "SEKERTARIAT"
        ]);
        Departemen::create([
            'kode' => "IT",
            'ekstensi' => "12-C-03",
            'unit_id' => 3,
            'nama_departemen' => "IT"
        ]);

        SubDepartemen::create([
            'departemen_id' => 4,
            'nama_sub_departemen' => 'Web Developer'
        ]);

        SubDepartemen::create([
            'departemen_id' => 4,
            'nama_sub_departemen' => 'DevOps Engineer'
        ]);

        SubDepartemen::create([
            'departemen_id' => 4,
            'nama_sub_departemen' => 'Desktop Developer'
        ]);

    }
}
