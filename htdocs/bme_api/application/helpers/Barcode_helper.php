<?php

    function getBarcodeData($barcode = ''){
        $data = explode('(',$barcode);
        $pp = [
            "barcode" => $barcode
        ];
		foreach($data as $k => $v){
			if(strlen($v) > 0){
		        $ppx = explode(')',$v);
               
                if( isset($ppx[1]) )
                    $pp[$ppx[0]] = $ppx[1];

                
			}
           
		}
        return $pp;
    }