<?php

namespace App\Services;

use CodeIgniter\Config\Services;
use CodeIgniter\Database\ConnectionInterface;
use Config\Database;

/**
 * Performance Monitoring and Optimization Service
 * 
 * Provides comprehensive performance monitoring, caching strategies,
 * and optimization utilities for BME4 application.
 */
class PerformanceService
{
    protected $cache;
    protected $logger;
    protected $db;
    protected $startTime;
    protected $memoryStart;

    public function __construct()
    {
        $this->cache = Services::cache();
        $this->logger = Services::logger();
        $this->db = Database::connect();
        $this->startTime = microtime(true);
        $this->memoryStart = memory_get_usage(true);
    }

    /**
     * =========================================================================
     * Query Caching and Optimization
     * =========================================================================
     */

    /**
     * Execute a cached database query
     * 
     * @param string $sql SQL query to execute
     * @param array $binds Query parameters
     * @param int $ttl Cache time-to-live in seconds (default: 1 hour)
     * @param string $tag Cache tag for organized invalidation
     * @return mixed Query results
     */
    public function cachedQuery(string $sql, array $binds = [], int $ttl = 3600, string $tag = 'database')
    {
        // Create unique cache key based on query and parameters
        $cacheKey = 'query_' . md5($sql . serialize($binds));
        
        // Try to get from cache first
        $result = $this->cache->get($cacheKey);
        
        if ($result === null) {
            $queryStart = microtime(true);
            
            // Execute query if not in cache
            $query = $this->db->query($sql, $binds);
            $result = $query ? $query->getResult() : [];
            
            $queryTime = microtime(true) - $queryStart;
            
            // Log slow queries (over 2 seconds)
            if ($queryTime > 2.0) {
                $this->logger->warning("Slow query detected", [
                    'query_time' => $queryTime,
                    'sql' => $sql,
                    'binds' => $binds
                ]);
            }
            
            // Cache the result
            $this->cache->save($cacheKey, $result, $ttl);
            
            // Tag cache entry for organized invalidation
            $this->tagCache($cacheKey, $tag);
            
            $this->logger->info("Database query executed and cached", [
                'cache_key' => $cacheKey,
                'query_time' => $queryTime,
                'result_count' => count($result)
            ]);
        } else {
            $this->logger->info("Database query served from cache", [
                'cache_key' => $cacheKey
            ]);
        }
        
        return $result;
    }

    /**
     * Invalidate cache by tag
     * 
     * @param string $tag Cache tag to invalidate
     * @return bool Success status
     */
    public function invalidateByTag(string $tag): bool
    {
        $tagKey = "cache_tag_{$tag}";
        $cacheKeys = $this->cache->get($tagKey, []);
        
        $invalidated = 0;
        foreach ($cacheKeys as $key) {
            if ($this->cache->delete($key)) {
                $invalidated++;
            }
        }
        
        // Clear the tag index
        $this->cache->delete($tagKey);
        
        $this->logger->info("Cache invalidated by tag", [
            'tag' => $tag,
            'keys_invalidated' => $invalidated
        ]);
        
        return $invalidated > 0;
    }

    /**
     * Tag a cache entry for organized invalidation
     */
    protected function tagCache(string $cacheKey, string $tag): void
    {
        $tagKey = "cache_tag_{$tag}";
        $taggedKeys = $this->cache->get($tagKey, []);
        $taggedKeys[] = $cacheKey;
        $this->cache->save($tagKey, array_unique($taggedKeys), 86400); // 24 hours
    }

    /**
     * =========================================================================
     * Application Performance Monitoring
     * =========================================================================
     */

    /**
     * Start performance monitoring for a specific operation
     * 
     * @param string $operation Operation name
     * @return array Tracking data
     */
    public function startMonitoring(string $operation): array
    {
        $tracking = [
            'operation' => $operation,
            'start_time' => microtime(true),
            'start_memory' => memory_get_usage(true),
            'peak_memory_start' => memory_get_peak_usage(true)
        ];
        
        // Store in cache for later retrieval
        $this->cache->save("perf_monitor_{$operation}", $tracking, 300); // 5 minutes
        
        return $tracking;
    }

    /**
     * End performance monitoring and log results
     * 
     * @param string $operation Operation name
     * @param array|null $additionalData Additional metrics to log
     * @return array Performance metrics
     */
    public function endMonitoring(string $operation, array $additionalData = []): array
    {
        $tracking = $this->cache->get("perf_monitor_{$operation}");
        
        if (!$tracking) {
            $this->logger->warning("Performance monitoring data not found", ['operation' => $operation]);
            return [];
        }
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        $peakMemory = memory_get_peak_usage(true);
        
        $metrics = [
            'operation' => $operation,
            'execution_time' => round($endTime - $tracking['start_time'], 4),
            'memory_used' => $endMemory - $tracking['start_memory'],
            'peak_memory_used' => $peakMemory - $tracking['peak_memory_start'],
            'memory_formatted' => [
                'used' => $this->formatBytes($endMemory - $tracking['start_memory']),
                'peak' => $this->formatBytes($peakMemory - $tracking['peak_memory_start']),
            ],
            'timestamp' => date('Y-m-d H:i:s'),
            'additional_data' => $additionalData
        ];
        
        // Log performance metrics
        $logLevel = $metrics['execution_time'] > 2.0 ? 'warning' : 'info';
        $this->logger->{$logLevel}("Performance metrics for {$operation}", $metrics);
        
        // Clean up tracking data
        $this->cache->delete("perf_monitor_{$operation}");
        
        return $metrics;
    }

    /**
     * =========================================================================
     * Database Connection Optimization
     * =========================================================================
     */

    /**
     * Get optimized database connection based on operation type
     * 
     * @param string $operation 'read' or 'write'
     * @return ConnectionInterface Database connection
     */
    public function getOptimizedConnection(string $operation = 'read'): ConnectionInterface
    {
        // For read operations, use the more stable TFCPILOT3 connection
        if ($operation === 'read') {
            try {
                $readDb = Database::connect('nwfth2_db'); // TFCPILOT3
                // Test connection health
                $readDb->query('SELECT 1')->getResult();
                return $readDb;
            } catch (\Exception $e) {
                $this->logger->warning("Read database connection failed, falling back to primary", [
                    'error' => $e->getMessage()
                ]);
                // Fall back to primary database
                return Database::connect('nwfth_db'); // TFCMOBILE
            }
        }
        
        // For write operations, always use TFCMOBILE
        return Database::connect('nwfth_db');
    }

    /**
     * Check database connection health
     * 
     * @param string $connectionName Database connection name
     * @return array Health status
     */
    public function checkDatabaseHealth(string $connectionName = 'default'): array
    {
        try {
            $db = Database::connect($connectionName);
            $start = microtime(true);
            
            // Execute simple query to test connection
            $result = $db->query('SELECT 1 as health_check')->getResult();
            
            $responseTime = microtime(true) - $start;
            
            return [
                'status' => 'healthy',
                'connection' => $connectionName,
                'response_time' => round($responseTime * 1000, 2), // Convert to milliseconds
                'timestamp' => date('Y-m-d H:i:s')
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'connection' => $connectionName,
                'error' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
    }

    /**
     * =========================================================================
     * Application-Level Caching
     * =========================================================================
     */

    /**
     * Cache application data with intelligent TTL
     * 
     * @param string $key Cache key
     * @param mixed $data Data to cache
     * @param string $category Data category (affects TTL)
     * @return bool Success status
     */
    public function cacheData(string $key, $data, string $category = 'default'): bool
    {
        $ttlMap = [
            'config' => 86400,      // 24 hours
            'user_data' => 3600,    // 1 hour
            'api_response' => 1800, // 30 minutes
            'database' => 3600,     // 1 hour
            'view' => 7200,         // 2 hours
            'default' => 1800       // 30 minutes
        ];
        
        $ttl = $ttlMap[$category] ?? $ttlMap['default'];
        
        $success = $this->cache->save("app_{$key}", $data, $ttl);
        
        if ($success) {
            $this->tagCache("app_{$key}", $category);
            $this->logger->info("Data cached successfully", [
                'key' => $key,
                'category' => $category,
                'ttl' => $ttl
            ]);
        }
        
        return $success;
    }

    /**
     * Retrieve cached application data
     * 
     * @param string $key Cache key
     * @param mixed $default Default value if not found
     * @return mixed Cached data or default
     */
    public function getCachedData(string $key, $default = null)
    {
        return $this->cache->get("app_{$key}", $default);
    }

    /**
     * =========================================================================
     * Utility Methods
     * =========================================================================
     */

    /**
     * Format bytes to human readable format
     * 
     * @param int $size Size in bytes
     * @param int $precision Number of decimal places
     * @return string Formatted size
     */
    protected function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }

    /**
     * Get current application performance statistics
     * 
     * @return array Performance statistics
     */
    public function getPerformanceStats(): array
    {
        $currentTime = microtime(true);
        $currentMemory = memory_get_usage(true);
        $peakMemory = memory_get_peak_usage(true);
        
        return [
            'execution_time' => round($currentTime - $this->startTime, 4),
            'memory_usage' => [
                'current' => $this->formatBytes($currentMemory),
                'peak' => $this->formatBytes($peakMemory),
                'used' => $this->formatBytes($currentMemory - $this->memoryStart)
            ],
            'cache_info' => [
                'handler' => get_class($this->cache),
                'is_supported' => $this->cache->isSupported()
            ],
            'database_connections' => [
                'default' => $this->checkDatabaseHealth('default'),
                'nwfth_db' => $this->checkDatabaseHealth('nwfth_db'),
                'nwfth2_db' => $this->checkDatabaseHealth('nwfth2_db')
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Clear all application caches
     * 
     * @return array Clear operation results
     */
    public function clearAllCaches(): array
    {
        $results = [];
        
        // Clear different cache categories
        $categories = ['config', 'user_data', 'api_response', 'database', 'view'];
        
        foreach ($categories as $category) {
            $cleared = $this->invalidateByTag($category);
            $results[$category] = $cleared;
        }
        
        // Clear CodeIgniter framework cache
        if (method_exists($this->cache, 'clean')) {
            $results['framework_cache'] = $this->cache->clean();
        }
        
        $this->logger->info("All caches cleared", $results);
        
        return $results;
    }
}