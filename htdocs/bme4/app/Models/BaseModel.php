<?php

namespace App\Models;

use App\Services\DatabaseService;
use CodeIgniter\Model;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

/**
 * Base Model Class
 * 
 * Provides common functionality for all models
 * Integrates with DatabaseService for consistent database operations
 */
abstract class BaseModel extends Model
{
    protected DatabaseService $dbService;
    
    // Common validation rules
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Soft delete support
    protected $useSoftDeletes = false;
    protected $deletedField = 'deleted_at';
    
    // Timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        $this->dbService = new DatabaseService();
    }
    
    /**
     * Get the database service instance
     */
    protected function getDbService(): DatabaseService
    {
        return $this->dbService;
    }
    
    /**
     * Execute a safe query with error handling
     */
    protected function safeQuery(string $sql, array $params = [], string $connection = 'default'): \CodeIgniter\Database\BaseResult
    {
        return $this->dbService->executeQuery($sql, $params, $connection);
    }
    
    /**
     * Execute a transaction safely
     */
    protected function safeTransaction(callable $callback, string $connection = 'default'): mixed
    {
        return $this->dbService->executeTransaction($callback, $connection);
    }
    
    /**
     * Get data from NWFTH database (primary)
     */
    protected function getFromNwfth(string $sql, array $params = []): \CodeIgniter\Database\BaseResult
    {
        return $this->safeQuery($sql, $params, 'nwfth_db');
    }
    
    /**
     * Get data from NWFTH2 database (secondary/read-only)
     */
    protected function getFromNwfth2(string $sql, array $params = []): \CodeIgniter\Database\BaseResult
    {
        return $this->safeQuery($sql, $params, 'nwfth2_db');
    }
    
    /**
     * Common validation for required fields
     */
    protected function validateRequired(array $data, array $requiredFields): array
    {
        $errors = [];
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[] = "Field '{$field}' is required";
            }
        }
        
        return $errors;
    }
    
    /**
     * Sanitize input data
     */
    protected function sanitizeData(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = trim(strip_tags($value));
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Log model operations
     */
    protected function logOperation(string $operation, array $data = []): void
    {
        log_message('info', static::class . " - {$operation}: " . json_encode($data));
    }
    
    /**
     * Get record with caching support
     */
    protected function getCachedRecord(string $cacheKey, callable $dataCallback, int $ttl = 300): mixed
    {
        $cache = \Config\Services::cache();
        
        $data = $cache->get($cacheKey);
        
        if ($data === null) {
            $data = $dataCallback();
            $cache->save($cacheKey, $data, $ttl);
        }
        
        return $data;
    }
    
    /**
     * Clear model cache
     */
    protected function clearModelCache(string $pattern = ''): void
    {
        $cache = \Config\Services::cache();
        
        if (empty($pattern)) {
            $pattern = strtolower(static::class) . '_*';
        }
        
        $cache->deleteMatching($pattern);
    }
    
    /**
     * Get model statistics 
     */
    public function getModelStats(): array
    {
        return [
            'table' => $this->table,
            'primary_key' => $this->primaryKey,
            'use_timestamps' => $this->useTimestamps,
            'use_soft_deletes' => $this->useSoftDeletes,
            'validation_rules' => count($this->validationRules),
            'total_records' => $this->countAllResults()
        ];
    }
}