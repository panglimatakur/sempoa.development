<?php defined('mainload') or die('Restricted Access'); ?>
<?php
function transaction_type($parent){
	global $db;
	global $id_root;
	global $lparam;
	global $tpref;
	global $periode;
	global $link;
	global $lparam;
	global $condition;
	$qlink 	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_PARENT='".$parent."' AND (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') ORDER BY ID_CASH_TYPE ASC");
	
	$jml 	= $db->numRows($qlink);
	if($jml >0){
?>
		<ul style="list-style:none">
<?php
		$t = 0;
		while($dt = $db->fetchNextObject($qlink)){
		$t++;
		$mox = $t%2;
		if($mox == 1){ $style = "style='background:#F5F5F5'"; }else{ $style = ""; }
		$total_t			= $db->sum("PAID",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".@$condition."");
		if($dt->IS_FOLDER == 1){
			$class			= "file";	
		}else{
			$class			= "folder";	
		}
		?>
			<li <?php echo $style; ?> id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
				<div id='link_list'>
					<p class='link1' style="float:left">
						<a href='<?php echo $lparam; ?>&parent_id=<?php echo $dt->ID_CASH_TYPE; ?>' class="<?php echo $class; ?>">
							<?php echo $dt->NAME; ?>
						</a>
					</p>
					<p class='buttons1' style="float:right; margin-right:5px;">
					<?php echo money("Rp.",$total_t); ?>
                    <input type='hidden' id='links_<?php echo $dt->ID_CASH_TYPE; ?>' value='<?php echo $link; ?>' />
					</p>
				</div>		
				<br clear="all" />  
				<?php echo transaction_type($dt->ID_CASH_TYPE); ?>
			</li>
		<?php
		}
?>
	</ul>
<?php
	}
}
?>

<ul id="browser" class="filetree">
    <?php
    $t = 0;
    while($dt 	= $db->fetchNextObject($qlink)){
        $t++;
        $mox = $t%2;
        if($mox == 1){ $style = "style='background:#F5F5F5'"; }else{ $style = ""; }
        $close = ""; 
        if($dt->ID_CASH_TYPE != 1 && $dt->ID_CASH_TYPE != 2){ 
            $class			= "file";
        }else{
            $close 			= "display:none";
            $class			= "folder";
        }
        $total_t			= $db->sum("PAID",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND ID_CLIENT='".$_SESSION['cidkey']."' ".@$condition."");
    ?>
        <li <?php echo $style; ?> id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
            <div id='link_list'>
                <p class='link1' style="float:left">
                <a href="<?php echo $lparam; ?>&parent_id=<?php echo $dt->ID_CASH_TYPE; ?>" class="<?php echo $class; ?>">
                    <?php echo $dt->NAME; ?>
                </a>
                </p>
                <p class='buttons1' style="float:right; margin-right:5px; <?php echo $close; ?>">
                        <?php echo money("Rp",$total_t); ?>
                </p>
             </div>
             <br clear="all" />
            <?php echo transaction_type($dt->ID_CASH_TYPE); ?>
        </li>
    <?php } ?>	
    <br clear="all" />
</ul>
