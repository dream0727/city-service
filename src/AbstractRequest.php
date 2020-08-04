<?php

namespace CityService;

use CityService\Exceptions\ValidationException;

abstract class AbstractRequest
{
    /**
     * @var array
     */
    private $payload = [];
    /**
     * @var array
     */
    protected $rules = [];
    /**
     * @var array
     */
    protected $message = [];

    public function __construct(array $params = [])
    {
        $this->payload = $this->validate($params);
    }

    abstract public function build(): array ;

    protected function getPayload(): array
    {
        return $this->payload;
    }

    protected function validate(array $params = []): array
    {
        // TODO 验证
        return $params;

//        throw new ValidationException("some thing");
    }
}