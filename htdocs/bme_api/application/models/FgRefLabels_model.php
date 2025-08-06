<?php
    class FgRefLabels_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

        private function customerdata(){
            $bme = $this->load->database('nwfth2',true);

            $mydta = $this->ndb->select('custkey')->group_by('custkey')->get('tbl_prod_reference')->result();
            $mwhere = [];
            foreach($mydta as $k=>$v){
                $mwhere[] = $v->custkey;
            }

            return  $bme->select("Customer_Key as id, concat(Customer_Key,' - ',Customer_Name) as text")->get('ARCUST')->result();
        }

        private function itemkeydata(){

            $bme = $this->load->database('nwfth2',true);
            

          return  $bme->select("Itemkey as id, concat(Itemkey,' - ',Desc1) as text")->get('inmast')->result();
        }

        private function allergen(){
            return [
                ["value"=>"Colors", "label"=> "Colors"],
                ["value"=>"Soy", "label"=> "Soy"],
                ["value"=>"Sulphites", "label"=> "Sulphites"],
                ["value"=>"Egg", "label"=> "Egg"],
                ["value"=>"Dairy", "label"=> "Dairy"],
                ["value"=>"MSG", "label"=> "MSG"],
                ["value"=>"Crustacean", "label"=> "Crustacean"],
                ["value"=>"Tree Nuts", "label"=> "Tree Nuts"],
                ["value"=>"Peanuts", "label"=> "Peanuts"],
                ["value"=>"Fish", "label"=> "Fish"],
                ["value"=>"Sesame", "label"=> "Sesame"],
                ["value"=>"Wheat", "label"=> "Wheat"],
                ["value"=>"Mustard", "label"=> "Mustard"],
                ["value"=>"Celery", "label"=> "Celery"],
                ["value"=>"Mollusk", "label"=> "Mollusk"],
            ];
        }


        public function getColumn($tbl=""){
            $array=[
				[
                    "db"=>"tbl_ref_fg_labels.tbl_ref_fg_labels_id",
                    "th"=>"Action",
                ],
                [
                    "db"=>"tbl_ref_fg_labels.custkey",
                    "th"=>"Customer",
                    "req"=>true,
                    "ref"=>$this->customerdata(),
                    "type"=>"select",
                    "onchange"=>"loadProdData()"
                ],
                [
                    "db"=>"tbl_ref_fg_labels.title",
                    "th"=>"Title",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_ref_fg_labels.subtitle",
                    "th"=>"Subtitle",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_ref_fg_labels.itemkey",
                    "th"=>"Item Key",
                    "req"=>true,
                    "ref"=>[],
                    "type"=>"select"
                ],
                [
                    "db"=>"tbl_ref_fg_labels.itemcode",
                    "th"=>"Item Code",
                ],
                [
                    "db"=>"tbl_ref_fg_labels.inners",
                    "th"=>"Inners",
                ],
                [
                    "db"=>"tbl_ref_fg_labels.allergen",
                    "th"=>"Allergen",
                    "type" => "checked",
                    "ref" => $this->allergen(),
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_ref_fg_labels.wt",
                    "th"=>"Net Wt",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_ref_fg_labels.producewith",
                    "th"=>"Notes",
                    "type" => "checked",
                    "ref" => $this->allergen(),
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_ref_fg_labels.template",
                    "th"=>"Template",
                    "req"=>true,
                ],
            ];

            return $array;
        }

    }