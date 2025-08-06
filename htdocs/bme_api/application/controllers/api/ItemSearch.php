<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: *");
defined('BASEPATH') OR exit('No direct script access allowed');


require(APPPATH.'/libraries/RestController.php');
require(APPPATH.'/libraries/Format.php');
use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;

class ItemSearch extends RestController
{
    var $ndb;
    var $bme;
    var $has_enter_by;
    var $has_created_at;
    var $has_updated_by;
    var $has_updated_date;
    var $enableLogs = true;
    var $debug = true;
	public function __construct() {
        parent::__construct();
        $this->load->helper("barcode");
        
        $this->has_enter_by = false;
        $this->has_created_at = false;
        $this->has_updated_by = false;
        $this->has_updated_date = false;
       # $this->ndb = $this->load->database('default',true);
       # $this->bme = $this->load->database('nwfth',true);
       if($this->debug != true){
        $param = $this->input->post();
        if(isset($param['uname']) && isset($param['nwfth-session']) && $param['uname'] != '' && $param['nwfth-session'] != ''){
            #$mdb = $this->load->database('default',true);
            $msession = $this->db
                                ->where('uname',$param['uname'])
                                ->where('sessionid',$param['nwfth-session'])
                                ->get('tbl_mobile_session')
                                ->row();

            if($msession == false){
                $msg['msg'] = "Invalid Credential";
                $msg['msgno'] = -100;
                $this->response($msg, 200);
            }
            
        }else{
            $msg['msg'] = "Invalid Credential";
            $msg['msgno'] = -100;
            $this->response($msg, 200);
        }
            
       }

       $this->ndb = $this->load->database('nwfth',true);

	}

    public function findlot_post(){
        $bme = $this->load->database('nwfth',true);
        $param = $this->input->post();
		$msg = [];
        $bcode = [];

        
        $LotWhere = ["QtyOnHand >"=>"0"];
        
        switch($param['searchType']){

            case 'prodcode':{

                if(isset($param['prodcode'])){
                    $prodcode = $param['prodcode'];
                    $bcode = getBarcodeData($prodcode);
                }

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

                break;
            }

            case 'itemkey':{

                if(isset($param['itemkey'])){
                    $LotWhere['INMAST.ItemKey'] = $bme->escape($param['itemkey']);
                }

                break;
            }

            case 'lot':{

                if(isset($param['lotno'])){
                    $LotWhere['LotNo'] = $bme->escape($param['lotno']);
                }

                break;
            }

            case 'bin':{

                if(isset($param['binno'])){
                    $LotWhere['LotMaster.BinNo'] = $bme->escape($param['binno']);
                }

                break;
            }

        }

      
        if($LotWhere != ''){

           # $this->response($LotWhere, 200);

            $lotdetails = $bme->select("
                                        LotNo,
                                        BinNo,
                                        QtyOnHand,
                                        FORMAT (DateReceived, 'MM-dd-yyyy') as DateReceived,
                                        FORMAT (DateExpiry, 'MM-dd-yyyy') as DateExpiry,
                                        LocationKey,
                                        INMAST.ItemKey,
                                        Stockuomcode,
                                        QtyCommitSales,
                                        INMAST.Desc1
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

    public function itemsummary_post(){
        $param = $this->input->post();
        $bme = $this->load->database('nwfth',true);
        
        if(isset($param['itemkey']) && $param['itemkey'] != ''){
            $iteminfo = $bme->select('INMAST.ItemKey,INMAST.Desc1,Stockuomcode')
                            ->where("INMAST.Itemkey",$bme->escape($param['itemkey']))
                            ->get('INMAST')
                            ->row();
            $lotdetails_data = $bme->select("
                                            LotNo,
                                            BinNo,
                                            QtyOnHand,
                                            FORMAT (DateReceived, 'MM-dd-yyyy') as DateReceived,
                                            FORMAT (DateExpiry, 'MM-dd-yyyy') as DateExpiry,
                                            LocationKey,
                                            INMAST.ItemKey,
                                            Stockuomcode,
                                            QtyCommitSales,
                                            INMAST.Desc1
                                            ")
                                            ->where("INMAST.Itemkey",$bme->escape($param['itemkey']))
                                            ->where("BinNo !='' ")
                                            ->join('INMAST','INMAST.Itemkey = LotMaster.ItemKey','inner')
                                            ->get('LotMaster')
                                            ->result();

            $po_data = $bme->select("
                                            POLIN.Pono,
                                            POLIN.Qtyord,
                                            POLIN.Qtyremn,
                                            FORMAT(POLIN.Reqdate, 'MM-dd-yyyy') as RequireDate,
                                            FORMAT(POLIN.Recdate, 'MM-dd-yyyy') as PODate,
                                            INMAST.ItemKey,
                                            POLIN.uom
                                            ")
                                            ->where("INMAST.Itemkey",$bme->escape($param['itemkey']))
                                            ->where("POLIN.Qtyremn >",0)
                                            ->where("POHDR.statusflg in ('OPEN','NEW')")
                                            ->where("POLIN.Pono not like 'RP%' ")
                                            ->join('INMAST','INMAST.Itemkey = POLIN.ItemKey','left')
                                            ->join('PoHDR','PoHDR.Pono = POLIN.Pono')
                                            ->get('POLIN')
                                            ->result();
                                            
            $msg['itemkey'] = $iteminfo->ItemKey;
            $msg['uom'] = $iteminfo->Stockuomcode;
            $msg['description']  = $iteminfo->Desc1;
            $msg['onhand'] = $lotdetails_data;
            $msg['podata'] = $po_data;
            $msg['msg']= '';
            $msg['msgno'] = 1;
        }else{
            $msg['results'] = [];
            $msg['msgno'] = 0;
            $msg['msg'] = "No record available.";
            
        }
       
        $this->response($msg, 200);


    }

    public function binsummary_post(){
        $bme = $this->load->database('nwfth',true);
        $param = $this->input->post();
		$msg = [];
        $bcode = [];

        if(isset($param['BinNo']) && $param['BinNo'] != ""){
            $LotWhere = [];
            $BinWhere = ["BinNo" => $bme->escape($param['BinNo'])];
            $LotWhere['QtyOnHand >'] = 0;
            $LotWhere['BinNo'] = $bme->escape($param['BinNo']);
            
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

                 $BinDetails = $bme->select("BinNo,Description")
                                            ->where($BinWhere)
										    ->get('BINMaster')
											->row();
                
                $msg['itemdetails'] = $lotdetails;	
                $msg['BinNo'] = $BinDetails->BinNo;	
                $msg['Description'] = $BinDetails->Description;	
                $msg['msgno'] = 1;
                $msg['msg'] = "";
            }else{
                $msg['results'] = [];
                $msg['msgno'] = 0;
                $msg['msg'] = "No record available.";
                
            }

            
        }else{
            $msg['results'] = [];
            $msg['norecord'] = 0;
            $msg['msg'] = "Please specify Bin No.";
            
        }
       
		
        $this->response($msg, 200);
    }
}
