<?php

declare(strict_types=1);

namespace App\Service;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Redis\RedisFactory;

class RedLockService extends BaseService {

    private $retryDelay;
    private $retryCount;
    private $clockDriftFactor = 0.01;

    private $quorum;

    private $servers = [];
    private $instances = [];

    /**
     * @Inject
     * @var RedisFactory
     */
    private $redisFactory;

    function __construct(ConfigInterface $config)
    {
        $this->servers = array_keys($config->get('redis'));

        $this->retryDelay = 200;
        $this->retryCount = 3;

        $this->quorum = min(count($this->servers), (count($this->servers) / 2 + 1));
    }

    public function lock($resource, $ttl)
    {
        $this->initInstances();

        $token = uniqid();
        $retry = $this->retryCount;

        do {
            $n = 0;

            $startTime = microtime(true) * 1000;

            foreach ($this->instances as $instance) {
                if ($this->lockInstance($instance, $resource, $token, $ttl)) {
                    $n++;
                }
            }

            # Add 2 milliseconds to the drift to account for Redis expires
            # precision, which is 1 millisecond, plus 1 millisecond min drift
            # for small TTLs.
            $drift = ($ttl * $this->clockDriftFactor) + 2;

            $validityTime = $ttl - (microtime(true) * 1000 - $startTime) - $drift;

            if ($n >= $this->quorum && $validityTime > 0) {
                return [
                    'validity' => $validityTime,
                    'resource' => $resource,
                    'token' => $token,
                ];

            } else {
                foreach ($this->instances as $instance) {
                    $this->unlockInstance($instance, $resource, $token);
                }
            }

            // Wait a random delay before to retry
            $delay = mt_rand(floor($this->retryDelay / 2), $this->retryDelay);
            usleep($delay * 1000);

            $retry--;

        } while ($retry > 0);

        return false;
    }

    public function unlock(array $lock)
    {
        $this->initInstances();
        $resource = $lock['resource'];
        $token = $lock['token'];

        foreach ($this->instances as $instance) {
            $this->unlockInstance($instance, $resource, $token);
        }
    }

    private function initInstances()
    {
        if (empty($this->instances)) {
            foreach ($this->servers as $server) {
                $redis = $this->redisFactory->get($server);

                $this->instances[] = $redis;
            }
        }
    }

    private function lockInstance($instance, $resource, $token, $ttl)
    {
        return $instance->set($resource, $token, ['NX', 'PX' => $ttl]);
    }

    private function unlockInstance($instance, $resource, $token)
    {
        $script = '
            if redis.call("GET", KEYS[1]) == ARGV[1] then
                return redis.call("DEL", KEYS[1])
            else
                return 0
            end
        ';
        return $instance->eval($script, [$resource, $token], 1);
    }
}