<style>
	.center {
		
		margin: auto;
		width: 40%;
		border: 3px solid green;
	}
</style>
<div class='center'>
	
	<form action="<?php echo base_url();?>changepassword" method="post">
		
		<?php

			if(@$msgno!="")
			echo '
				<div class="alert alert-'.($msgno==1 ? "success" :"danger").'">
					<strong>'.($msgno==1 ? "Success" :"Warning").'!</strong> '.$msg.'.
				</div>';
		
		?>

		<table class="table table-bordered table-sm">
			<tr>
				<td>Current Password</td>
				<td><input type='password' name='current_password' class='form-control'/></td>
			</tr>

			<tr>
				<td>New Password</td>
				<td><input type='password' name='new_password' class='form-control'/></td>
			</tr>

			<tr>
				<td>Confirm Password</td>
				<td><input type='password' name='confirm_password' class='form-control'/></td>
			</tr>

			<tr>
				<td colspan="2">
					<button class="btn btn-outline-success" type="submit">Submit</button>
				</td>
			</tr>

		</table>
	</form>

</div>
