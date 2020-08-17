<?php

namespace CityService\Drivers\Dada\Api;

use CityService\Drivers\Dada\Api\BaseApi;
use CityService\Drivers\Dada\Config\UrlConfig;

class MockAcceptApi extends BaseApi{
    
    public function __construct($params) {
        parent::__construct(UrlConfig::MOCK_ACCEPT, $params);
    }
}
