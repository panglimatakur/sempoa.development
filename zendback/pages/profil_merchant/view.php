<?php defined('mainload') or die('Restricted Access'); ?>
<form id="formID" class="formular" method="post" action="" enctype="multipart/form-data">
<div class="col-md-12">
    <div class="ibox float-e-margins">
        <div class="ibox-content no-padding">
		<?php 
            if(!empty($msg)){
                switch ($msg){
                    case "1":
                        echo msg("Data Link Berhasil Disimpan","success");
                    break;
                    case "2":
                        echo msg("Pengisian Form Belum Lengkap","error");
                    break;
                    case "3":
                        echo msg("Logo silahkan di isi dengan format *.png, *.jpg atau *.gif","error");
                    break;
                }
            }
        ?>
        <div class="panel blank-panel">
            <div class="panel-heading">
                <div class="panel-options">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#tab-4" aria-expanded="true">
                            	<i class="fa fa-building-o"></i> Identitas Merchant
                            </a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#tab-5" aria-expanded="false">
                            	<i class="fa fa-hashtag"></i> Optimasi Mesin Pencari
                            </a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#tab-6" aria-expanded="false">
                            	<i class="fa fa-qrcode"></i> Kode QR 
                            </a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#tab-3" aria-expanded="false">
                            	<i class="fa fa-map"></i> Peta Lokasi 
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div id="tab-4" class="tab-pane active">
                        <div class="col-md-4 no-padding-l">
                            <div class="form-group">
                                <?php 
                                    if(!empty($photo) && is_file($basepath."/files/images/logos/".@$photo)){
                                ?>
                                <div class="thumbnail">
                                    <div class="thumbnail-inner">
                                    <img src='<?php echo $dirhost; ?>/files/images/logos/<?php echo $photo; ?>' width="100%"/>
                                    </div>
                                </div>
                                <?php } ?>
                                <label>Logo</label>
                                <input type="file" name="photo" id="photo" class='file_1'/>
                            </div>
                        </div>
            
                        <div class="col-md-8 no-padding-l">
                        
                            <div class="form-group col-md-6">
                                <label class="req">Nama Merchant</label>
                                <input name="nama"  id="nama"type="text" value="<?php echo @$nama; ?>" class="form-control validate[required] text-input uppercase"/>
                            </div>
                            <div class="form-group col-md-6">
                              <label class="req">No Tlp</label>
                              <input type="text" name="tlp" id="tlp" value="<?php echo @$tlp; ?>" class="form-control validate[required] text-input" />
                            </div>



                            <div class="form-group col-md-6">
                              <label class="req">Email</label>
                              <input type="text" name="email" id="email" value="<?php echo @$email; ?>" class="form-control validate[required] text-input" />
                            </div>
                            <div class="form-group col-md-6">
                              <label>Website</label>
                              <input type="text" name="website" id="website" value="<?php echo @$website; ?>" class="form-control" style="text-transform:lowercase"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="req">Warna</label>
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type='text' name='w[1]' value='<?php echo @$w[1]; ?>' id="color_1" class="validate[required] text-input form-control" placeholder="Warna 1"/>
                                    <span class="input-group-addon"> Dan </span>
                                    <input type='text' name='w[2]' value='<?php echo @$w[2]; ?>' id="color_2" class="validate[required] text-input form-control" placeholder="Warna 1"/>
                                </div>
                            </div>        
                            <div class="form-group col-md-6">
                              <label class="req">Nama Aplikasi</label>
                              <input type="text" name="app" id="app" value="<?php echo @$app; ?>" class="form-control validate[required] text-input" readonly/>
                              <span id='app_load'></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                              <label class="req">Propinsi</label>
                                <select name="propinsi" id="propinsi" class="form-control validate[required] text-input">
                                    <option value=''>--PILIH PROPINSI--</option>
                                    <?php
                                    $query_propinsi = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '0' ORDER BY NAME ASC");
                                    while($data_propinsi = $db->fetchNextObject($query_propinsi)){
                                    ?>
                                        <option value='<?php echo $data_propinsi->ID_LOCATION; ?>' <?php if(!empty($propinsi) && $propinsi == $data_propinsi->ID_LOCATION){?> selected<?php } ?>><?php echo $data_propinsi->NAME; ?>
                                        </option>
                                <?php } ?>
                                </select>
                            </div>
                            
                            <span id="div_kota">
								<?php if(!empty($kota)){ include $call->inc($ajax_dir,"data.php"); }?>
                            </span>
                            
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                              <label class="req">Alamat</label>
                              <textarea name="alamat" id="alamat" class="form-control validate[required] text-input" style="text-transform:capitalize"><?php echo @$alamat; ?></textarea>
                            </div>
 
                            <div class="form-group col-md-6">
                              <label class="req">Deskripsi Merchant 
                                    <small>(<span id="cap_description"><?php echo $desc_len; ?></span> Karakter)</small>
                              </label>
                              <textarea name="deskripsi" id="deskripsi" class="form-control countext validate[required] text-input" data-id="cap_description" data-count="400"><?php echo @$deskripsi; ?></textarea>
                                <?php 
                                if(!empty($deskripsi) && !empty($meta_keywords)){
                                    $matchProfileWord		= "<br>";
                                    $a = 0;
                                    foreach($meta_keywords_exp as $match_profile){
                                        $keyOnProfile = substr_count(strtolower($deskripsi),strtolower($match_profile)); 
                                        if($keyOnProfile > 0){
                                            $matchProfileWord .= $match_profile.",";
                                        }else{
                                            $a++;
                                            echo "
                                                <span style='color:#FF0000'>Kata \"<b>".$match_profile."</b>\" </span>,";
                                        }
                                    }
                                    if(!empty($a)){ echo "tidak ditemukan pada <b>Deskripsi Merchant</b>"; }
                                }
                                ?>
                            </div>
                        </div>
                        <input type="hidden" id="coin_merchant" value="<?php echo $_SESSION['ccoin']; ?>" />
                        <input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
                        <input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
                        <button name="direction" type="submit"  class="btn btn-sempoa-1" value="save">
                            <i class="fa fa-check-square-o"></i> Simpan Data
                         </button>
                        <div class="clearfix"></div>
                    </div>
                    <div id="tab-5" class="tab-pane">
                        <fieldset>
                             <div class="form-group">
                                Search Engine Optimation (SEO) Tag ini berfungsi untuk mendongkrak rangking mesin pencari (search engine) seperti (Google, Bing, Yahoo, Altavista, Dll) untuk halaman toko online anda, agar mudah ditemukan oleh pengguna internet dimana saja.
                             </div>
                             <br />
                             <div class="form-group col-md-6">
                             
                              <label>Meta Keywords</label><br />
                              <input type="text" name="meta_keywords" id="meta_keywords countext" value="<?php echo @$meta_keywords; ?>" class="form-control"/><br />
                              Meta Keywords (bahasa Indonesia : kata kunci) adalah salah satu jenis meta yang memiliki fungsi menjelaskan tentang bisnis kamu melalui beberapa kata atau frasa. Seiring perkembangan zaman, jumlah penggunaan keywords telah dibatasi. Seperti Google yang menganggap "spam" penggunaan keywords dengan jumlah di atas 7 kata.<br />
                              Contoh : hotel, bintang, lima, bandung, reservasi, tengah kota, fasilitas lengkap
                            </div>
                            <div class="form-group col-md-6">
                              <label>Meta Title</label>
                              <input type="text" name="meta_title" id="meta_title" value="<?php echo @$meta_title; ?>" class="form-control countext"  data-id="cap_meta_title"  data-count="57"/> 
                              <br />
                              <b>Sisa <span id="cap_meta_title"><?php echo $meta_title_len; ?></span> Karakter</b>
                              <br />
                              Meta tag title atau juga dikenal dengan title tag, judul meta Merupakan judul gambaran besar bisnis kamu, kamu bisa melihatnya ketika membuka toko online kamu di <?php echo $website_name; ?>. Cara penulisan title/judul juga sangat berpengaruh dalam proses SEO.<br />
                              Contoh : Hotel Bintang Lima Bandung
                              <br />
                                <?php 
                                if(!empty($meta_title)){
                                    $b = 0;
                                    $matchTitleWord		= "";
                                    foreach($meta_keywords_exp as $match_title){
                                        $keyOnTitle = substr_count(strtolower($meta_title),strtolower($match_title)); 
                                        if($keyOnTitle > 0){
                                            $matchTitleWord .= $match_title.",";
                                        }else{
                                            echo "<span style='color:#FF0000'>Kata \"<b>".$match_title."</b>\"</span>,";
                                        }
                                    }
                                    if(!empty($b)){ echo "tidak ditemukan pada <b>Meta Title</b>"; }
                                }
                                ?>
                            </div>
                            <div class="form-group col-md-12">
                              <label>Meta Description</label>
                              <textarea name="meta_description" id="meta_description" class="form-control countext" data-id="cap_meta_description"  data-count="160"><?php echo @$meta_description; ?></textarea>
                              <br /><b> Sisa <span id="cap_meta_description"><?php echo $meta_description_len; ?></span> Karakter</b><br />
                              Meta Description dalam (bahasa Indonesia: penjelasan/deskripsi) adalah salah satu jenis meta yang memiliki fungsi memberikan garis besar tentang bisnis kamu. Cara menggunakan ini berbeda dengan keywords yaitu menggunakan kalimat bukan kata.<br />
                              Contoh : Hotel berbintang lima yang terletak di tengah kota bandung dengan fasilitas lengkap, silakan reservasi sekarang juga
                              <br />
                                <?php 
                                if(!empty($meta_description)){
                                    $c = 0;
                                    $matchDescWord		= "";
                                    foreach($meta_keywords_exp as $match_desc){
                                        $keyOnDesc = substr_count(strtolower($meta_description),strtolower($match_desc)); 
                                        if($keyOnDesc > 0){
                                            $matchDescWord .= $match_title.",";
                                        }else{
                                            echo "
                                                <span style='color:#FF0000'>Kata \"<b>".$match_desc."</b>\"</span>,";
                                        }
                                    }
                                    if(!empty($c)){ echo "tidak ditemukan pada <b>Meta Description</b>"; }
                                }
                                ?>
                            </div>
                        </fieldset>
                        <button name="direction" type="submit"  class="btn btn-sempoa-1" value="save">
                            <i class="fa fa-check-square-o"></i> Simpan Data
                         </button>
                        <div class="clearfix"></div>

                    
                    </div>
                    <div id="tab-6" class="tab-pane">
                        <div class='ibox-title'>
                            <div class="ibox-tools">
                               <a href="#">
                               		<i class="fa fa-print"></i> Cetak Kode QR
                               </a>
                            </div>  
                        </div>
                        <div class='ibox-content'>
                            <div style="margin-top: 1em; width:100%; padding-left: 10%;" id="qrcode"></div>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                    <div id="tab-3" class="tab-pane">
                        <span id="map_msg"></span>
                        <div class="col-md-8" style="position:relative">
                          <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                            <div id="map-canvas" class=""></div>
                            <input type="hidden" id="lat" name="lat" value="<?php echo $lat; ?>">
                            <input type="hidden" id="lng" name="lng" value="<?php echo $lng; ?>">
                        </div>
                        <div class="col-md-4">
                            <span id="h"></span>
							<div class="bs-callout bs-callout-warning" id="callout-navbar-overflow"> 
                            	<h4>Peta Lokasi</h4> 
                                <p class="text-justify">Peta lokasi ini adalah tempat anda untuk menentukan lokasi gerai-gerai anda yang tersebar, di kota-kota, berfungsi untuk memudahkan pelanggan untuk menemukan gerai mu, melalui web toko online dan aplikasi Discoin yang di download oleh pelanggan mu atau pelanggan dari komunitas usaha mu. 
                                 </p>
                                 <h4>Tutorial Pengoperasian</h4>
                                 <iframe width="300" height="215" src="https://www.youtube.com/embed/dGnz-U3i8ag" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>                               
                            </div>                        
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    	<div class="clearfix"></div>
    	</div>
    </div>
</div>
</form>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBtwnQ1TCmU465tsGQ9xYFk0o8U-hZMBOU&libraries=places" ></script><script>
	var marker, i;
	var markers 	= [];
	var locations 	= [<?php echo $coordinate; ?>];
	var marker_icon = "<?php echo $dirhost; ?>/files/images/logos/<?php echo $photo; ?>";
	var map;

	
	var icon = {
		url: marker_icon, // url
		scaledSize: new google.maps.Size(40,40), // scaled size
		origin: new google.maps.Point(0,0), // origin
		anchor: new google.maps.Point(20,50) // anchor
	};
	function SaveMarker(id,label){
		coordinate  = $("#latlng_"+id).val();
		keterangan  = $("#map_add_"+id).val();
		proses_page = $("#proses_page").val();
		$.ajax({
			url 	: proses_page,
			type	: "POST",
			data 	: {"direction":"save_coordinate","coordinate":coordinate,"keterangan":keterangan,"marker_id":label},
			success : function(response){
				alert(response);
			}
		});
	}
	function DeleteMarker(id) {
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].id == id) {
                markers[i].setMap(null);
                markers.splice(i, 1);
				proses_page = $("#proses_page").val();
				$.ajax({
					url 	: proses_page,
					type	: "POST",
					data 	: {"direction":"delete_coordinate","marker_id":id},
					success : function(response){
						//alert(response);
					}
				});
                return;
            }
        }
    }
	function addMarker(map,cordinate){
		num_latlng 		= $(".latlng").length;
		new_num_latlng	= +num_latlng + 1;
		var new_marker = new google.maps.Marker({
			position	: cordinate,
			draggable	: true,
			animation	: google.maps.Animation.DROP,
			map			: map,
			icon		: icon,
			my_id		: "latlng_"+new_num_latlng
		});
		markers.push(new_marker);
		new_marker.id = "marker_"+new_num_latlng;
		
		google.maps.event.addListener(new_marker, "dragend", function(){
			marker_id 	= this.my_id;
			coords 		= this.getPosition();
			$('#'+marker_id).val(coords.lat()+","+coords.lng());
		})
		
		input = '<input type="hidden" name="latlang['+new_num_latlng+']" class="latlng form-control" id="latlng_'+new_num_latlng+'" value="'+lat_pos+','+lng_pos+'">';
		$("#h").after(input);
		var contentString = '<h4 style="margin:0"><b><?php echo $_SESSION['cname']; ?></b></h4>'+
		'<div class="form-group">'+
			'<label>Keterangan</label>'+
			'<textarea id="map_add_'+new_num_latlng+'" class="map_add form-control" placeholder="Tuliskan Alamat atau program belanja atau apapun yang menyangkut bisnis kamu di lokasi ini"></textarea>'+
		'</div>'+
		'<div class="btn-group" role="group">'+
				'<button type="button" class="btn btn-danger" onclick="DeleteMarker(\''+new_marker.id+'\');"><i class="fa fa-trash-o"></i> Hapus</button>'+
				'<button type="button" class="btn btn-success" onclick="SaveMarker(\''+new_num_latlng+'\',\''+new_marker.id+'\');"><i class="fa fa-check-square-o"></i> Simpan</button>'+
		'</div>';
		
		var infowindow = new google.maps.InfoWindow({
			content: contentString
		});
		google.maps.event.addListener(new_marker, 'click', (function(new_marker, new_num_latlng) {
			return function(){
				infowindow.open(map,new_marker);
			}
		})(new_marker, new_num_latlng));
	}
	function initialize() {
		$("#map_msg").empty();
		lat_pos 	= parseFloat($("#lat").val());
		lng_pos 	= parseFloat($("#lng").val());		
		if(isNaN(lat_pos) && isNaN(lng_pos)){
			lat_pos = -6.972303; 
			lng_pos	= 107.652784;
		}
		map = new google.maps.Map(document.getElementById("map-canvas"),{
			center		: {lat:lat_pos, lng:lng_pos},
			zoom		: 16,
			draggable	: true,
			scrollwheel	: true,
			mapTypeId	: 'roadmap'
		});
				
		for (i = 0; i < locations.length; i++) { 
			lat_pos 	= locations[i][0];
			
			lng_pos		= locations[i][1];
			marker_ids  = locations[i][2];
			v 			= marker_ids.replace("marker_","");

			var cordinate 	= new google.maps.LatLng(lat_pos, lng_pos);
			marker 	= new google.maps.Marker({
				map			: map,
				draggable	: true,
				animation	: google.maps.Animation.DROP,
				icon		: icon,
				position	: cordinate,
				my_id		: "latlng_"+v
			})
			marker.id 		= "marker_"+v;
			markers.push(marker);
			
			input = '<input type="hidden" name="latlang['+v+']" class="latlng form-control"" id="latlng_'+v+'" value="'+locations[i][0]+','+locations[i][1]+'">';
			$("#h").after(input);
			
			var contentString 	= 
			'<h4 style="margin:0"><b><?php echo $_SESSION['cname']; ?></b></h4>'+
			'<div class="form-group">'+
				'<label>Keterangan</label>'+
				'<textarea id="map_add_'+v+'" class="map_add form-control" placeholder="Tuliskan Alamat atau program belanja atau apapun yang menyangkut bisnis kamu di lokasi ini"></textarea>'+
			'</div>'+
			'<div class="btn-group" role="group">'+
					'<button type="button" class="btn btn-danger" onclick="DeleteMarker(\''+marker.id+'\');"><i class="fa fa-trash-o"></i> Hapus</button>'+
					'<button type="button" class="btn btn-success" onclick="SaveMarker(\''+v+'\',\''+marker.id+'\');"><i class="fa fa-check-square-o"></i> Simpan</button>'+
			'</div>';
			var infowindow 		= new google.maps.InfoWindow()
			google.maps.event.addListener(marker, 'click', (function(marker,contentString,infowindow) {				
				return function(){
					infowindow.setContent(contentString);
					infowindow.open(map, marker);
				}
			})(marker,contentString,infowindow));
			
			google.maps.event.addListener(marker, "dragend", function(){
				marker_id 	= this.my_id;
				coords 		= this.getPosition();
				$('#'+marker_id).val(coords.lat()+","+coords.lng());
			})
			
			marker.setMap(map);
			
		}	
		google.maps.event.addDomListener(map, 'click', function(evt) {
			new_cordinate = new google.maps.LatLng(-6.972538, 107.650131);
			addMarker(map,new_cordinate);
		});		
		
		//SEARCH BOX 
		var input 		= document.getElementById('pac-input');
		var searchBox 	= new google.maps.places.SearchBox(input);
		map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
		map.addListener('bounds_changed', function() {
			searchBox.setBounds(map.getBounds());
		});
		searchBox.addListener('places_changed', function() {
			var places = searchBox.getPlaces();
			if (places.length == 0) { return;}
			markers.forEach(function(marker) {marker.setMap(null);});
			markers = [];
			var bounds = new google.maps.LatLngBounds();
			places.forEach(function(place) {
				if (!place.geometry) {
					console.log("Returned place contains no geometry");
					return;
				}
				if (place.geometry.viewport) {
					bounds.union(place.geometry.viewport);
				} else {
					bounds.extend(place.geometry.location);
				}
				cordinate = place.geometry.location;
				addMarker(map,cordinate);
			});
			map.fitBounds(bounds);
		});	
		
		navigator.geolocation.getCurrentPosition(showPosition);
		
		function showPosition(position) {
			cur_location = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);	
			var current_marker = new google.maps.Marker({
				position	: cur_location,
				animation	: google.maps.Animation.DROP,
				map			: map
			});
			var contentString 	= '<div>Posisi Kamu Disini</div>';
			var infowindow 		= new google.maps.InfoWindow()
			google.maps.event.addListener(current_marker, 'click', (function(current_marker,contentString,infowindow) {				
				return function(){
					infowindow.setContent(contentString);
					infowindow.open(map, current_marker);
				}
			})(current_marker,contentString,infowindow));
			
		}
		
		
	};
	google.maps.event.addDomListener(window, 'load', initialize);
</script>