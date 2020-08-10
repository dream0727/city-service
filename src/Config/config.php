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
    ],
];
