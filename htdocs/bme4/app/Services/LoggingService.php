<?php

namespace App\Services;

use CodeIgniter\Log\Logger;

/**
 * Enhanced Logging Service
 * 
 * Provides structured logging with context, performance metrics, and security audit trails
 */
class LoggingService
{
    private Logger $logger;
    private array $context = [];
    
    public function __construct()
    {
        $this->logger = service('logger');
        $this->initializeContext();
    }
    
    /**
     * Initialize logging context
     */
    private function initializeContext(): void
    {
        $this->context = [
            'environment' => ENVIRONMENT,
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => uniqid('req_', true),
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'user_id' => session()->get('user_id') ?? session()->get('username') ?? 'anonymous'
        ];
    }
    
    /**
     * Log database operation
     */
    public function logDatabaseOperation(string $operation, string $table, array $data = [], float $executionTime = 0): void
    {
        $context = array_merge($this->context, [
            'operation_type' => 'database',
            'operation' => $operation,
            'table' => $table,
            'execution_time_ms' => round($executionTime * 1000, 2),
            'data_size' => count($data)
        ]);
        
        if ($executionTime > 1.0) {
            $this->logger->warning("Slow database operation: {$operation} on {$table}", $context);
        } else {
            $this->logger->info("Database operation: {$operation} on {$table}", $context);
        }
    }
    
    /**
     * Log security event
     */
    public function logSecurityEvent(string $event, string $severity = 'info', array $details = []): void
    {
        $context = array_merge($this->context, [
            'operation_type' => 'security', 
            'event' => $event,
            'severity' => $severity,
            'details' => $details
        ]);
        
        match($severity) {
            'critical' => $this->logger->critical("Security event: {$event}", $context),
            'error' => $this->logger->error("Security event: {$event}", $context),
            'warning' => $this->logger->warning("Security event: {$event}", $context),
            default => $this->logger->info("Security event: {$event}", $context)
        };
    }
    
    /**
     * Log performance metrics
     */
    public function logPerformance(string $operation, float $executionTime, array $metrics = []): void
    {
        $context = array_merge($this->context, [
            'operation_type' => 'performance',
            'operation' => $operation,
            'execution_time_ms' => round($executionTime * 1000, 2),
            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'memory_peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'metrics' => $metrics
        ]);
        
        if ($executionTime > 2.0) {
            $this->logger->warning("Slow operation: {$operation}", $context);
        } else {
            $this->logger->info("Performance: {$operation}", $context);
        }
    }
    
    /**
     * Log user action
     */
    public function logUserAction(string $action, array $data = []): void
    {
        $context = array_merge($this->context, [
            'operation_type' => 'user_action',
            'action' => $action,
            'data' => $data
        ]);
        
        $this->logger->info("User action: {$action}", $context);
    }
    
    /**
     * Log API request/response
     */
    public function logApiCall(string $endpoint, string $method, int $statusCode, float $responseTime, array $data = []): void
    {
        $context = array_merge($this->context, [
            'operation_type' => 'api',
            'endpoint' => $endpoint,
            'method' => $method,
            'status_code' => $statusCode,
            'response_time_ms' => round($responseTime * 1000, 2),
            'data' => $data
        ]);
        
        if ($statusCode >= 400) {
            $this->logger->error("API error: {$method} {$endpoint}", $context);
        } elseif ($responseTime > 1.0) {
            $this->logger->warning("Slow API call: {$method} {$endpoint}", $context);
        } else {
            $this->logger->info("API call: {$method} {$endpoint}", $context);
        }
    }
    
    /**
     * Log business logic events
     */
    public function logBusinessEvent(string $event, string $entity = '', array $data = []): void
    {
        $context = array_merge($this->context, [
            'operation_type' => 'business',
            'event' => $event,
            'entity' => $entity,
            'data' => $data
        ]);
        
        $this->logger->info("Business event: {$event}", $context);
    }
    
    /**
     * Log error with context
     */
    public function logError(\Exception $e, string $operation = '', array $additionalContext = []): void
    {
        $context = array_merge($this->context, $additionalContext, [
            'operation_type' => 'error',
            'operation' => $operation,
            'exception_class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        $this->logger->error("Exception in {$operation}: " . $e->getMessage(), $context);
    }
    
    /**
     * Log validation errors
     */
    public function logValidationError(array $errors, string $operation = '', array $data = []): void
    {
        $context = array_merge($this->context, [
            'operation_type' => 'validation',
            'operation' => $operation,
            'validation_errors' => $errors,
            'data' => $data
        ]);
        
        $this->logger->warning("Validation failed in {$operation}", $context);
    }
    
    /**
     * Log cache operations
     */
    public function logCacheOperation(string $operation, string $key, bool $hit = false, float $executionTime = 0): void
    {
        $context = array_merge($this->context, [
            'operation_type' => 'cache',
            'operation' => $operation,
            'cache_key' => $key,
            'cache_hit' => $hit,
            'execution_time_ms' => round($executionTime * 1000, 2)
        ]);
        
        $this->logger->debug("Cache operation: {$operation} for key {$key}", $context);
    }
    
    /**
     * Set additional context
     */
    public function setContext(string $key, mixed $value): void
    {
        $this->context[$key] = $value;
    }
    
    /**
     * Get current context
     */
    public function getContext(): array
    {
        return $this->context;
    }
    
    /**
     * Clear additional context (keep base context)
     */
    public function clearContext(): void
    {
        $this->initializeContext();
    }
}