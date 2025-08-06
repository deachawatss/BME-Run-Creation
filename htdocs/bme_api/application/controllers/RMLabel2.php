<?php
	defined("BASEPATH") OR exit("No direct script access allowed");
	require 'vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	use Spipu\Html2Pdf\Exception\Html2PdfException;
	use Spipu\Html2Pdf\Exception\ExceptionFormatter;		
	class RMLabel2 extends MY_Controller {
		
		function __construct()
			{
				$this->pageid=48;
				parent::__construct();
				$this->pkey="LotTranNo";
				$this->table="LT";
				$this->ndb = $this->load->database('nwfth',true);
				$this->has_enter_by = false;
				$this->has_created_at = false;
				$this->has_updated_by = false;
				$this->has_updated_date = false;
				$this->page_load();
			}
		
			public function index(){
		
				 $data = array();
				 
					$this->includeJS[]="assets/js/page/RMLabel2.js";
				
				 $this->template->set("title", "<i class=\"fas fa-file-contract\"></i> RM Label");
				 $this->template->set("titlepage", "RM Label");
				 $this->template->newload("pages/RMLabel2", array() , $data);
				 
			}

			public function updateFeatures(){
				
				$param = $this->input->post();
				$pack_type = $param['pack_type'];
				$pack_size = $param['pack_size'];
				$itemkey = $param['itemkey'];
				$LotNo = $param['LotNo'];
				$LocationKey = $param['LocationKey'];

				$this->ndb->trans_begin();
				$toinsert = [
					"LotNo" => $LotNo,
					"ItemKey" => $itemkey,
					"LocationKey" => $LocationKey,
					"FeatureId" => "PACKUNIT",
					"FeatureValue" => $pack_type,
					"RecUserId" => $this->userinfo['uname'],
					"RecDate" => date("Y-m-d H:i:s"),
				];

				$this->models->Add('LotFeaturesValue',$toinsert);

				$toinsert = [
					"LotNo" => $LotNo,
					"ItemKey" => $itemkey,
					"LocationKey" => $LocationKey,
					"FeatureId" => "BAGSIZE",
					"FeatureValue" => $pack_size,
					"RecUserId" => $this->userinfo['uname'],
					"RecDate" => date("Y-m-d H:i:s"),
				];

				$this->models->Add('LotFeaturesValue',$toinsert);

				
				if ($this->ndb->trans_status() === FALSE)
				{
					$msg = [
						"msgno" => 999,
						"msg" => "Cannot Proccess,Please Contact Administrator"
					];

					echo json_encode($msg);
					$this->ndb->trans_rollback();
					die();
					
				}
				else
				{
					$this->ndb->trans_commit();

						$msg = [
							"msgno" => 900,
							"msg" => "Successfully Updated"
						];

						echo json_encode($msg);
						die();
				}
				
				
			}

			public function findreceiptdoc(){
				$param = $this->input->post();
				$data = [];
				
				
				if($param['ReceiptDocNo']){

					$xdata = $this->ndb
						->select("
							Lt.ItemKey,
							IM.Desc1,
							LT.LotNo,
							LT.Vendorkey,
							LT.VendorlotNo,
							LT.QtyReceived,
							LT.DateReceived,
							LT.DateExpiry,
							LT.ReceiptDocNo,
							LVTokey.FeatureValue as ToKey, 
							LV.FeatureValue as Convfctr, 
							IPD.PropDesc,
							LT.LotTranNo
						")
						->join('INMAST IM','IM.Itemkey = LT.ItemKey')
						->join('INMSPEC IP','IP.ItemKey = LT.ItemKey and IP.PropValue > 0 and IP.PropNumber < 16','left')
						->join('INMSPECD IPD','IPD.PropNumber = IP.PropNumber','left')
						//->join('INQTYCNV IQ','IQ.UMItemKey = LT.ItemKey','left')
						->join('LotFeaturesValue LV','LV.ItemKey = LT.ItemKey and LV.LotNo = LT.LotNo and FeatureId = \'BAGSIZE\' ','left')
						->join('LotFeaturesValue LVTokey','LV.ItemKey = LT.ItemKey and LV.LotNo = LT.LotNo and FeatureId = \'PACKUNIT\' ','left')
						->where([
							"LT.TransactionType"=>"1",
							"LT.ReceiptDocNo" => $this->ndb->escape($param['ReceiptDocNo'])
						])->get('QCLotTransaction LT')->result();
						
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
				
				if($param['itemkey'] && $param['lotno']){

					$xdata = $this->ndb
						->select("
							concat(LT.ItemKey,LT.LotNo) as LotTranNo,
							Lt.ItemKey,
							IM.Desc1,
							LT.LotNo,
							LT.Vendorkey,
							LT.VendorlotNo,
							LT.QtyReceived,
							LT.DateReceived,
							LT.DateExpiry,
							LT.ReceiptDocNo,
							LVTokey.FeatureValue as ToKey, 
							LV.FeatureValue as Convfctr, 
							LT.LocationKey,
							IPD.PropDesc
						")
						->join('INMAST IM','IM.Itemkey = LT.ItemKey')
						->join('INMSPEC IP','IP.ItemKey = LT.ItemKey and IP.PropValue > 0 and IP.PropNumber < 16','left')
						->join('INMSPECD IPD','IPD.PropNumber = IP.PropNumber','left')
						#->join('INQTYCNV IQ','IQ.UMItemKey = LT.ItemKey','left')
						->join('LotFeaturesValue LV','LV.ItemKey = LT.ItemKey and LV.LotNo = LT.LotNo and LV.FeatureId = \'BAGSIZE\' ','left')
						->join('LotFeaturesValue LVTokey','LVTokey.ItemKey = LT.ItemKey and LVTokey.LotNo = LT.LotNo and LVTokey.FeatureId = \'PACKUNIT\' ','left')
						->where([
							"LT.TransactionType"=>"1",
							"LT.LotNo" => $this->ndb->escape($param['lotno']),
							"LT.ItemKey" => $this->ndb->escape($param['itemkey']),
						])->get('QCLotTransaction LT')->result();
						
						
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
				 $pLotTranNo = $this->input->get('lotno');
				 $pItemKey = $this->input->get('ItemKey');

				$xdata = $this->ndb
						->select("
							concat(LT.ItemKey,LT.LotNo) as LotTranNo,
							Lt.ItemKey,
							IM.Desc1,
							LT.LotNo,
							LT.Vendorkey,
							LT.VendorlotNo,
							LT.QtyReceived,
							LT.DateReceived,
							LT.DateExpiry,
							LT.ReceiptDocNo,
							LVTokey.FeatureValue as ToKey, 
							LV.FeatureValue as Convfctr, 
							IPD.PropDesc
						")
						->join('INMAST IM','IM.Itemkey = LT.ItemKey')
						->join('INMSPEC IP','IP.ItemKey = LT.ItemKey and IP.PropValue > 0 and IP.PropNumber < 16','left')
						->join('INMSPECD IPD','IPD.PropNumber = IP.PropNumber','left')
						#->join('INQTYCNV IQ','IQ.UMItemKey = LT.ItemKey','left')
						->join('LotFeaturesValue LV','LV.ItemKey = LT.ItemKey and LV.LotNo = LT.LotNo and LV.FeatureId = \'BAGSIZE\' ','left')
						->join('LotFeaturesValue LVTokey','LVTokey.ItemKey = LT.ItemKey and LVTokey.LotNo = LT.LotNo and LVTokey.FeatureId = \'PACKUNIT\' ','left')
						->where([
							"LT.TransactionType"=>"1",
							"LT.LotNo" => $this->ndb->escape($pLotTranNo),
							"LT.ItemKey" => $this->ndb->escape($pItemKey)
						])->get('QCLotTransaction LT')->result();

					
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
						$html .= "<h4 class='text-left' style='margin:10px 0px 0px 0px;'>$vdata->Desc1</h4>";
						$html .= "<table style='margin:10px 0px 0px 0px;font-size:19px'>";
						$html .= "<col width=100></col> 
									<col width=250></col> ";
							$html .= "<tr>";
								$html .= "<td><b>".$vdata->ItemKey."</b></td>";
								$html .= "<td><b>$vdata->LotNo</b></td>";
							$html .= "</tr>";
							
							$html .= "<tr>";
								$html .= "<td style='font-size:19px;'><h2>Allergen:</h2></td><td><h1 style='margin:0px 0px 0px 0px;'>&nbsp;".$vdata->PropDesc."</h1></td>";
							$html .= "</tr>";
						$html .= "</table>";
						$html .= "<table style='margin:0px 0px 0px 0px;font-size:15px'>";
						$html .= "<col width=200></col> 
								<col width=180></col> ";

						$html .= "<tr>";
							$html .= "<td colspan=2 ><b >Pack Size:</b>&nbsp;".number_format(($vdata->Convfctr ?? 0),2)."&nbsp;$vdata->ToKey</td>";
						$html .= "</tr>";
						
						$html .= "<tr >";
							$html .= "<td ><b style='margin:5px 0px 0px 0px;'>Vendor:</b>&nbsp;$vdata->Vendorkey</td>";
							$html .= "<td><b style='margin:5px 0px 0px 0px;'>BIN:</b>&nbsp;</td>";
						$html .= "</tr>";
						
						$html .= "<tr>";
							$html .= "<td><b style='margin:5px 0px 0px 0px;'>Vendor Lot:</b>&nbsp;".$vdata->VendorlotNo."</td>";
							$html .= "<td><b style='margin:5px 0px 0px 0px;'>QTY:</b>&nbsp;".number_format($vdata->QtyReceived,2)."</td>";
						$html .= "</tr>";

						$html .= "<tr>";
							$html .= "<td><b style='margin:5px 0px 0px 0px;'>Received:</b>&nbsp;".date("d-M-Y",strtotime($vdata->DateReceived))."</td>";
							$html .= "<td><b style='margin:5px 0px 0px 0px;'>Expiry:</b>&nbsp;".date("d-M-Y",strtotime($vdata->DateExpiry))."</td>";
						$html .= "</tr>";
						$html .= "<tr>";
								$html .= '<td colspan=2><barcode dimension="1D" type="C128" value="(02)'.$vdata->ItemKey.'(10)'.$vdata->LotNo.'" label="label" style="margin:5px 0px 0px 0px; width:95mm; height:15mm;  font-size: 4mm"></barcode></td>';
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

			public function printDemo(){
				ob_start();
				$bme=$this->load->database("nwfth2",true);
				 $xcount = $this->input->get('count');
				 $pLotTranNo = $this->input->get('lotno');
				 $pItemKey = $this->input->get('ItemKey');



				$xdata = $this->ndb
						->select("
							concat(LT.ItemKey,LT.LotNo) as LotTranNo,
							Lt.ItemKey,
							IM.Desc1,
							LT.LotNo,
							LT.Vendorkey,
							LT.VendorlotNo,
							LT.QtyReceived,
							LT.DateReceived,
							LT.DateExpiry,
							LT.ReceiptDocNo,
							LVTokey.FeatureValue as ToKey, 
							LV.FeatureValue as Convfctr, 
							IPD.PropDesc
						")
						->join('INMAST IM','IM.Itemkey = LT.ItemKey')
						->join('INMSPEC IP','IP.ItemKey = LT.ItemKey and IP.PropValue > 0 and IP.PropNumber < 16','left')
						->join('INMSPECD IPD','IPD.PropNumber = IP.PropNumber','left')
						#->join('INQTYCNV IQ','IQ.UMItemKey = LT.ItemKey','left')
						->join('LotFeaturesValue LV','LV.ItemKey = LT.ItemKey and LV.LotNo = LT.LotNo and LV.FeatureId = \'BAGSIZE\' ','left')
						->join('LotFeaturesValue LVTokey','LVTokey.ItemKey = LT.ItemKey and LVTokey.LotNo = LT.LotNo and LVTokey.FeatureId = \'PACKUNIT\' ','left')
						->where([
							"LT.TransactionType"=>"8",
							"LT.LotNo" => $this->ndb->escape($pLotTranNo),
							"LT.ItemKey" => $this->ndb->escape($pItemKey)
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
						$html .= "<h4 class='text-left' style='margin:10px 0px 0px 0px;'>$vdata->Desc1</h4>";
						$html .= "<table style='margin:10px 0px 0px 0px;font-size:19px'>";
						$html .= "<col width=100></col> 
									<col width=250></col> ";
							$html .= "<tr>";
								$html .= "<td><b>".$vdata->ItemKey."</b></td>";
								$html .= "<td><b>$vdata->LotNo</b></td>";
							$html .= "</tr>";
							$html .= "<tr>";
								$html .= '<td colspan=2><barcode dimension="1D" type="C128" value="(02)'.$vdata->ItemKey.'(10)'.$vdata->LotNo.'" label="label" style="margin:5px 0px 0px 0px; width:95mm; height:15mm;  font-size: 4mm"></barcode></td>';
							$html .= "</tr>";
							$html .= "<tr>";
								$html .= "<td style='font-size:19px;'><h2>Allergen:</h2></td><td><h1 style='margin:0px 0px 0px 0px;'>&nbsp;".$vdata->PropDesc."</h1></td>";
							$html .= "</tr>";
						$html .= "</table>";
						$html .= "<table style='margin:0px 0px 0px 0px;font-size:15px'>";
						$html .= "<col width=200></col> 
								<col width=180></col> ";

						$html .= "<tr>";
							$html .= "<td colspan=2 ><b >Pack Size:</b>&nbsp;".number_format(($vdata->Convfctr ?? 0),2)."&nbsp;$vdata->ToKey</td>";
						$html .= "</tr>";
						
						$html .= "<tr >";
							$html .= "<td ><b style='margin:5px 0px 0px 0px;'>Vendor:</b>&nbsp;$vdata->Vendorkey</td>";
							$html .= "<td><b style='margin:5px 0px 0px 0px;'>BIN:</b>&nbsp;</td>";
						$html .= "</tr>";
						
						$html .= "<tr>";
							$html .= "<td><b style='margin:5px 0px 0px 0px;'>Vendor Lot:</b>&nbsp;".$vdata->VendorlotNo."</td>";
							$html .= "<td><b style='margin:5px 0px 0px 0px;'>QTY:</b>&nbsp;".number_format($vdata->QtyReceived,2)."</td>";
						$html .= "</tr>";

						$html .= "<tr>";
							$html .= "<td><b style='margin:5px 0px 0px 0px;'>Received:</b>&nbsp;".date("d-M-Y",strtotime($vdata->DateReceived))."</td>";
							$html .= "<td><b style='margin:5px 0px 0px 0px;'>Expiry:</b>&nbsp;".date("d-M-Y",strtotime($vdata->DateExpiry))."</td>";
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