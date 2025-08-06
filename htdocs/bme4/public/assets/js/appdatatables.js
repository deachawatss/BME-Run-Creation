/**
* APP - Datatables
* - Generic listeners related to user Datatable
*/
var APP = APP || {};
var _ALLFIELDS = [];

(function () {
    APP.Datatable = {

		defaultOpts: {
            column: null,
			list: [],
			url: null,
			options: {
					processing: true,
					serverSide: true,
					//responsive: false,
					pageLength: 10,
					lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
					destroy: true,
					"scrollX": true,
					columnDefs:[]
			},
			dom: 'Blfrtip',
			buttons: [
						'copy', 'csv', 'excel', 'pdf', 'print'
			],
			order: [ ],
			
        },

        init: function (_tableId,_options,_extras) {
		 
			var url= {
				ajax:{
						"url":APP.Datatable.defaultOpts.url,
						"type":'POST'
					}
				};

		   var _opt= $.extend(true,  APP.Datatable.defaultOpts.options, _options, url, {columns:APP.Datatable.defaultOpts.column}); 

		  
           return $("#"+_tableId).DataTable(_opt);

        },

		reDraw: function(_tableId){
			_tableId.ajax.reload(null, false);
		},

		/**
         * @function APP.Datatable.getColDefs
         * - Get Column Defaults 
         *
         */
		getColDefs: function(){

			var columnDefs=[
				{
					targets: 'no-sort',
					orderable: false,
				},
				{
					targets: 'hidden',
					visible: false,
				},
				{
					"targets": ['date'],
					"render": function (data) {
						console.log('hey');
						if (!moment(data).isValid()) {
							return '';
						}

						return moment(data).format('MM-DD-YYYY'); 
					}
				},
				{
					targets: 'time',
					className: 'text-center',
					render: function (data) {
						if (!moment(data).isValid()) {
							return '';
						}

						return moment(data).format('HH:II'); 
					}
				},
				{
					targets: 'datetime',
					className: 'text-center',
					render: function (data) {
						if (!moment(data).isValid()) {
							return '';
						}

						return moment(data).format('MM-DD-YYYY HH:II'); 
					}
				},
				{
					targets: 'db-number',
					className: 'text-right',
					render: function (data) {
						if (!moment(data).isValid()) {
							return '';
						}

						return moment(data).format('MM-DD-YYYY HH:II'); 
					}
				},
				{
					targets: 'text-left',
					className: 'text-left'
				},
				{
					targets: 'text-center',
					className: 'text-center'
				},
				{
					targets: 'text-right',
					className: 'text-right'
				},
				{
					targets: 'text-justify',
					className: 'text-justify'
				},
			]; 

			return {"columnDefs":columnDefs};

		}

	}
	
	$(document).ready(function () {
        APP.Forms.init();
    });

})();

