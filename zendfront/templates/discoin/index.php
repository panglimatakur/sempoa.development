<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo @$meta_title; ?></title>
    <meta name="keywords" content="Diskon,potongan, diskon harga, potongan harga, <?php echo $meta_keywords; ?>"/>
    <meta name="description" content="<?php echo @$meta_description; ?>"/>
    <meta property="og:title" content="<?php echo @$meta_title; ?>"/>
    <meta property="og:image" content="<?php echo $dirhost; ?>/files/images/logos/<?php echo $logo_merchant; ?>"/>
    <meta property="og:type" content="Kartu Member Digital <?php echo $nm_merchant; ?>" /> 
    <meta property="og:site_name" content="Discoin <?php echo strtoupper($file_name); ?>"/>
    <meta property="og:description" content="<?php echo @$deskripsi_merchant; ?>"/>
    <meta property="og:url" content="<?php echo $dirhost; ?>/<?php echo $app_merchant; ?>.coin" />
    <meta name="robots" content="INDEX, FOLLOW">
    <meta content='id_ID' property='og:locale:alternate'/>
    <meta content='ID' name='geo.country'/>
    <meta content='Indonesia' name='geo.placename'/>
    
    <link href="<?php echo $dicoin_tpl_dir; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $dicoin_tpl_dir; ?>css/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $dicoin_tpl_dir; ?>css/prettyPhoto.css" rel="stylesheet">
    <link href="<?php echo $dicoin_tpl_dir; ?>css/price-range.css" rel="stylesheet">
    <link href="<?php echo $dicoin_tpl_dir; ?>css/animate.css" rel="stylesheet">
	<link href="<?php echo $dicoin_tpl_dir; ?>css/responsive.css" rel="stylesheet">
	<link href="<?php echo $dicoin_tpl_dir; ?>css/main.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="<?php echo $dicoin_tpl_dir; ?>images/ico/favicon.ico">
    <link rel="image_src" href="<?php echo $dirhost; ?>/files/images/logos/<?php echo $logo_merchant; ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $dicoin_tpl_dir; ?>images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $dicoin_tpl_dir; ?>images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $dicoin_tpl_dir; ?>images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo $dicoin_tpl_dir; ?>images/ico/apple-touch-icon-57-precomposed.png">
    <?php $color_1 = $bg_1; ?>
   	<style type="text/css">
		a#scrollUp,.dropdown-menu  li  a:hover, .dropdown-menu  li  a:focus , .overlay-icon.searchform button,.searchform  button:hover, .searchform  button:focus,.carousel-indicators li.active,.get,.tooltip-inner,.item button:hover,.product-overlay,.add-to-cart:hover,.add-to-cart:hover,.category-tab ul  li  a:hover,.nav-tabs  li.active  a, .nav-tabs  li.active  a:hover, .nav-tabs  li.active  a:focus,.pagination  li  a:hover,.view-product h3,.item-control i,.cart,#reviews button,.content-404 h2 a,.login-form form button, .signup-form form button,.or,.breadcrumbs .breadcrumb li a,#cart_items .cart_info .cart_menu,.cart_delete a:hover,.update, .check_out,.checkout-options .nav i,.shopper-info .btn-primary,.blog-post-area .post-meta ul li i,.blog-post-area  .single-blog-post .btn-primary,.pagination-area .pagination li a:hover,.pagination-area .pagination li .active,.pager-area .pager li a:hover,.blog-socials ul li a:hover,.sinlge-post-meta li i,.replay-box label,.btn.btn-primary,.usa:hover,.pagination  .active  a, .pagination  .active  span, .pagination  .active  a:hover, .pagination  .active  span:hover, .pagination  .active  a:focus, .pagination  .active  span:focus,#reviews ul li a:hover {
		  background:<?php echo $color_1; ?>;
		}
		.checkout-options .nav li a,.shop-menu ul li a:hover,.companyinfo h2  span,.single-widget ul li a:hover,.footer-bottom p span a,.item h1 span,.control-carousel:hover,.left-sidebar h2, .brands_products h2,h2.title,.productinfo h2,.product-overlay .add-to-cart,.product-overlay .add-to-cart:hover ,.choose ul li a:hover,.product-information span span,#reviews ul li a i,#cart_items .cart_info .cart_total_price,#cart_items .cart_info,.table.table-condensed.total-result span,.blog-post-area .post-meta ul span,.post-meta span i,.rating-area .ratings .color,.rating-area .color,.replay-box span,.contact-info .social-networks ul li a:hover,#reviews ul li a:hover,.mainmenu ul li a:hover, .mainmenu ul li a.active,  .shop-menu ul li a.active{
		  color: <?php echo $color_1; ?>;
		}
		.usa:active, .usa.active ,.recommended-item-control i {
		  background: none repeat scroll 0 0 <?php echo $color_1; ?>;
		}
		.pagination  .active  a, .pagination  .active  span, .pagination  .active  a:hover, .pagination  .active  span:hover, .pagination  .active  a:focus, .pagination  .active  span:focus,.replay-box form input:hover, 
		.text-area textarea:hover,.usa:hover  {
		  border: 1px solid <?php echo $color_1; ?>;
		}
		.blank-arrow label:after,.breadcrumbs .breadcrumb li a:after,.sinlge-post-meta li i:after,
		.blog-post-area 
		.post-meta ul li i:after {
		  border-color:transparent transparent transparent <?php echo $color_1; ?>;
		}
		.tooltip.top .tooltip-arrow {
		  border-top-color: <?php echo $color_1; ?>;
		}
		.cart_menu{ color:#FFF; font-weight:bold; background: <?php echo $color_1; ?>; }
		.category-tab ul {
		  border-bottom: 1px solid <?php echo $color_1; ?>;
		}
		.header-middle{
			color: <?php echo $color_1; ?>;
		}
		.header_top {
		  background: none repeat scroll 0 0 <?php echo $bg_1; ?>;
		}

		.download_btn {
		
		  background: <?php echo $bg_2; ?>;
		  background-image: -webkit-linear-gradient(top, <?php echo $bg_1; ?>,<?php echo $bg_2; ?>);
		  background-image: -moz-linear-gradient(top, <?php echo $bg_1; ?>,<?php echo $bg_2; ?>);
		  background-image: -ms-linear-gradient(top, <?php echo $bg_1; ?>, <?php echo $bg_2; ?>);
		  background-image: -o-linear-gradient(top, <?php echo $bg_1; ?>, <?php echo $bg_2; ?>);
		  background-image: linear-gradient(to bottom, <?php echo $bg_1; ?>, <?php echo $bg_2; ?>);
		  -webkit-border-radius: 5;
		  -moz-border-radius: 5;
		  border-radius: 5px;
		  text-shadow: 0px 1px 3px #666666;
		  font-family:"Century Gothic"; 
		  color: #ffffff;
		  padding: 4px 10px 3px 10px;
		  text-decoration: none;
		  text-align:left;
		}
		.download_title{ font-size:13px; } 
		.download_content{ font-size:14px; } 
		.download_btn:hover {
		  background: <?php echo $bg_2; ?>;
		  background-image: -webkit-linear-gradient(top, <?php echo $bg_2; ?>, <?php echo $bg_1; ?>);
		  background-image: -moz-linear-gradient(top, <?php echo $bg_2; ?>, <?php echo $bg_1; ?>);
		  background-image: -ms-linear-gradient(top, <?php echo $bg_2; ?>, <?php echo $bg_1; ?>);
		  background-image: -o-linear-gradient(top, <?php echo $bg_2; ?>, <?php echo $bg_1; ?>);
		  background-image: linear-gradient(to bottom, <?php echo $bg_2; ?>, <?php echo $bg_1; ?>);
		  text-decoration: none;
		  color: #ffffff;
		}
		.pframe{
			margin:7px 0 0 4px; 
			border:1px solid #FF8080; 
			background:#FFF; 
			text-align:center; 
			float:left;	
			padding:3px;
			-moz-border-radius:3px;
			-webkit-border-radius:3px;
			border-radius:3px;
		}
		.st-head-row.st-head-row-main {
		  background: <?php echo $bg_1; ?>;
		}	
		.uppercase{ text-transform:uppercase; }
		.lowercase{ text-transform:lowercase; }
		.req:after { content: " *"; color: #ff0000; }
		/*.fixed-top{ position:fixed; width:78%; }*/
			
			
		.modal-full-screen {
		  width: 100%;
		  height: 100%;
		}
	</style>
    <script src="<?php echo $dicoin_tpl_dir; ?>js/jquery.js"></script>
    <script src="<?php echo $dicoin_tpl_dir; ?>js/cookies/js.cookie.js"></script>
	<script src="<?php echo $dicoin_tpl_dir; ?>js/bootbox/bootbox.js"></script>
    <link href="<?php echo $dicoin_tpl_dir; ?>js/switch/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script type="text/javascript" src="<?php echo $dicoin_tpl_dir; ?>js/switch/js/bootstrap-toggle.min.js"></script>
	<script type="text/javascript" src="<?php echo $dicoin_tpl_dir; ?>sidemenu/js/jquery.ntm.js"></script>
</head><!--/head-->
<body >

<input type="hidden" id="config" value='"id_client":"<?php echo $id_coin; ?>","dirhost":"<?php echo $dirhost; ?>","position":"<?php echo @$pos; ?>","page":"<?php echo @$page; ?>","realtime":"<?php echo @$realtime; ?>"'/>
	<header id="header"><!--header-->
		<div class="header_top"><!--header_top-->
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="contactinfo">
							<ul class="nav nav-pills">
								<li><a href="#">
                                	<i class="fa fa-phone"></i> Kontak : <?php echo @$phone_merchant; ?></a>
                                </li>
								<!--<li><a href="mailto:support@sempoa.biz"><i class="fa fa-envelope"></i> support@sempoa.biz</a></li>-->
							</ul>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="social-icons pull-right">
							<ul class="nav navbar-nav">
								<li><a href="https://www.facebook.com/profile.php?id=100008211553917&fref=ts" style="color:#FFF;" target="_blank" title="Sempoa Technology"><i class="fa fa-facebook"></i></a></li>
								<li><a href="https://twitter.com/SempoaTech" style="color:#FFF;" target="_blank" title="Sempoa Technology"><i class="fa fa-twitter"></i></a></li>
								<!--<li><a href="#" style="color:#FFF;"><i class="fa fa-linkedin"></i></a></li>
								<li><a href="#" style="color:#FFF;"><i class="fa fa-dribbble"></i></a></li>
								<li><a href="#" style="color:#FFF;"><i class="fa fa-google-plus"></i></a></li>-->
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header_top-->
                
        <div class="header-middle"><!--header-middle-->
            <div class="container">
                <div class="row" style="border-bottom:none">
                    <div class="col-sm-6">
						<?php if(is_file($basepath."/files/images/logos/".$logo_merchant)){?>
                            <img src="<?php echo $dirhost; ?>/files/images/logos/<?php echo $logo_merchant;?>" 
                                 alt="<?php echo $meta_title; ?>" style="width:13%" class="pull-left">
                        <?php } ?>
                        <h3 class="pull-left" style="margin-left:10px;"><?php echo $nm_merchant; ?></h3> 
                    </div>
                    <div class="col-sm-6">
                        <div class="shop-menu pull-right">
                            <ul class="nav navbar-nav" style="margin-top:10px;">
								<li><a href="<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin" <?php if(!empty($discoin_page) && $discoin_page == "home"){ ?>class="active"<?php } ?> title="Halaman Depan Discoin <?php echo $nm_merchant; ?>">Beranda</a></li>
                                <li><a href="<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/products" <?php if(!empty($discoin_page) && $discoin_page == "products"){ ?>class="active"<?php } ?> title="Daftar Produk / Item <?php echo $nm_merchant; ?>">Katalog </a></li>
                                <?php if($num_discount_client > 0){?>
                                <li><a href="<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/community" <?php if(!empty($discoin_page) && $discoin_page == "community"){ ?>class="active"<?php } ?> title="Komunitas Brand <?php echo $nm_merchant; ?>">Komunitas</a></li>
                                <?php } ?>
                                <!--<li><a href="javascript:void()" onclick="ajax_fancybox('<?php echo $dirhost; ?>/zendfront/pages/discoin/contact/ajax/contact.php?id_coin=<?php echo $id_coin; ?>','50%')"  title="Menghubungi <?php echo $nm_merchant; ?>">Kontak <?php echo ucwords(strtolower($nm_merchant)); ?></a></li>-->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        
	</header><!--/header-->
	
    
	<!--<section id="slider">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<?php //include $call->inc("zendfront/templates/discoin","slider.php"); ?>
				</div>
			</div>
		</div>
	</section>-->
	<?php
    function lchild($parent){
        global $db;
        global $tpref;
		global $file_name;
		global $dirhost;
        $qlink 	= $db->query("SELECT * FROM ".$tpref."products_categories WHERE ID_PARENT='".$parent."' ORDER BY SERI ASC");
        $jml 	= $db->numRows($qlink);
        if($jml >0){?>
        <div id="child_<?php echo $parent; ?>" class="panel-collapse collapse">
            <div class="panel-body">
                <ul>
					<?php while($dt = $db->fetchNextObject($qlink)){?>
                            <a href="<?php echo $dirhost;?>/<?php echo $file_name;?>.coin/products/<?php echo $dt->ID_PRODUCT_CATEGORY; ?>/<?php echo permalink($dt->NAME,""); ?>" title="Kategori <?php echo $dt->NAME; ?>"><?php echo $dt->NAME; ?></a>
                    <?php  } ?>
            	</ul>
            </div>
        </div>
    <?php
        }
    }
	$qlink 	= $db->query("SELECT * FROM ".$tpref."products_categories WHERE ID_CLIENT='".$id_coin."' AND ID_PARENT='0' ORDER BY SERI ASC");
	@$num_link = $db->numRows($qlink);
	if(empty($downloaded)){ $downloaded = 0; }
    ?>
	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="left-sidebar">
                    	<a name="discoin_app"></a>
                        
                        
                        <?php if(!empty($playstore) && $playstore == '1'){?>   
                        
                        	<a href='http://play.google.com/store/apps/details?id=<?php echo $app_package; ?>'>
                            	<img src="<?php echo $dirhost; ?>/files/images/playStore.png" style="width:100%">
                            </a>
                        <?php }else{
							if(is_file($basepath."/files/coin/".$app_merchant."/".strtoupper($app_merchant)."COIN.apk")){
						?>
                        <h2 style="margin-bottom:3px">Android OS</h2>
                        <form method="post" action="">
                            <div style="text-align:center; margin-top:10px" class="col-md-12">
                                <a href="javascript:void()" onclick="ajax_fancybox('<?php echo $dirhost; ?>/zendfront/pages/discoin/download/ajax/settings.php?id_coin=<?php echo $id_coin; ?>','600px','500px')" title="Android OS Online">
                                    <button type="button" class="btn download_btn" >
                                        <div class="pull-left col-md-2" style="padding:0">
                                        <img src="<?php echo $dirhost; ?>/files/images/android.gif" style="width:30px" title="Android OS Online" alt="Android OS Online">		
                                        </div>
                                        <div class="pull-left col-md-9" style="padding-top:5px;">
                                            <span class="download_title">Unduh Kartu Member</span>
                                        </div>
                                        
                                    </button>
                                </a>
                                <br>
                                <b>(Diunduh <?php echo @$downloaded; ?> Kali)</b>
                            </div>
                        </form>
                        <?php }
						}
						?>
                        <div style="clear:both;text-align:center; padding-top:14px;">
                            <a href="javascript:void()" onclick="note2FB()" title="Share To Facebook">
                            	<img src="<?php echo $dirhost; ?>/files/images/icons/fb_share.png"  title="Share To Facebook"  alt="Share To Facebook">
                            </a>
                            <a href="javascript:void()" onclick="note2Twitter()" title="Share To Twitter">
                            	<img src="<?php echo $dirhost; ?>/files/images/icons/twitter_share.png" title="Share To Twitter"  alt="Share To Twitter">
                            </a>
                            <a href="javascript:void()" onclick="note2Pinterest()" title="Share To Pinterest"><img
                              src="<?php echo $dirhost; ?>/files/images/icons/pinteres_share.png" title="Share To Pinterest" alt="Share To Pinterest"/>
                            </a>                            
                            <a href="javascript:void()" onclick="note2GPlus()" title="Share To Goole+"><img
                              src="<?php echo $dirhost; ?>/files/images/icons/google_share.png" title="Share To Goole+"  alt="Share To Goole+"/>
                            </a>   
                            <a href="javascript:void()" onclick="note2LinkedIn()" title="Share To LinkedIn"><img
                              src="<?php echo $dirhost; ?>/files/images/icons/linkedin_share.png" title="Share To LinkedIn"  alt="Share To LinkedIn"/>
                            </a>                            
                        </div>
                        <br>
                    	<?php if(!empty($num_link) && $num_link > 0){?>
						<h2>Kategori</h2>
					  	<div class="panel-group category-products" id="accordian"><!--category-productsr-->
							<?php while($dt = $db->fetchNextObject($qlink)){ ?>
							<div class="panel panel-default">
                                <div class="panel-heading">
									<h4 class="panel-title">
									<?php
                                    @$jml 	= $db->recount("SELECT 
																ID_PRODUCT_CATEGORY 
															FROM 
																".$tpref."products_categories 
															WHERE ID_PARENT='".$dt->ID_PRODUCT_CATEGORY."' ");
                                    if($jml > 0){  
                                        $href = '
					<a data-toggle="collapse" data-parent="#accordian" href="#child_'.$dt->ID_PRODUCT_CATEGORY.'">
                         <span class="badge pull-right"><i class="fa fa-plus"></i></span>'; 
                                    }else{
                                        $href = '<a href="'.$dirhost.'/'.$file_name.'.coin/products/'.$dt->ID_PRODUCT_CATEGORY.'/'.permalink($dt->NAME,"").'" title="Kategori '.$dt->NAME.'">'; 
                                    }
                                    ?>                                 
										 <?php echo @$href; ?>
                                            <?php echo @$dt->NAME; ?>
                                        </a>
                                        </p>
									</h4>
								</div>
                                <?php if($jml >0){ echo lchild($dt->ID_PRODUCT_CATEGORY); } ?>
							</div>
                            <?php } ?>
						</div><!--/category-products--><!--/brands_products--><!--/price-range-->
						<?php } ?>
                        	
                        <h2>Newsletter</h2>
                        <p style="text-align:justify">
                        Masukkan email Anda di sini dan dapatkan informasi terbaru tentang brand terbaru, yang bergabung di komunitas brand <?php echo $nm_merchant; ?>, dan informasi diskon komunitas brand terbaru &nbsp; 
                         </p>
                        <input type="email" id="email_letter" value="" style="margin:0;" class="form-control" required placeholder="E-Mail">
                        <br>
                        <button type="button" class='btn btn-primary' id='send_newsletter' value='send_newsletter' style="margin:-2px 0 0 0;">Daftarkan Email</button>
						<!--<div class="shipping text-center">
							<img src="<?php echo $dicoin_tpl_dir; ?>images/home/shipping.jpg" alt="" />
						</div><!--shipping-->

						<?php
						$q_testimonial 	 = $db->query("SELECT * FROM ".$tpref."clients_testimonials WHERE ID_CLIENT = '".$id_coin."' ORDER BY ID_CLIENT_TESTIMONIAL DESC LIMIT 0,10");
						$num_testimonial = $db->numRows($q_testimonial); 
						if($num_testimonial > 0){
						?>
                        
                        <h2 style="margin-top:10px">Testimonial</h2>
                        <p style="text-align:justify">
                        	<?php 
							while($dt_testimonial = $db->fetchNextObject($q_testimonial)){ 
								if(empty($dt_testimonial->ID_CUSTOMER)){
									$nama 		= $dt_testimonial->NAME;
									$photo 		= "<img src='".$dirhost."/files/images/members/big/".$dt_testimonial->PHOTO."'>";	
								}else{
									$q_member = $db->query("SELECT CUSTOMER_PHOTO, CUSTOMER_NAME FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$dt_testimonial->ID_CUSTOMER."'");
									$dt_member  = $db->fetchNextObject($q_member);
									$nama 		= $dt_member->CUSTOMER_NAME;
									$photo 		= "<img src='".$dirhost."/files/images/members/big/".$dt_member->CUSTOMER_PHOTO."'>";	
								}
								if(empty($photo)){
									$photo 	= "<img src='".$dirhost."/files/images/noimage-m.jpg'>";	
								}
							?>
                                <div class="col-sm-4" style="width:100%;">
                                    <div class="product-image-wrapper" style=" height:auto">
                                        <div class="single-products">
                                            <div class="productinfo text-center">
                                                <div class="overflow_height_1">
                                                	<?php echo @$photo; ?> 
                                                </div>                 
                                                <p style="margin:5px; font-weight:bold"><?php echo ucwords(@$nama); ?></p>
                                            </div>
                                            <div style="text-align:justify; font-size:11px;padding:5px;"><?php echo $dt_testimonial->TESTIMONIAL; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <br clear="all">
							<?php } ?>
                        </p>
                        
                        <?php } ?>
					</div>
				</div>
				
				<div class="col-sm-9 padding-right">
					<?php 
						if(!empty($direction) && $direction == "download"){
							include $call->inc("zendfront/pages/discoin/download","controller.php");
						}
						include $call->inc("zendfront/pages/discoin/".$discoin_page,"page.php");  
					?>
				</div>
			</div>
		</div>
	</section>
	
	<footer id="footer" style="margin-top:15px;"><!--Footer-->
		<div class="footer-top">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<div class="companyinfo">
							<h2><span><?php echo @$illuminate[0]; ?></span> <?php echo @$illuminate[1]; ?></h2>
							<p style="text-align:justify"><?php echo cutext(@$deskripsi_merchant,500); ?><br>
                            <!--<a href="#">..Baca Selanjutnya</a>--></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="footer-bottom">
			<div class="container">
				<div class="row">
					<p class="pull-left">Copyright © 2013 Sempoa Technology All rights reserved.</p>
					<p class="pull-right">
                        	<a href="<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/policy" style="color:#900">Kebijakan Privasi</a>
                   	</p>
				</div>
			</div>
		</div>
		
	</footer><!--/Footer-->
    <div class="modal fade modal-bg" id="shoppingCartModal" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    	<span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="ajaxModalLabel"><i class='fa fa-shopping-cart'></i> Troli Belanja</h4>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
	<script src="<?php echo $dicoin_tpl_dir; ?>js/bootstrap.min.js"></script>
	<script src="<?php echo $dicoin_tpl_dir; ?>js/jquery.scrollUp.min.js"></script>
	<script src="<?php echo $dicoin_tpl_dir; ?>js/price-range.js"></script>
    <script src="<?php echo $dicoin_tpl_dir; ?>js/jquery.prettyPhoto.js"></script>
    <script src="<?php echo $dicoin_tpl_dir; ?>js/main.js"></script>
	<script type="text/javascript" src="<?php echo $dirhost; ?>/libraries/fancybox/js/jquery-1.8.2.min.js"></script>
	<?php include $call->lib("fancybox"); ?>
	<script language="javascript">
		function ajax_fancybox(location,width,height){
			var screen_width 	= $(document).width();
			var fancy_width 	= "auto"; 
			var sizer 			= true; 
			if(width != "undefined"){ 
				if(screen_width > 500)	{ fancy_width = width;  var sizer = false; }
			}
			$.fancybox.open([{
				type	: 'ajax',
				href 	: location,                
			}],{'autoSize' : sizer,'width':fancy_width,'height':'auto',padding : "7px 0 0 0"}); 
			//'autoSize' : false,'width':''+width+'','height':''+height+''
		}
	
  		$(document).ready(function(){
			var title		= 'Discoin <?php echo $nm_merchant; ?> - <?php echo @$meta_title; ?>';
			var imgsrc 		= '<?php echo $dirhost; ?>/files/images/logos/<?php echo $logo_merchant; ?>';
			var direction	= '<?php echo $dirhost; ?>/<?php echo $app_merchant; ?>.coin';
			var deskripsi	= 'RECOMMENDED!! <?php echo @$meta_description; ?>';
			function note2FB(){			
				fb_share(deskripsi,title,imgsrc,direction,deskripsi);	
			}
			
			function note2Twitter() {
				deskripsi	= 'RECOMMENDED!! Aplikasi Kartu Member Digital <?php echo $nm_merchant; ?> - download gratis , dari Smartphone androidmu';
				var width  = 600,
					height = 600,
					left   = ($(window).width()  - width)  / 2,
					top    = ($(window).height() - height) / 2,
					url    = 'http://twitter.com/share/?text='+deskripsi,
					opts   = 'status=1' +
							 ',width='  + width  +
							 ',height=' + height +
							 ',top='    + top    +
							 ',left='   + left;
				
				window.open(url, 'twitter', opts);
				return false;
			}
			function note2GPlus(){
				pop_up = "https://plus.google.com/share?url='"+direction+"'";
				window.open(pop_up,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
				return false;
			}
			function note2LinkedIn(){
				pop_up 	= "http://www.linkedin.com/shareArticle?url="+direction+"&title="+title;
				window.open(pop_up,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
				return false;
			}
			function note2Pinterest(){
					window.open("//www.pinterest.com/pin/create/button/"+"?url="+direction+"&media="+imgsrc+"&description="+deskripsi,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
					return false;		
			}
		
		
            $('.demo').ntm();
			$(".to-cart").on("click", function() {
				url = $(this).attr("data-url");
				$("#shoppingCartModal .modal-dialog").removeClass("modal-full-screen");
				$("#shoppingCartModal").modal("show");
				$("#shoppingCartModal .modal-body").load(url);
			});
			$("#send_newsletter").on("click",function(){
				email_letter = $("#email_letter").val();
				id_client 	 = '<?php echo $id_coin; ?>';
				if(email_letter != ""){
				$("#send_newsletter").after("<img src='<?php echo $dirhost; ?>/files/images/loading.gif' style='margin:0 0 -8x 5px' id='news_loader'>");
					$.ajax({
						url 	: "<?php echo $dirhost; ?>/pages/discoin/contact/ajax/proses.php",
						data 	:{"direction":"send_newsletter","id_client":id_client,"email_letter":email_letter},
						type 	:"POST",
						success : function(response){
							alert(response);
							$("#news_loader").remove();
						}
					});
				}else{
					alert("Maaf, pengisian E-Mail belum lengkap");
				}
			})
			$("#new_customer").on("click",function(){
				new_email 		= $("#vis_email").val();
				new_nama		= $("#vis_nama").val();
				new_pass		= $("#vis_pass").val();
				//konf_pass		= $("#konf_pass").val();
				var config 		= JSON.parse("{"+$("#config").val()+"}");
				var dirhost		= config.dirhost;		
				if(new_email != "" && new_nama != "" && new_pass != ""){
					
					redirect 	= $(this).val();
					regist_page = $("#regist_page").val();
					$("#load_new_email").html('<div style="clear:both; text-align:center; margin:10px; font-family:Verdana, Geneva, sans-serif;"><img src="'+dirhost+'/files/images/loader_v.gif"><br>Mendaftarkan Email</div>');
					$.ajax({
						url 	: regist_page,
						data 	: {"direction":"regist_new_customer","new_nama":new_nama,
									"new_email":new_email,"new_pass":new_pass},
						type	: "POST",
						success: function(response){
							if(response != "null"){
								$.fancybox.close();
								ajax_fancybox(redirect);
							}else{
								$("#load_new_email").html("<div class='alert alert-danger'>Maaf, Penulisan Email Blm Valid</div>");
								$("#vis_email").val("").focus();
							}
						}
					})
					
				}else{
					$("#load_new_email").html("<div class='alert alert-danger'>Pengisian Form Belum Lengkap</div>");
				}
			})
			
		})
	</script>
    
    <?php if(is_file($basepath."/zendfront/pages/discoin/".$discoin_page."/js/js.js")){?>
		<script src="<?php echo $dirhost; ?>/zendfront/pages/discoin/<?php echo $discoin_page; ?>/js/js.js"></script>
    <?php } ?>
</body>
</html>