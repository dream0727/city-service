<?php

namespace CityService\Drivers\Dada;

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
        return $this->data['code'];
    }

    public function getData()
    {
        return $this->data ?? [];
    }

    public function setData($key, $data)
    {
        $this->data[$key] = $data;
    }

    public function isSuccessful(): bool
    {
        return !is_null($this->getCode()) && $this->getCode() === 0;
    }

    public function getMessage():  ? string
    {
        return $this->data['msg'];
    }

    public function __toString()
    {
        return json_encode($this->data);
    }
}
