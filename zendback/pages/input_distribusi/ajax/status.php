<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include $call->func("function.paging"); 
	$page 					= isset($_REQUEST['page']) 						? $_REQUEST['page'] 					: "";
	$direction 				= isset($_REQUEST['direction']) 				? $_REQUEST['direction'] 				: "";
	$type 					= isset($_REQUEST['type']) 						? $_REQUEST['type'] 					: "";
	$id_product_distribution= isset($_REQUEST['id_product_distribution']) 	? $_REQUEST['id_product_distribution'] 	: "";
?>
<style type="text/css">
	.form_input{
		border:1px solid #DADADA;	
	}
	.form_input td{
		padding:6px 0 6px 10px;
		vertical-align:middle;	
	}
	.form_input input{
		margin:0;	
	}
</style>
<div  style="width:100%">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Status Distribusi</h4>
        </div>
        <div class="ibox-content" style="padding:5px;">
        	<?php
				$q_dist					= $db->query("SELECT * FROM ".$tpref."products_distributions WHERE ID_PRODUCT_DISTRIBUTION='".$id_product_distribution."'");
				$dt_dist				= $db->fetchNextObject($q_dist);
				$id_branch				= $dt_dist->ID_BRANCH;
				$id_distribution_status	= $dt_dist->ID_DISTRIBUTION_STATUS;
				
			$stat_cond	= "";
			if($type == "request"){
				$stat_cond = "WHERE (ID_DISTRIBUTION_STATUS !='15' AND ID_DISTRIBUTION_STATUS !='16')";
			}
			?>
          <div class="form-group">
            <label class='req'>Status Kirim</label>
            <select name="item_type" id="item_type" class='mousetrap col-md-5' >
                <optgroup label="Pengiriman">
                <?php
				$origin	= 2;
                $query_type = $db->query("SELECT * FROM ".$tpref."distribution_status ".$stat_cond." ORDER BY ID_DISTRIBUTION_STATUS ASC");
                while($data_type = $db->fetchNextObject($query_type)){
					if($data_type->ORIGIN != $origin && empty($request)){
						$request = 1;
					?>
                  </optgroup>
                  <optgroup label="Permintaan & Pengembalian">
                    <?php		
					}
                ?>
                    <option value='<?php echo $data_type->ID_DISTRIBUTION_STATUS; ?>' <?php if($id_distribution_status == $data_type->ID_DISTRIBUTION_STATUS){?> selected <?php } ?>>
						<?php echo $data_type->NAME; ?> (<?php echo $data_type->NOTE; ?>)
                    </option>
            <?php 
				$origin = $data_type->ORIGIN; 
				} 
			?>
                </optgroup>
            </select>
            <input type='hidden' id='id_product_distribution' value='<?php echo $id_product_distribution; ?>' />
            <input type='hidden' id='id_branch_status' value='<?php echo $id_branch; ?>' />
            
         </div>
         <div class="form-group">
         	<label>Catatan</label>
            <textarea id="catatan" class='mousetrap col-md-5' ></textarea>
         </div>
         <div class="form-group">
           	<span id='dt_list'>
            <button class="btn btn-sempoa-1" id="send_button" value='<?php echo $type; ?>'>
            	<i class="icsw16-box-incoming icsw16-white"></i>Perbaiki Status
            </button>
            </span>
         </div>
         
        </div>
    </div>
</div>
<?php }else{
	defined('mainload') or die('Restricted Access');
}
?>