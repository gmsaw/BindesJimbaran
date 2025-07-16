<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CaptureKartuScreenshot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:capture-kartu-screenshot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        exec('node ' . base_path('screenshoot_puppeteer/screenshot.js'), $output, $result);
        $this->info($result === 0 ? 'Berhasil.' : 'Gagal.');
    }
}
