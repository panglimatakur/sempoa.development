$(document).ready(function(){
	$(".select_rows").on("click",function(){
		ch = $(this).prop("checked");
		if(ch == true){
			$(".row_sel").prop("checked","true");
		}else{
			$(".row_sel").prop("checked","false");
		}
	})
	$("#select_rows_2").on("click",function(){
		ch = $(".select_rows").prop("checked");
		if(ch == true){
			$(".select_rows").removeAttr("checked");	
			$(".row_sel").removeAttr("checked");	
		}else{
			
			$(".select_rows").prop("checked","true");
			$(".row_sel").prop("checked","true");
		}
	})
	$("#delete_picked").on("click",function(){
		bootbox.confirm("Dengan menghapus data Komunitas ini, data-data komunitas merchant yang sudah di daftarkan sebelumnya atas nama komunitas ini, akan kehilangan relasi data komunitasnya, Anda yakin menghapus data Komunitas Ini?",function(confirmed){
			if(confirmed == true){
				proses_page = $("#proses_page").val();
				$(".row_sel").each(function() {
				   ch 		= $(this).is(":checked");
				   ch_val 	= $(this).val();
				   if(ch == true){
					$.ajax({
						url		: proses_page,
						type	: "POST",
						data 	: {"direction":"delete","no":ch_val},
					})
					$("#tr_"+ch_val).fadeOut(500);	   
				   }
				});
			}
		})
	})
	get_result("0");
})



function get_result(id_parent){
	data 		= $("#data_page").val();
	$("#div_result").html("<img src='files/images/loading-bars.gif' style='margin-bottom:10px; float:right'><div class='clearfix'></div>");
	$.ajax({
		url		: data,
		type	: "POST",
		data	: {"direction":"get_data","id_parent":id_parent},
		success	: function(response){
			$(".table-location tbody").html(response);
			$("#div_result").empty();
		}
	})
}
function get_kota(el){
	data 		= $("#data_page").val();
	propinsi 	= $(el).val();
	$("#div_kota").html("");
	$("#div_kecamatan").html("");
	$("#div_kelurahan").html("");
	if(propinsi != ""){
		$("#div_kota").html("<img src='files/images/loading-bars.gif' style='margin-left:2%'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"get_kota","propinsi":propinsi},
			success	: function(response){
				$("#div_kota").html(response);
				$("#div_name").html("Tuliskan Nama Kota/Kabupaten");
				get_result(propinsi);
			}
		})
	}
}	
function get_kecamatan(el){
	data 		= $("#data_page").val();
	kota 		= $(el).val();
	$("#div_kecamatan").html("");
	$("#div_kelurahan").html("");
	if(kota != ""){
		$("#div_kecamatan").html("<img src='files/images/loading-bars.gif' style='margin-left:2%'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"get_kecamatan","kota":kota},
			success	: function(response){
				$("#div_kecamatan").html(response);
				$("#div_name").html("Tuliskan Nama Kecamatan");
				get_result(kota);
			}
		})
	}	
}
function get_kelurahan(el){
	data 		= $("#data_page").val();
	kecamatan 	= $(el).val();
	$("#div_kelurahan").html("");
	if(kecamatan != ""){
		$("#div_kelurahan").html("<img src='files/images/loading-bars.gif' style='margin-left:2%'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"get_kelurahan","kecamatan":kecamatan},
			success	: function(response){
				$("#div_kelurahan").html(response);
				$("#div_name").html("Tuliskan Nama Kelurahan");
				get_result(kecamatan);
			}
		})
	}
}

function delete_link(id){
	bootbox.confirm("Anda yakin menghapus Link Ini? Karena juga akan menghapus data link anak di bawahnya",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id},
				success	: function(){
					$("#li_"+id).fadeOut(500);	
				}
			})
		}
	})
}
