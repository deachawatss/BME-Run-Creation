<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class Type extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=46;
				parent::__construct();
				
				$this->table="file_type_tbl";
				$this->pkey="file_type_id";
			
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> Type");
				 $this->template->set("titlepage", "Type");
				 $this->template->newload("pages/Type", array() , $data);
				 
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