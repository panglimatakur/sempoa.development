<?php defined('mainload') or die('Restricted Access'); ?>
<?php
function lchild($parent){
	global $db;
	global $lparam;
	global $tpref;
	global $id_root;
	global $ajax_dir;
	
	$qlink 	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_PARENT='".$parent."' AND (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') ORDER BY IS_FOLDER DESC,ID_CASH_TYPE ASC");
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
		$link_function 	= "";
		$class			= "";
		?>
            <li id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
                <div id='link_list'>
                    <p class='link1' style="float:left">
                        <a href="javascript:void(0);"  class="folder">
                            [<?php echo $dt->ID_CASH_TYPE; ?>] <?php echo $dt->NAME; ?>
                        </a>
                    </p>
                	<?php if(allow('insert') == 1 && $dt->ID_CLIENT != 0){?>
                            <p class='buttons1' style="float:right">
                                <a href="javascript:void(0);" onclick="input_value('<?php echo $dt->ID_CASH_TYPE; ?>','<?php echo @$id_root; ?>');" class="btn btn-mini" title="Tambah Nilai Transaksi">
                                   <i class="icsw16-money"></i>
                                </a>
                            </p>
                    <?php } ?>
                </div>		
                <div style="height:0; clear:both"></div>  
	  			<?php echo lchild($dt->ID_CASH_TYPE); ?>
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
    $qlink 	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') AND ID_PARENT='0' ORDER BY IS_FOLDER DESC,ID_CASH_TYPE ASC");
    while($dt = $db->fetchNextObject($qlink)){
    $t++;
	$mox = $t%2;
	if($mox == 1){ $style = "style='background:#F5F5F5'"; }else{ $style = ""; }
	$close 				= ""; 
	$link_function 		= "";
	if($dt->IS_FOLDER == 1){ 
		$link_function 	= "onclick=\"input_master('".$dt->ID_CASH_TYPE."','".$id_root."');\"";
	}else{
		$close 			= "style='display:none'";
		$id_root		= $dt->ID_CASH_TYPE;
	}
	?>
        <li id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
            <div id='link_list'>
                <p class='link1' style="float:left">
                <a href="javascript:void(0);" onclick="input_master('<?php echo $dt->ID_CASH_TYPE; ?>','<?php echo @$id_root; ?>');" class="folder">
                    [<?php echo $dt->ID_CASH_TYPE; ?>] <?php echo $dt->NAME; ?>
                </a>
                </p>
                <?php if(allow('insert') == 1 && $dt->IS_FOLDER == 1){?>
                <p class='buttons1' style="float:right; ">
                    <a href="javascript:void(0);" onclick="input_value('<?php echo $dt->ID_CASH_TYPE; ?>','<?php echo @$id_root; ?>');" class="btn btn-mini" title="Tambah Nilai Transaksi" <?php echo @$close; ?>>
                       <i class="icsw16-money"></i>
                    </a>
                </p>
                <?php } ?>
             </div>
             <div style="height:0; clear:both"></div>
            <?php echo lchild($dt->ID_CASH_TYPE); ?>
        </li>
    <?php } ?>	
        <br clear="all" />
</ul>
