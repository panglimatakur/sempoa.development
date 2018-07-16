function next_product(id_client){ 
	data_page 		= $("#data_page").val();
	var config 		= JSON.parse("{"+$("#config").val()+"}");
	var dirhost		= config.dirhost;		
	$('div#lastPostsLoader').html('<div style="clear:both; text-align:center; margin:10px; font-family:Verdana, Geneva, sans-serif;"><img src="'+dirhost+'/files/images/loader_v.gif"><br>Mengambil Data...</div>');
	lastId			= $(".wrdLatest:last").attr("data-info");
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"id_coin":id_client,"lastID":lastId,"display":"list_product"},
		success : function(data){
			//alert(data);
			data = $.trim(data);
			if (data != "") {
				$("div.wrdLatest:last").after(data);
				/*$('html, body').animate({scrollTop:$("#gallery").offset().bottom}, 100);
				$("#gallery").animate({scrollTop: $("#gallery")[0].scrollHeight}, 1500);*/
			}
			if(data == ""){
				$('div#lastPostsLoader').remove();
			}
			$('div#lastPostsLoader').empty();
			
		}
	});
}
