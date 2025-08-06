<?php

namespace App\Models;

use App\Models\BaseModel;

class CreateRunPartialModel extends BaseModel
{
    protected $table = 'Cust_PartialRun';
    protected $primaryKey = 'RunNo';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'RunNo',
        'RowNum',
        'BatchNo',
        'FormulaId',
        'FormulaDesc',
        'NoOfBatches',
        'PalletsPerBatch',
        'Status',
        'RecUserId',
        'RecDate',
        'ModifiedBy',
        'ModifiedDate',
        'User1',
        'User2',
        'User3',
        'User4',
        'User5',
        'User6',
        'User7',
        'User8',
        'User9',
        'User10',
        'User11',
        'User12',
        'CUSTOM1',
        'CUSTOM2',
        'CUSTOM3',
        'CUSTOM4',
        'CUSTOM5',
        'CUSTOM6',
        'CUSTOM7',
        'CUSTOM8',
        'CUSTOM9',
        'CUSTOM10',
        'ESG_REASON',
        'ESG_APPROVER'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'RecDate';
    protected $updatedField = 'RecDate';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'FormulaId' => 'required|max_length[30]',
        'BatchNo' => 'required|max_length[30]',
        'NoOfBatches' => 'permit_empty|integer|greater_than_equal_to[0]',
        'PalletsPerBatch' => 'permit_empty|integer|greater_than_equal_to[0]',
        'Status' => 'required|max_length[10]'
    ];

    protected $validationMessages = [
        'FormulaId' => [
            'required' => 'Formula ID is required',
            'max_length' => 'Formula ID cannot exceed 30 characters'
        ],
        'BatchNo' => [
            'required' => 'Batch Number is required',
            'max_length' => 'Batch Number cannot exceed 30 characters'
        ],
        'NoOfBatches' => [
            'integer' => 'Number of batches must be a valid integer',
            'greater_than_equal_to' => 'Number of batches must be 0 or greater'
        ],
        'PalletsPerBatch' => [
            'integer' => 'Pallets per batch must be a valid integer',
            'greater_than_equal_to' => 'Pallets per batch must be 0 or greater'
        ],
        'Status' => [
            'required' => 'Status is required',
            'max_length' => 'Status cannot exceed 10 characters'
        ]
    ];

    protected $skipValidation = false;
    
    // Database connections handled by BaseModel

    /**
     * Get partial runs with pagination and search
     */
    public function getPartialRuns($limit = 25, $offset = 0, $search = '')
    {
        $this->logOperation('getPartialRuns', ['limit' => $limit, 'offset' => $offset, 'search' => $search]);
        
        $sql = "SELECT RunNo, RowNum, BatchNo, FormulaId, FormulaDesc, NoOfBatches, PalletsPerBatch, Status, RecUserId, RecDate 
                FROM {$this->table}";
        
        $params = [];
        if (!empty($search)) {
            $sql .= " WHERE (CAST(RunNo AS NVARCHAR(50)) LIKE ? 
                          OR CAST(FormulaId AS NVARCHAR(50)) LIKE ? 
                          OR CAST(BatchNo AS NVARCHAR(50)) LIKE ? 
                          OR CAST(Status AS NVARCHAR(50)) LIKE ?)";
            $params = ["%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"];
        }
        
        $sql .= " ORDER BY RunNo DESC, RowNum ASC 
                  OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
        $params[] = $offset;
        $params[] = $limit;
        
        return $this->getFromNwfth2($sql, $params)->getResultArray();
    }

    /**
     * Get total count of partial runs with optional search
     */
    public function getPartialRunsCount($search = '')
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE (CAST(RunNo AS NVARCHAR(50)) LIKE ? 
                          OR CAST(FormulaId AS NVARCHAR(50)) LIKE ? 
                          OR CAST(BatchNo AS NVARCHAR(50)) LIKE ? 
                          OR CAST(Status AS NVARCHAR(50)) LIKE ?)";
            $params = ["%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"];
        }
        
        $result = $this->getFromNwfth2($sql, $params)->getRowArray();
        return $result['total'] ?? 0;
    }

    /**
     * Get specific partial run by RunNo
     */
    public function getPartialRun($runNo)
    {
        $sql = "SELECT * FROM {$this->table} WHERE RunNo = ?";
        return $this->getFromNwfth2($sql, [$runNo])->getRowArray();
    }

    /**
     * Create new partial run
     */
    public function createPartialRun($data)
    {
        // Validate and sanitize data
        $data = $this->sanitizeData($data);
        $errors = $this->validateRequired($data, ['FormulaId', 'BatchNo', 'Status']);
        
        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode(', ', $errors));
        }
        
        return $this->safeTransaction(function($db) use ($data) {
            // Generate next RunNo
            $nextRunNo = $this->getNextRunNo();
            $data['RunNo'] = $nextRunNo;
            $data['RowNum'] = 1; // Default row number
            $data['RecDate'] = date('Y-m-d H:i:s');
            
            $this->logOperation('createPartialRun', ['RunNo' => $nextRunNo, 'data' => $data]);
            
            // Build insert SQL with all allowed fields
            $fields = implode(', ', $this->allowedFields);
            $placeholders = str_repeat('?,', count($this->allowedFields) - 1) . '?';
            $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
            
            $params = [];
            foreach ($this->allowedFields as $field) {
                $params[] = $data[$field] ?? null;
            }
            
            $this->safeQuery($sql, $params, 'nwfth_db');
            
            $this->clearModelCache();
            
            return [
                'success' => true,
                'RunNo' => $nextRunNo,
                'message' => 'Partial run created successfully'
            ];
        }, 'nwfth_db');
    }

    /**
     * Update existing partial run
     */
    public function updatePartialRun($runNo, $data)
    {
        $builder = $this->nwfth_db->table($this->table);
        
        if ($builder->where('RunNo', $runNo)->update($data)) {
            return [
                'success' => true,
                'message' => 'Partial run updated successfully'
            ];
        } else {
            throw new \Exception('Failed to update partial run data');
        }
    }

    /**
     * Delete partial run
     */
    public function deletePartialRun($runNo)
    {
        $builder = $this->nwfth_db->table($this->table);
        
        if ($builder->where('RunNo', $runNo)->delete()) {
            return [
                'success' => true,
                'message' => 'Partial run deleted successfully'
            ];
        } else {
            throw new \Exception('Failed to delete partial run data');
        }
    }

    /**
     * Get next available RunNo
     */
    public function getNextRunNo()
    {
        $sql = "SELECT MAX(RunNo) as max_run_no FROM {$this->table}";
        $result = $this->getFromNwfth($sql)->getRowArray();
        
        $maxRunNo = $result['max_run_no'] ?? 0;
        return $maxRunNo + 1;
    }

    /**
     * Get formula list for dropdown with caching
     */
    public function getFormulaList()
    {
        return $this->getCachedRecord('partial_formula_list', function() {
            $sql = "SELECT DISTINCT FormulaId, FormulaDesc 
                    FROM {$this->table} 
                    WHERE FormulaId IS NOT NULL AND FormulaId != '' 
                    ORDER BY FormulaId ASC";
            
            return $this->getFromNwfth2($sql)->getResultArray();
        }, 3600); // Cache for 1 hour
    }

    /**
     * Get batch statistics with caching
     */
    public function getBatchStatistics($runNo = null)
    {
        $cacheKey = $runNo ? "partial_batch_stats_{$runNo}" : 'partial_batch_stats_all';
        
        return $this->getCachedRecord($cacheKey, function() use ($runNo) {
            $sql = "SELECT 
                        COUNT(*) as total_runs,
                        SUM(NoOfBatches) as total_batches,
                        SUM(PalletsPerBatch * NoOfBatches) as total_pallets,
                        AVG(CAST(NoOfBatches AS FLOAT)) as avg_batches_per_run,
                        AVG(CAST(PalletsPerBatch AS FLOAT)) as avg_pallets_per_batch
                    FROM {$this->table}";
            
            $params = [];
            if ($runNo) {
                $sql .= " WHERE RunNo = ?";
                $params[] = $runNo;
            }
            
            return $this->getFromNwfth2($sql, $params)->getRowArray();
        }, 300); // Cache for 5 minutes
    }

    /**
     * Get runs by status
     */
    public function getRunsByStatus($status = null)
    {
        $builder = $this->nwfth2_db->table($this->table);
        
        if ($status) {
            $builder->where('Status', $status);
        }

        return $builder->select('Status, COUNT(*) as count')
                      ->groupBy('Status')
                      ->orderBy('Status', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Search partial runs with advanced filters
     */
    public function searchPartialRuns($filters = [])
    {
        $builder = $this->nwfth2_db->table($this->table);
        
        // Apply filters
        if (!empty($filters['run_no'])) {
            $builder->like('CAST(RunNo AS NVARCHAR(50))', $filters['run_no']);
        }
        
        if (!empty($filters['formula_id'])) {
            $builder->like('CAST(FormulaId AS NVARCHAR(50))', $filters['formula_id']);
        }
        
        if (!empty($filters['batch_no'])) {
            $builder->like('CAST(BatchNo AS NVARCHAR(50))', $filters['batch_no']);
        }
        
        if (!empty($filters['status'])) {
            $builder->where('Status', $filters['status']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('CAST(RecDate AS DATE) >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('CAST(RecDate AS DATE) <=', $filters['date_to']);
        }
        
        if (!empty($filters['user_id'])) {
            $builder->where('RecUserId', $filters['user_id']);
        }

        return $builder->select('RunNo, RowNum, BatchNo, FormulaId, FormulaDesc, NoOfBatches, PalletsPerBatch, Status, RecUserId, RecDate')
                      ->orderBy('RunNo', 'DESC')
                      ->orderBy('RowNum', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get partial run summary data
     */
    public function getRunSummary($runNo)
    {
        $sql = "SELECT 
                    RunNo,
                    COUNT(*) as total_batches,
                    SUM(NoOfBatches) as total_no_of_batches,
                    SUM(PalletsPerBatch) as total_pallets,
                    MIN(RecDate) as created_date,
                    MAX(ModifiedDate) as last_modified,
                    STRING_AGG(DISTINCT FormulaId, ', ') as formula_ids,
                    STRING_AGG(DISTINCT Status, ', ') as statuses
                FROM {$this->table} 
                WHERE RunNo = ?
                GROUP BY RunNo";
        
        return $this->getFromNwfth2($sql, [$runNo])->getRowArray();
    }

    /**
     * Check if batch is already used in any partial run
     */
    public function isBatchUsed($batchNo, $excludeRunNo = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE BatchNo = ?";
        $params = [$batchNo];
        
        if ($excludeRunNo) {
            $sql .= " AND RunNo != ?";
            $params[] = $excludeRunNo;
        }
        
        $result = $this->getFromNwfth2($sql, $params)->getRowArray();
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get available batches for partial runs (not used in any existing runs)
     */
    public function getAvailableBatches($limit = 100, $offset = 0, $filters = [])
    {
        $builder = $this->nwfth2_db->table('PNMAST');
        
        // Base conditions
        $builder->where('BatchType', 'M');
        $builder->where('Status', 'R');
        $builder->where('YEAR(EntryDate) >=', 2024);
        $builder->whereIn('ProcessCellId', ['AUSSIE','FENDER-L','FENDER-S','GIBSON','HOBART','TEXAS','YANKEE']);
        
        // Apply filters
        if (!empty($filters['formula_id'])) {
            $builder->where('FormulaID', $filters['formula_id']);
        }
        
        if (!empty($filters['batch_weight'])) {
            $builder->where('BatchWeight', $filters['batch_weight']);
        }
        
        // Exclude batches already used in partial runs
        $usedBatchesQuery = $this->nwfth2_db->table($this->table)
                                          ->select('BatchNo')
                                          ->where('BatchNo IS NOT NULL')
                                          ->getCompiledSelect();
        $builder->where("BatchNo NOT IN ($usedBatchesQuery)");
        
        return $builder->select('BatchNo, FormulaID, BatchWeight, BatchTicketDate, Description as FormulaDesc')
                      ->orderBy('BatchTicketDate', 'DESC')
                      ->orderBy('BatchNo', 'DESC')
                      ->limit($limit, $offset)
                      ->get()
                      ->getResultArray();
    }
}