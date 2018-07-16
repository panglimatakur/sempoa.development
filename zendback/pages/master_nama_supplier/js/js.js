$(document).ready(function(){		
	$('#propinsi').on('change',function(){
		data 		= $("#data_page").val();
		propinsi 	= $(this).val();
		$("#div_kota").html("<img src='files/images/loading-bars.gif' style='margin-left:2%'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"get_city","propinsi":propinsi},
			success	: function(response){
				$("#div_kota").html(response);
			}
		})
	})
	$('#propinsi2').on('change',function(){
		data 		= $("#data_page").val();
		propinsi 	= $(this).val();
		$("#div_kota_report").html("<img src='files/images/loading-bars.gif' style='margin-left:2%'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"get_city2","propinsi":propinsi},
			success	: function(response){
				$("#div_kota_report").html(response);
			}
		})
	})
	$("#select_rows").on("click",function(){
		ch = $(this).is(":checked");
		if(ch == true){
			$(".row_sel").attr("checked","checked");
		}else{
			$(".row_sel").removeAttr("checked");	
		}
	})
	$("#select_rows_2").on("click",function(){
		ch = $("#select_rows").is(":checked");
		if(ch == true){
			$("#select_rows").removeAttr("checked");	
			$(".row_sel").removeAttr("checked");	
		}else{
			$("#select_rows").attr("checked","checked");
			$(".row_sel").attr("checked","checked");
		}
	})
	$("#delete_picked").on("click",function(){
		bootbox.confirm("Dengan menghapus data supplier ini, data-data pembelian yang sudah di daftarkan sebelumnya atas nama supplier ini, akan kehilangan relasi data supplier untuk data pembelian tersebut, Anda yakin menghapus data supplier Ini?",function(confirmed){
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

