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

	faktur 		= $("#faktur").val();
	harga_pokok = $("#harga_pokok").val();

	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"lastID":lastId,"tgl_1":tgl_1,"tgl_2":tgl_2,"faktur":faktur,"harga_pokok":harga_pokok,"id_kategori":id_kategori,"code":code,"nama":nama,"deskripsi":deskripsi,"display":"list_report"},
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
		
		hutang_config 	= JSON.parse("{"+$("#hutang_config").val()+"}");
		id_facture		= hutang_config.id_facture;
		id_product_buy	= hutang_config.id_product_buy;
		proses_page 	= $("#proses_page").val();
		tgl_tempo		= $("#tgl_tempo").val();
		tgl_bayar 		= $("#tgl_bayar").val();
		bayar 			= $("#jml_bayar").val();
		termin 			= $("#termin").val();
		keterangan 		= $("#desc").val();
		total 			= $("#total_bayar").val();
		cash 			= count_cash("#jml_bayar","2","");
		if(tgl_tempo != "" && tgl_bayar != "" && bayar != "" && cash == 0){ 
			$("#btn_direction").hide();
			$("#load").html("<img src='files/images/loading-bars.gif'>");
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"page":page,"direction":"save","termin":termin,"id_facture":id_facture,"id_product_buy":id_product_buy,"tgl_tempo":tgl_tempo,"tgl_bayar":tgl_bayar,"bayar":bayar,"total":total,"keterangan":keterangan},
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

function count_pay(div){
	newval = document.getElementById(div.id).value.replace(/[^0-9]/g,'');
	$(div).val(newval);
	total 		= $("#total_bayar").val();	
	sisa 		= $("#sisa_bayar").val();
	bayar 		= $("#jml_bayar").val();
	sisa_label 	= accounting.formatMoney(sisa,"Rp.",2,".",",");
	
	if(bayar > +sisa){
		 $("#load").html("<div class='alert alert-error' style='padding:4px;'>Pembayaran Anda Melebihi Sisa Hutang Sebesar "+bayar+">"+sisa+" = "+sisa_label+"</div>");
		 $("#jml_bayar").val("");
	}else{
		$("#load").html("");
		new_sisa	= +sisa- +bayar;
		new_sisa	= accounting.formatMoney(new_sisa,"Rp.",2,".",",");
		$("#sisa_bayar_label").html(new_sisa);
	}
}
function print_r(){
	id_kategori 	= $("#id_kategori").val();
	code 			= $("#code").val();
	nama 			= $("#nama").val();
	deskripsi 		= $("#deskripsi").val();
	faktur 			= $("#faktur").val();
	harga_pokok 	= $("#harga_pokok").val();
	tgl_1 			= $("#tgl_1").val();
	tgl_2 			= $("#tgl_2").val();

	show_data		= $("#show_data").val();

	print_container = "<input type='hidden' name='id_kategori' value='"+id_kategori+"' />"+
	"<input type='hidden' name='code' value='"+code+"' />"+
	"<input type='hidden' name='nama' value='"+nama+"' />"+
	"<input type='hidden' name='deskripsi' value='"+deskripsi+"' />"+
	"<input type='hidden' name='faktur' value='"+faktur+"' />"+
	"<input type='hidden' name='harga_pokok' value='"+harga_pokok+"' />"+
	"<input type='hidden' name='tgl_1' value='"+tgl_1+"' />"+
	"<input type='hidden' name='tgl_2' value='"+tgl_2+"' />"+
	"<input type='hidden' name='show_data' value='"+show_data+"' />";

	$("#print_container").html(print_container);
	$("#form_print").submit();
	$.fancybox.close();
	
}


