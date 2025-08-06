<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Require authentication
        $authRedirect = $this->requireAuth();
        if ($authRedirect) {
            session()->set('intended_url', current_url());
            return $authRedirect;
        }
        
        $this->logOperation('home_index', ['user' => $this->getCurrentUser()]);

        $data = $this->getBaseViewData(
            'NWFTH - Run Creation System',
            'NWFTH - Run Creation System'
        );
        
        // Add home-specific data
        $data['app_name'] = 'NWFTH';
        $data['welcome_message'] = 'Welcome to the Run Creation System';
        $data['system_cards'] = [
            [
                'title' => 'Create Bulk Run',
                'description' => 'Create and manage bulk production runs with multiple batches and pallets',
                'url' => base_url('createrunbulk'),
                'icon' => 'fas fa-boxes',
                'color' => 'brown',
                'features' => [
                    'Bulk batch creation',
                    'Pallet management',
                    'Status tracking',
                    'Formula integration'
                ]
            ],
            [
                'title' => 'Create Partial Run',
                'description' => 'Create partial production runs for smaller batches and specific requirements',
                'url' => base_url('createrunpartial'),
                'icon' => 'fas fa-box',
                'color' => 'brown-light',
                'features' => [
                    'Partial batch creation',
                    'Flexible quantities',
                    'Quick setup',
                    'Status monitoring'
                ]
            ]
        ];

        return view('home', $data);
    }
}