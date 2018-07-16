<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 			= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] : "";
	$id 				= isset($_REQUEST['id']) 			? $_REQUEST['id'] : "";
		
	$from_id_pur 		= isset($_REQUEST['to_id_pur']) 	? $_REQUEST['to_id_pur'] 		: "";
	$from_id_comm 		= isset($_REQUEST['from_id_comm']) 	? $_REQUEST['from_id_comm'] 	: "";
	$to_id_comm 		= isset($_REQUEST['to_id_comm']) 	? $_REQUEST['to_id_comm'] 		: "";
	
	
	if(!empty($direction) && $direction == "out"){
		$db->delete($tpref."communities_merchants"," WHERE ID_COMMUNITY = '".$to_id_comm."' AND ID_CLIENT = '".$_SESSION['cidkey']."'");
	}
	
	
	if(!empty($direction) && $direction == "move"){
				
		$check_merchant = $db->recount("SELECT ID_CLIENT FROM ".$tpref."communities_merchants WHERE ID_COMMUNITY = '".$to_id_comm."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
		
		$nm_komunitas	= $db->fob("NAME",$tpref."communities"," WHERE ID_COMMUNITY = '".$to_id_comm."'");
		if($check_merchant == 0){
			$db->query("UPDATE ".$tpref."communities_merchants SET ID_COMMUNITY = '".$to_id_comm."' WHERE ID_COMMUNITY = '".$from_id_comm."' AND ID_CLIENT = '".$_SESSION['cidkey']."'");
			
			$result['msg'] 		= "<div class='alert alert-success'>Sekarang anda telah pindah dan bergabung ke komunitas <b>".$nm_komunitas."</b>";
			$result['io'] 		= "2";
			$result['nm_comm'] 	= trim($nm_komunitas);
		}else{
			$result['msg'] 		= "<div class='alert alert-error'>Maaf di komunitas ".$nm_komunitas.", anda sudah bergabung";
			$result['io'] 		= "1";
			$result['nm_comm'] 	= trim($nm_komunitas);
		}
		echo json_encode($result);
	}
	
	
	
	if(!empty($direction) && $direction == "add"){
		
		$nm_komunitas 		= isset($_REQUEST['nm_community']) 	? ucwords($_REQUEST['nm_community']) 		: "";
		$new_comm = array(1=>
						array("NAME",$nm_komunitas),
						array("BY_ID_USER",$_SESSION['cidkey']),
						array("BY_ID_PURPLE","1"),
						array("STATUS_ACTIVE","2"),
						array("TGLUPDATE",$tglupdate));
		$db->insert($tpref."communities",$new_comm);
		$to_id_comm 	= mysql_insert_id();
		$container = array(1=>
						array("ID_COMMUNITY",$to_id_comm),
						array("ID_CLIENT",$_SESSION['cidkey']),
						array("TGLUPDATE",$tglupdate));
		$db->insert($tpref."communities_merchants",$container);
		
		$num_current = $db->recount("SELECT ID_CLIENT FROM ".$tpref."communities_merchants WHERE ID_COMMUNITY = '".$to_id_comm."' AND ID_CLIENT='".$_SESSION['cidkey']."' ");
		
		$str_statistik = "
			SELECT 
				COUNT(a.ID_CUSTOMER) AS JML_COIN, 
				SUM(70000) AS TOTAL_PROFIT
			FROM 
				cat_customers a,cat_communities_merchants b 
			WHERE 
				a.CUSTOMER_STATUS = '3' AND
				b.ID_COMMUNITY = '".$to_id_comm."' AND
				a.ID_CLIENT = b.ID_CLIENT";
		$q_statistik 	= $db->query($str_statistik);
		$dt_statistik	= $db->fetchNextObject($q_statistik);
		
		$str_merchant	=
		"SELECT 
			a.ID_CLIENT, b.RANK, a.TGLUPDATE
		FROM 
			".$tpref."communities_merchants a, ".$tpref."clients_ranks b
		WHERE 
			a.ID_COMMUNITY 	=  '".$to_id_comm."' AND 
			a.ID_CLIENT 	!= '1' AND 
			a.ID_CLIENT 	=  b.ID_CLIENT
		GROUP BY a.ID_CLIENT
		ORDER BY b.RANK DESC";		
		//echo $str_merchant;		
		
?>
<div class="w-box-content col-md-4" style="margin-bottom:3px;" id="tbl_community_<?php echo $to_id_comm; ?>">
          <div class="ibox-title">
                <h4>Komunitas <?php echo @$nm_komunitas; ?> (<small style="font-size:11px; color:#FFF"><?php echo $dt_statistik->JML_COIN; ?> COIN Aktif</small>)</h4>
          </div>
          <table class="table table-striped " style="width:100%" >
              <tbody>
                <?php
                $q_merchant 		= $db->query($str_merchant);
                while($dt_merchant	= $db->fetchNextObject($q_merchant)){
                    $q_client 	= $db->query("SELECT ID_CLIENT,CLIENT_NAME,CLIENT_STATEMENT,CLIENT_URL FROM ".$tpref."clients WHERE ID_CLIENT='".$dt_merchant->ID_CLIENT."'");
                    $dt_client	= $db->fetchNextObject($q_client);
                    $client_web	= "";
					@$result_l 	= power($dt_client->ID_CLIENT);
					@$remain_l 	= $result_l['remain'];
					@$class_l  	= $result_l['class'];
					
					$client_url = "<span class='code'>".@$dt_client->CLIENT_NAME."</span>";
					if(!empty($dt_client->CLIENT_URL)){ 
						$client_url = " <a href='".$dt_client->CLIENT_URL."' class='code' target='_blank'>
											".@$dt_client->CLIENT_NAME."
										</a>"; 
						$client_web	= "
						<a href='".$dt_client->CLIENT_URL."' class='code' target='_blank'>
							<button type='button' class='btn' style='font-size:11px'>
								<i class='icsw16-link'></i> Website
							</button>
						</a>";
					}
					$statement 		= "";
					$q_discount_2 	= $db->query("SELECT VALUE,PIECE,STATEMENT,ID_PRODUCTS FROM ".$tpref."client_discounts WHERE ID_CLIENT = '".$dt_client->ID_CLIENT."'");
					$num_discount	= $db->numRows($q_discount_2);
					if($num_discount > 0 || !empty($dt_client->CLIENT_STATEMENT)){
						
                ?>
                    <tr id="id_merchant_<?php echo $dt_merchant->ID_CLIENT; ?>">
                      <td width="127">
                          <?php echo getclientlogo($dt_client->ID_CLIENT," class='thumbnail' style='width:50px'"); ?>
                      </td>
                      <td width="835">
                          <?php echo $client_url; ?>
                          <br />
                            <div style="font-size:11px; max-height:200px; overflow:scroll" class='code'>
                          	<?php 
							if($num_discount > 0){
								$persen	= "";
								$rupiah	= "";
								while($dt_discount_2 = $db->fetchNextObject($q_discount_2)){
									if($dt_discount_2->PIECE == "persen"){ $persen = "%"; 	}
									$img_show 	= "";
									$photo		= "";
									$id_product_discs 	= $dt_discount_2->ID_PRODUCTS;
									if(!empty($id_product_discs)){ 
										$f				  = 0;
										$num_product_discs= substr_count($id_product_discs,";");
										$id_product_disc  = explode(";",str_replace(",","",$id_product_discs)); 
										while($f < $num_product_discs){
											$f++;
											@$code		= $db->fob("CODE",$tpref."products"," WHERE ID_PRODUCT = '".$id_product_disc[$f]."'");
											@$photo		= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product_disc[$f]."'");
											?>
											<div class="pframe" id="pframe_<?php echo $f; ?>" style='margin:3px 0 0 4px'>
												<label><b><small class='code'><?php echo $code; ?></small></b></label>
												<?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
													<img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' class='photo' style='height:60px'/>
												<?php }else{ ?>
													<img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class='photo' style='height:60px'/>
												<?php } ?>
										   </div>
									<?php } ?>
                                 	<br clear="all" />
									<?php
									}
								echo  "<div style='border-bottom:1px dashed #666666'>
											Diskon ".$rupiah."".$dt_discount_2->VALUE."".$persen." 
											".$dt_discount_2->STATEMENT."
											<div style='clear:both; heigth:4px'></div>
									   </div>";
								}
							}else{
								echo $dt_client->CLIENT_STATEMENT;
							}
							?>
                            </div>
                            Bergabung 			: <?php echo $dtime->date2indodate($dt_merchant->TGLUPDATE); ?>
                            <br />
                            Power Of We (POW) 	: <?php echo $remain_l; ?>% 
                            <div class="progress <?php echo $class_l; ?> progress-striped active">
                                <div class="bar" style="width: <?php echo $remain_l; ?>%"></div>
                            </div>
                            <input type="hidden" id="power_<?php echo $dt_client->ID_CLIENT; ?>" value="<?php echo $remain_l; ?>" />
                            
                       		<div class='form-group' style="margin:10px 0 0 0; padding:0; float:left">
                            	<?php echo $client_web; ?>
                        	</div>
                      </td>
                  </tr>
                <?php }
				} ?>
                <tr style="display:none">
                	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
              </tbody>
          </table>
                
        <div class='ibox-title' style="text-align:center; margin:0" id="comm_footer_<?php echo $to_id_comm; ?>">
            <?php if($num_current > 0){ ?>
                <a href="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/direction.php?id_com=<?php echo $from_id_pur; ?>&id_pur=<?php echo $to_id_comm; ?>&direction=move" class="btn fancybox fancybox.ajax"><i class="icsw16-walking-man" ></i>Pindah
                </a>
                <a href="javascript:void()" class="btn" onclick="del_comm('<?php echo $dt_comm->BY_ID_PURPLE; ?>','<?php echo $dt_comm->ID_COMMUNITY; ?>','<?php echo trim($nm_community); ?>')">
                    <i class="icsw16-bended-arrow-left"></i>Keluar
                 </a>
                 
            <?php } ?>
        </div>
</div>
<?php	
	}
	
	if(!empty($direction) && $direction == "remove_merchant"){
		$db->delete($tpref."communities_merchants"," WHERE ID_COMMUNITY = '".$from_id_comm."' AND ID_CLIENT = '".$id."'");
		echo "sdf";
	}
	
}
?>