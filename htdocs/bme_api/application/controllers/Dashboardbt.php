<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class Dashboardbt extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=56;
				parent::__construct();
				
				$this->page_load();
			}
		
			public function index_old(){
		
				 $data = array();

				 $mydata = $this->ndb
				 		->select("tbl_runhrd.*, tbl_rundata.*")
				 		->join("tbl_runhrd "," tbl_runhrd.runid = tbl_rundata.runid")
						#->group_by("tbl_runhrd.runid")
						->order_by("tbl_runhrd.runno asc")
						->get("tbl_rundata")
						->result();
				
				$bme = $this->load->database('nwfth',true);
				$bme->protect_identifiers(false);
				
				#print_r($mydata);

				$r1 = [];
				$r2 = [];
				
				 foreach($mydata as $k => $v){
					
					
					$req = $bme
							->where("BatchNo",$bme->escape($v->batchno))
							->get("v_prod_assembly")->result();
							
							
					$rec = $bme
						->select("BatchNo,FormulaId,BatchWeight, SchStartDate as BatchTicketDate,Status")
						->where("(status = 'R' and BatchType = 'M')  and BatchNo = '$v->batchno'")
						->from('PNMAST')->get()->result();
					
					
					
					
					$tpickstat = "New";
					$pstat = [];
					$bstat = [];
					foreach($req as $kk => $vv){
						#get picked partial
						$partial = $this->ndb->select("sum(qty) as psum")->where("batchno",$vv->BatchNo)->where("itemkey",$vv->ItemKey)->get("tbl_rm_allocate_partial")->row()->psum;

						

						#get bulk partial
						$bulk = $this->ndb->select("sum(qty) as psum")->where("batchno",$vv->BatchNo)->where("itemkey",$vv->ItemKey)->get("tbl_rm_allocate_bulk")->row()->psum;
						
						if($vv->featurevalue){
							if( ($bulk /  $vv->featurevalue)  == $vv->Bulk){
								$bstat[] = "1";
							}else{
								if( $bulk != 0)
									$bstat[] = "2";
								else
									$bstat[] = "0";
							}
						}
						else
							$bstat[] ="0";

						#partial Status
						$wtfrom = ($vv->wtfrom ?? ($vv->PartialData - ($vv->PartialData * 0.01) ) );
						$wtto = ($vv->wtfrom ?? ($vv->PartialData + floatval($vv->PartialData * 0.01) ) );

						if($partial >= $wtfrom && $partial <= $wtto)
							$pstat[] = "1";
						else{

							if( $partial != 0)
								$pstat[] = "2";
							else
								$pstat[] = "0";
						}
							
						

						$vv->ppartial = $partial;
						$vv->pbulk = $bulk;
						$r2[$vv->BatchNo][] = $vv;
					}

						$pscounts = array_count_values($pstat);
						$pscount = isset($pscounts["1"]) ? $pscounts["1"] : 0;
						$pscounts = array_count_values($pstat);
						$pscountp = isset($pscounts["2"]) ? $pscounts["2"] : 0;

						switch(true){

							case ( count($pstat) == $pscount):{
								$v->pstat = "Ready";
								break;
							}

							case ($pscountp!= 0):{
								$v->pstat = "For Completion";
								break;
							}

							default:{
								$v->pstat = "New";
								break;
							}
						}


						$bscounts = array_count_values($bstat);
						$bscount = isset($bscounts["1"]) ? $bscounts["1"] : 0;
						$bscountp = isset($bscounts["2"]) ? $bscounts["2"] : 0;

						switch(true){

							case ( count($bstat) == $bscount):{
								$v->bstat = "Ready";
								break;
							}

							case ( $bscountp):{
								$v->bstat = "For Completion";
								break;
							}

							default:{
								$v->bstat = "New";
								break;
							}
						}

					if( !array_key_exists($v->runno,$r1) ){
							$v->fmystat = [$v->bstat, $v->pstat];
							$v->BatchTicketDate = 	date("m-d-Y",strtotime($rec[0]->BatchTicketDate));
							$data['tbl'][$v->runno] = $v;
					}else{
						$oldstat = $data['tbl'][$v->runno]->fmystat;
						$data['tbl'][$v->runno]->fmystat = array_merge($oldstat,[$v->bstat, $v->pstat]);

					}
					
					$r1[$v->runno][] = $v;

					
				 }

				 $data["r1"] = $r1;
				 $data["r2"] = $r2;
				
				 $this->includeJS[]="assets/js/page/dashboardbt.js";
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> Batchticket Dashboard");
				 $this->template->set("titlepage", "Batchticket Dashboard");
				 $this->template->newload("pages/Dashboardbt1", array() , $data);
				 
			}
		
			public function index(){

				$data = [];

				$bme = $this->load->database('nwfth',true);
				/*
				$details = $bme
								->select("pn.BatchNo,pn.FormulaId,pn.BatchTicketDate,pn.User2")
								->where("pn.User2 <> ''")
								->order_by("pn.User2 asc")
								->get("PNMAST as pn")->result();
				*/
				$details = $bme
								->select("Distinct ItemKey,FormulaId,LineTyp,Desc1,Location,StdQtyDispUom,featurevalue,PartialData,\"Bulk\",BatchNo,User2,wtfrom,wtto,BatchTicketDate,status")
								->where("status",$bme->escape('R'))
								->order_by("User2 asc")
								->get('v_prod_assembly')->result();
				$summary = $bme
							->select("User2")
							->where("status",$bme->escape('R'))
							->order_by("User2 asc")
							->group_by("User2")
							->get('v_prod_assembly')->result();

				$sdata = [];
				
				foreach($summary as $k => $v){
					$sdata[] = $v->User2;
				}

				$bulk = $this->ndb
								->select("itemkey,batchno,runno,sum(qty) as msum")
								->where('runno in ("'.implode('","',$sdata).'")')
								->where('statflag','R')
								->group_by('itemkey,batchno,runno')
								->get('tbl_rm_allocate_bulk')
								->result(); 
				$bulkdata = [];
				foreach($bulk as $k => $v){
					$bulkdata[$v->runno][$v->batchno][$v->itemkey] = $v; 
				}

				$partial = $this->ndb
								->select("itemkey,batchno,runno,sum(qty) as msum")
								->where('runno in ("'.implode('","',$sdata).'")')
								->group_by('itemkey,batchno,runno')
								->get('tbl_rm_allocate_partial')
								->result(); 

				$partialdata = [];
				foreach($partial as $k => $v){
					$partialdata[$v->runno][$v->batchno][$v->itemkey] = $v; 
				}
				$data['dbulk'] = $bulkdata;
				$data['dpartial'] = $partialdata;
				$data["details"] = $details;
				$this->includeJS[]="assets/js/page/dashboardbt.js";
				$this->template->set("title", "<i class=\"fas fa-file\"></i> Batchticket Dashboard");
				$this->template->set("titlepage", "Batchticket Dashboard");
				$this->template->newload("pages/Dashboardbt", array() , $data);
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