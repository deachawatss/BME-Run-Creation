<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
	require 'vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	use Spipu\Html2Pdf\Exception\Html2PdfException;
	use Spipu\Html2Pdf\Exception\ExceptionFormatter;		
	class RMLabel extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=46;
				parent::__construct();
				$this->pkey="LotTranNo";
				$this->table="LT";
				$this->ndb = $this->load->database('nwfth2',true);
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 
					$this->includeJS[]="assets/js/page/RMLabel.js";
				
				 $this->template->set("title", "<i class=\"fas fa-file-contract\"></i> RM Label");
				 $this->template->set("titlepage", "RM Label");
				 $this->template->newload("pages/RMLabel", array() , $data);
				 
			}

			public function findreceiptdoc(){
				$param = $this->input->post();
				$data = [];
				
				
				if($param['ReceiptDocNo']){

					$xdata = $this->ndb
						->select("
							LT.LotTranNo,
							LT.ReceiptDocNo,
							LT.ItemKey,
							IM.Desc1,
							LT.LotNo,
							AV.Vendor_Name,
							LT.VendorlotNo,
							LT.QtyReceived,
							LT.DateReceived,
							LT.DateExpiry,
							IQ.ToKey,
							IQ.Convfctr,
							QL.Status,
							QR.Tested_By,
							IPD.PropDesc
						")
						->join('INMAST IM','IM.Itemkey = LT.ItemKey')
						->join('APVEND AV','AV.Vendor_Key = LT.Vendorkey')
						->join('QCLotTransaction QL','QL.ReceiptDocNo = LT.ReceiptDocNo','left')
						->join('QCResult QR','QR.Receipt_no = LT.ReceiptDocNo','left')
						->join('INMSPEC IP','IP.ItemKey = LT.ItemKey and IP.PropValue > 0 and IP.PropNumber < 16','left')
						->join('INMSPECD IPD','IPD.PropNumber = IP.PropNumber','left')
						->join('INQTYCNV IQ','IQ.UMItemKey = LT.ItemKey','left')
						->where([
							"LT.TransactionType"=>"1",
							"LT.ReceiptDocNo" => $this->ndb->escape($param['ReceiptDocNo'])
						])->get('LotTransaction LT')->result();
						
						$ndata = [];
						$LotTranNo = [];
						
						foreach($xdata as $k => $v){
							if( !in_array($v->LotTranNo,$LotTranNo) ){
								$LotTranNo[]= $v->LotTranNo;
								$ndata[$v->LotTranNo]=$v;
								$allergen = [];
								$allergen[] = $v->PropDesc; 
							}else{
								
								if(!in_array($v->PropDesc,$allergen)){
									$allergen[]=$v->PropDesc;
								}
								$ndata[$v->LotTranNo]->PropDesc = implode(",",$allergen);
								#$ndata[$v->LotTranNo]->PropDesc = $ndata[$v->LotTranNo]->PropDesc.",".$v->PropDesc;
							}
						}

						$vdata = [];
						foreach($ndata as $k =>$v){
							$vdata[]=$v;
						}

						$data['data']=$vdata;

						$data['msgno'] = "100";
					}else{
						$data['msgno'] = "99";
				}
				
				echo json_encode($data);
			}
		
			public function findtransno(){
				$param = $this->input->post();
				$data = [];
				
				if($param['transno']){

					$xdata = $this->ndb
						->select("
						LT.LotTranNo,
						LT.ReceiptDocNo,
						LT.ItemKey,
						IM.Desc1,
						LT.LotNo,
						AV.Vendor_Name,
						LT.VendorlotNo,
						LT.QtyReceived,
						LT.DateReceived,
						LT.DateExpiry,
						IQ.ToKey,
						IQ.Convfctr,
						QL.Status,
						QR.Tested_By,
						IPD.PropDesc,
						")
						->join('INMAST IM','IM.Itemkey = LT.ItemKey')
						->join('APVEND AV','AV.Vendor_Key = LT.Vendorkey')
						->join('QCLotTransaction QL','QL.ReceiptDocNo = LT.ReceiptDocNo','left')
						->join('QCResult QR','QR.Receipt_no = LT.ReceiptDocNo','left')
						->join('INMSPEC IP','IP.ItemKey = LT.ItemKey and IP.PropValue > 0 and IP.PropNumber < 16','left')
						->join('INMSPECD IPD','IPD.PropNumber = IP.PropNumber','left')
						->join('INQTYCNV IQ','IQ.UMItemKey = LT.ItemKey','left')
						->where([
							"LT.TransactionType"=>"1",
							"LT.LotTranNo" => $this->ndb->escape($param['transno'])
						])->get('LotTransaction LT')->result();
						
						$ndata = [];
						$LotTranNo = [];
						
						foreach($xdata as $k => $v){
							if( !in_array($v->LotTranNo,$LotTranNo) ){
								$LotTranNo[]= $v->LotTranNo;
								$ndata[$v->LotTranNo]=$v;
								$allergen = [];
								$allergen[] = $v->PropDesc; 
							}else{
								
								if(!in_array($v->PropDesc,$allergen)){
									$allergen[]=$v->PropDesc;
								}
								$ndata[$v->LotTranNo]->PropDesc = implode(",",$allergen);
								#$ndata[$v->LotTranNo]->PropDesc = $ndata[$v->LotTranNo]->PropDesc.",".$v->PropDesc;
							}
						}

						$vdata = [];
						foreach($ndata as $k =>$v){
							$vdata=$v;
						}

						$data['data']=$vdata;

					
					$data['msgno'] = "100";
				}else{
					$data['msgno'] = "99";
				}
				echo json_encode($data);

			}

			public function print(){
				ob_start();
				$bme=$this->load->database("nwfth2",true);
				 $xcount = $this->input->get('count');
				 $pLotTranNo = $this->input->get('tranNo');

				$xdata = $this->ndb
						->select("
							LT.LotTranNo,
							LT.ReceiptDocNo,
							LT.ItemKey,
							IM.Desc1,
							LT.LotNo,
							AV.Vendor_Name,
							LT.VendorlotNo,
							LT.QtyReceived,
							LT.DateReceived,
							LT.DateExpiry,
							IQ.ToKey,
							IQ.Convfctr,
							QL.Status,
							QR.Tested_By,
							IPD.PropDesc
						")
						->join('INMAST IM','IM.Itemkey = LT.ItemKey')
						->join('APVEND AV','AV.Vendor_Key = LT.Vendorkey')
						->join('QCLotTransaction QL','QL.ReceiptDocNo = LT.ReceiptDocNo','left')
						->join('QCResult QR','QR.Receipt_no = LT.ReceiptDocNo','left')
						->join('INMSPEC IP','IP.ItemKey = LT.ItemKey and IP.PropValue > 0 and IP.PropNumber < 16','left')
						->join('INMSPECD IPD','IPD.PropNumber = IP.PropNumber','left')
						->join('INQTYCNV IQ','IQ.UMItemKey = LT.ItemKey','left')
						->where([
							"LT.TransactionType"=>"1",
							"LT.LotTranNo" => $this->ndb->escape($pLotTranNo)
						])->get('LotTransaction LT')->result();
						
						$ndata = [];
						$LotTranNo = [];
						
						foreach($xdata as $k => $v){
							if( !in_array($v->LotTranNo,$LotTranNo) ){
								$LotTranNo[]= $v->LotTranNo;
								$ndata[$v->LotTranNo]=$v;
								$allergen = [];
								$allergen[] = $v->PropDesc; 
							}else{
								
								if(!in_array($v->PropDesc,$allergen)){
									$allergen[]=$v->PropDesc;
								}
								$ndata[$v->LotTranNo]->PropDesc = implode(",",$allergen);
								#$ndata[$v->LotTranNo]->PropDesc = $ndata[$v->LotTranNo]->PropDesc.",".$v->PropDesc;
							}
						}

						$vdata = [];
						foreach($ndata as $k =>$v){
							$vdata=$v;
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
					
					for( $a=1 ; $a<=$xcount; $a++ ){

						$html .= "<page>";
						$html .= "<h1 class='text-left' style='margin:5px 0px 0px 0px;'>".$vdata->ItemKey."</h1>";
						$html .= "<h4 class='text-left' style='margin:5px 0px 0px 0px;'>$vdata->Desc1</h4>";
						$html .= "<h4 class='text-left' style='margin:5px 0px 0px 0px;'>Lot No:&nbsp;$vdata->LotNo</h4>";
						$html .= '<barcode dimension="1D" type="C128" value="(02)'.$vdata->ItemKey.'(10)'.$vdata->LotNo.'" label="label" style="margin:5px 0px 0px 0px; width:95mm; height:15mm;  font-size: 4mm"></barcode>';
						$html .= "<table style='margin:10px 0px 0px 0px;font-size:15px'>";
						$html .= "<col width=180></col> 
								<col width=180></col> ";

						$html .= "<tr>";
							$html .= "<td colspan=2><b>Vendor:</b>&nbsp;$vdata->Vendor_Name</td>";
						$html .= "</tr>";
						$html .= "<tr>";
							$html .= "<td colspan=2><b>Vendor Lot Code:</b>&nbsp;$vdata->VendorlotNo</td>";
						$html .= "</tr>";
						$html .= "<tr>";
							$html .= "<td colspan=2><b>PR No:</b>&nbsp;$vdata->ReceiptDocNo</td>";
						$html .= "</tr>";
						$html .= "<tr>";
							$html .= "<td><b>Pack Size:</b>&nbsp;$vdata->Convfctr</td>";
							$html .= "<td><b>Qty in KG:</b>&nbsp;$vdata->QtyReceived</td>";
						$html .= "</tr>";
						$html .= "<tr>";
							$html .= "<td><b>Pack Type:</b>&nbsp;$vdata->ToKey</td>";
							$html .= "<td><b>Qty in UOM:</b>&nbsp;".($vdata->QtyReceived / $vdata->Convfctr)."</td>";
						$html .= "</tr>";
						$html .= "<tr>";
							$html .= "<td><b>Received:</b>&nbsp;".date("d-M-Y",strtotime($vdata->DateReceived))."</td>";
							$html .= "<td><b>Expiry:</b>&nbsp;".date("d-M-Y",strtotime($vdata->DateExpiry))."</td>";
						$html .= "</tr>";
						
						#PASS TAG
						$html .= "<tr>";
							$html .= "<td colspan=2><span style='font-size:25px;margin-top:10px;'>".($vdata->Status == "ACCEPTED" ? "QC PASSED": "")."</span></td>";
						$html .= "</tr>";
						$html .= "<tr>";
							$html .= "<td style='font-size:15px;'><b>Tested By:</b>&nbsp;".$vdata->Tested_By."</td>";
							$html .= "<td style='font-size:15px;'><b>Allergen:</b>&nbsp;".$vdata->PropDesc."</td>";
						$html .= "</tr>";

						$html .= "</table>";
						#$html .= "<h4 class='text-left' style='margin:5px 0px 0px 0px;'>Vendor:&nbsp;$vdata->Vendor_Name</h4>";
						#$html .= "<h4 class='text-left' style='margin:5px 0px 0px 0px;'>Vendor Lot Code:&nbsp;$vdata->VendorlotNo</h4>";
						#$html .= "<h4 class='text-left' style='margin:5px 0px 0px 0px;'>Pack Size:&nbsp;$vdata->Convfctr</h4>";
						
						#$html .= "<h3 class='text-center' style='margin:0px;'>Net wt:".(isset($v->Key) && $v->Key == 'Quantity' ? $v->keyvalue : '')."</h3>";
						#$html .= "<h3 class='text-center' style='margin:0px;'>Lot Code: ".date("F d, Y")."</h3>";
						$html .= "</page>";
					}

					$html2pdf = new Html2Pdf('L', array(101.6,101.6), 'en', true, 'UTF-8', array(3, 0, 0, 0));
		
					#$content = ob_get_clean();
					#ob_end_clean();
					$html2pdf->writeHTML($html);
					$html2pdf->output();
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