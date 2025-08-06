var formulaID;
var batchsize;
var totalbatchsize = 0;
var mytotalbatch = 0;
var selected = {};
var selectedtbl2 = {};
var tbl2data = [];
var opendiag;
var isSave;
var isenable = true;

var column =[ 
    {data: 'BatchNo',title: 'Action',visible: true,className: ''},
    {data: 'BatchNo',title: 'Batch No',visible: true,className: ''},
    {data: 'FormulaId',title: 'Formula ID',visible: true,className: ''},
    {data: 'BatchWeight',title: 'Batch Weigth',visible: true,className: 'db-number'},
    {data: 'BatchTicketDate',title: 'Date',visible: true,className: 'date'},
    ]; 

var tbl1;
var tbl2;
var tbl3;

function createNewPopUp(){
    var mdiag = bootbox.dialog({
        'title' : "New Preweigh Run",
        'size'  : 'lg',
        'message': "<div id='mrun'> <table id='blist' class='table table-sm table-hovered' style='width:100%'></table> </div>",
        onShown  :   function(e){
            APP.Datatable.defaultOpts.url="CreateRun/gereftList";
			APP.Datatable.defaultOpts.column=column;
			APP.Datatable.defaultOpts.options.responsive=true;
			APP.Datatable.defaultOpts.options.pageLength=20;
			APP.Datatable.defaultOpts.options.serverSide=false;
			APP.Datatable.defaultOpts.options.ajax="CreateRun/gereftList";
			APP.Datatable.defaultOpts.options.columnDefs=[
                {
					targets: [0],
					className: 'text-center ',
					render: function (data, type, row) {
							return "<input type='checkbox' class='form-check-input chkbatch' data-formulaid='"+row.FormulaId+"' data-bsize='"+(row.BatchWeight).toFixed(4)+"' data-info='"+JSON.stringify(row)+"'></input>"; 
					}
				},
                {
					targets: [3],
					className: 'text-center ',
					render: function (data) {
					
						return data.toFixed(4); 
					}
				},
                {
					"targets": [4],
					className: 'text-center ',
					"render": function (data) {
						if (!moment(data).isValid()) {
							return '';
						}

						return moment(data).format('MM-DD-YYYY'); 
					}
				},
            ];
			tbl1 = APP.Datatable.init('blist');

        },
		'buttons' : {
			confirm: {
				label: 'ADD',
				className: 'btn-success',
				callback: function(){
					totalbatchsize = 0;
					selectedtbl2 = selected;
					tbl2data = [];
					mytotalbatch = 0;
					$.each(selected,function(k,v){
						tbl2data.push(v);
						totalbatchsize += v.BatchWeight;
						mytotalbatch++;
					});
					createTbl2();
					$("#mytotalbatchsize").val(totalbatchsize);
					$("#mytotalbatch").val(mytotalbatch);
					$("#saveRun").removeClass('disabled');
					$("#cancelRun").removeClass('disabled');
					$("#btnAdd").removeClass('disabled');
					$("#nRun").addClass('disabled');
					$("#btnOpen").addClass('disabled');
					isSave = 'A';
				}
			},
			cancel: {
				label: 'CANCEL',
				className: 'btn-danger',
				callback: function(){
					
				}
			}
		}
    });

	
}

function createPopUp(){
    var mdiag = bootbox.dialog({
        'title' : "New Preweigh Run",
        'size'  : 'lg',
        'message': "<div id='mrun'> <table id='blist' class='table table-sm table-hovered' style='width:100%'></table> </div>",
        onShown  :   function(e){
            APP.Datatable.defaultOpts.url="CreateRun/gereftList";
			APP.Datatable.defaultOpts.column=column;
			APP.Datatable.defaultOpts.options.responsive=true;
			APP.Datatable.defaultOpts.options.pageLength=20;
			APP.Datatable.defaultOpts.options.serverSide=false;
			APP.Datatable.defaultOpts.options.ajax="CreateRun/gereftList";
			APP.Datatable.defaultOpts.options.columnDefs=[
                {
					targets: [0],
					className: 'text-center ',
					render: function (data, type, row) {
						if( selectedtbl2[row.BatchNo] != undefined)
							return "<input type='checkbox' checked class='form-check-input chkbatch' data-formulaid='"+row.FormulaId+"' data-bsize='"+(row.BatchWeight).toFixed(4)+"' data-info='"+JSON.stringify(row)+"'></input>"; 
						else
							return "<input type='checkbox' class='form-check-input chkbatch' data-formulaid='"+row.FormulaId+"' data-bsize='"+(row.BatchWeight).toFixed(4)+"' data-info='"+JSON.stringify(row)+"'></input>"; 
					}
				},
                {
					targets: [3],
					className: 'text-center ',
					render: function (data) {
					
						return data.toFixed(4); 
					}
				},
                {
					"targets": [4],
					className: 'text-center ',
					"render": function (data) {
						if (!moment(data).isValid()) {
							return '';
						}

						return moment(data).format('MM-DD-YYYY'); 
					}
				},
            ];
			tbl1 = APP.Datatable.init('blist');

			if(Object.keys(selectedtbl2).length == 0){
				tbl1.columns('').search( '' ).draw();
			}else{
				
				
				tbl1.columns(3).search(tbl2data[0].BatchWeight,true);
				tbl1.columns(2).search(tbl2data[0].FormulaId,true).draw();
				
				
			}
        },
		'buttons' : {
			confirm: {
				label: 'ADD',
				className: 'btn-success',
				callback: function(){
					totalbatchsize = 0;
					selectedtbl2 = selected;
					tbl2data = [];
					mytotalbatch = 0;
					$.each(selected,function(k,v){
						tbl2data.push(v);
						totalbatchsize += v.BatchWeight;
						mytotalbatch++;
					});
					createTbl2();
					$("#mytotalbatchsize").val(totalbatchsize);
					$("#mytotalbatch").val(mytotalbatch);
					$("#saveRun").removeClass('disabled');
					$("#cancelRun").removeClass('disabled');
					$("#btnAdd").removeClass('disabled');
					$("#nRun").addClass('disabled');
					$("#btnOpen").addClass('disabled');
				}
			},
			cancel: {
				label: 'CANCEL',
				className: 'btn-danger',
				callback: function(){
					
				}
			}
		}
    });

	
}

$(document).on('click','#nRun',function(){
    createNewPopUp();
	
});

$(document).on('click','#cancelRun',function(){
	resetbtn();
});

$(document).on('change','.chkbatch',function(){
	var param = $(this).data('info');
	batchsize = $(this).data('bsize');
	formulaID = $(this).data('formulaid');

	$("#myformulaid").val(formulaID);
	$("#mybatchsize").val(batchsize);

	if( this.checked ){
		selected[param.BatchNo] = param;
    }else{
		delete selected[param.BatchNo];
	}
    
	if(Object.keys(selected).length == 0){
		tbl1.columns('').search( '' ).draw();
	}else{
		if(Object.keys(selected).length == 1){
			tbl1.columns(3).search(batchsize,true);
			tbl1.columns(2).search(formulaID,true).draw();
		}
		
	}
	
    

});

function createTbl2(){

	isenable = true;

	var myf = tbl2data.filter( function(currentValue, index, arr){

		if(currentValue.Status != undefined && currentValue.Status != 'R'){
			return true;
		}else{
			return false;
		}

	});

	if(myf.length > 0){
		isenable = false;
	}

	

	tbl2 = $("#selectedtbl").DataTable({
		processing: true,
		pageLength: 10,
		lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
		destroy: true,
		data:tbl2data,
		"scrollX": true,
		columnDefs:[
			{
				targets: [0],
				className: 'text-center ',
				render: function (data, type, row) {

					if(isenable)
						return "<a class='btn btn-sm btn-danger btnrem' data-id='"+data+"'><i class='fas fa-trash'></i></a>"; 
					else
						return '';
				}
			},
			{
				targets: [3],
				className: 'text-center ',
				render: function (data) {
				
					return data.toFixed(4); 
				}
			},
			{
				"targets": [4],
				className: 'text-center ',
				"render": function (data) {
					if (!moment(data).isValid()) {
						return '';
					}

					return moment(data).format('MM-DD-YYYY'); 
				}
			},
		],
		columns:column
	});

}

function resetbtn(){
	$("#nRun").removeClass('disabled');
	$("#btnOpen").removeClass('disabled');
	$("#saveRun").addClass('disabled');
	$("#cancelRun").addClass('disabled');
	$("#btnAdd").addClass('disabled');
	$("#deleteRun").addClass('disabled');
	$("#txtrun").val('');
	$("#myformulaid").val('');
	$("#mybatchsize").val('');
	$("#mytotalbatchsize").val('');
	$("#mytotalbatch").val('');
	$("#runid").val('');
	tbl2data = [];
	formulaID = '';
	batchsize = '';
	totalbatchsize = 0;
	selected = {};
	selectedtbl2 = {};
	mytotalbatch = 0;
	createTbl2();
}

$(document).on('click','.btnrem',function(){
	tbl2.row($(this).closest('tr')).remove().draw();
});

$(document).ready(function(){

	createTbl2();
	resetbtn();

});

$(document).on('click',"#btnAdd",function(){
	createPopUp();
});

$(document).on('click','#saveRun',function(){
	var param = {
		list : tbl2.rows().data().toArray(),
		type : isSave,
		runid: $("#runid").val(),
		runno: $("#txtrun").val()
	};

	$.post('CreateRun/saverun',param,function(res){

		if(res.msgno == "100"){
			Swal.fire(
				res.msg,
				'',
				'success'
			);
		}else{
			Swal.fire(
				res.msg,
				'',
				'warning'
			);
		}
		
		resetbtn();

	},"json");

});

function createOpenPopUp(){

	opendiag = bootbox.dialog({
        'title' : "Open Preweigh Run",
        'size'  : 'lg',
        'message': "<div id='mrun'> <table id='olist' class='table table-sm table-hovered' style='width:100%'></table> </div>",
		onShown  :   function(e){
			var xcolumn =[ 
				{data: 'tbl_runhrd.runid',title: 'Action',visible: false,className: ''},
				{data: 'tbl_runhrd.runno',title: 'Run No',visible: true,className: ''},
				{data: 'tbl_runhrd.formulaid',title: 'Formula ID',visible: true,className: ''},
				{data: 'tbl_runhrd.batchsize',title: 'Batch Weigth',visible: true,className: 'db-number'},
				{data: 'tbl_runhrd.created_at',title: 'Date',visible: true,className: 'date'},
				]; 

			tbl3 = $("#olist").DataTable({
				processing: true,
				serverSide: true,
				pageLength: 10,
				columns:xcolumn,
				lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
				destroy: true,
				ajax:"CreateRun/getOpen",
				"scrollX": true,
				columnDefs:[
					{
						targets: [3],
						className: 'text-center ',
						render: function (data) {
						
							return data; 
						}
					},
					{
						"targets": [4],
						className: 'text-center ',
						"render": function (data) {
							if (!moment(data).isValid()) {
								return '';
							}

							return moment(data).format('MM-DD-YYYY'); 
						}
					},
				],
				
			});

        },
		'buttons' : {
			confirm: {
				label: 'ADD',
				className: 'btn-success',
				callback: function(){
					
				}
			},
			cancel: {
				label: 'CANCEL',
				className: 'btn-danger',
				callback: function(){
					
				}
			}
		}
	});

}

$(document).on('click',"#btnOpen",function(){
	createOpenPopUp();
});

$(document).on('click',"#olist tr",function(){
	var mydata = tbl3.row(this).data().tbl_runhrd;

	opendiag.modal('hide');
	$("#txtrun").val(mydata.runno);
	$("#myformulaid").val(mydata.formulaid);
	$("#mybatchsize").val(mydata.batchsize);
	$("#runid").val(mydata.runid);

	$.post('Createrun/getOpenList',{runid:mydata.runid},function(res){
		tbl2data = res.mydata;
		selectedtbl2 = res.mdata;
		createTbl2();
		$("#mytotalbatch").val(res.totalbatch);
		$("#mytotalbatchsize").val(res.totalsize);
		
		if( (res.mydata).length ){
			$("#saveRun").removeClass('disabled');
			$("#cancelRun").removeClass('disabled');
			isSave = 'U';
			$("#btnAdd").removeClass('disabled');

			var myf = tbl2data.filter( function(currentValue, index, arr){

				if(currentValue.Status != undefined && currentValue.Status != 'R'){
					return true;
				}else{
					return false;
				}
		
			});
		
			if(myf.length == 0){
				$("#deleteRun").removeClass('disabled');
			}
		}

	},'json');

});

$(document).on('click','#deleteRun',function(){

	$.post('Createrun/remdata',{runid:$("#runid").val() , runno:$("#txtrun").val()},function(res){
		Swal.fire(
			res.msg,
			'',
			'success'
		);

		resetbtn();
	},'json');

});