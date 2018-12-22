<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo @$page_title; ?></title>
    <meta name="keywords" content="<?php echo @$keywords; ?>"/>
    <meta name="description" content="<?php echo @$description; ?>"/>
    <meta property="og:title" content="<?php echo @$page_title; ?>"/>
    <meta property="og:image" content="<?php echo $dirhost; ?>/files/images/logo.png"/>
    <meta property="og:type" content="website multi komunitas brand online" /> 
    <meta property="og:site_name" content="<?php echo @$website_name; ?>"/>
    <meta property="og:description" content="<?php echo @$description; ?>"/>
	<meta property="og:url" content="<?php echo $dirhost; ?>" />
    <meta name="robots" content="INDEX, FOLLOW">
	<meta name="google-site-verification" content="lv_8vki4IR1hc6DYg0NKxi0s06uc0UOZPyB9-0rHtIE" />
	<meta content='id_ID' property='og:locale:alternate'/>
    <meta content='ID' name='geo.country'/>
    <meta content='Indonesia' name='geo.placename'/>
	<link rel="alternate" href="<?php echo $dirhost; ?>/id" hreflang="ID" />
	<link rel="alternate" href="<?php echo $dirhost; ?>" hreflang="id-id" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $web_ftpl_dir; ?>images/ico/favicon.ico">


    <link href="<?php echo $web_ftpl_dir; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $web_ftpl_dir; ?>css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $web_ftpl_dir; ?>css/animate.min.css" rel="stylesheet"> 
    <link href="<?php echo $web_ftpl_dir; ?>css/lightbox.css" rel="stylesheet"> 
	<link href="<?php echo $web_ftpl_dir; ?>css/main.css" rel="stylesheet">
	<link href="<?php echo $web_ftpl_dir; ?>css/responsive.css" rel="stylesheet">

	<link href="<?php echo $web_ftpl_dir; ?>js/switch/bootstrap-switch.css" rel="stylesheet">
	<link href="<?php echo $web_ftpl_dir; ?>js/chosen/chosen.css" rel="stylesheet">
    <!--[if lt IE 9]>
	    <script src="js/html5shiv.js"></script>
	    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="<?php echo $web_ftpl_dir; ?>images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $web_ftpl_dir; ?>images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $web_ftpl_dir; ?>images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $web_ftpl_dir; ?>images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo $web_ftpl_dir; ?>images/ico/apple-touch-icon-57-precomposed.png">
	<?php
    function top_menu($id_parent){
        global $dirhost;
        global $db;
        @$child = $db->fob("ID_PAGE_DISCOIN","system_pages_discoin","WHERE ID_PARENT='".$id_parent."' AND STATUS='1'");
        if(!empty($child)){
            $qmenu 	= $db->query("SELECT * FROM system_pages_discoin WHERE ID_PARENT = '".$id_parent."' AND STATUS='1' ORDER BY SERI ASC");
            ?>
            <ul role="menu" class="sub-menu">
            <?php while($dtmenu = $db->fetchNextObject($qmenu)){ 
                $lpage 	 = "";
                if($dtmenu->IS_FOLDER == 1){
                    $url_link = "#";	
                }else{
                    if($dtmenu->TYPE == "statis"){ $lpage = "statis/"; }
                    //$url_link = $dirhost."/website/".@$lpage."".$dtmenu->PAGE;
                    $url_link = $dirhost."/?module=website&page=".$dtmenu->PAGE;	
                }
            ?>
                <li>
                    <a href="<?php echo $url_link; ?>" title="<?php echo $dtmenu->TITLE; ?>">
                        <?php echo $dtmenu->NAME; ?>
                    </a>
                    <?php echo top_menu($dtmenu->ID_PAGE_DISCOIN); ?>
                </li>
            
            <?php 
            } ?>
            </ul>
        <?php 
        }
    }
    ?>
</head><!--/head-->
<body>
	<header id="header">      
        <div class="container">
            <div class="row">
                <div class="col-sm-12 overflow">
                   <div class="social-icons pull-right">
                        <ul class="nav nav-pills">
                            <li><a href="https://www.facebook.com/profile.php?id=100008211553917"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://twitter.com/SempoaTech"><i class="fa fa-twitter"></i></a></li>
                            <!--<li><a href=""><i class="fa fa-google-plus"></i></a></li>
                            <li><a href=""><i class="fa fa-dribbble"></i></a></li>
                            <li><a href=""><i class="fa fa-linkedin"></i></a></li>-->
                        </ul>
                    </div> 
                </div>
             </div>
        </div>
        <div class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="index.html">
                    	<h1><img src="<?php echo $web_ftpl_dir; ?>images/logo.png" alt="logo"></h1>
                    </a>
                    
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
						<?php
							$qtop_menu = $db->query("SELECT * FROM system_pages_discoin WHERE ID_PARENT = '0' AND POSITION ='top' AND STATUS='1' AND (ID_PAGE_DISCOIN != '188' AND ID_PAGE_DISCOIN != '7') AND PAGE != 'kontak' ORDER BY SERI");
							while($dtop_menu = $db->fetchNextObject($qtop_menu)){
								$id_parent = "";
								$lpage 	 = "";
								$id_parent = $dtop_menu->ID_PAGE_DISCOIN;
								if($dtop_menu->IS_FOLDER == 1){
									$url_link = "#";	
								}else{
									if($dtop_menu->TYPE == "statis"){ $lpage = "statis/"; }
									$url_link = $dirhost."/website/".@$lpage."".$dtop_menu->PAGE;	
								}
							?>
							<li>
								<a href="<?php echo $url_link; ?>" 
                                	title="<?php echo $dtop_menu->TITLE; ?>">
									<?php echo $dtop_menu->NAME; ?>
                                    <?php if($dtop_menu->PAGE == "tentang"){?>
                                    	<i class="fa fa-angle-down"></i>
                                    <?php } ?>
								</a>
                                <?php echo top_menu($dtop_menu->ID_PAGE_DISCOIN); ?>
							</li>
					   <?php 
								} 
					   ?>
                       <li class="dropdown">
                            <a href="<?php echo $dirhost; ?>/website/komunitas" title="Daftar Komunitas Sempoa">
                                Komunitas <i class="fa fa-angle-down"></i>
                            </a>
							<ul role="menu" class="sub-menu">
							<?php
								$q_comm = $db->query("SELECT a.NAME,a.ID_COMMUNITY FROM ".$tpref."communities a,".$tpref."communities_merchants b WHERE a.ID_COMMUNITY = b.ID_COMMUNITY GROUP BY b.ID_COMMUNITY");
								while($dt_comm = $db->fetchNextObject($q_comm)){?>
										<li>
											<a href="<?php echo $dirhost; ?>/website/komunitas/<?php echo $dt_comm->ID_COMMUNITY; ?>" >
												<?php echo $dt_comm->NAME; ?>
											</a>
										</li>
							<?php } ?>
							</ul>
					   </li>
                       <li>
                       	<a href="<?php echo $dirhost; ?>/website/mendaftar" 
                        	title="Mendaftar Komunitas Online Sempoa">Mendaftar</a>
                        </li>
                       <li>
                       		<a href="<?php echo $dirhost; ?>/website/login">Login Merchant</a>
                       </li>
                    </ul>
                </div>
                <div class="search">
                    <form role="form">
                        <i class="fa fa-search"></i>
                        <div class="field-toggle">
                            <input type="text" class="search-form" autocomplete="off" placeholder="Search">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <!--/#header-->
	<?php if(empty($page) || (!empty($page) && $page == "beranda")){?>
    <section id="home-slider">
        <div class="container">
            <div class="row">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators visible-xs">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="main-slider">
                                <div class="slide-text">
                                    <h1>Online Store Management</h1>
                                    <p>Multi Komunitas Brand yang anda pilih dapat anda tentukan sendiri, karena di setiap komunitas, tidak harus berkelompok berdasarkan jenis industri yang sama, namun dapat melayani kebutuhan (bukan selera) pelanggan anda, agar anda sekaligus dapat secara tidak langsung, memperluas relasi dalam menjalin kerjasama bisnis dengan brand mitra usaha lainnya</p>
                                    <!--<a href="<?php echo $dirhost; ?>/website/mendaftar" class="btn btn-common">Mendaftar</a>-->
                                </div>
                                <img src="<?php echo $web_ftpl_dir; ?>images/home/slider2/store.slider.png" class="slider-hill" alt="slider image">
                                <img src="<?php echo $web_ftpl_dir; ?>images/home/slider2/sempoa-laptop-katalog.png" class="slider-house" alt="slider image" style="width:400px;">
                                <img src="<?php echo $web_ftpl_dir; ?>images/home/slider2/direction.png" class="slider-sun" alt="slider image" style="margin-top:150px;">
                            </div>
                        </div>
                        <div class="item">
                            <div class="main-slider">
                                <div class="slide-text">
                                    <h1>Mobile Apps Marketing</h1>
                                    <p>Sebuah Aplikasi Mobile Gratis, sebagai "Kartu Pelanggan Digital" untuk badan usaha, yang menggunakan icon dari logo usaha anda, sebagai alat pengikat pelanggan sebanyak-banyaknya, sekaligus melakukan promosi usaha secara realtime, dan dapat dilihat setiap hari oleh pelanggan anda (bukan di dompet tapi dari telephone genggam pelanggan).</p>
                                    <!--<a href="<?php echo $dirhost; ?>/website/mendaftar" class="btn btn-common">Mendaftar</a>-->
                                </div>
                                <img src="<?php echo $web_ftpl_dir; ?>images/home/slider3/coin.png" class="slider-hill" alt="slider image">
                                <img src="<?php echo $web_ftpl_dir; ?>images/home/slider3/coin2.png" class="slider-house" alt="slider image">
                                <img src="<?php echo $web_ftpl_dir; ?>images/home/slider3/coin3.png" class="slider-sun" alt="slider image">
                                <!--<img src="<?php echo $web_ftpl_dir; ?>images/home/slider/birds1.png" class="slider-birds1" alt="slider image">
                                <img src="<?php echo $web_ftpl_dir; ?>images/home/slider/birds2.png" class="slider-birds2" alt="slider image">-->
                            </div>
                        </div>
                        <div class="item">
                            <div class="main-slider">
                                <div class="slide-text">
                                    <h1>Business Community</h1>
                                    <p>Multi Komunitas Brand yang anda pilih dapat anda tentukan sendiri, karena di setiap komunitas, tidak harus berkelompok berdasarkan jenis industri yang sama, namun dapat melayani kebutuhan (bukan selera) pelanggan anda, agar anda sekaligus dapat secara tidak langsung, memperluas relasi dalam menjalin kerjasama bisnis dengan brand mitra usaha lainnya</p>
                                    <!--<a href="<?php echo $dirhost; ?>/website/mendaftar" class="btn btn-common">Mendaftar</a>-->
                                </div>
                                <img src="<?php echo $web_ftpl_dir; ?>images/home/slider4/sli1.png" class="slider-hill" alt="slider image">
                                <img src="<?php echo $web_ftpl_dir; ?>images/home/slider4/sli2.png" class="slider-house" alt="slider image">
                            </div>
                        </div>
                        
                    </div>
                    <a class="left carousel-control hidden-xs" href="#carousel-example-generic" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a class="right carousel-control hidden-xs" href="#carousel-example-generic" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </div><!--/#carousel-example-generic-->
            
            
                
            </div>
        </div>
        <div class="preloader"><i class="fa fa-sun-o fa-spin"></i></div>
    </section>
    <!--/#home-slider-->
	<?php } ?>
	<?php 
        cuser_log("customer","0","Membuka Halaman ".@$page." ".@$parameters." Dari ".$user_os,"1");
        include $call->inc("zendfront/pages/".$page,"page.php"); 
    ?>

    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center bottom-separator">
                    <img src="<?php echo $web_ftpl_dir; ?>images/home/under.png" class="img-responsive inline" alt="">
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="contact-info bottom">
                        <h2>Presentasi</h2>
                        <iframe width="350" height="200" 
                            src="https://www.youtube.com/embed/kGWi-FLM2Bg" frameborder="0" allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="contact-info bottom">
                        <h2>Alamat</h2>
                        <address>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3961.100582312984!2d107.63343700000001!3d-6.878551999999999!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e70f7ef2f079%3A0x5eed41876424636c!2sJl.+Ligar+Agung%2C+Cimenyan%2C+Bandung%2C+Jawa+Barat+40191!5e0!3m2!1sid!2sid!4v1422702598115" width="320" height="200" frameborder="0" style="border:0" scrolling="no" marginheight="0" marginwidth="0"></iframe> 
                        </address>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="contact-form bottom">
                        <!--<h2>Send a message</h2>
                        <!--<form id="main-contact-form" name="contact-form" method="post" action="sendemail.php">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" required placeholder="Name">
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" required placeholder="Email Id">
                            </div>
                            <div class="form-group">
                                <textarea name="message" id="message" required class="form-control" rows="8" placeholder="Your text here"></textarea>
                            </div>                        
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-submit" value="Submit">
                            </div>
                        </form>-->
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="copyright-text text-center">
                        <p>&copy; Sempoa Technology 2016. All Rights Reserved.</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--/#footer-->

    <script type="text/javascript" src="<?php echo $web_ftpl_dir; ?>js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="<?php echo $web_ftpl_dir; ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $web_ftpl_dir; ?>js/lightbox.min.js"></script>
    <script type="text/javascript" src="<?php echo $web_ftpl_dir; ?>js/wow.min.js"></script>
    <script type="text/javascript" src="<?php echo $web_ftpl_dir; ?>js/main.js"></script>
	<script src="<?php echo $web_ftpl_dir; ?>js/jquery.blockUI.js"></script>
    <script src="<?php echo $web_ftpl_dir; ?>js/switch/bootstrap-switch.js"></script>
    <script src="<?php echo $web_ftpl_dir; ?>js/chosen/chosen.jquery.js"></script>
    <script src="<?php echo $web_ftpl_dir; ?>js/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo $web_ftpl_dir; ?>js/bootbox/bootbox.min.js"></script>
    
    <?php if(is_file($basepath."/".$page_dir."/js/js.js")){ ?>			
		<script language="javascript" src="<?php echo $dirhost; ?>/<?php echo $page_dir; ?>/js/js.js"></script>
	<?php } ?>
    
	<script>
	  /*(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-57250786-1', 'auto');
	  ga('send', 'pageview');
	 $("#send_newsletter").live("click",function(){
	 	email_letter = $("#email_letter").val();
		if(email_letter != ""){
		$("#send_newsletter").after("<img src='<?php echo $dirhost; ?>/files/images/loading.gif' style='margin:0 0 -10px 5px' id='news_loader'>");
			$.ajax({
				url 	: "<?php echo $dirhost; ?>/pages/kontak/ajax/proses.php",
				data 	:{"direction":"send_newsletter","email_letter":email_letter},
				type 	:"POST",
				success : function(response){
					bootbox.alert(response);
					$("#news_loader").remove();
				}
			});
		}else{
			bootbox.alert("Maaf, pengisian E-Mail belum lengkap");
		}
	 })*/
	</script>
</body>
</html>
