<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Illuminate\Console\Scheduling\Schedule;
use GuzzleHttp\Client as apiClient;
use Predis\Client as redisClient;

class ScanOrderbookL2Bitmex extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'orderbook2l:scan {--exchange=bitmex}';

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

        $client = new apiClient([
            'base_uri' => $params['host'] . ':' . $params['port'],
            'timeout'  => 2.0,
        ]);

        $response = $client->get($params['pt_orderbook']);

        if ($response->getStatusCode() !== 200) {
            $this->error('Error Code ' . $response->getStatusCode());
        }

        // https://github.com/nrk/predis/blob/v1.1/examples/pubsub_consumer.php

        $rows = json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY, 2147483646);

        $redis = new redisClient();
        foreach ($rows as $row) {
            $key = 'bitmex_order_' . $row['id'];
            $redis->hset($key, 'id:size:side:price', $row['id'] . ' ' . $row['size'] . ' ' . $row['side'] . ' ' . $row['[price']);
        }            $key = 'bitmex_order_' . $row['id'];
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
