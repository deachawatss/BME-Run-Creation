<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * LDAP/Active Directory Configuration
 */
class Ldap extends BaseConfig
{
    /**
     * Enable LDAP authentication
     */
    public bool $enabled = true;

    /**
     * LDAP Server Configuration
     */
    public string $host = '192.168.0.1';
    public int $port = 389;
    public bool $useSsl = false;
    public bool $useTls = false;

    /**
     * Active Directory Domain Configuration
     */
    public string $baseDn = 'DC=NWFTH,DC=com';
    public string $userDn = 'CN=Users,DC=NWFTH,DC=com';
    public string $domain = 'NWFTH.com';

    /**
     * Service Account for LDAP Queries
     */
    public string $bindUser = 'deachawat@newlywedsfoods.co.th';
    public string $bindPassword = 'Wind@password1234';

    /**
     * User Attribute Mapping
     */
    public array $attributes = [
        'username'    => 'sAMAccountName',
        'email'       => 'mail',
        'firstname'   => 'givenName',
        'lastname'    => 'sn',
        'displayname' => 'displayName',
        'department'  => 'department',
        'title'       => 'title'
    ];

    /**
     * Authentication Options
     */
    public bool $authFallback = true;  // Fall back to local DB if LDAP fails
    public bool $createUsers = true;   // Auto-create users from LDAP
    public bool $updateUsers = true;   // Update existing users from LDAP

    /**
     * Connection timeout in seconds
     */
    public int $timeout = 10;

    /**
     * Search timeout in seconds
     */
    public int $searchTimeout = 10;

    public function __construct()
    {
        parent::__construct();

        // Override with environment variables if available
        $this->enabled = env('ldap.enabled', $this->enabled);
        $this->host = env('ldap.host', $this->host);
        $this->port = env('ldap.port', $this->port);
        $this->useSsl = env('ldap.use_ssl', $this->useSsl);
        $this->useTls = env('ldap.use_tls', $this->useTls);
        
        $this->baseDn = env('ldap.base_dn', $this->baseDn);
        $this->userDn = env('ldap.user_dn', $this->userDn);
        $this->domain = env('ldap.domain', $this->domain);
        
        $this->bindUser = env('ldap.bind_user', $this->bindUser);
        $this->bindPassword = env('ldap.bind_password', $this->bindPassword);
        
        $this->authFallback = env('ldap.auth_fallback', $this->authFallback);
        $this->createUsers = env('ldap.create_users', $this->createUsers);
        $this->updateUsers = env('ldap.update_users', $this->updateUsers);

        // Update attributes array with environment variables
        $this->attributes['username'] = env('ldap.attr_username', $this->attributes['username']);
        $this->attributes['email'] = env('ldap.attr_email', $this->attributes['email']);
        $this->attributes['firstname'] = env('ldap.attr_firstname', $this->attributes['firstname']);
        $this->attributes['lastname'] = env('ldap.attr_lastname', $this->attributes['lastname']);
        $this->attributes['displayname'] = env('ldap.attr_displayname', $this->attributes['displayname']);
        $this->attributes['department'] = env('ldap.attr_department', $this->attributes['department']);
        $this->attributes['title'] = env('ldap.attr_title', $this->attributes['title']);
    }
}