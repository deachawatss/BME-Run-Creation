<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: *");
defined('BASEPATH') OR exit('No direct script access allowed');


require(APPPATH.'/libraries/RestController.php');
require(APPPATH.'/libraries/Format.php');
use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;

class Bulkpick extends RestController
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
        $bme = $this->load->database('nwfth',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();
		$msg = [];
        $bcode = [];
        $runWhere = [];
        $runWhere['tbl_runhrd.statflag'] = 'N';
        if( isset($param['runno']) )
            $runWhere['tbl_runhrd.runno'] = $param['runno'];
        
        
        if($runWhere != ''){
            
            $nwfpdetails = $nwfp->select("
                                        tbl_runhrd.runno,
                                        tbl_runhrd.formulaid,
                                        tbl_runhrd.batchsize,
                                        count(*) as tbatch,
                                        group_concat(tbl_rundata.batchno) as batchlist
                                        ")->where($runWhere)
                                        ->join('tbl_rundata','tbl_rundata.runid = tbl_runhrd.runid')
                                        #->order_by('tbl_rundata.batchno asc')
                                        ->group_by('tbl_runhrd.runno')
                                        ->get('tbl_runhrd')
                                        ->result();
            
            #$nwfpdetails = $nwfp->get('tbl_runhrd')->result();

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

    public function getrun_post(){
        $bme = $this->load->database('nwfth',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();
		$msg = [];
        $bcode = [];
        $runWhere = '1 = 1';

        if( isset($param['runno']) )
            $runWhere = ["runno >"=>"0"];
        
        
        
        if($runWhere != ''){
            $batchlist = [];
            
            $batchlist = $nwfp->select("
                                        batchno,
                                        formulaid
                                        description,
                                        runno
                                        ")->where($runWhere)
                                        ->get('tbl_runhrd')
                                        ->result();

            foreach($nwfpdetails as $k => $v){
                $batchlist[] = $v->batchno;
            }
            
            $msg['results'] = $nwfpdetails;	
            $msg['norecord'] = count($nwfpdetails);
        }else{
            $msg['msgno'] = '108';
            $msg['msg'] = return_msg('108');
            
        }
       
        $this->response($msg, 200);
    }

    public function getruninfo_post(){
        $bme = $this->load->database('nwfth',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();
       # $where = '';
        $where2 = '';
        if(isset($param['runno']) && $param['runno'] != '' ){

            /*
            if(isset($param['itemkey'])  && $param['itemkey'] != ''  ){

                if( strpos($param['itemkey'], '(') != false )
                {
                    $prodcode = $param['itemkey'];
                    $bcode = getBarcodeData($prodcode);
                    $where = " and PT.ItemKey = '".$bcode['02']."'";
                }else{
                    $where = " and PT.ItemKey = '".$param['itemkey']."'";
                }
                   
            }

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
                ".$where."
                AND LineTyp = 'FI' ")->result();
            */


            $where = [];
            if(isset($param['itemkey'])  && $param['itemkey'] != ''  ){
                
                if( strpos($param['itemkey'], '(') != false )
                {
                    $prodcode = $param['itemkey'];
                    $bcode = getBarcodeData($prodcode);
                    $where['ItemKey'] = $bme->escape($bcode['02']);
                }else{
                    $where['ItemKey'] = $bme->escape($param['itemkey']);
                }
                
            }

            $productlist = $bme
                        ->select("
                        DISTINCT ItemKey,
                        FormulaId,
                        LineTyp,
                        Desc1,
                        Location,
                        StdQtyDispUom,
                        featurevalue,
                        PartialData,
                        \"Bulk\",
                        packunit
                        ")
                        ->where('User2',$bme->escape($param['runno']))
                        ->where($where)
                        ->get('v_prod_assembly')->result();
    

            $myprod = [];
            foreach($productlist as $k => $v){

                if($v->Bulk >= 1){
                    
                #suggested
                $suggested = $bme->select("
                            LM.itemkey,
                            LM.lotno,
                            LM.qtyonhand,
                            LM.qtycommitsales,
                            LM.Dateexpiry,
                            LM.BinNo,
                            (LM.qtyonhand - LM.Qtycommitsales) AS 'QtyAvailable',
                            LF.featurevalue
                        ")
                        ->join('LotFeaturesValue LF','LF.itemkey = LM.itemkey AND LM.LotNo = LF.LotNo AND LF.featureid = \'BAGSIZE\'','Left')
                        ->where("LM.ItemKey",$bme->escape($v->ItemKey))
                        ->where("LM.locationkey",$bme->escape('MAIN'))
                        ->where("(LM.qtyonhand - LM.Qtycommitsales) > 0")
                        ->where("(LF.featurevalue <= (LM.qtyonhand - LM.Qtycommitsales)  ) ")
                        ->order_by('LM.dateexpiry asc, LM.LotNo asc')
                        ->get('LotMaster LM')->row();

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

    public function getrunrec_post(){
        $bme = $this->load->database('nwfth',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();

        if( 
            isset($param['runno']) && $param['runno'] != '' &&
            isset($param['rmitemkey']) && $param['rmitemkey'] != '' 
         ){

            #picked
            $picked = $nwfp->select("*,sum(qty) as tqty ")
                            ->where("itemkey", $param['rmitemkey'])
                            ->where("runno", $param["runno"])
                            ->group_by('batchno')
                            ->get("tbl_rm_allocate_bulk")->result();

            $msg['results'] = $picked;	
            $msg['norecord'] = count($picked);

         }else{

            $msg['msgno'] = '108';
            $msg['msg'] = return_msg('108');

         }
       
        $this->response($msg, 200);
    }

    public function getitemlist_post(){
        $bme = $this->load->database('nwfth',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();

        if( 
            isset($param['runno']) && $param['runno'] != '' &&
            isset($param['batchno']) && $param['batchno'] != '' &&
            isset($param['rmitemkey']) && $param['rmitemkey'] != '' 
         ){

            $suggested = $bme->select("
                            itemkey,
                            lotno,
                            qtyonhand,
                            qtycommitsales,
                            Dateexpiry,
                            BinNo,
                            (qtyonhand - Qtycommitsales) AS 'QtyAvailable'
                        ")
                        ->where("ItemKey",$bme->escape($param['rmitemkey']))
                        ->where("locationkey",$bme->escape('MAIN'))
                        ->where("(qtyonhand - Qtycommitsales) > 0")
                        ->order_by('dateexpiry DESC')
                        ->get('LotMaster')->result();

            $msg['results'] = $suggested;	
            $msg['norecord'] = count($suggested);

         }else{

            $msg['msgno'] = '108';
            $msg['msg'] = return_msg('108');

         }
       
        $this->response($msg, 200);
    }

    public function getlotdata_post(){
        $bme = $this->load->database('nwfth',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();
        $bcode = [];
        if( 
            isset($param['rmitemkey']) && $param['rmitemkey'] != ''
        ){

            if(isset($param['barcode'])){
                $prodcode = $param['barcode'];
                $bcode = getBarcodeData($prodcode);
            }

            $LotWhere = [];

            if(isset($param['barcode']) && $param['barcode'] != ''){
            
                if( isset($bcode['02'])){
                    $LotWhere['LotMaster.ItemKey'] = $bme->escape($param['rmitemkey']);
                }else{
                    $msg['results'] = [];
                    $msg['norecord'] = 0;
                    $msg['msg'] = "Invalid input";
                    $this->response($msg, 200);
                    die();
                }
    
                if(isset($bcode['10'])){
                    $LotWhere['LotMaster.LotNo like'] = $bme->escape($bcode['10'].'%');
                }else{
                    $msg['results'] = [];
                    $msg['norecord'] = 0;
                    $msg['msg'] = "Invalid input";
                    $this->response($msg, 200);
                    die();
                }
            }

            $LotWhere['itemkey'] = $bme->escape($param['rmitemkey']);

            #Virtually commited Items
            $vitemlist = $nwfp
                            ->where("itemkey",$param['rmitemkey'])
                            ->where("statflag = 'N'")
                            ->get('tbl_rm_allocate_bulk')
                            ->result();
            $mitemlist = [];
            $alist = [];
            foreach($vitemlist as $k => $v){
                $mitemlist[ $v->itemkey."_".$v->lotno ] = $v;
                $alist[] = $v->itemkey."_".$v->lotno;
            }

            $itemlist = $bme->select("
                                    itemkey,
                                    lotno,
                                    qtyonhand,
                                    qtycommitsales,
                                    Dateexpiry,
                                    (qtyonhand - Qtycommitsales) AS 'QtyAvailable',
                                    binno
                                    ")
                                    ->where($LotWhere)
                                    ->where("binno not in ('A-PREWEIGH','C-Bulk','C-RM_STAGING','')")
                                    ->where("locationkey", $bme->escape('MAIN') )
                                    ->where("(qtyonhand - Qtycommitsales) > 0")
                                    ->order_by('dateexpiry asc')
                                    ->get('LotMaster')->result();
            $nitemlist = [];
            foreach($itemlist as $k => $v){
               
                if( in_array($v->itemkey."_".$v->lotno , $alist) ){
                    $v->QtyAvailable = $v->QtyAvailable - $mitemlist[ $v->itemkey."_".$v->lotno ]->qty;
                }

                $nitemlist[] = $v;
            }

            
            #$msg['vitemlist'] = $vitemlist;
            $msg['results'] = $nitemlist;	
            $msg['norecord'] = count($nitemlist);

         }else{

            $msg['msgno'] = '108';
            $msg['msg'] = return_msg('108');

         }
       
        $this->response($msg, 200);
    }

    public function additembulk_post(){
        $bme = $this->load->database('nwfth',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();
        $bcode = [];

        if( 
            isset($param['barcode']) && $param['barcode'] != '' &&
            isset($param['binno']) && $param['binno'] != '' &&
            isset($param['qty']) && $param['qty'] != '' &&
            isset($param['runno']) && $param['runno'] != '' &&
            isset($param['batchno']) && $param['batchno'] != ''
        ){


            $LotWhere = [];

            if(isset($param['barcode']) && $param['barcode'] != ''){
                $prodcode = $param['barcode'];
                $bcode = getBarcodeData($prodcode);
                if( !isset($bcode['02']) || !isset($bcode['10']) ){
                    $msg['msg'] = return_msg('109');
                    $msg['msgno'] = 109;
                    $this->response($msg, 200);
                    die();
                }
            }else{
                $msg['msg'] = return_msg('109');
                $msg['msgno'] = 109;
                $this->response($msg, 200);
                die();
            }

            #check if itemkey exist in formula
            $myreqitem  = $bme->query("
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
                                    And PT.ItemKey = '".$bcode['02']."'
                                    AND LineTyp = 'FI' ")->row();
            
            if(!($myreqitem->ItemKey)){
                $msg['msg'] = return_msg('107');
                $msg['msgno'] = 107;
                $this->response($msg, 200);
                die();
            }


            #check if lot-itemkey exist

            $itemlist = $bme->select("
                                    itemkey,
                                    lotno,
                                    qtyonhand,
                                    qtycommitsales,
                                    Dateexpiry,
                                    (qtyonhand - Qtycommitsales) AS 'QtyAvailable',
                                    binno
                                    ")
                                    ->where("locationkey", $bme->escape('MAIN') )
                                    ->where('itemkey',$bme->escape($bcode['02']))
                                    ->where('lotno',$bme->escape($bcode['10']))
                                    ->where('binno',$bme->escape($param['binno']))
                                    ->where("(qtyonhand - Qtycommitsales) > 0")
                                    ->get('LotMaster')->row();

            if(!($itemlist->itemkey)){
                $msg['msg'] = return_msg('107');
                $msg['msgno'] = 107;
                $this->response($msg, 200);
                die();
            }
            

            #checked if reached maximum requirements
            $picked = $nwfp->select("*,sum(qty) as tqty ")
                            ->where("itemkey", $bcode['02'])
                            ->where("batchno", $param['batchno'])
                            ->where("runno", $param["runno"])
                            ->get("tbl_rm_allocate_bulk")->row();
            $tqty = 0;
            if($param['itype'] == 0){
                $totalqty = $picked->tqty  + $param['qty'];
                if($totalqty > $myreqitem->StdQtyDispUom ){
                    $msg['msg'] = return_msg('107');
                    $msg['msgno'] = 107;
                    $this->response($msg, 200);
                    die();
                }

                #check if qty is available
                if($itemlist->QtyAvailable <  $param['qty'] ){
                    $msg['msg'] = return_msg('107');
                    $msg['msgno'] = 107;
                    $this->response($msg, 200);
                    die();
                }

                $tqty= $param['qty'];
            }else{
                $totalqty = ($picked->tqty / $myreqitem->featurevalue) + $param['qty'];
                if($totalqty > $myreqitem->Bulk ){
                    $msg['msg'] = return_msg('107');
                    $msg['msgno'] = 107;
                    $this->response($msg, 200);
                    die();
                }

                 #check if qty is available
                if($itemlist->QtyAvailable < ($myreqitem->featurevalue * $param['qty']) ){
                    $msg['msg'] = return_msg('107');
                    $msg['msgno'] = 107;
                    $this->response($msg, 200);
                    die();
                }

                $tqty=  ($param['qty'] * $myreqitem->featurevalue);
            }

            
           

            
            #add item to tbl_rm_allocate_bulk
            $this->ndb = $nwfp;
            $this->has_enter_by = true;
            $this->has_created_at = true;
            $this->has_updated_by = true;
            $this->has_updated_date = true;

            $this->load->model("Default_model_api","models");
            

            $toadd = [
                "runno" => $param['runno'],
                "itemkey" => $bcode['02'],
                "lotno" => $bcode['10'],
                "binno" => $param['binno'],
                "qty" => $tqty,
                "batchno" => $param['batchno'],
                "statflag" => 'N',
                "bulkqty" => $param['mbulk']
            ];

            $this->models->Add('tbl_rm_allocate_bulk',$toadd);
            $mydata = $nwfp
                        ->where('itemkey' , $bcode['02'])
                        ->where('runno' , $param['runno'])
                        ->where('batchno' , $param['batchno'])
                        ->get('tbl_rm_allocate_bulk')->result();

            $msg['data'] = $mydata;
            $msg['msgno'] = '200';
            #return updated data
                

        }else{

            $msg['msgno'] = '108';
            $msg['msg'] = return_msg('108');

        }
       
        $this->response($msg, 200);

    }

    public function getiteminfo_post(){
        $bme = $this->load->database('nwfth',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();

        if(
            isset($param['runno']) && $param['runno'] != '' &&
            isset($param['rm_itemkey']) && $param['rm_itemkey'] != '' 
            ){
            
            /*
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
                AND PT.ItemKey = '".$param['rm_itemkey']."'
                AND LineTyp = 'FI' ")->result();
            */
            $productlist = $bme
                        ->select("
                        DISTINCT ItemKey,
                        FormulaId,
                        LineTyp,
                        Desc1,
                        Location,
                        StdQtyDispUom,
                        featurevalue,
                        PartialData,
                        \"Bulk\",
                        packunit
                        ")
                        ->where('User2',$bme->escape($param['runno']))
                        ->where('ItemKey',$bme->escape($param['rm_itemkey']))
                        ->get('v_prod_assembly')->result();
            

            $myprod = [];
            foreach($productlist as $k => $v){

                if($v->Bulk >= 1){
                    
                    $suggested = $bme->select("
                        LM.itemkey,
                        LM.lotno,
                        LM.qtyonhand,
                        LM.qtycommitsales,
                        LM.Dateexpiry,
                        LM.BinNo,
                        (LM.qtyonhand - LM.Qtycommitsales) AS 'QtyAvailable',
                        LF.featurevalue
                    ")
                    ->join('LotFeaturesValue LF','LF.itemkey = LM.itemkey AND LM.LotNo = LF.LotNo AND LF.featureid = \'BAGSIZE\'','Left')
                    ->where("LM.ItemKey",$bme->escape($v->ItemKey))
                    ->where("LM.locationkey",$bme->escape('MAIN'))
                    ->where("(LM.qtyonhand - LM.Qtycommitsales) > 0")
                    ->where("(LF.featurevalue <= (LM.qtyonhand - LM.Qtycommitsales)  or LF.featurevalue is NULL) ")
                    ->order_by('LM.dateexpiry asc, LM.LotNo asc')
                    ->get('LotMaster LM')->row();

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

                    $myprod = $v;
                }  

            }

            $msg['result'] = $myprod;	

        }else{
            $msg['msgno'] = '108';
            $msg['msg'] = return_msg('108');
        }
        

        

        $this->response($msg, 200);
    }

    public function setallocate_post(){
        $bme = $this->load->database('nwfth',true);
        $nwfp = $this->load->database('default',true);
        $param = $this->input->post();
        $this->load->model("Default_model_api","models");

        $paramdata = $nwfp
                        ->where('itemkey',$param['rmitemkey'])
                        ->where('batchno',$param['batchno'])
                        ->where('statflag','N')
                        ->get('tbl_rm_allocate_bulk')->result();

        if( count($paramdata) > 0 ){
            $bme->trans_begin();
            foreach($paramdata as $pk => $pv){
                $batchno = $param['batchno'];
                $lotno = $pv->lotno;
                $putaway = $pv->qty;
                $tobin = 'C-Bulk'; #Change to virtual Bin
                $itemkey = $pv->itemkey;
                $bin = $pv->binno;
                $uname = $param['uname'];


                if($lotno == '' || $putaway == '' || $tobin == '' || $itemkey	== '' || $bin == ''){
                    # if($lotno == '' || $putaway == '' || $tobin == '' || $itemkey	== '' ){
                        $msg = [
                            "msgno" => 1000,
                            "msg" => "Enter Valid input"
                        ];
                        
                        $bme->trans_rollback();
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
                        $bme->trans_rollback();
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
                    $bme->trans_rollback();
                        $this->response($msg, 200);
                        die();
                }

                if($putaway > $lotdetails->QtyOnHand){

                        $msg = [
                            "msgno" => 1002,
                            "msg" => "Invalid Putaway Quantity!"
                        ];
                        $bme->trans_rollback();
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
                        $bme->trans_rollback();
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
                    $bme->trans_rollback();
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
                            "QtyOnHand" => ($lotexist->QtyOnHand + $putaway),
                            "QtyCommitSales" => ($lotexist->QtyCommitSales + $putaway)
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
                            "QtyOnHand" => ($lotexist->QtyOnHand + $putaway),
                            "QtyCommitSales" => ($lotexist->QtyCommitSales + $putaway)
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
                            "BinNo" => $tobin,
                            "QtyCommitSales" =>  $putaway
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
                            "User5" => $lotdetails->User5,
                            "QtyCommitSales" =>  $putaway
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


                #Start Lot Transactions

                $pnitemwhere = [
                    'BatchNo' => $bme->escape($batchno),
                    'ItemKey' => $bme->escape($itemkey),
                    'LineTyp' => $bme->escape('FI'),
                ];

                $pnitemdata = $bme
                                ->where($pnitemwhere)
                                ->get('PNITEM')->row();

                if($pnitemdata){
                    $nLotTransaction_i = [
                        "LotNo" => $lotdetails->LotNo,
                        "ItemKey" => $lotdetails->ItemKey,
                        "LocationKey" => $lotdetails->LocationKey,
                       # "DateReceived" => $lotdetails->DateReceived,
                        "DateExpiry" => $lotdetails->DateExpiry,
                       # "VendorKey" => NULL,
                        "VendorLotNo" => $lotdetails->VendorLotNo,
                        "RecUserid" => $uname, #
                        "RecDate" => $udate,
                        "Processed" => "N",
                        "TransactionType" => 5,
                        "ReceiptDocNo"=> "BT-".($Seqnum + 1) ,
                        "ReceiptDocLineNo" => 1,
                        "QtyReceived" => 0,
                        "IssueDocNo" => $batchno,
                        "IssueDocLineNo" => $pnitemdata->Lineid,
                        "IssueDate" => $udate,
                        "QtyIssued" => $putaway,
                        "BinNo" => $tobin,
                        "TempQty" => 0,
                        "QtyForLotAssignment" => 0,
                        "QtyUsed" => 0
                    ];
                    $this->models->Add("LotTransaction",$nLotTransaction_i);

                    #Add Transaction Lock
                    $nLotTransactionLock = [
                        "DocNo" => $batchno,
                        "DocLineNo" => $pnitemdata->Lineid,
                        "TranType" => 5,
                        "RecDate" => $udate,
                        "RecUserId" => $uname
                    ];
                    $this->models->Add("LotTransactionLock",$nLotTransactionLock);
                    
                    $npnmast = [
                        "SerLotQty" => ( $pnitemdata->SerLotQty + $putaway)
                    ];

                    if($pnitemdata->StdQtyDispUom == ($pnitemdata->SerLotQty + $putaway) ){
                        $npnmast["Status"]= "A";
                    }

                    $this->models->Update("PNITEM",$npnmast,$pnitemwhere);

                }

                #Change Bulk Status to R - Release / Ready
                $urmbulk = [
                    "statflag" => "R"
                ];

                $nwfp->update("tbl_rm_allocate_bulk",$urmbulk,"tbl_rm_allocate_bulk_id = '".$pv->tbl_rm_allocate_bulk_id."'");

            }

             #$bme->trans_rollback();
             if ($bme->trans_status() === FALSE)
             {
                 $msg = [
                     "msgno" => 999,
                     "msg" => "Cannot Proccess,Please Contact Administrator"
                 ];
                 
                 $bme->trans_rollback();
                 $this->response($msg, 200);
                 die();
                 
             }
             else
             {
                 

                 $msg = [
                    "msgno" => 200,
                    "msg" => "Successfully Allocated"
                ];
                $bme->trans_commit();
                $this->response($msg, 200);
                die();
                     #echo json_encode($msg);
                     #die();
             }

        }else{

        }

    }
}
