<?php

namespace CityService\Drivers\Dada;

use CityService\AbstractCityService;
use CityService\CityServiceInterface;
use CityService\Drivers\Dada\Api\AddOrderApi;
use CityService\Drivers\Dada\Api\CityCodeApi;
use CityService\Drivers\Dada\Api\DeliverFeeApi;
use CityService\Drivers\Dada\Api\PreAddOrderApi;
use CityService\Drivers\Dada\Client\DadaRequestClient;
use CityService\Drivers\Dada\Config\Config;
use CityService\Drivers\Dada\Exceptions\DadaException;
use CityService\Drivers\Dada\Model\DeliverFeeModel;
use CityService\Drivers\Dada\Model\OrderModel;
use CityService\Drivers\Dada\Model\PreAddOrderModel;
use CityService\Exceptions\CityServiceException;
use CityService\Exceptions\HttpException;
use CityService\ResponseInterface;

class Dada extends AbstractCityService implements CityServiceInterface
{
    public function __construct(array $config = [])
    {
        $dataConfig = new Config($config['sourceId'], false);
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
        // $preAddOrderModel = new PreAddOrderModel();
        // $preAddOrderModel->deliveryNo = $deliverFee['deliveryNo'];

        // $preAddOrderApi = new PreAddOrderApi(json_encode($preAddOrderModel));

        // $dada_client = new DadaRequestClient($this->config, $preAddOrderApi);
        // $resp = $dada_client->makeRequest();

        // if (!is_string($resp)) {
        //     $resp->setData('result', $deliverFee);
        // }

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
        $orderModel->setIsPrepay($data['is_prepay']);
        $orderModel->setReceiverName($data['receiver']['name']);
        $orderModel->setReceiverAddress($data['receiver']['address'] . $data['receiver']['address_detail']);
        $orderModel->setReceiverLat($data['sender']['lat']);
        $orderModel->setReceiverLng($data['sender']['lng']);
        $orderModel->setReceiverPhone($data['receiver']['phone']);
        $orderModel->setCargoWeight($data['cargo']['goods_weight']);
        $orderModel->setProductList([
            'sku_name' => $data['shop']['goods_name'],
            'src_product_no' => $data['shop']['img_url'],
            'count' => $data['shop']['goods_count'],
        ]);
        $orderModel->setCallback($data['callback']); // 回调url, 每次订单状态变更会通知该url(参照回调接口)

        $addOrderApi = new AddOrderApi(json_encode($orderModel));

        $dada_client = new DadaRequestClient($this->config, $addOrderApi);
        $resp = $dada_client->makeRequest();

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
        throw new DadaException('暂不支持该接口');
    }

    private function getCityCodeList()
    {
        $config = $this->config;
        $cityCodeModel = "";
        $cityCodeApi = new CityCodeApi($cityCodeModel);

        $dada_client = new DadaRequestClient($config, $cityCodeApi);
        $resp = $dada_client->makeRequest();

        if ($resp->getCode() != 0) {
            throw new DadaException($resp->getMessage());
        }

        return $resp->getData()['result'];
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
        $deliverFeeModel->setIsPrepay($data['is_prepay']);
        $deliverFeeModel->setReceiverName($data['receiver']['name']);
        $deliverFeeModel->setReceiverAddress($data['receiver']['address'] . $data['receiver']['address_detail']);
        $deliverFeeModel->setReceiverLat($data['sender']['lat']);
        $deliverFeeModel->setReceiverLng($data['sender']['lng']);
        $deliverFeeModel->setReceiverPhone($data['receiver']['phone']);
        $deliverFeeModel->setCargoWeight($data['cargo']['goods_weight']);
        $deliverFeeModel->setProductList([
            'sku_name' => $data['shop']['goods_name'],
            'src_product_no' => $data['shop']['img_url'],
            'count' => $data['shop']['goods_count'],
        ]);
        $deliverFeeModel->setCallback($data['callback']); // 回调url, 每次订单状态变更会通知该url(参照回调接口)

        $addOrderApi = new DeliverFeeApi(json_encode($deliverFeeModel));

        $dada_client = new DadaRequestClient($this->config, $addOrderApi);
        $resp = $dada_client->makeRequest();

        if ($resp->getCode() != 0) {
            throw new DadaException($resp->getMessage());
        }

        return $resp;

        // return $resp->getData()['result'];
    }
}
