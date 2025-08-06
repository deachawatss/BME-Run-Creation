<div class="col-md-12">
	<div class="box">
		<div class="box-header">
			<h4 class="box-title"><i class="fas fa-file"></i>&nbsp;RM Label</h4>
		</div>
		<div class="box-body">
		<!--
			<table class='table table-sm table-bordered ' style='width:100%;margin:0px auto;'>
				<tr>
					<td>Receipt Document #</td>
					<td colspan=3>
						
					</td>
				</tr>
			</table>
		-->
			<div class="container text-center">
				<div class="row">
					
					<div class="col align-self-center">
					
						<div class="input-group">
							<input type="text" class="form-control" name='receiptdoc' id='receiptdoc' placeholder="Receipt Document #">
							<div class="input-group-btn">
							<button class="btn btn-default" id='btn-lotsearch' type="submit">
								<i class="fas fa-search"></i>
							</button>
							</div>
						</div>

					</div>
				</div>
				<div class="row">
					
					<div class="col align-self-center">
						<a class='btn btn-sm btn-outline-success' id='btn-print'> <i class="fas fa-print"></i>&nbsp; Print</a> 
					</div>

				</div>
			</div>
			<table class='table table-sm table-bordered ' style='width:70%;margin:0px auto;'>
				<tr>
					<td>Description</td><td colspan=3>
						<input id='desc1' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/>
						<input id='LotTranNo' type='hidden'/>
					</td>
				</tr>
				<tr>
					<td>ItemKey</td><td><input id='ItemKey' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
					<td>Lot No</td><td><input id='lotno' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
				</tr>
				<tr>
					<td>Vendor Name</td><td><input id='vendname' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
					<td>Vendor Lot No</td><td><input id='vendlotno' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
				</tr>
				<tr>
					<td>PR No</td><td><input id='prno' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
					<td>Date Expiry</td><td><input id='date_exp' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
				</tr>
				<tr>
					<td>Qty Received</td><td><input id='qty_rec' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
					<td>Allergen</td><td><input id='allergen' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
				</tr>
				<tr>
					<td>Pack Type</td><td><input id='pack_type' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
					<td>Pack Size</td><td><input id='pack_size' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
				</tr>
				<tr>
					<td>QC Status</td><td><input id='qc_status' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
					<td>Tested By</td><td><input id='tested_by' type='text' class='form-control form-control-sm' style='background-color:#ffc10730'  readonly/></td>
				</tr>
			</table>
		</div>
	</div>
</div>
