<?php
namespace CityService;

use CityService\Exceptions\CityServiceException;

/**
 * Class Factory
 *
 * @package CityService
 */
class Factory
{
    /**
     * @param string $platform
     * @param array  $config
     *
     * @return CityServiceInterface
     * @throws CityServiceException
     */
    public static function getInstance($platform, array $config = []): CityServiceInterface
    {
        try {
            $platform = ucwords($platform);
            $class = new \ReflectionClass(__NAMESPACE__. "\\Drivers\\{$platform}\\{$platform}");
            return $class->newInstanceArgs([$config]);
        }
        catch (\ReflectionException $e)
        {
            throw new CityServiceException($e->getMessage());
        }
    }
}