<?php
require 'vendor/autoload.php';
	defined("BASEPATH") OR exit("No direct script access allowed");
		
	class Documents extends MY_Controller {
		var $accesswhere = "";
		function __construct()
			{
				$this->pageid=44;
				parent::__construct();
				
				$this->page_load();

				switch($this->userinfo['ulvl']){

					case 1:{
						$this->accesswhere = "1=1";
						break;
					}

					default:{
						$this->accesswhere = "( view_permission like '%".$this->userinfo['uname']."%' or view_group_permission like '%\"".$this->userinfo['userid']."\"%' )";
						break;
					}
				}
				
			}
			/*
				MESSAGE CODE
					1 = success
					101 = Filename already exist
					102 = Folder already exist
					103 = Access Denied
			*/
			public function index(){
		
				 $data = array();
				 $data['sidefolder'] = $this->ndb
				 								->where($this->accesswhere)
				 								#->where("exclude_user not like '%\"".$this->userinfo['userid']."\"%'")
												->where("statflag","A")
				 								->where("folder_parent_id","0")
												->order_by("folder_name asc")
												->get('folder_tbl')->result();
				 $data['menulist'] = [];
				 $data['menudetails'] = [];
				 $data['userlist'] = $this->ndb->select("concat(Lname,', ',Fname) as text,  uname as id")->order_by("Lname asc")->get("tbl_user")->result();
				 $data['usergrouplist'] = $this->ndb->select("group_name as text,  group_id as id")->order_by("group_name asc")->get("group_tbl")->result();

				 $data['boxlist'] = $this->ndb->select("box_name as text,  box_id as id")->order_by("box_name asc")->get("box_tbl")->result();
				 $data['typelist'] = $this->ndb->select("file_type as text,  file_type_id as id")->order_by("file_type asc")->get("file_type_tbl")->result();
				 $data['deptlist'] = $this->ndb->select("dept_name as text,  dept_code as id")->order_by("dept_name asc")->get("dept")->result();

				 $folder = $this->ndb
				 					->where("statflag","A")
				 					->where($this->accesswhere)
				 					->order_by("folder_name asc")
									->get('folder_tbl')->result();

				 foreach($folder as $k => $v){
					$data['menulist'][$v->folder_parent_id][$v->folder_id] =  $v;
					$data['menudetails'][$v->folder_id] = $v;
				 }

				 $this->includeJS[]='assets/js/page/error.js';
				 $this->includeJS[]='assets/js/page/documents/documents.js';
				 $this->includeJS[]='assets/js/page/documents/fileadd.js';
				 $this->includeJS[]='assets/js/page/documents/fileupdate.js';
				 $this->includeJS[]='assets/js/page/documents/filedelete.js';
				 $this->includeJS[]='assets/js/page/documents/folderadd.js';
				 $this->includeJS[]='assets/js/page/documents/folderupdate.js';
				 $this->includeJS[]='assets/js/page/documents/folderdelete.js';
				 $this->includeJS[]='assets/js/jbarcode/JsBarcode.all.min.js';


				 $this->includeCSS[]='assets/css/page/documents/documents.css';
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> Documents");
				 $this->template->set("titlepage", "Documents");
				 $this->template->newload("pages/Documents/Documents", array() , $data);
				 
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

			public function getfolder(){

				$param = $this->input->post();

				$where = '0';
				if($param['folderid'] != ""){
					$where = $param['folderid'];
				}
				
				$data["folder"] = $this->ndb
								->where($this->accesswhere)
								->where("statflag","A")
								->where('folder_parent_id',$where)
								->order_by("folder_name asc")
								->get('folder_tbl')->result();
				
				$data["files"] = $this->ndb
								->where($this->accesswhere)
								->where("statflag","A")
								->where('folder_parent_id',$where)
								->order_by("doc_title asc")
								->get('document_tbl')->result();

				$data["sidefolder"]=$this->ndb
									->where($this->accesswhere)
									#->where("exclude_user not like '%\"".$this->userinfo['userid']."\"%'")
									->where("statflag","A")
									->where("folder_parent_id","0")
									->order_by("folder_name asc")
									->get('folder_tbl')->result();

				$data["folderpermission"] = $this->ndb
								->where('folder_id',$where)
								->get('folder_tbl')->row();

				echo json_encode($data);

			}

			public function addfolder(){
				if($this->userinfo['ulvl'] == 1 || $this->userinfo['ulvl'] == 112){
					$param = $this->input->post();

					
					$folderchk = $this->ndb
						->where("folder_parent_id",$param["current_menu"])
						->where("folder_name",$param["foldername"])
						->get('folder_tbl')->row();

					if(!$folderchk){
						$toinsert = [
							"folder_parent_id" => $param["current_menu"],
							"folder_name" => $param["foldername"] ?? "",
							"view_permission" => isset($param["view_user_permission"]) ? json_encode($param["view_user_permission"]) : "",
							"view_group_permission" => isset($param["view_group_permission"]) ? json_encode($param["view_group_permission"]) : "",
							"upload_permission" => isset($param["upload_user_permission"]) ? json_encode($param["upload_user_permission"]) : "",
							"upload_group_permission" => isset($param["upload_group_permission"]) ? json_encode($param["upload_group_permission"]) : "",
							"delete_permission" => isset($param["delete_user_permission"]) ? json_encode($param["delete_user_permission"]) : "",
							"delete_group_permission" => isset($param["delete_group_permission"]) ? json_encode($param["delete_group_permission"]) : "",
						];

						$this->models->Add("folder_tbl",$toinsert);
						echo json_encode(['msgcode' => '1']);
					}else{
						echo json_encode(['msgcode' => '102']);
					}
				}else{
					echo json_encode(['msgcode' => '103']);
				}
				
			}

			public function updatefolder(){
				if($this->userinfo['ulvl'] == 1 || $this->userinfo['ulvl'] == 112){

					$param = $this->input->post();
					$toupdate = [
						//"folder_parent_id" => $param["current_menu"],
						"folder_name" => $param["foldername"] ?? "",
						"view_permission" => isset($param["view_user_permission"]) ? json_encode($param["view_user_permission"]) : "",
						"view_group_permission" => isset($param["view_group_permission"]) ? json_encode($param["view_group_permission"]) : "",
						"upload_permission" => isset($param["upload_user_permission"]) ? json_encode($param["upload_user_permission"]) : "",
						"upload_group_permission" => isset($param["upload_group_permission"]) ? json_encode($param["upload_group_permission"]) : "",
						"delete_permission" => isset($param["delete_user_permission"]) ? json_encode($param["delete_user_permission"]) : "",
						"delete_group_permission" => isset($param["delete_group_permission"]) ? json_encode($param["delete_group_permission"]) : "",
					];

					$this->models->Update("folder_tbl",$toupdate,"folder_id=".$param["folderid"]);
					echo json_encode([]);
				}else{
					echo json_encode(['msgcode' => '103']);
				}
			}

			public function deletefolder(){
				if($this->userinfo['ulvl'] == 1 || $this->userinfo['ulvl'] == 112){
					$param = $this->input->post();
					$toupdate = [
						"date_deleted" => date("Y-m-d H:i:s"),
						"deleted_by" => $this->userinfo["userid"],
						"statflag" => "D"
					];

					$this->models->Update("folder_tbl",$toupdate,"folder_id=".$param["folderid"]);
					echo json_encode([]);
				}else{
					echo json_encode(['msgcode' => '103']);
				}
			}

			public function addfile(){
				if($this->userinfo['ulvl'] == 1 || $this->userinfo['ulvl'] == 112){
					$param = $this->input->post();
					$folderchk = $this->ndb
						->where("folder_parent_id",$param["current_menu"])
						->where("doc_title",$param["doc_title"])
						->get('document_tbl')->row();

					if(!$folderchk){
						$config['upload_path']          = './filelist/';
						$config['allowed_types']        = 'pdf';
						$config['encrypt_name']        = true;
						$config['allowed_types']        = 'pdf';
						$this->load->library('upload', $config);

						if ( ! $this->upload->do_upload('fileupload'))
						{
								#$error = array('error' => $this->upload->display_errors());
							
							echo json_encode(['msgcode' => '104']);
						}
						else
						{
							$udata = $this->upload->data();

							$docno = $this->ndb->select('max(doc_no) as docno')->where('dept',$param["dept"])->get('document_tbl')->row();
							
							if($docno->docno){
								
								$tmpdoc = intval(substr($docno->docno,3)) + 1;
								$docno =  $param["dept"]."-". str_pad($tmpdoc,11,"0",STR_PAD_LEFT);
							}else{
								$docno = $param["dept"]."-". str_pad(1,11,"0",STR_PAD_LEFT);
							}

							#$boxdata = $this->ndb->where('box_id',$param["box_id"])->get('box_tbl')->row();
							$toinsert = [
								"folder_parent_id" => $param["current_menu"],
								"doc_title" => $param["doc_title"] ?? "",
								"doc_type" => $param["doc_type"] ?? "",
								"description" => $param["description"] ?? "",
								"box_id" => $param["box_id"] ?? "",
								"retention" => $param["retention"] ?? "",
								#"file_location" => $param["file_location"] ?? "",
								"encrypted_name" => $udata["file_name"] ?? "",
								"fillingname" => $udata["orig_name"] ?? "",
								"dept" => $param["dept"] ?? "",
								"doc_no" => $docno ?? "",
								"tags" => isset($param["tags"]) ? json_encode($param["tags"]) : "",
								"view_permission" => isset($param["view_user_permission"]) ? json_encode($param["view_user_permission"]) : "",
								"view_group_permission" => isset($param["view_group_permission"]) ? json_encode($param["view_group_permission"]) : "",
								"upload_permission" => isset($param["upload_user_permission"]) ? json_encode($param["upload_user_permission"]) : "",
								"upload_group_permission" => isset($param["upload_group_permission"]) ? json_encode($param["upload_group_permission"]) : "",
								"delete_permission" => isset($param["delete_user_permission"]) ? json_encode($param["delete_user_permission"]) : "",
								"delete_group_permission" => isset($param["delete_group_permission"]) ? json_encode($param["delete_group_permission"]) : "",
							];

							#barcodeformat roomid - rackid - boxid - date
							#boxdata->room_id boxdata->rack_id boxdata->box_id date

							$recordno = $this->models->Add("document_tbl",$toinsert);
							$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
							echo json_encode(['msgcode' => '1',"barcode" => base64_encode($generator->getBarcode($docno, $generator::TYPE_CODE_128)), "recid" => $docno]);
	
						}

					}else{
						echo json_encode(['msgcode' => '102']);
					}
				}else{
					echo json_encode(['msgcode' => '103']);
				}
				
			}

			public function getfile(){
				$param = $this->input->get();
				$mdata = $this->ndb
								->where('doc_no',$param['docno'])
								->where($this->accesswhere)
								->get('document_tbl')->row();
				
				if($mdata){

					header("Content-type: application/pdf");
					header("Content-Disposition: inline; filename=".$mdata->fillingname);
					@readfile('./filelist/'.$mdata->encrypted_name);

				}else{

				}
				
			}

			public function genBarcode(){

			}

			public function fileprint(){
				
			}

	}