function lastPostFunc(){ 
	data_page 	= $("#data_page").val();
	$('div#lastPostsLoader').html('<div style="text-align:center;margin:10px"><img src="files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	lastId		= $(".wrdLatest:last").attr("data-info");
	id_com		= $("#id_com").val();	
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"lastID":lastId,"id_com":id_com,"display":"list_report"},
		success : function(data){
			if (data != "") {
				$("#lastPostsLoader").before(data);
			}
			if(lastId == ""){
				$('div#lastPostsLoader').remove();
			}
			$('div#lastPostsLoader').empty();
			
		}
	});
};  

function removal(id,id_com){
	bootbox.confirm("Anda yakin menghapus merchant dari komunitas ini",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id,"id_com":id_com},
				success	: function(response){
					$("#tr_"+id).fadeOut(500);	
				}
			})
		}
	})
}

function show_abacus(id_community){
	$("#community_"+id_community).slideToggle(200);	
}