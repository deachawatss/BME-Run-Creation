<div class="col-md-12">
	<div class="box">
		<div class="box-header">
			<h4 class="box-title"><i class="fas fa-file"></i>&nbsp;Putaway</h4>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-2">
					Product Code #
				</div>
				<div class= 'col-md-8'>
							<div class="input-group">
								<input type="text" class="form-control" name='prodcode' id='prodcode' placeholder="Product Code">
								<div class="input-group-btn">
								<button class="btn btn-default" id='btn-lotsearch' type="submit">
									<i class="fas fa-search"></i>
								</button>
								</div>
							</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-2">
				Bin #
				</div>
				<div class= 'col-md-8'>
				<input id='BinNo' type='text' style='background-color:#ffc10730' readonly/>
				</div>
			</div>

			<div class="row">
				<div class="col-md-2">
					ItemKey
				</div>
				<div class= 'col-md-8'>
					<input id='ItemKey' type='text' style='background-color:#ffc10730'  readonly/>
				</div>
			</div>

			<div class="row">
				<div class="col-md-2">
					Lot #
				</div>
				<div class= 'col-md-8'>
					<input id='lotno' type='text' style='background-color:#ffc10730'  readonly/>
				</div>
			</div>

			<div class="row">
				<div class="col-md-2">
					Location	
				</div>
				<div class= 'col-md-4'>
					<input id='Location' type='text' style='background-color:#ffc10730' readonly/>
				</div>
				<div class="col-md-2">
					UOM	
				</div>
				<div class= 'col-md-4'>
					<input id='uom' type='text' style='background-color:#ffc10730' readonly/>
				</div>
			</div>

			<div class="row">

				<div class="col-md-2">
					QtyOnHand
				</div>
				<div class= 'col-md-4'>
				<input id='QtyOnHand' type='text' style='background-color:#ffc10730' readonly/>
				</div>
				<div class="col-md-2">
					Qty Available	
				</div>
				<div class= 'col-md-4'>
					<input id='qty_available' readonly style='background-color:#ffc10730' type='text' />
				</div>
			</div>

			<div class="row">
				<div class="col-md-2">
					Exp. Date
				</div>
				<div class= 'col-md-8'>
					<input id='ExpirationDate' type='text' style='background-color:#ffc10730' readonly/>
				</div>
			</div>

			<div class="row">
				<div class="col-md-2">
					Putaway Qty
				</div>
				<div class= 'col-md-8'>
					<input id='PutAway_qty' type='number' />
				</div>
			</div>

			<div class="row">
				<div class="col-md-2">
					To Bin #
				</div>
				<div class= 'col-md-8'>
						<div class="input-group">
								<input type="text" id='ToBinNo' class="form-control" placeholder="Search">
								<div class="input-group-btn">
								<button class="btn btn-default " type="submit" id="btn-bin-search">
									<i class="fas fa-search"></i>
								</button>
								</div>
						</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12"><a class='btn btn-sm btn-outline-success' id='btn-submit-putaway'> <i class="fas fa-paper-plane"></i>&nbsp; Submit</a></div>
			</div>

		</div>
	</div>
</div>


<script type='text/javascript'>

	
</script>