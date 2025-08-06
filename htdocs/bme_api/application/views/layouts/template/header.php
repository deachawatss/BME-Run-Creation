<?php
	$title=config_item("title");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title;?> - <?php echo @$titlepage;?></title>
	<style>
		.box{
			position: relative;
			border-radius: 3px;
			background: #ffffff;
			border-top: 3px solid #d2d6de;
			padding: 20px;
			width: 100%;
			box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
		}
		.row{
			padding:10px;
		}
	</style>
<?php

	$mycss=array(
		'assets/plugins/fontawesome-free/css/all.min.css',
		'assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
		'assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
		'assets/plugins/jqvmap/jqvmap.min.css',
		'assets/css/adminlte.min.css',
		'assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
		'assets/plugins/daterangepicker/daterangepicker.css',
		'assets/plugins/summernote/summernote-bs4.min.css',
		'assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
        'assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css',
        'assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css',
        'assets/plugins/select2/css/select2.min.css',
        'assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
        'assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
        'assets/plugins/sweetalert2/sweetalert2.min.css',
        'assets/css/template.css',
	);

foreach($mycss as $k=>$v){
		loadcss($v);
	}

foreach($this->includeCSS as $k=>$v){
		loadcss($v);
}
?>
</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
	<div class="wrapper">
		<!-- Preloader -->
		<div class="preloader flex-column justify-content-center align-items-center">
			<!--<img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">-->
		</div>
