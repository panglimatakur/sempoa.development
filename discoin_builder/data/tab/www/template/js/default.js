var apiDir		= $("#apiDir").val();
function setCookie(key, value) {
	localStorage.setItem(key,value);
}
function getCookie(key) {
	var keyValue = localStorage.getItem(key);
	return keyValue ;
}
function removeCookie(key) {	
	localStorage.removeItem(key);
}


$(document).ready(function(){
	$("#label_merchant").html(getCookie("nama_merchant"));
	$(".merchant-logo img").attr("src",getCookie("logo_merchant"));
	$(".btn-scan").css("background-color",getCookie("color_2"));
	$("#sidebar, .navbar").css("background-color",getCookie("color_1"));
	$(".sidebar-header").css("background-color",getCookie("color_2"));
	$("#label_userphoto").attr("src",getCookie("path_photo"));
	$("#profile_nama").html(getCookie("cust_name"));
	

	page_history = getCookie("page_history");
	if(page_history == null){
		History.pushState({path: ""},'', './default.html?page=beranda');
	}else{
		History.pushState({path: ""},'', './default.html'+page_history);
	}
	load_page_content();

    $(".page_link").on("click",function(e){
		//adapter.publish("/online_customer_chat",{"status":"online","id_customer":""+getCookie("sidkey")+""});
		$(".navbar").css("background-color",getCookie("color_1"));
        e.preventDefault();
		var urlPath = $(this).attr('data-page');
        $('#sidebar').removeClass('active');
		$('.overlay').fadeOut();
		setCookie("page_history",urlPath);
		History.pushState({path: ""},'', './default.html'+urlPath); // When we do this, History.Adapter will also execute its contents. 		
		load_page_content();
	})


	$("#btn_logouot").on("click",function(){
		$("#logout_loader").html("<img src='template/img/loading.gif'>");
		$.ajax({  
			url 	: apiDir+"/login/model.php",
			type 	: "POST",
			data 	: {"direction":"logout","sempoakey":"99765"},
			dataType: "jsonp",
			jsonp	: "mycallback",
			success : function(result) {
				removeCookie("communities");
				removeCookie("sidkey");
				removeCookie("susername");
				removeCookie("spassword");
				removeCookie("cust_name");
				removeCookie("cust_email");
				removeCookie("cust_sex");
				removeCookie("cust_phone");
				removeCookie("cust_add");

				removeCookie("path_photo");
				removeCookie("number");
				removeCookie("join_date");
				removeCookie("exp_date");
				
				removeCookie("csidkey");
				removeCookie("nama_merchant");
				removeCookie("color_1");
				removeCookie("color_2");
				removeCookie("saldo");
				removeCookie("page_history");
				location.href = "index.html";
			},
            error: function (xhr, status, errorThrown) {
                //The message added to Response object in Controller can be retrieved as following.
                alert(xhr.responseText);
            }
		});
	})
	
 })

function load_page_content() {
	page = get_url_value("page");
	$.ajax({  
		type    : 'post',
		url     : "pages/"+page+"/view.html",
		success : function(response) {
			$('#main_content').html(response);
			//adapter.publish("/online_customer_chat",{"status":"online","id_customer":""+getCookie("sidkey")+""});
		}
	});
}

function get_url_value(variable) {
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) {
			var pair = vars[i].split("=");
			if(pair[0] == variable){return pair[1];}
	}
	return(false);
}
