<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    if ( ! function_exists('return_msg')){
        function return_msg($msgno)
        {
            
            $msg = [
                #Error 100 Series
                "-100"  => "Invalid Credential", 
                "101"   => "Invalid Input",
                "102"   => "Batchticket is already processed.\nPlease use Putaway module",
                "103"   => "Cannot be Processed.\nBin doesn't exist",
                "104"   => "Cannot be Processed.\nItem is already in desired Bin",
                "105"   => "Batchticket doesn't exist",
                "106"   => "Cannot be Processed.\nBatchticket is not in FG Staging Bin",
                "107"   => "Cannot be Processed.\nInvalid ItemKey",
                "108"   => "No Data found",
                "109"   => "Invalid Barcode",
                #Success 200 Series
                "200"   =>  "",
                "201"   =>  "Transaction Success",

                "1000"  => "Successfully Login",
            ];

            return ($msg[$msgno] ?? "");
        
        }
    }