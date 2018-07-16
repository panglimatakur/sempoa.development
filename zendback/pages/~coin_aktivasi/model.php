<?php defined('mainload') or die('Restricted Access'); ?>
<?php
function lchild($parent){
	global $tpref;
	global $db;
	global $lparam;
	global $ajax_dir;
	
	$qlink 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='".$parent."' ORDER BY CLIENT_NAME ASC");
	$jml 	= $db->numRows($qlink);
	if($jml >0){
?>
		<ul>
<?php
		while($dt = $db->fetchNextObject($qlink)){
		?>
			<liid="li_<?php echo $dt->ID_CLIENT; ?>">
				<div class='link-name pull-left'>
					<p class="folder">
						<a href="javascript:void(0);" onclick="getparent('<?php echo $dt->ID_CLIENT; ?>');">
							&nbsp; <?php echo $dt->CLIENT_NAME; ?>
						</a>
					</p>
				</div>		
				<div class="clearfix"></div>
				<?php echo lchild($dt->ID_CLIENT); ?>
			</li>
		<?php
		}
?>
	</ul>
<?php }
}
?>

<?php
$query_str 	= "SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='0' ORDER BY CLIENT_NAME ASC";
$qlink 		= $db->query($query_str);


$str_coin 	= "SELECT * FROM ".$tpref."customers WHERE CUSTOMER_STATUS = '1' ORDER BY ID_CUSTOMER ASC LIMIT 0,30";
$query_coin = $db->query($str_coin);	
$num_coin	= $db->numRows($query_coin);
?>
