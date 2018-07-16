<?php defined('mainload') or die('Restricted Access'); ?>
<ul id="browser" class="filetree">
    <?php
    $qlink 	= $db->query("SELECT * FROM ".$tpref."products_categories WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_PARENT='0' ORDER BY SERI ASC");
    while($dt = $db->fetchNextObject($qlink)){
	?>
        <li id="li_<?php echo $dt->ID_PRODUCT_CATEGORY; ?>">
            <div class='link-name pull-left' >
                <p class="folder">
                    <a href="javascript:void(0);" onclick="getparent('<?php echo $dt->ID_PRODUCT_CATEGORY; ?>','divparent_id');" title="<?php echo $dt->ID_PRODUCT_CATEGORY; ?>">
                        &nbsp; <?php echo $dt->NAME; ?>
                    </a>
                </p>
             </div>
            <div class='link-btn pull-right'>
                <div class="btn-group" >
                <?php if(allow('delete') == 1){?> 
                <button type="button" onclick="delete_link('<?php echo $dt->ID_PRODUCT_CATEGORY; ?>');" class="btn btn-sm btn-info" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
                <?php } ?>
                <?php if(allow('edit') == 1){?> 
                <button type="button" class="btn btn-sm btn-sempoa-3">
                    <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt->ID_PRODUCT_CATEGORY; ?>"title="Edit" style="color:#FFF">
                       <i class="fa fa-pencil-square-o"></i>
                    </a>
                </button>
                <?php } ?>
                </div>
            </div>		
            <br clear="all" />
            <?php echo lchild($dt->ID_PRODUCT_CATEGORY); ?>
        </li>
    <?php } ?>	
        <br clear="all" />
</ul>
