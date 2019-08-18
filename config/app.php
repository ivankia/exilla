<?php

return [
    'name' => 'Exilla',

    'version' => app('git.version'),

    'production' => false,

    'providers' => [
        App\Providers\AppServiceProvider::class,
    ],

    'orderbook_params' => [
            'column'       => 'size_price', // default column
            'sortL'        => SORT_DESC,
            'sortR'        => SORT_DESC,
            'headers'      => ['Buy Size', ' Buy Price', '  ', 'Sell Price', 'Sell Size'], // table heads
            'price_limits' => [10400, 10960],
            'min_size'     => 10,
            'limit'        => 75,

//            'display'      => 'console',
            'display'      => 'html',

            'exchange'     => 'bitmex',

            'bitmex' => [
                'host' => 'http://localhost',
                'port' => 4444,

                'pt_orderbook'  => 'orderBookL2?symbol={symbol}&depth={depth}',
                'pt_instrument' => 'instrument?symbol={symbol}',
                'symbol'        => 'XBTUSD',
                'depth'         => 0,

                'api_url'            => 'http://localhost:4444/orderBookL2?symbol=XBTUSD&depth=',
                'api_url_instrument' => 'http://localhost:4444/instrument?symbol=',
            ],

            'schema'       => 'discrete_levels', // 'quoter_average',
            'discrete_levels' => [
                'low'  => 100,
                'mid'  => 200,
                'high' => 300
            ],
    ],
];
