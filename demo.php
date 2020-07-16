<?php

use CityService\CityService;

require "./vendor/autoload.php";

class demo
{
    public function getCityService()
    {
        $cityService = new CityService([
            'wechat' => [
                'appId' => 'wx2b156895eab82eec',
                'appSecret' => 'e24fd870f6da5cd786adde3f8e81c3c5',
            ],
            'cityService' => [
                'deliveryId' => 'TEST',
                'shopId' => 'test_shop_id',
                'appSecret' => 'test_app_secrect',
            ],
        ], 'wechat');

        return $cityService;
    }

    public function preAddOrder()
    {
        $cityService = $this->getCityService();
        $data = [
            "cargo" => [
                "cargo_first_class" => "美食夜宵",
                "cargo_second_class" => "零食小吃",
                "goods_detail" => [
                    "goods" => [
                        [
                            "good_count" => 1,
                            "good_name" => "水果",
                            "good_price" => 10,
                            "good_unit" => "元",
                        ],
                        [
                            "good_count" => 2,
                            "good_name" => "蔬菜",
                            "good_price" => 20,
                            "good_unit" => "元",
                        ],
                    ],
                ],
                "goods_height" => 1,
                "goods_length" => 3,
                "goods_value" => 5,
                "goods_weight" => 1,
                "goods_width" => 2,
            ],
            "openid" => "oN3X_0FmYXxmqfddBIceL6rpZS_o",
            "order_info" => [
                "delivery_service_code" => "",
                "expected_delivery_time" => 0,
                "is_direct_delivery" => 0,
                "is_finish_code_needed" => 1,
                "is_insured" => 0,
                "is_pickup_code_needed" => 1,
                "note" => "test_note",
                "order_time" => 1555220757,
                "order_type" => 0,
                "poi_seq" => "1111",
                "tips" => 0,
            ],
            "receiver" => [
                "address" => "xxx地铁站",
                "address_detail" => "2号楼202",
                "city" => "北京市",
                "coordinate_type" => 0,
                "lat" => 40.1529600000,
                "lng" => 116.5060300000,
                "name" => "老王",
                "phone" => "18512345678",
            ],
            "sender" => [
                "address" => "xx大厦",
                "address_detail" => "1号楼101",
                "city" => "北京市",
                "coordinate_type" => 0,
                "lat" => 40.4486120000,
                "lng" => 116.3830750000,
                "name" => "刘一",
                "phone" => "13712345678",
            ],
            "shop" => [
                "goods_count" => 2,
                "goods_name" => "宝贝",
                "img_url" => "https=>//mmbiz.qpic.cn/mmbiz_png/xxxxxxxxx/0?wx_fmt=png",
                "wxa_path" => "/page/index/index",
            ],
            "shop_no" => "12345678",
            "sub_biz_id" => "sub_biz_id_1",
            "shop_order_id" => "SFTC_001",
            "delivery_token" => "xxxxxxxx",
        ];

        $res = $cityService->action()->preAddOrder($data);

        return $res;
    }

    public function addOrder()
    {
        $cityService = $this->getCityService();
        $data = [
            "cargo" => [
                "cargo_first_class" => "美食夜宵",
                "cargo_second_class" => "零食小吃",
                "goods_detail" => [
                    "goods" => [
                        [
                            "good_count" => 1,
                            "good_name" => "水果",
                            "good_price" => 10,
                            "good_unit" => "元",
                        ],
                        [
                            "good_count" => 2,
                            "good_name" => "蔬菜",
                            "good_price" => 20,
                            "good_unit" => "元",
                        ],
                    ],
                ],
                "goods_height" => 1,
                "goods_length" => 3,
                "goods_value" => 5,
                "goods_weight" => 1,
                "goods_width" => 2,
            ],
            "openid" => "oN3X_0FmYXxmqfddBIceL6rpZS_o",
            "order_info" => [
                "delivery_service_code" => "",
                "expected_delivery_time" => 0,
                "is_direct_delivery" => 0,
                "is_finish_code_needed" => 1,
                "is_insured" => 0,
                "is_pickup_code_needed" => 1,
                "note" => "test_note",
                "order_time" => 1555220757,
                "order_type" => 0,
                "poi_seq" => "1111",
                "tips" => 0,
            ],
            "receiver" => [
                "address" => "xxx地铁站",
                "address_detail" => "2号楼202",
                "city" => "北京市",
                "coordinate_type" => 0,
                "lat" => 40.1529600000,
                "lng" => 116.5060300000,
                "name" => "老王",
                "phone" => "18512345678",
            ],
            "sender" => [
                "address" => "xx大厦",
                "address_detail" => "1号楼101",
                "city" => "北京市",
                "coordinate_type" => 0,
                "lat" => 40.4486120000,
                "lng" => 116.3830750000,
                "name" => "刘一",
                "phone" => "13712345678",
            ],
            "shop" => [
                "goods_count" => 2,
                "goods_name" => "宝贝",
                "img_url" => "https=>//mmbiz.qpic.cn/mmbiz_png/xxxxxxxxx/0?wx_fmt=png",
                "wxa_path" => "/page/index/index",
            ],
            "shop_no" => "12345678",
            "sub_biz_id" => "sub_biz_id_1",
            "shop_order_id" => "SFTC_001",
            "delivery_token" => "xxxxxxxx",
        ];

        $res = $cityService->action()->addOrder($data);

        return $res;
    }

    public function reOrder()
    {
        $cityService = $this->getCityService();
        $data = [
            "cargo" => [
                "cargo_first_class" => "美食夜宵",
                "cargo_second_class" => "零食小吃",
                "goods_detail" => [
                    "goods" => [
                        [
                            "good_count" => 1,
                            "good_name" => "水果",
                            "good_price" => 10,
                            "good_unit" => "元",
                        ],
                        [
                            "good_count" => 2,
                            "good_name" => "蔬菜",
                            "good_price" => 20,
                            "good_unit" => "元",
                        ],
                    ],
                ],
                "goods_height" => 1,
                "goods_length" => 3,
                "goods_value" => 5,
                "goods_weight" => 1,
                "goods_width" => 2,
            ],
            "openid" => "oN3X_0FmYXxmqfddBIceL6rpZS_o",
            "order_info" => [
                "delivery_service_code" => "",
                "expected_delivery_time" => 0,
                "is_direct_delivery" => 0,
                "is_finish_code_needed" => 1,
                "is_insured" => 0,
                "is_pickup_code_needed" => 1,
                "note" => "test_note",
                "order_time" => 1555220757,
                "order_type" => 0,
                "poi_seq" => "1111",
                "tips" => 0,
            ],
            "receiver" => [
                "address" => "xxx地铁站",
                "address_detail" => "2号楼202",
                "city" => "北京市",
                "coordinate_type" => 0,
                "lat" => 40.1529600000,
                "lng" => 116.5060300000,
                "name" => "老王",
                "phone" => "18512345678",
            ],
            "sender" => [
                "address" => "xx大厦",
                "address_detail" => "1号楼101",
                "city" => "北京市",
                "coordinate_type" => 0,
                "lat" => 40.4486120000,
                "lng" => 116.3830750000,
                "name" => "刘一",
                "phone" => "13712345678",
            ],
            "shop" => [
                "goods_count" => 2,
                "goods_name" => "宝贝",
                "img_url" => "https=>//mmbiz.qpic.cn/mmbiz_png/xxxxxxxxx/0?wx_fmt=png",
                "wxa_path" => "/page/index/index",
            ],
            "shop_no" => "12345678",
            "sub_biz_id" => "sub_biz_id_1",
            "shop_order_id" => "SFTC_001",
            "delivery_token" => "xxxxxxxx",
        ];

        $res = $cityService->action()->reOrder($data);

        return $res;
    }

    public function addTip()
    {
        $cityService = $this->getCityService();
        $data = [
            "shop_order_id" => "SFTC_001",
            "waybill_id" => "SFTC_001_waybillid",
            "tips" => 4,
            "remark" => "gogogo",
            "shop_no" => "12345678",
            "openid" => "oN3X_0FmYXxmqfddBIceL6rpZS_o",
        ];

        $res = $cityService->action()->addTip($data);

        return $res;
    }

    public function preCancelOrder()
    {
        $cityService = $this->getCityService();
        $data = [
            "shop_order_id" => "SFTC_001",
            "waybill_id" => "SFTC_001_waybillid",
            "cancel_reason_id" => 1,
            "cancel_reason" => "",
            "shop_no" => "12345678",
        ];

        $res = $cityService->action()->preCancelOrder($data);

        return $res;
    }

    public function cancelOrder()
    {
        $cityService = $this->getCityService();
        $data = [
            "shop_order_id" => "SFTC_001",
            "waybill_id" => "SFTC_001_waybillid",
            "cancel_reason_id" => 1,
            "cancel_reason" => "",
            "shop_no" => "12345678",
        ];

        $res = $cityService->action()->cancelOrder($data);

        return $res;
    }

    public function abnormalConfirm()
    {
        $cityService = $this->getCityService();
        $data = [
            "shop_order_id" => "SFTC_001",
            "shop_no" => "12345678",
            "waybill_id" => "SFTC_001_waybillid",
            "remark" => "remark",
        ];

        $res = $cityService->action()->abnormalConfirm($data);

        return $res;
    }
    public function getOrder()
    {
        $cityService = $this->getCityService();
        $data = [
            "shop_order_id" => "SFTC_001",
            "shop_no" => "12345678",
        ];

        $res = $cityService->action()->getOrder($data);

        return $res;
    }
}

$demo = new demo();
echo "<pre>";
var_dump($demo->getOrder());
echo "</pre>";
