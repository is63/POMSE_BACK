<?php

return [

    'default' => env('BROADCAST_DRIVER', 'null'),

    'connections' => [

        'pusher' => [
            'guards' => ['api'], 
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'encrypted' => false, // Importante para usar HTTPS
                'useTLS' => true, // Asegúrate de que esté habilitado para usar TLS
                'host' => 'api-' . $_ENV['PUSHER_APP_CLUSTER'] . '.pusher.com', // Asegúrate de que apunte al host correcto
                'port' => 443,
                'scheme' => 'https',
                'curl_options' => [
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ],
            ],

            'log' => [
                'driver' => 'log',
            ],

            'null' => [
                'driver' => 'null',
            ],

        ],
    ],
];
