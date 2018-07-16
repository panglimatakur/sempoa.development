<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
.pframe{
	-webkit-box-shadow: 0 1px 2px #999;
	-moz-box-shadow: 0 1px 2px #999;
	-ms-box-shadow: 0 1px 2px #999;
	-o-box-shadow: 0 1px 2px #999;
	box-shadow: 0 1px 2px #999;
	padding:4px; 
	margin-right:2px; 
	float:left; 
	text-align:center;
	background:#FFF;
}
.pframe label .code{
	font-size:2vmin;
}
.merchant_list{
	background:url("<?php echo $dirhost; ?>/zendfront/templates/default/images/section-shadow.png") no-repeat bottom;
}	
</style>
    <section id="page-breadcrumb">
        <div class="vertical-center sun">
             <div class="container">
                <div class="row">
                    <div class="action">
                        <div class="col-sm-12">
                            <h1 class="title">Daftar Komunitas Bisnis </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </section>
<section id="blog" >
    <div class="container">
        <div class="row">
		
	<?php 
	while($dt_community	= $db->fetchNextObject($q_community)){
		@$nm_komunitas 	= $dt_community->NAME;
		@$id_community	= $dt_community->ID_COMMUNITY;
		$str_merchant 	="SELECT 
							a.ID_CLIENT,b.* 
						  FROM 
							".$tpref."communities_merchants a,
							".$tpref."clients b
						  WHERE 
							a.ID_COMMUNITY='".$id_community."' AND
							a.ID_CLIENT = b.ID_CLIENT AND
							b.ACTIVATE_STATUS = '3'";
						if(empty($parameters)){
							$class	= "col-md-4";	
							$style	= "height:600px;";
						}else{
							$class	= "col-md-12";
						}
		$q_merchant 	= $db->query($str_merchant);
	?>
    	
        
            <div class="<?php echo @$class; ?>" 
            	 style="margin-bottom:10px;padding:0; ">
                <ul class="list-group col-md-12">
                    <li class="list-group-item list-group-item-default disabled">
                        <b>
                        	<a href="<?php echo $dirhost; ?>/website/komunitas/<?php echo $id_community; ?>">
                        		Komunitas <?php echo $nm_komunitas; ?>
                            </a>
                        </b>
                    </li>
                    <li class="list-group-item list-merchant-community" style="height:600px;">
                    <?php
                        $t = 0;			
                        while($dt_merchant		= $db->fetchNextObject($q_merchant)){
                            $t++;
                            $bg		= "";
                            @$id_parent 		= $dt_merchant->CLIENT_ID_PARENT;
                            if(empty($id_parent)){ $id_parent = $dt_merchant->ID_CLIENT; }
                            @$merchant_app 		= $dt_merchant->CLIENT_APP;
                            $statement 			= "";
                        
                        
							@$q_discount_2 		= $db->query("SELECT * 
														  FROM ".$tpref."clients_discounts 
														  WHERE 
																ID_CLIENT = '".$id_client."' AND 
																DISCOUNT_SEGMENT = 'community' AND
																DISCOUNT_STATUS = '3'");
							@$num_discount	= $db->numRows($q_discount_2);
							
							if(!empty($parameters)){
								$class = "col-md-6 ";
								$style = "height: 220px;";
								$img_h = "height: 90px;";
								$desc_height = "";
							}else{
								$class = "col-md-12";
								$style = "padding:12px; ";
								$img_h = "height: 50px;";
							}
							@$logo = getclientlogo($dt_merchant->ID_CLIENT," style='width:100%'");
                    ?>
                            <div class="<?php echo $class; ?>  merchant_list" style=" <?php echo @$style; ?>" >
                                <div class="col-md-2" style="text-align:center;padding:0">
                                	<div class="img-thumbnail" style=" <?php echo $img_h; ?> ">
                                    	<div class="potrait">
                                    		<?php echo @$logo; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10" style="padding-right:0;font-size:12px;">
                                    <span class="text-danger"><b>ACTIVE</b></span><br />
                                    <span class='text-info'>
										<b><?php echo @$dt_merchant->CLIENT_NAME; ?></b>
                                    </span><br />
                                    
                                    <b>Diskon Komunitas</b><br />
                                    
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
                                            <a href="javascript:void()" class='text-info'>
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
                                    
                                    <br />
                                    <div class="clearfix"></div>
                                    <a href='<?php echo $dirhost; ?>/<?php echo @$dt_merchant->CLIENT_APP; ?>.coin' target="_blank" class="btn btn-sm btn-warning"><i class="fa fa-home"></i> Kunjungi Toko Online</a>
                                </div>
                                <br clear="all" />
                                <br clear="all" />
                            </div>
                     <?php 
                        }
                    ?>
                    </li>
                </ul>
            </div>
            
        
	<?php } ?>
    
    
    	</div>
    </div>
</section>