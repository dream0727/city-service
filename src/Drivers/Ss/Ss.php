<?php

namespace CityService\Drivers\Ss;

use CityService\AbstractCityService;
use CityService\CityServiceInterface;
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
class Ss extends AbstractCityService implements CityServiceInterface
{
    const BASE_URI = 'http://open.s.bingex.com/openapi/merchants/v5'; //开发环境
//    const BASE_URI = 'http://open.ishansong.com/openapi/merchants/v5'; //正式环境

    public function getAllImmeDelivery(): \CityService\ResponseInterface
    {
        // TODO: Implement getAllImmeDelivery() method.
    }

    /**
     * 预创建订单
     * http://open.ishansong.com/documentCenter
     * @param array $data
     * @return ResponseInterface
     * @throws HttpException
     * @throws \CityService\Exceptions\CityServiceException
     */
    public function preAddOrder(array $data = []): \CityService\ResponseInterface
    {
        $path = 'orderCalculate';
        $receiver = [
            "orderNo" => "C1119A000013053981",
            "toAddress" => "望京",
		    "toAddressDetail" => "4楼",
            "toLatitude" => '40.004532',
            "toLongitude" => '116.475304',
            "toReceiverName" => '朱家帅',
            "toMobile" => '13545880179',
            "goodType" => 10,
            "weight" => 1,
        ];
        $json = [
            "cityName" => '北京市',
            "sender" => [
                "fromAddress" => "博彦科技大厦",
                "fromAddressDetail" => "4层101",
                "fromSenderName" => "小张",
                "fromMobile" => '13693100472',
                "fromLatitude" => '40.054759',
                "fromLongitude" => '116.289086',
            ],
            "receiverList" => [
                $receiver
            ],
            "appointType" => 0,
        ];

        //返回当前的毫秒时间戳
        function getMillisecond() {
            list($t1, $t2) = explode(' ', microtime());
            return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
        }

        $default = [
            'clientId' => $this->getConfig('client_id'),
            'shopId' => $this->getConfig('shop_id'),
            'timestamp' => getMillisecond(),
            'data' => json_encode($json)
        ];
        return $this->post($path, $default);
    }

    /**
     * 创建订单
     * http://open.ishansong.com/documentCenter
     * @param array $data
     * @return ResponseInterface
     * @throws HttpException
     * @throws \CityService\Exceptions\CityServiceException
     */
    public function addOrder(array $data = []): \CityService\ResponseInterface
    {
        $path = 'orderPlace';

        $json = [
            "issOrderNo" => $data['issOrderNo']
        ];

        //返回当前的毫秒时间戳
        function getMillisecond() {
            list($t1, $t2) = explode(' ', microtime());
            return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
        }

        $default = [
            'clientId' => $this->getConfig('client_id'),
            'shopId' => $this->getConfig('shop_id'),
            'timestamp' => getMillisecond(),
            'data' => json_encode($json)
        ];
        return $this->post($path, $default);
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
        ksort($args);
        $string = '';
        foreach ($args as $i => $arg) {
            if ($args === null || $arg === '') {
                continue;
            } else {
                $string .= ($i . $arg);
            }
        }
        $string = $this->getConfig('secret').$string;
        $string = md5($string);
        $result = strtoupper($string);
        return $result;
    }

    private function post($path, array $data = []): ResponseInterface
    {
        try {
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
                    'form_params' => $data
                ]
            )->getBody();
            return new Response(json_decode((string)$body, true));
        } catch (GuzzleException $e) {
            throw new HttpException($e->getMessage());
        }
    }
}
