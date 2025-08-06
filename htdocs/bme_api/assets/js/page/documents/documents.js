const contextMenu = document.getElementById("context-menu-explorer-body");
const Menufolder = document.getElementById("context-menu-folder");
const Menufile = document.getElementById("context-menu-file");
const scope = document.querySelector("#explorer-body");

const scopefolder = document.querySelector(".item-folder");
const scopefile = document.querySelector(".item-file");
var current_menu = 0;
var breadcrumb = [];
var folderpermission = {};

$(document).on("contextmenu","#explorer-body",function (ev) {
    ev.preventDefault();
    
    if(current_menu == 0 ){
        if(useraccess == 1 || useraccess ==112){
            const { clientX: mouseX, clientY: mouseY } = ev;
            contextMenu.style.top = `${mouseY}px`;
            contextMenu.style.left = `${mouseX}px`;
            contextMenu.style.display = 'block';
        }

    }else{
        const { clientX: mouseX, clientY: mouseY } = ev;
        contextMenu.style.top = `${mouseY}px`;
        contextMenu.style.left = `${mouseX}px`;
        contextMenu.style.display = 'block';
    }

    
    
});
folderdata = {};
$(document).on("contextmenu",".item-folder",function (ev) {
    ev.preventDefault();
    if(useraccess == 1 || useraccess ==112){
        folderdata = $(this).data('info');
        const { clientX: mouseX, clientY: mouseY } = ev;
        Menufolder.style.top = `${mouseY}px`;
        Menufolder.style.left = `${mouseX}px`;
        Menufolder.style.display = 'block';
    }
});

$(document).on("contextmenu",".item-file",function (ev) {
    ev.preventDefault();
    const { clientX: mouseX, clientY: mouseY } = ev;
    Menufile.style.top = `${mouseY}px`;
    Menufile.style.left = `${mouseX}px`;
    Menufile.style.display = 'block';
});

/*
scope.addEventListener("contextmenu", (event) => {
    event.preventDefault();

    const { clientX: mouseX, clientY: mouseY } = event;
    contextMenu.style.top = `${mouseY}px`;
    contextMenu.style.left = `${mouseX}px`;
    contextMenu.style.display = 'block';
    });

scopefolder.addEventListener("contextmenu", (event) => {
    event.preventDefault();
  
    const { clientX: mouseX, clientY: mouseY } = event;
    Menufolder.style.top = `${mouseY}px`;
    Menufolder.style.left = `${mouseX}px`;
    Menufolder.style.display = 'block';
});

scopefile.addEventListener("contextmenu", (event) => {
    event.preventDefault();
  
    const { clientX: mouseX, clientY: mouseY } = event;
    Menufile.style.top = `${mouseY}px`;
    Menufile.style.left = `${mouseX}px`;
    Menufile.style.display = 'block';
});

*/

$(document).on("click","#explorer-body",function(){
    contextMenu.style.display = 'none';
    Menufolder.style.display = 'none';
    Menufile.style.display = 'none';
});

$(document).on('dblclick','.item-folder',function(){
    myinfo = $(this).data('info');
    current_menu = myinfo.folder_id;
    breadcrumb.push(myinfo.folder_id);
    getfolder();
    
});

$(document).on('dblclick','.item-file',function(){
    
    myinfo = $(this).data('info');

    bootbox.dialog({
        'message':'<table>'+
                    '<tr>'+
                        '<td><iframe src="documents/getfile?docno='+myinfo.doc_no+'" style="height:70vh;width:70vw" ></iframe></td>'+
                        '<td>'+
                            '<table class="table table-sm table-bordered" style="width:100%">'+
                                '<tr><td>Document No</td><td>'+textval(myinfo.doc_no)+'</td></tr>'+
                                '<tr><td>Document Title</td><td>'+textval(myinfo.doc_title)+'</td></tr>'+
                                '<tr><td colspan=2>Description</td></tr>'+
                                '<tr><td colspan=2>'+textval(myinfo.description)+'</td></tr>'+
                                '<tr><td>Type</td><td>'+getvalobj(typelist,textval(myinfo.doc_type))+'</td></tr>'+
                                '<tr><td>Department</td><td>'+getvalobj(deptlist,textval(myinfo.dept))+'</td></tr>'+
                                '<tr><td>Room</td><td>'+textval(myinfo.room_id)+'</td></tr>'+
                                '<tr><td>Rack</td><td>'+textval(myinfo.rack_id)+'</td></tr>'+
                                '<tr><td>Box</td><td>'+getvalobj(boxlist,textval(myinfo.box_id))+'</td></tr>'+
                                '<tr><td colspan=2>Tags</td></tr>'+
                                '<tr><td colspan=2>'+arrtotext(textval(myinfo.tags))+'</td></tr>'+
                            '</table>'+
                        '</td>'+
                    '</tr>'+
                  '</table>',
        'className':'bigClass'
    }).find("div.modal-dialog");
    /*
    current_menu = myinfo.folder_id;
    breadcrumb.push(myinfo.folder_id);
    getfolder();
    */
    
});

$(document).on('click','.sidefolder',function(){
    breadcrumb = [];
    if($(this).data('id') != 0){
        myinfo = $(this).data('info');
        current_menu = myinfo.folder_id;
       
        breadcrumb.push(myinfo.folder_id);
    }else{
        current_menu = 0;
    }
    
   
    getfolder();
});

$(document).on('click','.bc-item',function(){
    var myid = $(this).data('id').toString();
    breadcrumb.splice(breadcrumb.indexOf(myid)+1 ,  breadcrumb.length );
    
    current_menu = myid;
    getfolder();
});

function genbreadcrumbs(){
    var html = "<li class='breadcrumb-item bc-item' data-id='0'><a href='#'>Documents</a></li>";

    $.each(breadcrumb,function(k,v){
        html += "<li class='breadcrumb-item bc-item' data-id='"+mmenu[v].folder_id+"'><a href='#'>"+ mmenu[v].folder_name +"</a></li>";
    });

    $("#breadcrumb").html(html);

}

function getfolder(){
    $.post('documents/getfolder',{folderid:current_menu},function(res){
        html = '';
        shtml = '<li class="sidefolder" data-info="" data-id="0" ><i class="fa-li fa fa-folder"></i>Documents</li>';
        mfolder = res.folder;
        msfolder = res.sidefolder;
        mpfolder = res.folderpermission;
        mfiles = res.files;

        if(mfolder.length > 0 || mfiles.length > 0){
            if(mfolder.length){
                $.each(mfolder, function(k,v){
                   
                   html += "<div class='file-item item-folder' data-info='"+JSON.stringify(v)+"'><div class='file-item-icon far fa-folder text-secondary'></div> <a data-id='"+v.folder_id+"'  class='file-item-name'> "+v.folder_name+" </a></div>";
                });
                
            }

            if(mfiles.length){
                $.each(mfiles, function(k,v){
                   
                   html += "<div class='file-item item-file' data-info='"+JSON.stringify(v)+"'><div style='color:red!important' class='file-item-icon far fa-file-pdf text-secondary'></div> <a data-id='"+v.rec_id+"'  class='file-item-name'> "+v.doc_title+" </a></div>";
                });
                
            }
        }else{
            html = "<center class='text-muted'><h5>Folder is Empty.</h5></center>";
        }
        $("#explorer-body").html(html);

        
        if(msfolder.length){
            $.each(msfolder, function(k,v){
                shtml += "<li class='sidefolder' data-id='"+v.folder_id+"' data-info='"+JSON.stringify(v)+"' ><i class='fa-li fa fa-folder'></i>"+v.folder_name+"</li>";
            });
        }
        
        if(mpfolder != null){
            folderpermission = {
                view_permission : mpfolder.view_permission,
                upload_permission : mpfolder.upload_permission,
                delete_permission : mpfolder.delete_permission,
                view_group_permission : mpfolder.view_group_permission,
                upload_group_permission : mpfolder.upload_group_permission,
                delete_group_permission : mpfolder.delete_group_permission,
                exclude_user : mpfolder.exclude_user,
            };
        }else{
            folderpermission= {};
        }

        $("#sidefoldermenu").html(shtml)
    },'json');     
    genbreadcrumbs();
}

$(document).ready(function(){
    getfolder();
});