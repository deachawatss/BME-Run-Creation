<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: *");
defined('BASEPATH') OR exit('No direct script access allowed');


require(APPPATH.'/libraries/RestController.php');
require(APPPATH.'/libraries/Format.php');
use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;

class Putaway extends RestController
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
                                        ->where("BinNo !='' and BinNo !='C-Bulk' and BinNo !='A-PREWEIGH'")
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

        $totalavailable = floatval($lotdetails->QtyOnHand) - $lotdetails->QtyCommitSales;

        if($totalavailable < $putaway ){
            $msg = [
                "msgno" => 1050,
                "msg" => "Item Cannot be Transfer, Item is allocated! Only ".$totalavailable." is allowed to trasnfer"
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
            'FG' => '100040',
            'RM' => '100050',
            'INT' => '100065',
            'PM' => '100060',
            'RND' => '100050'
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
                "VendorKey" => $lotdetails->VendorKey,
                "VendorLotNo" => $lotdetails->VendorLotNo,
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
}
