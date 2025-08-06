<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Services\DatabaseService;
use App\Services\LoggingService;
use App\Services\ErrorHandlingService;

/**
 * Enhanced Base Controller
 * 
 * Provides common functionality for all controllers
 * Integrates with DatabaseService and provides consistent error handling
 */
abstract class BaseController extends Controller
{
    protected DatabaseService $dbService;
    protected LoggingService $loggingService;
    protected ErrorHandlingService $errorHandler;
    
    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['form', 'url', 'session'];

    
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Initialize services
        $this->dbService = new DatabaseService();
        $this->loggingService = new LoggingService();
        $this->errorHandler = new ErrorHandlingService();
    }
    
    /**
     * Validate AJAX request
     */
    protected function validateAjaxRequest(): bool
    {
        return $this->request->isAJAX();
    }
    
    /**
     * Validate user authentication
     */
    protected function validateAuth(): bool
    {
        return session()->get('logged_in') === true;
    }
    
    /**
     * Redirect to login if not authenticated
     */
    protected function requireAuth(): ?ResponseInterface
    {
        if (!$this->validateAuth()) {
            return redirect()->to(base_url('auth/login'));
        }
        return null;
    }
    
    /**
     * Return JSON response with consistent format
     */
    protected function jsonResponse(array $data, int $statusCode = 200): ResponseInterface
    {
        return $this->response
                    ->setStatusCode($statusCode)
                    ->setJSON($data);
    }
    
    /**
     * Return success JSON response
     */
    protected function jsonSuccess(array $data = [], string $message = 'Success'): ResponseInterface
    {
        return $this->jsonResponse(array_merge([
            'success' => true,
            'message' => $message
        ], $data));
    }
    
    /**
     * Return error JSON response
     */
    protected function jsonError(string $message = 'Error occurred', array $errors = [], int $statusCode = 400): ResponseInterface
    {
        $data = [
            'success' => false,
            'error' => true,
            'message' => $message
        ];
        
        if (!empty($errors)) {
            $data['errors'] = $errors;
        }
        
        return $this->jsonResponse($data, $statusCode);
    }
    
    /**
     * Handle exceptions consistently
     */
    protected function handleException(\Exception $e, string $operation = 'operation'): ResponseInterface
    {
        $errorResponse = $this->errorHandler->handleException($e, $operation, [
            'controller' => static::class,
            'method' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? 'unknown'
        ]);
        
        return $this->jsonResponse($errorResponse, 500);
    }
    
    /**
     * Get DataTable parameters from request
     */
    protected function getDataTableParams(): array
    {
        return [
            'draw' => $this->request->getPost('draw') ?? 1,
            'start' => (int)($this->request->getPost('start') ?? 0),  
            'length' => (int)($this->request->getPost('length') ?? 25),
            'search' => $this->request->getPost('search')['value'] ?? '',
            'order' => $this->request->getPost('order') ?? [],
            'columns' => $this->request->getPost('columns') ?? []
        ];
    }
    
    /**
     * Format DataTable response
     */
    protected function formatDataTableResponse(array $data, int $totalRecords, int $filteredRecords, int $draw): array
    {
        return [
            'draw' => (int)$draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];
    }
    
    /**
     * Validate request data with rules
     */
    protected function validateRequest(array $rules, array $messages = []): array
    {
        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);
        
        if (!$validation->withRequest($this->request)->run()) {
            throw new \InvalidArgumentException('Validation failed: ' . implode(', ', $validation->getErrors()));
        }
        
        return $validation->getValidated();
    }
    
    /**
     * Get current user information
     */
    protected function getCurrentUser(): array
    {
        return [
            'id' => session()->get('user_id') ?? session()->get('username') ?? 'admin',
            'username' => session()->get('username') ?? 'admin',
            'name' => session()->get('name') ?? 'Administrator'
        ];
    }
    
    /**
     * Common view data
     */
    protected function getBaseViewData(string $title, string $pageTitle = '', array $breadcrumb = []): array
    {
        return [
            'title' => $title,
            'page_title' => $pageTitle ?: $title,
            'breadcrumb' => array_merge([
                ['title' => 'Home', 'url' => base_url()]
            ], $breadcrumb),
            'current_user' => $this->getCurrentUser(),
            'assets_version' => time() // For cache busting
        ];
    }
    
    /**
     * Log controller operation
     */
    protected function logOperation(string $operation, array $data = []): void
    {
        $this->loggingService->logUserAction($operation, array_merge([
            'controller' => static::class,
            'method' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? 'unknown'
        ], $data));
    }
    
    /**
     * Log performance metrics
     */
    protected function logPerformance(string $operation, float $startTime, array $metrics = []): void
    {
        $executionTime = microtime(true) - $startTime;
        $this->loggingService->logPerformance($operation, $executionTime, $metrics);
        
        if ($executionTime > 2.0) {
            $this->errorHandler->logPerformanceWarning($operation, $executionTime, $metrics);
        }
    }
}
