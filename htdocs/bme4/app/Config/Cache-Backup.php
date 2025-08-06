<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Cache Configuration
 * 
 * Performance-optimized cache configuration for BME4 production environment.
 * Designed for maximum performance with fallback strategies.
 */
class Cache extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Primary Handler - APCu for maximum performance
     * --------------------------------------------------------------------------
     *
     * The name of the preferred handler that should be used. If for some reason
     * it is not available, the $backupHandler will be used in its place.
     */
    public string $handler = 'apcu';

    /**
     * --------------------------------------------------------------------------
     * Backup Handler - File cache as fallback
     * --------------------------------------------------------------------------
     *
     * The backup cache handler to use when the primary handler is not available.
     * This must be the key to one of the cache engines listed in the $validHandlers
     * array below.
     */
    public string $backupHandler = 'file';

    /**
     * --------------------------------------------------------------------------
     * Cache key prefix
     * --------------------------------------------------------------------------
     *
     * This string is added to all cache item names to help avoid collisions
     * if you run multiple applications with the same cache engine.
     */
    public string $prefix = 'bme4_';

    /**
     * --------------------------------------------------------------------------
     * Default TTL (Time To Live) - Production optimized
     * --------------------------------------------------------------------------
     *
     * The default number of seconds to save items when none is specified.
     * Warning: This is not used by framework handlers where 60 seconds is hard-coded,
     * but may be useful to projects and modules. This will replace the hard-coded
     * value in a future release.
     */
    public int $ttl = 3600; // 1 hour for production efficiency

    /**
     * --------------------------------------------------------------------------
     * Reserved Characters
     * --------------------------------------------------------------------------
     *
     * A string of reserved characters that cannot be used in keys or tags.
     * Strings that contain any of the characters will cause an exception.
     * Default: {}()/\@:
     *
     * NOTE: The default characters comply with the Memcached standard.
     */
    public string $reservedCharacters = '{}()/\@:';

    /**
     * --------------------------------------------------------------------------
     * Valid cache handlers and their configurations - Performance optimized
     * --------------------------------------------------------------------------
     *
     * The cache engines that are available for use, along with their settings.
     * The first one will be used as the default driver, with the second one
     * acting as a backup should the primary one fail to initialize.
     *
     * Available engines: apcu, file, memcached, redis, predis, wincache, dummy
     */
    public array $validHandlers = [
        // APCu - Fastest option for single server
        'apcu' => [
            'handler'       => 'apcu',
            'storageKey'    => 'CI_',
        ],

        // File cache - Reliable fallback
        'file' => [
            'handler'       => 'file',
            'cachePath'     => null, // Will be set in constructor
            'mode'          => 0640,
        ],

        // Redis - For distributed caching (if available)
        'redis' => [
            'handler'       => 'redis',
            'host'          => '127.0.0.1',
            'password'      => null,
            'port'          => 6379,
            'timeout'       => 3.0,
            'database'      => 0,
        ],

        // Memcached - Alternative distributed cache
        'memcached' => [
            'handler'  => 'memcached',
            'host'     => '127.0.0.1',
            'port'     => 11211,
            'weight'   => 1,
            'raw'      => false,
        ],

        // WinCache - Windows specific cache
        'wincache' => [
            'handler' => 'wincache',
        ],

        // Dummy cache - For testing only
        'dummy' => [
            'handler' => 'dummy',
        ],
    ];

    /**
     * --------------------------------------------------------------------------
     * Web Page Caching: Cache Vary By Headers - Performance optimization
     * --------------------------------------------------------------------------
     *
     * Whether to take into account all headers when calculating
     * the cache key for the response cache.
     * This is more performance intensive, but provides more options.
     */
    public bool $cacheQueryString = false;

    /**
     * --------------------------------------------------------------------------
     * Web Page Caching: Vary By Headers
     * --------------------------------------------------------------------------
     *
     * Which headers to take into account when calculating cache key.
     * If $cacheQueryString is true, then this is ignored.
     * Accept, Accept-Charset, Accept-Encoding, Accept-Language, Origin
     */
    public array $responseVaryByHeaders = [
        'Accept',
        'Accept-Charset', 
        'Accept-Encoding',
        'Accept-Language',
    ];

    /**
     * --------------------------------------------------------------------------
     * Performance Optimization Settings
     * --------------------------------------------------------------------------
     */

    /**
     * Cache warming configuration for production
     */
    public array $cacheWarming = [
        'enabled'           => true,
        'preload_routes'    => true,
        'preload_views'     => true,
        'preload_configs'   => true,
    ];

    /**
     * Cache tags for organized cache invalidation
     */
    public array $cacheTags = [
        'database'      => 3600,    // 1 hour
        'views'         => 7200,    // 2 hours  
        'api'           => 1800,    // 30 minutes
        'user_session'  => 3600,    // 1 hour
        'config'        => 86400,   // 24 hours
    ];

    /**
     * Environment-specific cache settings
     */
    public function __construct()
    {
        parent::__construct();

        // Set dynamic paths and environment values that can't be set in property declarations
        $this->validHandlers['file']['cachePath'] = WRITEPATH . 'cache/';
        
        // Set Redis configuration from environment variables
        $this->validHandlers['redis']['host'] = env('cache.redis.host', '127.0.0.1');
        $this->validHandlers['redis']['password'] = env('cache.redis.password', null);
        $this->validHandlers['redis']['port'] = env('cache.redis.port', 6379);
        $this->validHandlers['redis']['timeout'] = env('cache.redis.timeout', 3.0);
        $this->validHandlers['redis']['database'] = env('cache.redis.database', 0);
        
        // Set Memcached configuration from environment variables
        $this->validHandlers['memcached']['host'] = env('cache.memcached.host', '127.0.0.1');
        $this->validHandlers['memcached']['port'] = env('cache.memcached.port', 11211);
        $this->validHandlers['memcached']['weight'] = env('cache.memcached.weight', 1);

        // Production optimizations
        if (ENVIRONMENT === 'production') {
            // Use APCu primarily, fall back to file
            $this->handler = extension_loaded('apcu') && apcu_enabled() ? 'apcu' : 'file';
            $this->ttl = 3600; // 1 hour default TTL
            
            // Enable cache query string optimization
            $this->cacheQueryString = false; // Keep false for better performance
        } elseif (ENVIRONMENT === 'development') {
            // Development settings - shorter TTL
            $this->handler = 'file';
            $this->ttl = 300; // 5 minutes for development
        } elseif (ENVIRONMENT === 'testing') {
            // Testing environment
            $this->handler = 'dummy';
            $this->ttl = 0;
        }
    }
}