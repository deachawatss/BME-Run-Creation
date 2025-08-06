<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Simple Cache Configuration for BME4
 * 
 * Fallback cache configuration that uses only constant values
 * to avoid any PHP constant expression issues.
 */
class Cache extends BaseConfig
{
    /**
     * Primary Handler
     */
    public string $handler = 'file';

    /**
     * Backup Handler
     */
    public string $backupHandler = 'dummy';

    /**
     * Cache key prefix
     */
    public string $prefix = 'bme4_';

    /**
     * Default TTL (seconds)
     */
    public int $ttl = 300; // 5 minutes default

    /**
     * Reserved Characters
     */
    public string $reservedCharacters = '{}()/\@:';

    /**
     * Simple cache handlers - no dynamic values
     */
    public array $validHandlers = [
        'file' => [
            'handler' => 'file',
            'cachePath' => null, // Set in constructor
            'mode' => 0640,
        ],
        'apcu' => [
            'handler' => 'apcu',
            'storageKey' => 'CI_',
        ],
        'dummy' => [
            'handler' => 'dummy',
        ],
    ];

    /**
     * Cache query string optimization
     */
    public bool $cacheQueryString = false;

    /**
     * Response vary by headers
     */
    public array $responseVaryByHeaders = [
        'Accept',
        'Accept-Charset',
        'Accept-Encoding',
        'Accept-Language',
    ];

    public function __construct()
    {
        parent::__construct();

        // Set the file cache path dynamically
        $this->validHandlers['file']['cachePath'] = WRITEPATH . 'cache/';

        // Environment-specific settings
        if (ENVIRONMENT === 'production') {
            // Use APCu if available, otherwise file cache
            $this->handler = extension_loaded('apcu') && apcu_enabled() ? 'apcu' : 'file';
            $this->ttl = 3600; // 1 hour for production
        } elseif (ENVIRONMENT === 'testing') {
            $this->handler = 'dummy';
            $this->ttl = 0;
        } else {
            // Development
            $this->handler = 'file';
            $this->ttl = 300; // 5 minutes for development
        }
    }
}