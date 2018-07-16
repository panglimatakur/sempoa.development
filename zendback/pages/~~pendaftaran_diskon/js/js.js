var conf 		= JSON.parse("{"+$("#config").val()+"}");
var data_page 	= $("#data_page").val();
var proses_page = $("#proses_page").val();

$(document).ready(function(){	
	$("#pattern").on("change",function(){
		var pattern 	= $(this).val();
		if(pattern !=""){
			$("#div_pattern").html("<br><img src='files/images/loading.gif' >");
			$.ajax({
				url 	: data_page,
				type	: "POST",
				data 	: {"direction":"show_pattern","pattern":pattern}, 
				success : function(response){
					$("#div_pattern").html(response);
				}
			})
		}else{
			$("#div_pattern").empty();	
		}
		$("#div_item").hide();	
	})
	$("#besar").on("keyup blur",function(){
		val = $(this).val();
		if(val < 5){
			bootbox.alert("Mohon masukan besar diskon di atas 10%, Terimakasih");
		}
	})
	$(".btn-list-item").on("click",function(){
		$(".btn-list-item-loader").html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mengambil daftar item..'); 
		$.ajax({
			url 	: data_page,
			data 	: {"direction":"show_item_list"},
			success	: function(response){
				$("#modal-product-list .dd-paging").show();
				$(".btn-list-item-loader").empty();
				$("#modal-product-list").modal("show");
				$("#modal-product-list .modal-body").html(response);
				
			}
		})
		
	})
	
	$(".item_discount_list").on("click",function(){
		id_diskon = $(this).attr("data-id-discount");
		$.ajax({
			url 	: data_page,
			type	: "POST",
			data 	: {"direction":"show_item_discount_list","id_diskon":id_diskon}, 
			success : function(response){
				$("#modal-picked-sproduct-list .modal-body").html(response);
				$("#modal-picked-sproduct-list").modal("show");
			}
		})
	})
	
	$("#sifat_jual_check").bootstrapSwitch({
		on: 'Pre Order',
		off: 'Langsung',
		size: 'sm',
		onClass: 'primary',
		offClass: 'success'
	}).on("change",function(){
		check_state = $(this).prop("checked");
		if(check_state == true)	{ $("#sifat_jual").val("preorder"); 	}
		if(check_state == false){ $("#sifat_jual").val("readystock"); 	}
	})
	
})

function open_item(el){
	var value 	  = $("#nilai").val();
 	if(value == "few"){
		$("#div_item").fadeIn("fast");;
	}else{
		$("#div_pattern").empty();
		$("#div_item").fadeOut("fast");;
	}
}
function pick_item(id_product){
	picked_pr = $("#picked_pr_"+id_product);
	if(picked_pr.length == 0){
		$("#pr_"+id_product).fadeOut(300);
		$.ajax({
			url 	: data_page,
			data 	: {"direction":"get_picked_item","id_product":id_product},
			success	: function(response){
				$("#picked_item").after(response);
			}
		})
	}else{
		$("#msg_"+id_product).html(
			"<div class='alert alert-danger alert-dismissable'>"+
				"<button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>"+
				"Produk Ini sudah dipilih, silahkan pilih yang lain"+
			"</div>");  
	}
}
function del_picked_preview_item(id_product){
	bootbox.confirm("Anda Yakin Menghapus Item Ini?",function(confirmed){
		if(confirmed == true){$("#picked_pr_"+id_product).remove();}
	})
}
function del_picked_item(id_diskon,id_product){
	bootbox.confirm("Anda Yakin Menghapus Item Ini?",function(confirmed){
		if(confirmed == true){
			$.ajax({
				url 	: proses_page, 
				type	: "POST",
				data 	: {"direction":"del_picked_item","id_diskon":id_diskon,"id_product":id_product}, 
				success : function(response){
					$("#pr_"+id_product).fadeOut();
				}
			})	
		}
	})
}
function del_discount(id_diskon){
	bootbox.confirm("Anda Yakin Menghapus Data Diskon Ini?",function(confirmed){
		if(confirmed == true){
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete_diskon","id_diskon":id_diskon},
				success	: function(){
					$("#disc_"+id_diskon).fadeOut();
				}
			})
			
		}
	})
}

function send_note(id_diskon,src_direction){
	proses_page 		= $("#proses_page").val();
	$.ajax({
		url 	: proses_page,
		type	: "POST",
		data 	: {"direction":"send_note","id_diskon":id_diskon,"src_direction":src_direction}, 
		success : function(response){
			$("#msg").html(response);
		}
	})	
}


function lastPostFunc(){ 
	lastId		= $(".wrdLatest:last").attr("data-info");		
	$('div#lastPostsLoader').html('<div style="text-align:center;margin:10px"><img src="files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: { "direction":"show_item_list",
					"lastID":lastId},
		success : function(data){
			if(data != "finish"){ $(".wrdLatest:last").after(data);}
			else				{ $("#modal-product-list .dd-paging").hide(); }
			$('div#lastPostsLoader').empty();
		}
	});
};  

