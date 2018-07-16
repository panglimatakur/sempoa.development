<?php defined('mainload') or die('Restricted Access'); ?>
    <?php 
    $condition_comm		= "";	
    if(!empty($_REQUEST['titanium']) && $_REQUEST['titanium'] == "true"){
        $condition_comm = "AND ID_CLIENT = '1'";
    }else{
        $condition_comm = "AND ID_CLIENT = '".$id_coin."'";
    }
    $str_list_comm	= "SELECT DISTINCT(ID_COMMUNITY) FROM ".$tpref."communities_merchants WHERE ID_COMMUNITY IS NOT NULL ".$condition_comm." ORDER BY ID_COMMUNITY ASC";
    //echo $str_list_comm;
    $num_community	= $db->recount($str_list_comm);
    $q_list_comm	= $db->query($str_list_comm);
    while($dt_comm	= $db->fetchNextObject($q_list_comm)){  
        $lastID = $dt_comm->ID_COMMUNITY;
    ?>
        <div class="w-box-header">
            <b>Brand Komunitas <?php echo @$db->fob("NAME",$tpref."communities"," WHERE ID_COMMUNITY='".$dt_comm->ID_COMMUNITY."'"); ?></b>
        </div>
        <div class="w-box-content black-box same-height overflow" style="text-align:center;">
        
        <small style="color:#F00; text-align:center"> 
        <strong>NOTE :</strong><br /> COIN dari Aplikasi Discoin member <?php echo $nm_merchant; ?>, bisa di validasi dan menikmati diskon di merchant dibawah ini.</small>
        <br />
        <table style="width:100%; margin-top:5px; border:1px solid #eee">
            <tbody>
            <?php
            $q_merchant = $db->query("SELECT * FROM ".$tpref."communities_merchants WHERE ID_COMMUNITY = '".$dt_comm->ID_COMMUNITY."' AND (ID_CLIENT != '1' AND ID_CLIENT != '".$id_coin."') ORDER BY ID_COMMUNITY_MERCHANT ASC");
            while($dt_merchant	= $db->fetchNextObject($q_merchant)){
                $q_client 		= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$dt_merchant->ID_CLIENT."'");
                $dt_client		= $db->fetchNextObject($q_client);
                $q_discount_2 	= $db->query("SELECT VALUE,PIECE,STATEMENT,ID_PRODUCTS,EXPIRATION_DATE FROM ".$tpref."client_discounts WHERE ID_CLIENT = '".$dt_client->ID_CLIENT."' AND REQUEST_BY_ID_CUSTOMER = ''");
                
                $num_discount	= $db->numRows($q_discount_2);
                if(($num_discount > 0 || !empty($dt_client->CLIENT_STATEMENT)) && $dt_client->ACTIVATE_STATUS == 3){?>
                  <tr>
                    <td width="3%" class="td_logo" style="padding:5px; vertical-align:top">
                        <?php echo getclientlogo($dt_client->ID_CLIENT," class='thumbnail' style='width:90%'"); ?>
                    </td>
                    <td width="97%" style="padding:0 5px 5px 5px; text-align:left; vertical-align:top;">
                        <small class='code' style="font-weight:bold"><?php echo @$dt_client->CLIENT_NAME; ?></small>
                        <br />
                        <div style="margin-bottom:5px;">
                            <?php echo $dt_client->CLIENT_ADDRESS; ?>  
                        </div>                                   
                        <b>COIN Diskon :</b>
                        <div class="disc_st" >
                        <?php 	
                            if($num_discount > 0){
                                $persen	= "";
                                $rupiah	= "";
                                while($dt_discount_2 = $db->fetchNextObject($q_discount_2)){
                                    if($dt_discount_2->PIECE == "persen"){ $persen = "%"; 	}
                                    $img_show 	= "";
                                    $photo		= "";
                                    $exp_date	= "";
                                    $exp_date	= $dt_discount_2->EXPIRATION_DATE;
                                    if(!empty($exp_date) && $exp_date != "0000-00-00"){
                                        $exp_date = "Berlaku s/d : ".$dtime->date2indodate($exp_date);	
                                    }else{
                                        $exp_date = "";	
                                    }
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
                                            <div class="pframe" id="pframe_<?php echo $f; ?>">
                                                <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$dt_client->ID_CLIENT."/thumbnails/".$photo)){ ?>
                                                    <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $dt_client->ID_CLIENT; ?>/thumbnails/<?php echo $photo; ?>' style='height:60px'/>
                                                <?php }else{ ?>
                                                    <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' tyle='height:60px'/>
                                                <?php } ?>
                                           </div>
                                    <?php } ?>
                                    <div style="clear:both; height:0"></div>
                                    <?php
                                    }
                                echo  "<div style='border-bottom:1px dashed #666666'>
                                            Diskon ".$rupiah."".$dt_discount_2->VALUE."".$persen." 
                                            ".$dt_discount_2->STATEMENT."
                                            <br>
                                            ".@$exp_date."
                                            <div style='clear:both; heigth:4px'></div>
                                       </div>";
                                }
                            }else{
                                echo $dt_client->CLIENT_STATEMENT;
                            }
                        ?>   
                        </div>                                    

                        <a href="<?php echo $dirhost; ?>/products/community/pages/merchant/ajax/produk.php?parent_id=<?php echo $dt_client->ID_CLIENT; ?>" class="fancybox fancybox.ajax">
                            <button type="button" class="myButton green" value="show"></i>Lihat Produk</button>
                        </a>
                    </td>
                </tr>
             <?php }
                } ?>
            </tbody>
        </table>
    	</div>
    <?php } ?>  
