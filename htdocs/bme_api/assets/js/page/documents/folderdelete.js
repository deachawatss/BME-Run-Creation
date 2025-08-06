$(document).on('click','#delete-folder',function(){
    Menufolder.style.display = 'none';
    contextMenu.style.display = 'none';
    var myinfo = folderdata;

    console.log(myinfo);

    Swal.fire({
        icon: 'question',
        title: 'Do you want to save the changes?',
        text: 'All file from the folder will be un-accessible.',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash"></i>&nbsp;Delete',
        confirmButtonColor: 'red',
        denyButtonText: 'Cancel',
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.post('documents/deletefolder',{
                folderid : myinfo.folder_id, 
            },function(res){
                if(res.msgcode != undefined){
                    Swal.fire(
                        errorcode[res.msgcode],
                        '',
                        'error'
                      );
                }
                getfolder();
            },'json');
        } 
      });

});