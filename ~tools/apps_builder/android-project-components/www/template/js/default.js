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
	
	
	var History = window.History;
	if (History.enabled) {
		var page = get_url_value('page');
		var path = page ? page : 'beranda';
		// Load the page
		load_page_content(path);
	} else {
		return false;
	}

	// Content update and back/forward button handler
	History.Adapter.bind(window, 'statechange', function() {
		var State = History.getState();	
		if(State.data.path == undefined){ dataPath = "beranda"; 		}
		else							{ dataPath = State.data.path; 	} 
		load_page_content(dataPath);
	});


    $(".page_link").on("click",function(e){
		$(".navbar").css("background-color",getCookie("color_1"));
        e.preventDefault();
		var urlPath = $(this).attr('data-page');
        var title = $(this).text();	
        $('#sidebar').removeClass('active');
        $('.overlay').fadeOut();
		History.pushState({path: urlPath}, title, './default.html?page=' + urlPath); // When we do this, History.Adapter will also execute its contents. 		
    })
	function load_page_content(page) {
		$.ajax({  
			type    : 'post',
			url     : "pages/"+page+"/view.html",
			success : function(response) {
				$('#main_content').html(response);
				call_page_js(page);		
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
				location.href = "index.html";
			},
            error: function (xhr, status, errorThrown) {
                //The message added to Response object in Controller can be retrieved as following.
                alert(xhr.responseText);
            }
		});
	})
	
 })
 function getUrlParameter(sParam) {
	var sPageURL = decodeURIComponent(window.location.search.substring(1)),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;

	for (i = 0; i < sURLVariables.length; i++) {
		sParameterName = sURLVariables[i].split('=');

		if (sParameterName[0] === sParam) {
			return sParameterName[1] === undefined ? true : sParameterName[1];
		}
	}
}

