<?php
    class PalletTag_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

		public function getColumn($tbl=""){
            $array=[
				
				[
                    "db"=>"tbl_pallet_tag.pallet_tag_id",
                    "th"=>"",
                ],
                [
                    "db"=>"tbl_pallet_tag.runno",
                    "th"=>"Run No",
                    "req"=>true,
                    "ajax"=>[
                        "display" => [
                                        "Run No" => "runno",
                                        "Formula ID"=>"formulaid",
                                        "Batch Size"=>"batchsize",
                                    ],
                        "table"=> 'tbl_runhrd',
                        "list_type"=> "popup",
                    ],
                    "filter" => 'tsr',
                    "type"=>"pop_up",
                    "pop_up_callback" => "pickdata"#function name
                ],
                /*
                [
                    "db"=>"tbl_pallet_tag.batchticket",
                    "th"=>"Batch Ticket",
                    "req"=>true,
                    "readonly"=>true
                ],
                */
                [
                    "db"=>"tbl_pallet_tag.formulaid",
                    "th"=>"Formula ID",
                    "req"=>true,
                    "readonly"=>true
                ],
                /*
                [
                    "db"=>"tbl_pallet_tag.batchno",
                    "th"=>"Batch No",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_pallet_tag.no_of_pallets",
                    "th"=>"No of Pallets",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_pallet_tag.pallet_no",
                    "th"=>"Pallet No",
                    "req"=>true,
                ],
                */
            ];

            return $array;
        }

        public function setFormatter(){
            
            return $array = [];

        }


	}
?>
