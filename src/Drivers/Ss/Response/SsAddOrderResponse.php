<?php

namespace CityService\Drivers\Ss\Response;

use CityService\ResponseInterface;

class SsAddOrderResponse implements ResponseInterface
{
    private $result;

    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    public function getCode()
    {
        return $this->result['status'];
    }
    
    public function getData()
    {
        return $this->result['data'];
    }

    public function getOriginalData()
    {
        return $this->result;
    }

    public function isSuccessful(): bool
    {
        return !is_null($this->getCode()) && $this->getCode() === 200;
    }

    public function getMessage():  ? string
    {
        return $this->result['msg'];
    }
}
