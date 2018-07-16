	function lastPostFunc(){ 
		data_page = $("#data_page").val();
		$('div#lastPostsLoader').html('<div style="text-align:center;margin:10px"><img src="files/images/loading-bars.gif"><br>Mengambil Data...</div>');
		lastId		= $(".wrdLatest:last").attr("data-info");
		tgl_1		= $("#tgl_1").val();
		tgl_2		= $("#tgl_2").val();
		
		id_kategori = $("#id_kategori").val();
		code 		= $("#code").val();
		nama 		= $("#nama").val();
		deskripsi 	= $("#deskripsi").val();
		marketing 	= $("#marketing").val();
		faktur 		= $("#faktur").val();
		harga 		= $("#harga").val();
		keterangan 	= $("#keterangan").val();

		$.ajax({
			url 	: data_page,
			type	: "POST",
			data	: {"lastID":lastId,"tgl_1":tgl_1,"tgl_2":tgl_2,"marketing":marketing,"faktur":faktur,"harga":harga,"keterangan":keterangan,"id_kategori":id_kategori,"code":code,"nama":nama,"deskripsi":deskripsi,"display":"list_report"},
			success : function(data){
				if (data != "") {
				$(".wrdLatest:last").after(data);			
				}
				$('div#lastPostsLoader').empty();
			}
		});
	};  

$(document).ready(function(){	
	
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
	$("#btn_direction").on("click",function(){
		var conf 		= JSON.parse("{"+$("#config").val()+"}");
		page			= conf.page;
		
		piutang_config 	= JSON.parse("{"+$("#piutang_config").val()+"}");
		id_facture		= piutang_config.id_facture;
		id_product_sale	= piutang_config.id_product_sale;

		proses_page 	= $("#proses_page").val();
		tgl_tempo		= $("#tgl_tempo").val();
		tgl_bayar 		= $("#tgl_bayar").val();
		bayar 			= $("#jml_bayar").val();
		keterangan 		= $("#desc").val();
		total 			= $("#total_bayar").val();
		termin 			= $("#termin").val();
		sisa_bayar		= $("#sisa_bayar").val();
		cash 			= count_cash("#jml_bayar","1","");
		if(tgl_tempo != "" && tgl_bayar != "" && bayar != "" && cash == 0){ 
			$("#btn_direction").hide();
			$("#load").html("<img src='files/images/loading-bars.gif'>");
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"page":page,"direction":"save","termin":termin,"id_facture":id_facture,"id_product_sale":id_product_sale,"tgl_tempo":tgl_tempo,"tgl_bayar":tgl_bayar,"bayar":bayar,"sisa":sisa_bayar,"total":total,"keterangan":keterangan},
				success	: function(response){
					new_cash = $("#new_cash").val();
					$("#cash").val(new_cash);
					$("#new_cash").val("");
					$("#tr_"+id_facture).remove();	
					$.fancybox.close();
					$("#table_data tbody tr:first").before(response);	
				}
			})
		}else{
			 $("#load").html("<div class='alert alert-error' style='float:left; width:50%; padding:4px; margin-left:5px'>Pengisian Form Belum Lengkap</div>");
		}
	})
});

function removal(id){
	bootbox.confirm("Anda Yakin Menghapus Data Ini?",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id},
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



function count_pay(div){
	newval = document.getElementById(div.id).value.replace(/[^0-9]/g,'');
	$(div).val(newval);
	total 		= $("#total_bayar").val();
	first_bayar = $("#first_bayar").val();	
	sisa 		= $("#sisa_bayar").val();
	bayar 		= $("#jml_bayar").val();
	sisa_label 	= accounting.formatMoney(sisa,"Rp.",2,".",",");
	
	if(bayar > +sisa){
		 $("#load").html("<div class='alert alert-error' style='padding:4px;'>Pembayaran Anda Melebihi Sisa Piutang Sebesar "+bayar+">"+sisa+" = "+sisa_label+"</div>");
	}else{
		$("#load").html("");
		new_sisa	= +sisa- +bayar;
		new_sisa	= accounting.formatMoney(new_sisa,"Rp.",2,".",",");
		$("#sisa_bayar_label").html(new_sisa);
		
		new_bayar	= +first_bayar + +bayar;
		new_bayar	= accounting.formatMoney(new_bayar,"Rp.",2,".",",");
		$("#first_bayar_label").html(new_bayar);
	}
}

function print_r(){
	id_kategori = $("#id_kategori").val();
	code 		= $("#code").val();
	nama 		= $("#nama").val();
	deskripsi 	= $("#deskripsi").val();
	
	faktur 		= $("#faktur").val();
	harga 		= $("#harga").val();
	marketing 	= $("#marketing").val();
	keterangan	= $("#keterangan").val();
	tgl_1 		= $("#tgl_1").val();
	tgl_2 		= $("#tgl_2").val();
	
	show_data		= $("#show_data").val();

	print_container = "<input type='hidden' name='id_kategori' value='"+id_kategori+"' />"+
	"<input type='hidden' name='code' value='"+code+"' />"+
	"<input type='hidden' name='nama' value='"+nama+"' />"+
	"<input type='hidden' name='deskripsi' value='"+deskripsi+"' />"+
	"<input type='hidden' name='faktur' value='"+faktur+"' />"+
	"<input type='hidden' name='harga' value='"+harga+"' />"+
	"<input type='hidden' name='marketing' value='"+marketing+"' />"+
	"<input type='hidden' name='keterangan' value='"+keterangan+"' />"+
	"<input type='hidden' name='tgl_1' value='"+tgl_1+"' />"+
	"<input type='hidden' name='tgl_2' value='"+tgl_2+"' />"+
	"<input type='hidden' name='show_data' value='"+show_data+"' />";

	$("#print_container").html(print_container);
	$("#form_print").submit();
	$.fancybox.close();
	
}

