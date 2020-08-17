<?php

namespace CityService\Drivers\Dada\Api;

use CityService\Drivers\Dada\Api\BaseApi;
use CityService\Drivers\Dada\Config\UrlConfig;

class AddOrderApi extends BaseApi{
    
    public function __construct($params) {
        parent::__construct(UrlConfig::ORDER_ADD_URL, $params);
    }
}
