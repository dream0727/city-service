<?php
/**
 * @copyright ©2020 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/8/7
 * Time: 14:17
 */

namespace CityService\Drivers\Ss;

use CityService\ResponseInterface;

class Response implements ResponseInterface
{

    /**
     * @var array
     */
    private $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getCode()
    {
        return $this->data['status'];
    }

    public function getData(){
        return $this->data ?? [];
    }

    public function isSuccessful():bool {
        return ! is_null($this->getCode()) && $this->getCode() === 0;
    }

    public function getMessage():?string {
        return $this->data['msg'] ;
    }

    public function __toString()
    {
        return json_encode($this->data);
    }
}
