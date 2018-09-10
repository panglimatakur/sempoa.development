<!DOCTYPE html>
<html>


<!-- Site: HackForums.Ru | E-mail: abuse@hackforums.ru | Skype: h2osancho -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sempoa Integrated Business Solution | Dashboard</title>

    <link href="<?php echo $web_btpl_dir; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $web_btpl_dir; ?>font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $web_btpl_dir; ?>images/favicon.ico">
    <!-- iconSweet2 icon pack (16x16) -->
    <link rel="stylesheet" href="<?php echo $web_btpl_dir; ?>img/icsw2_16/icsw2_16.css">
    <!-- iconSweet2 icon pack (32x32) -->
    <link rel="stylesheet" href="<?php echo $web_btpl_dir; ?>img/icsw2_32/icsw2_32.css">
    
    <!-- Toastr style -->
    <link href="<?php echo $web_btpl_dir; ?>css/plugins/toastr/toastr.min.css" rel="stylesheet">
               
    <!-- iCheck -->
    <link href="<?php echo $web_btpl_dir; ?>css/plugins/iCheck/custom.css" rel="stylesheet">
    <!-- Gritter -->
    <link href="<?php echo $web_btpl_dir; ?>js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    
    <!-- Choosen -->
    <link href="<?php echo $web_btpl_dir; ?>css/plugins/chosen/chosen.css" rel="stylesheet">
    
    <link href="<?php echo $web_btpl_dir; ?>css/animate.css" rel="stylesheet">
    <link href="<?php echo $web_btpl_dir; ?>css/style.css" rel="stylesheet">
    <?php
        if(is_file($page_dir."/css/style.css"))	{  ?>
            <link rel="stylesheet" href="<?php echo $page_dir; ?>/css/style.css">
    <?php } ?>
    <style type="text/css">
		.uppercase{ text-transform:uppercase; }
		.lowercase{ text-transform:lowercase; }
		.req:after { content: " *"; color: #ff0000; }
		.note_to_page{ cursor:pointer; }
		/*.fixed-top{ position:fixed; width:78%; }*/
	</style>
    
    <script src="<?php echo $web_btpl_dir; ?>js/jquery-2.1.1.js"></script>
    <?php //include $call->lib("mousetrap"); 
		cuser_log("user",$_SESSION['uidkey'],"Membuka Halaman ".@$page." Dari ".$user_os,"1");
	?>
    <?php include $call->lib("accounting"); ?>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> 
                        	<div class="col-md-3">
                                <div class='img-circle border-circle-white bg-white' style="width:53px;">
                                    <div class="img-circle-inner " style="width:50px; height:50px">
                                        <?php echo getuserfoto($_SESSION['uidkey']," width='100%'"); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> 
                                <strong class="font-bold"><?php echo $_SESSION['loginname']; ?></strong>
                                </span> <span class="text-muted text-xs block"><?php echo $_SESSION['levelname']; ?> <b class="caret"></b></span> </span> </a>
                                <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                    <li><a href="<?php echo $dirhost; ?>/?module=cpanel&page=profil_pengguna">Profile</a></li>
                                    <!--<li><a href="mailbox.html">Mailbox</a></li>-->
                                    <li class="divider"></li>
                                    <li><a href="<?php echo $dirhost; ?>/?module=cpanel&logout=true">Logout</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="logo-element">
                            IN+
                        </div>
                    </li>
					<?php
                        $q_main_menu_cpanel = $db->query("SELECT * 
														  FROM system_pages_client 
														  WHERE ID_PARENT = '0' AND 
														  POSITION ='top' AND 
														  STATUS='1' 
														  ORDER BY SERI");
                    
                        while($dt_main_menu_cpanel = $db->fetchNextObject($q_main_menu_cpanel)){
                            $id_parent = "";
                            $id_parent = $dt_main_menu_cpanel->ID_PAGE_CLIENT;
                            if(rightaccess($dt_main_menu_cpanel->PAGE)>0){
                            if($dt_main_menu_cpanel->IS_FOLDER == 1){
                                $url_link = "javascript:void()";	
                            }else{
                                $url_link = $dirhost."/?page=".$dt_main_menu_cpanel->PAGE;	
                            }
							@$child 	= $db->recount("SELECT ID_PAGE_CLIENT 
														FROM system_pages_client 
														WHERE ID_PARENT='".$id_parent."' AND STATUS='1'");
                        ?>
                        <li class="item">
                            <a href="<?php echo $url_link; ?>">
                                <i class="fa fa-th-large"></i> 
                                <span class="nav-label"><?php echo $dt_main_menu_cpanel->NAME; ?></span>
                                <?php if($child > 0){?><span class="fa arrow"></span><?php } ?>
                            </a>
                            <?php if($child > 0){ echo $tpl->main_menu_cpanel($id_parent,1); } ?>
                        </li>
                        <?php }
                        } 
                    ?>
                    
                    
                </ul>

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top fixed-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-sempoa-1 " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown">
                        	<?php
							$q_bell			= $db->query("SELECT * 
														  FROM ".$tpref."notifications 
														  WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND 
																ID_USER = '".$_SESSION['uidkey']."'");
							@$num_bell		= $db->numRows($q_bell);
							?>
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i> 
                                <?php if(empty($num_bell) || $num_bell == 0){ $display = "style='display:none' "; } ?>
                                <span class="label label-warning bell-count" <?php echo @$display; ?>>
									<?php echo $num_bell; ?>
                                </span>
                                
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li class="divider" style="display:none"></li>
                                <?php 
									while($dt_bell 	= $db->fetchNextObject($q_bell)){
										$type 		= $dt_bell->NOTIFICATION_TYPE;
										$src 		= $dt_bell->NOTIFICATION_SRC;
										$id_sender 	= $dt_bell->ID_SENDER;										
										$tglwkt 	= explode(" ",$dt_bell->UPDATEDATETIME);
										$tgl_note 	= $dtime->date2indodate($tglwkt[0]);
										$wkt_note 	= substr($tglwkt[1],0,5);
										$wkt 	  	= $dtime->timeDiff($dt_bell->UPDATEDATETIME);
										switch($src){
											case "USER":
												$q_user		= $db->query("SELECT ID_USER,USER_PHOTO,USER_NAME 
																				  FROM system_users_client 
																				  WHERE 
																				  ID_USER = '".$dt_bell->ID_SENDER."'");
												@$dt_user	= $db->fetchNextObject($q_user);
												@$user_foto	= $dt_user->USER_PHOTO;
												if(!empty($user_foto) && 
												 	is_file($basepath."/files/images/users/".$user_foto)) {
														$user_foto = "users/".$user_foto;  			}
												else{	$user_foto = "noimage-m.jpg";  							}
												@$user_foto = "<img src='".$dirhost."/files/images/".$user_foto."' 
																	class='img-circle' 
																	width='100%'>"; 								
												@$user_name = $dt_user->USER_NAME;
											break;
											case "CUSTOMER":
												$q_user		= $db->query("SELECT 
																		  ID_CUSTOMER,CUSTOMER_PHOTO,CUSTOMER_NAME 
																		  FROM ".$tpref."customers 
																		  WHERE 
																		  ID_CUSTOMER = '".@$dt_bell->ID_SENDER."'"); 
																			  
												@$dt_user	= $db->fetchNextObject($q_user);								
												@$user_foto	= $dt_user->CUSTOMER_PHOTO;
												if(!empty($user_foto) && 
													is_file($basepath."/files/images/members/".$user_foto)) {
														@$user_foto = "members/".$user_foto;  			}
												else{	@$user_foto = "noimage-m.jpg";  							}
												@$user_foto = "<img src='".$dirhost."/files/images/".$user_foto."' 
																	class='img-circle' 
																	width='100%'>"; 								
												@$user_name	= $dt_user->CUSTOMER_NAME;
											break;	
										}
								?>
                                        <li id="notif_<?php echo $type; ?>_<?php echo $id_sender; ?>"
                                        	class="note_to_page"
                                            data-url="<?php echo $dirhost; ?>/?page=chat_pelanggan&id_customer=<?php echo $id_sender; ?>">
                                            <div class="dropdown-messages-box">
                                                <a href="#" class="pull-left">
                                                    <?php echo @$user_foto; ?>
                                                </a>
                                                <div class="media-body">
                                                    <small class="pull-right"><?php echo $wkt; ?></small>
                                                    <?php echo $dt_bell->NOTIFICATION_CONTENT;?><br>
                                                    <small class="text-muted">
                                                        <?php echo $tgl_note."  ".$wkt_note; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="divider" id="divider_notif_<?php echo $type; ?>_<?php echo $id_sender; ?>"></li>
                                <?php } ?>
                                <!--<li>
                                    <div class="text-center link-block">
                                        <a href="<?php echo $dirhost; ?>/?page=notification">
                                            <i class="fa fa-envelope"></i> <strong>Baca Semua Notifikasi</strong>
                                        </a>
                                    </div>
                                </li>-->
                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo $dirhost; ?>/?module=cpanel&logout=true">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                        <li>
                            <a class="right-sidebar-toggle">
                                <i class="fa fa-tasks"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2><?php echo $page_title; ?></h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?php echo $dirhost; ?>">Home</a>
                        </li>
                        <li class="active">
                            <strong><a href="<?php echo $lparam; ?>"><?php echo $page_title; ?></a></strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2"></div>
            </div>
			
            <!-- main content -->
                <?php
                    @$user_foto = $db->fob("USER_PHOTO","system_users_client"," 
											WHERE ID_USER = '".$_SESSION['uidkey']."'"); 
                    if(empty($user_foto)){
                        $user_foto = $dirhost."/files/users/no_image.jpg";
                    }else{
                        $user_foto = $dirhost."/files/images/users/".$user_foto;	
                    }
                ?>                
                 <input type='hidden' id='config' value='"id_client":"<?php echo $id_client; ?>","cidkey":"<?php echo $_SESSION['cidkey']; ?>","uidkey":"<?php echo $_SESSION['uidkey']; ?>","dirhost":"<?php echo $dirhost; ?>","page":"<?php echo $page; ?>","id_page":"<?php echo $id_page; ?>","realtime":"<?php echo @$realtime; ?>"'/>
                 
                
                <input type='hidden' id='user_info' value='"user_photo":"<?php echo $user_foto; ?>","user_name":"<?php echo $_SESSION['loginname']; ?>","wkt_chat":"<?php echo substr($wktupdate,0,5); ?>","tgl_chat":"<?php echo $dtime->date2indodate($tglupdate); ?>"'/>                
                
                <?php if($realtime == 1){?>
                <script type="text/javascript" charset="utf-8" src="<?php echo $websock_conn; ?>/msg/client.js"></script>
                <script language="javascript">
                    var tulcom = new Faye.Client('<?php echo $websock_conn; ?>/msg');
					tulcom.subscribe("/note_bell", function(datas) {
						var conf 	= JSON.parse("{"+$("#config").val()+"}");
						id_page		= datas.id_page;
						id_merchant	= datas.id_merchant;
						type		= datas.notif_type;
						if(conf.id_client == id_merchant && conf.id_page != id_page){
							$.ajax({
								url 	: "<?php echo $backend_dir; ?>/pages/notifications/ajax/data.php",
								type 	: "POST",
								data 	: datas,
								success : function(response){
									bell_count 		= $(".bell-count").html();
									new_bell_count 	= +bell_count + 1;
									$(".bell-count").show().html(new_bell_count);
									ch_note = $("#notif_"+type+"_"+datas.id_customer).length;
									if(ch_note > 0){ 
										$("#notif_"+type+"_"+datas.id_customer).remove();
										$("#divider_notif_"+type+"_"+datas.id_customer).remove(); 
									}
									$(".dropdown-messages li.divider:last").after(response);
									chat_ring("open-ended");
								}
							})
						}
					});
                		
                </script>
                <?php } ?>
                
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
					<?php if(!empty($page)){ include $call->inc("zendback/pages","index.php"); } ?>
                </div>  
            </div>


        </div>
    </div>
	
    <div id="modal-ajax" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content animated flipInY">
                <div class="modal-body no-padding-lr no-padding-tb"></div>
            </div>
        </div>
    </div>
    
    
    
    <!-- Mainly scripts -->
    <script src="<?php echo $web_btpl_dir; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo $web_btpl_dir; ?>js/plugins/bootbox/bootbox.js"></script>
    <script src="<?php echo $web_btpl_dir; ?>js/plugins/metisMenu/jquery.metisMenu.js"></script>

</body>

<!-- Site: HackForums.Ru | E-mail: abuse@hackforums.ru | Skype: h2osancho -->
</html>
