<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ScanOrderbook extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'orderbook:scan {--exchange=all}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Scan Orderbook';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Scan Orderbook');
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
    }
}
