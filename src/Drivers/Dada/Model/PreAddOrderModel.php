<?php
namespace CityService\Drivers\Dada\Model;

/**
 * http://newopen.imdada.cn/#/development/file/add?_k=5f4vjj
 *
 */
class PreAddOrderModel
{

    public $deliveryNo;

    public function setDeliveryNo($deliveryNo)
    {
        !empty($deliveryNo) ? $this->deliveryNo = $deliveryNo : trigger_error('deliveryNo不能为空', E_USER_ERROR);
    }

    public function getDeliveryNo()
    {
        return $this->deliveryNo;
    }
}
