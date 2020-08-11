<?php
return [
    'default' => 'wechat',

    'drivers' => [
        'wechat' => [
            'appId' => 'your appid',
            'appSecret' => 'your app secret',

            'deliveryId' => '',
            'shopId' => '',
            'deliveryAppSecret' => 'delivery app secret',
        ],
        'dada' => [
            'appId' => 'your appid',
            'appSecret' => 'your app secret',
            'sourceId' => '',
            'isOnline' => true, // 是否测试
        ],
        'sf' => [
            'dev_id' => '',
            'dev_key' => '',
            'shop_id' => '',
        ],
        'ss' => [
            'client_id' => '',
            'secret' => '',
            'shop_id' => '',
            'debug' => false,// 是否开启调试
        ],
    ],
];
