<?php
    class XPSetup_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

		public function getColumn($tbl=""){
            $array=[
				[
                    "db"=>"inmast.Itemkey",
                    "th"=>"Action",
                ],
                [
                    "db"=>"inmast.itemkey",
                    "th"=>"Itemkey",
                    "type"=>"na",
                ],
				[
                    "db"=>"inmast.UPCCODE",
                    "th"=>"Label",
                ],
                [
                    "db"=>"inmast.Manufacturer",
                    "th"=>"Net Wt",
                ],
            ];

            return $array;
        }


	}
?>
