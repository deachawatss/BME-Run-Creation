<?php

namespace App\Models;

use App\Models\BaseModel;

class CreateRunBulkModel extends BaseModel
{
    protected $table = 'Cust_BulkRun';
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
        'RecDate'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'RecDate';
    protected $updatedField = 'RecDate';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'FormulaId' => 'required|max_length[50]',
        'NoOfBatches' => 'required|integer|greater_than[0]',
        'PalletsPerBatch' => 'required|integer|greater_than[0]',
        'Status' => 'required|max_length[20]'
    ];

    protected $validationMessages = [
        'FormulaId' => [
            'required' => 'Formula ID is required',
            'max_length' => 'Formula ID cannot exceed 50 characters'
        ],
        'NoOfBatches' => [
            'required' => 'Number of batches is required',
            'integer' => 'Number of batches must be a valid integer',
            'greater_than' => 'Number of batches must be greater than 0'
        ],
        'PalletsPerBatch' => [
            'required' => 'Pallets per batch is required',
            'integer' => 'Pallets per batch must be a valid integer',
            'greater_than' => 'Pallets per batch must be greater than 0'
        ],
        'Status' => [
            'required' => 'Status is required',
            'max_length' => 'Status cannot exceed 20 characters'
        ]
    ];

    protected $skipValidation = false;
    
    // Database connections handled by BaseModel

    /**
     * Get bulk runs with pagination and search
     */
    public function getBulkRuns($limit = 25, $offset = 0, $search = '')
    {
        $this->logOperation('getBulkRuns', ['limit' => $limit, 'offset' => $offset, 'search' => $search]);
        
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
     * Get total count of bulk runs with optional search
     */
    public function getBulkRunsCount($search = '')
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
     * Get specific bulk run by RunNo
     */
    public function getBulkRun($runNo)
    {
        $sql = "SELECT * FROM {$this->table} WHERE RunNo = ?";
        return $this->getFromNwfth2($sql, [$runNo])->getRowArray();
    }

    /**
     * Create new bulk run
     */
    public function createBulkRun($data)
    {
        // Validate and sanitize data
        $data = $this->sanitizeData($data);
        $errors = $this->validateRequired($data, ['FormulaId', 'NoOfBatches', 'PalletsPerBatch', 'Status']);
        
        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode(', ', $errors));
        }
        
        return $this->safeTransaction(function($db) use ($data) {
            // Generate next RunNo
            $nextRunNo = $this->getNextRunNo();
            $data['RunNo'] = $nextRunNo;
            $data['RowNum'] = 1; // Default row number
            $data['RecDate'] = date('Y-m-d H:i:s');
            
            $this->logOperation('createBulkRun', ['RunNo' => $nextRunNo, 'data' => $data]);
            
            // Insert into primary database
            $sql = "INSERT INTO {$this->table} (RunNo, RowNum, BatchNo, FormulaId, FormulaDesc, NoOfBatches, PalletsPerBatch, Status, RecUserId, RecDate) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $data['RunNo'],
                $data['RowNum'],
                $data['BatchNo'] ?? '',
                $data['FormulaId'],
                $data['FormulaDesc'] ?? '',
                $data['NoOfBatches'],
                $data['PalletsPerBatch'],
                $data['Status'],
                $data['RecUserId'] ?? '',
                $data['RecDate']
            ];
            
            $this->safeQuery($sql, $params, 'nwfth_db');
            
            $this->clearModelCache();
            
            return [
                'success' => true,
                'RunNo' => $nextRunNo,
                'message' => 'Bulk run created successfully'
            ];
        }, 'nwfth_db');
    }

    /**
     * Update existing bulk run
     */
    public function updateBulkRun($runNo, $data)
    {
        $builder = $this->nwfth_db->table($this->table);
        
        if ($builder->where('RunNo', $runNo)->update($data)) {
            return [
                'success' => true,
                'message' => 'Bulk run updated successfully'
            ];
        } else {
            throw new \Exception('Failed to update bulk run data');
        }
    }

    /**
     * Delete bulk run
     */
    public function deleteBulkRun($runNo)
    {
        $builder = $this->nwfth_db->table($this->table);
        
        if ($builder->where('RunNo', $runNo)->delete()) {
            return [
                'success' => true,
                'message' => 'Bulk run deleted successfully'
            ];
        } else {
            throw new \Exception('Failed to delete bulk run data');
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
        return $this->getCachedRecord('formula_list', function() {
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
        $cacheKey = $runNo ? "batch_stats_{$runNo}" : 'batch_stats_all';
        
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
     * Search bulk runs with advanced filters
     */
    public function searchBulkRuns($filters = [])
    {
        $builder = $this->nwfth2_db->table($this->table);
        
        // Apply filters
        if (!empty($filters['run_no'])) {
            $builder->like('CAST(RunNo AS NVARCHAR(50))', $filters['run_no']);
        }
        
        if (!empty($filters['formula_id'])) {
            $builder->like('CAST(FormulaId AS NVARCHAR(50))', $filters['formula_id']);
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
}