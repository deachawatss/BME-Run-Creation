function pickdata(res = {}){
    $("#batchticket").val(res.batchno);
    $("#runno").val(res.runno);
    $("#pop_runno").val(res.runno);
    $("#formulaid").val(res.formulaid);
}

function after_addfn(data){
    window.open('PalletTag/printdata?id='+data.newdata);
}