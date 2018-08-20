function setCookie(key, value) {
	localStorage.setItem(key,value);
}
function getCookie(key) {
	var keyValue = storage.getItem(key);
	return keyValue ;
}
//localStorage.removeItem("myCat");
var apiDir		= $("#apiDir").val();
function open_page(){
	id_merchant	 	= $("#id_merchant").val();
 	$.ajax({
		url 	: apiDir+"/login/model.php",
		type 	: "POST",
		data 	: {"direction":"load","id_coin":id_merchant,"sempoakey":"99765"},
        dataType: "jsonp",
        jsonp	: "mycallback",
		success:function(result){
            $("#merchant_name").html(result.m_name);
            $("#merchant_logo").attr("src",result.logo);
            $("body").css("background-color",result.color_1);
            $(".btn-login").css({"background-color":result.color_2,"border":"1px solid "+result.color_1}); 
			$(".login").fadeIn("fast");
		},
		error: function (xhr, status, errorThrown) {
			//The message added to Response object in Controller can be retrieved as following.
			alert(xhr.responseText);
		}
	})	
}

$(document).ready(function(){
	if (localStorage.getItem("susername") === null && 
		localStorage.getItem("sidkey") === null &&
		localStorage.getItem("cust_email") === null) {
		open_page();
	}else{
		location.href = "default.html";
	}
	
    $(".add_btn").on("click",function(e){
        e.preventDefault();
        btn_val = $(this).attr("data-value");
        if(btn_val == "p"){ 
            $(".form_start, #form_forget_add").slideUp("fast");
            $("#form_forget, #form_login_add, #form_register_add").slideDown("fast"); 
        }
        if(btn_val == "r"){ 
            $(".form_start, #form_register_add").slideUp("fast");
            $("#form_register, #form_login_add, #form_forget_add").slideDown("fast"); 
        }
        if(btn_val == "l"){ 
            $(".form_start, #form_login_add").slideUp("fast");
            $("#form_login, #form_register_add, #form_forget_add").slideDown("fast"); 
        }
    })



	var supportsTouch = false;
	if ('ontouchstart' in window) 				{ supportsTouch = true;	 } 
	else if(window.navigator.msPointerEnabled) 	{ supportsTouch = true;  } 
	//alert(supportsTouch);
	var clickOrTouch = (('ontouchstart' in window)) ? 'touchend' : 'click';
	$("#login").on("click",function(e){
        e.preventDefault();
		id_coin	 	= $("#id_merchant").val();
		username 	= $("#username").val();
		password 	= $("#password").val();
        titanium 	= $("#titanium").val();
		$("#loader").html("<div style='text-align:center; margin-bottom:10px'>Mohon Tunggu Sebentar...</div>");
		$.ajax({
			url : apiDir+"/login/model.php",
			type :"POST",
            data : {"direction":"login","id_coin":id_coin,"titanium":titanium,"username":username,      
                    "password":password,"sempoakey":"99765"},
			dataType: "jsonp",
			jsonp	: "mycallback",
			success: function(result){
				$("#loader").empty();
				if(result.io == 1){
					setCookie("communities",result.scomidkey);
					setCookie("sidkey",result.sidkey);
					setCookie("susername",result.susername);
					setCookie("spassword",result.spassword);
					setCookie("cust_name",result.cust_name);
					setCookie("cust_email",result.cust_email);
					setCookie("cust_sex",result.cust_sex);
					setCookie("cust_phone",result.cust_phone);
					setCookie("cust_add",result.cust_add);

					setCookie("path_photo",result.path_photo);
					setCookie("number",result.number);
					setCookie("join_date",result.join_date);
					setCookie("exp_date",result.exp_date);
					
					setCookie("logo_merchant",result.mlogo);
					setCookie("csidkey",result.csidkey);
					setCookie("nama_merchant",result.nama_merchant);
					setCookie("color_1",result.color_1);
					setCookie("color_2",result.color_2);
					setCookie("saldo",result.saldo);
					location.href = "default.html";
				}else{
					bootbox.alert("<span style='color:#000; font-weight:bold'>"+result.msg+"</span>");	
				}
			},
            error: function (xhr, status, errorThrown) {
                //The message added to Response object in Controller can be retrieved as following.
                alert(xhr.responseText);
            }
		})
	})

	$("#forgetpass").on("click",function(){
		id_coin	 		= $("#id_merchant").val();
		forgot_email 	= $("#forgot_email").val();
		$("#loader").html("<div style='text-align:center; margin-bottom:10px'>Mohon Tunggu Sebentar...</div>");
		$.ajax({
			url : apiDir+"/login/model.php",
			type :"POST",
			dataType: "jsonp",
			jsonp	: "mycallback",
			data : {"direction":"forget","id_coin":id_coin,"forgot_email":forgot_email,"sempoakey":"99765"},
			success: function(response){
				$("#loader").empty();
				bootbox.alert(response);
			}
		})
	})
	
	$("#register").on("click",function(){
		id_coin	 		= $("#id_merchant").val();
		nama	 		= $("#rname").val();
		email	 		= $("#rusername").val();
		user_pass	 	= $("#rpassword").val();
		kuser_pass	 	= $("#kpassword").val();
		sex	 			= $("#sex").val();
		tlp	 			= $("#hp").val();
		if(nama != "" && email != "" && user_pass != "" && sex != "" && tlp != ""){
			if(user_pass == kuser_pass){
				$("#loader").html("<div style='text-align:center; margin-bottom:10px'>Mohon Tunggu Sebentar...</div>");
				$.ajax({
					url 	: apiDir+"/login/model.php",
					type 	:"POST",
					dataType: "jsonp",
					jsonp	: "mycallback",
					data 	: {"direction":"insert","id_coin":id_coin,"nama":nama,
							   "email":email,"user_pass":user_pass,"tlp":tlp,
							   "sex":sex,"sempoakey":"99765"},
					success	: function(result){
						$("#loader").empty();
						bootbox.alert(result.msg);
					}
				})
			}else{
				bootbox.alert("Konfirmasi password tidak sama, silahkan ulangi Password");
			}
		}else{
			bootbox.alert("Pengisian form belum lengkap...");
		}
	})
})

