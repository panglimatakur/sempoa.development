<?php
defined('mainload') or die('Restricted Access');
class templ{
	function main_menu_cpanel($id_parent,$deep){
		global $dirhost;
		global $db;
		$deep_class	= "";
		$deep		= $deep+1;
		$q_main_menu_cpanel = $db->query("SELECT * 
										  FROM 
											system_pages_client 
										  WHERE 
										  ID_PARENT = '".$id_parent."' AND STATUS='1' ORDER BY SERI ASC");
		switch($deep){
			case "2":$deep_class = "nav-second-level";	break;
			case "3":$deep_class = "nav-third-level";	break;
			case "4":$deep_class = "nav-fourth-level";	break;
		}
		?>
		<ul class="nav <?php echo $deep_class; ?>">
		<?php while($dt_main_menu_cpanel = $db->fetchNextObject($q_main_menu_cpanel)){ 
			if($dt_main_menu_cpanel->IS_FOLDER == 1){ $url_link = "javascript:void()";								}
			else									{ $url_link = $dirhost."/?page=".$dt_main_menu_cpanel->PAGE;	}
			if(rightaccess($dt_main_menu_cpanel->PAGE) > 0){
				@$child 	= $db->recount("SELECT 
												ID_PAGE_CLIENT 
											FROM 
												system_pages_client 
											WHERE 
												ID_PARENT='".$dt_main_menu_cpanel->ID_PAGE_CLIENT."' AND STATUS='1'");
		?>
			<li>
				<a href="<?php echo $url_link; ?>">
					<?php echo $dt_main_menu_cpanel->NAME; ?>
                    <?php if($child > 0){?><span class="fa arrow"></span><?php } ?>
				</a>
				<?php if($child > 0){ echo $this->main_menu_cpanel($dt_main_menu_cpanel->ID_PAGE_CLIENT,$deep);}  ?>
			</li>
		<?php }
		} 
		?>
		</ul>
	<?php 
		
	}
	
}

$tpl = new templ();
?>
