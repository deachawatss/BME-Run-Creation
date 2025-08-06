$(".content-wrapper").addClass('kanban');

function card(param={}){
    var txtpriority = '';
    var hdrpriority = '';
    switch(param.task_priority){
        case "1":
            txtpriority = 'Urgent';
            hdrpriority = 'danger';
            break;
        
        case "2":
            txtpriority = 'High - Within the Day';
            hdrpriority = 'warning';
            break;

        case "3":
            txtpriority = 'Medium - Within 3 Days';
            hdrpriority = 'info';
            break;

        case "4":
            txtpriority = 'Low - Within the Week';
            hdrpriority = 'primary';
            break;
    }

    var chtml = '<div class="card-task" data-task_id="'+param.task_id+'"  >'; //ondragstart="drag(event,this)" draggable="true" 
            chtml += '<div class="card card-outline card-'+hdrpriority+'">';
                chtml += '<div class="card-header">';
                    chtml += '<h3 class="card-title">'+param.task_title+'</h3>';
                chtml += '</div>';
                chtml += '<div class="card-body ">';
                    chtml += "<div class='card-p'>"+param.task_description+"</div>";
                    chtml += '</div>';
                chtml += '<div class="card-footer">';
                    chtml += 'The footer of the card';
                chtml += '</div>';
            chtml += '</div>';
        chtml += '</div>';
    
    $('#progress-id-'+param.progress_id).append(chtml);

}

$( function() {
    $( ".card-task" ).draggable();
    $( ".card-task" ).sortable({
      connectWith: ".progress_table"
    }).disableSelection();
  } );

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev,param) {
    var mparam = "<div>"+ $(param).html() +"</param>";
    ev.dataTransfer.setData("items", mparam);
    
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("items");
    //$(ev.target).append(document.getElementById(data));
    $(ev.target).append(data);

    console.log(ev);
}

$(document).ready(function(){
    
    $.each(task,function(k,v){
        card(v);
    });


});