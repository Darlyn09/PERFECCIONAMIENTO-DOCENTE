<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDates extends Command
{
    protected $signature = 'fix:dates';
    protected $description = 'Fix invalid dates in participante table';

    public function handle()
    {
        $this->info('Fixing invalid dates...');
        try {
            DB::statement("UPDATE participante SET fecha_registro = '2000-01-01 00:00:00' WHERE CAST(fecha_registro AS CHAR) LIKE '0000-00-%'");
            $this->info('Dates fixed.');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
