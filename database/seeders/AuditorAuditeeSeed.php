<?php

namespace Database\Seeders;

use App\Models\Auditee;
use App\Models\Auditor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuditorAuditeeSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        Auditor::create([
            'user_id' => 1,
            'grup_auditor_id' => 1,
            'keanggotaan' => 'ketua',
        ]);

        Auditor::create([
            'user_id' => 2,
            'grup_auditor_id' => 1,
            'keanggotaan' => 'anggota',
        ]);

        Auditor::create([
            'user_id' => 3,
            'grup_auditor_id' => 2,
            'keanggotaan' => 'anggota',
        ]);

        Auditor::create([
            'user_id' => 4,
            'grup_auditor_id' => 2,
            'keanggotaan' => 'anggota',
        ]);

        Auditor::create([
            'user_id' => 5,
            'grup_auditor_id' => 1,
            'keanggotaan' => 'anggota',
        ]);

        Auditor::create([
            'user_id' => 6,
            'grup_auditor_id' => 1,
            'keanggotaan' => 'anggota',
        ]);
        Auditor::create([
            'user_id' => 8,
            'grup_auditor_id' => 2,
            'keanggotaan' => 'ketua',
        ]);
        Auditor::create([
            'user_id' => 9,
            'grup_auditor_id' => null,
            'keanggotaan' => null,
        ]);
        Auditor::create([
            'user_id' => 10,
            'grup_auditor_id' => null,
            'keanggotaan' => null,
        ]);
        Auditor::create([
            'user_id' => 11,
            'grup_auditor_id' => null,
            'keanggotaan' => null,
        ]);
        Auditor::create([
            'user_id' => 12,
            'grup_auditor_id' => null,
            'keanggotaan' => null,
        ]);
        Auditor::create([
            'user_id' => 13,
            'grup_auditor_id' => null,
            'keanggotaan' => null,
        ]);


        Auditee::create([
            'user_id' => 1,
        ]);

        Auditee::create([
            'user_id' => 2,
        ]);

        Auditee::create([
            'user_id' => 3,
        ]);

        Auditee::create([
            'user_id' => 4,
        ]);

        Auditee::create([
            'user_id' => 5,
        ]);

        Auditee::create([
            'user_id' => 6,
        ]);

        Auditee::create([
            'user_id' => 7,
        ]);
        Auditee::create([
            'user_id' => 8,
        ]);
        Auditee::create([
            'user_id' => 9,
        ]);
        Auditee::create([
            'user_id' => 10,
        ]);
        Auditee::create([
            'user_id' => 11,
        ]);
        Auditee::create([
            'user_id' => 12,
        ]);
        Auditee::create([
            'user_id' => 13,
        ]);
    }
}
