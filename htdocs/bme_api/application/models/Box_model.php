<?php
    class Box_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

		public function getColumn($tbl=""){
            $array=[
				
				[
                    "db"=>"box_tbl.box_id",
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
                    "th"=>"Rack Name",
                    "odb" => "rack_id",
                    "req"=>true,
                    "type"=>"select-ajax",
                    "filter"=>"room_id",
                    "ref"=>$this->models->getRefSelect2('room_id','room_name','room_tbl')
                ],
                [
                    "db"=>"box_tbl.box_name",
                    "th"=>"Box Name",
                    "req"=>true,
                ],
                [
                    "db"=>"box_tbl.statflag",
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
                        "box_tbl"=>[
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
