<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UnitSeed::class);
        $this->call(Dept_N_SubDeptSeed::class);
        $this->call(UserSeed::class);
        $this->call(GrupAuditorSeed::class);
        $this->call(AuditorAuditeeSeed::class);
        $this->call(IsoSeed::class);
        $this->call(ClausulSeed::class);
        $this->call(AuditSeed::class);
    }
}
