<?php

namespace CityService\Drivers\Dada;

use CityService\AbstractCityService;
use CityService\CityServiceInterface;
use CityService\Drivers\Dada\Api\AddOrderApi;
use CityService\Drivers\Dada\Api\CityCodeApi;
use CityService\Drivers\Dada\Api\DeliverFeeApi;
use CityService\Drivers\Dada\Api\MockAcceptApi;
use CityService\Drivers\Dada\Api\MockBackApi;
use CityService\Drivers\Dada\Api\MockCancelApi;
use CityService\Drivers\Dada\Api\MockFetchApi;
use CityService\Drivers\Dada\Api\MockFinishApi;
use CityService\Drivers\Dada\Client\DadaRequestClient;
use CityService\Drivers\Dada\Config\Config;
use CityService\Drivers\Dada\Exceptions\DadaException;
use CityService\Drivers\Dada\Model\DeliverFeeModel;
use CityService\Drivers\Dada\Model\MockModel;
use CityService\Drivers\Dada\Model\OrderModel;
use CityService\Drivers\Dada\Response\DadaAddOrderResponse;
use CityService\Drivers\Dada\Response\DadaPreAddOrderResponse;
use CityService\Drivers\Dada\Response\MockResponse;
use CityService\ResponseInterface;

class Dada extends AbstractCityService implements CityServiceInterface
{
    public function __construct(array $config = [])
    {
        $dataConfig = new Config($config['sourceId'], $config['isDebug'] ? false : true);
        $dataConfig->setAppKey($config['appId']);
        $dataConfig->setAppSecret($config['appSecret']);
        $this->config = $dataConfig;
    }

    /**
     * 获取已支持的配送公司列表
     * @return [type] [description]
     */
    public function getAllImmeDelivery(): ResponseInterface
    {
        throw new DadaException('暂不支持该接口');
    }

    /**
     * 预下单
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function preAddOrder(array $data = []): ResponseInterface
    {
        $deliverFee = $this->getDeliverFee($data);

        return $deliverFee;
    }

    /**
     * 下配送单
     * @param array $data [description]
     */
    public function addOrder(array $data = []): ResponseInterface
    {
        $orderModel = new OrderModel();
        $orderModel->setShopNo($data['shop_no']);
        $orderModel->setOriginId($data['shop_order_id']); // 第三方订单号
        $orderModel->setCityCode($this->getCityCode($data['city_name']));
        $orderModel->setCargoPrice($data['cargo']['goods_value']);
        $orderModel->setIsPrepay(isset($data['is_prepay']) && $data['is_prepay'] ? $data['is_prepay'] : 0);
        $orderModel->setReceiverName($data['receiver']['name']);
        $orderModel->setReceiverAddress($data['receiver']['address'] . $data['receiver']['address_detail']);
        $orderModel->setReceiverLat($data['sender']['lat']);
        $orderModel->setReceiverLng($data['sender']['lng']);
        $orderModel->setReceiverPhone($data['receiver']['phone']);
        $orderModel->setCargoWeight($data['cargo']['goods_weight']);
        $productList = [];
        foreach ($data['cargo']['goods_detail']['goods'] as $key => $item) {
            $productList[] = [
                'sku_name' => $item['good_name'],
                'src_product_no' => $item['good_no'] ?: '',
                'count' => number_format($item['good_count'], 2),
            ];
        }
        $orderModel->setProductList($productList);
        $orderModel->setCallback($data['callback']); // 回调url, 每次订单状态变更会通知该url(参照回调接口)

        $addOrderApi = new AddOrderApi(json_encode($orderModel));

        $dada_client = new DadaRequestClient($this->config, $addOrderApi);
        $resp = new DadaAddOrderResponse(json_decode($dada_client->makeRequest(), true));

        return $resp;
    }

    /**
     * 重新下单
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function reOrder(array $data = []): ResponseInterface
    {
        throw new DadaException('暂不支持该接口');
    }

    /**
     * 增加小费
     * @param array $data [description]
     */
    public function addTip(array $data = []): ResponseInterface
    {
        throw new DadaException('暂不支持该接口');
    }

    /**
     * 预取消配送订单
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function preCancelOrder(array $data = []): ResponseInterface
    {
        throw new DadaException('暂不支持该接口');
    }

    /**
     * 取消配送单
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function cancelOrder(array $data = []): ResponseInterface
    {
        throw new DadaException('暂不支持该接口');
    }

    /**
     * 异常件退回商家确认收货
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function abnormalConfirm(array $data = []): ResponseInterface
    {
        throw new DadaException('暂不支持该接口');
    }

    /**
     * 拉取配送单信息
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function getOrder(array $data = []): ResponseInterface
    {
        throw new DadaException('暂不支持该接口');
    }

    /**
     * 模拟配送过程
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function mockUpdateOrder(array $data = []): ResponseInterface
    {
        $orderModel = new MockModel();
        $orderModel->setOrderId($data['shop_order_id']);
        // var_dump($orderModel);
        // die();

        switch ($data['mock_type']) {
            case 'ACCEPT':
                $mockApi = new MockAcceptApi(json_encode($orderModel));
                break;
            case 'FETCH':
                $mockApi = new MockFetchApi(json_encode($orderModel));
                break;
            case 'FINISH':
                $mockApi = new MockFinishApi(json_encode($orderModel));
                break;
            case 'CANCEL':
                $mockApi = new MockCancelApi(json_encode($orderModel));
                break;
            case 'BACK':
                $mockApi = new MockBackApi(json_encode($orderModel));
                break;
            default:
                throw new DadaException('模拟类型不存在');
                break;
        }

        $dada_client = new DadaRequestClient($this->config, $mockApi);
        $resp = new MockResponse(json_decode($dada_client->makeRequest(), true));

        return $resp;
    }

    private function getCityCodeList()
    {
        $config = $this->config;
        $cityCodeModel = "";
        $cityCodeApi = new CityCodeApi($cityCodeModel);

        $dada_client = new DadaRequestClient($config, $cityCodeApi);
        $resp = $dada_client->makeRequest();

        $result = json_decode($resp, true);

        if ($result['code'] != 0) {
            throw new DadaException($result['msg']);
        }

        return $result['result'];
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
    /**
     * 预下单查询运费
     * @return [type] [description]
     */
    private function getDeliverFee($data)
    {
        $deliverFeeModel = new DeliverFeeModel();
        $deliverFeeModel->setShopNo($data['shop_no']);
        $deliverFeeModel->setOriginId($data['shop_order_id']); // 第三方订单号
        $deliverFeeModel->setCityCode($this->getCityCode($data['city_name']));
        $deliverFeeModel->setCargoPrice($data['cargo']['goods_value']);
        $deliverFeeModel->setIsPrepay(isset($data['is_prepay']) && $data['is_prepay'] ? $data['is_prepay'] : 0);
        $deliverFeeModel->setReceiverName($data['receiver']['name']);
        $deliverFeeModel->setReceiverAddress($data['receiver']['address'] . $data['receiver']['address_detail']);
        $deliverFeeModel->setReceiverLat($data['sender']['lat']);
        $deliverFeeModel->setReceiverLng($data['sender']['lng']);
        $deliverFeeModel->setReceiverPhone($data['receiver']['phone']);
        $deliverFeeModel->setCargoWeight($data['cargo']['goods_weight']);

        $productList = [];
        foreach ($data['cargo']['goods_detail']['goods'] as $key => $item) {
            $productList[] = [
                'sku_name' => $item['good_name'],
                'src_product_no' => $item['good_no'] ?: '',
                'count' => number_format($item['good_count'], 2),
            ];
        }
        $deliverFeeModel->setProductList($productList);
        $deliverFeeModel->setCallback($data['callback']); // 回调url, 每次订单状态变更会通知该url(参照回调接口)

        $addOrderApi = new DeliverFeeApi(json_encode($deliverFeeModel));

        $dada_client = new DadaRequestClient($this->config, $addOrderApi);
        $resp = new DadaPreAddOrderResponse(json_decode($dada_client->makeRequest(), true));

        return $resp;
    }
}
