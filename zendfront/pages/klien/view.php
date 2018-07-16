<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
.thumbnail{
	height:250px;	
}
.thumbnail img{ width:100%; }
</style>
<section id="page-breadcrumb">
    <div class="vertical-center sun">
         <div class="container">
            <div class="row">
                <div class="action">
                    <div class="col-sm-12">
                        <h1 class="title">Daftar Merchant </h1>
                        <p>Daftar Merchant Anggota Sempoa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="portfolio">
    <div class="container">
        <div class="row">
        	<form method="post" action="">
            <div class="form-group">
            	<div class="col-md-12"><label>Filter Pencarian</label></div>
                <div class="col-md-3">
                	<input type="text" name="search" class="form-control" value="<?php echo $search; ?>" placeholder="Kata Kunci"/>
                </div>
                <div class="col-md-3">
                    <select name="propinsi" id="propinsi" class="form-control">
                        <option value=''>--PILIH PROPINSI--</option>
                        <?php 
                        while($dt_propinsi = $db->fetchNextObject($q_propinsi)){
                        ?>
                            <option value='<?php echo $dt_propinsi->ID_LOCATION; ?>' <?php if(!empty($propinsi) && $propinsi == $dt_propinsi->ID_LOCATION){?> selected<?php } ?>><?php echo $dt_propinsi->NAME; ?>
                            </option>
                    <?php } ?>
                    </select>
                    <input type="hidden" id="data_page" value="<?php echo $dirhost; ?>/<?php echo $page_dir; ?>/ajax/data.php"/>
                </div>
                <span id="div_kota">
                	<?php
					if((!empty($direction) && $direction == "search") && !empty($propinsi)){
						include $call->inc($page_dir."/ajax","data.php");
					}
					?>
                </span>
                <div class="col-md-3">
                	<button type="submit" name="direction" value="search" class="btn btn-danger" style="padding:10px;"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
            </form>
            
            <div class="clearfix"></div>
            <div class="portfolio-items" style="margin-top:20px;">

                <?php while($dt_merchants = $db->fetchNextObject($q_merchants)){?>
                <div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
                	<div class="thumbnail">
                    	<div style="height:240px; overflow:hidden">
                    		<a href="<?php echo $dirhost; ?>/<?php echo $dt_merchants->CLIENT_APP; ?>.coin" target="_blank">
                        <?php if(is_file($basepath."/files/images/logos/".$dt_merchants->CLIENT_LOGO)){?>
                            <img class="" 
                            alt="<?php echo $dt_merchants->CLIENT_NAME; ?>" 
                            src="<?php echo $dirhost; ?>/files/images/logos/<?php echo $dt_merchants->CLIENT_LOGO; ?>" />
                        <?php }else{ ?>
                            <img class="" 
                            alt="<?php echo $dt_merchants->CLIENT_NAME; ?>" 
                            src="<?php echo $dirhost; ?>/files/images/no_image.jpg" />
                        <?php } ?>
                    		</a>
                    	</div>
                    </div>
                </div> <!-- col-6 / end -->            
                <?php } ?>
                
            </div>
        </div>
        
    </div> <!-- list-group / end -->
</section> <!-- row / end -->
