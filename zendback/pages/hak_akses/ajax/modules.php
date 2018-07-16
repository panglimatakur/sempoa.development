<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['uidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
	}else{
		defined('mainload') or die('Restricted Access');	
	}
}else{
	defined('mainload') or die('Restricted Access');
}

$single				 = isset($_REQUEST['single']) 			    ? $sanitize->str($_REQUEST['single']) 		    	  :"";
$client_id			 = isset($_REQUEST['client_id']) 		    ? $sanitize->number($_REQUEST['client_id']) 		  :"";
$id_client_level	 = isset($_REQUEST['id_client_level']) 	    ? $sanitize->number($_REQUEST['id_client_level']) 	  :"";
$id_client_user_level= isset($_REQUEST['id_client_user_level']) ? $sanitize->number($_REQUEST['id_client_user_level']):"";
//========================================================== LINK CHILDREN =======================//
function lchild($parent,$node){ 
	global $tpref;
	global $db;
	global $lparam;
	global $ajax_dir;
	global $client_id;
	global $id_client_level;
	global $id_client_user_level;
	global $single;
	global $r;
	
	$qlink 	= $db->query("SELECT PAGE,ID_PAGE_CLIENT,NAME FROM system_pages_client WHERE ID_PARENT='".$parent."' ORDER BY DEPTH ASC, SERI ASC");
	$jml 	= $db->numRows($qlink);
	if($jml>0){
?>
		<ul>
<?php
		$deep = 0;
		while($dt = $db->fetchNextObject($qlink)){
			$r++;
			$id_page	= $dt->ID_PAGE_CLIENT;
			$chakses 	= $db->recount("SELECT ID_RIGHTACCESS FROM system_pages_client_rightaccess WHERE ID_CLIENT='".$client_id."' AND ID_PAGE_CLIENT = '".$dt->ID_PAGE_CLIENT."' AND ID_CLIENT_LEVEL='".$id_client_level."' AND ID_CLIENT_USER_LEVEL = '".$id_client_user_level."'");
			
			$deep = "-".$node."-".$r;
			@$ch_child  = $db->recount("SELECT ID_PAGE FROM system_pages_client WHERE ID_PARENT = '".$dt->ID_PAGE_CLIENT."'");
		?>
        
			<li <?php if($ch_child > 0){?> class="has" <?php } ?> >
               
               <input type='hidden'   name='ori_page_id[<?php echo $r; ?>]'/> 
               <input type="checkbox" name='id_halaman[<?php echo $r; ?>]' 
			   		  <?php if(!empty($single) && $chakses>0){ ?> checked <?php } ?> 
                      value='<?php echo $id_page; ?>' />
               <label><?php echo $dt->NAME; ?></label>
			   <?php echo lchild($id_page,$deep); ?>
			</li>
		<?php
		}
?>
	</ul>
<?php
	
	}
}
//========================================================== END OF LINK CHILDREN =======================//

$nama_level_merchant = $db->fob("NAME","system_master_client_level"," WHERE ID_CLIENT_LEVEL = '".$id_client_level."'");
?>

<div class="ibox-title">
    <h4>Daftar Modul Aplikasi</h4>
</div>
<div class='ibox-content'>
    <div class='alert alert-info' style="margin:5px">
        Beri tanda centang pada daftar menu Modul dibawah ini, yang menunjukan halaman mana yang bisa di akses oleh level pengguna 
        <b><?php echo $nama_level_merchant; ?></b>
    </div>
    
    
    
    <ul class="tree">
    <?php
    $r = 0; $q = 0;
    $qlink = $db->query("SELECT PAGE,ID_PAGE_CLIENT,NAME FROM system_pages_client WHERE ID_PAGE_CLIENT IS NOT NULL and DEPTH = '1' AND ID_PARENT='0' ORDER BY DEPTH ASC, SERI ASC");
    while($dt = $db->fetchNextObject($qlink)) {
        $r++;
        $q++;			
        $id_page 	= $dt->ID_PAGE_CLIENT;
        $chakses 	= $db->recount("SELECT ID_RIGHTACCESS FROM system_pages_client_rightaccess WHERE ID_CLIENT='".$client_id."' AND ID_PAGE_CLIENT = '".$dt->ID_PAGE_CLIENT."' AND ID_CLIENT_LEVEL='".$id_client_level."' AND ID_CLIENT_USER_LEVEL = '".$id_client_user_level."'");
		
		@$ch_child  = $db->recount("SELECT ID_PAGE FROM system_pages_client WHERE ID_PARENT = '".$dt->ID_PAGE_CLIENT."'");
		
        ?>
            <li <?php if($ch_child > 0){?> class="has" <?php } ?>>
                <input type='hidden' name='ori_page_id[<?php echo $r; ?>]' value='<?php echo $id_page; ?>' />
                <input type="checkbox" name='id_halaman[<?php echo $r; ?>]' 
                	   value='<?php echo $id_page; ?>' 
					   <?php if(!empty($single) && $chakses > 0){ ?> checked <?php } ?>/>
                <label><?php echo $dt->NAME; ?></label>
                <?php echo lchild($dt->ID_PAGE_CLIENT,$q); ?>
            </li>   
    <?php } ?>	
    </ul>
    
    <br /><br />
    <div class='form-group'>
        <button type="submit" name="direction" class="btn btn-sempoa-1" value="insert">
        	<i class="fa fa-check-square-o"></i> Simpan Data</button>
        <input type="hidden" name="jmlpage" value="<?php echo $r; ?>" />
    </div>
</div>
<script language="javascript">
	$('.tree label').on('click', function(e) {
		$(this).next('ul').fadeToggle();
		collapse = $(this).next('ul').css("opacity");
		if(collapse == 0){ 
			$(this).closest("[class='has']").css("background","url(<?php echo $dirhost; ?>/libraries/treeform/images/bminus.png) no-repeat 0 6px"); }
		else{
			$(this).closest("[class='has']").css("background","url(<?php echo $dirhost; ?>/libraries/treeform/images/bplus.png) no-repeat 0 6px");  }
		e.stopPropagation();
	});
	
	$(".tree input[type=checkbox]").on('change', function(e) {
		$(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
		e.stopPropagation();
	});
</script>
