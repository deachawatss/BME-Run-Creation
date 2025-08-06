<?php
    class Rack_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

		public function getColumn($tbl=""){
            $array=[
				
				[
                    "db"=>"rack_tbl.rack_id",
                    "th"=>"",
                ],
                [
                    "db"=>"room_tbl.room_name",
                    "th"=>"Room Name",
                    "odb" => "room_id",
                    "req"=>true,
                    "type"=>"select",
                    "ref"=>$this->models->getRefSelect2('room_id','room_name','room_tbl')
                ],
                [
                    "db"=>"rack_tbl.rack_name",
                    "th"=>"Customer",
                    "req"=>true,
                ],
                [
                    "db"=>"rack_tbl.statflag",
                    "th"=>"Status",
                    "req"=>true,
                    "type"=>"select",
                    "ref"=>[
                            ["id" => "A", "text" => "Active" ],
                            ["id" => "I", "text" => "Inactive" ],
                    ]
                ]
            ];

            return $array;
        }

        public function setFormatter(){
            
            return $array = [
                        "rack_tbl"=>[
                            "statflag" => function($d,$row){
                                $ref = [
                                    "A" => "Active",
                                    "I" => "Inactive"
                                ];

                                return (isset($ref[$d]) ? $ref[$d] : "");
                            },
                        ]
                    ];

        }


	}
?>
