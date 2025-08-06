<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Cache Configuration - Fixed for CodeIgniter 4
 * 
 * This configuration follows CodeIgniter 4's expected structure
 * for cache handlers.
 */
class Cache extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Primary Handler
     * --------------------------------------------------------------------------
     */
    public string $handler = 'file';

    /**
     * --------------------------------------------------------------------------
     * Backup Handler
     * --------------------------------------------------------------------------
     */
    public string $backupHandler = 'dummy';

    /**
     * --------------------------------------------------------------------------
     * Cache key prefix
     * --------------------------------------------------------------------------
     */
    public string $prefix = 'bme4_';

    /**
     * --------------------------------------------------------------------------
     * Default TTL (Time To Live)
     * --------------------------------------------------------------------------
     */
    public int $ttl = 60;

    /**
     * --------------------------------------------------------------------------
     * Reserved Characters
     * --------------------------------------------------------------------------
     */
    public string $reservedCharacters = '{}()/\@:';

    /**
     * --------------------------------------------------------------------------
     * Valid cache handlers - FIXED Structure
     * --------------------------------------------------------------------------
     * 
     * This array maps handler names to their actual class names
     * and configuration parameters.
     */
    public array $validHandlers = [
        'apcu'      => \CodeIgniter\Cache\Handlers\APCuHandler::class,
        'file'      => \CodeIgniter\Cache\Handlers\FileHandler::class,
        'memcached' => \CodeIgniter\Cache\Handlers\MemcachedHandler::class,
        'redis'     => \CodeIgniter\Cache\Handlers\RedisHandler::class,
        'predis'    => \CodeIgniter\Cache\Handlers\PredisHandler::class,
        'wincache'  => \CodeIgniter\Cache\Handlers\WincacheHandler::class,
        'dummy'     => \CodeIgniter\Cache\Handlers\DummyHandler::class,
    ];

    /**
     * --------------------------------------------------------------------------
     * File Handler Settings
     * --------------------------------------------------------------------------
     */
    public string $storePath = '';  // Will be set to WRITEPATH . 'cache/' in constructor

    /**
     * --------------------------------------------------------------------------  
     * File Handler Mode
     * --------------------------------------------------------------------------
     */
    public int $mode = 0640;

    /**
     * --------------------------------------------------------------------------
     * Redis Handler Settings
     * --------------------------------------------------------------------------
     */
    public string $redisHost = '127.0.0.1';
    public ?string $redisAuth = null;
    public int $redisPort = 6379;
    public int $redisTimeout = 3;
    public int $redisDatabase = 0;

    /**
     * --------------------------------------------------------------------------
     * Memcached Handler Settings  
     * --------------------------------------------------------------------------
     */
    public array $memcached = [
        'host'   => '127.0.0.1',
        'port'   => 11211,
        'weight' => 1,
        'raw'    => false,
    ];

    /**
     * --------------------------------------------------------------------------
     * Web Page Caching
     * --------------------------------------------------------------------------
     */
    public bool $cacheQueryString = false;

    public array $responseVaryByHeaders = [
        'Accept',
        'Accept-Charset',
        'Accept-Encoding',
        'Accept-Language',
    ];

    public function __construct()
    {
        parent::__construct();

        // Set the storage path for file cache
        $this->storePath = WRITEPATH . 'cache/';

        // Environment-specific optimizations
        if (ENVIRONMENT === 'production') {
            // Use APCu if available, otherwise file
            if (extension_loaded('apcu') && apcu_enabled()) {
                $this->handler = 'apcu';
                $this->ttl = 3600; // 1 hour
            } else {
                $this->handler = 'file';  
                $this->ttl = 1800; // 30 minutes
            }
        } elseif (ENVIRONMENT === 'testing') {
            $this->handler = 'dummy';
            $this->ttl = 0;
        } else {
            // Development
            $this->handler = 'file';
            $this->ttl = 300; // 5 minutes
        }

        // Set Redis configuration from environment if available
        if (function_exists('env')) {
            $this->redisHost = env('cache.redis.host', '127.0.0.1');
            $this->redisAuth = env('cache.redis.password', null);
            $this->redisPort = env('cache.redis.port', 6379);
            $this->redisDatabase = env('cache.redis.database', 0);
            
            // Update memcached settings
            $this->memcached['host'] = env('cache.memcached.host', '127.0.0.1');
            $this->memcached['port'] = env('cache.memcached.port', 11211);
        }
    }
}