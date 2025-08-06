<!DOCTYPE html>
<html lang="en">
<head>
	<title>NWFTH CENTRALIZED PORTAL</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/login_template/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/login_template/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/login_template/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/login_template/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/login_template/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/login_template/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/login_template/css/main.css">
	
	<style>
		.bg-img{
			background-repeat:no-repeat;
			background-size: cover; 
			background-image:url("<?php echo base_url(); ?>assets/img/bliki-krugi-fon-razmytie.jpg");
			filter: blur(1px);
 			-webkit-filter: blur(1px);
			background-position: center;
			height: 100%; 
		}
		
		body, html {
		  height: 100%;
		  height: 100%;
		  margin: 0;
		  font-family: Arial, Helvetica, sans-serif;
		}
		
	</style>
<!--===============================================================================================-->
</head>
<body style=''>
	<div class='bg-img'>
	</div>
		<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="<?php echo base_url();?>assets/img/Logo.jpg" alt="IMG">
				</div>

				<form class="login100-form validate-form" action="<?php echo base_url();?>Auth/validate" method="POST">
					
					<span class="login100-form-title">
						NWFTH BARCODE SYSTEM
					</span>

					<span>
						<?php echo validation_errors(); ?>
						<?php echo @$msg; ?>
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Please enter your Employee ID">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="pass" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>

					
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="<?php echo base_url();?>assets/login_template/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/login_template/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url();?>assets/login_template/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/login_template/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/login_template/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/login_template/js/main.js"></script>

</body>
</html>
