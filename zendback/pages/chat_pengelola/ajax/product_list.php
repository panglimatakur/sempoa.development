<?php 
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
	$id_disc 		= isset($_REQUEST['id_disc']) ? $_REQUEST['id_disc'] 	: "";
	
	//INFORMASI PRODUK
	?>
<div class='w-box'>

	<?php if(empty($direction)){?>
        <div id="msg"></div>
        <input type="text" size="16" id="searching" placeholder='Pencarian Produk' style="width:20%; margin:8px 0 9px 9px">
        <select style="width:20%; margin:8px 0 9px 9px" id="filter">
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
        <button type="button" class="btn" style="margin:5px 0 9px 0" id="search_button"><i class='icon-search'></i></button>
        <input type='hidden' id='product_list' value='<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/product_list.php' />    
        <input type='hidden' id='id_disc' value='<?php echo $id_disc; ?>' />    
	<?php } ?>
        
    <?php 
	$condition		= "";
	if(!empty($direction) && $direction == "search_produk"){
		$searching 		= isset($_POST['searching']) 	? $_POST['searching'] 	: "";
		$filter 		= isset($_POST['filter']) 		? $_POST['filter'] 		: "";
		$item_type 		= isset($_POST['item_type']) 	? $_POST['item_type'] 	: "";
		if(!empty($item_type)){
			if(!empty($item_type)){
				$condition = " AND ID_PRODUCT_TYPE ='".$item_type."'";
			}
			switch ($filter){
				case "code":
					$condition = " AND CODE ='".$searching."'";
				break;
				case "nama":
					$condition = " AND NAME ='".$searching."'";
				break;
				case "deskripsi":
					$condition = " AND DESCRIPTION LIKE '%".$searching."%'";
				break;
			}
		}
	}
	$str_product		= "SELECT ID_PRODUCT,CODE,NAME,ID_PRODUCT_UNIT,SALE_PRICE,DESCRIPTION FROM ".$tpref."products WHERE ID_CLIENT='".$id_client."' ".$condition." ";
	$q_product			= $db->query($str_product);
	$num_product		= $db->numRows($q_product);
	if($num_product > 0){
		?>
        <div id="dt_list">
            <table width="80%" class="table table-striped">
              <thead>
                <tr>
                    <th width="98" style="width:100px">&nbsp;</th>
                    <th width="883"><b>Informasi</b></th>
                    <th width="95" style="text-align:center">Pilih</th>
              </tr>
              </thead>
              <tbody>
                <?php
                while($dt_product	= $db->fetchNextObject($q_product)){
				$photo				= "";
                @$id_product		= $dt_product->ID_PRODUCT;
                @$code 				= $dt_product->CODE;
                @$product_name		= $dt_product->NAME;
                @$price				= $dt_product->SALE_PRICE;
                @$description		= $dt_product->DESCRIPTION;
                @$id_product_unit	= $dt_product->ID_PRODUCT_UNIT;
                @$photo				= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product."'");
                @$unit				= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_product->ID_PRODUCT_UNIT."'"); 
                ?>
                      <tr id="tr_<?php echo @$id_product; ?>">
                        <td>
                            <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                                <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' class="photo"/>
                            <?php }else{ ?>
                                <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class="photo"/>
                            <?php } ?>
                        </td>
                <?php
					if(empty($photo) || !is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){
						$photo = "";
					}
				?>
                        <td>
                            <b>Kode Item</b><br />
                            <?php echo @$code; ?><br />
                            <b>Nama Item</b><br />
                            <?php echo @$product_name; ?><br />
                            <?php if(!empty($price)){?>
                                <b>Harga Jual</b><br />
                                <?php echo money("Rp.",$price); ?>
                            <?php } ?>
                            <?php if(!empty($description)){?>
                                <b>Deskripsi Item</b><br />
                                <?php echo $description; ?>
                            <?php } ?>
                            
                        </td>
                        <td style="text-align:center">
                        	<button type="button" class='btn' onclick="pick('<?php echo $id_product; ?>','<?php echo @$code; ?>','<?php echo $photo; ?>');"><i class='icon-ok'></i> Pilih</button>
                        </td>
                   </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php }else{?> 
		<div class='alert alert-error'>Maaf, anda belum mendaftarkan katalog produk anda</div>
	<?php }?>
    
    
</div>    
<?php }else{
	defined('mainload') or die('Restricted Access');
}
 ?>