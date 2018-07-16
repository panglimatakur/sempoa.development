function send(id_client){
	var proses_page	= $("#proses_page").val();
	var config 		= JSON.parse("{"+$("#config").val()+"}");
	var dirhost		= config.dirhost;		
	var nama		= $("#c-name").val();
	var email		= $("#c-email").val();
	var pesan		= $("#c-question").val();
	if(nama != "" && email != "" && pesan != ""){
		$("#load_send").html("<img src='"+dirhost+"/files/coin/download/slider/images/loader_v.gif'>");
		$.ajax({
			url 	: proses_page,
			type	: "POST",
			data	: {"direction":"send","id_client":id_client,"nama":nama,"email":email,"pesan":pesan},
			success : function(response){
				$("#formSend input[type='text'],#formSend textarea").val("");
				$("#load_send").html("<div style='text-align:center'>Pertanyaan Berhasil Dikirim</div>");
			}
		})
	}else{
		$("#load_send").html("<div style='text-align:center'>Pengisian Form, Belum Lengkap</div>");
	}
}
function ajax_fancybox(location){
	$.fancybox.open([{
		type	: 'ajax',
		href 	: location+"&detail=true",
		'autoScale': true             
	}], 
	{padding : 4});
}
function autorespon(){
	$.fancybox({
        href: '#tbl_msg', 
        modal: true,
		'hideOnContentClick' : true,
		'padding': 2,
		'autoScale': true
    });
	$(".fancybox-skin").css("background","none");
	$("#msg_button").after("<button type='button' id='cls_button' class='myButton orange' style='margin-left:2px'>Tutup</button>");
	
	
}
$(document).ready(function(){
	$("#cls_button").live("click",function(){ 
		$.fancybox.close(); 
		new_c = $("#tbl_msg").html();
		$("#tbl_msg2").html(new_c); 
		$("#tbl_msg").remove();
		
		var config 		= JSON.parse("{"+$("#config").val()+"}");
		var dirhost		= config.dirhost;		
	   $.ajax({
			url		: dirhost+"/files/coin/download/ajax/proses.php",
			type	: "POST",
			data	: {"direction":"save_close","id_client":config.id_client},
			success: function(){  }	
	   })
	})
})