<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	function tree_location($id_parent,$deep){
		global $dirhost;
		global $db;
		$deep_class	= "";
		$deep		= $deep+1;
		$q_loc 		= $db->query("SELECT * 
							  	  FROM 
									system_master_location 
							  	  WHERE 
								  	PARENT_ID = '".$id_parent."' ORDER BY NAME ASC");
		switch($deep){
			case "2":$deep_class = "nav-second-level";	break;
			case "3":$deep_class = "nav-third-level";	break;
			case "4":$deep_class = "nav-fourth-level";	break;
		}
		?>
		<ul class="<?php echo $deep_class; ?>">
			<?php while($dt_loc = $db->fetchNextObject($q_loc)){ 
			@$child_loc 	= $db->recount("SELECT 
												ID_LOCATION 
											FROM 
												system_master_location 
											WHERE 
												PARENT_ID = '".$dt_loc->ID_LOCATION."'");
			?>
			<li data-value='<?php echo $dt_loc->ID_LOCATION; ?>' class="jstree-open" data-jstree='{"type":"map"}'>
				<?php echo $dt_loc->NAME; ?>
				<?php if($child_loc > 0){ echo tree_location($dt_loc->ID_LOCATION,$deep);}  ?>
			</li>
			<?php } ?>
		</ul>
	<?php 
	}
	
	if(!empty($direction) && $direction == "edit" ){
		$qcont				=	$db->query("SELECT * FROM system_master_location WHERE ID_LOCATION='".$no."' ");
		$dtedit				=	$db->fetchNextObject($qcont);
		$nama				=	$dtedit->NAME;
		$parent_id			=	$dtedit->PARENT_ID;
	}
?>
