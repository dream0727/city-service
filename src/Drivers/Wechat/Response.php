<?php

namespace CityService\Drivers\Wechat;

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
        return $this->data['resultcode'] ?? null;
    }

    public function getData(){
        return $this->data['list'] ?? [];
    }

    public function isSuccessful():bool {
        return ! is_null($this->getCode()) && $this->getCode() === 0;
    }

    public function getMessage():?string {
        return $this->data['resultmsg'] ?? null;
    }

    public function __toString()
    {
        return json_encode($this->data);
    }
}