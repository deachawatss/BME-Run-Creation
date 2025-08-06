<!DOCTYPE html>
<html lang="en">
<head>
	<title><?= esc($title ?? 'NWFTH - Run Creation System Login') ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/css-variables.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/plugins/fontawesome-free/css/all.min.css">
	
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body, html {
			height: 100%;
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
		}

		.login-container {
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 100vh;
			padding: 20px;
		}

		.login-card {
			background: white;
			border-radius: 12px;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
			padding: 48px;
			width: 100%;
			max-width: 400px;
			text-align: center;
		}

		.logo {
			width: 200px;
			height: 200px;
			object-fit: contain;
			border-radius: 12px;
			margin-bottom: 4px;
		}

		.login-title {
			font-size: 26px;
			font-weight: 700;
			color: var(--color-slate-800);
			margin-bottom: 32px;
			letter-spacing: -0.02em;
			line-height: 1.3;
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', sans-serif;
			text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
		}

		.login-subtitle {
			color: var(--color-slate-600);
			margin-bottom: 32px;
			font-size: 14px;
		}

		.form-group {
			margin-bottom: 20px;
			text-align: left;
		}

		.form-label {
			display: block;
			font-size: 14px;
			font-weight: 700;
			color: var(--color-slate-800);
			margin-bottom: 6px;
		}

		.form-input {
			width: 100%;
			padding: 12px 16px;
			font-size: 14px;
			border: 2px solid var(--color-slate-200);
			border-radius: 8px;
			transition: all 0.2s ease;
			background: white;
			color: #000000;
		}

		.form-input:focus {
			outline: none;
			border-color: var(--color-brown-500);
			box-shadow: 0 0 0 3px rgba(162, 105, 74, 0.1);
		}

		.form-input::placeholder {
			color: var(--color-slate-400);
		}

		.login-button {
			width: 100%;
			padding: 14px;
			font-size: 14px;
			font-weight: 500;
			color: white;
			background: linear-gradient(135deg, var(--color-brown-600), var(--color-brown-700));
			border: none;
			border-radius: 8px;
			cursor: pointer;
			transition: all 0.2s ease;
			margin-top: 8px;
		}

		.login-button:hover {
			background: linear-gradient(135deg, var(--color-brown-700), var(--color-brown-800));
			transform: translateY(-1px);
			box-shadow: 0 4px 12px rgba(162, 105, 74, 0.3);
		}

		.login-button:disabled {
			opacity: 0.7;
			cursor: not-allowed;
			transform: none;
		}

		.error-message {
			background: rgba(239, 68, 68, 0.1);
			border: 1px solid rgba(239, 68, 68, 0.2);
			color: #dc2626;
			padding: 12px;
			border-radius: 6px;
			font-size: 13px;
			margin-bottom: 20px;
			text-align: left;
		}

		.footer-text {
			font-size: 12px;
			color: var(--color-slate-500);
			margin-top: 24px;
		}

		.footer-link {
			color: var(--color-brown-600);
			text-decoration: none;
		}

		.footer-link:hover {
			text-decoration: underline;
		}

		/* Responsive */
		@media (max-width: 480px) {
			.login-card {
				padding: 32px 24px;
			}
		}

		/* Loading state */
		.loading-spinner {
			display: none;
			margin-right: 8px;
		}

		.login-button.loading .loading-spinner {
			display: inline-block;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			from { transform: rotate(0deg); }
			to { transform: rotate(360deg); }
		}
	</style>
</head>

<body>
	<div class="login-container">
		<div class="login-card">
			<img src="https://img2.pic.in.th/pic/logo14821dedd19c2ad18.png" 
				 alt="NWFTH Logo" 
				 class="logo"
				 onerror="this.src='<?= base_url() ?>assets/img/Logo.jpg'">
			
			<h1 class="login-title">Newly Weds Food (TH)<br>Run Creation System</h1>
			
			<form action="<?= base_url('auth/authenticate') ?>" method="POST" id="loginForm">
				<?= csrf_field() ?>
				
				<?php if (isset($error_message) && $error_message): ?>
					<div class="error-message">
						<?= esc($error_message) ?>
					</div>
				<?php endif; ?>
				
				<?php if (isset($success_message) && $success_message): ?>
					<div class="error-message" style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.2); color: #16a34a;">
						<?= esc($success_message) ?>
					</div>
				<?php endif; ?>
				
				<div class="form-group">
					<label for="username" class="form-label">Username</label>
					<input type="text" 
						   id="username"
						   name="username" 
						   class="form-input" 
						   placeholder="Enter username"
						   required
						   autocomplete="username">
				</div>
				
				<div class="form-group">
					<label for="password" class="form-label">Password</label>
					<input type="password" 
						   id="password"
						   name="password" 
						   class="form-input" 
						   placeholder="Enter password"
						   required
						   autocomplete="current-password">
				</div>
				
				<button type="submit" class="login-button" id="loginButton">
					<i class="fas fa-spinner loading-spinner"></i>
					Sign In
				</button>
			</form>
			
			<p class="footer-text">
				© <?= date('Y') ?> Newly Weds Foods Thailand
			</p>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const loginForm = document.getElementById('loginForm');
			const loginButton = document.getElementById('loginButton');
			
			loginForm.addEventListener('submit', function() {
				loginButton.classList.add('loading');
				loginButton.disabled = true;
			});
			
			// Auto-focus first input
			document.getElementById('username').focus();
		});
	</script>
</body>
</html>