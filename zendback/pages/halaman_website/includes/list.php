<?php defined('mainload') or die('Restricted Access'); ?>
<ul id="browser" class="filetree">
    <?php
    $qlink 		= $db->query("SELECT * FROM system_pages_discoin WHERE ID_PAGE_DISCOIN IS NOT NULL AND ID_PARENT='0' ORDER BY SERI ASC");
    while($dt 	= $db->fetchNextObject($qlink)){
	if($dt->IS_FOLDER == 1){ $class = "folder"; }else{ $class = "file"; }	
	?>
        <li id="li_<?php echo $dt->ID_PAGE_DISCOIN; ?>" class="expandable">
            <div class='link-name pull-left' >
                <p class=" <?php echo $class; ?>">
                    <a href="javascript:void(0);" onclick="getparent('<?php echo $dt->ID_PAGE_DISCOIN; ?>','divparent_id');" title="<?php echo $dt->ID_PAGE_DISCOIN; ?>">
                        <?php echo $dt->NAME; ?>
                    </a>
                </p>
            </div>
            <div class='link-btn pull-right'>
            	<div class="btn-group" >
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
    <?php } ?>	
</ul>
