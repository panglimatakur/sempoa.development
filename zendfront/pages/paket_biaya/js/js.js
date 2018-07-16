$(document).ready(function(){		
	$("#demo").live("click",function(){
		bootbox.prompt("Masukan E-Email Anda ",function(email){
			alert(email);
			if(email != ""){
				proses_page	= $("#proses_page").val();
				$.ajax({
					url 	: proses_page,
					type	: "POST",
					data 	: {"direction":"demo","ct_email":email}, 
					success: function(response){
						if(response == 1){
							bootbox.alert("Informasi Akun Demo Sempoa.biz, telah di kirim ke email anda");	
						}else{
							bootbox.alert("Email anda belum lengkap..");	
						}
					}
				});
			}
		
		});
		
	})
});

