<?php
namespace CityService\Drivers\Dada\Model;

/**
 * http://newopen.imdada.cn/#/development/file/add?_k=5f4vjj
 *
 */
class MockModel
{

    public $order_id;

    public function setOrderId($orderId)
    {
        !empty($orderId) ? $this->order_id = $orderId : trigger_error('orderId不能为空', E_USER_ERROR);
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    // TODO 取消原因
}
