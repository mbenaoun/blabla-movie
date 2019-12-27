<?php

namespace App\Service;

use Predis\ClientInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;

/**
 * Class ApiCache
 * @package App\Service
 */
class ApiCache
{
    public const USERS_HASH_KEY = 'users';

    /** @var ClientInterface $redisCache */
    private $redisCache;

    /**
     * ApiCache constructor.
     */
    public function __construct()
    {
        $this->redisCache = RedisAdapter::createConnection(
            'redis://' . $_ENV['REDIS_HOST'] . ':' . $_ENV['REDIS_PORT']
        );
    }

    /**
     * @param string $key
     * @param $field
     * @param $value
     */
    public function set(string $key, $field, $value)
    {
        $this->redisCache->hset($key, (string)$field, serialize($value));
    }

    /**
     * @param string $key
     * @param $field
     * @return mixed|null
     */
    public function get(string $key, $field)
    {
        $value = $this->redisCache->hget($key, (string)$field);
        if (is_string($value)) {
            return unserialize($value);
        }
        return null;
    }
}
