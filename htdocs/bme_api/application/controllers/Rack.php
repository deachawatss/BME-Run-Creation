<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class Rack extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=48;
				parent::__construct();
				
				$this->table="rack_tbl";
				$this->pkey="rack_id";
			
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> Rack");
				 $this->template->set("titlepage", "Rack");
				 $this->template->newload("pages/Rack", array() , $data);
				 
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