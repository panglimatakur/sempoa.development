var apiDir			= $("#apiDir").val();
var id_merchant	 	= getCookie("csidkey");
var id_customer	 	= getCookie("sidkey");

$(document).ready(function(){
	$("#nm_merchant").html(getCookie("nama_merchant"));
    $("#nama").val(getCookie("cust_name")); 
	//$("#nm_customer").html(getCookie("cust_name"));
	
    $("#alamat").val(getCookie("cust_add"));
	//$("#alamat_customer").html(getCookie("cust_add"));
	 
    $("#tlp").val(getCookie("cust_phone")); 
  //  $("#phone_customer").html(getCookie("cust_phone"));
	$("#sex").val(getCookie("cust_sex")); 

	$("#email").val(getCookie("cust_email")); 
    //$("#email_customer").html(getCookie("cust_email"));

	$("#ori_user_name").val(getCookie("susername")); 
	//ch_session("regist_session");
})

function save(){
	event.preventDefault();
    var nama			= $("#nama").val(); 
    var user_name		= $("#user_name").val(); 
    var password		= $("#user_pass").val(); 
    var sex				= $("#sex").val(); 
    var alamat			= $("#alamat").val(); 
    var tlp				= $("#tlp").val(); 
    var email			= $("#email").val(); 
    var ori_user_name	= $("#ori_user_name").val(); 

	$.ajax({
		url		: apiDir+"/profile/controller.php",
		type	: 'POST',
        dataType: "jsonp",
        jsonp	: "mycallback",
		data	: {"direction":"save","id_merchant":id_merchant,"id_customer":id_customer,"nama":nama,"user_name":user_name,"user_pass":password,"sex":sex,"alamat":alamat,"tlp":tlp,"email":email,"ori_user_name":ori_user_name,"sempoakey":"99765"},
		success	: function (result) {
			bootbox.alert(result.msg);	
			setCookie("cust_name",result.cust_name);
			setCookie("cust_email",result.cust_email);
			setCookie("cust_sex",result.cust_sex);
			setCookie("cust_phone",result.cust_phone);
			setCookie("cust_add",result.cust_add);
		}, 
		function (error) {
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	});
}

