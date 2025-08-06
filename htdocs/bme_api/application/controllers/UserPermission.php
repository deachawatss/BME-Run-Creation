<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserPermission extends MY_Controller {

    function __construct()
    {
		$this->pageid=0;
        parent::__construct();
        #$this->load->model('employee_model');
       # $this->datatables->table="tbl_permission";
		#$this->datatables->pkey="userpermission_id";
		
		$this->page_load();
    }

    public function index(){

         #header("Access-Control-Allow-Origin: *");
         $data = array();

		 if( @$this->input->post('submit') === "submit" ){
			$this->saveall(); 
		 }

         $id=$this->input->get('id');
         $marry=$this->models->getRef("userlvlid","userlevel","tbl_userlvl","userlvlid=$id");
         

         $currentAccess=$this->models->getRef_data("tbl_permission","userlvlid=$id");
         $myaccess=array();
         
         foreach($currentAccess as $k=>$v){
            $myaccess[$v->pageid]=$v;
         }

         $data["myaccess"]=$myaccess;

         
         $x=$this->page_list();
         $data["menu_array"]=$x;
		 

		 $this->template->set('title', '<i class="fas fa-users"></i> User Level');
         $this->template->set('titlepage', 'User Level');
        # $this->template->newload('default_layout', 'contents' , 'admin/Usermanagement', $data);
         $this->template->newload('admin/UserPermission', array() , $data);

    }

    public function saveall(){
        #echo "<pre>";
        #print_r($this->input->post());
        #redirect("UserPermission?id=".@$this->db->get('id'),"refresh");
        $mydata=array();
        $mylist=$this->input->post('list');
        $myadd=$this->input->post('add');
        $myedit=$this->input->post('edit');
        $mydelete=$this->input->post('delete');
        $permission=$this->input->post('permission');

        if( is_array(@$mylist) ){

            foreach($mylist as $k=>$v){
                @$mydata[$v]['list']='1';
            }

        }

        if( is_array(@$myadd) ){

            foreach($myadd as $k=>$v){
                @$mydata[$v]['add']='1';
            }

        }

        if( is_array(@$myedit) ){

            foreach($myedit as $k=>$v){
                @$mydata[$v]['edit']='1';
            }

        }

        if( is_array(@$mydelete) ){

            foreach($mydelete as $k=>$v){
                @$mydata[$v]['delete']='1';
            }

        }

        $this->models->resetLevel();

        foreach($mydata as $k=>$v){
            $this->models->updateLevel($k,$v);
        }

        
        
        @$_SESSION['msg']="Successfully Updated";
        #redirect('UserPermission?id='.@$this->db->get('id'),"refresh");
        #header("Location:".base_url()."UserPermission?id=".@$this->db->get());
        #$this->index();
    }
    
    
}
?>
