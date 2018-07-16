$(document).ready(function(){
	$(".merchant-list").slimScroll({height: '922px'});
	$(".iCheck").bootstrapSwitch({
		on: 'Aktif',
		off: 'Tidak Aktif',
		size: 'sm',
		onClass: 'primary',
		offClass: 'default'
	});
	
	$("#show_coin").on("click",function(){
		data 		= $("#data_page").val();
		list_coin	= $("#list_coin").val();
		$("#divparent_id").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
		$.ajax({
			url 	: data,
			data	: {"direction":"get_customer","coin_numbers":list_coin},
			success : function(response){
				$("#divparent_id").html(response);
			}
		});
	});
	
	$("#tgl_all").on("change",function(){
		tgl_all = $(this).val();
		$(".tgl_aktif").val(tgl_all);
	})
	$("#bln_all").on("change",function(){
		bln_all = $(this).val();
		$(".bln_aktif").val(bln_all);
	})
	$("#thn_all").on("change",function(){
		thn_all = $(this).val();
		$(".thn_aktif").val(thn_all);
	})
});

function lastPostFunc(){ 
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	data_page 		= $("#data_page").val();
	$('div#lastPostsLoader').html('<div style="text-align:center; margin:10px"><img src="'+conf.dirhost+'/files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	lastId			= $(".wrdLatest:last").attr("data-info");
	last_row		= $("#last_row").val();
	id_client_parent= $("#id_client_parent").val();
	tgl_all			= $("#tgl_all").val();	
	bln_all			= $("#bln_all").val();
	thn_all			= $("#thn_all").val();
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"direction":"get_customer","lastId":lastId,"last_row":last_row,"id_client_parent":id_client_parent,"tgl_all":tgl_all,"bln_all":bln_all,"thn_all":thn_all,"display":"list_report"},
		success : function(data){
			if (data != "") {
				$("#last_row").remove();
				$("#paging").before(data);
				$("#paging").remove();
			}
			if(lastId == ""){
				$('div#lastPostsLoader').remove();
			}
			$('div#lastPostsLoader').empty();
			
		}
	});
}

function set_status(id_customer){
	name 		= $("#cust_name_"+id_customer).val();
	phone 		= $("#cust_phone_"+id_customer).val();
	email 		= $("#cust_email_"+id_customer).val();
	id_status 	= $("#id_status_"+id_customer).is(":checked");
	tgl 		= $("#tgl_"+id_customer).val();
	bln 		= $("#bln_"+id_customer).val();
	thn 		= $("#thn_"+id_customer).val();
	id_titanium = "";
	if($("#id_client_titanium_"+id_customer).length > 0){
		id_titanium = $("#id_client_titanium_"+id_customer).val();
	}
	if(tgl != "" && bln != "" && thn != "" && phone != "" && email != ""){
		expiration_date	= thn+"-"+bln+"-"+tgl;
		if(id_status == true)	{ id_status = "3"; }
		else					{ id_status = "1"; }
		proses_page = $("#proses_page").val();
		$.ajax({
			url		: proses_page,
			type	: "POST",
			data 	: {"direction":"set_status","id_customer":id_customer,"cust_name":name,"cust_phone":phone,"cust_email":email,"id_status":id_status,"id_titanium":id_titanium,"expiration_date":expiration_date},
			success : function(response){
				bootbox.alert("Status Aktif <b class='code'>"+name+"</b> Berhasil di Set");
			}
		})
	}else{
		bootbox.alert("Tanggal Expired Customer <b class='code'>"+name+"</b> Belum Ditentukan",function(){
			$("#id_status_"+id_customer).removeAttr("checked");
		});
	}
}

function getparent(id){
	data 		= $("#data_page").val();
	$("#divparent_id").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
	$.ajax({
		url 	: data,
		data	: {"direction":"get_customer","id_client_parent":id},
		success : function(response){
			$("#divparent_id").html(response);
		}
	});
}

