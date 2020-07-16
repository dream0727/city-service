<?php

namespace CityService;

abstract class BaseCityService
{
	// 获取已支持的配送公司列表
    abstract public function getAllImmeDelivery();
    // 预下单
    abstract public function preAddOrder();
    // 下配送单
    abstract public function addOrder();
    // 重新下单
    abstract public function reOrder();
    // // 增加小费
    abstract public function addTip();
    // // 预取消配送订单
    abstract public function preCancelOrder();
    // // 取消配送单
    abstract public function cancelOrder();
    // // 异常件退回商家确认收货
    abstract public function abnormalConfirm();
    // // 拉取配送单信息
    abstract public function getOrder();
}
