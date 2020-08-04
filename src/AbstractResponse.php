<?php

namespace CityService;

abstract class AbstractResponse
{
    /**
     * @var array
     */
    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return json_encode($this->data);
    }
}