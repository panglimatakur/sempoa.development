<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
.item-list{
	position:relative;
    border-bottom: 1px #e2e2e2 solid;
    padding: 6px 10px 3px;
    color: #555;
    font-size: 12px;
    background: #f2f2f2;
    -webkit-box-shadow: inset 0 1px 0 #fafafa;
    -moz-box-shadow: inset 0 1px 0 #fafafa;
    -ms-box-shadow: inset 0 1px 0 #fafafa;
    box-shadow: inset 0 1px 0 #fafafa;
    line-height: 16px;
    -webkit-overflow-scrolling: touch;
}
.item-list label{ 
	font-size: 11px;
	padding:4px;
	margin-top:8px;
	margin-right:8px;
	font-family:"Courier New", Courier, monospace;  
	float: left;
	background:#F7F7F7;
	border-radius:3px;
    -webkit-box-shadow: inset 0 1px 0 #fafafa;
    -moz-box-shadow: inset 0 1px 0 #fafafa;
    -ms-box-shadow: inset 0 1px 0 #fafafa;
    box-shadow: inset 0 1px 0 #fafafa;
}
.item-list .comm_label{ color:#67C77C; 		}
.item-list .no_comm_label{ color:#F8787B; 	}
.item-list .link-join{ 
	position:absolute;
	margin-top:4px;
	right:0;
	top:0;
}
.item-list img { width:100%; }
.item-list .img-avatar{ width:70px;  }
.item-list .img-avatar-inner{ text-align:center; height:60px; overflow:hidden;}
</style>
<div id="merchant_list">
    <?php
    while($dt_merchant 	= $db->fetchNextObject($q_merchant)){
		$community = "";
		$str_community   = "  SELECT * 
							  FROM 
								".$tpref."communities_merchants a, 
								".$tpref."communities b
							  WHERE 
								a.ID_CLIENT = '".$dt_merchant->ID_CLIENT."' AND
								a.ID_COMMUNITY = b.ID_COMMUNITY
							  ORDER BY b.NAME ASC";
		//echo $str_community;
		$q_community   	= $db->query($str_community);
		$num_community	= $db->numRows($q_community);
		if($num_community > 0){
			while($dt_community = $db->fetchNextObject($q_community)){ 
				$community .= "<label class='comm_label' title='".$dt_community->ID_COMMUNITY_MERCHANT."'>".$dt_community->NAME."</label> ";	
			}
			$communities	= substr($community,0, -1);
		}
		
	?>
        <div id="li_<?php echo $dt_merchant->ID_CLIENT; ?>" data-info="<?php echo $dt_merchant->ID_CLIENT; ?>" title="ID <?php echo $dt_merchant->ID_CLIENT; ?>" class="item-list">
            <a href="javascript:void()" class="link-join" onclick="pick('<?php echo $dt_merchant->ID_CLIENT; ?>')" id="btn_<?php echo $dt_merchant->ID_CLIENT; ?>" >
                <i class="icsw16-go-back-from-screen"></i>
            </a>
            <div class="col-md-3 no-padding-lr">
            	<div class="thumbnail md">
                    <?php if(is_file($basepath."/files/images/logos/".$dt_merchant->CLIENT_LOGO)){?>
                        <img src="<?php echo $dirhost; ?>/files/images/logos/<?php echo $dt_merchant->CLIENT_LOGO; ?>">
                    <?php }else{ ?>
                        <img src="<?php echo $dirhost; ?>/files/images/no_image.jpg">
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-9">
				<b><?php echo $dt_merchant->CLIENT_NAME; ?></b><br />
                <small>
				<?php if($num_community > 0){?>
					<?php echo @$communities; ?>
				<?php }else{ ?>
                	<label class='no_comm_label'>Merchant ini blm memiliki komunitas</label>
                <?php } ?>
                </small>
            </div>
         	<input type='hidden' name='client_list[]' value='<?php echo $dt_merchant->ID_CLIENT; ?>' />
         	<div class='clearfix'></div>
        </div>
    <?php } ?>	
        <div class='clearfix'></div>
</div>


