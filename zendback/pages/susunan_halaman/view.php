<?php defined('mainload') or die('Restricted Access'); ?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
            <label>Menu Induk Cpanel</label>
    </div>
    <div class="ibox-content no-padding-lr">
        <form name="form1" method="post" action="" enctype="multipart/form-data">
           <div  class="form-group col-md-4">
            <select name="root_id" id="root_id"  class='form-control select-box'>
                  <option value=''>--LINK ROOT--</option>
                  <?php
                  $qmod = $db->query("SELECT * FROM system_pages_client WHERE ID_PARENT = '0'");
                  while($dtmod = $db->fetchNextObject($qmod)){
                  ?>
                    <option value='<?php echo $dtmod->ID_PAGE_CLIENT; ?>' <?php if(!empty($root_id) && $dtmod->ID_PAGE_CLIENT == $root_id){ ?> selected <?php } ?>>
                    <?php echo $dtmod->NAME; ?>
                    </option>
                  <?PHP } ?>
             </select>    
          </div>
          <div  class="form-group col-md-8">
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
        <h5>Menu Anak Cpanel</h5>
    </div>
    <div class="ibox-content">
    
    <?php
    if(!empty($direction)){
        $cond = "";
        if(!empty($root_id)){ 	$cond = " AND ID_PARENT='".$root_id."'"; }else{ $cond = " AND ID_PARENT='0'"; }
    ?>
    <br />
    <?php
        if(!empty($root_id)){
            echo msg("Daftar Halaman Yang Sudah Terdaftar Dari Link Induk 
            <strong><u>".strtoupper($db->fob("NAME","system_pages_client","WHERE ID_PAGE_CLIENT='".$root_id."'"))."</u></strong>","info");
        }
    ?>
        <table width='100%'>
            <tr>
                <td>
                <div id="contentLeft">
                    <ul>
                        <?php
                        $r = 0;
                        $query 		= "SELECT * FROM system_pages_client WHERE ID_PAGE_CLIENT != '' ".$cond." ORDER BY SERI ASC";				
                        $qlink 		= $db->query($query);
                        while($dt = $db->fetchNextObject($qlink)){
                        $r++;
                        ?>
                        <li id="recordsArray_<?php echo $dt->ID_PAGE_CLIENT; ?>">
                            <div  class='page_list'>
                                <b>&nbsp;
                                    <a href="javascript:void(0);">
                                        <i class="fa fa-thumb-tack"></i> <?php echo $dt->NAME; ?>
                                    </a>
                                </b>
                                <?php echo lchild($dt->ID_PAGE_CLIENT); ?>
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
