<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $today = date('Y-m-d');
            DB::table('data_pendaftar_perawatan')
                ->where('status', 'antri')
                ->where('tgl_periksa', '<', $today)
                ->update([
                    'status' => 'batal',
                ]);
            DB::table('conf_antrean_rawat_jalan')
                ->where('status', 'antri')
                ->where('tanggal', '<', $today)
                ->update([
                    'status' => 'selesai',
                ]);
        })->dailyAt('05:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
