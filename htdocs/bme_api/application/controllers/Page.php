<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends MY_Controller {

    function __construct()
    {
		$this->pageid=5;
        parent::__construct();
        #$this->load->model('employee_model');
        $this->table="tbl_page";
		$this->pkey="pageid";
        $this->page_load();
        $this->list_defaults['created_at']=true;
		
    }

    public function index(){

         #header("Access-Control-Allow-Origin: *");
         $data = array();
		 $this->includeJS[]='assets/js/page/admin/page.js';
         $this->template->set('title', '<i class="fas fa-users"></i> Page Management');
         $this->template->set('titlepage', 'Page Management');
        # $this->template->load('default_layout', 'contents' , 'admin/Usermanagement', $data);
         $this->template->newload('pages/blank', array() , $data);
    }

	public function genpage(){
		$this->load->helper('file');
		$pageid = $this->input->post('pageid');
		$mdata=$this->ndb->where("pageid",$pageid)->get("tbl_page")->row();

		$mycontroller=$mdata->pagename;
		$mytbl=$this->input->post("tbl_name");
		$mytblid=$this->input->post("tbl_id");
		$tbl_js=$this->input->post("tbl_js");
		
		$ClassName=explode("/",$mycontroller);


$data='<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class '.ucfirst($ClassName[count($ClassName) - 1]).' extends MY_Controller {
		
		function __construct()
			{
				$this->pageid='.$pageid.';
				parent::__construct();
				';

			if($mytbl!=""){
			$data.='
				$this->table="'.$mytbl.'";
				$this->pkey="'.$mytblid.'";
			';
			}
				
		$data.='
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 ';
		if($tbl_js==1){
				$data.='
					$this->includeJS[]="assets/js/page/'.$mycontroller.'.js";
				';
		}		 
		$data.='
				 $this->template->set("title", "<i class=\"fas fa-'.$mdata->icon.'\"></i> '.$mdata->pagetitle.'");
				 $this->template->set("titlepage", "'.$mdata->pagetitle.'");
				 $this->template->newload("pages/'.$mycontroller.'", array() , $data);
				 
			}
		
			protected function before_insert($param=[]){
				
				return $param;
			}

			protected function after_insert($param=[],$newkey=""){
				

				//Add die to customize return
				return $param;
			}

			protected function before_delete($param=[],$id=0){

				return $param;
			}

			protected function after_delete($param=[],$id=0){
				
				return $param;
			}

			protected function before_update($param=[],$id=0){

				return $param;
			}

			protected function after_update($param=[],$id=0){
				
				return $param;
			}

	}';

		if(!file_exists(APPPATH."controllers/".$mycontroller.".php")){
			write_file(APPPATH."controllers/".$mycontroller.".php", $data);
		}

		$viewdata='
		<?php

			echo genTbl($this->table);
		
		?>
		';
		if(!file_exists(APPPATH."views/pages/".$mycontroller.".php")){
			write_file(APPPATH."views/pages/".$mycontroller.".php", $viewdata);
		}

		if(!file_exists(FCPATH."assets/js/page/".$mycontroller.".js")){
			write_file(APPPATH."assets/js/page/".$mycontroller.".js", "");
		}
		

		echo json_encode([ ]);

	}

}
?>
