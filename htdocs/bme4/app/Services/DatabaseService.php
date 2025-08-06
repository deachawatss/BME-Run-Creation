<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Config;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * Database Service
 * 
 * Centralizes database operations and connection management
 * Provides consistent interface for multi-database operations
 */
class DatabaseService
{
    private ?BaseConnection $defaultDb = null;
    private ?BaseConnection $nwfthDb = null;
    private ?BaseConnection $nwfth2Db = null;
    
    private array $connections = [];
    
    public function __construct()
    {
        // Initialize connections lazily
    }
    
    /**
     * Get default database connection (TFCMOBILE - Write operations)
     */
    public function getDefaultDb(): BaseConnection
    {
        if ($this->defaultDb === null) {
            $this->defaultDb = \Config\Database::connect('default');
            $this->connections['default'] = $this->defaultDb;
        }
        
        return $this->defaultDb;
    }
    
    /**
     * Get NWFTH database connection (Primary data source)
     */
    public function getNwfthDb(): BaseConnection
    {
        if ($this->nwfthDb === null) {
            $this->nwfthDb = \Config\Database::connect('nwfth_db');
            $this->connections['nwfth_db'] = $this->nwfthDb;
        }
        
        return $this->nwfthDb;
    }
    
    /**
     * Get NWFTH2 database connection (Secondary data source - Read operations)
     */
    public function getNwfth2Db(): BaseConnection
    {
        if ($this->nwfth2Db === null) {
            $this->nwfth2Db = \Config\Database::connect('nwfth2_db');
            $this->connections['nwfth2_db'] = $this->nwfth2Db;
        }
        
        return $this->nwfth2Db;
    }
    
    /**
     * Execute query with error handling and logging
     */
    public function executeQuery(string $sql, array $params = [], string $connection = 'default'): \CodeIgniter\Database\BaseResult
    {
        try {
            $db = $this->getConnection($connection);
            return $db->query($sql, $params);
        } catch (DatabaseException $e) {
            log_message('error', 'Database query failed: ' . $e->getMessage());
            log_message('error', 'SQL: ' . $sql);
            log_message('error', 'Params: ' . json_encode($params));
            throw $e;
        }
    }
    
    /**
     * Execute transaction with automatic rollback on failure
     */
    public function executeTransaction(callable $callback, string $connection = 'default'): mixed
    {
        $db = $this->getConnection($connection);
        
        $db->transStart();
        
        try {
            $result = $callback($db);
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new DatabaseException('Transaction failed');
            }
            
            return $result;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Transaction failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get connection by name
     */
    private function getConnection(string $name): BaseConnection
    {
        return match ($name) {
            'default' => $this->getDefaultDb(),
            'nwfth_db' => $this->getNwfthDb(),
            'nwfth2_db' => $this->getNwfth2Db(),
            default => throw new \InvalidArgumentException("Unknown connection: {$name}")
        };
    }
    
    /**
     * Close all connections
     */
    public function closeConnections(): void
    {
        foreach ($this->connections as $connection) {
            if ($connection) {
                $connection->close();
            }
        }
        
        $this->connections = [];
        $this->defaultDb = null;
        $this->nwfthDb = null;
        $this->nwfth2Db = null;
    }
    
    /**
     * Get database statistics
     */
    public function getStats(): array
    {
        $stats = [];
        
        foreach ($this->connections as $name => $connection) {
            if ($connection) {
                $stats[$name] = [
                    'connected' => true,
                    'platform' => $connection->getPlatform(),
                    'version' => $connection->getVersion(),
                    'last_query' => $connection->getLastQuery()
                ];
            }
        }
        
        return $stats;
    }
    
    /**
     * Health check for all connections
     */
    public function healthCheck(): array
    {
        $health = [];
        
        $connections = ['default', 'nwfth_db', 'nwfth2_db'];
        
        foreach ($connections as $name) {
            try {
                $db = $this->getConnection($name);
                $result = $db->query('SELECT 1 as test');
                $health[$name] = [
                    'status' => 'healthy',
                    'response_time' => microtime(true)
                ];
            } catch (\Exception $e) {
                $health[$name] = [
                    'status' => 'unhealthy',
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $health;
    }
}