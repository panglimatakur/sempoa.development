<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
	html,body	{ margin:0; }
	ul#gallery li{
		list-style:none;
		margin:0;
		text-align:center; 
		padding: 5px; 
		border: solid 1px #EFEFEF;
		border-radius:2px;
		-moz-border-radius:2px;
		-webkit-border-radius:2px;
	}
	.ul#gallery li:active { border: solid 1px #CCC; -moz-box-shadow: 1px 1px 5px #999; -webkit-box-shadow: 1px 1px 5px #999; box-shadow: 1px 1px 5px #999; }
	.gallery_pic{ 
		border:2px solid #FFF;
		margin-bottom:5px;
		width:98%;
		overflow:hidden;
	}
	.gallery_pic img{ width:100%; }
	.gallery_info { padding:4px; border:1px solid #D4D4D4; background:#F2F2F2; width:97%; text-align:center;  }
	.gallery_info b{ font-weight:bold;}
	.next_button{ color:#D0AA62;  width:80%; font-weight:bold; text-decoration:none; margin-top:10px; font-family:Verdana, Geneva, sans-serif;}
</style>

 
       <script language="javascript">
            function next_product(id_client){ 
                data_page 		= $("#data_page").val();
                $('div#lastPostsLoader').html('<div style="text-align:center; margin:10px; font-family:Verdana, Geneva, sans-serif;"><img src="<?php echo $dirhost; ?>/files/images/loading-bars.gif"><br>Mengambil Data...</div>');
                lastId			= $(".wrdLatest:last").attr("data-info");
                $.ajax({
                    url 	: data_page,
                    type	: "POST",
                    data	: {"id_coin":id_client,"lastID":lastId,"display":"list_product"},
                    success : function(data){
                        data = $.trim(data);
                        if (data != "") {
                            $("ul#gallery li.wrdLatest:last").after(data);
                            $('html, body').animate({scrollTop:$("#gallery").offset().bottom}, 100);
                            $("#gallery").animate({scrollTop: $("#gallery")[0].scrollHeight}, 1500);
                        }
                        if(lastId == ""){
                            $('div#lastPostsLoader').remove();
                        }
                        $('div#lastPostsLoader').empty();
                        
                    }
                });
            };
        </script>
        <div class="w-box-header">
            <b>Katalog Produk <?php echo @$nm_merchant; ?></b>
        </div>
        <div class="w-box-content black-box same-height overflow">
            <ul id="gallery" style="padding:0">
            <?php
            @$lastID 	= isset($_REQUEST['lastID']) ? $_REQUEST['lastID'] : "";
            $display	= isset($_REQUEST['display']) ? $_REQUEST['display'] : "";
            $next_list	= "";
            if(!empty($display)){
                $next_list = " AND a.ID_PRODUCT < ".$lastID." "; 
            }
            $query_str	= " SELECT 
                                a.*,b.PHOTOS 
                            FROM 
                                ".$tpref."products a,".$tpref."products_photos b 
                            WHERE 
                                a.ID_CLIENT = '".$id_coin."' AND 
                                a.ID_PRODUCT = b.ID_PRODUCT
                                ".$next_list."
                            ORDER BY a.ID_PRODUCT DESC";
                            //echo $query_str;
            $num_produk	= $db->recount($query_str);
			$discount 	= $db->fob("VALUE",$tpref."client_discounts","WHERE ID_CLIENT = '".$id_coin."' AND COMMUNITY_FLAG != '0' AND REQUEST_BY_ID_CUSTOMER = ''");
            if($num_produk > 0){
                $q_produk 	= $db->query($query_str."  LIMIT 0,10");
                while($dt_produk = $db->fetchNextObject($q_produk)){
                    $harga_diskon	= "";
                    @$photo 		= $dt_produk->PHOTOS;
                    @$harga			= $dt_produk->SALE_PRICE;
            ?>
            <li style="list-style:none; padding:3px; " class="span4 wrdLatest" data-info='<?php echo $dt_produk->ID_PRODUCT; ?>'>
                <div class='gallery_pic'>
                    <a href="<?php echo $dirhost; ?>/files/coin/download/ajax/detail.php?id_product=<?php echo $dt_produk->ID_PRODUCT; ?>&id_coin=<?php echo $id_coin; ?>" class="fancybox fancybox.ajax" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>">
					<?php if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_coin."/thumbnails/".$photo)){ ?>
                   
                        <img src='<?php echo $dirhost; ?>/<?php echo $img_dir; ?>/products/<?php echo $id_coin; ?>/thumbnails/<?php echo $photo; ?>' alt="Potongan Harga <?php echo ucwords($dt_produk->NAME); ?>" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>"/>
                    <?php }else{ ?>
                        <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' alt="Potongan Harga <?php echo ucwords($dt_produk->NAME); ?>" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>"/>
                    <?php } ?>
                    </a>
                </div>
                
                
                <div class='gallery_info'>
                    <?php echo ucwords($dt_produk->NAME); ?><br />
                    <?php if(!empty($dt_produk->SALE_PRICE)){
                                echo money("Rp.",@$harga);
                        ?>
                        <br />
                <?php } ?>
                </div>
                <div style='clear:both'></div>
            </li>
            <?php
                }
            }
            ?>
                <?php if(empty($lastID)){?><div id="lastPostsLoader"></div> <?php } ?>
            </ul>
            <?php 
            if($num_produk > 10 && @$detail != "true"){
                if(empty($lastID)){	?>
            <div class="w-box-footer" style="text-align:center">
                <a href='javascript:void()' onclick="next_product('<?php echo $id_coin; ?>')" class='next_button myButton orange'>
                    SELANJUTNYA
                </a>
                <br clear="all" />
            </div>
            <?php }
            } ?>
        </div>
</div>	
