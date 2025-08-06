<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserAccount extends MY_Controller {

    function __construct()
    {
        $this->pageid=3;
        parent::__construct();
		
        #$this->load->model('employee_model');
        $this->table="tbl_user";
		$this->pkey="userid";
        $this->page_load();
        $this->list_defaults['created_at']=true;
    }

    public function index(){

         #header("Access-Control-Allow-Origin: *");
         $data = array();
         $this->template->set('title', '<i class="fas fa-users"></i> User Management');
         $this->template->set('titlepage', 'User Management');
        # $this->template->load('default_layout', 'contents' , 'admin/Usermanagement', $data);
         $this->template->newload('pages/blank', array() , $data);
    }

}
?>
