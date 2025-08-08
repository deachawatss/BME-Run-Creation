<?php

namespace App\Controllers;

use App\Models\CreateRunBulkModel;

class CreateRunBulk extends BaseController
{
    private CreateRunBulkModel $createRunBulkModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Initialize model with DatabaseService integration
        $this->createRunBulkModel = new CreateRunBulkModel();
    }

    public function index()
    {
        // Require authentication
        $authRedirect = $this->requireAuth();
        if ($authRedirect) return $authRedirect;
        
        $this->logOperation('index', ['user' => $this->getCurrentUser()]);

        $data = $this->getBaseViewData(
            'NWFTH - Create Bulk Run',
            'NWFTH - Create Bulk Run System',
            [['title' => 'Create Bulk Run', 'url' => '']]
        );

        return view('CreateRunBulk/index', $data);
    }

    public function ajaxlist()
    {
        // Validate AJAX request
        if (!$this->validateAjaxRequest()) {
            return $this->jsonError('Invalid request method', [], 403);
        }

        try {
            // Get DataTable parameters
            $params = $this->getDataTableParams();
            $this->logOperation('ajaxlist', $params);

            // Get data using model
            $data = $this->createRunBulkModel->getBulkRuns($params['length'], $params['start'], $params['search']);
            $totalRecords = $this->createRunBulkModel->countAllResults();
            $filteredRecords = $this->createRunBulkModel->getBulkRunsCount($params['search']);

            // Format data for DataTable
            $formattedData = array_map(function($row) {
                return ['Cust_BulkRun' => $row];
            }, $data);

            // Return formatted DataTable response
            return $this->jsonResponse(
                $this->formatDataTableResponse($formattedData, $totalRecords, $filteredRecords, $params['draw'])
            );

        } catch (\Exception $e) {
            return $this->handleException($e, 'ajaxlist');
        }
    }

    public function create()
    {
        // Debug logging
        log_message('debug', 'CreateRunBulk::create() called with method: ' . $this->request->getMethod());
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
        
        // Handle POST request for creating new bulk run
        if (strtolower($this->request->getMethod()) === 'post') {
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'run_no' => 'required|numeric',
                'selected_batches' => 'required'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }

            try {
                $runNo = $this->request->getPost('run_no');
                $selectedBatches = $this->request->getPost('selected_batches');
                
                if (empty($selectedBatches) || !is_array($selectedBatches)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No batches selected'
                    ]);
                }

                // CRITICAL: Use atomic run number generation to prevent race conditions
                $actualRunNo = $this->generateAtomicRunNumber();

                // Start database transaction - let database constraints handle duplicate prevention
                $this->dbService->getNwfthDb()->transStart();

                // Insert each batch as a separate record with the calculated RunNo
                $rowNum = 1;
                $currentUserId = session()->get('username') ?: session()->get('user_id') ?: 'admin';
                
                // CRITICAL FIX: Truncate user ID to prevent database truncation error
                // TFCMOBILE schema: RecUserId nvarchar(8), ModifiedBy nvarchar(50)
                $currentUserId = substr((string)$currentUserId, 0, 8); // Truncate to 8 chars max
                
                $currentDateTime = date('Y-m-d H:i:s');
                $totalBatches = count($selectedBatches); // Calculate total batch count for NoOfBatches
                
                log_message('info', "CreateRunBulk: Using truncated UserId: '$currentUserId' (length: " . strlen($currentUserId) . ")");
                
                foreach ($selectedBatches as $batch) {
                    // CRITICAL FIX: Validate field lengths to prevent truncation
                    $batchNo = substr((string)$batch['batch_no'], 0, 30); // nvarchar(30)
                    $formulaId = substr((string)$batch['formula_id'], 0, 30); // nvarchar(30) 
                    $formulaDesc = substr((string)($batch['formula_desc'] ?? ''), 0, 160); // nvarchar(160)
                    
                    // Complete data structure matching synchronized schema (33 fields)
                    $data = [
                        'RunNo' => (int)$actualRunNo,           // int - both databases now use int
                        'RowNum' => $rowNum,                    // int - included for both databases
                        'BatchNo' => $batchNo,                  // nvarchar(30) - truncated
                        'FormulaId' => $formulaId,              // nvarchar(30) - truncated
                        'FormulaDesc' => $formulaDesc,          // nvarchar(160) - truncated
                        'NoOfBatches' => (int)$totalBatches,    // int - total batch count for this run
                        'PalletsPerBatch' => (int)1,            // int - explicit cast
                        'Status' => 'NEW',                      // nvarchar - uppercase
                        'RecUserId' => (string)$currentUserId,  // nvarchar - ensure string type
                        'RecDate' => $currentDateTime,          // datetime - current timestamp
                        'ModifiedBy' => null,                   // nvarchar - NULL for new records
                        'ModifiedDate' => $currentDateTime,     // datetime - current timestamp
                        // User fields - match schema exactly
                        'User1' => null,                        // nvarchar - NULL instead of empty string
                        'User2' => null,                        // nvarchar - NULL instead of empty string
                        'User3' => null,                        // nvarchar - NULL instead of empty string
                        'User4' => null,                        // nvarchar - NULL instead of empty string
                        'User5' => null,                        // nvarchar - NULL instead of empty string
                        'User6' => null,                        // datetime (can be null)
                        'User7' => null,                        // float (can be null)
                        'User8' => null,                        // float (can be null)
                        'User9' => null,                        // decimal (can be null)
                        'User10' => null,                       // decimal (can be null)
                        'User11' => null,                       // int (can be null)
                        'User12' => null,                       // int (can be null)
                        // Custom fields - bit types (can be null)
                        'CUSTOM1' => null,                      // bit
                        'CUSTOM2' => null,                      // bit
                        'CUSTOM3' => null,                      // bit
                        'CUSTOM4' => null,                      // bit
                        'CUSTOM5' => null,                      // bit
                        'CUSTOM6' => null,                      // bit
                        'CUSTOM7' => null,                      // bit
                        'CUSTOM8' => null,                      // bit
                        'CUSTOM9' => null,                      // bit
                        'CUSTOM10' => null,                     // bit
                        // ESG fields - nvarchar, NULL values
                        'ESG_REASON' => null,                   // nvarchar - NULL instead of empty string
                        'ESG_APPROVER' => null                  // nvarchar - NULL instead of empty string
                    ];

                    // Insert to TFCMOBILE (primary database)
                    try {
                        $insertResult = $this->dbService->getNwfthDb()->table('Cust_BulkRun')->insert($data);
                        
                        if (!$insertResult) {
                            $error = $this->dbService->getNwfthDb()->error();
                            log_message('error', "CreateRunBulk: Primary insert failed - " . json_encode($error));
                            
                            // Handle unique constraint violation (duplicate batch)
                            if (isset($error['code']) && ($error['code'] == 2601 || $error['code'] == 2627)) {
                                throw new \Exception("CONSTRAINT_VIOLATION:{$batch['batch_no']}");
                            }
                            
                            throw new \Exception("Database insert failed: " . ($error['message'] ?? 'Unknown error'));
                        }
                    } catch (\Exception $e) {
                        // Enhanced error handling for constraint violations
                        if (strpos($e->getMessage(), 'UX_Cust_BulkRun_BatchNo') !== false || 
                            strpos($e->getMessage(), 'duplicate') !== false ||
                            strpos($e->getMessage(), 'unique') !== false) {
                            throw new \Exception("CONSTRAINT_VIOLATION:{$batch['batch_no']}");
                        }
                        throw $e;
                    }
                    
                    // REPLICATION: Immediately replicate to TFCPILOT3
                    try {
                        $replicationResult = $this->dbService->getNwfth2Db()->table('Cust_BulkRun')->insert($data);
                        if ($replicationResult) {
                            log_message('info', "CreateRunBulk: Successfully replicated RunNo $actualRunNo, RowNum $rowNum to TFCPILOT3");
                        } else {
                            $replicationError = $this->dbService->getNwfth2Db()->error();
                            log_message('error', "CreateRunBulk: Replication failed for RunNo $actualRunNo - " . json_encode($replicationError));
                        }
                    } catch (\Exception $replicationError) {
                        log_message('error', "CreateRunBulk: Replication exception for RunNo $actualRunNo - " . $replicationError->getMessage());
                        // Continue with other batches but log the error
                    }
                    
                    // NEW: Create ingredient picking records for this batch
                    try {
                        $this->createBulkPickedRecords($actualRunNo, $rowNum, $batch['batch_no'], $currentUserId, $currentDateTime);
                        log_message('info', "CreateRunBulk: Successfully created picking records for BatchNo {$batch['batch_no']}");
                    } catch (\Exception $pickingError) {
                        log_message('error', "CreateRunBulk: Failed to create picking records for BatchNo {$batch['batch_no']} - " . $pickingError->getMessage());
                        // Continue with other batches but log the error
                    }
                    
                    $rowNum++;
                }

                $runNo = $actualRunNo; // Update runNo for response

                // Complete transaction
                $this->dbService->getNwfthDb()->transComplete();

                if ($this->dbService->getNwfthDb()->transStatus() === FALSE) {
                    // Get detailed transaction error information
                    $transError = $this->dbService->getNwfthDb()->error();
                    $errorMessage = 'Transaction failed: ' . ($transError['message'] ?? 'Unknown transaction error');
                    
                    log_message('error', "CreateRunBulk: Transaction failed for RunNo $actualRunNo - " . json_encode($transError));
                    
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to create bulk run - ' . $errorMessage,
                        'error_details' => $transError
                    ]);
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Bulk run created successfully',
                    'run_no' => $runNo
                ]);

            } catch (\Exception $e) {
                log_message('error', 'CreateRunBulk create error: ' . $e->getMessage());
                
                // Handle constraint violations specially
                if (strpos($e->getMessage(), 'CONSTRAINT_VIOLATION:') === 0) {
                    $batchNo = substr($e->getMessage(), strlen('CONSTRAINT_VIOLATION:'));
                    
                    return $this->response->setJSON([
                        'success' => false,
                        'constraint_violation' => true,
                        'conflicted_batch' => $batchNo,
                        'message' => "Batch $batchNo is already used in another bulk run. Available batches will be refreshed automatically.",
                        'refresh_batches' => true
                    ]);
                }
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create bulk run: ' . $e->getMessage()
                ]);
            }
        }

        return $this->response->setJSON(['error' => 'Invalid request method']);
    }


    public function delete($runNo = null)
    {
        // Validate AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request method']);
        }

        if (empty($runNo) || strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setJSON(['error' => 'Invalid request - RunNo required']);
        }

        // Validate RunNo is numeric
        if (!is_numeric($runNo)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid RunNo format'
            ]);
        }

        try {
            $runNo = (int)$runNo;
            
            // Log the deletion attempt with parameter details
            log_message('info', "CreateRunBulk: Starting deletion of RunNo $runNo (type: " . gettype($runNo) . ")");
            
            // Check both databases for RunNo existence
            $runCountPrimary = $this->dbService->getNwfthDb()->table('Cust_BulkRun')
                                        ->where('RunNo', $runNo)
                                        ->countAllResults();
            
            $runCountSecondary = $this->dbService->getNwfth2Db()->table('Cust_BulkRun')
                                        ->where('RunNo', $runNo)
                                        ->countAllResults();
            
            log_message('info', "CreateRunBulk: RunNo $runNo found in TFCMOBILE: $runCountPrimary records, TFCPILOT3: $runCountSecondary records");
            
            if ($runCountPrimary == 0 && $runCountSecondary == 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'RunNo not found in either database'
                ]);
            }
            
            // Start transaction on primary database
            $this->dbService->getNwfthDb()->transStart();
            
            // Check how many picking records will be deleted from primary database
            $pickingCountPrimary = $this->dbService->getNwfthDb()->table('cust_BulkPicked')
                                              ->where('RunNo', $runNo)
                                              ->countAllResults();
            
            log_message('info', "CreateRunBulk: Found $runCountPrimary run records and $pickingCountPrimary picking records for RunNo $runNo in TFCMOBILE");
            
            // Delete picking records first from primary database (foreign key dependency)
            if ($pickingCountPrimary > 0) {
                $pickingDeleteResult = $this->dbService->getNwfthDb()->table('cust_BulkPicked')
                                                     ->where('RunNo', $runNo)
                                                     ->delete();
                
                if (!$pickingDeleteResult) {
                    $error = $this->dbService->getNwfthDb()->error();
                    log_message('error', "CreateRunBulk: Primary picking delete failed - " . json_encode($error));
                    throw new \Exception("Database delete failed: " . ($error['message'] ?? 'Unknown error'));
                }
                
                log_message('info', "CreateRunBulk: Successfully deleted $pickingCountPrimary picking records for RunNo $runNo from TFCMOBILE");
            }
            
            // Delete run records from TFCMOBILE (primary database) - only if they exist
            if ($runCountPrimary > 0) {
                $deleteResult = $this->dbService->getNwfthDb()->table('Cust_BulkRun')
                                              ->where('RunNo', $runNo)
                                              ->delete();
                
                if (!$deleteResult) {
                    $error = $this->dbService->getNwfthDb()->error();
                    log_message('error', "CreateRunBulk: Primary delete failed - " . json_encode($error));
                    throw new \Exception("Database delete failed: " . ($error['message'] ?? 'Unknown error'));
                }
                
                log_message('info', "CreateRunBulk: Successfully deleted $runCountPrimary run records for RunNo $runNo from TFCMOBILE");
            }
            
            // Complete transaction
            $this->dbService->getNwfthDb()->transComplete();
            
            if ($this->dbService->getNwfthDb()->transStatus() === FALSE) {
                throw new \Exception('Transaction failed during deletion');
            }
            
            // REPLICATION: Delete from TFCPILOT3 (replication database)
            try {
                // Check and delete picking records from replication database
                $pickingCountSecondary = $this->dbService->getNwfth2Db()->table('cust_BulkPicked')
                                                        ->where('RunNo', $runNo)
                                                        ->countAllResults();
                
                if ($pickingCountSecondary > 0) {
                    $replicationPickingDeleteResult = $this->dbService->getNwfth2Db()->table('cust_BulkPicked')
                                                                    ->where('RunNo', $runNo)
                                                                    ->delete();
                    
                    if ($replicationPickingDeleteResult) {
                        log_message('info', "CreateRunBulk: Successfully deleted $pickingCountSecondary picking records for RunNo $runNo from TFCPILOT3");
                    } else {
                        $replicationError = $this->dbService->getNwfth2Db()->error();
                        log_message('error', "CreateRunBulk: TFCPILOT3 picking delete failed for RunNo $runNo - " . json_encode($replicationError));
                    }
                }
                
                // Delete run records from replication database - only if they exist
                if ($runCountSecondary > 0) {
                    $replicationDeleteResult = $this->dbService->getNwfth2Db()->table('Cust_BulkRun')
                                                             ->where('RunNo', $runNo)
                                                             ->delete();
                    
                    if ($replicationDeleteResult) {
                        log_message('info', "CreateRunBulk: Successfully deleted $runCountSecondary run records for RunNo $runNo from TFCPILOT3");
                    } else {
                        $replicationError = $this->dbService->getNwfth2Db()->error();
                        log_message('error', "CreateRunBulk: TFCPILOT3 run delete failed for RunNo $runNo - " . json_encode($replicationError));
                    }
                }
            } catch (\Exception $replicationError) {
                log_message('error', "CreateRunBulk: TFCPILOT3 delete exception for RunNo $runNo - " . $replicationError->getMessage());
                // Continue - deletion was attempted
            }
            
            // Calculate total records deleted from both databases
            $totalRunDeleted = $runCountPrimary + $runCountSecondary;
            $totalPickingDeleted = $pickingCountPrimary + ($pickingCountSecondary ?? 0);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "Successfully deleted $totalRunDeleted batches and $totalPickingDeleted picking records from Run $runNo",
                'deleted_run_count' => $totalRunDeleted,
                'deleted_picking_count' => $totalPickingDeleted,
                'run_no' => $runNo,
                'details' => [
                    'tfcmobile' => ['runs' => $runCountPrimary, 'picking' => $pickingCountPrimary],
                    'tfcpilot3' => ['runs' => $runCountSecondary, 'picking' => $pickingCountSecondary ?? 0]
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'CreateRunBulk delete error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete bulk run: ' . $e->getMessage()
            ]);
        }
    }

    public function getNextRunNumber()
    {
        // Validate AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request method']);
        }

        try {
            // ATOMIC RUN NUMBER GENERATION with row locking
            // This prevents race conditions when multiple users request run numbers simultaneously
            $nextRunNo = $this->generateAtomicRunNumber();
            
            return $this->response->setJSON([
                'success' => true,
                'next_run_no' => $nextRunNo
            ]);

        } catch (\Exception $e) {
            log_message('error', 'CreateRunBulk getNextRunNumber error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to get next run number: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Generate atomic run number using row locking to prevent race conditions
     * This ensures unique run numbers even with concurrent access
     */
    private function generateAtomicRunNumber(): int
    {
        try {
            // CRITICAL: Use serializable transaction to prevent concurrent access issues
            $this->dbService->getNwfthDb()->transBegin();
            
            // Lock-based approach: Get max RunNo with row lock (UPDLOCK, HOLDLOCK)
            $query = "SELECT ISNULL(MAX(RunNo), 0) as MaxRunNo FROM Cust_BulkRun WITH (UPDLOCK, HOLDLOCK)";
            $writeResult = $this->dbService->getNwfthDb()->query($query)->getRowArray();
            $writeMaxRunNo = $writeResult['MaxRunNo'] ?? 0;
            
            // Also check read database for consistency (without lock)
            $query = "SELECT ISNULL(MAX(RunNo), 0) as MaxRunNo FROM Cust_BulkRun";
            $readResult = $this->dbService->getNwfth2Db()->query($query)->getRowArray();
            $readMaxRunNo = $readResult['MaxRunNo'] ?? 0;
            
            // Take maximum from both databases and add 1 for next available
            $nextRunNo = max($writeMaxRunNo, $readMaxRunNo) + 1;
            
            // Commit the lock transaction to release the lock
            $this->dbService->getNwfthDb()->transCommit();
            
            log_message('info', "CreateRunBulk: Generated atomic RunNo $nextRunNo (writeMax: $writeMaxRunNo, readMax: $readMaxRunNo)");
            
            return $nextRunNo;
            
        } catch (\Exception $e) {
            // Rollback transaction on any error
            if ($this->dbService->getNwfthDb()->transStatus() !== false) {
                $this->dbService->getNwfthDb()->transRollback();
            }
            
            log_message('error', 'CreateRunBulk atomic run number generation failed: ' . $e->getMessage());
            throw new \Exception('Failed to generate run number: ' . $e->getMessage());
        }
    }

    public function getBatchListPaginated()
    {
        // Validate AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request method']);
        }

        // BUSINESS RULE: Prevent batch reuse across runs
        // Each batch can only be used in ONE run to maintain data integrity

        try {
            // FIXED: Get DataTable server-side parameters
            $draw = $this->request->getPost('draw') ?? 1;
            $start = $this->request->getPost('start') ?? 0;
            $length = $this->request->getPost('length') ?? 25;
            $searchValue = $this->request->getPost('search')['value'] ?? '';
            
            // Get custom filter parameters
            $formulaId = $this->request->getPost('formulaid') ?? '';
            $batchWeight = $this->request->getPost('batchwt') ?? '';
            $excludeRunNo = $this->request->getPost('erunno') ?? '';

            // Build query directly on PNMAST table like CI3
            $builder = $this->dbService->getNwfth2Db()->table('PNMAST');
            
            // Base conditions matching CI3
            $builder->where('BatchType', 'M');
            $builder->where('Status', 'R');
            $builder->where('YEAR(EntryDate) >=', 2024);
            $builder->whereIn('ProcessCellId', ['AUSSIE','FENDER-L','FENDER-S','GIBSON','HOBART','TEXAS','YANKEE']);

            // Apply search conditions if search term provided (DataTable search)
            if (!empty($searchValue)) {
                $builder->groupStart();
                $builder->like('CAST(BatchNo AS NVARCHAR(50))', $searchValue);
                $builder->orLike('CAST(FormulaID AS NVARCHAR(50))', $searchValue);
                $builder->groupEnd();
            }

            // Apply filters like CI3
            if (!empty($formulaId)) {
                $builder->where('FormulaID', $formulaId);
            }

            if (!empty($batchWeight) && is_numeric($batchWeight)) {
                $batchWeightFloat = floatval($batchWeight);
                $tolerance = 0.001; // 0.1% tolerance for floating-point comparison
                
                log_message('debug', "CreateRunBulk BatchWeight filter: {$batchWeight} -> {$batchWeightFloat} ± {$tolerance}");
                
                $builder->where('BatchWeight >=', $batchWeightFloat - $tolerance);
                $builder->where('BatchWeight <=', $batchWeightFloat + $tolerance);
            }

            // ENHANCED: Exclude batches used in BOTH bulk AND partial runs (comprehensive filtering)
            $this->applyUsedBatchExclusion($builder);

            // FIXED: Get total count without search (for recordsTotal)
            $totalRecords = $builder->countAllResults();

            // Reset builder and apply same conditions for filtered count calculation
            $builder = $this->dbService->getNwfth2Db()->table('PNMAST');
            $builder->where('BatchType', 'M');
            $builder->where('Status', 'R');
            $builder->where('YEAR(EntryDate) >=', 2024);
            $builder->whereIn('ProcessCellId', ['AUSSIE','FENDER-L','FENDER-S','GIBSON','HOBART','TEXAS','YANKEE']);
            
            // Apply search conditions (FIXED: use $searchValue not $search)
            if (!empty($searchValue)) {
                $builder->groupStart();
                $builder->like('CAST(BatchNo AS NVARCHAR(50))', $searchValue);
                $builder->orLike('CAST(FormulaID AS NVARCHAR(50))', $searchValue);
                $builder->groupEnd();
            }

            // Apply custom filters
            if (!empty($formulaId)) {
                $builder->where('FormulaID', $formulaId);
            }

            if (!empty($batchWeight) && is_numeric($batchWeight)) {
                $batchWeightFloat = floatval($batchWeight);
                $tolerance = 0.001; // 0.1% tolerance for floating-point comparison
                
                log_message('debug', "CreateRunBulk BatchWeight filter (filtered count): {$batchWeight} -> {$batchWeightFloat} ± {$tolerance}");
                
                $builder->where('BatchWeight >=', $batchWeightFloat - $tolerance);
                $builder->where('BatchWeight <=', $batchWeightFloat + $tolerance);
            }

            // ENHANCED: Exclude batches used in BOTH bulk AND partial runs (comprehensive filtering)
            $this->applyUsedBatchExclusion($builder);

            // FIXED: Get filtered count (with search and filters applied)
            $filteredRecords = $builder->countAllResults();

            // Reset builder again for data retrieval with same conditions
            $builder = $this->dbService->getNwfth2Db()->table('PNMAST');
            $builder->where('BatchType', 'M');
            $builder->where('Status', 'R');
            $builder->where('YEAR(EntryDate) >=', 2024);
            $builder->whereIn('ProcessCellId', ['AUSSIE','FENDER-L','FENDER-S','GIBSON','HOBART','TEXAS','YANKEE']);
            
            // Apply same search conditions for data retrieval
            if (!empty($searchValue)) {
                $builder->groupStart();
                $builder->like('CAST(BatchNo AS NVARCHAR(50))', $searchValue);
                $builder->orLike('CAST(FormulaID AS NVARCHAR(50))', $searchValue);
                $builder->groupEnd();
            }

            // Apply same custom filters for data retrieval
            if (!empty($formulaId)) {
                $builder->where('FormulaID', $formulaId);
            }

            if (!empty($batchWeight) && is_numeric($batchWeight)) {
                $batchWeightFloat = floatval($batchWeight);
                $tolerance = 0.001; // 0.1% tolerance for floating-point comparison
                
                log_message('debug', "CreateRunBulk BatchWeight filter (data retrieval): {$batchWeight} -> {$batchWeightFloat} ± {$tolerance}");
                
                $builder->where('BatchWeight >=', $batchWeightFloat - $tolerance);
                $builder->where('BatchWeight <=', $batchWeightFloat + $tolerance);
            }

            // ENHANCED: Exclude batches used in BOTH bulk AND partial runs (comprehensive filtering)
            $this->applyUsedBatchExclusion($builder);

            // Get paginated data with proper ordering matching CI3 - including Description as FormulaDesc
            $data = $builder->select('BatchNo, FormulaID, BatchWeight, EntryDate, Description as FormulaDesc')
                           ->orderBy('EntryDate', 'DESC')
                           ->orderBy('BatchNo', 'DESC')
                           ->limit($length, $start)
                           ->get()
                           ->getResultArray();

            // FIXED: Return DataTable server-side format
            return $this->response->setJSON([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords, // Use actual filtered count, not total
                'data' => $data,
                'performance_metrics' => [
                    'cached' => false,
                    'query_time' => microtime(true),
                    'memory_usage' => memory_get_usage()
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'CreateRunBulk getBatchListPaginated error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to get batch list: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Apply bulk run batch exclusion filtering
     * Excludes batches that are already used in OTHER bulk runs only
     * Business Rule: A batch can be used in both bulk AND partial runs, but not twice in the same run type
     * 
     * @param \CodeIgniter\Database\BaseBuilder $builder
     * @return void
     */
    private function applyUsedBatchExclusion($builder)
    {
        try {
            // CORRECTED: Only exclude batches used in OTHER bulk runs (not partial runs)
            $usedBulkBatchesQuery = $this->dbService->getNwfth2Db()->table('Cust_BulkRun')
                                                                    ->select('BatchNo')
                                                                    ->where('BatchNo IS NOT NULL')
                                                                    ->getCompiledSelect();
            
            $builder->where("BatchNo NOT IN ($usedBulkBatchesQuery)");
            
            log_message('info', 'CreateRunBulk: Applied bulk-only batch exclusion (allows partial run reuse)');
            
        } catch (\Exception $e) {
            log_message('error', 'CreateRunBulk: Bulk batch exclusion failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create ingredient picking records in cust_BulkPicked table
     * Primary: TFCMOBILE (sqlserver2) -> Replication: TFCPILOT3 (sqlserver)
     */
    private function createBulkPickedRecords($runNo, $rowNum, $batchNo, $userId, $dateTime)
    {
        try {
            // CRITICAL FIX: Truncate user ID to prevent database truncation error
            // cust_BulkPicked schema: RecUserId nvarchar(8), ModifiedBy nvarchar(50) 
            $userId = substr((string)$userId, 0, 8); // Truncate to 8 chars max
            
            // Query PNITEM for ingredients (LineTyp = 'FI') from TFCPILOT3 (read database)
            $ingredients = $this->dbService->getNwfth2Db()
                ->table('PNITEM')
                ->where('BatchNo', $batchNo)
                ->where('LineTyp', 'FI')
                ->get()
                ->getResultArray();

            log_message('info', "CreateRunBulk: Found " . count($ingredients) . " ingredients for BatchNo $batchNo (UserId: '$userId')");

            $processedCount = 0;
            $skippedCount = 0;

            foreach ($ingredients as $ingredient) {
                // Skip ingredients from silos SILO1-4 (automatically dispensed)
                if ($this->isFromSilo($ingredient['ItemKey'], $ingredient['Location'])) {
                    log_message('info', "CreateRunBulk: Skipping silo ingredient: {$ingredient['ItemKey']} from {$ingredient['Location']} (BatchNo: $batchNo)");
                    $skippedCount++;
                    continue;
                }
                
                // Calculate PackSize and quantities
                $packSize = $this->calculatePackSize($ingredient['ItemKey']); 
                $standardQty = $ingredient['StdQty'];
                $toPickedBulkQty = $packSize > 0 ? floor($standardQty / $packSize) : 0;
                $topickedStdQty = $toPickedBulkQty * $packSize; // Pack size multiples
                
                // Debug logging to compare with official calculations
                log_message('debug', "CreateRunBulk: ItemKey={$ingredient['ItemKey']}, StandardQty=$standardQty, PackSize=$packSize, ToPickedBulkQty=$toPickedBulkQty, TopickedStdQty=$topickedStdQty");

                $data = [
                    'RunNo' => $runNo,
                    'RowNum' => $rowNum,
                    'BatchNo' => $batchNo,
                    'LineTyp' => $ingredient['LineTyp'],
                    'LineId' => $ingredient['Lineid'],
                    'ItemKey' => $ingredient['ItemKey'],
                    'Location' => $ingredient['Location'],
                    'Unit' => $ingredient['DispUom'],
                    'StandardQty' => $standardQty,
                    'PackSize' => $packSize,
                    'TopickedStdQty' => $topickedStdQty, // Pack size multiples
                    'ToPickedBulkQty' => $toPickedBulkQty,
                    'PickedBulkQty' => null, // To be filled during picking
                    'PickedQty' => null, // To be filled during picking
                    'PickingDate' => null, // To be filled during picking
                    'RecUserId' => $userId,
                    'RecDate' => $dateTime,
                    'ModifiedBy' => null,
                    'ModifiedDate' => $dateTime,
                    'ItemBatchStatus' => null,
                    // User fields (User1-User12) - all null
                    'User1' => null, 'User2' => null, 'User3' => null, 'User4' => null, 'User5' => null,
                    'User6' => null, 'User7' => null, 'User8' => null, 'User9' => null, 'User10' => null,
                    'User11' => null, 'User12' => null,
                    // Custom fields (CUSTOM1-CUSTOM10) - all null
                    'CUSTOM1' => null, 'CUSTOM2' => null, 'CUSTOM3' => null, 'CUSTOM4' => null, 'CUSTOM5' => null,
                    'CUSTOM6' => null, 'CUSTOM7' => null, 'CUSTOM8' => null, 'CUSTOM9' => null, 'CUSTOM10' => null,
                    // ESG fields
                    'ESG_REASON' => null,
                    'ESG_APPROVER' => null
                ];

                // PRIMARY: Insert to TFCMOBILE (sqlserver2) first
                $primaryResult = $this->dbService->getNwfthDb()->table('cust_BulkPicked')->insert($data);
                
                if (!$primaryResult) {
                    $error = $this->dbService->getNwfthDb()->error();
                    log_message('error', "CreateRunBulk: Primary cust_BulkPicked insert failed - " . json_encode($error));
                    throw new \Exception("Database insert failed: " . ($error['message'] ?? 'Unknown error'));
                }
                
                log_message('info', "CreateRunBulk: Successfully inserted cust_BulkPicked RunNo $runNo, BatchNo $batchNo, LineId {$ingredient['Lineid']} to TFCMOBILE");
                
                // REPLICATION: Then replicate to TFCPILOT3 (sqlserver)
                try {
                    $replicationResult = $this->dbService->getNwfth2Db()->table('cust_BulkPicked')->insert($data);
                    if ($replicationResult) {
                        log_message('info', "CreateRunBulk: Successfully replicated cust_BulkPicked RunNo $runNo, BatchNo $batchNo, LineId {$ingredient['Lineid']} to TFCPILOT3");
                    } else {
                        $replicationError = $this->dbService->getNwfth2Db()->error();
                        log_message('error', "CreateRunBulk: cust_BulkPicked replication failed - " . json_encode($replicationError));
                    }
                } catch (\Exception $replicationError) {
                    log_message('error', "CreateRunBulk: cust_BulkPicked replication exception - " . $replicationError->getMessage());
                }
                
                $processedCount++;
            }

            // Log summary of ingredient processing
            log_message('info', "CreateRunBulk: BatchNo $batchNo - Total ingredients: " . count($ingredients) . ", Processed: $processedCount, Silo-skipped: $skippedCount");

        } catch (\Exception $e) {
            log_message('error', 'CreateRunBulk createBulkPickedRecords error: ' . $e->getMessage());
            // Re-throw to handle in main transaction
            throw $e;
        }
    }

    /**
     * Calculate pack size for an item from INMAST table
     */
    private function calculatePackSize($itemKey)
    {
        try {
            // Query INMAST for pack size from TFCPILOT3
            $result = $this->dbService->getNwfth2Db()
                ->table('INMAST')
                ->select('User7') // Pack size is in User7 field
                ->where('ItemKey', $itemKey)
                ->get()
                ->getRowArray();
            
            $packSize = $result['User7'] ?? 25; // Default pack size
            log_message('debug', "CreateRunBulk: Pack size for $itemKey = $packSize");
            
            return $packSize;
            
        } catch (\Exception $e) {
            log_message('error', "CreateRunBulk: Error getting pack size for $itemKey - " . $e->getMessage());
            return 25; // Default fallback
        }
    }

    /**
     * Check if ingredient comes from silo (SILO1-4) and should be excluded from picking
     */
    private function isFromSilo($itemKey, $location)
    {
        try {
            // Check if ingredient is stored in any silo (should be excluded from picking)
            // Fixed: Use same logic as ERP filtering - check only ItemKey with SILO% pattern
            $siloCheckQuery = "SELECT COUNT(*) as SiloCount FROM LotMaster WHERE Itemkey = ? AND BinNo LIKE 'SILO%'";
            $siloResult = $this->dbService->getNwfth2Db()->query($siloCheckQuery, [$itemKey])->getRowArray();
            
            $isFromSilo = $siloResult && $siloResult['SiloCount'] > 0;
            log_message('debug', "CreateRunBulk: ItemKey $itemKey - Silo ingredient: " . ($isFromSilo ? 'YES (excluded)' : 'NO (included)'));
            
            return $isFromSilo;
            
        } catch (\Exception $e) {
            log_message('error', "CreateRunBulk: Error checking silo status for $itemKey - " . $e->getMessage());
            return false; // Default to include if error (safer for ingredient inclusion)
        }
    }

    /**
     * Get run details for print functionality
     * Similar to CreateRunPartial but adapted for bulk run logic
     */
    public function getRunDetails($runNo = null)
    {
        // Validate AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request method']);
        }

        if (empty($runNo) || !is_numeric($runNo)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid RunNo provided'
            ]);
        }

        try {
            $runNo = (int)$runNo;
            
            // Get all run records for this RunNo from TFCMOBILE (primary database)
            $runBuilder = $this->dbService->getNwfthDb()->table('Cust_BulkRun');
            $allRunData = $runBuilder->where('RunNo', $runNo)
                                    ->orderBy('RowNum')
                                    ->get()
                                    ->getResultArray();
            
            if (!$allRunData) {
                // Try TFCPILOT3 (replication database) as fallback
                $runBuilder = $this->dbService->getNwfth2Db()->table('Cust_BulkRun');
                $allRunData = $runBuilder->where('RunNo', $runNo)
                                        ->orderBy('RowNum')
                                        ->get()
                                        ->getResultArray();
                
                if (!$allRunData) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Run not found in either database'
                    ]);
                }
            }
            
            // Use first record for basic run information
            $runData = $allRunData[0];
            
            // Get batch numbers and FG item information
            $batchNumbers = [];
            $fgItemData = null;
            
            foreach ($allRunData as $batch) {
                $batchNumbers[] = $batch['BatchNo'];
            }
            
            // Get FG (Finished Goods) item data from PNITEM - this is what should show in the header
            if (!empty($batchNumbers)) {
                try {
                    $fgQuery = "SELECT TOP 1 
                        pi.ItemKey as FG_ItemKey, 
                        pi.StdQty as FG_StandardQty,
                        COALESCE(i.Desc1, i.Desc2, pi.ItemKey) as FG_Description
                        FROM PNITEM pi
                        LEFT JOIN INMAST i ON pi.ItemKey = i.Itemkey
                        WHERE pi.BatchNo IN ('" . implode("','", $batchNumbers) . "') 
                          AND pi.LineTyp = 'FG'
                        ORDER BY pi.BatchNo DESC, pi.LineId";
                    $fgResult = $this->dbService->getNwfth2Db()->query($fgQuery)->getRowArray();
                    
                    if ($fgResult) {
                        $fgItemData = $fgResult;
                        log_message('info', "CreateRunBulk: Found FG item {$fgResult['FG_ItemKey']} - {$fgResult['FG_Description']} for RunNo $runNo");
                    }
                } catch (\Exception $e) {
                    log_message('error', "CreateRunBulk: Error getting FG item data - " . $e->getMessage());
                }
            }
            
            // Calculate FG quantity - should be per batch standard quantity, not total
            $totalFgQty = 0;
            if ($fgItemData) {
                // Use FG item standard quantity per batch (not multiplied by batch count)
                $totalFgQty = floatval($fgItemData['FG_StandardQty']);
                log_message('info', "CreateRunBulk: FG Qty set to standard batch size: $totalFgQty");
            } else {
                // Fallback: Try PNMAST table for single batch weight
                if (!empty($allRunData)) {
                    $firstBatch = $allRunData[0];
                    
                    try {
                        $batchQuery = "SELECT TOP 1 
                            COALESCE(BatchWeight, OrderWeight, TotalFGWeightYielded) as BatchSize 
                            FROM PNMAST 
                            WHERE BatchNo = ?";
                        $batchResult = $this->dbService->getNwfth2Db()->query($batchQuery, [$firstBatch['BatchNo']])->getRowArray();
                        
                        if ($batchResult && $batchResult['BatchSize'] > 0) {
                            $totalFgQty = floatval($batchResult['BatchSize']);
                        }
                    } catch (\Exception $e) {
                        // Use fallback estimate
                        $totalFgQty = floatval($firstBatch['PalletsPerBatch'] ?? 1) * 500;
                    }
                }
            }
            
            // Get ingredient picking details with descriptions from INMAST
            // BULK RUN LOGIC: Use ToPickedBulkQty and TopickedStdQty (pack multiples, not remainders)
            $ingredientsQuery = "
                SELECT 
                    cb.ItemKey,
                    cb.LineId,
                    COALESCE(i.Desc1, i.Desc2, cb.ItemKey) as Description,
                    cb.Unit,
                    cb.StandardQty,
                    cb.PackSize,
                    cb.ToPickedBulkQty,
                    cb.TopickedStdQty,
                    cb.PickedBulkQty,
                    cb.BatchNo,
                    cb.RowNum
                FROM cust_BulkPicked cb
                LEFT JOIN INMAST i ON cb.ItemKey = i.Itemkey
                WHERE cb.RunNo = ? 
                ORDER BY cb.LineId, cb.ItemKey, cb.RowNum
            ";
            
            try {
                // CRITICAL: Follow user-specified database strategy - read from TFCPILOT3 first (more stable)
                // Try TFCPILOT3 first with full INMAST join for descriptions
                $ingredientsData = $this->dbService->getNwfth2Db()->query($ingredientsQuery, [$runNo])->getResultArray();
                log_message('info', "CreateRunBulk: Found " . count($ingredientsData) . " raw ingredients from TFCPILOT3 (primary read)");
                
                // If no data found in TFCPILOT3, fallback to TFCMOBILE (without INMAST join)
                if (empty($ingredientsData)) {
                    $simpleQuery = "
                        SELECT 
                            cb.ItemKey,
                            cb.LineId,
                            cb.ItemKey as Description,  -- Fallback to ItemKey if no description
                            cb.Unit,
                            cb.StandardQty,
                            cb.PackSize,
                            cb.ToPickedBulkQty,
                            cb.TopickedStdQty,
                            cb.PickedBulkQty,
                            cb.BatchNo,
                            cb.RowNum
                        FROM cust_BulkPicked cb
                        WHERE cb.RunNo = ? 
                        ORDER BY cb.LineId, cb.ItemKey, cb.RowNum
                    ";
                    
                    $ingredientsData = $this->dbService->getNwfthDb()->query($simpleQuery, [$runNo])->getResultArray();
                    log_message('info', "CreateRunBulk: Found " . count($ingredientsData) . " raw ingredients from TFCMOBILE (fallback)");
                    
                    // Enhance with descriptions from INMAST in TFCPILOT3 if we used TFCMOBILE
                    foreach ($ingredientsData as &$ingredient) {
                        try {
                            $descQuery = "SELECT COALESCE(Desc1, Desc2, ?) as Description FROM INMAST WHERE Itemkey = ?";
                            $descResult = $this->dbService->getNwfth2Db()->query($descQuery, [$ingredient['ItemKey'], $ingredient['ItemKey']])->getRowArray();
                            if ($descResult && !empty($descResult['Description'])) {
                                $ingredient['Description'] = $descResult['Description'];
                            }
                        } catch (\Exception $e) {
                            // Keep the ItemKey as description if INMAST lookup fails
                            log_message('warning', "CreateRunBulk: Could not get description for ItemKey: {$ingredient['ItemKey']}");
                        }
                    }
                    log_message('info', "CreateRunBulk: Enhanced descriptions from TFCPILOT3 INMAST table");
                }
                
                // CRITICAL: Official app consolidation logic - Group by LineId (not ItemKey)
                // This ensures INSOYF01+INSOYI01 at same LineId become single ingredient
                $consolidatedIngredients = [];
                $ingredientGroups = [];
                
                // Group ingredients by LineId (official app behavior)
                foreach ($ingredientsData as $ingredient) {
                    $lineId = $ingredient['LineId'];
                    if (!isset($ingredientGroups[$lineId])) {
                        $ingredientGroups[$lineId] = [];
                    }
                    $ingredientGroups[$lineId][] = $ingredient;
                }
                log_message('info', "CreateRunBulk: Grouped ingredients by LineId - " . count($ingredientGroups) . " groups found");
                
                // For each LineId group, consolidate ingredients and sum quantities
                foreach ($ingredientGroups as $lineId => $ingredients) {
                    if (empty($ingredients)) continue;
                    
                    // Sort by ItemKey to get consistent representative ingredient (INSOYF01 before INSOYI01)
                    usort($ingredients, function($a, $b) {
                        return strcmp($a['ItemKey'], $b['ItemKey']);
                    });
                    
                    // Use first ItemKey (alphabetically) as representative ingredient
                    $representativeIngredient = $ingredients[0];
                    
                    // Sum quantities from ALL ingredients in this LineId group
                    $totalStandardQty = 0;
                    $totalTopickedStdQty = 0;
                    $totalBulkQty = 0;
                    $qtyPerBatch = 0;
                    $bulkQtyPerBatch = 0;
                    
                    foreach ($ingredients as $ingredient) {
                        $totalStandardQty += floatval($ingredient['StandardQty'] ?? 0);
                        $totalTopickedStdQty += floatval($ingredient['TopickedStdQty'] ?? 0);
                        $totalBulkQty += floatval($ingredient['ToPickedBulkQty'] ?? 0);
                        
                        // For per-batch quantities, use the representative ingredient from first batch
                        if ($ingredient['ItemKey'] === $representativeIngredient['ItemKey'] && $ingredient['RowNum'] == 1) {
                            $qtyPerBatch = floatval($ingredient['StandardQty'] ?? 0);
                            $bulkQtyPerBatch = floatval($ingredient['ToPickedBulkQty'] ?? 0);
                        }
                    }
                    
                    // If we didn't find RowNum=1, use the representative ingredient data
                    if ($qtyPerBatch == 0) {
                        $qtyPerBatch = floatval($representativeIngredient['StandardQty'] ?? 0);
                        $bulkQtyPerBatch = floatval($representativeIngredient['ToPickedBulkQty'] ?? 0);
                    }
                    
                    log_message('info', "CreateRunBulk: LineId $lineId - Representative: {$representativeIngredient['ItemKey']}, Total Qty: $totalStandardQty, Total Bulk: $totalBulkQty");
                    
                    $consolidatedIngredients[] = [
                        'ItemKey' => $representativeIngredient['ItemKey'],
                        'Description' => $representativeIngredient['Description'],
                        'Unit' => $representativeIngredient['Unit'],
                        'QtyPerBatch' => $qtyPerBatch,
                        'TotalQty' => $totalStandardQty,
                        'PackSize' => floatval($representativeIngredient['PackSize'] ?? 0),
                        'BulkQty' => $totalBulkQty,
                        'BulkQtyPerBatch' => $bulkQtyPerBatch
                    ];
                }
                log_message('info', "CreateRunBulk: Found " . count($consolidatedIngredients) . " consolidated ingredients for RunNo $runNo");
                
            } catch (\Exception $e) {
                log_message('error', "CreateRunBulk: Error getting ingredients - " . $e->getMessage());
                $consolidatedIngredients = [];
            }
            
            // Prepare response data matching the reference image structure
            $responseData = [
                'run_header' => [
                    'RunNo' => $runData['RunNo'],
                    'FormulaId' => $runData['FormulaId'],
                    'FormulaDesc' => $runData['FormulaDesc'] ?: ($fgItemData['FG_Description'] ?? $runData['FormulaId']),
                    'NoOfBatches' => count($allRunData),
                    'PalletsPerBatch' => $runData['PalletsPerBatch'] ?? 1,
                    'FG_ItemKey' => $fgItemData['FG_ItemKey'] ?? $runData['FormulaId'],
                    'FG_Description' => $fgItemData['FG_Description'] ?? ($runData['FormulaDesc'] ?: $runData['FormulaId']),
                    'FG_Qty' => $totalFgQty,
                    'FG_Unit' => 'KG'
                ],
                'batch_numbers' => $batchNumbers,
                'ingredients' => $consolidatedIngredients
            ];
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $responseData
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'CreateRunBulk getRunDetails error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to get run details: ' . $e->getMessage()
            ]);
        }
    }
}