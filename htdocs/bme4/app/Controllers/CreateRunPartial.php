<?php

namespace App\Controllers;

use App\Models\CreateRunPartialModel;

class CreateRunPartial extends BaseController
{
    private CreateRunPartialModel $createRunPartialModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Initialize model with DatabaseService integration
        $this->createRunPartialModel = new CreateRunPartialModel();
    }

    public function index()
    {
        // Require authentication
        $authRedirect = $this->requireAuth();
        if ($authRedirect) return $authRedirect;
        
        $this->logOperation('index', ['user' => $this->getCurrentUser()]);

        $data = $this->getBaseViewData(
            'NWFTH - Create Partial Run',
            'NWFTH - Create Partial Run System',
            [['title' => 'Create Partial Run', 'url' => '']]
        );

        return view('CreateRunPartial/index', $data);
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
            $data = $this->createRunPartialModel->getPartialRuns($params['length'], $params['start'], $params['search']);
            $totalRecords = $this->createRunPartialModel->countAllResults();
            $filteredRecords = $this->createRunPartialModel->getPartialRunsCount($params['search']);

            // Format data for DataTable
            $formattedData = array_map(function($row) {
                return ['Cust_PartialRun' => $row];
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
        log_message('debug', 'CreateRunPartial::create() called with method: ' . $this->request->getMethod());
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
        
        // Handle POST request for creating new partial run
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

                // Get the actual next RunNo from TFCPILOT3 (read database) to maintain sequence
                $builder = $this->dbService->getNwfth2Db()->table('Cust_PartialRun');
                $result = $builder->selectMax('RunNo')->get()->getRowArray();
                $actualRunNo = ($result['RunNo'] ?? 0) + 1;

                // Start database transaction
                $this->dbService->getNwfthDb()->transStart();

                // Insert each batch as a separate record with the calculated RunNo
                $rowNum = 1;
                $currentUserId = session()->get('username') ?: session()->get('user_id') ?: 'admin';
                
                // CRITICAL FIX: Truncate user ID to prevent database truncation error
                // TFCMOBILE schema: RecUserId nvarchar(8), ModifiedBy nvarchar(50)
                $currentUserId = substr((string)$currentUserId, 0, 8); // Truncate to 8 chars max
                
                $currentDateTime = date('Y-m-d H:i:s');
                $totalBatches = count($selectedBatches); // Calculate total batch count for NoOfBatches
                
                log_message('info', "CreateRunPartial: Using truncated UserId: '$currentUserId' (length: " . strlen($currentUserId) . ")");
                
                foreach ($selectedBatches as $batch) {
                    // CRITICAL FIX: Validate field lengths to prevent truncation
                    $batchNo = substr((string)$batch['batch_no'], 0, 30); // nvarchar(30)
                    $formulaId = substr((string)$batch['formula_id'], 0, 30); // nvarchar(30) 
                    $formulaDesc = substr((string)($batch['formula_desc'] ?? ''), 0, 160); // nvarchar(160)
                    
                    // Complete data structure matching Cust_PartialRun schema (32 fields)
                    $data = [
                        'RunNo' => (int)$actualRunNo,           // int - both databases now use int
                        'RowNum' => $rowNum,                    // int - included for both databases
                        'BatchNo' => $batchNo,                  // nvarchar(30) - truncated
                        'FormulaId' => $formulaId,              // nvarchar(30) - truncated
                        'FormulaDesc' => $formulaDesc,          // nvarchar(160) - truncated
                        'NoOfBatches' => (int)$totalBatches,    // int - total batch count for this run
                        'PalletsPerBatch' => (int)1,            // int - explicit cast
                        'Status' => 'NEW',                      // nvarchar(10) - uppercase
                        'RecUserId' => (string)$currentUserId,  // nvarchar(8) - ensure string type
                        'RecDate' => $currentDateTime,          // datetime - current timestamp
                        'ModifiedBy' => null,                   // nvarchar(8) - NULL for new records
                        'ModifiedDate' => $currentDateTime,     // datetime - current timestamp
                        // User fields - match schema exactly
                        'User1' => null,                        // nvarchar(225) - NULL instead of empty string
                        'User2' => null,                        // nvarchar(225) - NULL instead of empty string
                        'User3' => null,                        // nvarchar(225) - NULL instead of empty string
                        'User4' => null,                        // nvarchar(50) - NULL instead of empty string
                        'User5' => null,                        // nvarchar(50) - NULL instead of empty string
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
                        'ESG_REASON' => null,                   // nvarchar(255) - NULL instead of empty string
                        'ESG_APPROVER' => null                  // nvarchar(255) - NULL instead of empty string
                    ];

                    // Insert to TFCMOBILE (primary database)
                    $insertResult = $this->dbService->getNwfthDb()->table('Cust_PartialRun')->insert($data);
                    
                    if (!$insertResult) {
                        $error = $this->dbService->getNwfthDb()->error();
                        log_message('error', "CreateRunPartial: Primary insert failed - " . json_encode($error));
                        throw new \Exception("Database insert failed: " . ($error['message'] ?? 'Unknown error'));
                    }
                    
                    // REPLICATION: Immediately replicate to TFCPILOT3
                    try {
                        $replicationResult = $this->dbService->getNwfth2Db()->table('Cust_PartialRun')->insert($data);
                        if ($replicationResult) {
                            log_message('info', "CreateRunPartial: Successfully replicated RunNo $actualRunNo, RowNum $rowNum to TFCPILOT3");
                        } else {
                            $replicationError = $this->dbService->getNwfth2Db()->error();
                            log_message('error', "CreateRunPartial: Replication failed for RunNo $actualRunNo - " . json_encode($replicationError));
                        }
                    } catch (\Exception $replicationError) {
                        log_message('error', "CreateRunPartial: Replication exception for RunNo $actualRunNo - " . $replicationError->getMessage());
                        // Continue with other batches but log the error
                    }
                    
                    // NEW: Create ingredient picking records for this batch
                    try {
                        $this->createPartialPickedRecords($actualRunNo, $rowNum, $batch['batch_no'], $currentUserId, $currentDateTime);
                        log_message('info', "CreateRunPartial: Successfully created picking records for BatchNo {$batch['batch_no']}");
                    } catch (\Exception $pickingError) {
                        log_message('error', "CreateRunPartial: Failed to create picking records for BatchNo {$batch['batch_no']} - " . $pickingError->getMessage());
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
                    
                    log_message('error', "CreateRunPartial: Transaction failed for RunNo $actualRunNo - " . json_encode($transError));
                    
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to create partial run - ' . $errorMessage,
                        'error_details' => $transError
                    ]);
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Partial run created successfully',
                    'run_no' => $runNo
                ]);

            } catch (\Exception $e) {
                log_message('error', 'CreateRunPartial create error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create partial run: ' . $e->getMessage()
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
            log_message('info', "CreateRunPartial: Starting deletion of RunNo $runNo (type: " . gettype($runNo) . ")");
            
            // Check both databases for RunNo existence
            $runCountPrimary = $this->dbService->getNwfthDb()->table('Cust_PartialRun')
                                        ->where('RunNo', $runNo)
                                        ->countAllResults();
            
            $runCountSecondary = $this->dbService->getNwfth2Db()->table('Cust_PartialRun')
                                        ->where('RunNo', $runNo)
                                        ->countAllResults();
            
            log_message('info', "CreateRunPartial: RunNo $runNo found in TFCMOBILE: $runCountPrimary records, TFCPILOT3: $runCountSecondary records");
            
            if ($runCountPrimary == 0 && $runCountSecondary == 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'RunNo not found in either database'
                ]);
            }
            
            // Start transaction on primary database
            $this->dbService->getNwfthDb()->transStart();
            
            // Check how many picking records will be deleted from primary database
            $pickingCountPrimary = $this->dbService->getNwfthDb()->table('cust_PartialPicked')
                                              ->where('RunNo', $runNo)
                                              ->countAllResults();
            
            log_message('info', "CreateRunPartial: Found $runCountPrimary run records and $pickingCountPrimary picking records for RunNo $runNo in TFCMOBILE");
            
            // Delete picking records first from primary database (foreign key dependency)
            if ($pickingCountPrimary > 0) {
                $pickingDeleteResult = $this->dbService->getNwfthDb()->table('cust_PartialPicked')
                                                     ->where('RunNo', $runNo)
                                                     ->delete();
                
                if (!$pickingDeleteResult) {
                    $error = $this->dbService->getNwfthDb()->error();
                    log_message('error', "CreateRunPartial: Primary picking delete failed - " . json_encode($error));
                    throw new \Exception("Database delete failed: " . ($error['message'] ?? 'Unknown error'));
                }
                
                log_message('info', "CreateRunPartial: Successfully deleted $pickingCountPrimary picking records for RunNo $runNo from TFCMOBILE");
            }
            
            // Delete run records from TFCMOBILE (primary database) - only if they exist
            if ($runCountPrimary > 0) {
                $deleteResult = $this->dbService->getNwfthDb()->table('Cust_PartialRun')
                                              ->where('RunNo', $runNo)
                                              ->delete();
                
                if (!$deleteResult) {
                    $error = $this->dbService->getNwfthDb()->error();
                    log_message('error', "CreateRunPartial: Primary delete failed - " . json_encode($error));
                    throw new \Exception("Database delete failed: " . ($error['message'] ?? 'Unknown error'));
                }
                
                log_message('info', "CreateRunPartial: Successfully deleted $runCountPrimary run records for RunNo $runNo from TFCMOBILE");
            }
            
            // Complete transaction
            $this->dbService->getNwfthDb()->transComplete();
            
            if ($this->dbService->getNwfthDb()->transStatus() === FALSE) {
                throw new \Exception('Transaction failed during deletion');
            }
            
            // REPLICATION: Delete from TFCPILOT3 (replication database)
            try {
                // Check and delete picking records from replication database
                $pickingCountSecondary = $this->dbService->getNwfth2Db()->table('cust_PartialPicked')
                                                        ->where('RunNo', $runNo)
                                                        ->countAllResults();
                
                if ($pickingCountSecondary > 0) {
                    $replicationPickingDeleteResult = $this->dbService->getNwfth2Db()->table('cust_PartialPicked')
                                                                    ->where('RunNo', $runNo)
                                                                    ->delete();
                    
                    if ($replicationPickingDeleteResult) {
                        log_message('info', "CreateRunPartial: Successfully deleted $pickingCountSecondary picking records for RunNo $runNo from TFCPILOT3");
                    } else {
                        $replicationError = $this->dbService->getNwfth2Db()->error();
                        log_message('error', "CreateRunPartial: TFCPILOT3 picking delete failed for RunNo $runNo - " . json_encode($replicationError));
                    }
                }
                
                // Delete run records from replication database - only if they exist
                if ($runCountSecondary > 0) {
                    $replicationDeleteResult = $this->dbService->getNwfth2Db()->table('Cust_PartialRun')
                                                             ->where('RunNo', $runNo)
                                                             ->delete();
                    
                    if ($replicationDeleteResult) {
                        log_message('info', "CreateRunPartial: Successfully deleted $runCountSecondary run records for RunNo $runNo from TFCPILOT3");
                    } else {
                        $replicationError = $this->dbService->getNwfth2Db()->error();
                        log_message('error', "CreateRunPartial: TFCPILOT3 run delete failed for RunNo $runNo - " . json_encode($replicationError));
                    }
                }
            } catch (\Exception $replicationError) {
                log_message('error', "CreateRunPartial: TFCPILOT3 delete exception for RunNo $runNo - " . $replicationError->getMessage());
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
            log_message('error', 'CreateRunPartial delete error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete partial run: ' . $e->getMessage()
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
            // Get next run number from database - using both databases like CI3
            // First check write database (TFCMOBILE)
            $builder = $this->dbService->getNwfthDb()->table('Cust_PartialRun');
            $writeResult = $builder->selectMax('RunNo')->get()->getRowArray();
            $writeMaxRunNo = $writeResult['RunNo'] ?? 0;
            
            // Then check read database (TFCPILOT3)
            $builder = $this->dbService->getNwfth2Db()->table('Cust_PartialRun');
            $readResult = $builder->selectMax('RunNo')->get()->getRowArray();
            $readMaxRunNo = $readResult['RunNo'] ?? 0;
            
            // Take the maximum from both databases and add 1 (like CI3 does)
            $nextRunNo = max($writeMaxRunNo, $readMaxRunNo) + 1;
            
            return $this->response->setJSON([
                'success' => true,
                'next_run_no' => $nextRunNo
            ]);

        } catch (\Exception $e) {
            log_message('error', 'CreateRunPartial getNextRunNumber error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to get next run number'
            ]);
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
            // Get DataTable server-side parameters
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
                $builder->where('BatchWeight', floatval($batchWeight));
            }

            // Exclude ALL batches that have been used in ANY existing partial run (Global exclusion)
            $usedBatchesQuery = $this->dbService->getNwfth2Db()->table('Cust_PartialRun')
                                              ->select('BatchNo')
                                              ->where('BatchNo IS NOT NULL')
                                              ->getCompiledSelect();
            $builder->where("BatchNo NOT IN ($usedBatchesQuery)");

            // Get total count without search (for recordsTotal)
            $totalRecords = $builder->countAllResults();

            // Reset builder and apply same conditions for filtered count calculation
            $builder = $this->dbService->getNwfth2Db()->table('PNMAST');
            $builder->where('BatchType', 'M');
            $builder->where('Status', 'R');
            $builder->where('YEAR(EntryDate) >=', 2024);
            $builder->whereIn('ProcessCellId', ['AUSSIE','FENDER-L','FENDER-S','GIBSON','HOBART','TEXAS','YANKEE']);
            
            // Apply search conditions
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
                $builder->where('BatchWeight', floatval($batchWeight));
            }

            // Exclude ALL batches that have been used in ANY existing partial run (Global exclusion)
            $usedBatchesQuery = $this->dbService->getNwfth2Db()->table('Cust_PartialRun')
                                              ->select('BatchNo')
                                              ->where('BatchNo IS NOT NULL')
                                              ->getCompiledSelect();
            $builder->where("BatchNo NOT IN ($usedBatchesQuery)");

            // Get filtered count (with search and filters applied)
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
                $builder->where('BatchWeight', floatval($batchWeight));
            }

            // Exclude ALL batches that have been used in ANY existing partial run (Global exclusion)
            $usedBatchesQuery = $this->dbService->getNwfth2Db()->table('Cust_PartialRun')
                                              ->select('BatchNo')
                                              ->where('BatchNo IS NOT NULL')
                                              ->getCompiledSelect();
            $builder->where("BatchNo NOT IN ($usedBatchesQuery)");

            // Get paginated data with proper ordering matching CI3 - including Description as FormulaDesc
            $data = $builder->select('BatchNo, FormulaID, BatchWeight, EntryDate, Description as FormulaDesc')
                           ->orderBy('EntryDate', 'DESC')
                           ->orderBy('BatchNo', 'DESC')
                           ->limit($length, $start)
                           ->get()
                           ->getResultArray();

            // Return DataTable server-side format
            return $this->response->setJSON([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data,
                'performance_metrics' => [
                    'cached' => false,
                    'query_time' => microtime(true),
                    'memory_usage' => memory_get_usage()
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'CreateRunPartial getBatchListPaginated error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to get batch list: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Create ingredient picking records in cust_PartialPicked table
     * Primary: TFCMOBILE (sqlserver2) -> Replication: TFCPILOT3 (sqlserver)
     */
    private function createPartialPickedRecords($runNo, $rowNum, $batchNo, $userId, $dateTime)
    {
        try {
            // CRITICAL FIX: Truncate user ID to prevent database truncation error
            // cust_PartialPicked schema: RecUserId nvarchar(8), ModifiedBy nvarchar(50)
            $userId = substr((string)$userId, 0, 8); // Truncate to 8 chars max
            
            // Query PNITEM for ingredients (LineTyp = 'FI') from TFCPILOT3 (read database)
            $ingredients = $this->dbService->getNwfth2Db()
                ->table('PNITEM')
                ->where('BatchNo', $batchNo)
                ->where('LineTyp', 'FI')
                ->get()
                ->getResultArray();

            log_message('info', "CreateRunPartial: Found " . count($ingredients) . " ingredients for BatchNo $batchNo (UserId: '$userId')");

            $processedCount = 0;
            $skippedCount = 0;

            foreach ($ingredients as $ingredient) {
                // Skip ingredients from silos SILO1-4 (automatically dispensed)
                if ($this->isFromSilo($ingredient['ItemKey'], $ingredient['Location'])) {
                    log_message('info', "CreateRunPartial: Skipping silo ingredient: {$ingredient['ItemKey']} from {$ingredient['Location']} (BatchNo: $batchNo)");
                    $skippedCount++;
                    continue;
                }
                
                // Calculate PackSize and quantities  
                $packSize = $this->calculatePackSize($ingredient['ItemKey']);
                $standardQty = $ingredient['StdQty'];
                // PARTIAL RUN LOGIC: Calculate remainder after full packs (different from bulk runs)
                $toPickedBulkQty = $packSize > 0 ? floor($standardQty / $packSize) : 0;
                $toPickedPartialQty = $standardQty - ($toPickedBulkQty * $packSize); // REMAINDER calculation
                
                // Debug logging to compare with official calculations
                log_message('debug', "CreateRunPartial: ItemKey={$ingredient['ItemKey']}, StandardQty=$standardQty, PackSize=$packSize, ToPickedBulkQty=$toPickedBulkQty, ToPickedPartialQty=$toPickedPartialQty");

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
                    'ToPickedPartialQty' => $toPickedPartialQty,
                    'PickedPartialQty' => null, // To be filled during picking
                    'PickingDate' => null, // To be filled during picking
                    'RecUserId' => $userId,
                    'RecDate' => $dateTime,
                    'ModifiedBy' => null,
                    'ModifiedDate' => $dateTime,
                    'ToPickedPartialQtyKG' => null,
                    'PickedPartialQtyKG' => null,
                    'ItemBatchStatus' => null,
                    'Allergen' => $this->getAllergen($ingredient['ItemKey']), // Lookup allergen info
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
                $primaryResult = $this->dbService->getNwfthDb()->table('cust_PartialPicked')->insert($data);
                
                if (!$primaryResult) {
                    $error = $this->dbService->getNwfthDb()->error();
                    log_message('error', "CreateRunPartial: Primary cust_PartialPicked insert failed - " . json_encode($error));
                    throw new \Exception("Database insert failed: " . ($error['message'] ?? 'Unknown error'));
                }
                
                log_message('info', "CreateRunPartial: Successfully inserted cust_PartialPicked RunNo $runNo, BatchNo $batchNo, LineId {$ingredient['Lineid']} to TFCMOBILE");
                
                // REPLICATION: Then replicate to TFCPILOT3 (sqlserver)
                try {
                    $replicationResult = $this->dbService->getNwfth2Db()->table('cust_PartialPicked')->insert($data);
                    if ($replicationResult) {
                        log_message('info', "CreateRunPartial: Successfully replicated cust_PartialPicked RunNo $runNo, BatchNo $batchNo, LineId {$ingredient['Lineid']} to TFCPILOT3");
                    } else {
                        $replicationError = $this->dbService->getNwfth2Db()->error();
                        log_message('error', "CreateRunPartial: cust_PartialPicked replication failed - " . json_encode($replicationError));
                    }
                } catch (\Exception $replicationError) {
                    log_message('error', "CreateRunPartial: cust_PartialPicked replication exception - " . $replicationError->getMessage());
                }
                
                $processedCount++;
            }

            // Log summary of ingredient processing
            log_message('info', "CreateRunPartial: BatchNo $batchNo - Total ingredients: " . count($ingredients) . ", Processed: $processedCount, Silo-skipped: $skippedCount");

        } catch (\Exception $e) {
            log_message('error', 'CreateRunPartial createPartialPickedRecords error: ' . $e->getMessage());
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
            log_message('debug', "CreateRunPartial: Pack size for $itemKey = $packSize");
            
            return $packSize;
            
        } catch (\Exception $e) {
            log_message('error', "CreateRunPartial: Error getting pack size for $itemKey - " . $e->getMessage());
            return 25; // Default fallback
        }
    }

    /**
     * Get allergen information for an item
     */
    private function getAllergen($itemKey)
    {
        try {
            // Query INMAST for allergen information from TFCPILOT3
            $result = $this->dbService->getNwfth2Db()
                ->table('INMAST')
                ->select('User3') // Assuming User3 contains allergen info
                ->where('ItemKey', $itemKey)
                ->get()
                ->getRowArray();
            
            $allergen = $result['User3'] ?? ''; // Default empty if no allergen
            log_message('debug', "CreateRunPartial: Allergen for $itemKey = '$allergen'");
            
            return $allergen;
            
        } catch (\Exception $e) {
            log_message('error', "CreateRunPartial: Error getting allergen for $itemKey - " . $e->getMessage());
            return ''; // Default empty
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
            log_message('debug', "CreateRunPartial: ItemKey $itemKey - Silo ingredient: " . ($isFromSilo ? 'YES (excluded)' : 'NO (included)'));
            
            return $isFromSilo;
            
        } catch (\Exception $e) {
            log_message('error', "CreateRunPartial: Error checking silo status for $itemKey - " . $e->getMessage());
            return false; // Default to include if error (safer for ingredient inclusion)
        }
    }

    /**
     * Get detailed run information for print modal
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
            $runBuilder = $this->dbService->getNwfthDb()->table('Cust_PartialRun');
            $allRunData = $runBuilder->where('RunNo', $runNo)
                                    ->orderBy('RowNum')
                                    ->get()
                                    ->getResultArray();
            
            if (!$allRunData) {
                // Try TFCPILOT3 (replication database) as fallback
                $runBuilder = $this->dbService->getNwfth2Db()->table('Cust_PartialRun');
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
                        log_message('info', "CreateRunPartial: Found FG item {$fgResult['FG_ItemKey']} - {$fgResult['FG_Description']} for RunNo $runNo");
                    }
                } catch (\Exception $e) {
                    log_message('error', "CreateRunPartial: Error getting FG item data - " . $e->getMessage());
                }
            }
            
            // Calculate FG quantity - should be per batch standard quantity, not total
            $totalFgQty = 0;
            if ($fgItemData) {
                // Use FG item standard quantity per batch (not multiplied by batch count)
                $totalFgQty = floatval($fgItemData['FG_StandardQty']);
                log_message('info', "CreateRunPartial: FG Qty set to standard batch size: $totalFgQty");
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
            // FIXED: Show all ingredient records per batch to match official app behavior
            $ingredientsQuery = "
                SELECT 
                    cp.ItemKey,
                    cp.LineId,
                    COALESCE(i.Desc1, i.Desc2, cp.ItemKey) as Description,
                    cp.Unit,
                    cp.StandardQty,
                    cp.PackSize,
                    cp.ToPickedPartialQty,
                    cp.PickedPartialQty,
                    cp.BatchNo,
                    cp.RowNum
                FROM cust_PartialPicked cp
                LEFT JOIN INMAST i ON cp.ItemKey = i.Itemkey
                WHERE cp.RunNo = ? 
                ORDER BY cp.LineId, cp.ItemKey, cp.RowNum
            ";
            
            try {
                // DEBUGGING: First let's see all raw data for this RunNo to understand what's missing
                $debugQuery = "SELECT ItemKey, LineId, StandardQty, PackSize, ToPickedPartialQty FROM cust_PartialPicked WHERE RunNo = ? ORDER BY LineId, ItemKey";
                $debugData = $this->dbService->getNwfthDb()->query($debugQuery, [$runNo])->getResultArray();
                log_message('info', "CreateRunPartial DEBUG - RunNo $runNo raw ingredients from TFCMOBILE: " . json_encode($debugData));
                
                // DEBUGGING: Also check what ingredients SHOULD exist by looking at PNITEM for this run's batches
                $batchList = array_unique($batchNumbers);
                if (!empty($batchList)) {
                    try {
                        $originalIngredientsQuery = "SELECT BatchNo, ItemKey, LineId, StdQty, Location FROM PNITEM WHERE BatchNo IN ('" . implode("','", $batchList) . "') AND LineTyp = 'FI' ORDER BY BatchNo, LineId, ItemKey";
                        $originalIngredients = $this->dbService->getNwfth2Db()->query($originalIngredientsQuery)->getResultArray();
                        log_message('info', "CreateRunPartial DEBUG - RunNo $runNo original PNITEM ingredients: " . json_encode($originalIngredients));
                    } catch (\Exception $debugError) {
                        log_message('error', "CreateRunPartial DEBUG - Could not query PNITEM: " . $debugError->getMessage());
                    }
                }
                
                // CRITICAL: Follow user-specified database strategy - read from TFCPILOT3 first (more stable)
                // Try TFCPILOT3 first with full INMAST join for descriptions
                $ingredientsData = $this->dbService->getNwfth2Db()->query($ingredientsQuery, [$runNo])->getResultArray();
                log_message('info', "CreateRunPartial: Found " . count($ingredientsData) . " raw ingredients from TFCPILOT3 (primary read)");
            } catch (\Exception $e) {
                // If no data found in TFCPILOT3, fallback to TFCMOBILE (without INMAST join)
                log_message('info', "CreateRunPartial: TFCPILOT3 failed, trying TFCMOBILE fallback - " . $e->getMessage());
                
                $simpleIngredientsQuery = "
                    SELECT 
                        cp.ItemKey,
                        cp.LineId,
                        cp.ItemKey as Description,
                        cp.Unit,
                        cp.StandardQty,
                        cp.PackSize,
                        cp.ToPickedPartialQty,
                        cp.PickedPartialQty,
                        cp.BatchNo,
                        cp.RowNum
                    FROM cust_PartialPicked cp
                    WHERE cp.RunNo = ? 
                    ORDER BY cp.LineId, cp.ItemKey, cp.RowNum
                    ";
                    
                $ingredientsData = $this->dbService->getNwfthDb()->query($simpleIngredientsQuery, [$runNo])->getResultArray();
                log_message('info', "CreateRunPartial: Found " . count($ingredientsData) . " raw ingredients from TFCMOBILE (fallback)");
                
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
                        log_message('warning', "CreateRunPartial: Could not get description for ItemKey: {$ingredient['ItemKey']}");
                    }
                }
                log_message('info', "CreateRunPartial: Enhanced descriptions from TFCPILOT3 INMAST table");
            }
            
            // CRITICAL: Official app consolidation logic - Group by LineId (not ItemKey)
            // This ensures INSOYF01+INSOYI01 at same LineId become single ingredient (matches CreateRunBulk)
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
            log_message('info', "CreateRunPartial: Grouped ingredients by LineId - " . count($ingredientGroups) . " groups found");
                
            
            // For each LineId group, consolidate ingredients and sum quantities (same as CreateRunBulk)
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
                $totalPartialQty = 0;
                $qtyPerBatch = floatval($representativeIngredient['StandardQty'] ?? 0);
                $partialQtyPerBatch = floatval($representativeIngredient['ToPickedPartialQty'] ?? 0);
                
                foreach ($ingredients as $ingredient) {
                    $totalStandardQty += floatval($ingredient['StandardQty'] ?? 0);
                    $totalPartialQty += floatval($ingredient['ToPickedPartialQty'] ?? 0);
                }
                
                log_message('info', "CreateRunPartial: LineId $lineId - Representative: {$representativeIngredient['ItemKey']}, Total Qty: $totalStandardQty, Total Partial: $totalPartialQty");
                
                $consolidatedIngredients[] = [
                    'ItemKey' => $representativeIngredient['ItemKey'],
                    'LineId' => $representativeIngredient['LineId'],
                    'Description' => $representativeIngredient['Description'],
                    'Unit' => $representativeIngredient['Unit'],
                    'PackSize' => floatval($representativeIngredient['PackSize'] ?? 0),
                    'QtyPerBatch' => $qtyPerBatch,
                    'TotalQty' => $totalStandardQty,
                    'ToPickedPartialQty' => $partialQtyPerBatch
                ];
            }
            
            // Sort by LineId for consistent ordering
            usort($consolidatedIngredients, function($a, $b) {
                return $a['LineId'] <=> $b['LineId'];
            });
            
            log_message('info', "CreateRunPartial: Found " . count($consolidatedIngredients) . " consolidated ingredients for RunNo $runNo");
            
            // Use consolidated ingredients for response
            $ingredientsData = $consolidatedIngredients;
            
            // Skip the rest of the old complex logic
            if (false) {
                // This old complex logic has been replaced by consolidated ingredients above
                // Keeping for reference but disabled
            } // End of if (false) - old complex logic skipped
            
            // Update run data with calculated totals and FG item information
            $runData['TotalFgQty'] = $totalFgQty;
            $runData['TotalBatches'] = count($allRunData);
            
            // Add FG item data to run header (what official app shows in header)
            if ($fgItemData) {
                $runData['FG_ItemKey'] = $fgItemData['FG_ItemKey'];
                $runData['FG_Description'] = $fgItemData['FG_Description'];
                $runData['FG_StandardQty'] = $fgItemData['FG_StandardQty'];
                log_message('info', "CreateRunPartial: FG Item added to header - {$fgItemData['FG_ItemKey']} ({$fgItemData['FG_Description']})");
            }
            
            // Log retrieval for debugging
            log_message('info', "CreateRunPartial: Retrieved print details for RunNo $runNo - " . 
                       "Batches found: " . count($allRunData) . ", Total FG Qty: $totalFgQty, Ingredients found: " . count($ingredientsData));
                       
            // Log final ingredient data for debugging
            foreach ($ingredientsData as $ing) {
                log_message('info', "CreateRunPartial FINAL: {$ing['ItemKey']} - QtyPerBatch: {$ing['QtyPerBatch']}, TotalQty: {$ing['TotalQty']}, PartialQty: {$ing['ToPickedPartialQty']}");
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'run' => $runData,
                    'batches' => $batchNumbers,
                    'ingredients' => $ingredientsData,
                    'fg_item' => $fgItemData // Include FG item data separately for easy access
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'CreateRunPartial getRunDetails error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to retrieve run details: ' . $e->getMessage()
            ]);
        }
    }
}