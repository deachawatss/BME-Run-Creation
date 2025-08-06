<style>
	.progress span {
		position:absolute;
		left:0;
		width:100%;
		text-align:center;
		z-index:2;
		color: black;
	}
</style>
<div class="col-md-12">
	<div class="box">
		<div class="box-header">
			<h4 class="box-title"><i class="fas fa-file"></i>&nbsp;Putaway</h4>
		</div>
		<div class="box-body">
			
			
			<br/>

			<div class="progress" style='height:80px'>
				<div class="progress-bar progress-bar-striped progress-bar-animated" id='wbar' role="progressbar" aria-label="Animated striped example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
				<span class='mt-4'><h2 id='lbar'>0</h2></span>
			</div>
			
			<table class='table table-sm table-bordered'>
				<tr>
					<td>Current Weight</td>
					<td><input type='number' id='mydata' /></td>
				</tr>

				<tr>
					<td>To Weight</td>
					<td><input type='number' id='tofill' /></td>
				</tr>

				<tr>
					<td>Max Weight</td>
					<td><input type='number' id='maxw' /></td>
				</tr>

				<tr>
					<td>Min Weight</td>
					<td><input type='number' id='minw' /></td>
				</tr>
			</table>
			
			<a class='btn btn-outline-success' id='btnid' > Button </a>

		</div>
	</div>
</div>