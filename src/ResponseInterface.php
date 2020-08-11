<?php

namespace CityService;

interface ResponseInterface
{
    public function getCode();

    public function getData();

    public function getOriginalData();

    public function isSuccessful(): bool;

    public function getMessage(): ?string;
}