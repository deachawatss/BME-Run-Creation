<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class XPSetup extends MY_Controller {
		
		function __construct()
			{
				$this->has_enter_by = false;
				$this->has_created_at = false;
				$this->has_updated_by = false;
				$this->has_updated_date = false;
				$this->pageid=53;
				parent::__construct();
				
				$this->table="inmast";
				$this->pkey="Itemkey";
				$this->ndb = $this->load->database("nwfth2",true);
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> XP Setup");
				 $this->template->set("titlepage", "XP Setup");
				 $this->template->newload("pages/XPSetup", array() , $data);
				 
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

	}