<?php defined('mainload') or die('Restricted Access'); ?>
<form class="formular form_multi" id="formID" action="" method="POST" enctype="multipart/form-data">
<div class='col-md-7'>
<table width="100%" class="table table-striped table-bordered table-condensed table-hover" id="table_list">
    <thead>
          <tr class='editable'>
            <th colspan="3">
			<?php
                $id_product	= "multi";
            ?>
            <?php if(allow('insert') == 1){?>
                <div style='float:right; margin:3px 0 5px 0;'>
                <a href='<?php echo $ajax_dir; ?>/catatan.php?page=<?php echo $page; ?>' class='btn add_button fancybox fancybox.ajax' style=' <?php if(!empty($direction) && $direction == "show"){?>display:none<?php } ?> '>
                    <i class='icon-plus'></i>Tambah Produk
                </a>
              </div>
             <?php } ?>
            </th>
        </tr>
        <tr>
            <th width="15%">&nbsp;</th>
            <th width="70%" style='text-align:center'>Jumlah</th>
            <th width="15%" style='text-align:center'>ACTION</th>
        </tr>
    </thead>
    <tbody>
      <tr style="display:none;">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
       </tr>
	</tbody>
</table>
</div>
<div class='col-md-5'>
  <div class="form-group">
	<label class='req'>Total Jumlah Produk </label>
	<input type="text" id="jumlah_multi" name="jumlah_multi" value=""  onkeyup="calculate_multi('insert','jumlah_multi','multi')" onblur="calculate_multi('insert','jumlah_multi','multi')" class="mousetrap form-control validate[required] text-input" readonly/>
  </div>
  <div class="form-group">
  <label class='req'>Tanggal </label>
		<?php $tgl_kirim = date("d-m-Y"); ?>
		<span class="input-append date" id="dp2" data-date="<?php echo $tgl_kirim_multi; ?>" data-date-format="dd-mm-yyyy">
			<input size="16" value="<?php echo $tgl_kirim; ?>" readonly="" type="text" id="tgl_kirim_multi" name="tgl_kirim_multi" class="mousetrap form-control validate[required] text-input">
			<span class="add-on"><i class="icsw16-day-calendar"></i></i></span>
		</span>                        
</div>
  
  <div class="form-group">
	<label class='req'>Tujuan</label>
    <select name="id_dest_multi" id="id_dest_multi" class="form-control mousetrap validate[required] text-input">
    	<?php if($_SESSION['uclevelkey'] ==  2){?>
        <option value=''>--PILIH TUJUAN--</option>
			<?php
            $query_branch = $db->query("SELECT * FROM ".$tpref."clients WHERE CLIENT_ID_PARENT_LIST LIKE '%,".$_SESSION['cidkey'].",%' AND ID_CLIENT != '".$_SESSION['cidkey']."' ORDER BY CLIENT_NAME");
            while($data_branch = $db->fetchNextObject($query_branch)){
            ?>
                <option value='<?php echo $data_branch->ID_CLIENT; ?>' <?php if(!empty($id_branch) && $id_branch == $data_branch->ID_CLIENT){?> selected<?php } ?>><?php echo $data_branch->CLIENT_NAME; ?>
                </option>
            <?php } ?>
    	<?php }else{ 
				$parent = $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$_SESSION['cparentkey']."'");	
		?>
                <option value='<?php echo $_SESSION['cparentkey']; ?>'><?php echo $parent; ?></option>
        <?php } ?>
    </select>
	</span>
  </div>
  <div class="form-group">
    <label class='req'>Status Kirim</label>
    <select id="status_send_multi" name="status_send_multi" class="mousetrap validate[required] text-input">
		<?php
        if($_SESSION['uclevelkey'] ==  2){
			$query_status = $db->query("SELECT * FROM ".$tpref."distribution_status ORDER BY ID_DISTRIBUTION_STATUS ASC");
			while($data_status = $db->fetchNextObject($query_status)){
        ?>
            <option value='<?php echo $data_status->ID_DISTRIBUTION_STATUS; ?>' >
				<?php echo $data_status->NAME; ?> (<?php echo $data_status->NOTE; ?>)
            </option>
        <?php }
        }else{ ?>
            <option value='15' >PERMINTAAN PENGIRIMAN</option>
        <?php } ?>
    </select>

</div>
  <div class="form-group">
  	<label>Keterangan</label>
    <textarea name="keterangan_multi" id="keterangan_multi" class='mousetrap form-control' ><?php echo @$keterangan_multi; ?></textarea>
  </div>
  <?php if(allow('insert') == 1){?>
  <div class="form-group">
	<label>&nbsp;</label>
    <input type="hidden" name='direction' value="insert_multi" />
	<button type="button" class="btn btn-sempoa-1 col-md-6" style='margin:0' id="insert_multi_button" >
		<i class="icsw16-truck icsw16-white"></i>Kirim Barang
	</button>
  </div>
  <?php } ?>
</div>
</form>
