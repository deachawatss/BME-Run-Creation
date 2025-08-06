<div class="col-md-12" style="overflow:scroll">
	<table class="table table-sm table-bordered table-hover" style="white-space: nowrap;overflow:scroll;background-color:white">
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
			<th>Picked</th>
			<th>Partial Picking</th>
			<th>Item Key</th>
			<th>Qty Required</th>
			<th>Picked</th>
		</thead>
		<tbody>
			<?php
			
			foreach($tbl as $k => $v){
				$myfstat = $v->fmystat;
				$mystat = "";
				$fscounts = array_count_values($myfstat);
				$fscount = isset($fscounts["Ready"]) ? $fscounts["Ready"] : 0;
				$fscountp = isset($fscounts["For Completion"]) ? $fscounts["For Completion"] : 0;
				$mysample = "";
				switch(true){

					case ( count($myfstat) == $fscount):{
						$mystat = "Ready";
						$mysample = "success";
						break;
					}

					case ($fscountp!= 0):{
						$mystat = "For Completion";
						$mysample = "warning";
						break;
					}

					default:{
						$mystat = "New";
						$mysample= "info";
							break;
						}
				}

				$bypass = ['23000001','23000004','23000005'];
				

				$mysample =( in_array($v->runno,$bypass)  ? "success" : $mysample)
			?>
				<tr class="rowrun table-<?= $mysample?>" data-runno="<?= $v->runno?> " style=" cursor: pointer;" >
					<td><?= $v->runno ?></td>
					<td></td>
					<td><?= $v->formulaid ?></td>
					<td><?= count($r1[$v->runno]) ?></td>
					<td><?= $v->batchsize ?></td>
					<td><?= $v->BatchTicketDate ?></td>
					<td><?= ( in_array($v->runno,$bypass)  ? "Ready" :  $mystat) ?></td>
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

				<?php 
					foreach($r1[$v->runno] as $kk => $vv){
				?>

				<tr class="batchrow runno-<?= $v->runno?>" data-batch="<?= $vv->batchno?>" style=" cursor: pointer;">
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?= $vv->batchno ?></td>
					<td><?= (  in_array($v->runno,$bypass)  ? "Ready" : $vv->bstat)  ?></td> <!-- Bulk Picking / Status -->
					<td></td> <!-- Item Key -->
					<td></td> <!-- Qty Required -->
					<td></td> <!-- Picked -->
					<td><?= (  in_array($v->runno,$bypass)  ? "Ready" : $vv->bstat) ?></td> <!-- Partial Picking / Status -->
					<td></td> <!-- Item Key -->
					<td></td> <!-- Qty Required -->
					<td></td> <!-- Picked -->
				</tr>


					<?php 
						foreach($r2[$vv->batchno] as $kkk => $vvv){
					?>

					<tr class="pickrow batch-<?= $vv->batchno?>">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td> <!-- Bulk Picking / Status -->
						<td><?= $vvv->ItemKey ?></td> <!-- Item Key -->
						<td><?= $vvv->Bulk ?></td> <!-- Qty Required -->
						<td><?= ( in_array($v->runno,$bypass) ?  $vvv->Bulk : ($vvv->pbulk ? ($vvv->pbulk / $vvv->featurevalue) : "0" ) ) ?></td> <!-- Picked -->
						<td></td> <!-- Partial Picking / Status -->
						<td><?= $vvv->ItemKey ?></td> <!-- Item Key -->
						<td><?= $vvv->PartialData ?></td> <!-- Qty Required -->
						<td><?=  ( in_array($v->runno,$bypass)  ? $vvv->PartialData : $vvv->ppartial) ?></td> <!-- Picked -->
					</tr>

					<?php		
						}
					?>

				<?php		
					}
				?>
			<?php
			}
			?>
		</tbody>
	</table>
</div>