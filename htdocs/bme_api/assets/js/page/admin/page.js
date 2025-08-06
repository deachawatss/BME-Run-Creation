$(document).on("click",".btn-create-tbl_page",function(){
	var myid=$(this).attr("data-id");
	
	html="<form id='autoFrm'><table class='table table-sm table-bordered'>";
	html+="<tr><td>Javascript</td><td> ";
	html+="<input id='js_no' type='radio' name='tbl_js' value='0' checked/>&nbsp;<label for='js_no'>No</label>&nbsp;&nbsp;&nbsp;";
	html+="<input id='js_yes' type='radio' name='tbl_js' value='1' />&nbsp;<label for='js_yes'>Yes</label>";
	html+="</td></tr>";
	html+="<tr><td>Table</td><td><input type='text' placeholder='Table Name' name='tbl_name' class='form-control' /></td></tr>";
	html+="<tr><td>Table ID</td><td><input type='text' placeholder='Table ID' name='tbl_id' class='form-control' /></td></tr>";
	html+="</table></form>";

	var daig = bootbox.dialog({
		title:"Create Page",
		message:html,
		buttons:{
			save: {
				label: '<i class="fas fa-save"></i> Save',
				className: 'btn-outline-success',
				callback: function(){
					var formData = new FormData($('#autoFrm')[0]);
					formData.append("pageid",myid);
					$.ajax({
						type: "POST",
						url: 'page/genpage',
						data: formData,
						contentType: false,
						processData: false,
						dataType: "json",
						beforeSend: function() {
							
						},
						success: function(msg) {

							Swal.fire(
								'Successfully Created',
								'',
								'success'
							);
							
						},
						error: function(msg) {
							Swal.fire(
								'Oppps Something went wrong, Please contact the system Adminstrator',
								'',
								'error'
							);
						}
					});


				}
			},
			cancel: {
				label: '<i class="fas fa-window-close"></i> Cancel',
				className: 'btn-outline-danger',
				callback: function(){
									
				}
			},
		}
	});

});
