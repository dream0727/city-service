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
}
