<?php
    class RMLabel_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

		public function getColumn($tbl=""){
            $array=[
				[
                    "db"=>"LT.LotTranNo",
                    "th"=>"Action",
                ],
				[
                    "db"=>"LT.ReceiptDocNo",
                    "th"=>"ReceiptDocno",
                    "req"=>true,
                ],
                [
                    "db"=>"LT.ItemKey",
                    "th"=>"Item Key",
                    "req"=>true,
                ],
                [
                    "db"=>"IM.Desc1",
                    "th"=>"Description",
                    "req"=>true,
                ],
                [
                    "db"=>"LT.LotNo",
                    "th"=>"Lot No",
                    "req"=>true,
                ],
                [
                    "db"=>"AV.Vendor_Name",
                    "th"=>"Vendor Name",
                    "req"=>true,
                ],
                
                /*
                [
                    "db"=>"LT.VendorlotNo",
                    "th"=>"Vendor lot No",
                    "req"=>true,
                    "visible"=>false
                ],
                [
                    "db"=>"LT.QtyReceived",
                    "th"=>"Qty Received",
                    "req"=>true,
                    "visible"=>false
                ],
                [
                    "db"=>"LT.DateReceived",
                    "th"=>"Date Received",
                    "req"=>true,
                    "visible"=>false
                ],
                [
                    "db"=>"LT.DateExpiry",
                    "th"=>"Date Expiry",
                    "req"=>true,
                    "visible"=>false
                ],
                [
                    "db"=>"IQ.ToKey",
                    "th"=>"Pack Type",
                    "req"=>true,
                    "visible"=>false
                ],
                [
                    "db"=>"IQ.Convfctr",
                    "th"=>"Pack Size",
                    "req"=>true,
                    "visible"=>false
                ],
                [
                    "db"=>"QL.Status",
                    "th"=>"QC Status",
                    "req"=>true,
                    "visible"=>false
                ],
                [
                    "db"=>"QR.Tested_By",
                    "th"=>"Tested By",
                    "req"=>true,
                    "visible"=>false
                ],
                [
                    "db"=>"IPD.PropDesc",
                    "th"=>"Allergen",
                    "req"=>true,
                    "visible"=>false
                ],
                */
            ];

            return $array;
        }

		public function setFormatter(){
            
            $array = [
                    "LT" => [
                        "LotTranNo" => function($d,$row){
                            return "<a class='btn btn-outline-info btn-select btn-sm' data-info='".json_encode($row)."'><i class='fas fa-check'></i></a>";
                        },
                    ]
            ];
                    
            return $array;
            
            
        }

	}
?>
