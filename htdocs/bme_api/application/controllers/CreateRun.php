<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class CreateRun extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=55;
				parent::__construct();
				
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 
				 $this->includeJS[]="assets/js/page/CreateRun.js";
				
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> Create Run");
				 $this->template->set("titlepage", "Create Run");
				 $this->template->newload("pages/CreateRun", array() , $data);
				 
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

			public function gereftList(){
				$this->ndb = $this->load->database('nwfth',true);
				$this->ndb->protect_identifiers(false);
				$mydata = $this->ndb
					->select("BatchNo,FormulaId,BatchWeight, SchStartDate as BatchTicketDate,Status")
					->where("(status = 'R' and BatchType = 'M') and (User2 is NULL  or User2 = '')")
					->from('PNMAST')->get()->result();



				$this->output->set_header("Pragma: no-cache")
					->set_header("Cache-Control: no-store, no-cache")
					->set_content_type('application/json')
					->set_output(json_encode(["data"=>$mydata]));

			}

			public function getOpen(){
				$this->load->library('Datatable_mssql', ['model' => 'datatables/CreateRun/CreateRun_dt', 'rowIdCol' => "tbl_runhrd.runid", "table"=> "tbl_runhrd" ] );
				$this->output->set_header("Pragma: no-cache")
							->set_header("Cache-Control: no-store, no-cache")
							->set_content_type('application/json')
							->set_output(json_encode($this->datatable_mssql->datatableJson()));
			}
			
			public function saverun(){
				$bme = $this->load->database('nwfth',true);
				$param = $this->input->post();
				$param_init = $param['list'][0]; 
				$mylist = [];
				$msg = [
					"msgno" => '',
					"msg" => ''
				];
				
				foreach($param['list'] as $k => $v){
					$mylist[] = $v['BatchNo'];
				}

				if($param['type'] == 'A'){
					$mstat = true;

					$mlist = $this->ndb->query("
								Select count(*) as mlist from  tbl_rundata where batchno in ('".implode("','",$mylist)."')
								")->row()->mlist;
					
					if($mlist > 0){
						$msg = [
							"msgno" => '200',
							"msg" => 'Batch No is already included to another Run.'
						];
						echo json_encode($msg);

						die();
					}

					$addhrd = [
						"formulaid" => $param_init['FormulaId'],
						"batchsize" => $param_init['BatchWeight'],
						"statflag"	=> 'N'
					];
					
	
					$mdata = $this->ndb->select('Max(runno) as maxdata')->where('runno like "'.date("y").'%" ')->get('tbl_runhrd')->row();
	
					if($mdata->maxdata){
						$nrec = intval(substr($mdata->maxdata,2)) + 1;
						$addhrd['runno'] =  date('y').str_pad($nrec,6,"0",STR_PAD_LEFT) ;
					}else{
						$addhrd['runno'] =  date('y')."000001";
					}
					
					#$bme->trans_start();
					#print_r($bme->trans_status());
					$this->ndb->trans_start();
					$nid = $this->models->Add('tbl_runhrd',$addhrd);
		
					$addlist = [
						"runid" => $nid,
						"statflag" => "N"
					];
		
					#BME Paramiter
					$bmeupdate = [
						"User2" => $bme->escape($addhrd['runno'])
					];

					foreach($param['list'] as $k => $v){
						$addlist['batchno'] = $v['BatchNo'];
						$this->models->Add('tbl_rundata',$addlist);
						$bme->where("BatchNo",$bme->escape($v['BatchNo']))->update("PNMAST",$bmeupdate);
					}

						
					
					if($mstat){

						$this->db->trans_complete();
						#$bme->trans_complete();
					}
					else{

						$this->db->trans_rollback();
						#$bme->trans_rollback();
					}
					
					$msg = [
							"msgno" => '100',
							"msg" => 'Your Run No. is '. $addhrd['runno']
					];
					
				}else{

					if(isset($param['runid']) && isset($param['runno'])){
						$this->ndb->trans_start();
						#$bme->trans_start();
						$this->ndb->where("runid",$param['runid'])->delete("tbl_rundata");

						$mstat = true;

						$mlist = $this->ndb->query("
									Select count(*) as mlist from  tbl_rundata where batchno in ('".implode("','",$mylist)."') and runid != '".$param['runid']."'
									")->row()->mlist;
						
						#Reset BME data
						$resetbmedata = [
							"user2" => $bme->escape('')
						];

						$bme->where('user2',$bme->escape($param['runno']))->update("PNMAST",$resetbmedata);

						$updateData = [
							"formulaid" => $param_init['FormulaId'],
							"batchsize" => $param_init['BatchWeight'],
						];

						$nid = $this->models->Update('tbl_runhrd',$updateData,"runid='".$param['runid']."'");
						
						if($mlist > 0){
							$msg = [
								"msgno" => '200',
								"msg" => 'Batch No is already included to another Run.'
							];
							echo json_encode($msg);

							die();
						}

						$addlist = [
							"runid" => $param['runid'],
							"statflag" => "N"
						];

						#Update BME data
						$bmedata = [
							"user2" => $bme->escape($param['runno'])
						];
		
						foreach($param['list'] as $k => $v){
							$addlist['batchno'] = $v['BatchNo'];
							$this->models->Add('tbl_rundata',$addlist);
							$bme->where('BatchNo',$bme->escape($v['BatchNo']))->update("PNMAST",$bmedata);
						}
						
						if($mstat)
							$this->db->trans_complete();
						else
							$this->db->trans_rollback();
						
						$msg = [
								"msgno" => '100',
								"msg" => 'Successfully Updated'
						];

					}else{

						$msg = [
							"msgno" => '200',
							"msg" => 'Invalid Run No.'
						];

					}

				}

				echo json_encode($msg);
				
			}

			public function getOpenList(){
				$bme = $this->load->database("nwfth",true);

				$param = $this->input->post();
				$mydata = $this->ndb->where("runid",$param['runid'])->get('tbl_rundata')->result();
				$myselected = [];
				$myselectedx = [];
				$totalbatch = 0;
				$totalsize = 0;
				if($mydata){

					foreach($mydata as $k=>$v){
						$myselected[] =$v->batchno;
					}

					$mlist = $bme->query("
								Select
								BatchNo,
								FormulaId, 
								BatchWeight,
								SchStartDate as BatchTicketDate,
								Status
								from  PNMAST where BatchNo in ('".implode("','",$myselected)."')
							")->result();
					
					foreach($mlist as $k=>$v){
						$myselectedx[$v->BatchNo] = $v;
						$totalbatch++;
						$totalsize += $v->BatchWeight;
					}

				}
					

				echo json_encode(["mydata" => $mlist, "mdata" => $myselectedx, "totalbatch" => $totalbatch, "totalsize" => $totalsize]);
			}

			public function remdata(){
				$bme = $this->load->database('nwfth',true);
				$param = $this->input->post();
				$mylist = [];
				$msg = [
					"msgno" => '',
					"msg" => ''
				];

				$this->ndb->where("runid",$param['runid'])->delete("tbl_runhrd");
				$this->ndb->where("runid",$param['runid'])->delete("tbl_rundata");

				#Reset BME data
				$resetbmedata = [
					"user2" => $bme->escape('')
				];

				$bme->where('user2',$bme->escape($param['runno']))->update("PNMAST",$resetbmedata);

				$msg = [
					"msgno" => '100',
					"msg" => 'Successfully Removed',

				];

				echo json_encode($msg);
			}

	}