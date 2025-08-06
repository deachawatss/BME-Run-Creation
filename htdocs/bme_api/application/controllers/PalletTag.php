<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
	require 'vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	use Spipu\Html2Pdf\Exception\Html2PdfException;
	use Spipu\Html2Pdf\Exception\ExceptionFormatter;	
	class PalletTag extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=57;
				parent::__construct();
				
				$this->table="tbl_pallet_tag";
				$this->pkey="pallet_tag_id";
			
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 $this->includeJS[]='assets/js/page/pallettag.js';
				 $this->template->set("title", "<i class=\"fas fa-file\"></i> Pallet Tag");
				 $this->template->set("titlepage", "Pallet Tag");
				 $this->template->newload("pages/PalletTag", array() , $data);
				 
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

			public function printdata(){
				ob_start();
				$bme=$this->load->database("nwfth2",true);
				$id = $this->input->get('id');

				$runno = $this->ndb->where("pallet_tag_id", $id)->get("tbl_pallet_tag")->row()->runno;
				
				$mydata = $this->ndb->where('runno', $runno)->order_by('batchno')->get('v_rundetails')->result();

				$html = "<style>
						table {
							border-collapse:separate; 
							border-spacing: 0 1em;
						}
						td {
							padding-top: 10px; 
  							padding-bottom:10px
						}
						</style>
				";
				foreach($mydata as $k => $v){
					$html .= "<page backtop='3mm' backbottom='0mm' backleft='0mm' backright='0mm' >";
						$html .= "<div style='border=1px solid black;width:710px;margin:40px 10px '>";
							$html .= "<h1 style='text-align:center'>PALLET TAG FOR PREBATCHED RM</h1>";
							$html .= "<h3 style='text-align:right;'>Run No: <u>$runno</u></h3>";

							$html .= "<table width=550 style='font-size:50px;font-weight:bold' >";
							$html .= "<col width=320></col> <col width=420></col> ";
							$html .= "<tr ><td >Batch Ticket:</td><td style='border-bottom:1px solid black;text-align:center'>".$v->batchno."</td></tr>";
							$html .= "<tr style='font-size:35px;'><td>Product Description:</td><td style='border-bottom:1px solid black;text-align:center'>".$v->formulaid."</td></tr>";
							$html .= "<tr><td>Pallet #:</td><td style='border-bottom:1px solid black;text-align:center'>".($k + 1)." of ".count($mydata)."</td></tr>";
							$html .= "<tr><td>Batch No:</td><td style='border-bottom:1px solid black;text-align:center'>".($k + 1)." / ".count($mydata)."</td></tr>";
							$html .= "<tr style='font-size:14px;'><td style='text-align:left'>Form No.: TF145a Rev. 01</td><td style='text-align:right'>Issued Date: 21-Jun-2021</td></tr>";
							$html .= "</table>";

						$html .= "</div>";
					$html .= "</page>";
				}
				

				$html2pdf = new Html2Pdf('L', array(210 ,148.5), 'en', true, 'UTF-8', array(2, 0, 0, 0));
				$html2pdf->setDefaultFont('times');
				$html2pdf->writeHTML($html);
				$html2pdf->output();
			}

	}