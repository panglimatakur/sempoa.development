function lastPostFunc(){ 
	data_page 	= $("#data_page").val();
	$('div#lastPostsLoader').html('<div style="text-align:center;margin:10px"><img src="files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	lastId		= $(".wrdLatest:last").attr("data-info");
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"lastID":lastId,"display":"list_report"},
		success : function(data){
			if (data != "") {
				$("#client_list tbody tr:last").after(data);
			}
			if(lastId == ""){
				$('div#lastPostsLoader').remove();
			}
			$('div#lastPostsLoader').empty();
			
		}
	});
};  

function pick(id_client){
	content = "<tr id='tr_"+id_client+"' style='cursor:pointer' onclick='cancel(\""+id_client+"\")'>";
	content += $("#tr_"+id_client).html();
	content	+= "</tr>";
	$("#new_pick tbody tr:last").after(content);
	$("#tr_"+id_client).slideUp(200);
	jml 	= $("#new_pick tbody tr").length;
	if(jml > 1){
		$("#sub_butt").show();		
	}
}

function cancel(id_client){
	$("#new_pick #tr_"+id_client).remove();
	$("#client_list #tr_"+id_client).slideDown(200);
	jml 	= $("#new_pick tbody tr").length;
	if(jml < 2){
		$("#sub_butt").hide();
	}
}