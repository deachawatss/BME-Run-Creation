<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Changepassword extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->page_load();
    }

    public function index(){

		 #header("Access-Control-Allow-Origin: *");
		 
		$current_password=$this->input->post('current_password');
		$new_password=$this->input->post('new_password');
		$confirm_password=$this->input->post('confirm_password');

		$msg=array();

		if($current_password!="" && $new_password!="" && $confirm_password!=""){

			if(@$this->userinfo['pword']!=$current_password){
				$msg=array(
					"msg"=>"Invalid Password",
					"msgno"=>2
				);

			}

			if($new_password!=$confirm_password){
				$msg=array(
					"msg"=>"Password Mismatch",
					"msgno"=>2
				);
			}else{

				$toupdate=array(
					"pword"=>$new_password
				);

				$this->db->where("userid",@$this->userinfo['userid'])->update("tbl_user",$toupdate);
				@$this->userinfo['pword']=$new_password;
				$msg=array(
					"msg"=>"Successfully Updated",
					"msgno"=>1
				);
			}

			

		}

		 $data =$msg;
		 
         $this->template->set('title', '<i class="fas fa-users"></i> Change Password');
         $this->template->set('titlepage', 'Change Password');
         $this->template->load('default_layout', 'contents' , 'changepassword', $data);
	}
	
	

}
?>
