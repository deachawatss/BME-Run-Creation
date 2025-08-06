<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if ( ! function_exists('genTable')){
		function genTable($param,$tbl="")
		{ 
		
			echo "<table class='table table-sm table-hover'>";
				echo "<thead>";
				foreach($param as $k=>$v ){
					echo "<th>".$v['db']."</th>";
				}
				echo "</thead>";
			echo "</table>";
			
		}
	}
	
	if ( ! function_exists('genTbl')){
		function genTbl($mytbl)
		{
			$CI =& get_instance();
			
			
			$tbl=$CI->models->getColumn($mytbl);
			
			if(!is_array($tbl)){
				$tbl=json_decode(json_encode($tbl),true);
			}

			$mytitle="";
			
			switch(true){

				case(@$CI->ctitle):{
					$mytitle = @$CI->ctitle;
				} 

				case(@$CI->title):{
					$mytitle = @$CI->title;
				} 

				default:{
					$mytitle = "<i class='fas fa-".@$CI->npermission[$CI->pageid]->icon."'></i>&nbsp;".@$CI->npermission[$CI->pageid]->pagetitle;
					break;
				}
			}
			
			$tblstr="<div class='col-md-12' >";
			$tblstr.="<div class='box' >";
			$tblstr.="<div class='box-header' >";
			$tblstr.="<h4 class='box-title'>".@$mytitle."</h4>";
			$tblstr.="</div>";
			
			$tblstr.="<div class='box-body'>";
			
			if(@$CI->npermission[$CI->pageid]->add && @$CI->table){
				$tblstr.= "<a class='btn mb-1 btn-outline-success btn-add-".$CI->table." btn-sm'  ><i class='fas fa-plus'></i> ADD</a>";
			}
			
			$tblstr.="<div id='cbtn'>".$CI->cBtn."</div>";
			$tblstr.="<table width='100%'' id='auto-gen-".$mytbl."' class='table table-hover table-sm table-bordered display nowrap' >";
			$tblstr.="<thead class='text-center'>";
			
			$tblstr.="</thead>";
			$tblstr.="</table>";
			
			if(@$CI->npermission[$CI->pageid]->add && @$CI->table){
				$tblstr.= "<a class='btn btn-outline-success btn-add-".$CI->table."' ><i class='fas fa-plus'></i> ADD</a>";
			}
			
			$tblstr.="</div>";
			$tblstr.="</div>";
			$tblstr.="</div>";
			
			if( @$CI->table)
			$CI->tblJS=genTblJS($tbl,$mytbl);
			
			if(@$CI->npermission[$CI->pageid]->add && @$CI->table)
				$CI->tblJS.=genAddRecord();
			
			if(@$CI->npermission[$CI->pageid]->edit && @$CI->table)
				$CI->tblJS.=genEditRecord();
			
			if(@$CI->npermission[$CI->pageid]->delete && @$CI->table)
				$CI->tblJS.=genDeleteRecord();

			return $tblstr;
		}
	}

	if ( ! function_exists('genTblJS')){
		function genTblJS($mytbl=array(),$tbl="",$order=[ 0=>"desc",1=>"asc"])
		{
			$CI =& get_instance();
			if(!is_array($mytbl)){
				$mytbl=json_decode(json_encode($mytbl),true);
			}

			$pop_updata = [];
			
			$optJS="var column =[ \n";
			
			$CI->jsvar="var dt_".$tbl.";\n";
			
			foreach($mytbl as $k=>$v){
				$data_type="";
				$dec_points=2;
				
				$formatter=array(
					"float"=>"db-number",
					"decimal"=>"db-number",
					"int"=>"db-number",
					"bigint"=>"db-number",
					"smallint"=>"db-number",
					"datetime"=>"datetime",
					"date"=>"",
					"time"=>"",
					"text"=>"",
					"varchar"=>"",
					"char"=>""
				);
				
				if(!isset($v['visible']) ){
					@$v['visible']=true;
				}

				$optJS.="\t\t\t\t\t\t{";
					$optJS.="data: '".$v['db']."',";
					$optJS.="title: '".$v['th']."',";
					$optJS.="visible: ".(@$v['visible']==false ? "false" : "true" ).",";
					
					#Data Type
					$data_type=( @$formatter[$v['type']] !="" ? @$formatter[@$v['type']] : @$v['type'] );
					
					$data_class = @$v['class'];
					
					$optJS.="className: '".$data_class."'";
					
				$optJS.="},\n";

				
			}
				
			$optJS.="\t\t\t\t]; \n\n";
			$CI->addJS=$optJS."\n";	

			$tblorder = [];
			foreach($order as $k=>$v){
				$tblorder[] = "[".$k." , '".$v."']";
			}

			
			
			$CI->addJS.="
						var myurl = '".$_SERVER['REQUEST_URI']."';
						APP.Datatable.defaultOpts.url=\"".current_url()."/dataTable\";
						APP.Datatable.defaultOpts.column=column;
						APP.Datatable.defaultOpts.order=[". implode(',',$tblorder) ."];
						dt_".$tbl." = APP.Datatable.init('auto-gen-".$tbl."');"."\n
						APP.Forms.defaultOpts.frm = dt_".$tbl.";
						";	
						
		}
	}
	
	if(! function_exists('genForm')){
		function genForm($mytbl=array()){
			$CI =& get_instance();
			$frm=[];
			$ref=[];
			$pop_updata = [];
			$noshow=[
				$CI->pkey,
				"created_at",
				"enter_by"
			];
			
			$mdata = [];

			foreach($mytbl as $k => $v ){
				$mm= explode(".",$v['db'])[1];
				
				if(isset($v['odb'])){
					$mm = $v['odb'];
				}
				
				
				if(!in_array($mm,$noshow))
				$frm[]=[
					"db"=>$mm,
					"th"=>$v['th'],
					"type" => @$v['type'],
					"ref" => @$v['ref'],
					"req"=>intval((isset($v['req']) ?? "0")),
					"readonly"=>intval(isset($v['readonly']) ?? "0"),
					"id"=>$mm,
					"label"=>$v['th'],
					"required"=>(isset($v['req']) ?? "0"),
					"btn_textClick" => @$v['btnSearch'],
					"val"=>@$v["value"],
					"onchange"=>@$v['onchange'],
					"maxsize"=>(isset($v['maxsize']) ? $v['maxsize'] : "0"),
					/*
					"trigger",
					"textType",
					"containerClass"
					*/
				];

				/*
				if(isset($v['type']) && $v['type'] == 'pop_up'){
					echo "<pre>";
					print_r($mytbl);
					die();
				}
				
				

				if( isset($v['ref']) ){

					if(is_string($v['ref']))
						$v['ref'] = json_decode($v['ref'],1);

					if(is_object($v['ref'])){
						$v['ref'] = json_decode(json_encode($v['ref']),1);
					}

					

					$mdata[$mm][''] = '';
					foreach( $v['ref'] as $k => $v){
						if(is_object($v))
							$mdata[$mm][$v->id] = $v->text;
						elseif(is_array($v))
							$mdata[$mm][$v['id']] = $v["text"];
						#else
						#	$mdata[$mm][$v['id']] = $v["text"];
					}
				}
				*/

				//$pop_updata
				if( isset($v['ajax']) ){
					
					foreach( $v['ajax']['display'] as $kk => $vv){
						$pop_updata[ $mm ][] = [
							"title" => $kk,
							"data" => $vv,
						];
					}
					
				}
			}

			
			
			$CI->addJS.="					
						var pop_updata = ".json_encode($pop_updata).";
						var mypopup_ref = ".json_encode($mdata).";";
			
			return $frm;
		}
	}


	if(! function_exists('genFormAdd')){
		function genFormAdd($mytbl=array()){
			$CI =& get_instance();
			$frm=[];
			$ref=[];

			$defaultvalue = [];
			
			$noshow=[
				$CI->pkey,
				"created_at",
				"enter_by"
			];
			
			foreach($mytbl as $k => $v ){
				$mm= explode(".",$v['db'])[1];
				
				if(isset($v['odb'])){
					$mm = $v['odb'];
				}
				
				if(@$v['noAdd']=="")
					if(!in_array($mm,$noshow))
					$frm[]=[
						"db"=>$mm,
						"th"=>$v['th'],
						"type" => @$v['type'],
						"ref" => @$v['ref'],
						//"req"=>intval((isset($v['req']) ?? "0")),
						"req"=>( (isset($v['req']) && $v['req'] == 1) ? 1 :0 ),
						"readonly"=>(@$v['readonly'] == "" ? false : true),
						"disabled"=>(@$v['readonly'] == "" ? false : true),
						"id"=>$mm,
						"label"=>$v['th'],
						"required"=>(isset($v['req']) ?? "0"),
						"btn_textClick" => @$v['btnSearch'],
						"val"=>@$v["value"],
						"maxsize"=>(isset($v['maxsize']) ? $v['maxsize'] : "0"),
						"filter"=>(isset($v['filter']) ? $v['filter'] : ""),
						"others"=>(isset($v['others']) ? $v['others'] : ""),
						"row"=>$k,
						"pop_up_callback" => (isset($v['pop_up_callback']) ? $v['pop_up_callback'] : "")
						/*
						"trigger",
						"textType",
						"containerClass"
						*/
					];
				

				if(isset($v["value"]))
					$defaultvalue["data"][$mm]= $v["value"];
				
			}

			$CI =& get_instance();
			$CI->addJS.="var in_add_default".$CI->table." = ".json_encode($defaultvalue)."; \n";

			
			return $frm;
		}
	}

	if(! function_exists('genFormEdit')){
		function genFormEdit($mytbl=array()){
			$CI =& get_instance();
			$frm=[];
			$ref=[];
			
			$noshow=[
				$CI->pkey,
				"created_at",
				"enter_by"
			];
			
			foreach($mytbl as $k => $v ){
				$mm= explode(".",$v['db'])[1];
				
				if(isset($v['odb'])){
					$mm = $v['odb'];
				}
				
				if(@$v['noEdit']=="")
					if(!in_array($mm,$noshow))
					$frm[]=[
						"db"=>$mm,
						"th"=>$v['th'],
						"type" => @$v['type'],
						"ref" => @$v['ref'],
						#"req"=>intval((isset($v['req']) ?? "0")),
						"req"=>( (isset($v['req']) && $v['req'] == 1) ? 1 :0 ),
						"readonly"=>(@$v['readonly'] == "" ? false : true),
						"disabled"=>(@$v['readonly'] == "" ? false : true),
						"id"=>$mm,
						"label"=>$v['th'],
						"required"=>(isset($v['req']) ?? "0"),
						"btn_textClick" => @$v['btnSearch'],
						"val"=>@$v["value"],
						"maxsize"=>(isset($v['maxsize']) ? $v['maxsize'] : "0"),
						"filter"=>(isset($v['filter']) ? $v['filter'] : ""),
						"row"=>$k,
						"pop_up_callback" => (isset($v['pop_up_callback']) ? $v['pop_up_callback'] : "")
						/*
						"trigger",
						"textType",
						"containerClass"
						*/
					];
				
			}
			
			return $frm;
		}
	}
	
	if(! function_exists('genAddRecord')){
		function genAddRecord(){
			$CI =& get_instance();
			#$url=base_url().$CI->router->fetch_class()."/addData";
			$url=base_url().$CI->npermission[$CI->pageid]->pagename."/addData";
			$tbl=genFormAdd($CI->models->getColumn($CI->table));
			$CI->addJS.="var in_add_".$CI->table." = ".json_encode($tbl)."; \n";
			$CI->frmJS="APP.Forms.defaultOpts.frmAddFields = ".json_encode($tbl)."; \n";
			
			return "
				$('.btn-add-".$CI->table."').click(function(event){
					APP.Forms.defaultOpts.frm = dt_".$CI->table.";
					APP.Forms.defaultOpts.selector = $(this);
					APP.Forms.defaultOpts.frmAddValue = in_add_".$CI->table." ;
					APP.Forms.createForm('add','".$url."'); \n
				});";
				
			
		}
	}
	
	if(! function_exists('genEditRecord')){
	
		function genEditRecord(){
			$CI =& get_instance();
			
			$urlgetinfo=base_url().$CI->npermission[$CI->pageid]->pagename."/getInfo";
			#$urlgetinfo=base_url().$CI->router->fetch_class()."/getInfo";
			$url=base_url().$CI->npermission[$CI->pageid]->pagename."/updateData";
			#$url=base_url().$CI->router->fetch_class()."/updateData";

			$tbl=genFormEdit($CI->models->getColumn($CI->table));
			$CI->addJS.="var in_edit_".$CI->table." = ".json_encode($tbl)."; \n";
			$CI->frmJS.="APP.Forms.defaultOpts.frmEditFields = ".json_encode($tbl)."; \n";
			$CI->frmJS.="var frmGetInfo = '".$urlgetinfo."'; \n";
			$CI->frmJS.="var frmUpdateUrl = '".$url."'; \n";
			

			return "\n\t\t\t\t $(document).on('click','.btn-edit-".$CI->table."',function(event){

					var vdata=$(this).attr('data-id');\n
					APP.Forms.defaultOpts.frm = dt_".$CI->table.";
					APP.Forms.defaultOpts.selector = $(this);
					$.post('".$urlgetinfo."',{id:vdata},function(xres){
						APP.Forms.defaultOpts.frmEditValue = xres;
						APP.Forms.createForm('edit','".$url."'); \n
					},'json');

				});";

		
		}
	
	}
	
	if(! function_exists('genDeleteRecord')){
		
		function genDeleteRecord(){
			$CI =& get_instance();
			$url=base_url().$CI->npermission[$CI->pageid]->pagename."/deleteData";
			#$url=base_url().$CI->router->fetch_class()."/deleteData";
			$tbl=genForm($CI->models->getColumn($CI->table));
			
			return "
				APP.Forms.defaultOpts.frmDeleteAction = '".$url."';
				APP.Forms.defaultOpts.frm = dt_".$CI->table.";
				$(document).on('click','.btn-delete-".$CI->table."',function(){
					var vdata=$(this).attr('data-id');\n
					Swal.fire({
						title: 'Are you sure?',
						text: 'You won\'t be able to revert this!',
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, delete it!'
					}).then((result) => {
						if (result.isConfirmed) {
							$.ajax({
								type: 'POST',
								url: APP.Forms.defaultOpts.frmDeleteAction,
								data: {id:vdata},
								//use contentType, processData for sure.
								dataType: 'json',
								beforeSend: function() {
									
								},
								success: function(msg) {

									Swal.fire(
										'Your file has been deleted.',
										'',
										'success'
									);
									APP.Datatable.reDraw(APP.Forms.defaultOpts.frm);
									//dt_tbl_user.ajax.reload(null, false);
									//dt_tbl_user._fnReDraw;
									//APP.Datatable.
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
					});
				});";
		}

	}
	
	

?>
