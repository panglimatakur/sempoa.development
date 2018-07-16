<?php defined('mainload') or die('Restricted Access'); ?>
<div class="features_items"><!--features_items-->   
    <h2 class="title text-center">Mitra <?php echo @$nm_merchant; ?></h2>
    <div style="color:#F00; text-align:center"> 
    
    	<strong>NOTE :</strong><br /> 
    	COIN dari Aplikasi Discoin member <?php echo $nm_merchant; ?>, bisa di validasi untuk menikmati diskon belanja di merchant dibawah ini.
    
    </div>
    <br />
</div>

<div class="features_items"><!--category-tab-->
    <div class="col-sm-12">
        <ul class="nav nav-tabs">
            <?php 
            $i = 0;
            while($dt_comm	= $db->fetchNextObject($q_list_comm)){  
                $i++;
                $lastID 		= $dt_comm->ID_COMMUNITY;
                $nm_komunitas 	= @$db->fob("NAME",$tpref."communities"," WHERE ID_COMMUNITY='".$dt_comm->ID_COMMUNITY."'");
                ?>
            <li <?php if($i==1){?>class="active"<?php } ?>><a href="#komunitas_<?php echo $dt_comm->ID_COMMUNITY; ?>" data-toggle="tab">Komunitas <?php echo $nm_komunitas; ?></a></li>
            <?php } ?>
        </ul>
    </div>
   
    
    <br clear="all" />
  	<br clear="all" />  
    <div class="tab-content">
    <?php
    while($dt_comm	= $db->fetchNextObject($q_list_merch)){  $j++;
        $str_merchant  	= "SELECT 
                                a.ID_COMMUNITY,
                                b.ID_CLIENT,
                                b.CLIENT_NAME,
								b.CLIENT_APP
                           FROM 
                                ".$tpref."communities_merchants a,".$tpref."clients b
                           WHERE 
                                a.ID_CLIENT = b.ID_CLIENT AND
                                b.ACTIVATE_STATUS = '3' AND
                                a.ID_COMMUNITY = '".$dt_comm->ID_COMMUNITY."' AND 
                                (a.ID_CLIENT != '1' AND a.ID_CLIENT != '".$id_coin."') 
                           ORDER BY 
                                a.ID_COMMUNITY_MERCHANT ASC limit 0,100";
        $q_merchant 	= $db->query($str_merchant); ?>
        <div class="tab-pane fade  <?php if($j==1){?>active<?php } ?> in" 
        	 id="komunitas_<?php echo $dt_comm->ID_COMMUNITY; ?>" >
        <?php while($dt_merchant	= $db->fetchNextObject($q_merchant)){
					$q_discount_partner 	 = $db->query("SELECT * FROM ".$tpref."clients_discounts WHERE ID_CLIENT  = '".$dt_merchant->ID_CLIENT."'"); 
					$num_discount_partner = $db->numRows($q_discount_partner); 
					if($num_discount_partner > 0){
		?>
            <div class="col-sm-4">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <div class="thumbnail">
                                <?php echo getclientlogo($dt_merchant->ID_CLIENT,"
										   title='".$dt_merchant->CLIENT_NAME."'  
										   alt='".$dt_merchant->CLIENT_NAME."'
										   class='potrait'"); ?>
                            </div>
                            <h2 style="font-size:13px;"><?php echo @$dt_merchant->CLIENT_NAME; ?></h2>
                            <a href="<?php echo $dirhost; ?>/<?php echo @$dt_merchant->CLIENT_APP; ?>.coin">
                                <button type="button" class="btn btn-primary add-to-cart view-merchant" value="show"><i class="fa fa-home"></i>Kunjungi</button>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        <?php 
					}
		} 
		?>
        </div>
    <?php 
    } ?>            
    </div>
      
</div><!--/category-tab-->
