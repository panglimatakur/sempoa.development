<?php defined('mainload') or die('Restricted Access'); ?>
<!-- main content -->
<div class="ibox-content">
    <div class="ibox float-e-margins">
    <form method="post" action="" name="form_periode">
        <div class='form-group'>
        <label>Periode</label>
        <select style="width:20%; margin:5px 0 9px 9px" name="periode" id="periode" >
            <option value="">--PERIODE--</option>
            <option value="harian" <?php if(!empty($periode) && $periode=="harian"){?>selected<?php } ?>>HARIAN</option>
            <option value="bulanan" <?php if(!empty($periode) && $periode=="bulanan"){?>selected<?php } ?>>BULANAN</option>
            <option value="tahunan" <?php if(!empty($periode) && $periode=="tahunan"){?>selected<?php } ?>>TAHUNAN</option>
        </select>
        <span id="div_periode"><?php if(!empty($periode)){ include $call->inc($ajax_dir,"data.php"); } ?></span>
        <button type="submit" class="btn" style="margin:5px 0 9px 0" id="save_button" name="direction" value="periode">
        	<i class='icon-search'></i>
        </button>
        </div>
    </form>
    </div>
</div>
<input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />

<div class="row-fluid">
<?php $d = 0; while($dt_polling = $db->fetchNextObject($q_polling)){ $d++; ?>
    <div class="col-md-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h4><?php echo $dt_polling->SUBJECT; ?></h4>
            </div>
            <div class="w-box-content cnt_b" >
            	<div style="text-align:center"><?php echo $dt_polling->QUESTION; ?></div>
                <div id="ch_polling_<?php echo $dt_polling->ID_POLLING; ?>" class="chart_a" style='height:500px' ></div>
            </div>
        </div>
    </div>
    
    
	<?php
    $q_group 		= $db->query("SELECT * FROM ".$tpref."pollings_options WHERE ID_POLLING='".$dt_polling->ID_POLLING."' ORDER BY INDEX_POLLING_OPTION ASC ");
    $c 				= 0;
    $data_group		= "";
    while($dt_group = $db->fetchNextObject($q_group)){
		
        $result['label'] 	= $dt_group->VALUE_POLLING_OPTION;
        $result['data']		= $db->recount("SELECT ID_POLLING_RESULT FROM ".$tpref."pollings_results WHERE ID_POLLING = '".$dt_group->ID_POLLING."' AND ID_POLLING_OPTION = '".$dt_group->ID_POLLING_OPTION."'");
		if($result['data'] > 0){
            $c++;
            $data_group .= '{"label":"'.$result['label'].'","data":'.$result['data'].'},';
        }
    }
	$id_polling[$d]		= $dt_polling->ID_POLLING;
	$data_polling[$d]	= $data_group;
} 
?>
</div>
