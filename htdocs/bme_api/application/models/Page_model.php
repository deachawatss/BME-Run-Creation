<?php
    class Page_model extends MY_Model {
    
        function __construct()
        {
            parent::__construct();
            
        }

		public function setFormatter(){
            
            $array = [
                        "tbl_page"=>[
							"pageid" => function($d,$row){

								$btn = "";
								$btn .="<a class='btn btn-outline-info btn-edit-tbl_page'  data-id='".$d."' ><i class='fas fa-pencil-alt'></i></a>";
								$btn .="<a class='btn btn-outline-danger btn-delete-tbl_page' data-id='".$d."'><i class='fas fa-trash'></i></a>";
								$btn .="<a class='btn btn-outline-secondary btn-create-tbl_page' data-id='".$d."'><i class='fas fa-scroll'></i></a>";
						
								return $btn;
							}
						],
					];

			return $array;

		}
	}
