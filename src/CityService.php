<?php

namespace CityService;

class CityService
{
    private $config;
    private $platform;
    private $action;

    public function __construct($config = array(), $platform)
    {
        $this->config = $config;
        $this->platform = $platform;
        $this->setModel();
    }

    private function setModel()
    {
        switch ($this->platform) {
            case 'wechat':
                $this->action = new \CityService\Wechat\CityService($this->config);
                break;
            default:
                throw new \Exception('未知平台' . $this->platform);  
                break;
        }
    }

    public function action()
    {
        return $this->action;
    }
}
