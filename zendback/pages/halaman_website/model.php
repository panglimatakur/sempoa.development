<?php defined('mainload') or die('Restricted Access'); ?>
<?php
function lchild($parent){
	global $db;
	global $lparam;
	global $ajax_dir;
	
	$qlink 	= $db->query("SELECT * FROM system_pages_discoin WHERE ID_PARENT='".$parent."' ORDER BY SERI ASC");
	$jml 	= $db->numRows($qlink);
	if($jml >0){
?>
	<ul>
<?php
		while($dt = $db->fetchNextObject($qlink)){
		if($dt->IS_FOLDER == 1){ $class = "folder"; }else{ $class = "file"; }	
		?>
            <li <?php echo @$style; ?> id="li_<?php echo $dt->ID_PAGE_DISCOIN; ?>" class="expandable">
                <div class='link-name pull-left'>
                    <p class=" <?php echo $class; ?>">
                        <a href="javascript:void(0);" onclick="getparent('<?php echo $dt->ID_PAGE_DISCOIN; ?>','divparent_id');" title="<?php echo $dt->ID_PAGE_DISCOIN; ?>"><?php echo $dt->NAME; ?>
                        </a>
                    </p>
                </div>		
                <div class='link-btn pull-right'>
                    <div class="btn-group">
                        <button type="button" onclick="delete_link('<?php echo $dt->ID_PAGE_DISCOIN; ?>');" class="btn btn-sm btn-info" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                       <button type="button" class="btn btn-sm btn-sempoa-3">
                            <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt->ID_PAGE_DISCOIN; ?>"  title="Edit" style="color:#FFF">
                            <i class="fa fa-pencil-square-o"></i>
                            </a>
                       </button>
                    </div>            
                 </div>
                 <div class="clearfix"></div>
	  			<?php echo lchild($dt->ID_PAGE_DISCOIN); ?>
            </li>
		<?php
		}
?>
	</ul>
<?php
	}
}
?>

<?php
	if(!empty($direction) && $direction == "edit"){
		$qcont				=	$db->query("SELECT * FROM ".$tblnya." WHERE ID_PAGE_DISCOIN='".$no."' ");
		$dtedit				=	$db->fetchNextObject($qcont);
		$parent_id			=	$dtedit->ID_PARENT;
		$nama				=	$dtedit->NAME;
		$permalink  		= 	$dtedit->PAGE; 
		$idhalaman  		= 	$dtedit->ID_PAGE;
		$judul 				= 	$dtedit->TITLE;
		$keywords 				= 	$dtedit->KEYWORDS;
		$description 				= 	$dtedit->DESCRIPTIONS;
		$isi 				= 	$dtedit->CONTENT;
		$posisi				= 	$dtedit->POSITION;
		$depth				= 	$dtedit->DEPTH;
		$contenttype		= 	$dtedit->TYPE;
		$depth				= 	$dtedit->DEPTH;
		$is_folder			= 	$dtedit->IS_FOLDER;
		$status				=	$dtedit->STATUS;
		$icon				=	$dtedit->ICON;
	}
	if(!empty($parent_id)){
		@$nama_parent		=	$db->fob("NAME",$tblnya,"WHERE ID_PAGE_DISCOIN='".$parent_id."'");
	}
?>
