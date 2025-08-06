<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\AuthModel;

class Auth extends BaseController
{
    protected $authModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Initialize model
        $this->authModel = new AuthModel();
    }

    public function index()
    {
        return redirect()->to(base_url('auth/login'));
    }

    public function login()
    {
        // If user is already logged in, redirect to home
        if (session()->get('logged_in')) {
            return redirect()->to(base_url());
        }

        $data = [
            'title' => 'Login - NWFTH Run Creation System',
            'page_title' => 'NWFTH - Run Creation System',
            'app_name' => 'NWFTH',
            'error_message' => session()->getFlashdata('error_message'),
            'success_message' => session()->getFlashdata('success_message')
        ];

        return view('login', $data);
    }

    public function authenticate()
    {
        // Validate request method
        if (strtolower($this->request->getMethod()) !== 'post') {
            log_message('debug', 'Non-POST request, redirecting to login');
            return redirect()->to(base_url('auth/login'));
        }

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[50]',
            'password' => 'required|min_length[3]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            log_message('debug', 'Validation failed: ' . json_encode($validation->getErrors()));
            session()->setFlashdata('error_message', 'Please enter valid username and password');
            return redirect()->to(base_url('auth/login'));
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        log_message('debug', "Attempting authentication for user: {$username}");

        try {
            // Attempt authentication
            $authResult = $this->authModel->authenticateUser($username, $password);
            
            // Debug logging
            log_message('debug', 'Authentication result: ' . json_encode($authResult));
            
            if ($authResult['success']) {
                log_message('debug', 'Authentication successful, setting session');
                
                // Set session data
                $sessionData = [
                    'user_id' => $authResult['user_id'],
                    'username' => $username,
                    'full_name' => $authResult['full_name'] ?? $username,
                    'email' => $authResult['email'] ?? '',
                    'department' => $authResult['department'] ?? '',
                    'logged_in' => true,
                    'login_time' => date('Y-m-d H:i:s'),
                    'last_activity' => time()
                ];
                
                session()->set($sessionData);
                
                // Verify session was set
                log_message('debug', 'Session set. Logged_in status: ' . (session()->get('logged_in') ? 'true' : 'false'));
                
                // Log successful login
                log_message('info', "User {$username} logged in successfully from IP: " . $this->request->getIPAddress());
                
                // Redirect to intended page or home
                $redirectUrl = session()->get('intended_url') ?? base_url();
                session()->remove('intended_url');
                
                log_message('debug', "Redirecting to: {$redirectUrl}");
                return redirect()->to($redirectUrl);
                
            } else {
                log_message('debug', 'Authentication failed: ' . ($authResult['message'] ?? 'Unknown error'));
                
                // Authentication failed
                session()->setFlashdata('error_message', $authResult['message'] ?? 'Invalid username or password');
                
                // Log failed login attempt
                log_message('warning', "Failed login attempt for user {$username} from IP: " . $this->request->getIPAddress());
                
                return redirect()->to(base_url('auth/login'));
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Authentication error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            session()->setFlashdata('error_message', 'System error occurred. Please try again later.');
            return redirect()->to(base_url('auth/login'));
        }
    }

    public function logout()
    {
        $username = session()->get('username') ?? 'unknown';
        
        // Log logout
        log_message('info', "User {$username} logged out from IP: " . $this->request->getIPAddress());
        
        // Destroy session
        session()->destroy();
        
        // Clear any cached data
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
        
        session()->setFlashdata('success_message', 'You have been logged out successfully');
        return redirect()->to(base_url('auth/login'));
    }

    public function checkSession()
    {
        // AJAX endpoint to check session status
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request method']);
        }

        $isLoggedIn = session()->get('logged_in') ?? false;
        $lastActivity = session()->get('last_activity') ?? 0;
        $sessionTimeout = 28800; // 8 hours in seconds
        
        if ($isLoggedIn && (time() - $lastActivity) < $sessionTimeout) {
            // Update last activity
            session()->set('last_activity', time());
            
            return $this->response->setJSON([
                'logged_in' => true,
                'username' => session()->get('username'),
                'time_remaining' => $sessionTimeout - (time() - $lastActivity)
            ]);
        } else {
            // Session expired
            return $this->response->setJSON([
                'logged_in' => false,
                'message' => 'Session expired'
            ]);
        }
    }

    public function extendSession()
    {
        // AJAX endpoint to extend session
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request method']);
        }

        if (session()->get('logged_in')) {
            session()->set('last_activity', time());
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Session extended successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No active session found'
            ]);
        }
    }

    public function changePassword()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth/login'));
        }

        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'current_password' => 'required|min_length[3]',
                'new_password' => 'required|min_length[8]|differs[current_password]',
                'confirm_password' => 'required|matches[new_password]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }

            $username = session()->get('username');
            $currentPassword = $this->request->getPost('current_password');
            $newPassword = $this->request->getPost('new_password');

            try {
                $result = $this->authModel->changePassword($username, $currentPassword, $newPassword);
                
                if ($result['success']) {
                    log_message('info', "User {$username} changed password successfully");
                    
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Password changed successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $result['message']
                    ]);
                }
                
            } catch (\Exception $e) {
                log_message('error', 'Change password error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'System error occurred'
                ]);
            }
        }

        $data = [
            'title' => 'Change Password',
            'page_title' => 'Change Password'
        ];

        return view('change_password', $data);
    }

    public function profile()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth/login'));
        }

        try {
            $username = session()->get('username');
            $userProfile = $this->authModel->getUserProfile($username);
            
            $data = [
                'title' => 'User Profile',
                'page_title' => 'User Profile',
                'user_profile' => $userProfile
            ];

            return view('user_profile', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Profile error: ' . $e->getMessage());
            session()->setFlashdata('error_message', 'Unable to load profile');
            return redirect()->to(base_url());
        }
    }

    public function validateToken()
    {
        // AJAX endpoint for CSRF token validation
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request method']);
        }

        $token = $this->request->getPost('csrf_token');
        $isValid = csrf_hash() === $token;
        
        return $this->response->setJSON([
            'valid' => $isValid,
            'new_token' => csrf_hash()
        ]);
    }
}