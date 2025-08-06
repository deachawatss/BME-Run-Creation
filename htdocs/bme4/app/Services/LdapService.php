<?php

namespace App\Services;

use Config\Ldap as LdapConfig;

/**
 * LDAP/Active Directory Service
 * 
 * Handles authentication and user information retrieval from Active Directory
 */
class LdapService
{
    protected LdapConfig $config;
    protected $ldapConnection;
    protected bool $isConnected = false;

    public function __construct()
    {
        $this->config = new LdapConfig();
    }

    /**
     * Authenticate user against Active Directory
     */
    public function authenticate(string $username, string $password): array
    {
        try {
            log_message('debug', "LDAP: Starting authentication for user: {$username}");

            if (!$this->config->enabled) {
                log_message('debug', 'LDAP: LDAP authentication is disabled');
                return [
                    'success' => false,
                    'message' => 'LDAP authentication is disabled'
                ];
            }

            if (!extension_loaded('ldap')) {
                log_message('error', 'LDAP: PHP LDAP extension is not loaded');
                return [
                    'success' => false,
                    'message' => 'LDAP extension not available'
                ];
            }

            // Connect to LDAP server
            if (!$this->connect()) {
                return [
                    'success' => false,
                    'message' => 'Could not connect to Active Directory server'
                ];
            }

            // Try to authenticate the user
            $userDn = $this->buildUserDn($username);
            log_message('debug', "LDAP: Attempting to bind with DN: {$userDn}");

            $bind = @ldap_bind($this->ldapConnection, $userDn, $password);
            
            if (!$bind) {
                $ldapError = ldap_error($this->ldapConnection);
                log_message('debug', "LDAP: Authentication failed - {$ldapError}");
                $this->disconnect();
                return [
                    'success' => false,
                    'message' => 'Invalid username or password'
                ];
            }

            log_message('debug', "LDAP: Authentication successful for {$username}");

            // Get user information from Active Directory
            $userData = $this->getUserInfo($username);
            $this->disconnect();

            return [
                'success' => true,
                'user_data' => $userData,
                'message' => 'Authentication successful'
            ];

        } catch (\Exception $e) {
            log_message('error', 'LDAP: Authentication error - ' . $e->getMessage());
            $this->disconnect();
            return [
                'success' => false,
                'message' => 'LDAP authentication error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get user information from Active Directory
     */
    public function getUserInfo(string $username): array
    {
        try {
            if (!$this->isConnected && !$this->connect()) {
                return [];
            }

            // Search for user in Active Directory
            $searchFilter = "(&(objectClass=user)({$this->config->attributes['username']}={$username}))";
            log_message('debug', "LDAP: Searching with filter: {$searchFilter}");

            $searchResult = ldap_search(
                $this->ldapConnection,
                $this->config->baseDn,
                $searchFilter,
                array_values($this->config->attributes)
            );

            if (!$searchResult) {
                log_message('error', 'LDAP: Search failed - ' . ldap_error($this->ldapConnection));
                return [];
            }

            $entries = ldap_get_entries($this->ldapConnection, $searchResult);
            
            if ($entries['count'] === 0) {
                log_message('debug', "LDAP: User {$username} not found in Active Directory");
                return [];
            }

            $userEntry = $entries[0];
            
            // Map LDAP attributes to our user data structure
            $userData = [
                'username' => $this->getLdapAttribute($userEntry, $this->config->attributes['username']),
                'email' => $this->getLdapAttribute($userEntry, $this->config->attributes['email']),
                'firstname' => $this->getLdapAttribute($userEntry, $this->config->attributes['firstname']),
                'lastname' => $this->getLdapAttribute($userEntry, $this->config->attributes['lastname']),
                'displayname' => $this->getLdapAttribute($userEntry, $this->config->attributes['displayname']),
                'department' => $this->getLdapAttribute($userEntry, $this->config->attributes['department']),
                'title' => $this->getLdapAttribute($userEntry, $this->config->attributes['title']),
                'dn' => $userEntry['dn'] ?? '',
                'auth_source' => 'LDAP'
            ];

            // Build full name if displayname is not available
            if (empty($userData['displayname'])) {
                $userData['displayname'] = trim($userData['firstname'] . ' ' . $userData['lastname']);
            }

            log_message('debug', "LDAP: Retrieved user info for {$username}: " . json_encode(array_filter($userData)));

            return $userData;

        } catch (\Exception $e) {
            log_message('error', 'LDAP: Error getting user info - ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Connect to LDAP server
     */
    protected function connect(): bool
    {
        try {
            if ($this->isConnected) {
                return true;
            }

            $ldapUrl = ($this->config->useSsl ? 'ldaps://' : 'ldap://') . $this->config->host;
            log_message('debug', "LDAP: Connecting to {$ldapUrl}:{$this->config->port}");

            $this->ldapConnection = ldap_connect($ldapUrl, $this->config->port);
            
            if (!$this->ldapConnection) {
                log_message('error', 'LDAP: Failed to connect to server');
                return false;
            }

            // Set LDAP options
            ldap_set_option($this->ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($this->ldapConnection, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($this->ldapConnection, LDAP_OPT_NETWORK_TIMEOUT, $this->config->timeout);
            ldap_set_option($this->ldapConnection, LDAP_OPT_TIMELIMIT, $this->config->searchTimeout);

            // Start TLS if configured
            if ($this->config->useTls && !$this->config->useSsl) {
                if (!ldap_start_tls($this->ldapConnection)) {
                    log_message('error', 'LDAP: Failed to start TLS - ' . ldap_error($this->ldapConnection));
                    return false;
                }
            }

            // Bind with service account if configured
            if (!empty($this->config->bindUser) && !empty($this->config->bindPassword)) {
                $bind = @ldap_bind($this->ldapConnection, $this->config->bindUser, $this->config->bindPassword);
                if (!$bind) {
                    log_message('error', 'LDAP: Failed to bind with service account - ' . ldap_error($this->ldapConnection));
                    return false;
                }
                log_message('debug', 'LDAP: Successfully bound with service account');
            }

            $this->isConnected = true;
            log_message('debug', 'LDAP: Successfully connected to Active Directory');
            return true;

        } catch (\Exception $e) {
            log_message('error', 'LDAP: Connection error - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Disconnect from LDAP server
     */
    protected function disconnect(): void
    {
        if ($this->ldapConnection && $this->isConnected) {
            ldap_close($this->ldapConnection);
            $this->isConnected = false;
            log_message('debug', 'LDAP: Disconnected from Active Directory');
        }
    }

    /**
     * Build user DN for authentication
     */
    protected function buildUserDn(string $username): string
    {
        // For Active Directory, we can use UPN format (username@domain)
        if (strpos($username, '@') === false) {
            return $username . '@' . $this->config->domain;
        }
        
        return $username; // Already in UPN format
    }

    /**
     * Get LDAP attribute value safely
     */
    protected function getLdapAttribute(array $entry, string $attribute): string
    {
        $attribute = strtolower($attribute);
        
        if (isset($entry[$attribute][0])) {
            return $entry[$attribute][0];
        }
        
        return '';
    }

    /**
     * Test LDAP connection
     */
    public function testConnection(): array
    {
        try {
            if (!extension_loaded('ldap')) {
                return [
                    'success' => false,
                    'message' => 'PHP LDAP extension is not loaded'
                ];
            }

            if ($this->connect()) {
                $this->disconnect();
                return [
                    'success' => true,
                    'message' => 'Successfully connected to Active Directory'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to connect to Active Directory'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cleanup on destruction
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}