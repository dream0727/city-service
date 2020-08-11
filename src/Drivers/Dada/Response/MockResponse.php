<?php

namespace CityService\Drivers\Dada\Response;

use CityService\ResponseInterface;

class MockResponse implements ResponseInterface
{
    private $result;

    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    public function getCode()
    {
        return $this->result['code'];
    }

    public function getData()
    {
        return $this->result;
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
        return $this->result['msg'];
    }
}
