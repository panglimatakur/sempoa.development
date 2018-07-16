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

function count_pay(div){
	newval = document.getElementById(div.id).value.replace(/[^0-9]/g,'');
	$(div).val(newval);
	total 		= $("#total_bayar").val();	
	sisa 		= $("#sisa_bayar").val();
	bayar 		= $("#jml_bayar").val();
	sisa_label 	= accounting.formatMoney(sisa,"Rp.",2,".",",");
	
	if(bayar > +sisa){
		 $("#load").html("<div class='alert alert-error' style='padding:4px;'>Pembayaran Anda Melebihi Sisa Hutang Sebesar "+bayar+">"+sisa+" = "+sisa_label+"</div>");
	}else{
		$("#load").html("");
		new_sisa	= +sisa- +bayar;
		new_sisa	= accounting.formatMoney(new_sisa,"Rp.",2,".",",");
		$("#sisa_bayar_label").html(new_sisa);
	}
}
function save_hutang(id){	
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	page			= conf.page;
	proses_page 	= $("#proses_page").val();
	id_root			= $("#id_root").val();
	id_cash_flow	= $("#id_cash_flow").val();
	tgl_tempo		= $("#tgl_tempo").val();
	tgl_bayar 		= $("#tgl_bayar").val();
	termin 			= $("#termin").val();
	keterangan 		= $("#desc").val();
	bayar 			= $("#jml_bayar").val();
	total 			= $("#total_bayar").val();
	cash 			= count_cash("#jml_bayar",id_root,"");
	if(tgl_tempo != "" && tgl_bayar != "" && bayar != "" && cash == 0){ 
		$("#btn_direction").hide();
		$("#load").html("<img src='files/images/loading-bars.gif'>");
		$.ajax({
			url		: proses_page,
			type	: "POST",
			data 	: {"page":page,"direction":"save","id_root":id_root,"termin":termin,"id_cash_flow":id,"tgl_tempo":tgl_tempo,"tgl_bayar":tgl_bayar,"bayar":bayar,"total":total,"keterangan":keterangan},
			success	: function(response){
				new_cash = $("#new_cash").val();
				$("#new_cash").val("");
				money2html("#cash","#balance",new_cash,"Balance : Rp.");
				$("#tr_"+id_cash_flow).remove();
				$("#table_data tbody tr:first").before(response);	
				$.fancybox.close();
			}
		})
	}else{
		 $("#load").html("<div class='alert alert-error' style='float:left; width:50%; padding:4px; margin-left:5px'>Pengisian Form Belum Lengkap</div>");
	}
}
function print_r(){
	parent_id	= $("#parent_id").val();
	lunas 		= $("#status_lunas").val();
	tgl_1 		= $("#tgl_1").val();
	tgl_2 		= $("#tgl_2").val();

	show_data		= $("#show_data").val();

	print_container = "<input type='hidden' name='parent_id' value='"+parent_id+"' />"+
	"<input type='hidden' name='status_lunas' value='"+lunas+"' />"+
	"<input type='hidden' name='tgl_1' value='"+tgl_1+"' />"+
	"<input type='hidden' name='tgl_2' value='"+tgl_2+"' />"+
	"<input type='hidden' name='show_data' value='"+show_data+"' />";

	$("#print_container").html(print_container);
	$("#form_print").submit();
	$.fancybox.close();
	
}


