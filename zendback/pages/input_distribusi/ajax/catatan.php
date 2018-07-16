<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include $call->func("function.paging"); 
	$page 						= isset($_REQUEST['page']) 						? $_REQUEST['page'] 					: "";
	$direction 					= isset($_REQUEST['direction']) 				? $_REQUEST['direction'] 				: "";
	$type 						= isset($_REQUEST['type']) 						? $_REQUEST['type'] 					: "";
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
            <h5>Daftar Produk</h4>
        </div>
        <div class="ibox-content">
        	<div id="msg"></div>
			<?php if(empty($direction) && $direction != "edit"){?>
            <input type="text" size="16" id="searching" placeholder='Pencarian Produk' style="width:20%; margin:8px 0 9px 9px">
            <select style="width:20%; margin:8px 0 9px 9px" id="filter">
            	<option value=''>--PILIH FILTER--</option>
            	<option value="code">Kode Item</option>
            	<option value="nama">Nama Item</option>
            	<option value="deskripsi">Deskripsi</option>
            </select>
            <select name="item_type" id="item_type" style="width:10%; margin:8px 0 9px 9px">
                <?php
                $query_type = $db->query("SELECT * FROM ".$tpref."products_types ORDER BY ID_PRODUCT_TYPE ASC");
                while($data_type = $db->fetchNextObject($query_type)){
                ?>
                    <option value='<?php echo $data_type->ID_PRODUCT_TYPE ?>' <?php if(!empty($item_type) && $item_type == $data_type->ID_PRODUCT_TYPE){?> selected<?php } ?>><?php echo $data_type->NAME; ?>
                    </option>
            <?php } ?>
            </select>
            <button class="btn" style="margin:5px 0 9px 0" id="search_button"><i class='icon-search'></i></button>
            <?php } ?>
            <div id="dt_list">
			<?php
				if($direction != "edit"){
					include $call->inc("modules/".$page."/includes","product_list_kolektif.php");
				}else{
					include $call->inc("modules/".$page."/includes","product_list.php");
				}
       	 	?>
        	</div>
            <input type="hidden" id="type" value="<?php echo $type; ?>" />
            <input type="hidden" id="id_product_distribution" value="<?php echo $id_product_distribution; ?>" />
            <input type="hidden" id="id_product_stock" value="<?php echo $id_product_stock; ?>" />
        </div>
    </div>
</div>
<?php }else{
	defined('mainload') or die('Restricted Access');
}
?>