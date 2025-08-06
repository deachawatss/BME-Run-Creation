<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    function __construct()
    {
		$this->pageid=1;
        parent::__construct();
        # $this->load->model("auth_model");
        #print_r(@$this->session->userdata);
        if ( ! $this->session->userdata('logged_in'))
            { 
                // Allow some methods?
                $allowed = array(
                    'some_method_in_this_controller',
                    'other_method_in_this_controller',
                );
                if ( ! in_array($this->router->fetch_method(), $allowed))
                {
                   # redirect('Home',"refresh");
                }
            }
        else{
            redirect('Auth',"refresh");
        }

        
		$this->page_load();
    }

 public function index()
    {
		$data=array();
        
		$this->template->set('title', '<i class="fas fa-chart-line"></i> Home');
        $this->template->set('titlepage', 'Home');
       # $this->template->load('default_layout', 'contents' , 'home', $data);
        
        $this->template->newload('pages/blank', array() , $data);
    }


}
