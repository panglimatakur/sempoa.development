<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(!empty($_REQUEST['root_id']))			{ $root_id 	= $sanitize->str($_REQUEST['root_id']); 	}
function lchild($parent){
	?>
    <ul>
    <?php
	global $tpref;
	global $db;
	global $lparam;
	global $ajax_dir;

	$qlink 	= $db->query("SELECT * FROM system_pages_discoin WHERE ID_PARENT='".$parent."' ORDER BY SERI ASC");
	$jml 	= $db->numRows($qlink);
	if($jml >0){	
		while($dt = $db->fetchNextObject($qlink)){
		?>
        <li id="recordsArray_<?php echo $dt->ID_PAGE_DISCOIN; ?>">
            <div  class='page_list' >
            <b>&nbsp;<a href="javascript:void(0);">&raquo; <?php echo $dt->NAME; ?></a></b>
            <br />
            <?php echo lchild($dt->ID_PAGE_DISCOIN); ?>
            </div>
        </li>
		<?php
		}
	}
	?>
    </ul>
    <?php
}
?>

