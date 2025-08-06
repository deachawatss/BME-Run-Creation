<?php

namespace App\Controllers;

use App\Services\LdapService;

class LdapTest extends BaseController
{
    public function index()
    {
        // Simple test page for LDAP connectivity
        $data = [
            'title' => 'LDAP Connection Test',
            'page_title' => 'Active Directory Connection Test'
        ];

        return view('ldap_test', $data);
    }

    public function testConnection()
    {
        // Test LDAP connection
        $ldapService = new LdapService();
        $result = $ldapService->testConnection();

        return $this->response->setJSON($result);
    }

    public function testAuth()
    {
        // Test authentication with provided credentials
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Username and password are required'
            ]);
        }

        $ldapService = new LdapService();
        $result = $ldapService->authenticate($username, $password);

        // Don't return sensitive information
        if (isset($result['user_data'])) {
            unset($result['user_data']['dn']);
        }

        return $this->response->setJSON($result);
    }

    public function phpInfo()
    {
        // Check PHP LDAP extension and configuration
        $ldapInfo = [
            'ldap_extension_loaded' => extension_loaded('ldap'),
            'php_version' => PHP_VERSION,
            'ldap_functions' => []
        ];

        if (extension_loaded('ldap')) {
            $ldapInfo['ldap_functions'] = [
                'ldap_connect' => function_exists('ldap_connect'),
                'ldap_bind' => function_exists('ldap_bind'),
                'ldap_search' => function_exists('ldap_search'),
                'ldap_start_tls' => function_exists('ldap_start_tls')
            ];
        }

        return $this->response->setJSON($ldapInfo);
    }
}