<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$show				=	isset($_REQUEST['show']) 			? $_REQUEST['show']		:"";
	$id_cash_flow		=	isset($_REQUEST['id_cash_flow']) 	? $sanitize->number($_REQUEST['id_cash_flow']):"";
	$id_cash_type		=	isset($_REQUEST['id_cash_type']) 	? $sanitize->number($_REQUEST['id_cash_type']):"";
	
	$qparent			=	$db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_CASH_TYPE='".$id_cash_type."' AND (ID_CLIENT='".$id_client."' OR ID_CLIENT = '0')");
	$dt_parent			=	$db->fetchNextObject($qparent);
	$id_root			= 	$dt_parent->IN_OUT;
	$nama_parent		=   $dt_parent->NAME;
	if($id_root == 1){
		$id_debcre		= 3;	
	}
	if($id_root == 2){
		$id_debcre		= 1;	
	}
	$qchild				=	$db->query("SELECT * FROM ".$tpref."cash_flow WHERE ID_CASH_FLOW='".$id_cash_flow."'");
	$dt_transaksi		=	$db->fetchNextObject($qchild);
    if(strlen($id_cash_flow) == 1){ $id_transaction = '0'.$id_cash_flow; }else{ $id_transaction = $id_cash_flow; }
	$keterangan 		= 	$dt_transaksi->NOTE;
	$sumber 			= 	$dt_transaksi->ID_CASH_SOURCE;
	$status_lunas 		= 	$dt_transaksi->PAID_STATUS;
	$downpay 			= 	$dt_transaksi->PAID;
	$termin 			= 	$dt_transaksi->TERMS;
	$kredit 			= 	$dt_transaksi->REMAIN;
	$keterangan			= 	$dt_transaksi->NOTE; 
	$tgl_bayar_edit		= 	$dt_transaksi->TGLUPDATE; 
}
else{  defined('mainload') or die('Restricted Access'); }
?>
<?php
if(!empty($show) && $show == "form_value"){?>
<input type="hidden" id="id_cash_flow" value="<?php echo $id_cash_flow; ?>">
<div class="w-box w-box-blue">
    <div class="ibox-title">
        <h4>Perbaikan Transaksi <?php echo "<b><u>TR".$id_transaction."</u></b>"; ?></h4>
    </div>
</div>
<div class="form-group">
    <label class='req'>Tanggal Bayar</label>
    <?php $tgl_bayar_edit = date("d-m-Y"); ?>
    <span class="input-append date" id="dp2" data-date="<?php echo $tgl_bayar_edit; ?>" data-date-format="dd-mm-yyyy">
        <input size="16" value="<?php echo $tgl_bayar_edit; ?>" readonly="" type="text" id="tgl_bayar_edit" class="col-md-3 mousetrap  validate[required] text-input">
        <span class="add-on"><i class="icsw16-day-calendar"></i></i></span>
    </span>                        
</div>
<div class="form-group">
    <label class='req'>Nama Transaksi</label>
    <input name="nama" id="nama" type="text" value="<?php echo @$nama_parent; ?>" class="col-md-3 mousestrap" style="text-transform:capitalize" readonly="readonly"/>
</div>
<div class="form-group">
    <label class='req'>Keterangan Untuk <?php echo @$nama_parent; ?></label>
    <input type='text' name="keterangan" id="keterangan" class="col-md-6 mousestrap validate[required] text-input" value='<?php echo @$keterangan; ?>'input>
</div>
<div class="form-group">
    <label class='req'>Nilai Uang</label>
    <input type="text" id="cash_value" value="<?php echo $dt_transaksi->CASH_VALUE; ?>" class='col-md-3' onkeyup="count_payment(this,'')" onblur="count_payment(this,'')">
    <input type="hidden" id="cash_original_value" value="<?php echo $dt_transaksi->CASH_VALUE; ?>">
</div>
<div class="form-group">
    <label>Sumber Dana</label>
     <div class="category" id='id_cat'>
        <select id="sumber" class="col-md-3">
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
    <div class="category" style="display:none" id="div_category">
        <input type='text' id='category' class="col-md-3" style='text-transform:capitalize; margin:3px 3px 0 0;' placeholder='Sumber Dana'>
        <a href='javascript:void()' class='btn cancel_cat' data-info='' style='margin:3px 3px 0 3px'>
            <i class='icon-remove'></i> Batal
        </a>
        <a href='javascript:void()' class='btn save_cat' data-info='' style='margin:3px 3px 0 3px'>
            <i class='icon-ok'></i> Simpan
        </a>
    </div> 
</div>
<div class="form-group">
    <label class='req'>Status Bayar</label>
    <select id="status_lunas" name="status_lunas" onchange="show_kredit()" class="mousetrap col-md-3 validate[required] text-input">
      <option value='2' <?php if(!empty($status_lunas) && $status_lunas == 2){?> selected <?php } ?>>Lunas</option>
      <?php if(!empty($id_root) && $id_root == 2){ $sisa_label = "Hutang"; ?>
        <option value='1' <?php if(!empty($status_lunas) && $status_lunas == 1){?> selected <?php } ?>>Hutang</option>
      <?php } ?>
      <?php if(!empty($id_root) && $id_root == 1){ $sisa_label = "Piutang"; ?>
        <option value='3' <?php if(!empty($status_lunas) && $status_lunas == 3){?> selected <?php } ?>>Piutang</option>
      <?php } ?>
    </select>
</div>
<?php if(empty($status_lunas) || !empty($status_lunas) && $status_lunas == 2){ $display_lunas = "style='display:none'"; }else{ $display_lunas = ""; } ?>
<span id="contoh"></span>
<div  class="div_kredit" <?php echo $display_lunas ?>>
    <div class="form-group">
        <label>Uang Muka</label>
        <span class="input-prepend input-append">
        <span class="add-on">Rp.</span>
       <input type='text' id='downpay' name='downpay' value='<?php if(!empty($downpay)){ echo @$downpay; } ?>' class='col-md-3 mousetrap' onkeyup="count_payment(this,'')" onblur="count_payment(this,'')">
         <span class="add-on">,00</span>
        </span>
    </div>
    <div class="form-group">
        <label>Sisa <?php echo @$sisa_label; ?> </label>
        <span class="input-prepend input-append">
        <span class="add-on">Rp.</span>
        <input type='text' id='kredit_label' value='<?php if(!empty($kredit)){  echo money("",@$kredit); } ?>' class='mousetrap col-md-3' readonly />
        </span>
        <input type='hidden' id='credit' value='<?php if(!empty($kredit)){  echo $kredit; } ?>'>
        <input type='hidden' id='first_credit' value='<?php if(!empty($kredit)){  echo $kredit; } ?>'>
    </div>
    <div class="form-group">
        <label>Termin Pembayaran</label>
        <input value="<?php echo @$termin; ?>" type="text" id="termin_edit" name="termin" class="mousetrap col-md-3" readonly="readonly">
        <button class='btn' id='btn_termin' style="margin:-10px 0 0 0 "><i class="icon-plus"></i></button>
    </div>
	  <?php 
      if(!empty($termin)){
        $termin_cond = "";
        $q_termin = $db->query("SELECT * FROM ".$tpref."debt_credit_reminder WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND DEBT_CREDIT='".$id_debcre."' ");
        $r = 0;
        while($dt_termin = $db->fetchNextObject($q_termin)){
            $r++;
            $remider_date = $dtime->date2indodate($dt_termin->REMINDER_DATE);
      ?>
      <div id='tr_edit_<?php echo $dt_termin->ORDINAL; ?>' class='form-group tr_edit' data-list='old'>
        <label class='option'>Tanggal Jatuh Tempo <?php echo $r; ?></label>
        <span class='input-append date' id='dp_edit_<?php echo $dt_termin->ORDINAL; ?>' data-date='' data-date-format='dd-mm-yyyy'>
            <input class='mousetrap date_input' size='16' value='<?php echo $remider_date; ?>' readonly='' type='text' id='tgl_tempo_edit_<?php echo $dt_termin->ORDINAL; ?>'>
            <span class='add-on'><i class='icsw16-day-calendar'></i></i></span>
        </span> 
        
        <?php if($dt_termin->STATUS != 1){?>
            <script language="javascript">
                $('#dp_edit_<?php echo $dt_termin->ORDINAL; ?>').datepicker();
            </script>
            <button class='btn beoro-btn' style='margin-left:3px' onclick="remove_tempo('<?php echo $dt_termin->ORDINAL; ?>','<?php echo $id_cash_flow; ?>')"><i class="icsw16-trashcan"></i></button>
            <?php }else{ 
            $last_pay = $db->fob("AMOUNT",$tpref."cash_debt_credit"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND ORDINAL = '".$dt_termin->ORDINAL."'");
        ?>
            <div class='alert alert-info' style='float:right; margin:0; padding:2px 8px 2px 10px; width:40%; overflow:hidden'>
                Di Bayar Sebesar <?php echo money("Rp.",$last_pay); ?>
            </div>
        <?php } ?>
        <input type='hidden' id='st_termin_<?php echo $dt_termin->ORDINAL; ?>' value='<?php echo $dt_termin->STATUS; ?>' />
        <span id='delete_tempo_loader_<?php echo $dt_termin->ORDINAL; ?>'></span>
      </div>
      <?php
        }
      }
      ?>
    <span id="div_termin_edit" ></span>
</div>
	<?php if(allow('edit') == 1){?> 
        <div class="form-group" id="direction_save_button">
        	<input type='hidden' id="tgljs" value='<?php echo date("d-m-Y"); ?>'/>
            <button name="direction" id="direction_save" type="button" class="btn btn-sempoa-1" data-info='<?php echo $id_cash_flow; ?>' value="save">Simpan Data</button><div id="load"></div>
        </div>
    <?php } ?>
<?php } ?>