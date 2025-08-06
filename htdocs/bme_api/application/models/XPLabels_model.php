<?php
    class XPLabels_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

        private function itemkeydata(){

            $bme = $this->load->database('nwfth2',true);

          return  $bme->select("Itemkey as id, concat(Itemkey,' - ',Desc1) as text")->get('inmast')->result();
        }

        private function batchnodata(){
            $mdata = [];

            for($aa = 1; $aa <= 50; $aa++){
                $mdata[]=["id" => str_pad($aa,2,"0",STR_PAD_LEFT), "text" => str_pad($aa,2,"0",STR_PAD_LEFT) ];
            }

            return $mdata;
        }

		public function getColumn($tbl=""){
            $array=[
				[
                    "db"=>"tbl_xp_labels.tbl_xp_labels_id",
                    "th"=>"Action",
                ],
				[
                    "db"=>"tbl_xp_labels.itemkey",
                    "th"=>"Item Key",
                    "req"=>true,
                    "ref"=>$this->itemkeydata(),
                    "type"=>"select"
                ],
                [
                    "db"=>"tbl_xp_labels.lotcode",
                    "th"=>"Lot Code",
                    "type" => "date",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_xp_labels.expiry_date",
                    "th"=>"Expiry Date",
                    "type" => "select",
                    "ref" => [
                        ["id" => "30", "text" => "30 Days" ],
                        ["id" => "60", "text" => "60 Days" ],
                        ["id" => "90", "text" => "90 Days" ],
                        ["id" => "120", "text" => "120 Days" ],
                        ["id" => "150", "text" => "150 Days" ],
                        ["id" => "180", "text" => "180 Days" ],
                        ["id" => "360", "text" => "360 Days" ],
                    ],
                    "value"=>"90",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_xp_labels.batchno",
                    "th"=>"Batch No",
                    "type" => "select",
                    "ref"=> $this->batchnodata(),
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_xp_labels.qty_to_print_start",
                    "th"=>"Qty to Print Start",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_xp_labels.qty_to_print_end",
                    "th"=>"Qty to Print End",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_xp_labels.enter_by",
                    "th"=>"Encoded By",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_xp_labels.created_at",
                    "th"=>"Date Encoded",
                    "req"=>true,
                ],
            ];

            return $array;
        }

		public function setFormatter(){
            
            $array = [
                    "tbl_xp_labels" => [
                        "tbl_xp_labels_id" => function($d,$row){
                            $btn="";
                            if(@$this->permission->edit)
                                $btn="<a class='btn btn-outline-info btn-edit-".$this->table."'  data-id='".$d."' ><i class='fas fa-pencil-alt'></i></a>";
               
                            if(@$this->permission->delete)
                                $btn.="<a class='btn btn-outline-danger btn-delete-".$this->table."' data-id='".$d."'><i class='fas fa-trash'></i></a>";
                           

                                $btn.="<a class='btn btn-outline-success btn-reprint-".$this->table."' data-id='".$d."'><i class='fas fa-print'></i></a>";
                            return $btn;
                        },
                        
                        "enter_by" => function($d,$row){
                            $mydata = $this->ndb->where("userid",$d)->get("tbl_user")->row();
                            return $mydata->Fname." ".$mydata->Lname;
                        },
                        "lotcode" => function($d,$row){
                            return date("dmy",strtotime($d))."B".$row['batchno'];
                        }
                    ]
            ];
                    
            return $array;
            
            
        }

	}
?>
