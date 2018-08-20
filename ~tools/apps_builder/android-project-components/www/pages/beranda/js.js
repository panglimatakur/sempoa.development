var apiDir		    = $("#apiDir").val();
var id_merchant	 	= $("#id_merchant").val();
var id_customer	 	= $("#id_user").val();

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
                        if(result.io_log != "" && result.msg_log != ""){
                            bootbox.alert(result.msg_log,function(){
                                location.href = "index.html";
                            });
                        }else{
                            $('#qrcode_result').modal({show:true});
                            if(response.discount > 0){ $(".btn-scan").show(); }
                            $('#qrcode_result #merchant_name').html(response.client_name);
                            $('#qrcode_result .modal-body').html(response.content);
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