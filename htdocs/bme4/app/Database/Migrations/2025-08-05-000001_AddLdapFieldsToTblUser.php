<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLdapFieldsToTblUser extends Migration
{
    public function up()
    {
        // Add LDAP-related fields to tbl_user table
        $fields = [
            'email' => [
                'type' => 'NVARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'User email address from LDAP or manual entry'
            ],
            'department' => [
                'type' => 'NVARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'User department from LDAP'
            ],
            'auth_source' => [
                'type' => 'NVARCHAR',
                'constraint' => 10,
                'null' => true,
                'default' => 'LOCAL',
                'comment' => 'Authentication source: LDAP or LOCAL'
            ],
            'ldap_username' => [
                'type' => 'NVARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'LDAP sAMAccountName'
            ],
            'ldap_dn' => [
                'type' => 'NVARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'LDAP Distinguished Name'
            ],
            'last_ldap_sync' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Last LDAP synchronization timestamp'
            ],
            'ad_enabled' => [
                'type' => 'BIT',
                'null' => true,
                'default' => 1,
                'comment' => 'Active Directory account status'
            ]
        ];

        $this->forge->addColumn('tbl_user', $fields);
        
        // Add indexes for performance
        $this->forge->addKey('ldap_username', false, false, 'idx_tbl_user_ldap_username');
        $this->forge->addKey('auth_source', false, false, 'idx_tbl_user_auth_source');
        $this->forge->addKey('email', false, false, 'idx_tbl_user_email');
    }

    public function down()
    {
        // Remove the added fields
        $fields = [
            'email',
            'department', 
            'auth_source',
            'ldap_username',
            'ldap_dn',
            'last_ldap_sync',
            'ad_enabled'
        ];

        $this->forge->dropColumn('tbl_user', $fields);
        
        // Drop indexes (SQL Server syntax)
        $this->db->query('DROP INDEX idx_tbl_user_ldap_username ON tbl_user');
        $this->db->query('DROP INDEX idx_tbl_user_auth_source ON tbl_user');  
        $this->db->query('DROP INDEX idx_tbl_user_email ON tbl_user');
    }
}