<?php
namespace CityService\Drivers\Dada\Config;

class UrlConfig
{
    /**
     * 查询订单运费接口
     */
    const DELIVER_FEE_URL = '/api/order/queryDeliverFee';
    /**
     * 查询运费后发单接口
     */
    const PRE_ORDER_ADD_URL = "/api/order/addAfterQuery";
    /**
     * 下单接口
     */
    const ORDER_ADD_URL = "/api/order/addOrder";
    /**
     * 查询城市code接口
     */
    const CITY_ORDER_URL = "/api/cityCode/list";

    // 模拟订单回调接口
    // 接受订单
    const MOCK_ACCEPT = "/api/order/accept";
    // 完成取货
    const MOCK_FETCH = "/api/order/fetch";
    // 完成订单
    const MOCK_FINISH = "/api/order/finish";
    // 取消订单
    const MOCK_CANCEL = "/api/order/cancel";
    // 订单异常 货品退回
    const MOCK_BACK = "/api/order/delivery/abnormal/back";
}
