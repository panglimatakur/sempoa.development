<?php defined('mainload') or die('Restricted Access'); ?>
 
<div class="features_items"><!--features_items-->
    <h2 class="title text-center">Promo Diskon</h2>
    
    
        <div style="color:#F00; text-align:center"><b>NOTE :</b><br />Berlaku Bagi Member Dari Komunitas <?php echo @$nm_community; ?> / COIN Tervalidasi</div><br>
        <?php
        $str_discount 		= "SELECT * FROM ".$tpref."client_discounts WHERE ID_CLIENT='".$id_coin."' AND COMMUNITY_FLAG = '1' AND DISCOUNT_STATUS='3'";
        $client_statement	= $db->fob("CLIENT_STATEMENT",$tpref."clients"," WHERE ID_CLIENT='".$id_coin."'"); 
        //echo $str_discount;
        $q_discount_2 		= $db->query($str_discount);
        $num_discount		= $db->numRows($q_discount_2);
        if($num_discount > 0 || !empty($client_statement)){
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
                        $exp_date = "<small class='code'>Berlaku s/d : ".$dtime->date2indodate($exp_date)."</small>";	
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
                            <div class="col-sm-12">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                             <label><b><small class='code'><?php echo $code; ?></small></b></label>
                                            <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_coin."/thumbnails/".$photo)){ ?>
                                                <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_coin; ?>/thumbnails/<?php echo $photo; ?>' />
                                            <?php }else{ ?>
                                                <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg'/>
                                            <?php } ?>
                                        </div>
                                   </div>
                               </div>
                           </div>
                    <?php }
                    }
                    
                    if($dt_discount_2->ID_DISCOUNT_PATTERN == 1){
                        if(empty($dt_discount_2->ID_PRODUCTS)) { $for_sum = " Untuk Semua Produk";	}
                        if(!empty($dt_discount_2->ID_PRODUCTS)){ $for_sum = " Untuk Beberapa Produk";	}
                    }else{
                        $for_sum = " Untuk Minimal Pembelian ".money("Rp.",$dt_discount_2->BUY_SUMMARY); 
                    }
                echo  "<div style='clear:both; margin:0 0 3px 17px; text-align:justify; line-height:normal'>
                            Diskon ".$rupiah."".$dt_discount_2->VALUE."".$persen." ".@$for_sum."
                            <br>
                            <b>Keterangan : </b><br>
                            ".$dt_discount_2->STATEMENT."
                            <br>
                            ".@$exp_date."
                            <div style='clear:both; heigth:4px'></div>
                       </div>";
                }
            }else{
                echo $client_statement;
            }
         } ?>
</div>