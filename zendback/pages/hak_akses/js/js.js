$(document).ready(function(){
	$("#id_client_level").on("change",function(){
		$("#id_client_user_level").val("");
		data_page 		= $("#clients_page").val();
		id_client_level	= $(this).val();
		if(id_client_level == ""){
			$("#div_clients").empty();
			$("#div_modules").empty();
			$("#input_user_level").slideUp(200);
		}else{
		$("#div_clients").html("<img src='files/images/loading-bars.gif'>");
			$.ajax({
				url 	: data_page,
				type	: "POST",
				data	: {"show":"clients","id_client_level":id_client_level},
				success : function(response){
					$("#div_clients").html(response);
				}
			})
		}
	})
	
})

function get_module(){
	treeview_check_page	= $("#treeview_check_page").val();
	data_page 			= $("#modules_page").val();
	single				= $("#single").val();
	id_client_level		= $("#id_client_level").val();
	id_client_user_level= $("#id_client_user_level").val();
	client_id			= $("#client_id").val();
	if(id_client_level != "" && id_client_user_level != ""){
		$("#div_modules").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
		$.ajax({
			url 	: data_page,
			type	: "POST",
			data	: {"show":"modules","single":single,"client_id":client_id,"id_client_level":id_client_level,"id_client_user_level":id_client_user_level},
			success : function(response){
				var conf 			= JSON.parse("{"+$("#config").val()+"}");
				$("#div_modules").html(response);
				$('#tree2').checkboxTree({
					collapseImage: conf.dirhost+'/'+treeview_check_page+'/images/bminus.png',
					expandImage: conf.dirhost+'/'+treeview_check_page+'/images/bplus.png'
					
				});
			}
		})
	}else{
		bootbox.alert("Pengisian Form Belum Lengkap?",function(){
			$("#id_client_user_level").val("");
		});
		
	}
}

function write_client(el){
	id_client = $(el).val();
	$("#client_id").val(id_client);
	$("#input_user_level").slideDown(200);
	$("#id_client_user_level").val("");
	$("#div_modules").empty();
}