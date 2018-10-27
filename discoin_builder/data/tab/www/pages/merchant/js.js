var apiDir			= $("#apiDir").val();
var id_merchant	 	= getCookie("csidkey");
var id_customer	 	= getCookie("sidkey");
var nm_customer		= getCookie("cust_name");
function open_page(id_merchant_target){
	$("#page_content").html("<div class='page_loader'><img src='template/img/bars.svg'></div>");
	$.ajax({
		url 	: apiDir+"/merchant/model.php",
		type 	: "POST",
		data 	: {"direction":"load","id_merchant_target":id_merchant_target,"nm_customer":nm_customer,"id_merchant":id_merchant,"id_customer":id_customer,"sempoakey":"99765"},
        dataType: "jsonp",
        jsonp	: "mycallback",
		success:function(result){
			if(result.io_log != "" && result.msg_log != ""){
				bootbox.alert(result.msg_log,function(){
					location.href = "index.html";
				});
			}else{
				$("#page_content").html(result.content);
				logo_width = $(".merchant_logo").width();
				$(".merchant_logo").height(logo_width);
				pic_width = $(".thumbnail").width();
				$(".gallery_pic, .gallery_pic_inner").height(pic_width);
				$(".navbar").css("background-color",result.colour_1);
				
				if(result.coordinate != ""){
					myMap(result.marker_icon,result.coordinate);
				}
			}
		},
		error: function (xhr, status, errorThrown) {
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	})	
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

function open_detail_product(location,id_merchant_target,id_product){
	$('#detail .modal-body').load(location,function(result){
		$('#detail').modal({show:true});
		$(this).html("<div class='page_loader' style='margin-top:-6px'><img src='template/img/bars.svg'></div>");
		$.ajax({
			url 	: apiDir+"/merchant/ajax/product_detail.php",
			type 	: "POST",
			data 	: {"direction":"load","id_merchant_target":id_merchant_target,"id_merchant":id_merchant,"id_customer":id_customer,"id_product":id_product,"sempoakey":"99765"},
			dataType: "jsonp",
			jsonp	: "mycallback",
			success:function(result){
				$('#detail .modal-title').html("<i class='icsw16-balloons'></i> Detail Produk");
				$('#detail .modal-body').html(result.content);
			},
			error: function (xhr, status, errorThrown) {
				//The message added to Response object in Controller can be retrieved as following.
				bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
					location.href = "index.html";
				});
			}
		})	
	});	
}

function open_cart_product(id_merchant,id_product,besar,price,discount){
	$("#id_product").val(id_product);

	$('#shopping_cart').modal({show:true});
	$('#shopping_cart .modal-body').html("<div class='page_loader' style='margin-top:-6px'><img src='template/img/bars.svg'></div>");
	alert(id_merchant+"/cart/model.php");
	$.ajax({
		url 	: apiDir+"/cart/model.php",
		type 	: "POST",
		data 	: {"direction":"load","besar":besar,"price":price,"discount":discount,"id_merchant":id_merchant,"id_customer":id_customer,"id_product":id_product,"sempoakey":"99765"},
		dataType: "jsonp",
		jsonp	: "mycallback",
		success:function(result){
			$('#shopping_cart .modal-body').html(result.content);
		},
		error: function (xhr, status, errorThrown) {
			alert(xhr.status);
			//The message added to Response object in Controller can be retrieved as following.
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	});	
}

function next_product(id_merchant_target){ 
	$('div#lastPostsLoader').html('<div style="text-align:center; margin:10px"><img src="template/img/loader_v.gif"><br>Mengambil Data...</div>');
	lastId			= $(".wrdLatest:last").attr("data-info");
	$.ajax({
		url 	: apiDir+"/merchant/model.php",
		type	: "POST",
        dataType: "jsonp",
        jsonp	: "mycallback",
		data	: {"direction":"next_product","id_merchant_target":id_merchant_target,"id_merchant":id_merchant,"id_customer":id_customer,"lastID":lastId,"sempoakey":"99765"},
		success : function(result){
			//alert(result.content);
			data = $.trim(result.content);
			if (data != "") {
				$("#gallery_product .gallery li.wrdLatest:last").after(data);				
			}
			if(result.jumlah < 10){ $('div#lastPostsLoader, #footer_product').remove(); }
			$('div#lastPostsLoader').empty();
			
		},
		error: function (xhr, status, errorThrown) {
			//The message added to Response object in Controller can be retrieved as following.
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
			
		}
	});
}

function next_member(id_merchant_target){ 
	$('div#lastMemberLoader').html('<div style="text-align:center; margin:10px"><img src="template/img/loader_v.gif"><br>Mengambil Data...</div>');
	lastId			= $(".memberLatest:last").attr("data-info");
	$.ajax({
		url 	: apiDir+"/merchant/model.php",
		type	: "POST",
        dataType: "jsonp",
        jsonp	: "mycallback",
		data	: {"direction":"next_member","id_merchant_target":id_merchant_target,"id_merchant":id_merchant,"id_customer":id_customer,"lastID":lastId,"sempoakey":"99765"},
		success : function(result){
			//alert(result.content);
			data = $.trim(result.content);
			if (data != "") {
				$("#gallery_member .gallery div.memberLatest:last").after(data);				
			}else{ $("#footer_member").empty(); }

			if($.trim(result.jumlah) < 10){ $('div#lastMemberLoader, #footer_member').remove(); }
			$('div#lastMemberLoader').empty();
			
		},
		error: function (xhr, status, errorThrown) {
			//The message added to Response object in Controller can be retrieved as following.
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	});
};

function open_member(location,id_member){
	$('#detail .modal-body').load(location,function(result){
		$('#detail').modal({show:true});
		$(this).html("<div class='page_loader' style='margin-top:-6px'><img src='template/img/bars.svg'></div>");
		$.ajax({
			url 	: apiDir+"/merchant/ajax/member_detail.php",
			type 	: "POST",
			data 	: {"direction":"load","id_member":id_member,"id_merchant":id_merchant,"id_customer":id_customer,"sempoakey":"99765"},
			dataType: "jsonp",
			jsonp	: "mycallback",
			success:function(result){
				$('#detail .modal-title').html("<i class='icsw16-v-card-2'></i> Info Member");
				$('#detail .modal-body').html(result.content);
			},
			error: function (xhr, status, errorThrown) {
				//The message added to Response object in Controller can be retrieved as following.
				bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
					location.href = "index.html";
				});
			}
		})	
	});	
}

function set_amount(set,id_cart){
	cur_amount = $("#jmlh_"+id_cart).val();
	if(set == "max"){ new_amount = +cur_amount + 1; }
	if(set == "min"){ new_amount = +cur_amount - 1; }
	$("#jmlh_"+id_cart).val(new_amount);
	$.ajax({
		url	 	: api_dir+"/cart/ajax/proses.php",
		type 	: "POST",
		data 	: {"direction":"save_jml","jml_voucher":set,"id_deal":id_cart,"sempoakey":"99765"},
		dataType: "jsonp",
		jsonp	: "mycallback",
		success:function(result){
			$('#detail .modal-title').html("<i class='icsw16-v-card-2'></i> Info Member");
			$('#detail .modal-body').html(result.content);
		}
	})
}

$(document).ready(function(){
	id_merchant_target = get_url_value("id_merchant_target");
	if(id_merchant_target == false){ 
		id_merchant_target	 	= id_merchant;
	}
	open_page(id_merchant_target);
})