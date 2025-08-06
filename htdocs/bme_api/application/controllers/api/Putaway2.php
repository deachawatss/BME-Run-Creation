<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: *");
defined('BASEPATH') OR exit('No direct script access allowed');


require(APPPATH.'/libraries/RestController.php');
require(APPPATH.'/libraries/Format.php');
use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;

class Putaway2 extends RestController
{
    var $ndb;
    var $bme;
    var $has_enter_by;
    var $has_created_at;
    var $has_updated_by;
    var $has_updated_date;
    var $enableLogs = true;
	public function __construct() {
        parent::__construct();
        $this->load->helper("barcode");
        $this->ndb = $this->load->database('nwfth',true);
        $this->load->helper("msg");
        $this->has_enter_by = false;
        $this->has_created_at = false;
        $this->has_updated_by = false;
        $this->has_updated_date = false;
       # $this->ndb = $this->load->database('default',true);
       # $this->bme = $this->load->database('nwfth',true);
	}

    public function findlot_post(){
        $bme = $this->load->database('nwfth',true);
        $param = $this->input->post();
		$msg = [];
        $bcode = [];
        if(isset($param['prodcode'])){
            $prodcode = $param['prodcode'];
            $bcode = getBarcodeData($prodcode);
        }
       
		#$LotWhere = ["1"=>"1"];
		$LotWhere = ["QtyOnHand >"=>"0"];

        if(isset($param['prodcode']) && $param['prodcode'] != ''){
            
            if( isset($bcode['02'])){
                $LotWhere['INMAST.ItemKey'] = $bme->escape($bcode['02']);
            }else{
                $msg['results'] = [];
                $msg['norecord'] = 0;
                $msg['msg'] = "Invalid input";
                $this->response($msg, 200);
                die();
            }

            if(isset($bcode['10'])){
                $LotWhere['LotNo'] = $bme->escape($bcode['10']);
            }else{
                $msg['results'] = [];
                $msg['norecord'] = 0;
                $msg['msg'] = "Invalid input";
                $this->response($msg, 200);
                die();
            }

        }
		

        if($LotWhere != ''){
            $lotdetails = $bme->select("
                                        LotNo,
                                        BinNo,
                                        QtyOnHand,
                                        FORMAT (DateReceived, 'MM-dd-yyyy') as DateReceived,
                                        FORMAT (DateExpiry, 'MM-dd-yyyy') as DateExpiry,
                                        LocationKey,
                                        INMAST.ItemKey,
                                        Stockuomcode,
                                        QtyCommitSales
                                        ")->where($LotWhere)
                                        ->where("BinNo !='' ")
                                        ->join('INMAST','INMAST.Itemkey = LotMaster.ItemKey','inner')
                                        ->get('LotMaster')
                                        ->result();
            
            $msg['results'] = $lotdetails;	
            $msg['norecord'] = count($lotdetails);
            $msg['msg'] = "";
        }else{
            $msg['results'] = [];
            $msg['norecord'] = 0;
            $msg['msg'] = "No record available.";
            
        }

        $this->response($msg, 200);
    }

    public function findBin_post(){

        $bme = $this->load->database('nwfth',true);

		$param = $this->input->post();
		$msg = [];

		if($param['BinNo'] == ''){
			$BinWhere = '1=1';
		}else{
			$BinWhere = ['BinNo' => $bme->escape($param['BinNo'])];
		}

		if($BinWhere != ''){
		    $BinDetails = $bme->select("
										BinNo,
										Description
										")->where($BinWhere)
										    ->get('BINMaster')
											->result();
			$msg['results'] = $BinDetails;	
            $msg['norecord'] = count($BinDetails);
		}else{
            $msg['results'] = [];
            $msg['norecord'] = 0;
        }

        $this->response($msg, 200);
    }

    public function sendputaway_post(){
        $bme = $this->load->database('nwfth',true);
        $param = $this->input->post();
        $uname = "";
        if(isset($param['uname'])){
            $uname = $param['uname'];
            unset($param['uname']);
        }
        
        $this->load->model("Default_model_api","models");
        $lotno = $param['lotno'];
        $putaway = $param['putaway'];
        $tobin = $param['tobin'];
        $itemkey = $param['itemkey'];
        $bin = $param['bin'];

        if($lotno == '' || $putaway == '' || $tobin == '' || $itemkey	== '' || $bin == ''){
       # if($lotno == '' || $putaway == '' || $tobin == '' || $itemkey	== '' ){
            $msg = [
                "msgno" => 1000,
                "msg" => "Enter Valid input"
            ];

            $this->response($msg, 200);
            die();
        }

        if($tobin == $bin){
            $msg = [
                "msgno" => 10010,
                "msg" => "Product is already in new BIN!"
            ];

            $this->response($msg, 200);
            die();
        }
        

        #Check if Lot Record Exist
            $lotdetails = $bme
                                ->select("LotMaster.*,INLOC.inclasskey")
                                ->where("LotMaster.LotNo",$bme->escape($lotno))
                                ->where("LotMaster.BinNo",$bme->escape($bin))
                                ->where("LotMaster.Itemkey",$bme->escape($itemkey))
                                ->join("INLOC","INLOC.itemkey = LotMaster.Itemkey and INLOC.Location = LotMaster.LocationKey","left")
                                ->get('LotMaster')
                                ->row();

        #Check if Lot Record Exist
        $lotexist = $bme
        ->select("LotMaster.*,INLOC.inclasskey")
        ->where("LotMaster.LotNo",$bme->escape($lotno))
        ->where("LotMaster.BinNo",$bme->escape($tobin))
        ->where("LotMaster.Itemkey",$bme->escape($itemkey))
        ->join("INLOC","INLOC.itemkey = LotMaster.Itemkey and INLOC.Location = LotMaster.LocationKey","left")
        ->get('LotMaster')
        ->row();
    
        if(!$lotdetails){
            $msg = [
                "msgno" => 1001,
                "msg" => "Item Cannot be found!"
            ];

            echo json_encode($msg);
            die();
        }
        
        if(!$lotdetails){
                $msg = [
                    "msgno" => 1001,
                    "msg" => "Item Cannot be found!"
                ];

                $this->response($msg, 200);
                die();
        }

        if($lotdetails->QtyCommitSales > 0 ){
            $msg = [
                "msgno" => 1050,
                "msg" => "Item Cannot be Transfer, Item is allocated!"
            ];

            $this->response($msg, 200);
            die();
        }
        #Check put Away
            if($putaway <= 0) {
                    $msg = [
                        "qty" => $putaway,
                        "msgno" => 1001,
                        "msg" => "Invalid Putaway Quantity!"
                    ];

                    $this->response($msg, 200);
                    die();
            }

            if($putaway > $lotdetails->QtyOnHand){

                    $msg = [
                        "msgno" => 1002,
                        "msg" => "Invalid Putaway Quantity!"
                    ];

                    $this->response($msg, 200);
                    die();
            }

        #Check Bin
        
            $BinDetails = $bme->select("BinNo,Description")->where("BinNo",$bme->escape($bin))->get('BINMaster')->row();
            if(!$BinDetails){
                    $msg = [
                        "msgno" => 1003,
                        "msg" => "No Bin Record for the Item!"
                    ];

                    $this->response($msg, 200);
                    die(); 
            } 

        #Check newBin
        
            $BinDetails = $bme->select("BinNo,Description")->where("BinNo",$bme->escape($tobin))->get('BINMaster')->row();
             if(!$BinDetails){
                 $msg = [
                     "msgno" => 1003,
                     "msg" => "New Bin does not exist!"
                 ];
 
                 $this->response($msg, 200);
                 die();
            }
        

        $data = [];
        $Seqnum = $bme->where("SeqName",$bme->escape("BT"))->get("SeqNum")->row()->SeqNum + 1;
        $udate = date("Y-m-d H:i:s");
        $inclasskey = [
            'FG' => '100050',
            'RM' => '100040',
            'INT' => '100065',
            'PM' => '100060',
        ];
        $this->ndb->trans_begin();
        $nMintxdh = [
            "ItemKey" => $itemkey,
            "Location" => $lotdetails->LocationKey,
            "SysID" => "7", #
            "ProcessID" => "M", #
            "SysDocID" => "BT-".$Seqnum,
            "SysLinSq" => "1",#Line Sequence
            "TrnTyp" => "A",
            "DocNo" => "BT-".$Seqnum,
            "DocDate" => $udate,
            "AplDate" => $udate,
            "TrnDesc" => "Bin Transfer",
            "NLAcct" => "100040",#10040
            "INAcct" => $inclasskey[$lotdetails->inclasskey],#
            "CreatedSerLot" => "Y", #
            "RecUserID" => $uname, # USER
            "RecDate" => $udate,
            "Updated_FinTable" => 0
        ];

        $Mintxdh = $this->models->Add("Mintxdh",$nMintxdh);
        
        $uSeqnum = ["SeqNum" => ($Seqnum + 1)];

        $this->models->Update("Seqnum",$uSeqnum,"SeqName='BT'");
        

        if($lotexist){
            #Update Data

            if($lotdetails->QtyOnHand == $putaway){
                #Update whole data
                $LotMaster = [
                    "DocumentNo" => "BT-".($Seqnum + 1),
                    "DocumentLineNo" => "1",
                    "TransactionType" => "8",
                    "RecUserId" => $uname,#USER
                    "Recdate" => $udate,
                    "BinNo" => $tobin,
                    "QtyOnHand" => ($lotexist->QtyOnHand + $putaway)
                ];

                $this->models->Update("LotMaster",$LotMaster,["LotNo" => $bme->escape($lotno),"BinNo" => $bme->escape($tobin),"Itemkey" => $bme->escape($itemkey)]);

                $uLotMaster = [
                    "QtyOnHand" => 0,
                ];

                $this->models->Update("LotMaster",$uLotMaster,["LotNo" => $bme->escape($lotno),"BinNo" => $bme->escape($bin),"Itemkey" => $bme->escape($itemkey)]);

            }else{
                #Update recieving and sender data
                $LotMaster_from = [
                    "QtyOnHand" => ($lotdetails->QtyOnHand - $putaway),
                ];

                $LotMaster_to = [
                    "DocumentNo" => "BT-".($Seqnum + 1),
                    "DocumentLineNo" => "1",
                    "TransactionType" => "8",
                    "RecUserId" => $uname,#USER
                    "Recdate" => $udate,
                    "BinNo" => $tobin,
                    "QtyOnHand" => ($lotexist->QtyOnHand + $putaway)
                ];

                if($tobin != $bin){
                    $this->models->Update("LotMaster",$LotMaster_to,["LotNo" => $bme->escape($lotno),"BinNo" => $bme->escape($tobin),"Itemkey" => $bme->escape($itemkey)]);
                    $this->models->Update("LotMaster",$LotMaster_from,["LotNo" => $bme->escape($lotno),"BinNo" => $bme->escape($bin),"Itemkey" => $bme->escape($itemkey)]);
                }
            }

        }else{
            #Add Data

            if($lotdetails->QtyOnHand == $putaway){
                #Update whole data
                $LotMaster = [
                    "DocumentNo" => "BT-".($Seqnum + 1),
                    "DocumentLineNo" => "1",
                    "TransactionType" => "8",
                    "RecUserId" => $uname,#USER
                    "Recdate" => $udate,
                    "BinNo" => $tobin
                ];

                $this->models->Update("LotMaster",$LotMaster,["LotNo" => $bme->escape($lotno),"BinNo" => $bme->escape($bin),"Itemkey" => $bme->escape($itemkey)]);

            }else{
                #Update Lot Master
                $uLotMaster = [
                    "QtyOnHand" => ($lotdetails->QtyOnHand - $putaway),
                ];

                $this->models->Update("LotMaster",$uLotMaster,["LotNo" => $bme->escape($lotno),"BinNo" => $bme->escape($bin),"Itemkey" => $bme->escape($itemkey)]);

                #Add New Lot
                $nLotMaster = [
                    "LotNo" => $lotdetails->LotNo,
                    "ItemKey" => $lotdetails->ItemKey,
                    "LocationKey" => $lotdetails->LocationKey,
                    "DateReceived" => $lotdetails->DateReceived,
                    "DateExpiry" => $lotdetails->DateExpiry,
                    "LotStatus" => $lotdetails->LotStatus,
                    "QtyReceived" => $putaway,
                    "QtyOnHand" =>  $putaway,
                    "DocumentNo" => "BT-".($Seqnum + 1),
                    "DocumentLineNo" => "1",
                    "TransactionType" => "8",
                    "RecUserId" => $uname,#USER
                    "Recdate" => $udate,
                    "BinNo" => $tobin,
                    "User5" => $lotdetails->User5
                ];

                $this->models->Add("LotMaster",$nLotMaster);
            }


        }

        #Add Bin
            $nBin = [
                "ItemKey" => $lotdetails->ItemKey,
                "Location" => $lotdetails->LocationKey,
                "LotNo" => $lotdetails->LotNo,
                "BinNoFrom" => $lotdetails->BinNo,
                "BinNoTo" => $tobin,
                "LotTranNo" => $Mintxdh,
                "QtyOnHand" => $lotdetails->QtyOnHand,
                "TransferQty" => $putaway,
                "InTransID" => 0,
                "RecUserID" => $uname,#USER
                "RecDate"=> $udate,
            ];
        
            $this->models->Add("BinTransfer",$nBin);

        #Add LotTransaction
            $nLotTransaction_o = [
                "LotNo" => $lotdetails->LotNo,
                "ItemKey" => $lotdetails->ItemKey,
                "LocationKey" => $lotdetails->LocationKey,
                "DateReceived" => $lotdetails->DateReceived,
                "DateExpiry" => $lotdetails->DateExpiry,
                "RecUserid" => $uname, #
                "RecDate" => $udate,
                "Processed" => "Y",
                "TransactionType" => 9,
                "QtyReceived" => 0,
                "IssueDocNo" => "BT-".($Seqnum + 1),
                "IssueDocLineNo" => 1,
                "IssueDate" => $udate,
                "QtyIssued" => $putaway,
                "BinNo" => $lotdetails->BinNo
            ];

            $nLotTransaction_i = [
                "LotNo" => $lotdetails->LotNo,
                "ItemKey" => $lotdetails->ItemKey,
                "LocationKey" => $lotdetails->LocationKey,
                "DateReceived" => $lotdetails->DateReceived,
                "DateExpiry" => $lotdetails->DateExpiry,
                "VendorKey" => $lotdetails->VendorKey,
                "VendorLotNo" => $lotdetails->VendorLotNo,
                "RecUserid" => $uname, #
                "RecDate" => $udate,
                "Processed" => "Y",
                "TransactionType" => 8,
                "ReceiptDocNo"=> "BT-".($Seqnum + 1) ,
                "ReceiptDocLineNo" => 1,
                "QtyReceived" => $putaway,
                "CustomerKey" => "R",
                "BinNo" => $tobin
            ];

            $this->models->Add("LotTransaction",$nLotTransaction_o);
            $this->models->Add("LotTransaction",$nLotTransaction_i);

            #$this->ndb->trans_rollback();
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
                        "msg" => "Successfully transferred"
                    ];

                    echo json_encode($msg);
                    die();
            }

    }

    private function checkItem($bin){
        $bme = $this->load->database('nwfth_test',true);
        
        $msg = [];
        $msg['msgno'] = 0;

        $mdata = $bme
                    ->select("via.allergen")
                    ->where("BinNo",$bme->escape($bin))
                    ->where("QtyOnHand >",$bme->escape(0))
                    ->join("v_item_allergen via","via.ItemKey = LotMaster.ItemKey")
                    ->get('LotMaster')->row();

        if($mdata){
            $msg['allergen']= ($mdata->allergen ?? "") ;
            $msg['msgno'] = 1;

        } 

        
        return $msg;
    }

    public function checkBin_post(){#$bin,$itemkey
        $bme = $this->load->database('nwfth_test',true);
        $param = $this->input->post();

        $bin = $param['binno'];
        $itemkey = $param['itemkey'];
        $msg = [];

        if($bin != "" && $itemkey != ""){
            
            $BinInfo = $bme->where("BinNo",$bme->escape($bin))->get("BINMaster")->row();

            if($BinInfo){

                #Is Rack
                if($BinInfo->User5 == 'Y'){

                    $ItemkeyInfo = $bme->where("ItemKey",$bme->escape($itemkey))->get("v_item_allergen")->row();
                
                    if($ItemkeyInfo){

                        #check Level 1
                        $rl1 = $this->checkItem($BinInfo->User1);

                        #check Level 2
                        $rl2 = $this->checkItem($BinInfo->User2);

                        #check Level 3
                        $rl3 = $this->checkItem($BinInfo->User3);

                        #check Level 4
                        $rl4 = $this->checkItem($BinInfo->User4);

                        $msg["data"]=[
                            "L1" => $rl1,
                            "L2" => $rl2,
                            "L3" => $rl3,
                            "L4" => $rl4,
                        ];

                        
                        if( ($rl1['msgno']==0 ) && $rl2['msgno']==0 && $rl3['msgno']==0 && $rl4['msgno']==0 ){

                        }else{
                            switch($bin){

                                case $BinInfo->User1:{
                                    
                                    /*
                                        Validation for bin
                                    */
                                    
                                    if($rl1['msgno'] != 0){
                                        
                                        if($bin == $BinInfo->User1){

                                        }

                                    }

                                    if($rl2['msgno'] != 0){

                                    }

                                    if($rl3['msgno'] != 0){

                                    }

                                    if($rl4['msgno'] != 0){

                                    }

                                    
                                    break;
                                }
    
                                case $BinInfo->User2:{
    
                                    break;
                                }
    
                                case $BinInfo->User3:{
    
                                    break;
                                }
    
                                case $BinInfo->User4:{
    
                                    break;
                                }
    
                            }
                        }

                        

                    }else{
                        $msg['msg'] = return_msg("107");
                        $msg['msgno'] = 107;
                    }

                }else{

                    $msg['msgno'] = 200;
                }

                
            }else{
                $msg['msg'] = return_msg("103");
                $msg['msgno'] = 103;
                
            }

        }else{
            $msg['msg'] = return_msg("101");
            $msg['msgno'] = 101;
        }

        $this->response($msg, 200);
        
    }

}
