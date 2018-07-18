var apiDir			= $("#apiDir").val();
var id_merchant	 	= getCookie("csidkey");
var id_customer	 	= getCookie("sidkey");

function open_page(){
	$(".w-box-header").css("background-color",getCookie("color_2"));
	$("#page_content").html("<div class='page_loader'><img src='template/img/bars.svg'></div>");	
 	$.ajax({
		url 	: apiDir+"/community/model.php",
		type 	: "POST",
		data 	: {"direction":"load","id_merchant":id_merchant,"id_customer":id_customer,"sempoakey":"99765"},
        dataType: "jsonp",
        jsonp	: "mycallback",
		success	: function(result){
			if(result.io_log != "" && result.msg_log != ""){
				bootbox.alert(result.msg_log,function(){
					location.href = "index.html";
				});
			}else{
				$("#page_content").html(result.content);
				myMap(result.coordinate);
				//$(".w-box-header").css({"background-color":getCookie("color_1"),"padding-top":"2px"});
			}
		}, 
		error	: function(){
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	})	
}

function enter_merchant(id_community,id_merchant_target){
	$("#page_content").html("<div class='page_loader'><img src='template/img/bars.svg'></div>");
	$("#id_merchant").val(id_merchant);
	page_merchant = "?page=merchant&id_merchant_target="+id_merchant_target;
	History.pushState({path: ""},'', './default.html'+page_merchant);
	setCookie("page_history",page_merchant);
	load_page_content();
}

function view_discount(id_diskon){
	$.ajax({
		url 	: apiDir+"/merchant/ajax/discount_detail.php",
		type	: "POST",
		data 	: {"direction":"view_discount","id_diskon":id_diskon}, 
		success : function(response){
			$("#detail .modal-body").html(response);
			$("#detail").modal("show");
		}
	})	
}

function item_discount_list(id_diskon){
	$.ajax({
		url 	: apiDir+"/merchant/ajax/discount_detail.php",
		type	: "POST",
		data 	: {"direction":"show_item_discount_list","id_diskon":id_diskon}, 
		success : function(response){
			$("#picked_item").html(response);
		}
	})
}

function lastPostFunc(){ 
	$('div#lastPostsLoader').html('<div style="text-align:center;"><img src="template/img/loader_v.gif"><br>Mengambil Data...</div>');
	lastId		= $(".wrdLatest:last").attr("data-info");
	id_com		= $("#id_com").val();	
	$.ajax({
		url 	: apiDir+"/community/model.php",
		type	: "POST",
        dataType: "jsonp",
        jsonp	: "mycallback",
		data	: {"direction":"next_community","lastID":lastId,"id_com":id_com,"id_merchant":id_merchant,"id_customer":id_customer,},
		success : function(result){
			//alert(result.content);
			data = $.trim(result.content);
			if (data != "") {
				$("#lastPostsLoader").before(data);
			}
			if(lastId == ""){
				$('div#lastPostsLoader').remove();
			}
			$('div#lastPostsLoader').empty();
			
		}, 
		error:function(){
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	});
}

$(document).ready(function(){
	open_page();
})