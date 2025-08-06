<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
	require 'vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	use Spipu\Html2Pdf\Exception\Html2PdfException;
	use Spipu\Html2Pdf\Exception\ExceptionFormatter;
	class XPLabels extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=50;
				parent::__construct();
				
				$this->table="tbl_xp_labels";
				$this->pkey="tbl_xp_labels_id";
			
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 
				 $this->includeJS[]='assets/js/page/xplabels.js';
				 $this->template->set("title", "<i class=\"fas fa-file-contract\"></i> XP Labels");
				 $this->template->set("titlepage", "XP Labels");
				 $this->template->newload("pages/XPLabels", array() , $data);
				 
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

			public function reprint(){
				$id = $this->input->get('id');
				$qty_to_print_start = $this->input->get('pstart');
				$qty_to_print_end = $this->input->get('pend');

				$itemdata = $this->ndb->where("tbl_xp_labels_id",$id)->get("tbl_xp_labels")->row();
				if($qty_to_print_start == '')
					$qty_to_print_start = $itemdata->qty_to_print_start;
				if($qty_to_print_end == '')
					$qty_to_print_end = $itemdata->qty_to_print_end;

				$toinsert = [
					"tbl_xp_label_id"=>$id,
					"qty_to_print_start"=>$qty_to_print_start,
					"qty_to_print_end"=>$qty_to_print_end,
				];

				$this->models->Add("tbl_xp_label_reprint",$toinsert);

				$this->print();
			}

			public function print(){
				ob_start();
				 $bme=$this->load->database("nwfth2",true);
				 $id = $this->input->get('id');
				 $qty_to_print_start = $this->input->get('pstart');
				 $qty_to_print_end = $this->input->get('pend');

				 $allergen_code = [
					1 => "Colors",
					2 => "Soy",
					3 => "Sulphites",
					4 => "Egg",
					5 => "Dairy",
					6 => "MSG",
					7 => "Crustacean",
					8 => "Tree Nuts",
					9 => "Peanuts",
					10 => "Fish",
					11 => "Sesame",
					12 => "Wheat",
					13 => "Mustard",
					14 => "Celery",
					15 => "Mollusk"
				 ];

				 $allergen = [
					1 => "Colors",
					2 => "Soy",
					3 => "Sulphites",
					4 => "Egg",
					5 => "Dairy",
					6 => "MSG",
					7 => "Crustacean",
					8 => "Tree Nuts",
					9 => "Peanuts",
					10 => "Fish",
					11 => "Sesame",
					12 => "Wheat",
					13 => "Mustard",
					14 => "Celery",
					15 => "Mollusk",
				 ];
				
				
				$itemdata = $this->ndb->where("tbl_xp_labels_id",$id)->get("tbl_xp_labels")->row();

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

						$html = "<style>
								.text-center{
									text-align:center;
								}
								.text-left{
									text-align:left;
								}
								.text-right{
									text-align:right;
								}
								.noborder{
									border:none;
								}
								.no-border-t{
									border-top:none;
								}
								.no-border-b{
									border-bottom:none;
								}
								.no-border-l{
									border-left:none;
								}
								.no-border-r{
									border-right:none;
								}
								
								.square {
									height: 2px;
									width: 15px;
									border:1px solid black;
								}
								.checkbox{
									border: 0px solid grey;
									margin:0px;
									padding:0px;
									font-size: 18px;
								}
							</style>";
					
					for( $a=$qty_to_print_start ; $a<=($qty_to_print_end + 2); $a++ ){

						$html .= "<page backtop='3mm' backbottom='0mm' backleft='0mm' backright='0mm'>";
						$html .= "<h1 class='text-center' style='margin:4px auto 0px auto;font-size:".(strlen($xdata->UPCCODE) >= 20 ? '25px' : '35px')."'>$xdata->UPCCODE</h1>";
						$html .= "<h2 class='text-center' style='margin:4px auto 0px auto;font-size:25px'>Item Key:$xdata->Itemkey</h2>";
						$html .= "<h3 class='text-center' style='margin:4px auto 0px auto;font-size:25px'>Allergens/Sensitives:</h3>";
						$html .= "<h4 class='text-center' style='margin:4px auto 0px auto;font-size:25px'>". ($xdata->allergens_desc ?  str_replace('Sulfites',"Sulphites",$xdata->allergens_desc) : '')  ."</h4>";
						$html .= "<h2 class='text-center' style='margin:4px auto 0px auto;'>Net Wt.:$xdata->Manufacturer Kg</h2>";
						
						
						$html .= "<table  style='margin:4px 0px 0px 0px;font-size:20px'>";
						$html .= "<col width=140></col> 
									<col width=30></col>
									<col width=70></col>
									<col width=140></col> ";
							$html .= "<tr>";
								$html .= "<td class='text-right' colspan=2><b>Lot Code:</b></td>";
								$html .= "<td  colspan=2><b>".date("d-M-Y",strtotime($itemdata->lotcode))."</b></td>";
							$html .= "</tr>";
							$html .= "<tr>";
								$html .= "<td class='text-right' colspan=2><b>Best Before:</b></td>";
								$html .= "<td  colspan=2><b>".date("d-M-Y",strtotime($itemdata->expiry_date))."</b></td>";
							$html .= "</tr>";
							
							$html .= "<tr>";
								$html .= "<td class='text-right' ><b>Batch:</b></td>";
								$html .= "<td><b>".str_pad($itemdata->batchno,2,"0",STR_PAD_LEFT)."</b></td>";
								
								
								if($a > $qty_to_print_end ){
									$html .= "<td colspan=2><b>Sample Label ".str_pad(($a - $qty_to_print_end),2,"0",STR_PAD_LEFT)."</b></td>";
								}else{
									$html .= "<td class='text-left'><b>Bag No.</b></td>";
									$html .= "<td><b>".str_pad($a,2,"0",STR_PAD_LEFT)."</b></td>";
								}

								
								
							$html .= "</tr>";

						$html .= "</table>";
						
						$html .= "<h3 class='text-center' style='margin:5px auto 0px auto;'>Store Under Dry and Cool Conditions</h3>";
								
						$html .= "<table style='margin:5px 0px 0px 0px;font-size:19px'>";
						$html .= "<col width=180></col> 
									<col width=250></col> ";
							$html .= "<tr>";
								$html .= '<td colspan=2><barcode dimension="1D" type="C128" value="(02)'.$xdata->Itemkey.'(10)'.date("dmy",strtotime($itemdata->lotcode))."B".$itemdata->batchno.'(17)'.date("dmy",strtotime($itemdata->expiry_date)).'" label="label" style="margin:5px 0px 0px 0px; width:95mm; height:15mm;  font-size: 4mm"></barcode></td>';
							$html .= "</tr>";
						$html .= "</table>";
						$html .= "</page>";
					}

					$html2pdf = new Html2Pdf('P', array(101.6,101.6), 'en', true, 'UTF-8', array(3, 0, 0, 0));
					$html2pdf->setDefaultFont('times');
					#$content = ob_get_clean();
					#ob_end_clean();
					$html2pdf->writeHTML($html);
					$html2pdf->output();
			}

	}