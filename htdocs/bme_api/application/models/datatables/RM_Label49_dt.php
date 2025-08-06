<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RM_Label49_dt extends MY_Model implements DatatableModel{

			
	public function appendToSelectStr() {
        return ["btn" => "concat(LT.LotNo,LT.ItemKey)"];
    }
  
    public function fromTableStr() {
        return "LotTransaction LT";
    }

    public function joinArray() {
        return [
                'INMAST IM | LEFT' => 'IM.Itemkey = LT.ItemKey',
                #'QCLotTransaction QL | left' => 'QL.ReceiptDocNo = LT.ReceiptDocNo',
                #'QCResult QR | left' => 'QR.Receipt_no = LT.ReceiptDocNo',
                #'INMSPEC IP | left' => 'IP.ItemKey = LT.ItemKey and IP.PropValue > 0 and IP.PropNumber < 16',
                #'INMSPECD IPD | left' => 'IPD.PropNumber = IP.PropNumber',
                #'INQTYCNV IQ | left' => 'IQ.UMItemKey = LT.ItemKey',
            ];
    }

    public function whereClauseArray() {
        return [
                "LT.TransactionType"=>"1",
              #  "LT.ReceiptDocNo not like" => "'OI-%'", 
            ];
    }
}