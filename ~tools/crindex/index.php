<?php
if(!defined('mainload')) { define('mainload','Master Web Card',true); }
include_once('../../includes/config.php');
include_once('../../includes/classes.php');
include_once('../../includes/functions.php');
include_once('../../includes/declarations.php');

if(!empty($_REQUEST['proses'])){
	$proses = $_REQUEST['proses'];
	
	function go_in($dir){ global $proses; ?>
    <ul>
    <?php 
	  $dir	= $dir."/";
	  if ($dh = opendir($dir)){
		while (($file = readdir($dh)) !== false){
		  if(is_dir($dir.$file) && $file != '.' && $file != '..' && $file != 'Archieves'){?>
		  	<li>
				<?php echo "filename: " . $file; ?>
                <?php 
					if($proses == "bikin"){ copy("file/index.html",$dir.$file."/index.html"); }
					if($proses == "hapus"){ unlink($dir.$file."/index.html"); }
				?>
            	<?php go_in($dir.$file); ?>
            </li>
		  <?php }
		}
		closedir($dh);
	  } ?>
      </ul>
     <?php
	}
?>
<ul>
<?php
$dir = "../../";
	if (is_dir($dir)){
	  if ($dh = opendir($dir)){
		while (($file = readdir($dh)) !== false){
		  if(is_dir($dir.$file) && $file != '.' && $file != '..' && $file != 'Archieves' && $file != '~tools'){?>
		  	<li>
				<?php echo "filename: " . $file; ?>
                <?php 
					if($proses == "bikin"){ copy("file/index.html",$dir.$file."/index.html"); }
					if($proses == "hapus"){ unlink($dir.$file."/index.html"); }
				?>
            	<?php go_in($dir.$file); ?>
            </li>
		  <?php }
		}
		closedir($dh);
	  }
	}
?>
</ul>
<?php
}
?>
<form name="form1" method="post" action="">
  <button type="submit" name="proses" value="bikin">BIKIN INDEX</button>
  <button type="submit" name="proses" value="hapus">HAPUS INDEX</button>
</form>
