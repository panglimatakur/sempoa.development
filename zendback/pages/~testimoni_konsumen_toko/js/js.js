$(document).ready(function() {
	$("#test_src").on("change",function(){
		val = $(this).val();
		if(val == "data"){
			$("#data_user").slideDown("fast");
			$("#nama").attr("readonly","readonly");
			$("#photo").attr("disabled","disabled");
		}else{
			$("#nama").removeAttr("readonly").val("");
			$("#photo").removeAttr("disabled");
			$("#data_user").slideUp("fast");
			$("#photo_pelanggan").empty();
		}
	})
});
function removal(id){
	bootbox.confirm("Dengan menghapus data supplier ini, data-data pembelian yang sudah di daftarkan sebelumnya atas nama supplier ini, akan kehilangan relasi data supplier untuk data pembelian tersebut, Anda yakin menghapus data supplier Ini?",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id},
				success	: function(){
					$("#tr_"+id).fadeOut(500);	
				}
			})
		}
	})
}

function show_form(el){
	var id_customer = $(el).val();
	data_page = $("#data_page").val();
	$("#form_testi").html("<div style='text-align:center'><img src='files/images/loader_v.gif' style='margin:4px'></div>");
	$.ajax({
		url		: data_page,
		type	: "POST",
		data 	: {"direction":"show_form","id_customer":id_customer},
		success	: function(response){
			result = JSON.parse(response);
			$("#nama").val(result.nama);
			$("#photo_pelanggan").html(result.foto+" "+result.user_id);
			$("#form_testi").empty();
		}
	})
}