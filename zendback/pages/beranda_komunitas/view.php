<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
.redup{
	-webkit-filter: grayscale(100%); /* Ch 23+, Saf 6.0+, BB 10.0+ */
	filter: grayscale(100%); /* FF 35+ */	
}
</style>
<div class="col-md-12">
    <?php 
    $result = power($id_client);
    $remain = $result['remain'];
    $class 	= $result['class'];
    ?>
	<div class="ibox-title">
		<h5>Power Of We (POW) <?php if(!empty($cname)){echo $cname;  } ?> : <?php echo $remain; ?>% </h5>
	</div>
    <div class="ibox-content">
        <div class="progress progress-striped">
            <div style="width: <?php echo $remain; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $remain; ?>" role="progressbar" class="progress-bar <?php echo $class; ?>">
                <span class="sr-only"><?php echo $remain; ?>%</span>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<?php  $r = 0; 
		while($dt_community = $db->fetchNextObject($q_community)){ 
		$r++;
		$nm_community 	= $dt_community->NAME;
		$id_community 	= $dt_community->ID_COMMUNITY;
		$id_purple 		= $dt_community->BY_ID_PURPLE;
?>
<div class="col-md-4 tbl_community" id="tbl_community_<?php echo $id_community; ?>">

	<?php
		$num_current = $db->recount("SELECT ID_CLIENT FROM ".$tpref."communities_merchants 
									 WHERE ID_COMMUNITY = '".$id_community."' AND 
									 ID_CLIENT='".$_SESSION['cidkey']."' ");
		$str_statistik = "
			SELECT 
				COUNT(a.ID_CUSTOMER) AS JML_COIN, 
				SUM(70000) AS TOTAL_PROFIT
			FROM 
				cat_customers a,
				cat_communities_merchants b 
			WHERE 
				a.CUSTOMER_STATUS = '3' AND
				b.ID_COMMUNITY = '".$id_community."' AND
				a.ID_CLIENT = b.ID_CLIENT";
		$q_statistik 	= $db->query($str_statistik);
		$dt_statistik	= $db->fetchNextObject($q_statistik);
		$jml_member 	= $dt_statistik->JML_COIN;
	?>
	<div class="ibox-title">
		<h5>
			<?php echo @$nm_community; ?> 
			<small>( <?php echo $jml_member; ?> Member)</small>
		</h5>
	</div>
	<!--<div class='ibox-title' style="height:30px;text-align:center; margin:0" id="comm_footer_<?php //echo $dt_comm->ID_COMMUNITY; ?>">
		<?php //if($num_current > 0){ ?>
			<a href="<?php //echo $dirhost; ?>/<?php //echo $ajax_dir; ?>/direction.php?id_com=<?php //echo $dt_comm->ID_COMMUNITY; ?>&id_pur=<?php //echo $dt_comm->BY_ID_PURPLE; ?>&direction=move" class="btn fancybox fancybox.ajax"><i class="icsw16-walking-man" ></i>Pindah
			</a>
			<a href="javascript:void()" class="btn" onclick="del_comm('<?php echo $dt_comm->BY_ID_PURPLE; ?>','<?php //echo $dt_comm->ID_COMMUNITY; ?>','<?php //echo trim($nm_community); ?>')">
				<i class="icsw16-bended-arrow-left"></i>Keluar
			 </a>
			 
		<?php //} ?>
	</div>-->
	<div class="community-merchants">
	<table class="table table-bordered table-striped " style="width:100%" >
	  <tbody>
		<?php
		$str_merchant	=
		"SELECT 
			DISTINCT(a.ID_CLIENT), 
			b.RANK, 
			a.TGLUPDATE,
			c.CLIENT_NAME,
			c.CLIENT_LOGO,
			c.CLIENT_URL
		FROM 
			".$tpref."communities_merchants a, 
			".$tpref."clients_ranks b,
			".$tpref."clients c
		WHERE 
			a.ID_COMMUNITY = '".$id_community."' AND 
			a.ID_CLIENT != '1' AND 
			a.ID_CLIENT = b.ID_CLIENT AND
			a.ID_CLIENT = c.ID_CLIENT 
		ORDER BY b.RANK DESC";	
		//GROUP BY a.ID_CLIENT		
		//echo $str_merchant;		
		
		
		$q_merchant 		= $db->query($str_merchant);
		while($dt_merchant	= $db->fetchNextObject($q_merchant)){
			@$id_client	= $dt_merchant->ID_CLIENT;
			$logo 		= $dt_merchant->CLIENT_LOGO;
			$client_web	= "";
			@$result_l 	= power($dt_merchant->ID_CLIENT);
			@$remain_l 	= $result_l['remain'];
			@$class_l  	= $result_l['class'];
			
			$client_url = "<span class='code'>".@$dt_merchant->CLIENT_NAME."</span>";
			if(!empty($dt_merchant->CLIENT_URL)){ 
				$client_url = " <a href='".$dt_merchant->CLIENT_URL."' class='code' target='_blank'>
									".@$dt_merchant->CLIENT_NAME."
								</a>"; 
				$client_web	= "
				<a href='".$dt_merchant->CLIENT_URL."' target='_blank'>
					<button type='button' class='btn btn-white btn-sm'>
						<i class='fa fa-globe'></i> Website
					</button>
				</a>";
			}
			$statement 		= "";
			@$q_discount_2 	= $db->query("SELECT * 
										  FROM ".$tpref."clients_discounts 
										  WHERE 
										  		ID_CLIENT = '".$id_client."' AND 
												DISCOUNT_SEGMENT = 'community' AND
												DISCOUNT_STATUS = '3'");
			@$num_discount	= $db->numRows($q_discount_2);
		?>
			<tr id="id_merchant_<?php echo $id_client; ?>"
            	<?php if($num_discount == 0){?> class="redup" <?php } ?>>
			  <td style="width:20%; padding:3px" class="text-center align-top">
                <div class='img-thumbnail sm'>
                    <?php if(is_file($basepath."/files/images/logos/".$logo)){?>
                    	<img src="<?php echo $dirhost; ?>/files/images/logos/<?php echo $logo; ?>" class="potrait"/>
                    <?php }else{ ?>
                    	<img src="<?php echo $dirhost; ?>/files/images/no_image.jpg" class="potrait"/>
                    <?php } ?>
                </div>
			  </td>
			  <td style="width:80%;" class="no-padding">
				<div class="ibox-title" style="padding:5px;min-height:25px;">
					<b><?php echo $client_url; ?></b>
				</div>  
				<div class="merchant-discounts">
					<b>Diskon Komunitas</b><br />
                    <div class="merchant-discount-list">
						<?php 
						if($num_discount > 0){
							while($dt_discount_2 = $db->fetchNextObject($q_discount_2)){
							@$id_discount	= $dt_discount_2->ID_DISCOUNT;
							$discount		= $dt_discount_2->DISCOUNT;
							@$id_pattern	= $dt_discount_2->ID_DISCOUNT_PATTERN;
							@$pattern 		= $db->fob("DESCRIPTION",$tpref."discount_patterns"," 
																WHERE ID_DISCOUNT_PATTERN = '".$id_pattern."'");
						?>			
							<div>
								<a href="javascript:void()" class='text-info' 
								   onclick="view_discount('<?php echo $id_discount; ?>')">
								   <b><?php echo $discount; ?>%</b>
								   <?php echo $pattern; ?>
								</a>
							</div>
						<?php
							}
						}else{
							echo "<b class='text-danger'>0%</b>";	
						}
						?>
                    </div>
                    
				</div>
				<div class="merchant-info">
					<b>Info</b><br />
					Bergabung 			: <?php echo $dtime->date2indodate($dt_merchant->TGLUPDATE); ?>
					<br />
					Power Of We (POW) 	: <?php echo $remain_l; ?>% 
					<br />                            
					<div class="progress progress-striped">
						<div style="width: <?php echo $remain_l; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $remain_l; ?>" role="progressbar" class="progress-bar <?php echo $class_l; ?>">
							<span class="sr-only"><?php echo $remain_l; ?>%</span>
						</div>
					</div>
					<br />
					<input type="hidden" id="power_<?php echo $id_client; ?>" value="<?php echo $remain_l; ?>" />
					
					<?php echo $client_web; ?>
				</div>
                <?php if(($id_purple ==  $_SESSION['cidkey'] && $_SESSION['ulevelkey'] == 1) || 
						  $_SESSION['admin_only'] == "true"){?>
                <button type="button" class="btn btn-block btn-sempoa-1 " 
                		onclick="remove_merchant('<?php echo $id_client; ?>','<?php echo $id_community; ?>')">
                    	<i class="fa fa-sign-out"></i> Keluarkan
                 </button>
                 <?php } ?>
			  </td>
		  </tr>
		<?php 
		} 
		?>
	  </tbody>
	</table>
	</div>
	<!--<div class='ibox-title' id="comm_footer_<?php //echo $id_community; ?>">
	<?php /*if($num_current > 0){ ?>
		<div class="btn-group btn-block">
			<a href="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/direction.php?id_com=<?php echo $id_community; ?>&id_pur=<?php echo $id_purple; ?>&direction=move" class="btn btn-sempoa-1 col-md-6 fancybox fancybox.ajax "><i class="fa fa-exchange" ></i> Pindah
			</a>
			<a href="javascript:void()" class="btn col-md-6 btn-sempoa-1 " onclick="del_comm('<?php echo $id_purple; ?>','<?php echo $id_community; ?>','<?php echo trim($nm_community); ?>')">
				<i class="fa fa-sign-out"></i> Keluar
			 </a>
		 </div>
	<?php }*/ ?>
		<div class="clearfix"></div>
	</div>-->


</div>
<?php } ?>
 
<input type="hidden" id="proses_page" value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/proses.php"/>
<input type="hidden" id="data_page" value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/data.php"/>
<div id="modal-ajax-beranda" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated flipInY">
            <div class="modal-body no-padding-lr no-padding-tb"></div>
        </div>
    </div>
</div>
