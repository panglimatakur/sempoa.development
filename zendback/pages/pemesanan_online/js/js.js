function set_status(id_deal,id_merchant){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	proses_page 	 = $("#proses_page").val();
	st	= $("#st_"+id_deal).val();
	$("#v_loader_"+id_deal).html('<img src="'+conf.dirhost+'/files/images/loader_v.gif"><br>');
	$.ajax({
		url	 : proses_page,
		type 	: "POST",
		data	: {"direction":"set_status","id_merchant":id_merchant,"id_deal":id_deal,"st":st},
		success	: function(response){
			//alert(response);
			//$("#vera").html(response);
			$("#v_loader_"+id_deal).html("<div class='alert alert-success'>Status Berhasil Ubah</div>");
		}
	})
}
