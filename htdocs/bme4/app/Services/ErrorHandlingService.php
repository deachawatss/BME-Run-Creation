<?php

namespace App\Services;

use App\Services\LoggingService;

/**
 * Error Handling Service
 * 
 * Centralized error handling with logging, monitoring, and user-friendly responses
 */
class ErrorHandlingService
{
    private LoggingService $logger;
    private array $errorMap;
    
    public function __construct()
    {
        $this->logger = new LoggingService();
        $this->initializeErrorMap();
    }
    
    /**
     * Initialize error code mapping
     */
    private function initializeErrorMap(): void
    {
        $this->errorMap = [
            // Database errors
            'DB001' => 'Database connection failed',
            'DB002' => 'Query execution failed', 
            'DB003' => 'Transaction failed',
            'DB004' => 'Constraint violation',
            'DB005' => 'Record not found',
            
            // Validation errors
            'VAL001' => 'Required field missing',
            'VAL002' => 'Invalid data format',
            'VAL003' => 'Data length exceeded',
            'VAL004' => 'Invalid data type',
            'VAL005' => 'Business rule violation',
            
            // Authentication/Authorization errors
            'AUTH001' => 'Authentication required',
            'AUTH002' => 'Invalid credentials',
            'AUTH003' => 'Access denied',
            'AUTH004' => 'Session expired',
            'AUTH005' => 'Permission denied',
            
            // File/Upload errors
            'FILE001' => 'File upload failed',
            'FILE002' => 'Invalid file type',
            'FILE003' => 'File size exceeded',
            'FILE004' => 'File not found',
            'FILE005' => 'File permission denied',
            
            // API/Network errors
            'API001' => 'Invalid request format',
            'API002' => 'Rate limit exceeded',
            'API003' => 'External service unavailable',
            'API004' => 'Timeout occurred',
            'API005' => 'Invalid API key',
            
            // Business logic errors
            'BUS001' => 'Business rule violation',
            'BUS002' => 'Workflow error',
            'BUS003' => 'Data integrity error',
            'BUS004' => 'Calculation error',
            'BUS005' => 'State transition error',
            
            // System errors
            'SYS001' => 'System maintenance mode',
            'SYS002' => 'Resource exhausted',
            'SYS003' => 'Configuration error',
            'SYS004' => 'Service unavailable',
            'SYS005' => 'Internal server error'
        ];
    }
    
    /**
     * Handle exception with appropriate response
     */
    public function handleException(\Exception $e, string $operation = '', array $context = []): array
    {
        $errorCode = $this->determineErrorCode($e);
        $userMessage = $this->getUserFriendlyMessage($errorCode, $e);
        
        // Log the error
        $this->logger->logError($e, $operation, array_merge($context, [
            'error_code' => $errorCode,
            'user_message' => $userMessage
        ]));
        
        // Determine if we should send detailed error info
        $includeDetails = ENVIRONMENT === 'development';
        
        $response = [
            'success' => false,
            'error' => true,
            'error_code' => $errorCode,
            'message' => $userMessage,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        if ($includeDetails) {
            $response['debug'] = [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
        }
        
        return $response;
    }
    
    /**
     * Handle validation errors
     */
    public function handleValidationErrors(array $errors, string $operation = ''): array
    {
        $this->logger->logValidationError($errors, $operation);
        
        return [
            'success' => false,
            'error' => true,
            'error_code' => 'VAL001',
            'message' => 'Validation failed',
            'validation_errors' => $errors,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Handle database errors specifically
     */
    public function handleDatabaseError(\Exception $e, string $operation = '', array $context = []): array
    {
        $errorCode = 'DB002'; // Default to query execution failed
        
        // Analyze exception for specific database errors
        $message = strtolower($e->getMessage());
        if (strpos($message, 'connection') !== false) {
            $errorCode = 'DB001';
        } elseif (strpos($message, 'constraint') !== false || strpos($message, 'foreign key') !== false) {
            $errorCode = 'DB004';
        } elseif (strpos($message, 'not found') !== false || strpos($message, 'no rows') !== false) {
            $errorCode = 'DB005';
        }
        
        $this->logger->logDatabaseOperation($operation, $context['table'] ?? 'unknown', $context);
        $this->logger->logError($e, $operation, ['error_code' => $errorCode]);
        
        return [
            'success' => false,
            'error' => true,
            'error_code' => $errorCode,
            'message' => $this->getUserFriendlyMessage($errorCode, $e),
            'timestamp' => date('Y-m-d H:i:s')
        ];  
    }
    
    /**
     * Handle authentication errors
     */
    public function handleAuthError(string $errorType = 'AUTH001', string $details = ''): array
    {
        $this->logger->logSecurityEvent('authentication_failed', 'warning', [
            'error_type' => $errorType,
            'details' => $details
        ]);
        
        return [
            'success' => false,
            'error' => true,
            'error_code' => $errorType,
            'message' => $this->errorMap[$errorType] ?? 'Authentication error',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Determine error code from exception
     */
    private function determineErrorCode(\Exception $e): string
    {
        $className = get_class($e);
        $message = strtolower($e->getMessage());
        
        // Map exception types to error codes
        if (strpos($className, 'Database') !== false) {
            if (strpos($message, 'connection') !== false) return 'DB001';
            if (strpos($message, 'constraint') !== false) return 'DB004';
            return 'DB002';
        }
        
        if (strpos($className, 'Validation') !== false || strpos($className, 'InvalidArgument') !== false) {
            return 'VAL002';
        }
        
        if (strpos($className, 'Auth') !== false || strpos($message, 'permission') !== false) {
            return 'AUTH003';
        }
        
        if (strpos($className, 'File') !== false || strpos($message, 'file') !== false) {
            return 'FILE001';
        }
        
        // Default to system error
        return 'SYS005';
    }
    
    /**
     * Get user-friendly error message
     */
    private function getUserFriendlyMessage(string $errorCode, \Exception $e): string
    {
        $baseMessage = $this->errorMap[$errorCode] ?? 'An error occurred';
        
        // In development, include more details
        if (ENVIRONMENT === 'development') {
            return $baseMessage . ': ' . $e->getMessage();
        }
        
        // In production, keep messages generic for security
        return $baseMessage;
    }
    
    /**
     * Log performance warning
     */
    public function logPerformanceWarning(string $operation, float $executionTime, array $metrics = []): void
    {
        $this->logger->logPerformance($operation, $executionTime, $metrics);
        
        if ($executionTime > 5.0) {
            $this->logger->logSecurityEvent('performance_degradation', 'warning', [
                'operation' => $operation,
                'execution_time' => $executionTime,
                'metrics' => $metrics
            ]);
        }
    }
    
    /**
     * Create custom error response
     */
    public function createErrorResponse(string $errorCode, string $message = '', array $data = []): array
    {
        return [
            'success' => false,
            'error' => true,
            'error_code' => $errorCode,
            'message' => $message ?: ($this->errorMap[$errorCode] ?? 'Unknown error'),
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Get error map for reference
     */
    public function getErrorMap(): array
    {
        return $this->errorMap;
    }
}