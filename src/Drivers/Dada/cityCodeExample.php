<?php
namespace CityService\Drivers\Dada;

use CityService\Drivers\Dada\api\CityCodeApi;
use CityService\Drivers\Dada\client\DadaRequestClient;
use CityService\Drivers\Dada\config\Config;

//参考文档 http://newopen.imdada.cn/#/development/file/cityList?_k=qbcp8l

//*********************1.配置项*************************
$config = new Config(0, false);

//*********************2.实例化一个model*************************
// city_code 业务参数为""
$cityCodeModel = "";

//*********************3.实例化一个api*************************
$cityCodeApi = new CityCodeApi($cityCodeModel);

//***********************4.实例化客户端请求************************
$dada_client = new DadaRequestClient($config, $cityCodeApi);
$resp = $dada_client->makeRequest();
echo json_encode($resp);
