<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class Prebatch extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=47;
				parent::__construct();
				
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 $this->includeJS[]='assets/js/page/prebatch.js';
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> Pre-Batch");
				 $this->template->set("titlepage", "Pre-Batch");
				 $this->template->newload("pages/Prebatch", array() , $data);
				 
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