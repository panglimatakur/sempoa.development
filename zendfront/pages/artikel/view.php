<?php defined('mainload') or die('Restricted Access'); ?>
<div class="container-fluid" style="margin-top:15px;">
	<div class="col-md-7">
	<?php while($dt_statis		= $db->fetchNextObject($q_statis)){
		$nm_user = $db->fob("USER_NAME","system_users_client"," WHERE ID_USER = '".$dt_statis->ID_USER."'");	
	?>
	<section style="text-align:justify; ">
		
        <a href="<?php echo $dirhost; ?>/website/artikel/<?php echo $dt_statis->ID_POST; ?>">
            <h2><?php echo @$dt_statis->POST_TITLE; ?></h2>
        </a>
		
		<div style="margin-bottom:3px;">
			<i class="glyphicon glyphicon-calendar"></i> 
			<?php echo $dtime->now2indodate2(@$dt_statis->TGLUPDATE); ?> , 
			By : <?php echo $nm_user; ?> 
		</div>
		<?php if(is_file($basepath."/files/images/".$dt_statis->POST_COVER)){?>
			<div class="imagedropshadow col-md-3" style="float: left; margin-right:6px">
				<div class="imagedropshadow_inn">
					<img src="<?php echo $dirhost?>/files/images/<?php echo $dt_statis->POST_COVER; ?>" style="width:99%" />
				</div>
			</div>
		<?php } ?>
		<?php if(!empty($parameters)){ ?> 
			<?php echo @$dt_statis->POST_CONTENT; ?>
		<?php }else{ ?>
			<?php echo printtext(@$dt_statis->POST_CONTENT,300); ?>
			<a href="<?php echo $dirhost; ?>/website/artikel/<?php echo $dt_statis->ID_POST; ?>">
				...Baca Selanjutnya
			</a>
		<?php } ?>
		<br class="cl">
        <br class="cl">
	</section>
	<?php } ?>
	</div>
    
	<div class="col-md-4" style="margin-left:0; padding-left:0">
		<h3>Artikel</h3>
		<ul>
		<?php while($dt_title = $db->fetchNextObject($q_title)){?>
			<li style="font-size:11px;">
				<a href="<?php echo $dirhost; ?>/website/artikel/<?php echo $dt_title->ID_POST; ?>">
					<?php echo $dt_title->POST_TITLE; ?>
				</a><br />
				<i class="glyphicon glyphicon-calendar"></i>
				<?php echo $dtime->now2indodate2(@$dt_title->TGLUPDATE); ?>
			</li>
		<?php } ?>
		</ul>
	</div>
	<div class="cl">&nbsp;</div>
</div>
