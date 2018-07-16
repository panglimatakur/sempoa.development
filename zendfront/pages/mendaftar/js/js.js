var proses_page = $("#ct_proses_page").val(); 
var data_page 	= $("#data_page").val(); 
$(document).ready(function(){
	$("#level-help").on("click",function(){
		id_jabatan = $("#id_jabatan").val();
		nm_jabatan = $("#id_jabatan option[value='"+id_jabatan+"']").text() 
		if(id_jabatan != ""){
			$.ajax({
				url 	: data_page,
				type	: "POST",
				data	: {"direction":"get_page","id_jabatan":id_jabatan,"nm_jabatan":nm_jabatan},
				success	: function(result){
					$("#myModal").modal("show");
					$("#myModal .modal-body").html(result);
				}
			})
		}
	})
	$("#id_jabatan").on("change",function(){
		id_jabatan = $(this).val();
		$("#level-help").attr("data-level",id_jabatan);
	})
	$("#registered_status").bootstrapSwitch({
		on: 'Sudah Pernah',
		off: 'Belum Pernah',
		size: 'sm',
		onClass: 'primary',
		offClass: 'default'
	}).on("change",function(){
		check_state = $(this).prop("checked");
		if(check_state ==  true){
			$("#ever").fadeIn();
			$("#never").fadeOut();	
			$("#merchant_header").html("<b>Cari Merchant / Perusahaan</b>");
			$("#merchant_name").chosen({
				create_option: true,
				persistent_create_option: true,
				create_option_text: 'add',
			}).change(function() {
				choosen_val = $("#merchant_name").chosen().val();
				$("#merchant_id").val(choosen_val)
			});
			get_jabatan("0");
		}else{
			$("#ever").fadeOut();
			$("#never").fadeIn();
			$("#merchant_header").html("<b>Masukan Informasi Merchant / Perusahaan</b>");
			get_jabatan("1");
		}
	})
	
	$("#formID").on("submit",function(){
		check_state = $("#registered_status").prop("checked");
		if(check_state == true) { existing = "2"; }
		if(check_state == false){ existing = "1"; }
		
		id_merchant		= $("#id_merchant").val();  
		id_jabatan		= $("#id_jabatan").val();  
		nama_pemohon	= $("#nama_pemohon").val();  
		kontak			= $("#kontak").val();   
		email			= $("#email").val();  
		new_pass		= $("#new_pass").val();  
		konf_new_pass	= $("#konf_new_pass").val(); 
		
		
		if(existing == "2"){ if(id_merchant != ""){ done_merchant = '2'; }else{ done_merchant = 0; } }
		
		if(existing == "1"){ 
			nama		= $("#nama").val(); 
			email_brand	= $("#email_brand ").val();
			tlp			= $("#tlp").val();
			alamat 		= $("#alamat").val(); 
			deskripsi	= $("#deskripsi").val();
			if(nama != "" && email_brand != "" && tlp != "" && alamat != "" && deskripsi != ""){ 
				done_merchant = '2'; 
			}else{
				done_merchant = '1'; 
			}
		}
		if(done_merchant == '2' && id_jabatan != "" && nama_pemohon != "" && kontak != "" && 
		   email != "" && new_pass != ""){
		   if(new_pass == konf_new_pass){
				parameter 	= $("#formID").serialize();
				parameter 	= "direction=register&existing="+existing+"&"+parameter;
				
				$.blockUI({ 
							message	: '<div class="col-md-2"><div class="coffee-loader">'+
										'<img src="../zendfront/templates/multicolor/images/coffee-dribbble.gif" width="100%">'+
									  '</div></div>'+
									  '<div class="col-md-10" style="color:#fff;padding-top:20px;text-align:left; padding-left:0">'+
										'<h4><b>Mohon tunggu sebentar, pendaftaran anda sedang di proses...</b><h4>'+
									  '</div>'+
									  '<div class="clearfix"></div>',
							css		: {'border': 'none',
									   'background':'transparent',
									   'padding-bottom':'15px',
									   'height':'150px'
									  }
						 });
				$.ajax({
					url 	: proses_page,
					type	: "POST",
					data	: parameter,
					success	: function(result){
						data = JSON.parse(result);
						bootbox.alert(data.msg,function(){
							if(data.io == "3"){ location.href = data.redirect_page; }
						});
						$.unblockUI();
					}
				})
		   }else{
			bootbox.alert("Password anda tidak sama, mohon konfirmasikan password anda pada kolom \"Konfirmasi Password\" ");	
		   }
		}else{
			bootbox.alert("Pegisian Form, belum lengkap, silahkan lengkapi kolom isian dengan simbol bintang ( <span style='color:#990000;'>*</span> )");	
		}
		return false;
	});
	
})

get_jabatan = function(id_jabatan){
	$("#jabatan_loader").html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"direction":"get_jabatan","id_jabatan":id_jabatan},
		success	: function(response){
			$("#id_jabatan").html(response);
			$("#jabatan_loader").empty();
		}
	})
}