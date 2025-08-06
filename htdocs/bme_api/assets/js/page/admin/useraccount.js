var mmdiag;
$(document).on('click',"#btn-text-uname",function(){
    mmdiag = bootbox.dialog({
        "title": "SEARCH AD USER",
        "message": '<table class="table table-sm table-hovered table-bordered" id="adusers"></table>'
    });

    var column =[ 
        {data: 'username',title: 'Action',visible: true,className: '', 
            render: function ( data, type, row ) {
                return "<a class='btn btn-outline-primary btn-sm btn-ad-user' data-info='"+JSON.stringify(row)+"'><i class='fas fa-check'></i></a>";
            }
        },
        {data: 'username',title: 'Username',visible: true,className: ''},
        {data: 'surname',title: 'Surname',visible: true,className: ''},
        {data: 'firstname',title: 'Fistname',visible: true,className: ''},
    ]; 

    mmdiag.on('shown.bs.modal', function(e){
        $("#adusers").DataTable({
                columns: column,
                data: aduser,
                processing: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                destroy: true,
                "scrollX": true
        });
    });

});

$(document).on("click",".btn-ad-user",function(){
    info = $(this).data('info');
    $("#uname").val(info.username);
    $("#Fname").val(info.firstname);
    $("#Lname").val(info.surname);
    mmdiag.find(".bootbox-close-button").trigger("click");
});