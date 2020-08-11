<?php

namespace CityService\Drivers\Sf\Response;

use CityService\ResponseInterface;

class SfPreAddOrderResponse implements ResponseInterface
{
    private $result;

    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    public function getCode()
    {
        return $this->result['error_code'];
    }
    
    public function getData()
    {
        return [
            'fee' => $this->result['result']['total_price'],
        ];
    }

    public function getOriginalData()
    {
    	return $this->result;
    }

    public function isSuccessful(): bool
    {
        return !is_null($this->getCode()) && $this->getCode() === 0;
    }

    public function getMessage():  ? string
    {
        return $this->result['error_msg'];
    }
}
