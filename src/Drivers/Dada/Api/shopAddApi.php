<?php
namespace CityService\Drivers\Dada\Api;

use CityService\Drivers\Dada\Api\BaseApi;
use CityService\Drivers\Dada\Config\UrlConfig;

class AddShopApi extends BaseApi{
    
    public function __construct($params) {
        parent::__construct(UrlConfig::SHOP_ADD_URL, $params);
    }
}
