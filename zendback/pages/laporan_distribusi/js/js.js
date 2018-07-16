function lastPostFunc(){ 
	data_page = $("#data_page").val();
	$('div#lastPostsLoader').html('<div style="text-align:center;margin:10px"><img src="files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	lastId			= $(".wrdLatest:last").attr("data-info");
	
	tgl_1			= $("#tgl_1").val();
	tgl_2			= $("#tgl_2").val();
	
	id_kategori 	= $("#id_kategori").val();
	code 			= $("#code").val();
	nama 			= $("#nama").val();
	deskripsi 		= $("#deskripsi").val();
	
	id_branch 		= $("#id_branch").val();
	jumlah 			= $("#jumlah").val();
	keterangan 		= $("#keterangan").val();
	shipp_direction = $("#shipp_direction").val()
	
	
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"lastID":lastId,"tgl_1":tgl_1,"tgl_2":tgl_2,"id_branch":id_branch,"jumlah":jumlah,"keterangan":keterangan,"shipp_direction":shipp_direction,"id_kategori":id_kategori,"code":code,"nama":nama,"deskripsi":deskripsi,"display":"list_report"},
		success : function(data){
			if (data != "") {
				$(".wrdLatest:last").after(data);	
			}
			$('div#lastPostsLoader').empty();
		}
	});
}

$(document).ready(function(){
	$('#search_button').on('click',function(){
		var conf 	= JSON.parse("{"+$("#config").val()+"}");
		page		= conf.page;
		searching 	= $("#searching").val();
		filter		= $("#filter").val();	
		item_type	= $("#item_type").val();	
		product_list= $("#product_list").val();
		if(item_type != "" || searching !=""){
			$("#dt_list").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
			$.ajax({
				url 	: product_list,
				type	: "POST",
				data 	: {"page":page,"direction":"search_produk","searching":searching,"filter":filter,"item_type":item_type}, 
				success : function(response){
					$("#dt_list").html(response);
				}
			})	
		}else{
			window.location.reload();	
		}
	});     
	$("#send_button").on('click',function(){
		var conf 				= JSON.parse("{"+$("#config").val()+"}");
		page					= conf.page;
		id_branch 				= $("#id_branch_status").val();
		
		type 					= $(this).val();
		catatan 				= $("#catatan").val();
		item_type				= $("#item_type").val();
		id_product_distribution = $("#id_product_distribution").val();
		
		proses_page 			= $("#proses_page").val();	
		if(item_type != ""){
			$("#dt_list").html("<img src='files/images/loading.gif' style='margin:4px 0 0 0; float:right'>");
			$.ajax({
				url 	: proses_page,
				type	: "POST",
				data 	: {"page":page,"direction":"update_status","type":type,"id_branch":id_branch,"item_type":item_type,"id_product_distribution":id_product_distribution,"note":catatan}, 
				success : function(response){
					//alert(response);
					if(conf.realtime == 1){
						container   		= '{"cidkey":"'+conf.cidkey+'","for_id":"'+id_branch+'"}';
						pushit("/laporan_distribusi",1,container);
					}
					$("#tr_req_"+id_product_distribution).remove();
					$("#table_req tbody tr:first").after(response);					
					$.fancybox.close();
					
				}
			})	
		}
	}); 
	$("#id_type_report").on("change",function(){
		data_page 	= $("#data_page").val();
		type		= $(this).val();
		if(type != ""){
			$("#div_kategori_report").html("<img src='files/images/loading-bars.gif' style='margin-left:4%'>");
			$.ajax({
				url		: data_page,
				type	: "POST",
				data 	: {"display":"kategori_report","id_type_report":type},
				success : function(response){
					$("#div_kategori_report").html(response);	
				}
			})
		}else{
			$("#div_kategori").html("");	
		}
	})
})



function removal_single(type,id_product_distribution,id_stock){
	bootbox.prompt("Alasan Menghapus Produk Ini", function(reason) {
			if(reason != null){
				proses_page = $("#proses_page").val();
				$.ajax({
					url		: proses_page,
					type	: "POST",
					data 	: {	"direction":"delete_single",
								"type":type,
								"note":reason,
								"id_product_distribution":id_product_distribution,
								"id_product_stock":id_stock
							   },
					success	: function(response){
						$("#td_"+id_stock).fadeOut(300);
						if(response == "delete_all"){
							$("#tr_req_"+id_product_distribution).slideUp(200);
						}
					}
				})
			}
	});
}



function calculate_multi(src,div,id){
	newval = document.getElementById(div).value.replace(/[^0-9]/g,'');
	$("#"+div).val(newval);
	$("#stock_note").html("").removeClass("alert alert-error col-md-4");
	jual 				= $("#jumlah_"+id).val();
	ori_stock			= $("#ori_stock_"+id).val();

	if(+jual > +ori_stock){
		$("#jumlah_"+id).val("");
		$("#jumlah_"+id).after("<div class='alert alert-error col-md-4' style='padding:3px; float:right; margin:0 8px 0 0' id='stock_note'>Jumlah Penjualan Lebih Besar Dari Pada Stock Barang</div>");
		done = 0;
	}else{
		done = 1;
	}
	if(jual == "")	{ new_stock = ori_stock; 		}
	else			{ new_stock = ori_stock-jual; 	}
	$("#stock_label_"+id).html(new_stock);	
}

function edit_stock(){
	type 					= $("#type").val();
	id_product_distribution = $("#id_product_distribution").val();
	id_product_stock 		= $("#id_product_stock").val();
	
	proses 					= $("#proses_page").val();
	jumlah 					= $("#jumlah").val();
	keterangan 				= $("#keterangan").val();
	if(jumlah != ""){
		$("#load").html("<img src='files/images/loading-bars.gif' style='margin-left:4px'>");
		$("#btn_direction").hide();
		$.ajax({
			url 	: proses,
			type 	: "POST",
			data	: {"direction":"edit_stock","type":type,"id_product_distribution":id_product_distribution,"id_product_stock":id_product_stock,"jumlah":jumlah,"note":keterangan},
			success	: function(response){
				$("#tr_req_"+id_product_distribution).remove();
				$("#table_req tbody tr:first").after(response);
				$.fancybox.close();
			}
		});
	}else{
		 $("#load_"+id_history).html("<div class='alert alert-error' style='float:left; width:50%; padding:4px; margin-left:5px'>Pengisian Form Belum Lengkap</div>");
	}
}

function print_r(){
	id_kategori 	= $("#id_kategori").val();
	code 			= $("#code").val();
	nama 			= $("#nama").val();
	deskripsi 		= $("#deskripsi").val();
	id_branch 		= $("#id_branch").val();
	jumlah 			= $("#jumlah").val();
	keterangan 		= $("#keterangan").val();
	shipp_direction = $("#shipp_direction").val();
	tgl_1 			= $("#tgl_1").val();
	tgl_2 			= $("#tgl_2").val();
	
	show_data		= $("#show_data").val();
	print_container = "<input type='hidden' name='id_kategori' value='"+id_kategori+"' />"+
	"<input type='hidden' name='code' value='"+code+"' />"+
	"<input type='hidden' name='nama' value='"+nama+"' />"+
	"<input type='hidden' name='deskripsi' value='"+deskripsi+"' />"+
	"<input type='hidden' name='id_branch' value='"+id_branch+"' />"+
	"<input type='hidden' name='jumlah' value='"+jumlah+"' />"+
	"<input type='hidden' name='shipp_direction' value='"+shipp_direction+"' />"+
	"<input type='hidden' name='keterangan' value='"+keterangan+"' />"+
	"<input type='hidden' name='tgl_1' value='"+tgl_1+"' />"+
	"<input type='hidden' name='tgl_2' value='"+tgl_2+"' />"+
	"<input type='hidden' name='show_data' value='"+show_data+"' />";
	
	$("#print_container").html(print_container);
	$("#form_print").submit();
	$.fancybox.close();
	
}