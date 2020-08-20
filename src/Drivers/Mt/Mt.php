<?php

namespace CityService\Drivers\Mt;

use CityService\AbstractCityService;
use CityService\CityServiceInterface;
use CityService\Drivers\Mt\Response\MtAddOrderResponse;
use CityService\Drivers\Mt\Response\MtPreAddOrderResponse;
use CityService\Exceptions\HttpException;
use CityService\ResponseInterface;
use GuzzleHttp\Client;

class Mt extends AbstractCityService implements CityServiceInterface
{
    const BASE_URI = 'https://peisongopen.meituan.com/api';

    public function getAllImmeDelivery(): \CityService\ResponseInterface
    {
        // TODO: Implement getAllImmeDelivery() method.
    }

    /**
     * 预创建订单
     * https://peisong.meituan.com/open/doc#section2-1
     * @param array $data
     * @return ResponseInterface
     * @throws HttpException
     * @throws \CityService\Exceptions\CityServiceException
     */
    public function preAddOrder(array $data = []): \CityService\ResponseInterface
    {
        $path = '/order/createByShop';

        $goodsDetail = [];
        foreach ($data['cargo']['goods_detail']['goods'] as $key => $item) {
            $goodsDetail['goods'][] = [
                'goodName' => $item['good_name'],
                'goodPrice' => $item['good_price'],
                'goodCount' => (int) $item['good_count'],
                'goodUnit' => $item['good_unit'],
            ];
        }

        $default = [
            'delivery_id' => $data['delivery_id'],
            'order_id' => $data['shop_order_id'],
            'outer_order_source_desc' => $data['outer_order_source_desc'],
            'shop_id' => $this->getConfig('shop_id'),
            'delivery_service_code' => $data['delivery_service_code'],
            'receiver_name' => $data['receiver']['name'],
            'receiver_address' => $data['receiver']['address'] . $data['receiver']['address_detail'],
            'receiver_phone' => $data['receiver']['phone'],
            'receiver_lng' => $data['receiver']['lng'] * pow(10, 6),
            'receiver_lat' => $data['receiver']['lat'] * pow(10, 6),
            'goods_value' => $data['cargo']['goods_value'],
            'goods_weight' => $data['cargo']['goods_weight'],
            'goods_detail' => json_encode($goodsDetail, JSON_UNESCAPED_UNICODE),
        ];

        $result = $this->post($path, $default);

        return new MtPreAddOrderResponse(json_decode($result, true));
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
        $path = '/order/createByShop';
        $goodsDetail = [];
        foreach ($data['cargo']['goods_detail']['goods'] as $key => $item) {
            $goodsDetail['goods'][] = [
                'goodName' => $item['good_name'],
                'goodPrice' => $item['good_price'],
                'goodCount' => (int) $item['good_count'],
                'goodUnit' => $item['good_unit'],
            ];
        }

        $default = [
            'delivery_id' => $data['delivery_id'],
            'order_id' => $data['shop_order_id'],
            'outer_order_source_desc' => $data['outer_order_source_desc'],
            'shop_id' => $this->getConfig('shop_id'),
            'delivery_service_code' => $data['delivery_service_code'],
            'receiver_name' => $data['receiver']['name'],
            'receiver_address' => $data['receiver']['address'] . $data['receiver']['address_detail'],
            'receiver_phone' => $data['receiver']['phone'],
            'receiver_lng' => $data['receiver']['lng'] * pow(10, 6),
            'receiver_lat' => $data['receiver']['lat'] * pow(10, 6),
            'goods_value' => $data['cargo']['goods_value'],
            'goods_weight' => $data['cargo']['goods_weight'],
            'goods_detail' => json_encode($goodsDetail, JSON_UNESCAPED_UNICODE),
        ];
        
        $result = $this->post($path, $default);
        return new MtAddOrderResponse(json_decode($result, true));
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

    private function makeSign($data)
    {
        ksort($data);

        $args = $this->getConfig('appSecret');

        foreach ($data as $key => $value) {
            if ($value) {
                $args .= $key . $value;
            }
        }

        $sign = sha1($args);

        return $sign;
    }

    private function post($path, array $data = [])
    {
        try {
            // 系统参数
            $data['appkey'] = $this->getConfig('appKey');
            $data['timestamp'] = time();
            $data['version'] = '1.0';
            $data['sign'] = $this->makeSign($data);

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
                    'query' => $data,
                ]
            )->getBody();
            return $body;
        } catch (GuzzleException $e) {
            throw new HttpException($e->getMessage());
        }
    }
}
