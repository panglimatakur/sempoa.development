<?php if(!empty($dirhost)){ $map_api = $dirhost."/libraries/map_api/"; }  ?>
	<script src="<?php echo @$map_api; ?>map.js"></script>
    <!--<script type="text/javascript" src="<?php echo @$map_api; ?>jquery-1.8.2.min.js"></script>-->
    <style type="text/css">
      .container_map{
        height: 70%;
        width: 90%;
		text-align:center;
		padding:6px;
		border:1px solid #333;
		margin-left:auto;
		margin-right:auto;
		overflow:scroll; 
	  }
      #map-canvas {
        height: 80%;
        width: 100%;
      }
      #outputDiv {
        font-size: 11px;
      }
	</style>      
    <script>
		var map;
		var geocoder;
		function initialize() {
		  geocoder = new google.maps.Geocoder();
		}
		
		function calculateDistances() {
			reset_money();
			var destinations	= [];
			$(".client_add").each(function(){
				destination = $(this).val();
				alert(destination); 
				destinations.push(destination);
			});
			var origin1      = 'Sekeloa, Coblong, Bandung, Jawa Barat, Indonesia'; //new google.maps.LatLng(55.930, -3.118);
			//var origin2      = 'Greenwich, England';
			var kota		 =  $("#kota option:selected").text();
			var kecamatan	 =  $("#kecamatan option:selected").text();
			var kelurahan	 =  $("#kelurahan option:selected").text();
		 	var destinationA =  kelurahan+","+kecamatan+","+kota;
			destinations.push(destinationA);
			//alert(destinations);
		  	if(destinationA != ""){
			  var service = new google.maps.DistanceMatrixService();
			 
			  service.getDistanceMatrix({
				  origins: [origin1],
				  destinations: destinations,
				  travelMode: google.maps.TravelMode.DRIVING,
				  unitSystem: google.maps.UnitSystem.METRIC,
				  avoidHighways: false,
				  avoidTolls: false
				}, callback);
		  	}else{
				alert("Tentuan Lokasi Antar");  
		  	}
		}
		
		function callback(response, status) {
		  if (status != google.maps.DistanceMatrixStatus.OK) {
			alert('Error was: ' + status);
		  } else {
			var origins = response.originAddresses;
			var destinations = response.destinationAddresses;
			var outputDiv = document.getElementById('outputDiv');
			outputDiv.innerHTML = '';//<b>Asal :</b> ' + origins+' <br>

			var per_km = 3000;
		    var total_biaya = "";
			var total_jarak = "";
			for (var i = 0; i < origins.length; i++) {
			  var results = response.rows[i].elements;
			  for (var j = 0; j < results.length; j++) {
				var jarak = parseFloat(results[j].distance.text);
				var biaya = +jarak*+per_km;
				//alert(jarak+" x "+per_km+" = "+biaya);
				/*outputDiv.innerHTML += 
					'<b>Tujuan Antar :</b> ' + destinations[j]+' <br>'+
					'<b>Jarak Tempuh :</b> ' + results[j].distance.text + '<br>'+
					//'<b>Perkiraan Tiba :</b> '+ results[j].duration.text + '<br>'+
					'<b>Biaya Antar :</b> Rp.'+ biaya + '<br>';*/
				total_biaya = +biaya + +total_biaya;
				total_jarak = +jarak + +total_jarak;
			  }
			}
			$('#total_biaya').val(total_biaya);
			$('#total_jarak').val(total_jarak+" Km");
			
			voucher_total 		= $("#voucher_total").val();
			new_voucher_total	= +voucher_total + total_biaya;
			newmoney = accounting.formatMoney(new_voucher_total,"Rp.",2,".",","); // €4.999,99	
			ori_money_cap = "<b>Total Belanja : <span class='code moneys' id='new_ttl_pay'>"+newmoney+"</span></b>";	
			$("#new_total").html(ori_money_cap);
		  }
		}
		

	$(document).ready(function(){ initialize(); })
	//
    </script>
    
    <!--<div class="container_map">
      <div id="inputs">
        <p>
            <label>Alamat Antar</label><br>
            <input type="text" id="antar"><br>
            <input type='text' id="total_biaya" value='' placeholder="Total Biaya Antar" style="margin-bottom:5px;"><br>
            <button type="button" onclick="calculateDistances();" style="margin-top:5px;">
              Menghitung Jarak
            </button>
         </p>
      </div>
      
      <div id="outputDiv"></div>
      
      <div id="map-canvas"></div>
    </div>
-->