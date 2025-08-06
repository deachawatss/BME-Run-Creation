<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: *");
defined('BASEPATH') OR exit('No direct script access allowed');


require(APPPATH.'/libraries/RestController.php');
require(APPPATH.'/libraries/Format.php');
use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;

class Partialpick extends RestController
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
       $this->load->helper('msg');
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

    public function findrun_post(){
        $bme = $this->load->database('nwfth_test',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();
		$msg = [];
        $bcode = [];
        $runWhere = '1 = 1';
        if( isset($param['runno']) )
            $runWhere = ["runno >"=>"0"];
        
        
        if($runWhere != ''){

            $nwfpdetails = $nwfp->select("
                                        tbl_runhrd.runno,
                                        tbl_runhrd.formulaid,
                                        tbl_runhrd.batchsize,
                                        count(*) as tbatch,
                                        group_concat(tbl_rundata.batchno) as batchlist
                                        ")->where($runWhere)
                                        ->join('tbl_rundata','tbl_rundata.runid = tbl_runhrd.runid')
                                        ->group_by('tbl_runhrd.runno')
                                        ->get('tbl_runhrd')
                                        ->result();
            
            $msg['results'] = $nwfpdetails;	
            $msg['norecord'] = count($nwfpdetails);
            $msg['msg'] = "";
        }else{
            $msg['results'] = [];
            $msg['norecord'] = 0;
            $msg['msg'] = "No record available.";
            
        }
       
        $this->response($msg, 200);
    } 

    public function getruninfo_post(){
        $bme = $this->load->database('nwfth_test',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();

        if(isset($param['runno']) && $param['runno'] != '' ){

            $productlist = $bme->query("
                            SELECT DISTINCT
                            (PT.ItemKey),
                            PM.FormulaId,
                            PT.LineTyp,
                            IM.Desc1,
                            PT.Location,
                            PT.StdQtyDispUom,
                            LF.featurevalue,
                            (
                                            CAST (
                                                        PT.stdqtydispuom AS NUMERIC (9, 4)
                                            ) % CAST (
                                                        LF.featurevalue AS NUMERIC (9, 4)
                                            )
                            ) AS 'Partial',
                            CAST (
                                            (
                                                        CAST (
                                                                        PT.stdqtydispuom AS NUMERIC (9, 4)
                                                        ) / CAST (
                                                                        LF.featurevalue AS NUMERIC (9, 4)
                                                        )
                                            ) AS INT
                            ) AS 'Bulk',
                            LF2.FeatureValue as packunit

                FROM
                            PNMAST PM
                INNER JOIN PNITEM PT ON PM.BatchNo = PT.BatchNo
                INNER JOIN INMAST IM ON IM.Itemkey = PT.itemkey
                LEFT JOIN LotFeaturesValue LF ON LF.itemkey = PT.itemkey
                AND LF.locationkey = PT.location
                AND LF.featureid = 'BAGSIZE'
                LEFT JOIN LotFeaturesValue LF2 ON LF2.itemkey = PT.itemkey AND LF2.locationkey = PT.location AND LF2.featureid = 'PACKUNIT'
                WHERE
                PM.User2 = '".$param['runno']."'
                AND LineTyp = 'FI' ")->result();
            
            $myprod = [];
            foreach($productlist as $k => $v){

                if($v->Bulk >= 1){
                    
                #suggested
                $suggested = $bme->select("
                            itemkey,
                            lotno,
                            qtyonhand,
                            qtycommitsales,
                            Dateexpiry,
                            BinNo,
                            (qtyonhand - Qtycommitsales) AS 'QtyAvailable'
                        ")
                        ->where("ItemKey",$bme->escape($v->ItemKey))
                        ->where("locationkey",$bme->escape('MAIN'))
                        ->where("(qtyonhand - Qtycommitsales) > 0")
                        ->order_by('dateexpiry DESC')
                        ->get('LotMaster')->row();

                    $v->suggested = $suggested;
                    
                    #picked summary
                    $pickedsummary = $nwfp->select("*,sum(qty) as tqty ")
                                    ->where("itemkey",$v->ItemKey)
                                    ->where("runno",$param["runno"])
                                    ->get("tbl_rm_allocate_bulk")->row();

                    $v->pickedsummary = $pickedsummary;

                    #picked
                    $picked = $nwfp->select("*,sum(qty) as tqty ")
                                    ->where("itemkey",$v->ItemKey)
                                    ->where("runno",$param["runno"])
                                    ->group_by('batchno')
                                    ->get("tbl_rm_allocate_bulk")->result();

                    $v->picked = $picked;

                    #picked detailed
                    $pickeddetailed = $nwfp->where("itemkey",$v->ItemKey)
                                            ->where("runno",$param["runno"])
                                            ->get("tbl_rm_allocate_bulk")->result();

                    $v->pickedDetail = $pickeddetailed;

                    $myprod[] = $v;
                } 

            }

            $msg['results'] = $myprod;	

        }else{
            $msg['msgno'] = '108';
            $msg['msg'] = return_msg('108');
        }
        

        

        $this->response($msg, 200);
    }

}