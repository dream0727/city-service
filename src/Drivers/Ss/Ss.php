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
    const BASE_URI_DEBUG = 'http://open.s.bingex.com/openapi/merchants/v5'; //开发环境
    const BASE_URI = 'http://open.ishansong.com/openapi/merchants/v5'; //正式环境

    public function getAllImmeDelivery(): \CityService\ResponseInterface
    {
        // TODO: Implement getAllImmeDelivery() method.
    }


    /**
     * 腾讯地图---->百度地图
     * @param double $lat 纬度
     * @param double $lng 经度
     */
    private function Convert_GCJ02_To_BD09($lat,$lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng;
        $y = $lat;
        $z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta) + 0.0065;
        $lat = $z * sin($theta) + 0.006;
        return array('lng'=>$lng,'lat'=>$lat);
    }

    /**
     * 转换腾讯地图坐标为百度坐标
     * @param $data
     */
    private function dealPos(&$data)
    {
        $receiverPos = $this->Convert_GCJ02_To_BD09($data['receiver']['lat'], $data['receiver']['lng']);
        $data['receiver']['lat'] = $receiverPos['lat'];
        $data['receiver']['lng'] = $receiverPos['lng'];
        $senderPos = $this->Convert_GCJ02_To_BD09($data['sender']['lat'], $data['sender']['lng']);
        $data['sender']['lat'] = $senderPos['lat'];
        $data['sender']['lng'] = $senderPos['lng'];
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
        $this->dealPos($data);
        $path = 'orderCalculate';
        $receiver = [
            "orderNo" => $data['shop_order_id'],
            "toAddress" => $data['receiver']['address'] ?? '',
		    "toAddressDetail" =>  $data['receiver']['address_detail'],
            "toLatitude" => $data['receiver']['lat'],
            "toLongitude" => $data['receiver']['lng'],
            "toReceiverName" => $data['receiver']['name'],
            "toMobile" => $data['receiver']['phone'],
            "goodType" => 10,
            "weight" => $data['cargo']['goods_weight'],
        ];
        $json = [
            "cityName" => $data['sender']['city'],
            "sender" => [
                "fromAddress" => $data['sender']['address'],
                "fromAddressDetail" => $data['sender']['address_detail'],
                "fromSenderName" => $data['sender']['name'],
                "fromMobile" => $data['sender']['phone'],
                "fromLatitude" => $data['sender']['lat'],
                "fromLongitude" => $data['sender']['lng'],
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
            try {
                $debug = $this->getConfig('debug');
            } catch (\Exception $e) {
                $debug = false;
            }
            if ($debug) {
                $url = self::BASE_URI_DEBUG . '/' . $path;
            } else {
                $url = self::BASE_URI . '/' . $path;
            }
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
