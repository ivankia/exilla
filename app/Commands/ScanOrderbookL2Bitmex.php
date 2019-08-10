<?php

namespace App\Commands;

use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class ScanOrderbookL2Bitmex extends ScanOrderbook
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'orderbook:scan {--exchange=bitmex}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Scan bitmex OrderbookL2 updates';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = config('app.orderbook_params')['bitmex'];

        $params['pt_orderbook'] = str_replace('{symbol}', $params['symbol'], $params['pt_orderbook']);
        $params['pt_orderbook'] = str_replace('{depth}', $params['depth'], $params['pt_orderbook']);

        if ($this->option('exchange')) {
            $params['exchange'] = $this->option('exchange');
        }

        $client = new Client([
            'base_uri' => $params['host'] . ':' . $params['port'],
            'timeout'  => 2.0,
        ]);

        $response = $client->get($params['pt_orderbook']);

        if ($response->getStatusCode() !== 200) {
            $this->error('Error Code ' . $response->getStatusCode());
        }


        $rows = json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY, 2147483646);

        foreach ($rows as $row) {
            Redis::set('bitmex_order_' . $row[0]['id'], $row[0]); // {"symbol": "XBTUSD", "id": 8798816300, "side": "Buy", "size": 23458, "price": 11837}
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        $schedule->command(static::class)->everyMinute();
    }
}
