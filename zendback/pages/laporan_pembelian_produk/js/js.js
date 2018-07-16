function lastPostFunc(){ 
	data_page = $("#data_page").val();
	$('div#lastPostsLoader').html('<div style="text-align:center;margin:10px"><img src="files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	lastId	= $(".wrdLatest:last").attr("data-info");
	tgl_1		= $("#tgl_1").val();
	tgl_2		= $("#tgl_2").val();
	id_kategori = $("#id_kategori").val();
	code 		= $("#code").val();
	nama 		= $("#nama").val();
	satuan 		= $("#satuan").val();
	deskripsi 	= $("#deskripsi").val();
	
	faktur 		= $("#faktur").val();
	stock 		= $("#stock").val();
	harga 		= $("#harga").val();
	harga_pokok = $("#harga_pokok").val()
	total 		= $("#total").val();
	lunas 		= $("#lunas").val();
	
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"lastID":lastId,"tgl_1":tgl_1,"tgl_2":tgl_2,"faktur":faktur,"harga_pokok":harga_pokok,"harga":harga,"stock":stock,"total":total,"lunas":lunas,"id_kategori":id_kategori,"code":code,"nama":nama,"satuan":satuan,"deskripsi":deskripsi,"display":"list_report"},
		success : function(data){
			if (data != "") {
				if($("#new_total").length > 0){ $("#new_total").remove(); }
				
				$(".wrdLatest:last").after(data);
				
				if($("#new_total").length > 0){
					new_totals = $("#new_total").val();
					new_total = JSON.parse('{'+new_totals+'}');
					if(new_total.jumlah_all != 0)	{ 
						new_jumlah	= $("#jumlah_all").html()+ +new_total.jumlah_all;
						$("#jumlah_all").html(new_jumlah);	
					}
					if(new_total.hutang_all != 0)	{ 
						new_hutang	= +$("#hutang_all_num").val()+ +new_total.new_hutang;
						hutang_all 	= accounting.formatMoney(new_hutang,"Rp.",2,".",",");
						$("#hutang_all").html(hutang_all);	
						$("#hutang_all_num").val(new_hutang);
					}
					if(new_total.total_all != 0)	{ 
						new_total	= +$("#total_all_num").val()+ +new_total.total_all;
						total_all 	= accounting.formatMoney(new_total,"Rp.",2,".",",");
						$("#total_all").html(total_all);	
						$("#total_all_num").val(new_total);
					}
				}
			}
				$('div#lastPostsLoader').empty();
		}
	});
};  

$(document).ready(function(){	
	$('#search_button').on('click',function(){
		var conf 	= JSON.parse("{"+$("#config").val()+"}");
		page		= conf.page;
		searching 	= $("#searching").val();
		filter		= $("#filter").val();	
		item_type	= $("#item_type").val();	
		product_list= $("#product_list").val();
		kolektif	= $("#kolektif").val();
		if(kolektif == "true"){
			product_list= $("#product_list_kolektif").val();
		}else{
			product_list= $("#product_list").val();
		}
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
		bootbox.confirm("Anda Yakin Menghapus Data Ini?",function(confirmed){
			if(confirmed == true){
				proses_page = $("#proses_page").val();
				$(".row_sel").each(function() {
				   ch 		= $(this).is(":checked");
				   ch_val 	= $(this).val();
				   if(ch == true){
					$.ajax({
						url		: proses_page,
						type	: "POST",
						data 	: {"direction":"delete","id_product_buy":ch_val},
					})
					$("#tr_"+ch_val).fadeOut(500);	   
				   }
				});
			}
		})
	})
});


function show_form(){
	$("#form_edit").toggle(300);
	$(".icon_edit").toggle();
	$("#btn_direction_edit").show();
	if($('#dp2_edit').length) {
		$('#dp2_edit').datepicker()
	}
}
function show_kredit(target){
	status_lunas 		= $("#status_lunas_multi_label").val();
	if(status_lunas !=  2){
		$(".div_kredit_"+target).slideUp(300);
	}else{
		$(".div_kredit_"+target).slideDown(300);
	}
}

function calculate_edit(src,div){
	newval = document.getElementById(div).value.replace(/[^0-9]/g,'');
	$("#"+div).val(newval);
	$("#payment_msg").remove();
	num_collective		= $("#num_collective").val();
	lunas 				= $("#status_lunas_edit").val();
	
	real_total_bayar	= $("#real_total_bayar").val();
	real_total_beli		= $("#real_total_beli").val();
	
	harga_beli_edit		= $("#harga_beli_edit").val();
	jumlah_edit			= $("#jumlah_edit").val();
	
	new_total_beli		= +harga_beli_edit * +jumlah_edit;
	money2input("#total_bayar_edit","#total_bayar_label_edit",new_total_beli,"");

	new_total_bayar 	= (+real_total_bayar - +real_total_beli) + +new_total_beli;
	
	next 				= 2;
	payment 			= $("#downpay_edit").val();
	
	if(+payment > +new_total_bayar){
		$("#form_msg").show();
		$(".form_test").addClass("alert alert-error");
		$("#btn_direction_edit").hide();
		calculate_multi(src,div);
		next = 1;
	}
	
	new_payment = new_total_bayar;
	$("#new_total_bayar").val(new_payment); 
	new_total_pembelian 		= accounting.formatMoney(new_payment,"",2,".",","); //"Rp.",2,".",",");
	$("#new_total_pembelian").val(new_total_pembelian);
	remain 				= +new_total_bayar - +payment;
	money2input("#kredit_edit","#kredit_label_edit",remain,"");
	
	if(next == 2){
		$("#btn_direction_edit").show();
		$("#form_msg").hide();
		$(".form_test").removeClass("alert alert-error");
		
		if(remain > 0){
			if(lunas == 2){
				$('#status_lunas_edit_label').val(1);
				$('#status_lunas_edit').val(1);
				$(".div_kredit_edit").slideDown(500);
			}
		}else{
			if(lunas != 2){
				$('#status_lunas_edit_label').val(2);
				$('#status_lunas_edit').val(2);
				$(".div_kredit_edit").slideUp(500);
			}
		}
			
		if(num_collective > 1){
			new_result_label = accounting.formatMoney(remain,"Rp.",2,".",","); //new_kredit,"Rp.",2,".",",")
			$("#div_all_total").html(new_result_label);
		}
	}
}

function calculate_multi(src,div,id){
	newval = document.getElementById(div).value.replace(/[^0-9]/g,'');
	$("#"+div).val(newval);	
		cash 			= $("#cash").val();
	ori_stock 			= $("#ori_stock_"+id).val();
	pokok 				= $("#harga_beli_"+id).val();
	stock 				= $("#jumlah_"+id).val();
	
	if(stock !="" && stock != "0")	{ new_stock = +ori_stock + +stock; 	}
	else							{ new_stock	= ori_stock; 			}
	$("#stock_label_"+id).html(new_stock);


	status_lunas 		= $("#status_lunas_multi").val();
	downpay 			= $("#downpay_multi").val();
	kredit 				= $("#kredit_multi").val();
	kredit_label		= $("#kredit_label_multi").val();
	total_harga_beli	= $("#total_bayar_multi").val();

	downpay_st = 1;
	if(downpay != ""){
		if(+downpay > +total_harga_beli){
			if($(".modal-body").length == 0){
				bootbox.alert("Jumlah Pembayaran Lebih Besar Dari Pada Total Bayar",function(){
					var new_downpay = downpay.substring(0,downpay.length - 1);
					var new_kredit 	= +total_harga_beli - +new_downpay;
					$("#downpay_multi").val(new_downpay);
					calculate_multi(src,div,id);
				});
			}
			downpay_st = 0;
		}else{
			new_kredit 	= +total_harga_beli - +downpay;
		}
		new_cash		= +cash - +downpay;
	}else{
		new_kredit 		= total_harga_beli;
		new_cash		= +cash - +total_harga_beli;
	}
	if(new_kredit > 0){
		if(status_lunas == 2){
			$('#status_lunas_multi_label').val(1);
			$('#status_lunas_multi').val(1);
			$(".div_kredit_multi").slideDown(500);
		}
	}else{
		if(status_lunas != 2){
			$('#status_lunas_multi_label').val(2);
			$('#status_lunas_multi').val(2);
			$(".div_kredit_multi").slideUp(500);
		}
	}
	money2input("#kredit_multi","#kredit_label_multi",new_kredit,"");
	money2html("#new_cash","#balance",new_cash,"Balance : Rp."); 
}

function get_rekap(old_sum,new_sum,old_remain,new_remain,old_paid,new_paid){
	sum 	 	= $("#jumlah_all_num").val();
	remain   	= $("#hutang_all_num").val();
	paid	 	= $("#total_all_num").val(); 
	
	sum_all  	= (+sum - +old_sum) + +new_sum;
	remain_all	= (+remain - +old_remain) + +new_remain;
	paid_all	= (+paid - +old_paid) + +new_paid;
	
	$("#jumlah_all").html(sum_all);
	$("#jumlah_all_num").val(sum_all);
	money2html("#hutang_all_num","#hutang_all",remain_all,"Rp.");
	money2html("#total_all_num","#total_all",paid_all,"Rp.");	
}

function do_direction(direction,id){
	//alert(direction);
	var conf 			= JSON.parse("{"+$("#config").val()+"}");
	page				= conf.page;
	proses 				= $("#proses_page").val();
	
	tgl_beli 			= $("#tgl_beli_edit").val();
	id_partner			= $("#id_partner_edit").val();
	id_faktur 			= $("#id_facture_edit").val();
	harga_beli 			= $("#harga_beli_edit").val();
	stock 				= $("#jumlah_edit").val();
	harga_jual 			= $("#harga_jual_edit").val();
	total				= $("#total_bayar_edit").val();

	id_cash_flow 		= $("#id_cash_flow").val();
	faktur 				= $("#no_faktur_edit").val();
	lunas 				= $("#status_lunas_edit").val();
	termin 				= $("#termin_edit").val();
	nopo 				= $("#nopo_edit").val();
	downpay 			= $("#downpay_edit").val();
	kredit 				= $("#kredit_edit").val();
	keterangan			= $("#keterangan_edit").val();

	real_total_beli		= $("#real_total_beli").val();
	real_total_bayar	= $("#real_total_bayar").val();
	first_stock 		= $("#ori_stock_edit").val();

	first_total_bayar	= $("#first_total_bayar").val(); 
	new_total_bayar		= $("#new_total_bayar").val();

	if(harga_beli != "" && harga_jual != ""){
							
		$("#btn_direction_edit").hide();
		$("#load_edit").html("<img src='files/images/loading-bars.gif'>");
		id_product_buy 	= $("#id_product_buy").val();
		st_termin		= "";
		tgl_tempo_edit	= "";
		if(termin != "" || termin != 0){
			t = 0;
			for(t2=1;t2<=termin;t2++){
				t++;
				tgl_tempo_edit += ";"+$("#tgl_tempo_edit_"+t2).val();
				st_termin	   += ";"+$("#st_termin_"+t2).val();
			}
		}
		$.ajax({
			url 	: proses,
			type 	: "POST",
			data	: {
						"page":page,
						"direction":direction,
						"id_facture":id_faktur,
						"id_product_buy":id_product_buy,
						"id_product":id,
						"tgl_beli":tgl_beli,
						"id_partner":id_partner,
						"harga_pokok":harga_beli,
						"harga":harga_jual,
						"stock":stock,
						"total":total,
						"id_cash_flow":id_cash_flow,
						"faktur":faktur,
						"nopo":nopo,
						"lunas":lunas,
						"termin":termin,
						"tgl_tempo":tgl_tempo_edit,
						"st_termin":st_termin,
						"downpay":downpay,
						"kredit":kredit,
						"keterangan":keterangan,
						"first_stock":first_stock,
						"real_total_beli":real_total_beli,
						"real_total_bayar":real_total_bayar},
			success	: function(response){
				$("#form_edit").toggle(300);
				$(".icon_edit").toggle();
				$("#load_edit").html("");
				$("#tr_"+id_cash_flow).remove(); 
				
				if(downpay != ""){
					new_total_bayar = downpay;
				}
				CASH 			= $("#cash").val();
				NCASH			= (+CASH + +first_total_bayar)- +new_total_bayar;

				money2html("#cash","#balance",NCASH,"Balance : Rp.");
				
				get_rekap(first_stock,stock,first_kredit,kredit,first_total_bayar,new_total_bayar);
				
				$.fancybox.close();
				$("#table_data tbody tr:first").before(response);
			}
		});
		
		
	}else{
		 $("#load_edit").html("<div class='alert alert-error' style='float:left; width:50%; padding:4px; margin:0 0 0 5px'>Pengisian Form Belum Lengkap</div>");
	}
}

function removal(id,id_buy){
	bootbox.confirm("Anda Yakin Menghapus Data Ini?",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","id_product_buy":id},
				success	: function(response){
					new_cash = $.trim(response);
					res = accounting.formatMoney(new_cash,"Balance : Rp.",2,".",","); // €4.999,99	
					$("#balance").html(res);
					$("#new_cash").val(new_cash);
					$("#tr_"+id).fadeOut(500);	
				}
			})
		}
	})
	
}
function removal_single(id_cash_flow,id_buy){
	bootbox.confirm("Anda Yakin Menghapus Data Ini?",function(confirmed){
		if(confirmed == true){
			$("#content_load").html("<img src='files/images/loading-bars.gif'>");
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete_single","id_cash_flow":id_cash_flow,"id_product_buy":id_buy},
				success	: function(response){
					//alert(response);
					result = JSON.parse(response);
					if($.trim(result.note) != ""){
						bootbox.alert(result.note);
					}
					//$("#mes").html(response);
					new_cash = $.trim(result.cash);
					res = accounting.formatMoney(new_cash,"Balance : Rp.",2,".",","); // €4.999,99	
					$("#balance").html(res);
					$("#new_cash").val(new_cash);
					$("#tr_"+id_cash_flow).fadeOut(300);
					
					if(result.num_list > 0){
						$.post(proses_page,{"direction":"show_content","show_list":"1","id_cash_flow":id_cash_flow,"id_product_buy":id_buy},function(table_list){
							$("#table_data tbody tr:first").before(table_list);	
						})
					}
					$("#content_load").empty();
				}
			})
		}
	})
}

function print_r(){
	id_kategori = $("#id_kategori").val();
	code 		= $("#code").val();
	nama 		= $("#nama").val();
	deskripsi 	= $("#deskripsi").val();
	faktur 		= $("#faktur").val();
	harga_pokok = $("#harga_pokok").val();
	harga		= $("#harga").val();
	stock 		= $("#stock").val();
	total 		= $("#total").val();
	lunas 		= $("#lunas").val();
	tgl_1 		= $("#tgl_1").val();
	tgl_2 		= $("#tgl_2").val();

	show_data		= $("#show_data").val();

	print_container = "<input type='hidden' name='id_kategori' value='"+id_kategori+"' />"+
	"<input type='hidden' name='code' value='"+code+"' />"+
	"<input type='hidden' name='nama' value='"+nama+"' />"+
	"<input type='hidden' name='deskripsi' value='"+deskripsi+"' />"+
	"<input type='hidden' name='faktur' value='"+faktur+"' />"+
	"<input type='hidden' name='harga_pokok' value='"+harga_pokok+"' />"+
	"<input type='hidden' name='harga' value='"+harga+"' />"+
	"<input type='hidden' name='stock' value='"+stock+"' />"+
	"<input type='hidden' name='total' value='"+total+"' />"+
	"<input type='hidden' name='lunas' value='"+lunas+"' />"+
	"<input type='hidden' name='tgl_1' value='"+tgl_1+"' />"+
	"<input type='hidden' name='tgl_2' value='"+tgl_2+"' />"+
	"<input type='hidden' name='show_data' value='"+show_data+"' />";

	$("#print_container").html(print_container);
	$("#form_print").submit();
	$.fancybox.close();
	
}
