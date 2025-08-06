<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'tbl_user'; // TFCMOBILE user table
    protected $primaryKey = 'userid';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'uname',
        'pword',
        'Fname',
        'Lname',
        'ulvl',
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
        'full_name' => 'required|max_length[100]',
        'email' => 'permit_empty|valid_email|max_length[100]'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters',
            'max_length' => 'Username cannot exceed 50 characters',
            'is_unique' => 'Username already exists'
        ],
        'full_name' => [
            'required' => 'Full name is required',
            'max_length' => 'Full name cannot exceed 100 characters'
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address',
            'max_length' => 'Email cannot exceed 100 characters'
        ]
    ];

    protected $skipValidation = false;
    
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect('nwfth_db'); // Use TFCMOBILE database
    }

    /**
     * Authenticate user against LDAP and local database
     */
    public function authenticateUser($username, $password)
    {
        try {
            log_message('debug', "Starting authentication for user: $username");
            
            // Check if LDAP is enabled and extension is available
            $ldapConfig = new \Config\Ldap();
            
            if ($ldapConfig->enabled && extension_loaded('ldap')) {
                log_message('debug', 'LDAP authentication enabled, attempting LDAP authentication');
                
                // Try LDAP authentication first
                $ldapService = new \App\Services\LdapService();
                $ldapResult = $ldapService->authenticate($username, $password);
                
                if ($ldapResult['success']) {
                    log_message('debug', 'LDAP authentication successful');
                    
                    // Update or create user record in local database
                    $localUser = $this->updateOrCreateUser($username, $ldapResult['user_data']);
                    
                    return [
                        'success' => true,
                        'user_id' => $localUser['userid'] ?? $username,
                        'full_name' => $localUser['Fname'] . ' ' . $localUser['Lname'],
                        'email' => $localUser['email'] ?? $ldapResult['user_data']['email'] ?? '',
                        'department' => $ldapResult['user_data']['department'] ?? '',
                        'auth_source' => 'LDAP'
                    ];
                } else {
                    log_message('debug', 'LDAP authentication failed: ' . ($ldapResult['message'] ?? 'Unknown error'));
                    
                    // If LDAP fallback is enabled, try local authentication
                    if ($ldapConfig->authFallback) {
                        log_message('debug', 'Falling back to local database authentication');
                        return $this->authenticateLocal($username, $password);
                    } else {
                        return $ldapResult; // Return LDAP error
                    }
                }
            } else {
                log_message('debug', 'LDAP disabled or extension not available, using database authentication');
                return $this->authenticateLocal($username, $password);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Authentication error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return [
                'success' => false,
                'message' => 'Authentication system error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update or create user record from LDAP data
     */
    private function updateOrCreateUser($username, $ldapData)
    {
        try {
            $nwfth_db = \Config\Database::connect('nwfth_db');
            
            // Check if user exists
            $builder = $nwfth_db->table('tbl_user');
            $existingUser = $builder->where('uname', $username)->get()->getRowArray();
            
            $userData = [
                'uname' => $username,
                'Fname' => $ldapData['firstname'] ?? '',
                'Lname' => $ldapData['lastname'] ?? '',
                'email' => $ldapData['email'] ?? '',
                'department' => $ldapData['department'] ?? '',
                'auth_source' => 'LDAP',
                'ldap_username' => $ldapData['username'] ?? $username,
                'ldap_dn' => $ldapData['dn'] ?? '',
                'last_ldap_sync' => date('Y-m-d H:i:s'),
                'ulvl' => 1 // Default user level
            ];
            
            if ($existingUser) {
                // Update existing user
                $builder->where('userid', $existingUser['userid'])->update($userData);
                log_message('debug', "Updated existing user {$username} from LDAP");
                
                // Return updated user data
                return array_merge($existingUser, $userData);
            } else {
                // Create new user
                $userData['created_at'] = date('Y-m-d H:i:s');
                $userData['pword'] = ''; // No password needed for LDAP users
                
                $builder->insert($userData);
                $newUserId = $nwfth_db->insertID();
                
                log_message('info', "Created new user {$username} from LDAP with ID {$newUserId}");
                
                // Return new user data
                $userData['userid'] = $newUserId;
                return $userData;
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error updating/creating user from LDAP: ' . $e->getMessage());
            
            // Return basic user data even if database update fails
            return [
                'userid' => $username,
                'uname' => $username,
                'Fname' => $ldapData['firstname'] ?? '',
                'Lname' => $ldapData['lastname'] ?? '',
                'email' => $ldapData['email'] ?? '',
                'department' => $ldapData['department'] ?? ''
            ];
        }
    }

    /**
     * Local Database Authentication
     */
    private function authenticateLocal($username, $password)
    {
        try {
            log_message('debug', "Attempting authentication for user: $username");
            
            // Connect to TFCMOBILE database for user authentication
            $nwfth_db = \Config\Database::connect('nwfth_db'); // TFCMOBILE database
            
            // Query user from tbl_user table
            $builder = $nwfth_db->table('tbl_user');
            $user = $builder->where('uname', $username)->get()->getRowArray();
            
            log_message('debug', 'User query result: ' . json_encode($user ? 'User found' : 'User not found'));
            
            if (!$user) {
                log_message('debug', 'User not found in database');
                return [
                    'success' => false,
                    'message' => 'Invalid username or password'
                ];
            }
            
            // Verify password (support both plain text and hashed passwords)
            $passwordMatch = false;
            if (password_verify($password, $user['pword'])) {
                log_message('debug', 'Password verified with bcrypt');
                $passwordMatch = true;
            } elseif ($user['pword'] === $password) {
                log_message('debug', 'Password verified with plain text');
                $passwordMatch = true;
            }
            
            if ($passwordMatch) {
                log_message('debug', 'Authentication successful');
                
                // Note: last_login column doesn't exist in tbl_user table, so we skip the update
                log_message('debug', 'Skipping last_login update (column does not exist)');
                
                $result = [
                    'success' => true,
                    'user_id' => $user['userid'],
                    'username' => $user['uname'],
                    'full_name' => trim($user['Fname'] . ' ' . $user['Lname']),
                    'first_name' => $user['Fname'],
                    'last_name' => $user['Lname'],
                    'email' => $user['uname'] . '@nwfth.com',
                    'department' => 'Production',
                    'user_level' => $user['ulvl']
                ];
                
                log_message('debug', 'Returning success result: ' . json_encode($result));
                return $result;
            }
            
            log_message('debug', 'Password verification failed');
            return [
                'success' => false,
                'message' => 'Invalid username or password'
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Database authentication error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return [
                'success' => false,
                'message' => 'Authentication system error: ' . $e->getMessage()
            ];
        }
    }


    /**
     * Get user profile information
     */
    public function getUserProfile($username)
    {
        return $this->where('uname', $username)->first();
    }

    /**
     * Change user password (LDAP-based systems would need different implementation)
     */
    public function changePassword($username, $currentPassword, $newPassword)
    {
        // For LDAP-based systems, this would need to connect to LDAP
        // For now, return a placeholder response
        
        // First verify current password
        $authResult = $this->authenticateUser($username, $currentPassword);
        
        if (!$authResult['success']) {
            return [
                'success' => false,
                'message' => 'Current password is incorrect'
            ];
        }

        // In a real LDAP implementation, you would update the password in LDAP
        // For now, we'll just log the attempt
        log_message('info', "Password change attempt for user: {$username}");
        
        return [
            'success' => false,
            'message' => 'Password changes must be done through the company directory system'
        ];
    }

    /**
     * Get user activity log
     */
    public function getUserActivity($username, $limit = 50)
    {
        // This would typically come from an activity log table
        // For now, return basic structure
        return [
            [
                'activity' => 'Login',
                'timestamp' => date('Y-m-d H:i:s'),
                'ip_address' => service('request')->getIPAddress(),
                'user_agent' => service('request')->getUserAgent()->getAgentString()
            ]
        ];
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission($username, $permission)
    {
        // This would typically check against a permissions/roles table
        // For now, return true for basic access
        return true;
    }

    /**
     * Get user roles
     */
    public function getUserRoles($username)
    {
        // This would typically come from a user_roles table
        // For now, return basic structure
        return ['user']; // Default role
    }

    /**
     * Log user activity
     */
    public function logActivity($username, $activity, $details = null)
    {
        try {
            // This would typically insert into an activity log table
            log_message('info', "User Activity - {$username}: {$activity}" . ($details ? " - {$details}" : ''));
            
        } catch (\Exception $e) {
            log_message('error', 'Error logging user activity: ' . $e->getMessage());
        }
    }

    /**
     * Validate session
     */
    public function validateSession($sessionData)
    {
        if (!isset($sessionData['username']) || !isset($sessionData['logged_in'])) {
            return false;
        }

        // Check if user is still active
        $user = $this->getUserProfile($sessionData['username']);
        
        return $user && $user['status'] === 'active';
    }
}