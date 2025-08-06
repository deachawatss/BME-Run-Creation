<div class="col-md-12" style="overflow:scroll">
	<table class="table table-sm table-bordered table-hover" style="white-space: nowrap;overflow:scroll;background-color:white">
        <thead>
            <th colspan="8"></th>
            <th colspan="6" class='text-center'>BULK</th>
            <th colspan="4" class='text-center'>PARTIAL</th>
        </thead>
		<thead>
			<th>Run No</th>
			<th>Blender</th>
			<th>Formula</th>
			<th>Total Batches</th>
			<th>Total Qty (KG)</th>
			<th>Prod. Date</th>
			<th>Status</th>
			<th>Batches</th>
			<th>Bulk Picking</th>
			<th>Item Key</th>
			<th>Qty Required</th>
			<th>BulkPicked</th>
            <th>Bulk KGs</th>
            <th>KGs Picked</th>
			<th>Partial Picking</th>
			<th>Item Key</th>
			<th>Qty Required</th>
			<th>Picked</th>
		</thead>
		<tbody>
        <?php 
            $runno = [];
            $counter = 1;       
            $htmlhrd = [];
            $htmlbody = [];
            $batch = [];

            $bulkis = []; // 0 - New , 1 - PENDING, 2 - Complete
            $partialis = []; // 0 - New , 1 - PENDING, 2 - Complete
            $partial = []; 
        ?>
            <?php foreach($details as $k => $v){ ?>
                <?php 
                   $user2 = "";
                   $formula = "";
                   $totalbacthes = "";
                   $totalqty = "";
                   $proddate = "";
                   $packunit = "";
                   $partialdata = "";
                   $bulkdata = "";
                   if(!in_array($v->User2,$runno)){
                        $user2 = $v->User2;
                        $runno[] = $v->User2;
                        $counter = 1;

                        $formula = $v->FormulaId;
                        $proddate = $v->BatchTicketDate;

                        
                        $htmlhrd[$v->User2] = "
                            <tr class='rowrun %s' data-runno='".$v->User2."' data-batch='".$v->BatchNo."'>
                                <td> $user2 </td>
                                <td></td>
                                <td> $formula </td>
                                <td> %u </td>
                                <td> $totalqty </td>
                                <td>".($proddate ? date("m-d-Y",strtotime($proddate)) : "")."</td>
                                <td>%s</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>    
                       ";

                       $batch[$v->User2] = [];
                    }  

                    if(!in_array($v->BatchNo,$batch[$v->User2])){
                        $batch[$v->User2][$v->BatchNo] = "
                                <tr class='batchrow runno-".$v->User2." %s' data-batch='".$v->BatchNo."'>
                                    <td></td> <!--Run No -->
                                    <td></td> <!--Blender -->
                                    <td></td> <!--Formula -->
                                    <td></td> <!--Total Batches -->
                                    <td></td> <!--Total Qty (KG) -->
                                    <td></td> <!--Prod. Date -->
                                    <td></td> <!--Status -->
                                    <td>".$v->BatchNo."</td> <!--Batches -->
                                    <td class='%s'> %s </td> <!--Bulk Picking -->
                                    <td class='%s'></td> <!--Item Key	 -->
                                    <td class='%s'></td> <!--Qty Required -->
                                    <td class='%s'></td> <!--Picked -->
                                    <td class='%s'></td> <!--Bulk KGs-->
                                    <td class='%s'></td> <!--KGs Picked -->
                                    <td class='%s'> %s </td> <!--Partial Picking -->
                                    <td class='%s'></td> <!--Item Key -->
                                    <td class='%s'></td> <!--Qty Required -->
                                    <td class='%s'></td> <!--Picked -->
                                </tr>    
                        ";
                    }
                        
                        #$partialdata = $v->StdQtyDispUom; 
                        $partialdata = ($v->PartialData ?? 0);
                        $bulkdata = ($v->Bulk ?? 0);
                        $bulkKGdata = ( ($v->Bulk * $v->featurevalue) ?? 0);

                        $mybulk = (isset($dbulk[$v->User2][$v->BatchNo][$v->ItemKey]->msum) ? ($dbulk[$v->User2][$v->BatchNo][$v->ItemKey]->msum / $v->featurevalue) :  0);
                        $mybulkKG = (isset($dbulk[$v->User2][$v->BatchNo][$v->ItemKey]->msum) ? ($dbulk[$v->User2][$v->BatchNo][$v->ItemKey]->msum) :  0);

                        $mypartial = (isset($dpartial[$v->User2][$v->BatchNo][$v->ItemKey]->msum) ? ($dpartial[$v->User2][$v->BatchNo][$v->ItemKey]->msum ) :  0);
                        
                        if($bulkdata > 0)
                            $bulkis[$v->BatchNo][] = ( ($bulkdata == 0)|| ($bulkdata > 0 && $mybulk > 0) ? 1 : 0 );

                        if($partialdata > 0)
                            $partialis[$v->BatchNo][] = ( ($partialdata == 0)|| ($partialdata > 0 && $mypartial > 0) ? 1 : 0 );
                        

                        $htmlbody[$v->BatchNo][]= "
                                <tr class='pickrow batch-".$v->BatchNo."'>
                                    <td></td> <!--Run No -->
                                    <td></td> <!--Blender -->
                                    <td></td> <!--Formula -->
                                    <td></td> <!--Total Batches -->
                                    <td></td> <!--Total Qty (KG) -->
                                    <td></td> <!--Prod. Date -->
                                    <td></td> <!--Status -->
                                    <td></td> <!--Batches -->
                                    <td class='%s'></td> <!--Bulk Picking -->
                                    <td class='%s'>".$v->ItemKey."</td> <!--Item Key	 -->
                                    <td class='%s'>".$bulkdata."</td> <!--Qty Required -->
                                    <td class='%s'>".$mybulk."</td> <!--Picked -->
                                    <td class='%s'>".$bulkKGdata."</td> <!--Bulk KGs-->
                                    <td class='%s'>".$mybulkKG."</td> <!--KGs Picked -->
                                    <td class='%s'></td> <!--Partial Picking -->
                                    <td class='%s'>".$v->ItemKey."</td> <!--Item Key -->
                                    <td class='%s'>".$partialdata."</td> <!--Qty Required -->
                                    <td class='%s' >".$mypartial."</td> <!--Picked -->
                                </tr>    
                        ";

                   

                   $counter++;  

                ?>
                
            <?php } ?>
            
            <?php
                
                foreach($htmlhrd as $k => $v){
                    $myhtml = "";
                    $fstat = [];
                    #$fstatpartial = [];
                    

                    foreach($batch[$k] as $kk => $vv){

                        if(isset($bulkis[$kk])){
                            $mdta = array_count_values($bulkis[$kk]);
                            $cm = count($bulkis[$kk]);
                        }else{
                            $mdta = [0,0];
                            $cm = 0;
                        }

                        if(isset($partialis[$kk])){
                            $mdta_partial = array_count_values($partialis[$kk]);
                            $cm_partial = count($partialis[$kk]);
                        }else{
                            $mdta_partial = [0,0];
                            $cm_partial = 0;
                        }

                     
                        $statbulk = "";
                        $statpartial = "";
                        $statbulkcss = "";
                        $statpartialcss = "";
                        
                        if( isset($mdta[1]) && $mdta[1] == $cm ){
                            $statbulk = "COMPLETED";
                        }
                        elseif( isset($mdta[0]) && $mdta[0] == $cm ) { 
                            $statbulk = "NEW";
                        }else{
                            $statbulk = "PENDING";
                        }

                        if( isset($mdta_partial[1]) && $mdta_partial[1] == $cm_partial ){
                            $statpartial = "COMPLETED";
                        }
                        elseif( isset($mdta_partial[0]) && $mdta_partial[0] == $cm_partial ) { 
                            $statpartial = "NEW";
                        }else{
                            $statpartial = "PENDING";
                        }

                        $fstat[] = $statbulk;
                        $fstat[] = $statpartial;

                        /*
                        if($statbulk == "COMPLETED" && $statpartial == "COMPLETED" ){
                            $statcss = "table-success";
                        }elseif($statbulk == "NEW" && $statpartial == "NEW" ){
                            $statcss = "";
                        }else{
                            $statcss = "table-info";
                        }
                        */

                        if($statbulk == "COMPLETED"  ){
                            $statbulkcss = "table-success";
                        }elseif($statbulk == "NEW" ){
                            $statbulkcss = "";
                        }else{
                            $statbulkcss = "table-info";
                        }

                        if($statpartial == "COMPLETED" ){
                            $statpartialcss = "table-success";
                        }elseif($statpartial == "NEW" ){
                            $statpartialcss = "";
                        }else{
                            $statpartialcss = "table-info";
                        }

                        $myhtml .= sprintf($vv, "" ,$statbulkcss, $statbulk,$statbulkcss,$statbulkcss,$statbulkcss,$statbulkcss,$statbulkcss, $statpartialcss ,$statpartial, $statpartialcss, $statpartialcss, $statpartialcss );
                        #$myhtml .= sprintf($vv,  $statcss ,$statbulk ,$statpartial );
                        #$myhtml .= implode("", $htmlbody[$kk]);
                        foreach( $htmlbody[$kk] as $kkk => $vvv){
                            $myhtml .= sprintf($vvv,$statbulkcss,$statbulkcss,$statbulkcss,$statbulkcss,$statbulkcss,$statbulkcss,$statpartialcss,$statpartialcss,$statpartialcss,$statpartialcss);
                        }
                        
                    }

                    $mystat = array_count_values($fstat);
                    $mystatf  = "";
                    $mystatfcss  = "";

                    if( isset($mystat["COMPLETED"]) && $mystat["COMPLETED"] == count($fstat) ){
                        $mystatf = "COMPLETED";
                        $mystatfcss  = "table-success";
                    }
                    elseif( isset($mystat["NEW"]) && $mystat["NEW"] == count($fstat) ) { 
                        $mystatf = "NEW";
                        $mystatfcss  = "";
                    }else{
                        $mystatf = "PENDING";
                        $mystatfcss  = "table-info";
                    }


                    echo sprintf($v,$mystatfcss,count($batch[$k]), $mystatf). $myhtml;
                }

               
               
            ?>

        </tbody>
    </table>
</div>