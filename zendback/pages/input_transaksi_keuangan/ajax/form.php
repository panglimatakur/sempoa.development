<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$show			=	isset($_REQUEST['show']) 		? $_REQUEST['show']		:"";
	$parent_id		=	isset($_REQUEST['parent_id']) 	? $_REQUEST['parent_id']:"";
	$id_root		=	isset($_REQUEST['id_root']) 	? $_REQUEST['id_root']	:"";
	$qchild			=	$db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_CASH_TYPE='".$parent_id."'");
	$dtchild		=	$db->fetchNextObject($qchild);
	$no 			= 	$dtchild->ID_CASH_TYPE;
	$nama_parent	= 	$dtchild->NAME;
	$contenttype	= 	$id_root;
	$inout			= 	$db->fob("NAME",$tpref."cash_type"," WHERE ID_CASH_TYPE = '".$dtchild->IN_OUT."'");
}
else{  defined('mainload') or die('Restricted Access'); }
?>
<?php
if(!empty($show) && $show == "form_value"){?>
    <div class="w-box w-box-blue">
        <div class="ibox-title">
            <h5>Jenis Transaksi <?php echo "<b><u>".$inout."</u></b>"; ?></h4>
        </div>
    </div>
    <div class="form-group">
        <label class='req'>Tanggal Bayar</label>
        <?php $tgl_bayar_multi = date("d-m-Y"); ?>
        <span class="input-append date" id="dp2" data-date="<?php echo @$tgl_bayar_multi; ?>" data-date-format="dd-mm-yyyy">
            <input size="16" value="<?php echo $tgl_bayar_multi; ?>" readonly="" type="text" id="tgl_bayar_multi" class="mousetrap form-control validate[required] text-input">
            <span class="add-on"><i class="icsw16-day-calendar"></i></i></span>
        </span>                        
    </div>
    <div class="form-group">
        <label class='req'>Nama Transaksi</label>
        <input name="nama" id="nama" type="text" value="<?php echo @$nama_parent; ?>" class="form-control mousestrap" style="text-transform:capitalize" readonly="readonly"/>
    </div>
    <div class="form-group">
        <label class='req'>Keterangan Untuk <?php echo @$nama_parent; ?></label>
        <input type='text' name="keterangan" id="keterangan" class="form-control  mousestrap validate[required] text-input" value='<?php echo @$keterangan; ?>'input>
    </div>
    <div class="form-group">
        <label class='req'>Nilai Uang</label>
        <input name="nilai" id="nilai" type="text" value="<?php echo @$nilai; ?>" class="form-control  mousestrap validate[required] text-input" onkeyup="count_payment(this,'')"  onblur="count_payment(this,'')"  />
    </div>
    
    <div class="form-group">
        <label>Sumber Dana</label>
         <div class="category" id='id_cat'>
            <select name="sumber" id="sumber" class="col-md-6  mousestrap validate[required] text-input">
                <option value=''>--SUMBER DANA--</option>
                <?php 
                    $q_src = $db->query("SELECT * FROM ".$tpref."cash_sources WHERE ID_CLIENT='".$_SESSION['cidkey']."'"); 
                    while($dt_src = $db->fetchNextObject($q_src)){
                ?>
                    <option value='<?php echo $dt_src->ID_CASH_SOURCE; ?>' <?php if(@$sumber == $dt_src->ID_CASH_SOURCE){ ?> selected <?php } ?>>
                        <?php echo $dt_src->NAME; ?>
                    </option>
                <?php } ?>
             </select>
            <a href="javascript:void()" class='btn new_cat' style="margin:-12px 0 0 0">
                <i class="icon-plus"></i>Tambah Sumber Dana
            </a>
        </div>
        <div class="category" style="display:none"  id="div_category">
            <input type='text' id='category' style='text-transform:capitalize; margin:3px 3px 0 0;' placeholder='Sumber Dana'>
            <a href='javascript:void()' class='btn cancel_cat' data-info='' style='margin:0 3px 0 0'>
                <i class='icon-remove'></i> Batal
            </a>
            <a href='javascript:void()' class='btn save_cat' data-info=''>
                <i class='icon-ok'></i> Simpan
            </a>
        </div> 
    </div>
    <div class="form-group">
        <label class='req'>Status Bayar</label>
        <select id="status_lunas" name="status_lunas" onchange="show_kredit()" class="mousetrap form-control validate[required] text-input">
          <option value='2' >Lunas</option>
          <?php if(!empty($id_root) && $id_root == 2){ $sisa_label = "Hutang"; ?>
            <option value='1' >Hutang</option>
          <?php } ?>
          <?php if(!empty($id_root) && $id_root == 1){ $sisa_label = "Piutang"; ?>
            <option value='3' >Piutang</option>
          <?php } ?>
        </select>
    </div>
	<?php if(empty($status_lunas) || !empty($status_lunas) && $status_lunas == 2){ $display_lunas = "style='display:none'"; }else{ $display_lunas = ""; } ?>
    <div  class="div_kredit" <?php echo $display_lunas ?>>
        <div class="form-group">
            <label>Uang Muka</label>
            <span class="input-prepend input-append">
            <span class="add-on">Rp.</span>
            <input type='text' id='downpay' name='downpay' value='' class='form-control mousetrap' onkeyup="count_payment(this,'')" onblur="count_payment(this,'')">
             <span class="add-on">,00</span>
            </span>
        </div>
        <div class="form-group">
            <label>Sisa <?php echo @$sisa_label; ?> </label>
            <span class="input-prepend input-append">
            <span class="add-on">Rp.</span>
            <input type='text' id='kredit_label' value='<?php echo money("",@$kredit); ?>' class='mousetrap form-control' readonly />
        	</span>
            <input type='hidden' id='credit' value=''>
        </div>
        <div class="form-group">
            <label>Termin Pembayaran</label>
            <input value="<?php echo @$termin_multi; ?>" type="text" id="termin_multi" name="termin_multi" class="mousetrap form-control">
        </div>
        <span id="div_termin_multi" style="display:none" class='form-control'></span>
    </div>
    <div class="form-group" id="button_c">
		<?php
        if(empty($direction) || (!empty($direction) && ($direction == "insert" || $direction == 'delete'))){
			$directionvalue	= "insert";	
        }
        if(!empty($direction) && ($direction == "edit" || $direction == "save")){
			$directionvalue = "save";
			$addbutton = "
				<a href='".$lparam."'>
					<input name='button' type='button' class='btn btn-beoro-3' value='Tambah Data'>
				</a>";
        ?>
        <input type='hidden' name='no' value='<?php echo $no; ?>'>
        <?php } ?>
        <input type="hidden" name="id_root" value='<?php echo $id_root; ?>' id='id_root'/>
        <input type="hidden" name="parent_id" value='<?php echo $parent_id; ?>' id='parent_id'/>
        <button name="direction" id="direction" type="button" class="btn btn-sempoa-1" value="<?php echo $directionvalue; ?>">Simpan Data</button>
        <?php echo @$addbutton; ?>
    </div>
<?php } ?>