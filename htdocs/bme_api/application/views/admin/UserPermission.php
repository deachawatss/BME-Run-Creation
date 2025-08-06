		<div class="col-md-12">
			<div class="box">
				<div class="box-header">
					<h4 class="box-title"><?php echo $title; ?></h4>
				</div>
				<div class="box-body">
					
					<form action="<?php echo base_url();?>UserPermission?id=<?php echo $this->input->get('id'); ?>" method="POST">
					<table class='table table-sm table-bordered'>
					<thead class='text-center'>
						<th class='text-center'>Page</th>
						<th>LIST <input id="clist" onchange="tgl(this,'clist')" type='checkbox'/></th>
						<th>ADD <input id="cadd" onchange="tgl(this,'cadd')" type='checkbox'/></th>
						<th>EDIT <input id="cedit" onchange="tgl(this,'cedit')" type='checkbox'/></th>
						<th>DELETE <input id="cdel" onchange="tgl(this,'cdel')" type='checkbox'/></th>
					</thead>
					<?php
						
					# print_r($myaccess);
						function genStr($arr=array(),$myaccess=array(),$strx=""){
						
						
							$str="";
						
								foreach($arr as $k=>$v){
										$str.="<tr>";
										$str.="<td>".$strx.@$v["pagetitle"]."<input type='hidden' value='".@$v["pageid"]."' name='permission[]'></td>";
										$str.="<td><center><input name='list[]' class='clist' value='".@$v["pageid"]."' type='checkbox' ".( intval(@$myaccess[$v["pageid"]]->list)==0 ?"":"checked='checked'" )." /></center></td>";
										$str.="<td><center><input name='add[]' class='cadd' value='".@$v["pageid"]."' type='checkbox' ".( intval(@$myaccess[$v["pageid"]]->add)==0 ?"":"checked='checked'" )." /></center></td>";
										$str.="<td><center><input name='edit[]' class='cedit' value='".@$v["pageid"]."' type='checkbox' ".( intval(@$myaccess[$v["pageid"]]->edit)==0 ?"":"checked='checked'" )." /></center></td>";
										$str.="<td><center><input name='delete[]' class='cdel' value='".@$v["pageid"]."' type='checkbox' ".( intval(@$myaccess[$v["pageid"]]->delete)==0 ?"":"checked='checked'" )." /></center></td>";
										$str.="</tr>";
										
										if( key_exists("submenu",$v) ){
												$str.=genStr($v['submenu'],$myaccess,$strx."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
										}
										
								
								}
						
							return $str;
						
						}
						
						$tbl=genStr($menu_array,$myaccess);
						
						echo $tbl;

					?>

					</table>
					<button type='submit' class="btn  btn-outline-success" value="submit" name='submit'><i class="fas fa-save"></i>&nbsp;SAVE</button>
					<a class="btn  btn-outline-danger" href='<?php echo base_url();?>UserLevel'><i class="fas fa-times-circle"></i>&nbsp;Cancel</a>
					</form>
				</div>
			</div>
		</div>
<script>

   function tgl(e,f){
        var val=$(e).is(":checked");

        if(!val){
            $("."+f).prop("checked",false);
        }else{
            $("."+f).prop("checked",true);
        }

    };

		<?php

			
			if(@$_SESSION["msg"]!=""){
				
				 @$this->tblJS= "Swal.fire(
					'Successfully Updated!.',
					'',
					'success'
					);
				";
				unset($_SESSION["msg"]);
			}

		?>
                                
	

</script>
