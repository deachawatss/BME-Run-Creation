	<!-- Main Sidebar Container -->
	<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
			<!-- Brand Logo -->
			<a href="<?php echo base_url(); ?>" class="brand-link">
			<img src="<?php echo base_url('assets/img/Logo.jpg');?>" alt="Newly Weds Foods Philippines" class="brand-image elevation-3" style="margin-left:unset">
			<span class="brand-text font-weight-light">NWFTH</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
			<!-- Sidebar user panel (optional) -->
			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
				<div class="image">
				<!--<img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">-->
				<i class="fa fa-user-circle" style='font-size: 38px;color: #fff;'></i>
				</div>
				<div class="info">
				<a href="#" class="d-block"><?php echo ucfirst($this->userinfo['Lname']).", ".ucfirst($this->userinfo['Fname']); ?></a>
				</div>
			</div>

			<!-- Sidebar Menu -->
			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

					<?php
					
						$CI= &get_instance();
						$mymenu = $CI->load_menu();
						echo sidemenu($mymenu);
					?>
					
					<li class="nav-item">
						<a class="nav-link" href="<?php echo base_url('auth/logout'); ?>">
								<i class="fas fa-sign-out-alt"></i>&nbsp;<p>Logout </p>
						</a>
					</li>
				</ul>
			</nav>
			<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>
		
		
