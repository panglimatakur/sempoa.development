$(document).ready(function(){		
	$('#direction').on('click',function(){
		
		no				= $("#no").val();
		proses_page 	= $("#proses_page").val();
		direction 		= $(this).val();
		id_root			= $("#id_root").val();
		parent_id		= $("#parent_id").val();
		nama			= $("#nama").val();
		is_folder		= $("#is_folder").val();
		$("#loader").html("<img src='files/images/loading-bars.gif'>");
		$.ajax({
			url		: proses_page,
			type	: "POST",
			data	: {"direction":direction,"no":no,"parent_id":parent_id,"id_root":id_root,"nama":nama,"is_folder":is_folder},
			success	: function(response){
				result  = JSON.parse(response);
				if(result.msg == 2){
					
					msg = "<div class='alert alert-success' style='margin:0 0 4px 0'>"+
							"Data Transaksi Berhasil Di Simpan"+
						  "</div>";
					$("#loader").html(msg);	
					location.reload();
				}
				if(result.msg == 1){
					bootbox.alert("Pengisian Form Belum Lengkap");
					$("#loader").html("");
				}
			}
		})
	})
});
function input_master(id,id_root){
	$("#form_value").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
	form_page = $("#form_page").val();
	$.ajax({
		url		: form_page,
		type	: "POST",
		data 	: {"show":"form_master","parent_id":id,"id_root":id_root},
		success	: function(response){
			$("#form_value").html(response);	
		}
	})
}

function delete_link(id){
	bootbox.confirm("Dengan menghapus data jenis transaksi ini, data-data transaksi yang sudah di daftarkan sebelumnya untuk jenis transaksi ini, akan kehilangan relasi data jenis transaksinya untuk data transaksi tersebut, Anda yakin menghapus data jenis transaksi Ini?",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id},
				success	: function(response){
					$("#li_"+id).fadeOut(300);	
				}
			})
		}
	})
}


