<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class Box extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=49;
				parent::__construct();
				
				$this->table="box_tbl";
				$this->pkey="box_id";
				$this->ref_filter = [
					"room_id" => [
						"tbl"=>"rack_tbl",
						"id"=>"rack_id",
						"val"=>"rack_name"
					]
				];
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> Box");
				 $this->template->set("titlepage", "Box");
				 $this->template->newload("pages/Box", array() , $data);
				 
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