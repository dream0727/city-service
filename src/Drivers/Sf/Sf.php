<?php

namespace CityService\Drivers\Sf;

use CityService\AbstractCityService;
use CityService\CityServiceInterface;
use CityService\Drivers\Sf\Response\SfAddOrderResponse;
use CityService\Drivers\Sf\Response\SfPreAddOrderResponse;
use CityService\Exceptions\HttpException;
use CityService\ResponseInterface;
use GuzzleHttp\Client;

/**
 * @copyright ©2020 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/8/7
 * Time: 14:15
 */
class Sf extends AbstractCityService implements CityServiceInterface
{
    const BASE_URI = 'https://commit-openic.sf-express.com/open/api/external';
    //const BASE_URI = 'http://sfapi-proxy.jsonce.com/open/api/external';

    public function getAllImmeDelivery(): \CityService\ResponseInterface
    {
        // TODO: Implement getAllImmeDelivery() method.
    }

    /**
     * 预创建订单
     * http://commit-openic.sf-express.com/open/api/docs/index#/apidoc
     * @param array $data
     * @return ResponseInterface
     * @throws HttpException
     * @throws \CityService\Exceptions\CityServiceException
     */
    public function preAddOrder(array $data = []): \CityService\ResponseInterface
    {
        $path = 'precreateorder';
        $default = [
            'dev_id' => $this->getConfig('dev_id'),
            'shop_id' => $this->getConfig('shop_id'),
            'user_lng' => $data['receiver']['lng'],
            'user_lat' => $data['receiver']['lat'],
            'user_address' => $data['receiver']['address_detail'],
            'weight' => $data['cargo']['goods_weight'],
            'product_type' => 99,
            'is_appoint' => 0,
            'pay_type' => 1,
            'is_insured' => 0,
            'is_person_direct' => 0,
            'push_time' => time()
        ];
        $result = $this->post($path, $default);

        return new SfPreAddOrderResponse(json_decode($result, true));
    }

    /**
     * 创建订单
     * http://commit-openic.sf-express.com/open/api/docs/index#/apidoc
     * @param array $data
     * @return ResponseInterface
     * @throws HttpException
     * @throws \CityService\Exceptions\CityServiceException
     */
    public function addOrder(array $data = []): \CityService\ResponseInterface
    {
        $path = 'createorder';
        $default = [
            'dev_id' => $this->getConfig('dev_id'),
            'shop_id' => $this->getConfig('shop_id'),
            'shop_order_id' => $data['shop_order_id'],
            'order_source' => $data['shop_no'],
            'pay_type' => 1,
            'order_time' => time(),
            'is_appoint' => 0,
            'is_insured' => 0,
            'is_person_direct' => 0,
            'return_flag' => 511,
            'push_time' => time(),
            'version' => 1,
            'receive' => [
                'user_name' => $data['receiver']['name'],
                'user_phone' => $data['receiver']['phone'],
                'user_address' => $data['receiver']['address_detail'],
                'user_lng' => $data['receiver']['lng'],
                'user_lat' => $data['receiver']['lat'],
            ],
            'order_detail' => [
                'total_price' => $data['cargo']['goods_value'],
                'product_type' => 99,
                'weight_gram' => $data['cargo']['goods_weight'],
                'product_num' => $data['shop']['goods_count'],
                'product_type_num' => 1,
            ]
        ];
        $result = $this->post($path, $default);
        return new SfAddOrderResponse(json_decode($result, true));
    }

    public function reOrder(array $data = []): \CityService\ResponseInterface
    {
        // TODO: Implement reOrder() method.
    }

    public function addTip(array $data = []): \CityService\ResponseInterface
    {
        // TODO: Implement addTip() method.
    }

    public function preCancelOrder(array $data = []): \CityService\ResponseInterface
    {
        // TODO: Implement preCancelOrder() method.
    }

    public function cancelOrder(array $data = []): \CityService\ResponseInterface
    {
        // TODO: Implement cancelOrder() method.
    }

    public function abnormalConfirm(array $data = []): \CityService\ResponseInterface
    {
        // TODO: Implement abnormalConfirm() method.
    }

    public function getOrder(array $data = []): \CityService\ResponseInterface
    {
        // TODO: Implement getOrder() method.
    }

    public function mockUpdateOrder(array $data = []): \CityService\ResponseInterface
    {
        // TODO: Implement mockUpdateOrder() method.
    }

    private function makeSign($args)
    {
        if (isset($args['sign'])) {
            unset($args['sign']);
        }
        $postData = json_encode($args);
        $signChar = $postData . "&{$this->getConfig('dev_id')}&{$this->getConfig('dev_key')}";
        $sign = base64_encode(MD5($signChar));
        return $sign;
    }

    private function post($path, array $data = [])
    {
        try {
            $client = new Client(
                [
                    'verify' => false,
                    'timeout' => 30,
                ]
            );
            $url = self::BASE_URI . '/' . $path;
            $body = $client->post(
                $url,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'query' => [
                        'sign' => $this->makeSign($data)
                    ],
                    'body' => json_encode($data)
                ]
            )->getBody();
            return $body;
        } catch (GuzzleException $e) {
            throw new HttpException($e->getMessage());
        }
    }
}
