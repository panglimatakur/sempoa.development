<?php defined('mainload') or die('Restricted Access'); ?>
<?php
function lchild($parent){
	global $tpref;
	global $db;
	global $lparam;
	global $ajax_dir;
	
	$qlink 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='".$parent."' ORDER BY CLIENT_NAME ASC");
	$jml 	= $db->numRows($qlink);
	if($jml >0){
?>
        <ul>
<?php
		$t = 0;
		while($dt = $db->fetchNextObject($qlink)){
		$t++;
		$mox = $t%2;
		if($mox == 1){ $style = "style='background:#F5F5F5'"; }else{ $style = ""; }
		?>
            <li <?php echo $style; ?> id="li_<?php echo $dt->ID_CLIENT; ?>">
                <div id='link_list'>
                    <p class='link1' style="float:left">
                        <a href="javascript:void(0);" onclick="getparent('<?php echo $dt->ID_CLIENT; ?>','divparent_id');">
                            <?php echo $dt->CLIENT_NAME; ?>
                        </a>
                    </p>
                </div>		
                <br clear="all" />  
	  			<?php echo lchild($dt->ID_CLIENT); ?>
            </li>
		<?php
		}
?>
	</ul>
<?php
	}
}
?>
<ul id="browser" class="filetree">
    <?php
	$t = 0;
    while($dt = $db->fetchNextObject($qlink)){
    $t++;
	$mox = $t%2;
	if($mox == 1){ $style = "style='background:#F5F5F5'"; }else{ $style = ""; }
	?>
        <li <?php echo $style; ?> id="li_<?php echo $dt->ID_CLIENT; ?>">
            <div id='link_list'>
                <p class='link1' style="float:left">
                <a href="javascript:void(0);" onclick="getparent('<?php echo $dt->ID_CLIENT; ?>','divparent_id');">
                    <?php echo $dt->CLIENT_NAME; ?>
                </a>
                <br />
                <button onclick="send_starterpack('<?php echo $dt->ID_CLIENT; ?>')" type="button" class="btn btn-beoro-2" id="start_<?php echo $dt->ID_CLIENT; ?>">
                	Kirim StarterPack
                </button>
                </p>
             </div>
             <br clear="all" />
            <?php echo lchild($dt->ID_CLIENT); ?>
        </li>
    <?php } ?>	
        <br clear="all" />
</ul>
