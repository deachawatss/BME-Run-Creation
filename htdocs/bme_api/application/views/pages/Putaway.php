<div class="col-md-12">
	<div class="box">
		<div class="box-header">
			<h4 class="box-title"><i class="fas fa-file"></i>&nbsp;Putaway</h4>
		</div>
		<div class="box-body">
			<table class='table table-sm table-bordered ' style='width:70%;margin:0px auto;'>
				<thead>
				</thead>
				<tbody>
					<tr>
						<td>Lot #</td>
						<td colspan=3>
							<div class="input-group">
								<input type="text" class="form-control" name='lot' id='lot' placeholder="Search">
								<div class="input-group-btn">
								<button class="btn btn-default" id='btn-lotsearch' type="submit">
									<i class="fas fa-search"></i>
								</button>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>Bin #</td><td colspan=3><input id='BinNo' type='text' style='background-color:#ffc10730' readonly/></td>
					</tr>
					<tr>
						<td>ItemKey</td><td colspan=3><input id='ItemKey' type='text' style='background-color:#ffc10730'  readonly/></td>
					</tr>
					<tr>
						<td>Location</td><td><input id='Location' type='text' style='background-color:#ffc10730' readonly/></td><td>UOM</td><td><input id='uom' type='text' style='background-color:#ffc10730' readonly/></td>
					</tr>
					<tr>
						<td>QtyOnHand</td><td><input id='QtyOnHand' type='text' style='background-color:#ffc10730' readonly/></td><td>Qty Available</td><td><input id='qty_available' readonly style='background-color:#ffc10730' type='text' /></td>
					</tr>
					<tr>
						<td>Exp. Date</td><td colspan=3><input id='ExpirationDate' type='text' style='background-color:#ffc10730' readonly/></td>
					</tr>
					<tr>
						<td>Putaway Qty</td><td colspan=3><input id='PutAway_qty' type='number' /></td>
					</tr>
					<tr>
						<td>To Bin #</td><td colspan=3>
							<div class="input-group">
								<input type="text" id='ToBinNo' class="form-control" placeholder="Search">
								<div class="input-group-btn">
								<button class="btn btn-default " type="submit" id="btn-bin-search">
									<i class="fas fa-search"></i>
								</button>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=4> <a class='btn btn-sm btn-outline-success' id='btn-submit-putaway'> <i class="fas fa-paper-plane"></i>&nbsp; Submit</a> </td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type='text/javascript'>

	
</script>