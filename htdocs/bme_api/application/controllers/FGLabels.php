<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
	require 'vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	use Spipu\Html2Pdf\Exception\Html2PdfException;
	use Spipu\Html2Pdf\Exception\ExceptionFormatter;	
	class FGLabels extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=51;
				parent::__construct();
				
				$this->table="tbl_fg_labels";
				$this->pkey="tbl_fg_labels_id";
				$this->load->helper('labels');
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 
					$this->includeJS[]="assets/js/page/fglabels.js";
				
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> FG Labels");
				 $this->template->set("titlepage", "FG Labels");
				 $this->template->newload("pages/FGLabels", array() , $data);
				 
			}
		
			protected function before_insert($param=[]){
				

				$lotcode = $param['lotcode'];
				$expiry_date = $param['expiry_date'];

				$nexpiry_date = strtotime("+".$expiry_date." days ". $lotcode);

				$param['expiry_date'] = date("Y-m-d",$nexpiry_date);

				
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
				$mydta = $this->ndb->select('itemkey')->where('custkey',$custkey)->get('tbl_ref_fg_labels')->result();
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

			public function reprint(){
				$id = $this->input->get('id');
				$qty_to_print_start = $this->input->get('pstart');
				$qty_to_print_end = $this->input->get('pend');

				$itemdata = $this->ndb->where("tbl_fg_labels_id",$id)->get("tbl_fg_labels")->row();
				if($qty_to_print_start == '')
					$qty_to_print_start = $itemdata->qty_to_print_start;
				if($qty_to_print_end == '')
					$qty_to_print_end = $itemdata->qty_to_print_end;

				$toinsert = [
					"tbl_fg_label_id"=>$id,
					"qty_to_print_start"=>$qty_to_print_start,
					"qty_to_print_end"=>$qty_to_print_end,
					'data' => json_encode($itemdata)
				];

				$this->models->Add("tbl_fg_label_reprint",$toinsert);

				$this->print();
			}

			public function print(){
				ob_start();
				 $bme=$this->load->database("nwfth2",true);
				 $id = $this->input->get('id');
				 $qty_to_print_start = $this->input->get('pstart');
				 $qty_to_print_end = $this->input->get('pend');
				

				/*
				$itemdata = $this->ndb->where("tbl_fg_labels_id",$id)->get("tbl_fg_labels")->row();

				$template = $this->ndb->where('itemkey',$itemdata->itemkey)->get('tbl_prod_reference')->row();
				

				if($qty_to_print_start == '')
					$qty_to_print_start = $itemdata->qty_to_print_start;
				if($qty_to_print_end == '')
					$qty_to_print_end = $itemdata->qty_to_print_end;

				 $myallergen = [];
				if($itemdata){

					$xdata = $bme
							->select("
								IM.Itemkey, 
								IM.UPCCODE, 
								IM.Manufacturer, 
								IM.Desc1,
								IA.allergens_desc
							")
							->join('v_item_allergen IA ','IM.Itemkey = IA.Itemkey',"left")
							->where([
								"IM.Itemkey"=>$bme->escape($itemdata->itemkey)
							])->get('INMAST IM')->row();
					

				}

					$print = $template->template;
					
					$html = $print($qty_to_print_start,$qty_to_print_end,$xdata,$itemdata,$template);
				*/	

				$itemdata = $this->ndb->where("tbl_fg_labels_id",$id)->get("tbl_fg_labels")->row();

				$template = $this->ndb->where('itemkey',$itemdata->itemkey)->get('tbl_ref_fg_labels')->row();

				
				if($template->template == '' || $template->template == 'default'){
					$template->template = 'template';
				}
				

				if($qty_to_print_start == '')
					$qty_to_print_start = $itemdata->qty_to_print_start;
				if($qty_to_print_end == '')
					$qty_to_print_end = $itemdata->qty_to_print_end;

				$myallergen = [];
				
				$myallergen = json_decode($template->allergen);
				$template->allergen = implode(", ",$myallergen);

				$myprod = json_decode($template->producewith);
				$template->producewith = implode(", ",$myprod);

				$print = $template->template;
				$html = $print($qty_to_print_start,$qty_to_print_end,$itemdata,$template);

					$html2pdf = new Html2Pdf('L', array(101.6,101.6), 'en', true, 'UTF-8', array(2, 0, 0, 0));
					$html2pdf->setDefaultFont('times');
					#$content = ob_get_clean();
					#ob_end_clean();
					$html2pdf->writeHTML($html);
					$html2pdf->output();
			}

			



	}