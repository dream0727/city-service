<?php

namespace CityService\Drivers\Dada;

use CityService\AbstractCityService;
use CityService\CityServiceInterface;
use CityService\Drivers\Dada\Exceptions\DadaException;
use CityService\Drivers\Dada\api\CityCodeApi;
use CityService\Drivers\Dada\client\DadaRequestClient;
use CityService\Drivers\Dada\config\Config;
use CityService\Exceptions\CityServiceException;
use CityService\Exceptions\HttpException;
use CityService\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Dada extends AbstractCityService implements CityServiceInterface
{
    const BASE_URI = 'https://newopen.imdada.cn';

    /**
     * 获取已支持的配送公司列表
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    public function getAllImmeDelivery(): ResponseInterface
    {
        throw new HttpException('暂不支持该接口');
    }

    /**
     * 预下单
     *
     * @param array $data
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    public function preAddOrder(array $data = []): ResponseInterface
    {
        $cityCode = $this->getCityCode('嘉兴');
        echo $cityCode;
        die();

        $params = $this->getParams($data);

        return $this->post($path, $params);
    }

    /**
     * 下配送单
     *
     * @param array $data
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    public function addOrder(array $data = []): ResponseInterface
    {
        $path = '/order/add';
        $params = $this->getParams($data);

        return $this->post($path, $params);
    }

    /**
     *
     * 重新下单
     *
     * @param array $data
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    public function reOrder(array $data = []): ResponseInterface
    {
        $params = $this->getParams($data);
        $path = '/order/readd';

        return $this->post($path, $params);
    }

    /**
     * 增加小费
     *
     * @param array $data
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    public function addTip(array $data = []): ResponseInterface
    {
        $params = $this->getParams($data);
        $path = '/order/addtips';

        return $this->post($path, $params);
    }

    /**
     * 预取消配送订单
     *
     * @param array $data
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    public function preCancelOrder(array $data = []): ResponseInterface
    {
        $params = $this->getParams($data);
        $path = '/order/precancel';

        return $this->post($path, $params);
    }

    /**
     * 取消配送单
     *
     * @param array $data
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    public function cancelOrder(array $data = []): ResponseInterface
    {
        $params = $this->getParams($data);
        $path = '/order/cancel';

        return $this->post($path, $params);
    }

    /**
     * 异常件退回商家确认收货
     *
     * @param array $data
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    public function abnormalConfirm(array $data = []): ResponseInterface
    {
        $params = $this->getParams($data);
        $path = '/order/confirm_return';

        return $this->post($path, $params);
    }

    /**
     * 拉取配送单信息
     *
     * @param array $data
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    public function getOrder(array $data = []): ResponseInterface
    {
        $params = $this->getParams($data);
        $path = '/order/get';

        return $this->post($path, $params);
    }

    /**
     * 拉取配送单信息
     *
     * @param array $data
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    public function mockUpdateOrder(array $data = []): ResponseInterface
    {
        $params = $this->getParams($data);
        $path = '/test_update_order';

        return $this->post($path, $params);
    }

    /**
     * http post method
     * @param       $path
     * @param array $data
     *
     * @return ResponseInterface
     * @throws CityServiceException
     * @throws HttpException
     * @throws \luweiss\Wechat\WechatException
     */
    private function post($path, array $data = []): ResponseInterface
    {
        try {
            $client = new Client([
                'timeout' => 30,
            ]);
            $url = substr($path, 0, 1) !== '/' ? self::BASE_URI . '/' . $path : self::BASE_URI . $path;
            $body = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'query' => [],
                'body' => json_encode($data, JSON_UNESCAPED_UNICODE),
            ])->getBody();
            return new Response(json_decode((string) $body, true));
        } catch (GuzzleException $e) {
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * @param string $shop_order_id
     *
     * @return array
     * @throws \CityService\Exceptions\CityServiceException
     */
    private function getParams(array $data = [])
    {
        // isOnline 判断是否是测试环境，会有不同的域名等
        $isOnline = false;
        $sourceId = 73753;
        // 初始化一个config
        $config = new Config($sourceId, $isOnline);
        $config->app_key = $this->getConfig('appId');
        $config->app_secret = $this->getConfig('appSecret');

        return array_merge($data, [
            'shop_no' => $this->getConfig('shopId'),
            'app_key' => $this->getConfig('appId'),
            'app_secret' => $this->getConfig('appSecret'),
        ]);
    }

    private function getCityCodeList()
    {
        $config = new Config(0, false);
        $cityCodeModel = "";
        $cityCodeApi = new CityCodeApi($cityCodeModel);

        $dada_client = new DadaRequestClient($config, $cityCodeApi);
        $resp = $dada_client->makeRequest();


        if ($resp->code != 0) {
            throw new DadaException($resp->msg);
        }
        
        return $resp->result;
    }
    /**
     * 获取城市编码
     * @param  [stirng] $cityName [城市名称 例如: 上海]
     * @return [string]           [description]
     */
    private function getCityCode($cityName)
    {
        $cityCodeList = $this->getCityCodeList();
        foreach ($cityCodeList as $key => $item) {
            if ($item['cityName'] == $cityName) {
                return $item['cityCode'];
            }
        }

        throw new DadaException($cityName . '不支持配送');
    }
}
