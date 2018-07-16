<?php defined('mainload') or die('Restricted Access'); ?>
<ul id="browser" class="filetree">
    <?php
    while($dt_merchant_parent = $db->fetchNextObject($q_merchant_parent)){ 
	?>
        <li  id="li_<?php echo $dt_merchant_parent->ID_CLIENT; ?>" class="expandable">
            <div class='link-name pull-left'>
            	<p class="folder">
                    <a href="javascript:void(0);" onclick="getparent('<?php echo $dt_merchant_parent->ID_CLIENT; ?>','divparent_id');" title="<?php echo $dt_merchant_parent->ID_CLIENT; ?>">
                        <?php echo $dt_merchant_parent->CLIENT_NAME; ?>
                    </a>
                </p>
            </div>
            <div class='link-btn pull-right'>
            	<div class="btn-group">
                    <button type="button" class='btn btn-sm btn-warning fancybox fancybox.ajax' title="View">
                        <i class="fa fa-eye"></i>
                    </button>
                    <button type="button" onclick="delete_link('<?php echo $dt_merchant_parent->ID_CLIENT; ?>');" class="btn btn-sm btn-info" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-sempoa-3">
                    	<a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_merchant_parent->ID_CLIENT; ?>"  title="Edit" style="color:#FFF">
                        	<i class="fa fa-pencil-square-o"></i>
                        </a>
                    </button>
                    
            	</div>
            </div>
            <div class="clearfix"></div>
            <?php echo merchant_child($dt_merchant_parent->ID_CLIENT); ?>
        </li>
    <?php } ?>	
</ul>
