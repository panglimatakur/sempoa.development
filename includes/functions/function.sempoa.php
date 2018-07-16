<?php defined("mainload") or die("Restricted Access"); ?>
<?php

function client_address($id_merchant){
	global $db;
	global $tpref;
	$q_loc 		 		 = $db->query("SELECT CLIENT_PROVINCE,CLIENT_CITY,CLIENT_DISTRICT,CLIENT_SUBDISTRICT,CLIENT_ADDRESS FROM ".$tpref."clients WHERE ID_CLIENT = '".$id_merchant."'");
	$dt_loc				= $db->fetchNextObject($q_loc);
	$result['propinsi']   	= $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CLIENT_PROVINCE."'");
	$result['kota']   		= $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CLIENT_CITY."' AND PARENT_ID = '".$dt_loc->CLIENT_PROVINCE."'");
	$result['kecamatan']   = $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CLIENT_DISTRICT."' AND PARENT_ID = '".$dt_loc->CLIENT_CITY."'");
	$result['kelurahan']   = $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CLIENT_SUBDISTRICT."' AND PARENT_ID = '".$dt_loc->CLIENT_DISTRICT."'");
	$result['alamat']   = $dt_loc->CLIENT_ADDRESS;
	return $result;
}

function customer_address($id_customer){
	global $db;
	global $tpref;
	$q_loc 		 		 	= $db->query("SELECT CUSTOMER_PROVINCE,CUSTOMER_CITY,CUSTOMER_DISTRICT,CUSTOMER_SUBDISTRICT,CUSTOMER_ADDRESS FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."'");
	$dt_loc					= $db->fetchNextObject($q_loc);
	@$result['propinsi']   	= $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CUSTOMER_PROVINCE."'");
	@$result['kota']   		= $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CUSTOMER_CITY."' AND PARENT_ID = '".$dt_loc->CUSTOMER_PROVINCE."'");
	@$result['kecamatan']   = $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CUSTOMER_DISTRICT."' AND PARENT_ID = '".$dt_loc->CUSTOMER_PROVINCE."'");
	@$result['kelurahan']   = $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CUSTOMER_SUBDISTRICT."' AND PARENT_ID = '".$dt_loc->CUSTOMER_DISTRICT."'");
	@$result['alamat']   	= $dt_loc->CUSTOMER_ADDRESS;
	return $result;
}
function pantek(){
	$return = "testinf";	
	return $return;
}
function ch_addon($id_client){
	global $db;
	global $tpref;
	$str_addons  = "SELECT a.ID_DISCOIN_ADDON, a.ADDON_ALIAS, b.ID_CLIENT
					FROM cat_discoin_addons a,cat_discoin_configs b 
					WHERE a.ID_DISCOIN_ADDON = b.ID_DISCOIN_ADDON AND b.ID_CLIENT = '".$id_client."'";
	$q_addons 	 = $db->query($str_addons);
	while($dt_addons	= $db->fetchNextObject($q_addons)){
		@$addons[$dt_addons->ID_DISCOIN_ADDON] = "true";
	}
	return @$addons;
}
function parseSmiley($text){
	global $db;
	global $dirhost;
	global $tpref;
	
	$smileys	= array();
	$q_emoticon = $db->query("SELECT * FROM ".$tpref."chat_emoticons ORDER BY ID_EMOTICON ASC, ID_EMOTICON_TYPE ASC");
	while($dt_emoticon = $db->fetchNextObject($q_emoticon)){
		$smileys[$dt_emoticon->SYMBOL] = $dt_emoticon->IMAGE;
	}
	foreach($smileys as $smiley => $img){
		//echo $smiley." = ".substr_count($text,$smiley)."<br>";
		$text = str_replace($smiley,"<img src='".$dirhost."/files/images/emoticons/1/{$img}' style='width:30px; margin-top:4px'/>",$text);
	}
	return $text;
}					

function cuser_log($type,$id_cuser,$activity,$id_coin){
	global $db;
	global $tpref;
	global $wktupdate;
	global $tglupdate;
	global $ip_address;
	
	$field = "ID_CUSTOMER";
	if($type == "customer")	{ $field = "ID_CUSTOMER"; }
	if($type == "user")		{ $field = "ID_USER"; }
	$log_container	= array(1=>
						array($field,$id_cuser),
						array("ID_CLIENT",@$id_coin),
						array("ACTIVITY",$activity),
						array("IP_ADDRESS",@$ip_address),
						array("TGLUPDATE",$tglupdate),
						array("WKTUPDATE",$wktupdate));
	$db->insert($tpref."logs",$log_container);
}
function cvisitor_log($sessichat,$activity,$id_coin){
	global $db;
	global $tpref;
	global $wktupdate;
	global $tglupdate;
	global $ip_address; 
	$log_container	= array(1=>
						array("ID_CLIENT",@$id_coin),
						array("ACTIVITY",@$activity),
						array("IP_ADDRESS",@$ip_address),
						array("SESSION",@$sessichat),
						array("TGLUPDATE",$tglupdate),
						array("WKTUPDATE",$wktupdate));
	$db->insert($tpref."logs",$log_container);
}

function power($id_client){
	global $db;
	global $dtime;
	global $tpref;
	global $tglupdate;
	
	//INTERACTION SEMPOA ROUTINE
	$max_action		= 1800;
	$jml_action 	= $db->recount("SELECT ID_CLIENT FROM ".$tpref."chat WHERE ID_CLIENT='".$id_client."'");
	$index_action 	= round(($jml_action/$max_action)*100);
	//BEST FRIEND SUMMARY
	$max_coin	= 90;	
	$jml_coin	= $db->recount("SELECT ID_CUSTOMER FROM ".$tpref."customers WHERE ID_CLIENT='".$id_client."' AND CUSTOMER_STATUS = '3' ");
	//echo "SELECT ID_CUSTOMER FROM ".$tpref."customers WHERE ID_CLIENT='".$id_client."' AND CUSTOMER_STATUS = '3' ";
	$index_coin = round(($jml_coin/$max_coin)*100);
	$max_index	= 200;
	$index		= $index_action + $index_coin;
	
	$remain		= round(($index/$max_index)*100);
	//$db->query("UPDATE ".$tpref."clients SET POW = '".$remain."' WHERE ID_CLIENT='".$id_client."'");
	if($remain < 10){ $class = "progress-bar-danger"; }
	if($remain >= 10 && $remain < 40){ $class = "progress-bar-warning"; }
	if($remain >= 40 && $remain < 70){ $class = "progress-bar-success"; }
	if($remain >= 70 && $remain < 90){ $class = "progress-bar-info"; }
	if($remain >= 90){ $class = "progress-bar-sempoa"; }
	$result['remain'] 	= $remain;
	$result['class'] 	= $class;
	return $result;
}

function rank_formula($id_merchant){
	global $db;
	global $tpref;
	global $tglupdate;
	global $wktupdate;
	$id_parent 		= $db->fob("CLIENT_ID_PARENT",$tpref."clients"," WHERE ID_CLIENT='".$id_merchant."'");
	
	if(empty($id_parent) || @$id_parent == "0"){ $id_parent = $id_merchant; }
	$ord_rank		= $db->last("RANK",$tpref."clients_ranks"," WHERE ID_CLIENT='".$id_parent."'");
	
	$active_coin	= $db->recount("SELECT ID_CUSTOMER FROM ".$tpref."customers WHERE ID_CLIENT='".$id_parent."' AND CUSTOMER_STATUS = '3'");
	$cust_visit 	= $db->recount("SELECT ID_CUSTOMER FROM ".$tpref."clients_visitors WHERE CUSTOMER_ID_CLIENT='".$id_parent."' AND ID_CLIENT = '".$id_parent."'");
	$noncust_visit 	= $db->recount("SELECT ID_CUSTOMER FROM ".$tpref."clients_visitors WHERE CUSTOMER_ID_CLIENT != '".$id_parent."' AND ID_CLIENT = '".$id_parent."'");

	$discoin_rank	= @$active_coin + @$cust_visit + @$noncust_visit;
	if(empty($ord_rank)){
		$rank_container	= array(1=>
							array("ID_CLIENT",$id_parent),
							array("RANK",$discoin_rank),
							array("TGLUPDATE",$tglupdate),
							array("WKTUPDATE",$wktupdate));
		$db->insert($tpref."clients_ranks",$rank_container);
	}else{
		$rank_container	= array(1=>
							array("RANK",$discoin_rank),
							array("TGLUPDATE",$tglupdate),
							array("WKTUPDATE",$wktupdate));
		$db->update($tpref."clients_ranks",$rank_container," WHERE ID_CLIENT='".$id_parent."'");
	}
	return $discoin_rank;
}

function check_cookies(){
	global $db;
	global $tpref;
	global $titanium;
	global $id_coin;
	if(!empty($titanium) && $titanium == "true"){ 
		$log_condition = "AND ID_CLIENT='".$id_coin."'"; 
	}
	if(isset($_COOKIE['sidkey'])){
		$query_login 					= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER='".$_COOKIE['sidkey']."' AND ID_CLIENT='".@$_COOKIE['csidkey']."'");
		$num_login					= $db->numRows($query_login);
		if($num_login > 0){
			$data_logins 				= $db->fetchNextObject($query_login);
				if($data_logins->CUSTOMER_STATUS == "3"){
					if($titanium == "true"){
						$_SESSION['titanium']	= "true";
					}else{
						$_SESSION['titanium']	= "false";	
					}
					$_SESSION['sidkey']			= $data_logins->ID_CUSTOMER;
					$_SESSION['susername']		= $data_logins->CUSTOMER_USERNAME;
					$_SESSION['spassword']		= $data_logins->CUSTOMER_PASS;
					$_SESSION['cust_name']		= $data_logins->CUSTOMER_NAME;
					$_SESSION['csidkey']		= $data_logins->ID_CLIENT;
					$merchant_join 				= $db->query("SELECT ID_COMMUNITY FROM ".$tpref."communities_merchants WHERE ID_CLIENT='".$data_logins->ID_CLIENT."' ");
					$num_merchant_join			= $db->numRows($merchant_join);
					$com_join					= array();
					while($dt_join = $db->fetchNextObject($merchant_join)){
						$com_join[] = $dt_join->ID_COMMUNITY;	
					}
					$_SESSION['scomidkey'] = $com_join;	
				}
		}
	}
	//echo $_SESSION['susername']."tes cookie";
}

function print_header($title){
	global $dirhost;
	global $tglupdate;
	global $dtime;
	echo "<div id='print_header'>
			".$title."<br>
			<small>Tanggal : ".$dtime->now2indodate2($tglupdate)."</small>
			<div style='clear:both'></div>
		  </div>";
}

function print_footer(){
	global $dirhost;
	global $product_name;
	echo "<div id='print_footer'>
			<img src='".$dirhost."/files/images/favicon.png' align='absmiddle'>
			Powered By <span>".$product_name."</span>
			<div style='clear:both'></div>
		  </div>";
}
function parent_condition($id,$pre = NULL){ 
	global $db;
	global $tpref;
	if(!empty($_SESSION['childkey'])){
		$list		= explode(",",$id);
		$e = 0;
		$q_list = $db->query("SELECT DISTINCT(ID_CLIENT) FROM ".$tpref."clients WHERE CLIENT_ID_PARENT_LIST LIKE '%,".$id."%'");
		$condition 	= "";
		while($dt_list = $db->fetchNextObject($q_list)){
			if(!empty($dt_list->ID_CLIENT)){
				if($dt_list->ID_CLIENT != $_SESSION['cidkey']){
					$e++;
					$condition .= " OR ".$pre."ID_CLIENT = '".$dt_list->ID_CLIENT."'";	
				}
			}
		}
		return $condition;
	}
}

function networks_condition($id_parent,$pre = NULL){
	global $db;
	global $tpref;
	$e 		= 0;
	$q_list = $db->query("SELECT DISTINCT(ID_CLIENT) FROM ".$tpref."clients WHERE CLIENT_ID_PARENT_LIST LIKE '%,".$id_parent.",%'");
	$condition 	= "";
	while($dt_list = $db->fetchNextObject($q_list)){
		if(!empty($dt_list->ID_CLIENT)){
			if($dt_list->ID_CLIENT != $id_parent){
				$e++;
				$condition .= " OR ".$pre."ID_CLIENT = '".$dt_list->ID_CLIENT."'";	
			}
		}
	}
	return $condition;
}

//BAHASA NYA "CEK HAK AKSES $id_user DI $tbl_hakakses UNTUK MODULE $mod_akses HALAMAN $page PADA TABLE $tbl_page"//
function rightaccess($page){
	global $db;
	$id_page 		= $db->fob("ID_PAGE_CLIENT","system_pages_client","WHERE PAGE='".$page."'");
	if(!empty($_SESSION['ulevelkey'])){
		$str_right 		= "SELECT * FROM system_pages_client_rightaccess WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ID_PAGE_CLIENT='".$id_page."' AND ID_CLIENT_LEVEL='".$_SESSION['uclevelkey']."' AND ID_CLIENT_USER_LEVEL='".$_SESSION['ulevelkey']."'";
		$chright 		= $db->recount($str_right);
	}else{
		$chright 		= 0;
	}
	return $chright;
}

function id_page($page,$tbl){
	global $db;
	$id_page = $db->fob("ID_PAGES_CLIENT",$tbl,"WHERE PAGE='".$page."'");
	return $id_page;
}
function tipe_page($id_page,$tbl){
	global $db;
	$tipe_page = $db->fob("TYPE",$tbl,"WHERE ID_PAGES_CLIENT='".$id_page."'");	
	return $tipe_page;

}
function getuserinfo($field,$id){
 global $db;
 $info 	= $db->fob($field,"system_users_client","WHERE ID_USER ='".$id."' ");
 return $info;
}

function getmemberfoto($id,$attr){
  global $db;
  global $tpref;
  global $dirhost;
  global $basepath;
  global $img_dir;
  
	  @$foto 	= $db->fob("CUSTOMER_PHOTO",$tpref."customers","WHERE ID_CUSTOMER ='".$id."' ");
	  if(!empty($foto)){
		 if(is_file($basepath."/".$img_dir."/members/".$foto)){
		 	$img = "<img src='".$dirhost."/".$img_dir."/members/".$foto."' ".$attr."/>" ;
	  	}else{
			$img = "<img src='".$dirhost."/".$img_dir."/no_image.jpg' ".$attr."/>";
		}
	  }
	  else{
			$img = "<img src='".$dirhost."/".$img_dir."/no_image.jpg' ".$attr."/>";
	  }
 return $img;
}


function getuserfoto($id,$attr){
  global $db;
  global $dirhost;
  global $basepath;
  global $img_dir;
  global $user_foto_dir;
  
	  @$foto 	= $db->fob("USER_PHOTO","system_users_client","where ID_USER ='".$id."' ");
	  if(!empty($foto)){
		 if(is_file($basepath."/".$user_foto_dir."/".$foto)){
		 	$img = "<img src='".$dirhost."/".$user_foto_dir."/".$foto."' ".$attr."/>" ;
	  	}else{
			$img = "<img src='".$dirhost."/".$img_dir."/noimage-m.jpg' ".$attr."/>";
		}
	  }
	  else{
			$img = "<img src='".$dirhost."/".$img_dir."/noimage-m.jpg' ".$attr."/>";
	  }
 return $img;
}
function getclientlogo($id,$attr){
  global $db;
  global $tpref;
  global $dirhost;
  global $basepath;
  global $img_dir;
  
	  @$foto 	= $db->fob("CLIENT_LOGO",$tpref."clients","WHERE ID_CLIENT ='".$id."' ");
	  if(!empty($foto)){
		 if(is_file($basepath."/".$img_dir."/logos/".$foto)){
		 	$img = "<img src='".$dirhost."/".$img_dir."/logos/".$foto."' ".$attr."/>" ;
	  	}else{
			$img = "<img src='".$dirhost."/".$img_dir."/no_image.jpg' ".$attr."/>";
		}
	  }
	  else{
			$img = "<img src='".$dirhost."/".$img_dir."/no_image.jpg' ".$attr."/>";
	  }
 return $img;
}

function get_product_info($id_product,$img_width = "90%"){
	global $dirhost;
	global $basepath;
	global $img_dir;
	global $tpref;
	global $db;
	$q_product 	     = $db->query("SELECT a.ID_CLIENT,a.NAME,a.DESCRIPTION,a.ADDITIONAL_PRODUCT,a.CODE,a.SALE_PRICE,a.DISCOUNT,a.ID_PRODUCT_UNIT,b.PHOTOS FROM ".$tpref."products a, ".$tpref."products_photos b WHERE a.ID_PRODUCT = b.ID_PRODUCT AND a.ID_PRODUCT='".$id_product."' GROUP BY b.ID_PRODUCT");
	$dt_product	    				= $db->fetchNextObject($q_product);
	$photo		   	 				= $dt_product->PHOTOS;
	$result['id_merchant']    		= $dt_product->ID_CLIENT;
	$result['code']    				= $dt_product->CODE;
	$result['image']   				= $photo;
	$result['name']    				= $dt_product->NAME;
	$result['price']   				= $dt_product->SALE_PRICE;
	$result['add_product']   		= $dt_product->ADDITIONAL_PRODUCT;
	$result['description']   		= $dt_product->DESCRIPTION;
	@$result['unit']	= $db->fob("NAME",$tpref."products_units","WHERE ID_PRODUCT_UNIT = '".$dt_product->ID_PRODUCT_UNIT."'");
	if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$dt_product->ID_CLIENT."/thumbnails/".$photo)){
		$result['photo'] = '
		<a href="'.$dirhost.'/'.$img_dir.'/products/'.$dt_product->ID_CLIENT.'/thumbnails/'.$photo.'" class="fancybox">
			<img src="'.$dirhost.'/'.$img_dir.'/products/'.$dt_product->ID_CLIENT.'/thumbnails/'.$photo.'" class="thumbnail" style="width:'.$img_width.'"/>
	</a>';
	}else{
		$result['photo'] = '<img src="'.$dirhost.'/files/images/no_image.jpg" class="thumbnail" style="width:'.$img_width.'"/>';
	}
	return $result;
}

function category_list($id_parent){
	global $db;
	global $tpref;
	global $dirhost;
	global $id_kategori;
	$query_kategori = $db->query("SELECT * FROM ".$tpref."products_categories WHERE ID_PARENT = '".$id_parent."' AND ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY SERI"); 
?>
	<ul class="kategori_list">
		<?php
		while($data_kategori = $db->fetchNextObject($query_kategori)){
			$class_selected = "";
			if(!empty($id_kategori) && $id_kategori == $data_kategori->ID_PRODUCT_CATEGORY){
				$class_selected = "class='class_selected' style='border:1px solid #F9ECF7; background:#FFE1FF'";	
			}
		?>	
			<li id="cat_<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>" <?php echo @$class_selected; ?>>
				<img src="<?php echo $dirhost; ?>/files/images/icons/bullet_go.png" />
				<a href='javascript:void()' onclick="select_category('<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>')">
					<?php echo $data_kategori->NAME; ?>
				</a>
			</li>
		<?php } ?>
	</ul>
<?php	
}
function category_list_report($id_parent){
	global $db;
	global $tpref;
	global $dirhost;
	global $id_kategori;
	$query_kategori = $db->query("SELECT * FROM ".$tpref."products_categories WHERE ID_PARENT = '".$id_parent."' AND ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY SERI"); 
?>
	<ul class="kategori_list">
		<?php
		while($data_kategori = $db->fetchNextObject($query_kategori)){
			$class_selected = "";
			if(!empty($id_kategori) && $id_kategori == $data_kategori->ID_PRODUCT_CATEGORY){
				$class_selected = "class='class_selected' style='border:1px solid #F9ECF7; background:#FFE1FF'";	
			}
		?>	
			<li id="cat_report_<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>" <?php echo @$class_selected; ?>>
				<img src="<?php echo $dirhost; ?>/files/images/icons/bullet_go.png" />
				<a href='javascript:void()' onclick="select_category_report('<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>')">
					<?php echo $data_kategori->NAME; ?>
				</a>
                <?php echo category_list_report($data_kategori->ID_PRODUCT_CATEGORY); ?>
			</li>
		<?php } ?>
	</ul>
<?php	
}

function transaction_type_list($parent){
	global $db;
	global $id_client;
	global $id_root;
	global $lparam;
	global $tpref;
	global $condition;
	global $first_condition;
	$qlink 	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_PARENT='".$parent."' AND (ID_CLIENT='".$_SESSION['ori_cidkey']."' OR ID_CLIENT='0') ".$first_condition." ORDER BY ID_CASH_TYPE ASC");
	$jml 	= $db->numRows($qlink);
	if($jml >0){
?>
		<ul>
<?php
		$t = 0;
		while($dt = $db->fetchNextObject($qlink)){
		$t++;
		$close = ""; 
		if($dt->IS_FOLDER == 2){ $close = "display:none"; }
		$total_t			= $db->sum("PAID",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND ID_CLIENT='".$id_client."' ".@$condition."");
		?>
			<li id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
				<div id='link_list'>
					<p class='link1' style="float:left">
						<a href="<?php echo $lparam; ?>&parent_id=<?php echo $dt->ID_CASH_TYPE; ?>" class="folder">
							<?php echo $dt->NAME; ?>
						</a>
					</p>
					<p class='buttons1' style="float:right; margin-right:5px; <?php echo $close; ?>">
					<a href="<?php echo $lparam; ?>&parent_id=<?php echo $dt->ID_CASH_TYPE; ?>"><?php echo money("Rp.",$total_t); ?></a>
					</p>
				</div>		
				<br clear="all" />  
				<?php echo transaction_type_list($dt->ID_CASH_TYPE); ?>
			</li>
		<?php
		}
?>
	</ul>
<?php
	}
}

function insert_cash($id_cash_type,$sumber,$total,$paid,$id_direction){
	global $id_client;
	global $db;
	global $cash;
	global $termin;
	global $tpref;
	global $keterangan;
	global $tgltempo;
	global $tglupdate;
	global $wktupdate;
	
	if(empty($paid)){ $paid = 0;}
	$status			= 2;
	$kredit  		= $total-$paid;	
	$flow 			= $db->fob("IN_OUT",$tpref."cash_type"," WHERE ID_CASH_TYPE='".$id_cash_type."'");
	
	//echo $total."-".$paid."=".$kredit." = SELECT IN_OUT ".$tpref."cash_type WHERE ID_CASH_TYPE='".$id_cash_type."'<br>";
	
	if($flow == 1){
		if($paid < $total){
			$status = 3;
		}
		$cash_residual_value = $cash + $paid; 
		//echo $flow." - ".$cash_residual_value."= ".$cash." + ".$paid; 
	}
	if($flow == 2){
		if($paid < $total){
			$status = 1;
		}
		$cash_residual_value = $cash - $paid; 
		//echo $flow." - ".$cash_residual_value."= ".$cash." - ".$paid; 
	}
	$content 		= array(1=>
						array("ID_CLIENT",$id_client),
						array("ID_CASH_TYPE",$id_cash_type),
						array("ID_CASH_SOURCE",@$sumber),
						array("CASH_VALUE",@$total),
						array("PAID",@$paid),
						array("REMAIN",@$kredit),
						array("NOTE",@$keterangan),
						array("PAID_STATUS",@$status),
						array("TERMS",@$termin),
						array("BY_ID_USER",$_SESSION['uidkey']),
						array("TGLUPDATE",$tglupdate),
						array("WKTUPDATE",$wktupdate));
	$db->insert($tpref."cash_flow",$content);
	$id_cash_flow = mysql_insert_id();
	
	$content = array(1=>
				array("ID_CLIENT",$id_client),
				array("ID_CASH_FLOW",$id_cash_flow),
				array("ID_CASH_TYPE",$id_cash_type),
				array("ID_CASH_SOURCE",@$sumber),
				array("CASH_VALUE",@$total),
				array("PAID",@$paid),
				array("REMAIN",@$kredit),
				array("PAID_STATUS",@$status),
				array("TERMS",@$termin),
				array("NOTE",@$keterangan),
				array("CASH_RESIDUAL_VALUE",@$cash_residual_value),
				array("BY_ID_USER",$_SESSION['uidkey']),
				array("ID_DIRECTION",$id_direction),
				array("TGLUPDATE",$tglupdate),
				array("WKTUPDATE",$wktupdate)
				);
	$db->insert($tpref."cash_flow_history",$content);

	return $id_cash_flow;
}

function save_cash($id_cash_flow,$sumber,$total,$paid,$id_direction){
	global $cash;
	global $id_client;
	global $termin;
	global $keterangan;
	global $db;
	global $tpref;
	global $tgltempo;
	global $tglupdate;
	global $wktupdate;
	
	if(empty($paid)){ $paid = 0;}
	$status			= 2;
	$kredit  		= $total-$paid;	
	if($kredit < 1){ $kredit = 0; }
		
	$q_cash_flow 	= $db->query("SELECT ID_CASH_TYPE,PAID FROM ".$tpref."cash_flow WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."'");
	$dt_cash_flow	= $db->fetchNextObject($q_cash_flow);
	$id_cash_type	= $dt_cash_flow->ID_CASH_TYPE;
	$first_value	= $dt_cash_flow->PAID;
	
	$flow 			= $db->fob("IN_OUT",$tpref."cash_type"," WHERE ID_CASH_TYPE='".$id_cash_type."' ");
	if($flow == 1){
		if($paid < $total){
			$status = 3;
		}
		$cash_residual_value = ($cash - $first_value) + $paid; 	
	}
	if($flow == 2){
		if($paid < $total){
			$status = 1;
		}
		$cash_residual_value = ($cash + $first_value) - $paid; 	
	}
	
	//echo $flow." - ".$cash_residual_value."= (".$cash." + ".$first_value.") - ".$paid; 
	$content = array(1=>
				array("CASH_VALUE",@$total),
				array("PAID",@$paid),
				array("REMAIN",@$kredit),
				array("PAID_STATUS",@$status),
				array("TERMS",@$termin),
				array("ID_CASH_SOURCE",@$sumber),
				array("BY_ID_USER",$_SESSION['uidkey']),
				array("TGLUPDATE",$tglupdate),
				array("WKTUPDATE",$wktupdate)
				);
	$db->update($tpref."cash_flow",$content," WHERE ID_CLIENT='".$id_client."' AND ID_CASH_FLOW='".$id_cash_flow."'");
	
	$content = array(1=>
				array("ID_CLIENT",$_SESSION['cidkey']),
				array("ID_CASH_FLOW",$id_cash_flow),
				array("ID_CASH_TYPE",$id_cash_type),
				array("ID_CASH_SOURCE",@$sumber),
				array("CASH_VALUE",@$total),
				array("PAID",@$paid),
				array("REMAIN",@$kredit),
				array("PAID_STATUS",@$status),
				array("TERMS",@$termin),
				array("CASH_RESIDUAL_VALUE",@$cash_residual_value),
				array("BY_ID_USER",$_SESSION['uidkey']),
				array("ID_DIRECTION",$id_direction),
				array("TGLUPDATE",$tglupdate),
				array("WKTUPDATE",$wktupdate)
				);
	$db->insert($tpref."cash_flow_history",$content);
	return $cash_residual_value;
}

function delete_cash($id_cash_flow,$id_direction){
	global $cash;
	global $id_client;
	global $db;
	global $tpref;

	$q_cash					= $db->query("SELECT * FROM ".$tpref."cash_flow WHERE ID_CASH_FLOW='".$id_cash_flow."'");
	$dt_cash				= $db->fetchNextObject($q_cash);
	$id_client				= $dt_cash->ID_CLIENT;
	$id_cash_type 			= $dt_cash->ID_CASH_TYPE;
	$sumber 				= $dt_cash->ID_CASH_SOURCE;
	$total 					= $dt_cash->CASH_VALUE;
	$paid 					= $dt_cash->PAID;
	$kredit 				= $dt_cash->REMAIN;
	$status 				= $dt_cash->PAID_STATUS;
	$termin 				= $dt_cash->TERMS;
	$uidkey	 				= $dt_cash->BY_ID_USER;
	$tglupdate 				= $dt_cash->TGLUPDATE;
	$wktupdate				= $dt_cash->WKTUPDATE;
	
	$flow 			= $db->fob("IN_OUT",$tpref."cash_type"," WHERE ID_CASH_TYPE='".$id_cash_type."' ");
	if($flow == 1){
		$cash_residual_value	= $cash-$paid;
	}else{
		$cash_residual_value	= $cash+$paid;
	}
	$content = array(1=>
				array("ID_CLIENT",$_SESSION['cidkey']),
				array("ID_CASH_FLOW",$id_cash_flow),
				array("ID_CASH_TYPE",$id_cash_type),
				array("ID_CASH_SOURCE",@$sumber),
				array("CASH_VALUE",@$total),
				array("PAID",@$paid),
				array("REMAIN",@$kredit),
				array("PAID_STATUS",@$status),
				array("TERMS",@$termin),
				array("CASH_RESIDUAL_VALUE",@$cash_residual_value),
				array("BY_ID_USER",$_SESSION['uidkey']),
				array("ID_DIRECTION",$id_direction),
				array("TGLUPDATE",$tglupdate),
				array("WKTUPDATE",$wktupdate));
	$db->insert($tpref."cash_flow_history",$content);
	$db->delete($tpref."cash_flow"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."'");
	return $cash_residual_value;
		
}

function allow($direction){
	$result = "";
	if($direction == "insert" && !empty($_SESSION['insert'])){
		$result = $_SESSION['insert'];	
	}
	if($direction == "edit" && !empty($_SESSION['edit'])){
		$result = $_SESSION['edit'];	
	}
	if($direction == "delete" && !empty($_SESSION['delete'])){
		$result = $_SESSION['delete'];	
	}
	return $result;
}

function insert_decre($id_cash_flow,$dc,$plusminus,$amount,$remain,$tgl_bayar){
	global $id_client;
	global $keterangan;
	global $db;
	global $tpref;
	global $tglupdate;
	global $wktupdate;	
	if(!empty($amount)){
		$ordinal = $db->last("ORDINAL",$tpref."cash_debt_credit"," WHERE ID_CASH_FLOW='".$id_cash_flow."' AND PLUS_MINUS = '-'")+1;
		$content = array(1=>
					array("ID_CLIENT",$id_client),
					array("ID_CASH_FLOW",$id_cash_flow),
					array("DEBT_CREDIT",$dc),
					array("PLUS_MINUS",$plusminus),
					array("AMOUNT",@$amount),
					array("REMAIN",@$remain),
					array("ORDINAL",$ordinal),
					array("NOTE",$keterangan),
					array("BY_ID_USER",$_SESSION['uidkey']),
					array("PAY_DATE",$tgl_bayar),
					array("TGLUPDATE",$tglupdate),
					array("WKTUPDATE",$wktupdate));
		$db->insert($tpref."cash_debt_credit",$content);
		
		$content = array(1=>array("STATUS","1"));
		$db->update($tpref."debt_credit_reminder",$content," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND ORDINAL = '".$ordinal."' ");
	}
}
function save_decre($id_cash_flow,$amount,$remain,$tgl_bayar,$plus_minus){
	global $id_client;
	global $keterangan;
	global $db;
	global $tpref;
	global $tglupdate;
	global $wktupdate;
		$content = array(1=>
					array("AMOUNT",@$amount),
					array("REMAIN",@$remain),
					array("NOTE",$keterangan),
					array("BY_ID_USER",$_SESSION['uidkey']),
					array("PAY_DATE",$tgl_bayar),
					array("TGLUPDATE",$tglupdate),
					array("WKTUPDATE",$wktupdate)
					);
		$db->update($tpref."cash_debt_credit",$content," WHERE ID_CASH_FLOW='".$id_cash_flow."' AND ID_CLIENT='".$id_client."' AND PLUS_MINUS='".$plus_minus."' AND ORDINAL = '1'");
		
}

function delete_decre($id_cash_flow){
	global $id_client;
	global $db;
	global $tpref;
	$db->delete($tpref."cash_debt_credit"," WHERE ID_CASH_FLOW='".$id_cash_flow."' AND ID_CLIENT='".$id_client."'");
}
?>