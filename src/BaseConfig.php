<?php

namespace CityService;

class BaseConfig
{
    public $deliveryId;
    public $shopId;
    public $appSecret;

    public function __construct($config = array())
    {
        $this->deliveryId = isset($config['cityService']['deliveryId']) ? $config['cityService']['deliveryId'] : '';
        $this->shopId = isset($config['cityService']['shopId']) ? $config['cityService']['shopId'] : '';
        $this->appSecret = isset($config['cityService']['appSecret']) ? $config['cityService']['appSecret'] : '';

        $this->setConfig($config);
    }

    public function setConfig($config)
    {
        return true;
    }
}
