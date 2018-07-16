<?php defined('mainload') or die('Restricted Access'); ?>
    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 text-center padding wow fadeIn" data-wow-duration="500ms" data-wow-delay="300ms">
                    <div class="single-service">
                        <div class="wow scaleIn" data-wow-duration="500ms" data-wow-delay="300ms">
                            <img src="<?php echo $dirhost; ?>/files/images/ostore.png" style="width:100px;"/>
                        </div>
                        <h2>Toko Online Marketing</h2>
                        <p>Anda dapat memiliki toko online sendiri untuk memaksimalkan penjualan produk-produk usaha, secara online, dan dapat berlatih teknik-teknis SEO untuk meningkatkan rangking toko online anda di mesin pencari </p>
                    </div>
                </div>
                <div class="col-sm-4 text-center padding wow fadeIn" data-wow-duration="500ms" data-wow-delay="600ms">
                    <div class="single-service">
                        <div class="wow scaleIn" data-wow-duration="500ms" data-wow-delay="600ms">
                             <img src="<?php echo $dirhost; ?>/files/images/discoin_coin_2.png" style="width:100px;"/>
                        </div>
                        <h2>Mobile Apps Marketing</h2>
                        <p>Anda dapat memiliki aplikasi android gratis,  berfungsi sebagai kartu member digital untuk perekrutan pelanggan anda, yang dirancang untuk mengangkat brand usaha anda, dan mendekatkan anda dan pelanggan anda</p>
                    </div>
                </div>
                <div class="col-sm-4 text-center padding wow fadeIn" data-wow-duration="500ms" data-wow-delay="900ms">
                    <div class="single-service">
                        <div class="wow scaleIn" data-wow-duration="500ms" data-wow-delay="900ms">
                            <img src="<?php echo $dirhost; ?>/files/images/ocomm.png" style="width:100px;">
                        </div>
                        <h2>Komunitas Bisnis</h2>
                        <p>Anda juga dapat terhubung dengan komunitas bisnis lainnya, dan menghubungkan pelanggan anda dengan komunitas bisnis anda untuk mengikuti program diskon komunitas, untuk pelayanan pelanggan anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/#services-->


	
    <section id="blog">
        <div class="container">
            <div class="row">
            
            	<div class="wow fadeIn" data-wow-duration="500ms" data-wow-delay="100ms">
                    <h2 class="page-header">ANGGOTA</h2>
                    <?php while($dt_client = $db->fetchNextObject($q_client)){ 
                            $last_id = $dt_client->ID_CLIENT; 
							$size	  = "";
							$width	  = ""; $height = "";
						if(is_file($basepath."/files/images/logos/".$dt_client->CLIENT_LOGO)){
							$logo_file 	= $basepath."/files/images/logos/".$dt_client->CLIENT_LOGO;
							list($width, $height, $type, $attr) = getimagesize($logo_file);
							if($width > $height){ $size = "style='width:100%; margin:auto 0 auto 0'"; 		}
							elseif($width < $height){ $size = "style='height:100%; margin:0 auto 0 auto'"; 	}
							else{ $size = "style='height:100%; margin:0 auto 0 auto'"; }
						}
                    ?>
                         <div class="col-md-3 col-sm-6 blog-padding-right">
                            <div class="single-blog two-column">
                                <div class="post-thumb thumbnail">
                                	<div class="thumbnail-inner" style="height:220px;">
                                    <?php if(is_file($basepath."/files/images/logos/".$dt_client->CLIENT_LOGO)){?>
                                        <img src="<?php echo $dirhost; ?>/files/images/logos/<?php echo str_replace(" ","%20",$dt_client->CLIENT_LOGO); ?>" alt="Logo <?php echo $dt_client->CLIENT_NAME; ?>" <?php echo @$size; ?>>
                                    <?php }else{ ?>
                                        <img src="<?php echo $dirhost; ?>/files/images/no_image.jpg" alt="Logo <?php echo $dt_client->CLIENT_NAME; ?>" width="100%">
                                    <?php } ?>
                                    </div>
                                </div>
                                <div class="post-content overflow">
                                    <h3 class="post-title bold"><?php echo $dt_client->CLIENT_NAME; ?></h3>
                                    <p style="height:100px;overflow:hidden">
                                        <?php echo cutext($dt_client->CLIENT_DESCRIPTIONS,120); ?> [...]
                                    </p>
                                    <a href="<?php echo $dirhost; ?>/<?php echo $dt_client->CLIENT_APP; ?>.coin" class="read-more" title="Discoin <?php echo $dt_client->CLIENT_NAME; ?>" target="_blank">Kunjungi Merchant..</a>
                                    <!--<div class="post-bottom overflow">
                                        <ul class="nav nav-justified post-nav">
                                            <li><a href="#"><i class="fa fa-heart"></i>32 Love</a></li>
                                            <li><a href="#"><i class="fa fa-comments"></i>3 Comments</a></li>
                                        </ul>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    
                    <?php } ?>
                
                </div>
                
                <div class="clearfix"></div>
            	<div class="wow fadeIn" data-wow-duration="1000ms" data-wow-delay="100ms">
                    <h2 class="page-header">KOMUNITAS BISNIS</h2>
                    <div class="list-group">
                    <?php while($dt_communities = $db->fetchNextObject($q_communities)){ 
                        $jml_merchant = $db->recount("SELECT ID_COMMUNITY_MERCHANT FROM ".$tpref."communities_merchants WHERE ID_COMMUNITY = '".$dt_communities->ID_COMMUNITY."'");
                    ?>
                        <div class="col-md-3 col-sm-6" style="margin-bottom:10px;">
                            <div class="list-group-item">
                                <a href="<?php echo $dirhost; ?>/website/komunitas/<?php echo $dt_communities->ID_COMMUNITY; ?>" style="text-decoration:none;">
                                    <?php echo $dt_communities->NAME; ?> 
                                    <span class="badge badge-default badge-pill"><?php echo $jml_merchant; ?></span>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                
                <div class="clearfix"></div>
                
                <div class="wow fadeIn" data-wow-duration="1000ms" data-wow-delay="100ms">
                <h2 class="page-header">PRODUK - PRODUK</h2>
            	<?php
				while($dt_produk = $db->fetchNextObject($q_produk)){ $last_id = $dt_produk->ID_PRODUCT; 
                    
                    $q_merchant 	= $db->query("SELECT CLIENT_NAME,CLIENT_APP,ACTIVATE_STATUS FROM ".$tpref."clients WHERE ID_CLIENT = '".$dt_produk->ID_CLIENT."'");
                    $dt_merchant 	= $db->fetchNextObject($q_merchant);
                    $ch_status 		= $dt_merchant->ACTIVATE_STATUS;
                    $nm_merchant 	= $dt_merchant->CLIENT_NAME;
                    $client_app		= $dt_merchant->CLIENT_APP;
                    if($ch_status == 3){
                ?>
                     <div class="col-md-3 col-sm-6 blog-padding-right" style="height:500px;">
                        <div class="single-blog two-column">
                            <div class="post-thumb thumbnail">
                            		<div class="thumbnail-inner" style="height:220px;">
                                    <a href="<?php echo $dirhost; ?>/<?php echo $client_app; ?>.coin">
                                <?php if(!empty($dt_produk->PHOTOS) && is_file($basepath."/files/images/products/".$dt_produk->ID_CLIENT."/".$dt_produk->PHOTOS)){?>
                                    <img src="<?php echo $dirhost; ?>/files/images/products/<?php echo $dt_produk->ID_CLIENT; ?>/<?php echo $dt_produk->PHOTOS; ?>" alt="<?php echo $dt_produk->NAME; ?>" width="100%">
                                <?php }else{ ?>
                                    <img src="<?php echo $dirhost; ?>/files/images/no_image.jpg" alt="Logo <?php echo $dt_client->CLIENT_NAME; ?>" width="100%">
                                <?php } ?>
                                	</a>
                                    </div>
                                
                            </div>
                        </div>
                        
                        <div class="post-content overflow">
                            <h3 class="post-title bold"><?php echo $dt_produk->NAME; ?></h3>
                            <small style="color:#F00">By : <?php echo $nm_merchant; ?></small>
                            <p style="height:80px;overflow:hidden; text-align:justify">
								<?php echo cutext($dt_produk->DESCRIPTION,120); ?>
                            	<br />
                            </p>
							<?php if(!empty($dt_produk->SALE_PRICE)){?>
                                <p style="text-align:justify"><?php echo money("Rp.",$dt_produk->SALE_PRICE); ?></p>
                            <?php } ?>
                            <a href="<?php echo $dirhost; ?>/<?php echo $client_app; ?>.coin" class="more" title="Discoin <?php echo $dt_produk->NAME; ?>" target="_blank">Lihat Produk</a>
                        </div>
                    </div>
                <?php }
				}
				?>
            
            	</div>
            
            </div>
        </div>
	</div>
	<br /><br /><br />
