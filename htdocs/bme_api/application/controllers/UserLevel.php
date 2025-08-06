<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserLevel extends MY_Controller {

    function __construct()
    {
		$this->pageid=4;
        parent::__construct();
        #$this->load->model('employee_model');
        #$this->datatables->table="tbl_userlvl";
		#$this->datatables->pkey="userlvlid";
		

        $this->table="tbl_userlvl";
		$this->pkey="userlvlid";
		$this->page_load();
        $this->list_defaults['created_at']=true;
    }

    public function index(){

         #header("Access-Control-Allow-Origin: *");
         $data = array();
         $this->template->set('title', '<i class="fas fa-users"></i> User Level');
         $this->template->set('titlepage', 'User Level');
        # $this->template->newload('default_layout', 'contents' , 'admin/Usermanagement', $data);
         $this->template->newload('pages/blank', array() , $data);
    }

}
?>
