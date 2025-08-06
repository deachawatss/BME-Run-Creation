<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class Room extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=47;
				parent::__construct();
				
				$this->table="room_tbl";
				$this->pkey="room_id";
			
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> Room");
				 $this->template->set("titlepage", "Room");
				 $this->template->newload("pages/Room", array() , $data);
				 
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