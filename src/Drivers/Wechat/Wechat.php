<?php

namespace CityService\Drivers\Wechat;

use CityService\AbstractCityService;
use CityService\CityServiceInterface;
use CityService\Exceptions\CityServiceException;
use CityService\Exceptions\HttpException;
use CityService\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Wechat extends AbstractCityService implements CityServiceInterface
{
    const BASE_URI = 'https://api.weixin.qq.com/cgi-bin/express/local/business';

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
        $path = '/delivery/getall';
        return $this->post($path);
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
        $path = '/order/pre_add';
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
    public function addOrder(array $data = []):ResponseInterface
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
    public function reOrder(array $data = []):ResponseInterface
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
    public function addTip(array $data = []):ResponseInterface
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
    public function preCancelOrder(array $data = []):ResponseInterface
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
    public function cancelOrder(array $data = []):ResponseInterface
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
    public function abnormalConfirm(array $data = []):ResponseInterface
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
    public function getOrder(array $data = []):ResponseInterface
    {
        $params = $this->getParams($data);
        $path = '/order/get';

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
                'query' => [
                    'access_token' => $this->getAccessToken()
                ],
                'body'  => json_encode($data, JSON_UNESCAPED_UNICODE)
            ])->getBody();
            return new Response(json_decode((string)$body, true));
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
    private function getParams(array $data = []) {
        if(!isset($data['shop_order_id'])) {
            throw new CityServiceException('The shop_order_id field is required.');
        }

        return array_merge($data, [
            'shopid'        => $this->getConfig('shopId'),
            'delivery_id'   => $this->getConfig('deliveryId'),
            'delivery_sign' => SHA1($this->getConfig('shopId') . $data['shop_order_id'] . $this->getConfig('deliveryAppSecret'))
        ]);
    }

    /**
     *
     * get access_token
     *
     * @param bool $refresh
     *
     * @return string
     * @throws \CityService\Exceptions\CityServiceException
     * @throws \luweiss\Wechat\WechatException
     */
    private function getAccessToken($refresh = false)
    {
        return (new \luweiss\Wechat\Wechat([
            'appId'     => $this->getConfig('appId'),
            'appSecret' => $this->getConfig('appSecret'),
        ]))->getAccessToken($refresh);
    }
}