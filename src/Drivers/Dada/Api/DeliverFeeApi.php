<?php

namespace CityService\Drivers\Dada\Api;

use CityService\Drivers\Dada\Api\BaseApi;
use CityService\Drivers\Dada\Config\UrlConfig;

class DeliverFeeApi extends BaseApi{
    
    public function __construct($params) {
        parent::__construct(UrlConfig::DELIVER_FEE_URL, $params);
    }
}
