var conf = JSON.parse("{"+$("#config").val()+"}");
var ring = JSON.parse("{"+$("#ringtones").val()+"}");
function new_balance(){
		new_cash	= $("#cash").val();
		res 		= accounting.formatMoney(new_cash,"Balance : Rp.",2,".",","); // €4.999,99	
		$("#balance").html(res);	
}
function substr_count(haystack, needle, offset, length) {
    var pos = 0,
        cnt = 0;

    haystack += '';
    needle += '';
    if (isNaN(offset)) {
        offset = 0;
    }
    if (isNaN(length)) {
        length = 0;
    }
    offset--;

    while ((offset = haystack.indexOf(needle, offset + 1)) != -1) {
        if (length > 0 && (offset + needle.length) > length) {
            return false;
        } else {
            cnt++;
        }
    }
    return cnt;
}

function numeric(txb) {
   txb.value = txb.value.replace(/[^0-9]/g,'');
}

function count_cash(element,id_root,first_value){
	value		= $(element).val();
	cash 		= $("#cash").val();
	if(value != "" && value != 0){
		if(id_root == 1){
			new_cash	= (+cash - +first_value)+ +value;
			fail		= 0;
		}else{
			new_cash	= (+cash + +first_value)-value;
			if(value > +cash){ fail = 1; }else{ fail = 0; }
		}
	}else{
		new_cash = cash;
		fail = 0;	
	}
	if(fail == 1){
		$.fancybox.close();
		$("#balance").css("color","#CC0000"); 
		if($(".modal-body").length == 0){
			bootbox.alert("Uang kas anda tidak mencukupi untuk transaksi ini",function(){
				cash 		= $("#cash").val();
				res = accounting.formatMoney(cash,"Balance : Rp.",2,".",","); // €4.999,99	
				$("#balance").html(res);
				$("#balance").css("color","#000000");
				$(element).val("");
			});
		}
	}else{
		$("#balance").css("color","#000000");
		res = accounting.formatMoney(new_cash,"Balance : Rp.",2,".",","); // €4.999,99	
		$("#balance").html(res);
		$("#new_cash").val(new_cash);
		$("#cash").val(new_cash);
	}
	
	return fail;
}

function count_transaction(el,id_root,first_val){
	new_val = $(el).val();
	numeric(el);
	count_cash(el,id_root,first_val);
}

function money2input(element,element_label,new_value,caption){
	$(element).val(new_value);
	new_result_label = accounting.formatMoney(new_value,caption,2,".",","); //new_kredit,"Rp.",2,".",",")
	$(element_label).val(new_result_label);
}
function money2html(element,element_label,new_value,caption){
	$(element).val(new_value);
	new_result_label = accounting.formatMoney(new_value,caption,2,".",","); //new_kredit,"Rp.",2,".",",")
	$(element_label).html(new_result_label);
}
function more_page(el){
	value = $(el).val();
	$("#pagesize").val(value);
	$("#form_paging").submit();
}

function select_category(id_kategori){
	$("#id_kategori").val(id_kategori);
	$(".kategori_list li").css({"font-weight":"normal","color":"","border":"1px solid #EBEBEB","background-color":"#FFFFFF"});
	$("#cat_"+id_kategori).css({"font-weight":"bold","color":"#F9ECF7","border":"1px solid #F9ECF7","background-color":"#F9ECF4"});
}

function pushit(channel,message, nickname) {
  if(message != false){
    tulcom.publish(channel, { msg: message, nick: nickname});
  }
}

function chat_ring(sound){
	 $('<audio id="chatAudios">'+
		'<source src="'+conf.dirhost+'/files/audio/'+sound+'/'+sound+'.ogg" type="audio/ogg">'+
		'<source src="'+conf.dirhost+'/files/audio/'+sound+'/'+sound+'.mp3" type="audio/mpeg">'+
	'</audio>').appendTo('body');
	$('#chatAudios')[0].play();
	
}