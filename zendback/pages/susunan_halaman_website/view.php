<?php defined('mainload') or die('Restricted Access'); ?>
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <form name="form1" method="post" action="" enctype="multipart/form-data">
           <div  class="form-group col-md-6">
            <label>Menu Induk Website</label>
            <select name="root_id" id="root_id" class='form-control select-box'>
                  <option value=''>--LINK ROOT--</option>
                  <?php
                  $qmod = $db->query("SELECT * FROM system_pages_discoin WHERE ID_PARENT = '0'");
                  while($dtmod = $db->fetchNextObject($qmod)){
                  ?>
                    <option value='<?php echo $dtmod->ID_PAGE_DISCOIN; ?>' <?php if(!empty($root_id) && $dtmod->ID_PAGE_DISCOIN == $root_id){ ?> selected <?php } ?>>
                    <?php echo $dtmod->NAME; ?>
                    </option>
                  <?PHP } ?>
             </select>    
          </div>
          <div  class="form-group col-md-6">
          	<label>&nbsp;</label><br />
            <button type="submit" name="direction" value="show" class='btn btn-sempoa-1'/>
            	<i class="fa fa-eye"></i> Lihat Halaman
            </button>
            <input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/save.php' />
          </div>
        </form>
        <div class="clearfix"></div>
    </div>
</div>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Menu Anak Website</h5>
    </div>
    <div class="ibox-content">
		<?php
        if(!empty($direction)){
            $cond = "";
            if(!empty($root_id)){ 	$cond = " AND ID_PARENT='".$root_id."'"; }else{ $cond = " AND ID_PARENT='0'"; }
        ?>
        <?php
            if(!empty($root_id)){
                echo msg("Daftar Halaman Yang Sudah Terdaftar Dari Link Induk 
                <strong><u>".strtoupper($db->fob("NAME","system_pages_client","WHERE ID_PAGE_CLIENT='".$root_id."'"))."</u></strong>","note");
            }
        ?>
        <table width='100%'>
            <tr>
                <td>
                    <div id="contentLeft">
                        <ul>
							<?php
                            $r = 0;
                            $query 		= "SELECT * FROM system_pages_discoin WHERE ID_PAGE_DISCOIN != '' ".$cond." ORDER BY SERI ASC";
                            $qlink 		= $db->query($query);
                            while($dt = $db->fetchNextObject($qlink)){
                            $r++;
                            ?>
                            <li id="recordsArray_<?php echo $dt->ID_PAGE_DISCOIN; ?>">
                                <div  class='page_list'>
                                    <b>&nbsp;
                                    <a href="javascript:void(0);">
                                    	<i class="fa fa-thumb-tack"></i> <?php echo $dt->NAME; ?>
                                    </a>
                                    </b>
                                    <?php echo lchild($dt->ID_PAGE_DISCOIN); ?>
                                </div>
                            </li>
                            <?php }  ?>
                        </ul>
                    </div>
                </td>
            </tr>
        </table>
        <?php 	} ?>
    </div>
</div>
