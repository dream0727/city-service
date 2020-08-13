<?php
namespace CityService\Drivers\Dada\Api;

use CityService\Drivers\Dada\Api\BaseApi;
use CityService\Drivers\Dada\Config\UrlConfig;

class CityCodeApi extends BaseApi{
    
    public function __construct($params) {
        parent::__construct(UrlConfig::CITY_ORDER_URL, $params);
    }
}
