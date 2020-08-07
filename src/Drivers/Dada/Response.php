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
        // echo '<pre>';
        // var_dump($data);
        // echo '</pre>';
        // die();
    }

    public function getCode()
    {
        return $this->data['resultcode'] ?? $this->data['errorCode'];
    }

    public function getData(){
        return $this->data ?? [];
    }

    public function isSuccessful():bool {
        return ! is_null($this->getCode()) && $this->getCode() === 0;
    }

    public function getMessage():?string {
        return $this->data['resultmsg'] ?? $this->data['msg'];
    }

    public function __toString()
    {
        return json_encode($this->data);
    }
}