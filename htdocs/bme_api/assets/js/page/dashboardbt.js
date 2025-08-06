$(".batchrow").hide()
$(".pickrow").hide()

$(".rowrun").click(function(){
    var mydata = $(this).data("runno");
    var mydatabatch = $(this).data("batch");

    $(".runno-"+mydata).toggle()
    $(".batch-"+mydatabatch).hide();
});

$(".batchrow").click(function(){
    var mydata = $(this).data("batch");

    $(".batch-"+mydata).toggle()
});