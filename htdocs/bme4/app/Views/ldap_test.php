<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LDAP Test' ?></title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <style>
        .result-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-top: 1rem;
        }
        .success { border-color: #198754; background-color: #d1e7dd; }
        .error { border-color: #dc3545; background-color: #f8d7da; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0"><?= $page_title ?? 'LDAP Connection Test' ?></h3>
                    </div>
                    <div class="card-body">
                        
                        <!-- PHP LDAP Extension Check -->
                        <div class="mb-4">
                            <h5>PHP LDAP Extension Status</h5>
                            <button type="button" class="btn btn-info" onclick="checkPhpInfo()">Check PHP LDAP Extension</button>
                            <div id="phpInfoResult" class="result-box" style="display: none;"></div>
                        </div>

                        <!-- Connection Test -->
                        <div class="mb-4">
                            <h5>LDAP Connection Test</h5>
                            <p class="text-muted">Test connection to Active Directory server (192.168.0.1)</p>
                            <button type="button" class="btn btn-primary" onclick="testConnection()">Test Connection</button>
                            <div id="connectionResult" class="result-box" style="display: none;"></div>
                        </div>

                        <!-- Authentication Test -->
                        <div class="mb-4">
                            <h5>LDAP Authentication Test</h5>
                            <form id="authForm">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username:</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           placeholder="Enter AD username or email" required>
                                    <small class="form-text text-muted">Example: deachawat or deachawat@newlywedsfoods.co.th</small>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-success">Test Authentication</button>
                            </form>
                            <div id="authResult" class="result-box" style="display: none;"></div>
                        </div>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="<?= base_url('auth/login') ?>" class="btn btn-secondary">Back to Login</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        function checkPhpInfo() {
            fetch('<?= base_url('LdapTest/phpInfo') ?>')
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('phpInfoResult');
                    resultDiv.style.display = 'block';
                    
                    let html = '<h6>PHP LDAP Extension Status:</h6>';
                    html += '<ul>';
                    html += `<li><strong>PHP Version:</strong> ${data.php_version}</li>`;
                    html += `<li><strong>LDAP Extension Loaded:</strong> ${data.ldap_extension_loaded ? '✅ Yes' : '❌ No'}</li>`;
                    
                    if (data.ldap_extension_loaded && data.ldap_functions) {
                        html += '<li><strong>LDAP Functions Available:</strong><ul>';
                        for (const [func, available] of Object.entries(data.ldap_functions)) {
                            html += `<li>${func}: ${available ? '✅' : '❌'}</li>`;
                        }
                        html += '</ul></li>';
                    }
                    html += '</ul>';
                    
                    resultDiv.innerHTML = html;
                    resultDiv.className = data.ldap_extension_loaded ? 'result-box success' : 'result-box error';
                })
                .catch(error => {
                    const resultDiv = document.getElementById('phpInfoResult');
                    resultDiv.style.display = 'block';
                    resultDiv.innerHTML = `<strong>Error:</strong> ${error.message}`;
                    resultDiv.className = 'result-box error';
                });
        }

        function testConnection() {
            const button = event.target;
            button.disabled = true;
            button.textContent = 'Testing...';
            
            fetch('<?= base_url('LdapTest/testConnection') ?>')
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('connectionResult');
                    resultDiv.style.display = 'block';
                    resultDiv.innerHTML = `<strong>${data.success ? 'Success' : 'Failed'}:</strong> ${data.message}`;
                    resultDiv.className = data.success ? 'result-box success' : 'result-box error';
                })
                .catch(error => {
                    const resultDiv = document.getElementById('connectionResult');
                    resultDiv.style.display = 'block';
                    resultDiv.innerHTML = `<strong>Error:</strong> ${error.message}`;
                    resultDiv.className = 'result-box error';
                })
                .finally(() => {
                    button.disabled = false;
                    button.textContent = 'Test Connection';
                });
        }

        document.getElementById('authForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            
            submitButton.disabled = true;
            submitButton.textContent = 'Authenticating...';
            
            fetch('<?= base_url('LdapTest/testAuth') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('authResult');
                resultDiv.style.display = 'block';
                
                let html = `<strong>${data.success ? 'Success' : 'Failed'}:</strong> ${data.message}`;
                
                if (data.success && data.user_data) {
                    html += '<br><br><strong>User Information:</strong><ul>';
                    for (const [key, value] of Object.entries(data.user_data)) {
                        if (value && key !== 'dn') {
                            html += `<li><strong>${key}:</strong> ${value}</li>`;
                        }
                    }
                    html += '</ul>';
                }
                
                resultDiv.innerHTML = html;
                resultDiv.className = data.success ? 'result-box success' : 'result-box error';
            })
            .catch(error => {
                const resultDiv = document.getElementById('authResult');
                resultDiv.style.display = 'block';
                resultDiv.innerHTML = `<strong>Error:</strong> ${error.message}`;
                resultDiv.className = 'result-box error';
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Test Authentication';
            });
        });
    </script>
</body>
</html>