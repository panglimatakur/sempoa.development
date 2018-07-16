var data_page 	= $("#data_page").val();
var proses_page = $("#proses_page").val();

$(document).ready(function(){	
	$('.community-merchants').slimScroll({height: '500px'});
	$('.merchant-discount-list').slimScroll({height: '30px'});
	
	
	
	$("#new_comm").on("click",function(){
		$("#div_new_comm").slideDown(300);
		$(this).slideUp(300);
	})
	$("#close_comm").on("click",function(){
		$("#div_new_comm").slideUp(300);
		$("#new_comm").slideDown(300);
	})
	$("#build").on("click",function(){
		nm_community = $("#comm_name").val();
		if(nm_community != ""){
			bootbox.confirm("Anda yakin akan merancang komunitas <b class='code' style='text-transform:capitalize'>"+nm_community+"</b> ?,<br>Sebagai informasi, untuk bergabung lebih dari 1 (satu) komunitas, maka anda akan dibebankan biaya infrastruktur komunitas sebesar Rp.3.500.000 (<i>tiga juta lima ratus ribu rupiah</i>) per tahun, selama anda bergabung di dalam komunitas ini, terimakasih",function(confirmed){
				if(confirmed == true){
					$("#build").after("<img src='files/images/loading-bars.gif' id='new_comm_load'>");
					$.ajax({
						url 	: proses_page,
						type	: "POST",
						data 	: {"direction":"add","nm_community":nm_community}, 
						success : function(response){
							$("#div_new_comm").slideUp(300,function(){ $("#new_comm_load").remove(); });
							$("#new_comm").slideDown(300);
							$(".tbl_community").prepend(response);
						}
					})	
				}
			})
		}
	})
	 $("#to_comm").on("change",function(){
  		var conf 		= JSON.parse("{"+$("#config").val()+"}");
		from_id_comm	= $("#from_id_comm").val();
		from_id_pur		= $("#from_id_pur").val();
		to_id_comm		= $(this).val();
		if(to_id_comm != ""){
			$("#dir_load").html("<img src='files/images/loading-bars.gif'>");
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				data	: {"direction":"move","from_id_pur":from_id_pur,"from_id_comm":from_id_comm,"to_id_comm":to_id_comm},
				type	: "POST",
				success	: function(response){
					result 	= JSON.parse(response);
					msg		= result.msg; 
					io		= result.io;
					nm_comm	= result.nm_comm; 
					if(io == 2){
						var old_table = "<tr id='id_merchant_"+conf.cidkey+"' >"+$("#tbl_community_"+from_id_comm+" #id_merchant_"+conf.cidkey).html()+"</tr>";
						$("#tbl_community_"+from_id_comm+" #comm_footer_"+from_id_comm).remove();
						$("#tbl_community_"+from_id_comm+" #id_merchant_"+conf.cidkey).remove();
						$("#tbl_community_"+to_id_comm+" table tbody tr:last").before(old_table);
						
						var footer = 
							'<a href="'+conf.dirhost+'/modules/'+conf.page+'/ajax/direction.php?id_com='+to_id_comm+'&id_pur='+from_id_pur+'&direction=move" class="btn fancybox fancybox.ajax" style="margin-left:4px">'+
								'<i class="icsw16-walking-man" ></i>Pindah'+
							'</a>'+
							'<a href="javascript:void()" class="btn" style="margin-left:4px" onclick="del_comm(\''+from_id_pur+'\',\''+to_id_comm+'\',\''+nm_comm+'\')">'+
								'<i class="icsw16-bended-arrow-left"></i>Keluar'+
							'</a>';
						$("#tbl_community_"+to_id_comm+" #comm_footer_"+to_id_comm).html(footer);
					}						
					$("#dir_load").html(msg);
				}
			})
		}else{
			$("#dir_load").empty();
		}
	 })
});

function remove_merchant(id_client,id_community){
	bootbox.confirm("Anda yakin akan menghapus merchant ini dari komunitas",function(confirmed){
		if(confirmed == true){
			$.ajax({
				url 	: proses_page,
				type	: "POST",
				data 	: {"direction":"remove_merchant","from_id_comm":id_community,"id":id_client}, 
				success : function(response){
					$("#id_merchant_"+id_client).fadeOut();
				}
			})
		}
	})
	
}
function exit_community(id_purple,id_community,nm_community){
	bootbox.confirm("Anda yakin akan keluar komunitas <b class='code'>"+nm_community+"</b> ?,<br>Sebagai informasi, jika sebelumnya anda bergabung kedalam komunitas <b class='code'>"+nm_community+"</b> dikenakan biaya infrastruktur komunitas, maka biaya apapun yang telah anda keluarkan sebelumnya, tidak akan dikembalikan lagi, terimakasih",function(confirmed){
		if(confirmed == true){
		$("#tbl_community_"+id_community+" table").after("<div style='text-align:center; margin:4px 0 0 0' id='div_comm_"+id_community+"'><img src='files/images/loading-bars.gif'></div>");
			$.ajax({
				url 	: proses_page,
				type	: "POST",
				data 	: {"direction":"out","from_id_purple":id_purple,"to_id_comm":id_community}, 
				success : function(response){
					$("#tbl_community_"+id_community+" #id_merchant_"+conf.cidkey).fadeOut(200);
					$("#div_comm_"+id_community).remove();
					$("#comm_footer_"+id_community).empty();
				}
			})	
		}
	})
}
function view_discount(id_diskon){
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data 	: {"direction":"view_discount","id_diskon":id_diskon}, 
		success : function(response){
			$("#modal-ajax-beranda .modal-body").html(response);
			$("#modal-ajax-beranda").modal("show");
		}
	})	
}

function item_discount_list(id_diskon){
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data 	: {"direction":"show_item_discount_list","id_diskon":id_diskon}, 
		success : function(response){
			$("#picked_item").html(response);
		}
	})
}

