var apiDir		    = $("#apiDir").val();
var id_merchant	 	= getCookie("csidkey");
var id_customer	 	= getCookie("sidkey");

$(document).ready(function(){
    $("#label_username").html(getCookie("cust_name"));
    $("#label_coin").val(getCookie("number"));
    $("#label_join").html(getCookie("join_date"));
    $("#label_expired").html(getCookie("exp_date"));

    $('#scan').on("click", function() {
        cordova.plugins.barcodeScanner.scan(
            function (result) {
                $.ajax({
                    url 	: apiDir+"/beranda/model.php",
                    type 	: "POST",
                    data 	: {"direction":"scan","id_coin":result.text,"sempoakey":"99765","id_merchant":id_merchant,"id_customer":id_customer},
                    dataType: "jsonp",
                    jsonp	: "mycallback",
                    success:function(response){
                        if(response.io == "match"){
                            $('#qrcode_result').modal("show");
                            if(response.discount > 0){ $(".btn-scan").show(); }
                            $('#qrcode_result #merchant_name').html(response.client_name);
                            $('#qrcode_result .modal-body').html(response.content);
                        }else{
                            bootbox.alert(response.msg);
                        }
                    }
                })	
                            
            }, 
            function (error) {
                bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
                    location.href = "index.html";
                });
            }
        );
    });
 })