<?php
    class FGLabels_Model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

        private function customerdata(){
            $bme = $this->load->database('nwfth2',true);

            $mydta = $this->ndb->select('custkey')->group_by('custkey')->get('tbl_ref_fg_labels')->result();
            $mwhere = [];
            foreach($mydta as $k=>$v){
                $mwhere[] = $v->custkey;
            }

            return  $bme->where("Customer_Key in ('".implode("','",$mwhere)."') ")->select("Customer_Key as id, concat(Customer_Key,' - ',Customer_Name) as text")->get('ARCUST')->result();
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

        private function expidate(){
            $mdata = [];

            for($aa = 30; $aa <= 360; $aa+=30){
                $mdata[]=["id" => $aa, "text" => $aa." Days"];
            }

            return $mdata;

        }

		public function getColumn($tbl=""){
            $array=[
				[
                    "db"=>"tbl_fg_labels.tbl_fg_labels_id",
                    "th"=>"Action",
                ],
                [
                    "db"=>"tbl_fg_labels.custkey",
                    "th"=>"Customer",
                    "req"=>true,
                    "ref"=>$this->customerdata(),
                    "type"=>"select",
                    "onchange"=>"loadProdData()"
                ],
				[
                    "db"=>"tbl_fg_labels.itemkey",
                    "th"=>"Item Key",
                    "req"=>true,
                    "ref"=>[],
                    "type"=>"select"
                ],
                [
                    "db"=>"tbl_fg_labels.lotcode",
                    "th"=>"Lot Code",
                    "type" => "date",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_fg_labels.expiry_date",
                    "th"=>"Expiry Date",
                    "type" => "select",
                    "ref" => $this->expidate(),
                    "value"=>"180",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_fg_labels.batchno",
                    "th"=>"Batch No",
                    "type" => "select",
                    "ref"=> $this->batchnodata(),
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_fg_labels.blender",
                    "th"=>"Blender",
                    "type" => "radio",
                    "ref"=>  [
                        ["value" => "2", "label" => "Blender 2" ],
                    ],
                    "visible" => false
                ],
                [
                    "db"=>"tbl_fg_labels.qty_to_print_start",
                    "th"=>"Qty to Print Start",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_fg_labels.qty_to_print_end",
                    "th"=>"Qty to Print End",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_fg_labels.enter_by",
                    "th"=>"Encoded By",
                    "req"=>true,
                ],
                [
                    "db"=>"tbl_fg_labels.created_at",
                    "th"=>"Date Encoded",
                    "req"=>true,
                ],
            ];

            return $array;
        }

		public function setFormatter(){
            
            $array = [
                    "tbl_fg_labels" => [
                        "tbl_fg_labels_id" => function($d,$row){
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
