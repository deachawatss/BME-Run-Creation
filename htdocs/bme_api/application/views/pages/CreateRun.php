<div class='col-md-12' >
	<div class='box' >
		<div class='box-header' >
			<h4 class='box-title'><i class='fas fa-file'></i>&nbsp;Create Run</h4>
		</div>
		<div class='box-body'>

			<table class='table table-bordered table-sm' style="width:50%;margin:0px auto;">
				<tr>
					<td>Run No:</td>
					<td>
						<input type='text' id='txtrun' readonly class='form-control form-control-sm'/>
						<input type='hidden' id='runid' readonly class='form-control form-control-sm'/>
					</td>
					<td><a id='nRun' class="btn btn-sm btn-success" style='width:100%'>New Run</a></td>
				</tr>
				<tr>
					<td>Formula ID:</td>
					<td>
						<div class="input-group">
							<input type='text' readonly class='form-control form-control-sm'  id='myformulaid'/>
						</div>
					</td>
					<td><a id="btnOpen" class="btn btn-sm btn-info" style='width:100%'>Open Run</a></td>
				</tr>
				<tr>
					<td>Batch Size:</td>
					<td><input type='number' readonly class='form-control form-control-sm' id='mybatchsize'/></td>
					<td><a id="saveRun" class="btn btn-sm btn-success disabled" style='width:100%'>Save</a></td>
				</tr>
				<tr>
					<td>Total Batches:</td>
					<td><input type='number' readonly  class='form-control form-control-sm' id='mytotalbatch'/></td>
					<td><a id="cancelRun" class="btn btn-sm btn-warning disabled" style='width:100%'>Cancel</a></td>
				</tr>
				<tr>
					<td>Total Batches Size:</td>
					<td><input type='number' readonly  class='form-control form-control-sm' id='mytotalbatchsize'/></td>
					<td><a id="deleteRun" class="btn btn-sm btn-danger disabled" style='width:100%'>Delete Run</a></td>
				</tr>
				<tr>
					<td colspan=3>
						<table class="table table-sm table-bordered table-bordred " id='selectedtbl' style='width:100%'>
							<thead class='text-center'>
								<th>No.</th>
								<th>Batch No</th>
								<th>Formula ID</th>
								<th>Batch Size</th>
								<th>Date</th>
							</thead>
						</table>
						<a class="btn btn-sm btn-success disabled" id='btnAdd'><i class="fas fa-plus"></i>&nbsp;Add Batch</a>
					</td>
				</tr>
			</table>

		</div>
	</div>
</div>
