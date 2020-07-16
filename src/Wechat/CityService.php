<?php

namespace CityService\Wechat;

use CityService\BaseCityService;
use CityService\GuzzleHttp;
use CityService\Wechat\Config;
use luweiss\Wechat\Wechat;

class CityService extends BaseCityService
{
    private $config;

    public function __construct($config = array())
    {
        $this->config = new Config($config);
    }

    private function addData($data)
    {
        $data['shopid'] = $this->config->shopId;
        $data['delivery_id'] = $this->config->deliveryId;
        $data['delivery_sign'] = SHA1($this->config->shopId . $data['shop_order_id'] . $this->config->appSecret);

        return $data;
    }

    public function getAllImmeDelivery()
    {
        try {
            $api = 'https://api.weixin.qq.com/cgi-bin/express/local/business/delivery/getall?access_token=' . $this->config->accessToken;
            $res = GuzzleHttp::post($api);
            $res = json_decode($res->getBody()->getContents(), true);
            return $res;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function preAddOrder($data = array())
    {
        try {
            $data = $this->addData($data);
            $api = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/pre_add?access_token=' . $this->config->accessToken;
            $res = GuzzleHttp::post($api, $data);
            $res = json_decode($res->getBody()->getContents(), true);
            return $res;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function addOrder($data = array())
    {
        try {
            $data = $this->addData($data);
            $api = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/add?access_token=' . $this->config->accessToken;
            $res = GuzzleHttp::post($api, $data);
            $res = json_decode($res->getBody()->getContents(), true);
            return $res;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function reOrder($data = array())
    {
        try {
            $data = $this->addData($data);
            $api = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/readd?access_token=' . $this->config->accessToken;
            $res = GuzzleHttp::post($api, $data);
            $res = json_decode($res->getBody()->getContents(), true);
            return $res;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function addTip($data = array())
    {
        try {
            $data = $this->addData($data);
            $api = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/addtips?access_token=' . $this->config->accessToken;
            $res = GuzzleHttp::post($api, $data);
            $res = json_decode($res->getBody()->getContents(), true);
            return $res;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function preCancelOrder($data = array())
    {
        try {
            $data = $this->addData($data);
            $api = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/precancel?access_token=' . $this->config->accessToken;
            $res = GuzzleHttp::post($api, $data);
            $res = json_decode($res->getBody()->getContents(), true);
            return $res;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function cancelOrder($data = array())
    {
        try {
            $data = $this->addData($data);
            $api = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/cancel?access_token=' . $this->config->accessToken;
            $res = GuzzleHttp::post($api, $data);
            $res = json_decode($res->getBody()->getContents(), true);
            return $res;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function abnormalConfirm($data = array())
    {
        try {
            $data = $this->addData($data);
            $api = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/confirm_return?access_token=' . $this->config->accessToken;
            $res = GuzzleHttp::post($api, $data);
            $res = json_decode($res->getBody()->getContents(), true);
            return $res;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getOrder($data = array())
    {
        try {
            $data = $this->addData($data);
            $api = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/get?access_token=' . $this->config->accessToken;
            $res = GuzzleHttp::post($api, $data);
            $res = json_decode($res->getBody()->getContents(), true);
            return $res;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
