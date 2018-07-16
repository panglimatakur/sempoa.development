var conf = JSON.parse("{"+$("#config").val()+"}");
var proses_page = $("#proses_page").val();
var data_page 	= $("#data_page").val();

$(document).ready(function(){
	$("#productGroup").chosen({
		create_option: true,
		width: "100%",
		persistent_create_option: true,
		create_option_text: 'add',
	}).change(function() {
		choosen_val = $("productGroup").chosen().val();
		$("#id_client_form").val(choosen_val)
        
	});
	
	$("#id_client_report_sel").chosen({
		create_option: true,
		width: "100%",
		persistent_create_option: true,
		create_option_text: 'add',
	}).change(function() {
		choosen_val = $("id_client_report_sel").chosen().val();
		$("#id_client_report").val(choosen_val)
		$("#div_reg_by").html("<img src='files/images/loading-bars.gif'>");
		var cidkey	= $(this).val();
		$.ajax({
			url		: data_page,
			type	: "POST",
			data 	: {"direction":"get_user","cidkey":cidkey},
			success	: function(response){
				$("#div_reg_by").html(response);
			}
		})
	});
	
	$('#propinsi').on('change',function(){
		propinsi 	= $(this).val();
		$("#div_kota").html("<img src='files/images/loading-bars.gif' style='margin-left:2%'>");
		$.ajax({
			url		: data_page,
			type	: "POST",
			data	: {"direction":"get_city","propinsi":propinsi},
			success	: function(response){
				$("#div_kota").html(response);
			}
		})
	})
	$('#propinsi2').on('change',function(){
		propinsi 	= $(this).val();
		$("#div_kota_report").html("<img src='files/images/loading-bars.gif' style='margin-left:2%'>");
		$.ajax({
			url		: data_page,
			type	: "POST",
			data	: {"direction":"get_city2","propinsi":propinsi},
			success	: function(response){
				$("#div_kota_report").html(response);
			}
		})
	})
	$("#select_rows").on("click",function(){
		ch = $(this).is("checked");
		if(ch == true){
			$(".row_sel").attr("checked","checked");
		}else{
			$(".row_sel").removeAttr("checked");	
		}
	})
	$("#select_rows_2").on("click",function(){
		ch = $("select_rows").is("checked");
		if(ch == true){
			$("#select_rows").removeAttr("checked");	
			$(".row_sel").removeAttr("checked");	
		}else{
			$("#select_rows").attr("checked","checked");
			$(".row_sel").attr("checked","checked");
		}
	})
	$("#delete_picked").on("click",function(){
		bootbox.confirm("Dengan menghapus data pelanggan ini, data-data penjualan yang sudah di daftarkan sebelumnya kepada pelanggan ini, akan kehilangan relasi data pelanggan untuk data penjualan tersebut, Anda yakin menghapus data pelanggan Ini?",function(confirmed){
			if(confirmed == true){
				$(".row_sel").each(function() {
				   ch 		= $(this).is("checked");
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


function removal(id,src){
	bootbox.confirm("Dengan menghapus data pelanggan ini, data-data penjualan yang sudah di daftarkan sebelumnya kepada pelanggan ini, akan kehilangan relasi data pelanggan untuk data penjualan tersebut, Anda yakin menghapus data pelanggan Ini?",function(confirmed){
		if(confirmed == true){
			
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id},
				success	: function(response){
					if(src == "table"){
						$("#tr_"+id).fadeOut(500);
					}else{
						location.href = conf.dirhost+"/?page="+conf.page; 
					}
				}
			})
		}
	})
}

function set_status(){
	active_id 		= $("#active_id").val();
	id_customer 	= $("#id_customer").val();
	cidkey 			= $("#cidkey").val();
	keterangan		= $("#keterangan").val();
	$("#activate_loader").html("<img src='files/images/loading.gif' style='float:right'>");
	$.ajax({
		url		: proses_page,
		type	: "POST",
		data 	: {"direction":"activate",
				   "cidkey":cidkey,
				   "id_customer":id_customer,
				   "active_id":active_id,
				   "keterangan":keterangan},
		success	: function(response){
			bootbox.alert("Terimakasih, permintaan telah dikirim, kami akan segera memproses permintaan anda");
		}
	})
}

function lastPostFunc(){ 
	page		= conf.page;
	lastId		= $(".wrdLatest:last").attr("data-info");
	parameters 	= $("#form_report").serialize();
	parameters	= parameters+"&page="+page+"&lastID="+lastId+"&direction=list_report";
	$('div#lastPostsLoader').html('<div style="text-align:center;margin:10px"><img src="files/images/loading-bars.gif"><br>Mengambil Data...</div>');

	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: parameters,
		success : function(data){
			if (data != "") {
				$(".wrdLatest:last").after(data)				
			}
			$('div#lastPostsLoader').empty();
		}
	});
};  
