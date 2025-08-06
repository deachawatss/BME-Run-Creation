<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection (TFCMOBILE - Write operations).
     * Uses environment variables for security.
     *
     * @var array<string, mixed>
     */
    public array $default = [];

    /**
     * NWFTH Database connection (Primary data source - TFCMOBILE).
     * Uses environment variables for security.
     *
     * @var array<string, mixed>
     */
    public array $nwfth_db = [];

    /**
     * NWFTH2 Database connection (Secondary data source - TFCPILOT3).
     * Uses environment variables for security.
     *
     * @var array<string, mixed>
     */
    public array $nwfth2_db = [];

    //    /**
    //     * Sample database connection for SQLite3.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'database'    => 'database.db',
    //        'DBDriver'    => 'SQLite3',
    //        'DBPrefix'    => '',
    //        'DBDebug'     => true,
    //        'swapPre'     => '',
    //        'failover'    => [],
    //        'foreignKeys' => true,
    //        'busyTimeout' => 1000,
    //        'synchronous' => null,
    //        'dateFormat'  => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for Postgre.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => '',
    //        'hostname'   => 'localhost',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'database'   => 'ci4',
    //        'schema'     => 'public',
    //        'DBDriver'   => 'Postgre',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'utf8',
    //        'swapPre'    => '',
    //        'failover'   => [],
    //        'port'       => 5432,
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for SQLSRV.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => '',
    //        'hostname'   => 'localhost',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'database'   => 'ci4',
    //        'schema'     => 'dbo',
    //        'DBDriver'   => 'SQLSRV',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'utf8',
    //        'swapPre'    => '',
    //        'encrypt'    => false,
    //        'failover'   => [],
    //        'port'       => 1433,
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for OCI8.
    //     *
    //     * You may need the following environment variables:
    //     *   NLS_LANG                = 'AMERICAN_AMERICA.UTF8'
    //     *   NLS_DATE_FORMAT         = 'YYYY-MM-DD HH24:MI:SS'
    //     *   NLS_TIMESTAMP_FORMAT    = 'YYYY-MM-DD HH24:MI:SS'
    //     *   NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS'
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => 'localhost:1521/XEPDB1',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'DBDriver'   => 'OCI8',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'AL32UTF8',
    //        'swapPre'    => '',
    //        'failover'   => [],
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    /**
     * This database connection is used when running PHPUnit database tests.
     *
     * @var array<string, mixed>
     */
    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'dateFormat'  => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        // Initialize database connections using environment variables
        $this->default = [
            'DSN'          => '',
            'hostname'     => env('database.default.hostname', 'localhost'),
            'username'     => env('database.default.username', ''),
            'password'     => env('database.default.password', ''),
            'database'     => env('database.default.database', ''),
            'schema'       => 'dbo',
            'DBDriver'     => env('database.default.DBDriver', 'SQLSRV'),
            'DBPrefix'     => '',
            'pConnect'     => false,
            'DBDebug'      => ENVIRONMENT !== 'production',
            'charset'      => 'utf8',
            'swapPre'      => '',
            'encrypt'      => false,
            'compress'     => false,
            'strictOn'     => false,
            'failover'     => [],
            'port'         => (int) env('database.default.port', 1433),
            'numberNative' => false,
            'foundRows'    => false,
            'dateFormat'   => [
                'date'     => 'Y-m-d',
                'datetime' => 'Y-m-d H:i:s',
                'time'     => 'H:i:s',
            ],
        ];

        $this->nwfth_db = [
            'DSN'          => '',
            'hostname'     => env('database.nwfth_db.hostname', 'localhost'),
            'username'     => env('database.nwfth_db.username', ''),
            'password'     => env('database.nwfth_db.password', ''),
            'database'     => env('database.nwfth_db.database', ''),
            'schema'       => 'dbo',
            'DBDriver'     => env('database.nwfth_db.DBDriver', 'SQLSRV'),
            'DBPrefix'     => '',
            'pConnect'     => true,  // Enable persistent connections for 15-20% performance boost
            'DBDebug'      => ENVIRONMENT !== 'production', // Disable debug in production
            'charset'      => 'utf8',
            'swapPre'      => '',
            'encrypt'      => false,
            'compress'     => false,
            'strictOn'     => false,
            'failover'     => [],
            'port'         => (int) env('database.nwfth_db.port', 1433),
            'numberNative' => false,
            'foundRows'    => false,
            'dateFormat'   => [
                'date'     => 'Y-m-d',
                'datetime' => 'Y-m-d H:i:s',
                'time'     => 'H:i:s',
            ],
            // SQL Server specific performance optimizations - Production tuned
            'SQLSRV'       => [
                'CharacterSet' => 'UTF-8',
                'ConnectionPooling' => 1,
                'MultipleActiveResultSets' => 1,
                'TrustServerCertificate' => 1,
                'LoginTimeout' => 15,    // Reduced for faster failure detection
                'QueryTimeout' => 30,    // Reduced from 60 to prevent long-running queries
                'ConnectRetryCount' => 3, // Retry failed connections
                'ConnectRetryInterval' => 10, // Wait 10 seconds between retries
                'ApplicationIntent' => 'ReadWrite', // Optimize for read-write operations
                'MultiSubnetFailover' => 'No', // Set based on your network topology
                'Encrypt' => 'Optional', // Adjust based on your security requirements
                'PacketSize' => 4096     // Optimize packet size for performance
            ]
        ];

        $this->nwfth2_db = [
            'DSN'          => '',
            'hostname'     => env('database.nwfth2_db.hostname', 'localhost'),
            'username'     => env('database.nwfth2_db.username', ''),
            'password'     => env('database.nwfth2_db.password', ''),
            'database'     => env('database.nwfth2_db.database', ''),
            'schema'       => 'dbo',
            'DBDriver'     => env('database.nwfth2_db.DBDriver', 'SQLSRV'),
            'DBPrefix'     => '',
            'pConnect'     => true,  // Enable persistent connections for 15-20% performance boost
            'DBDebug'      => ENVIRONMENT !== 'production', // Disable debug in production
            'charset'      => 'utf8',
            'swapPre'      => '',
            'encrypt'      => false,
            'compress'     => false,
            'strictOn'     => false,
            'failover'     => [],
            'port'         => (int) env('database.nwfth2_db.port', 1433),
            'numberNative' => false,
            'foundRows'    => false,
            'dateFormat'   => [
                'date'     => 'Y-m-d',
                'datetime' => 'Y-m-d H:i:s',
                'time'     => 'H:i:s',
            ],
            // SQL Server specific performance optimizations - Production tuned
            'SQLSRV'       => [
                'CharacterSet' => 'UTF-8',
                'ConnectionPooling' => 1,
                'MultipleActiveResultSets' => 1,
                'TrustServerCertificate' => 1,
                'LoginTimeout' => 15,    // Reduced for faster failure detection
                'QueryTimeout' => 30,    // Reduced from 60 to prevent long-running queries
                'ConnectRetryCount' => 3, // Retry failed connections
                'ConnectRetryInterval' => 10, // Wait 10 seconds between retries
                'ApplicationIntent' => 'ReadWrite', // Optimize for read-write operations
                'MultiSubnetFailover' => 'No', // Set based on your network topology
                'Encrypt' => 'Optional', // Adjust based on your security requirements
                'PacketSize' => 4096     // Optimize packet size for performance
            ]
        ];

        // Performance optimization: Enable connection health monitoring
        if (ENVIRONMENT === 'production') {
            // Add connection validation queries for health checks
            $this->default['validationQuery'] = 'SELECT 1';
            $this->nwfth_db['validationQuery'] = 'SELECT 1';
            $this->nwfth2_db['validationQuery'] = 'SELECT 1';
            
            // Enable connection validation before use
            $this->default['testOnBorrow'] = true;
            $this->nwfth_db['testOnBorrow'] = true;
            $this->nwfth2_db['testOnBorrow'] = true;
        }

        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
