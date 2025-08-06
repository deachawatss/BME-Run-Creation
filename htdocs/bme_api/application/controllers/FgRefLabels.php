<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class FgRefLabels extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=54;
				parent::__construct();
				
				$this->table="tbl_ref_fg_labels";
				$this->pkey="tbl_ref_fg_labels_id";
			
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();

				 $this->includeJS[]="assets/js/page/FgRefLabels.js";

				 $this->template->set("title", "<i class=\"fas fa-file-contract\"></i> FG Labels");
				 $this->template->set("titlepage", "FG Labels");
				 $this->template->newload("pages/FgRefLabels", array() , $data);
				 
			}
		
			protected function before_insert($param=[]){

				$where = [
					"custkey"=>$param['custkey'],
					"itemkey"=>$param['itemkey'],
				];

				$mydata = $this->ndb->select('count(*) as mycount')->where($where)->get('tbl_ref_fg_labels')->row();

				if($mydata->mycount > 0){
					echo json_encode([
						"msgno"=> 200,
						"msg" => "Label already Exist"
					]);
					die();
				}
				
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

			public function prodlookup(){
				$bme = $this->load->database('nwfth2',true);
				
				$custkey = $this->input->post('custkey');
				#$mydta = $this->ndb->select('itemkey')->where('custkey',$custkey)->get('tbl_ref_fg_labels')->result();
				$mydta = $bme
							->select('INMAST.itemkey')
							->join("PRMXCUSTITEMD PD","INMAST.itemkey = PD.itemkey","left")
							->join("PRMXCUSTITEMH PH","PH.PMKEY = PD.PMKEY","left")
							->where('PH.Custkey',$bme->escape($custkey))->get('INMAST')->result();
				$mm = [];
				if($mydta){
					$mwhere = [];
					foreach($mydta as $k=>$v){
						$mwhere[] = $v->itemkey;
					}
	
					$mm = $bme->where("Itemkey in ('".implode("','",$mwhere)."') ")
							->select("Itemkey as id, concat(Itemkey,' - ',Desc1) as text")
							->get('inmast')->result();
				}

				echo json_encode($mm);
				

			}

	}