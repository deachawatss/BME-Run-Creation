<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'NWFTH - Run Creation System' ?></title>
    
    <style>
        .box{
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            padding: clamp(12px, 3vw, 20px);
            width: 100%;
            box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
        }
        
        /* Brown theme for AdminLTE sidebar */
        .main-sidebar {
            background-color: var(--color-brown-800) !important;
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: var(--color-brown-600) !important;
            color: white !important;
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
            background-color: var(--color-brown-700) !important;
            color: white !important;
        }
        
        /* Fix footer positioning */
        .main-footer {
            background-color: var(--color-brown-100) !important;
            border-top: 1px solid var(--color-brown-200) !important;
            color: var(--color-brown-800) !important;
        }
        
        /* Navbar brown theme */
        .navbar-white {
            background-color: var(--color-brown-50) !important;
            border-bottom: 1px solid var(--color-brown-200) !important;
        }
        
        /* Logout Button Styling */
        .btn-logout-header:hover {
            background: var(--color-brown-100) !important;
            border-color: var(--color-brown-300) !important;
            color: var(--color-brown-800) !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(101, 67, 33, 0.2);
        }
        
        .btn-logout-header:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(101, 67, 33, 0.2);
        }
        
        /* User welcome text */
        .nav-link.text-muted {
            color: var(--color-brown-600) !important;
            font-weight: 500;
        }
        
        /* Mobile dropdown styling */
        @media (max-width: 576px) {
            .navbar-nav .nav-link {
                padding: 0.5rem 0.75rem;
            }
        }
    </style>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/jqvmap/jqvmap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/daterangepicker/daterangepicker.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/summernote/summernote-bs4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-select/css/select.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/css-variables.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/minimal-ui.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/datatables-fixes.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/css-cleanup.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/enhanced-table-styling.css') ?>">

    <!-- Load critical JavaScript libraries early to prevent undefined errors -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/lodash/lodash.min.js') ?>"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <!--<img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">-->
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= base_url() ?>" class="nav-link">Home</a>
                </li>
            </ul>
            
            <!-- Center navbar brand for larger screens -->
            <div class="navbar-nav mx-auto d-none d-lg-flex align-items-center">
                <img src="<?= base_url('assets/img/nwfth-logo.png') ?>" 
                     alt="NWFTH Logo" 
                     style="height: 40px; width: auto; object-fit: contain; margin-right: 12px;">
                <h4 class="mb-0" style="color: #000000; font-weight: 600;">NWFTH - Run Creation System</h4>
            </div>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- User Info -->
                <li class="nav-item d-none d-sm-inline-block">
                    <span class="nav-link text-muted">
                        <i class="fas fa-user mr-1"></i>
                        Welcome, <?= session()->get('username') ?: session()->get('full_name') ?: 'User' ?>
                    </span>
                </li>
                
                <!-- Prominent Logout Button -->
                <li class="nav-item">
                    <a href="<?= base_url('auth/logout') ?>" 
                       class="nav-link btn-logout-header" 
                       onclick="return confirm('Are you sure you want to logout?')"
                       style="color: var(--color-brown-700); font-weight: 600; padding: 8px 16px; margin: 4px 8px; border-radius: var(--radius-md); background: var(--color-brown-50); border: 1px solid var(--color-brown-200); transition: all 0.2s ease;">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </li>
                
                <!-- User Dropdown (Secondary) -->
                <li class="nav-item dropdown d-sm-none">
                    <a class="nav-link" data-toggle="dropdown" href="#" aria-label="User Menu">
                        <i class="fas fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-item-text">
                            <strong><?= session()->get('username') ?: 'User' ?></strong>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="<?= base_url('auth/logout') ?>" 
                           class="dropdown-item"
                           onclick="return confirm('Are you sure you want to logout?')">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= base_url() ?>" class="brand-link" style="background-color: var(--color-brown-600); border-bottom: 1px solid var(--color-brown-500);">
                <img src="<?= base_url('assets/img/nwfth-logo.png') ?>" 
                     alt="NWFTH Logo" 
                     class="brand-image elevation-3" 
                     style="opacity: .9; width: 33px; height: 33px; object-fit: contain; margin-left: 8px; background: white; padding: 4px; border-radius: 50%;">
                <span class="brand-text font-weight-light" style="color: white;">NWFTH System</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="<?= base_url() ?>" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('CreateRunBulk') ?>" class="nav-link">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>Create Bulk Run</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('CreateRunPartial') ?>" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>Create Partial Run</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= $page_title ?? 'NWFTH Run Creation System' ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <?php if (isset($breadcrumb) && is_array($breadcrumb)): ?>
                                    <?php foreach ($breadcrumb as $crumb): ?>
                                        <?php if (!empty($crumb['url'])): ?>
                                            <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= $crumb['title'] ?></a></li>
                                        <?php else: ?>
                                            <li class="breadcrumb-item active"><?= $crumb['title'] ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">