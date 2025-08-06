<?php
    class Price_quotation_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

		public function getColumn($tbl=""){
            $array=[
				
				[
                    "db"=>"tbl_price_quotation.price_quotation_id",
                    "th"=>"",
                ],
                [
                    "db"=>"tbl_price_quotation.tsr",
                    "th"=>"TSR",
                    "req"=>true,
                    "type"=>"select",
                    "ref"=>$this->models->getRefSelect2('Salesp_Key','Salesp_Name','arsalesp')
                ],
                [
                    "db"=>"tbl_price_quotation.customer",
                    "th"=>"Customer",
                    "req"=>true,
                    "ajax"=>[
                        "filter_field" => 'Salespersn_Ky',
                        "id" => 'Customer_Key',
                        "text" => 'Customer_Name',
                        "display" => [
                                        "Customer Key" => "Customer_Key",
                                        "Customer Name"=>"Customer_Name",
                                        "Address"=>"address"
                                    ],
                        "table"=> 'v_arcust',
                        "list_type"=> "popup",
                    ],
                    "filter" => 'tsr',
                    "type"=>"pop_up",
                    "filter_field"=>'Salespersn_Ky',
                    "pop_up_callback" => "getaddress"#function name
                ],
                [
                    "db"=>"tbl_price_quotation.project",
                    "th"=>"Project",
                    "req"=>true,
                    "type"=>"text"
                ],
                [
                    "db"=>"tbl_price_quotation.default_delivery_address",
                    "th"=>"Default Delivery Address",
                    "req"=>true,
                    "type"=>"textarea"
                ],
                [
                    "db"=>"tbl_price_quotation.actual_delivery_address",
                    "th"=>"Actual Delivery Address",
                    "req"=>true,
                    "type"=>"textarea"
                ],
            ];

            return $array;
        }

		public function setFormatter(){
            
            $array = [
                        "tbl_price_quotation"=>[

                            "price_quotation_id" => function($d,$row){
                                $btn = '';
                                if(@$this->npermission[$this->pageid]->edit)
                                    $btn .= '<a data-toggle="tooltip" class="btn btn-outline-info btn-edit-tbl_price_quotation btn-sm" data-id="'.$d.'"><i class="fas fa-pencil-alt"></i></a>';

                                if(@$this->npermission[$this->pageid]->delete)
                                    $btn .= '<a data-toggle="tooltip" class="btn btn-outline-danger btn-delete-tbl_price_quotation btn-sm" data-id="'.$d.'"><i class="fas fa-trash"></i></a>';
                                
                                if( intval($this->userinfo['ulvl']) == 116 || intval($this->userinfo['ulvl']) == 1)
                                    $btn .= '<a data-toggle="tooltip" class="btn btn-outline-warning btn-trans-tbl_price_quotation btn-sm" data-id="'.$d.'"><i class="fas fa-file-contract"></i></a>';
                                
                                
                                $btn .= '<a data-toggle="tooltip" class="btn btn-outline-info btn-print-tbl_price_quotation btn-sm" data-id="'.$d.'"><i class="fas fa-print"></i></a>';
                                
                            return $btn;
                            },
                            "tsr"=> function($d,$row){
                               $mdata = $this->ndb->where('Salesp_Key',$d)->get('arsalesp')->row();
                                return $mdata->Salesp_Name;
                           },
                           "customer"=> function($d,$row){
                                $mdata = $this->ndb->where('Customer_Key',$d)->get('v_arcust')->row();
                                return $mdata->Customer_Name;
                            }
                        ],
                    ];
                    
            return $array;
            
            
        }

	}
?>
