<?php defined('mainload') or die('Restricted Access'); ?>
<?php
function lchild($parent){
	global $db;
	global $lparam;
	global $id_root;
	global $ajax_dir;
	global $tpref;
	
	$qlink 	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_PARENT='".$parent."' AND (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') ORDER BY IS_FOLDER DESC,ID_CASH_TYPE ASC");
	$jml 	= $db->numRows($qlink);
	if($jml >0){
?>
        <ul>
<?php
		$t = 0;
		while($dt = $db->fetchNextObject($qlink)){
		$t++;
		$noinsert		= "false";
		$noedit 		= "false"; 
		$nodelete 		= "false";

		$no_insert_code = array(1=>"69","70","77","78");
		$allow_insert 	= array_search($dt->ID_CASH_TYPE,$no_insert_code);
		if($allow_insert > 0){ $noinsert =  "true"; }

		if($dt->ID_CLIENT == "0"){ $noedit 		= "true"; $nodelete 		= "true";	}		
		?>
            <li id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
                <div id='link_list'>
                    <p class='link1' style="float:left">
                        <a href="javascript:void(0);" class="folder" id="name_<?php echo $dt->ID_CASH_TYPE; ?>">
                            [<?php echo $dt->ID_CASH_TYPE; ?>] <?php echo $dt->NAME; ?>
                        </a>
                    </p>
                    <p class='buttons1' style="float:right">
                    <?php if(allow('insert') == 1 && $noinsert == "false"){?> 
                    <a href="javascript:void(0);" onclick="input_master('<?php echo $dt->ID_CASH_TYPE; ?>','<?php echo $id_root; ?>');" class="btn btn-mini" title="Tambah Jenis Transaksi">
                       <i class="icon-plus"></i>
                    </a>
                    <?php } ?>
                    <?php if(allow('edit') == 1 && $noedit == "false"){?> 
                    <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt->ID_CASH_TYPE; ?>" class="btn btn-mini" title="Perbaiki Jenis Transaksi">
                       <i class="icon-pencil"></i>
                    </a>
                    <?php } ?>
                    <?php if(allow('delete') == 1 && $nodelete == "false"){?> 
                    <a href='javascript:void()' onclick="delete_link('<?php echo $dt->ID_CASH_TYPE; ?>');" class="btn btn-mini" title="Hapus Jenis Transaksi">
                        <i class="icon-trash"></i>
                    </a>
                    <?php } ?>
                    </p>
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
	?>
        <li id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
            <div id='link_list'>
                <p class='link1' style="float:left">
                <a href="javascript:void(0);" class="folder" id="name_<?php echo $dt->ID_CASH_TYPE; ?>">
                    [<?php echo $dt->ID_CASH_TYPE; ?>] <?php echo $dt->NAME; ?>
                </a>
                </p>
                <p class='buttons1' style="float:right; ">
                    <?php if(allow('insert') == 1){?> 
                    <a href="javascript:void(0);" onclick="input_master('<?php echo $dt->ID_CASH_TYPE; ?>','<?php echo $id_root; ?>');" class="btn btn-mini" title="Tambah Jenis Transaksi">
                       <i class="icon-plus"></i>
                    </a>
                    <?php } ?>
                    <?php if(allow('edit') == 1){?> 
                    <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt->ID_CASH_TYPE; ?>" class="btn btn-mini" title="Perbaiki Jenis Transaksi" <?php echo @$close; ?>>
                       <i class="icon-pencil"></i>
                    </a>
                    <?php } ?>
                    <?php if(allow('delete') == 1){?> 
                    <a href='javascript:void()' onclick="delete_link('<?php echo $dt->ID_CASH_TYPE; ?>');" class="btn btn-mini" title="Hapus Jenis Transaksi" <?php echo @$close; ?>>
                        <i class="icon-trash"></i>
                    </a>
                    <?php } ?>
                </p>
             </div>
             <div style="height:0; clear:both"></div>
            <?php echo lchild($dt->ID_CASH_TYPE); ?>
        </li>
    <?php } ?>	
        <br clear="all" />
</ul>
