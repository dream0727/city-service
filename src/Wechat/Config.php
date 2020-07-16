<?php

namespace CityService\Wechat;

use CityService\BaseConfig;
use luweiss\Wechat\Wechat;

class Config extends BaseConfig
{
    public $wechatAppId;
    public $wechatAppSecret;
    public $accessToken;

    public function setConfig($config)
    {
        $this->wechatAppId = isset($config['wechat']['appId']) ? $config['wechat']['appId'] : '';
        $this->wechatAppSecret = isset($config['wechat']['appSecret']) ? $config['wechat']['appSecret'] : '';

        $wechat = $this->getWechat();
        $this->accessToken = $wechat ? $wechat->getAccessToken() : '';
    }

    private function getWechat()
    {
        $wechat = new Wechat([
            'appId' => $this->wechatAppId,
            'appSecret' => $this->wechatAppSecret,
        ]);

        return $wechat;
    }
}
