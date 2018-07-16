<?php
session_start(); 
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	$clients		= isset($_REQUEST['client']) ? $_REQUEST['client'] 	:"";
	$size			= isset($_REQUEST['size']) 	 ? $_REQUEST['size'] 	:"";
	if(!empty($size)){
		$padding = "padding:0 25px 0 0";	
	}
	$count			= count($clients);
	$t 				= 0;
	$condition		= "(";
	foreach($clients as &$client){
		$t++; if($t < $count){ $op = "OR"; }else{ $op = ""; }
		$condition .= "ID_CLIENT='".$client."' ".$op." ";
	} 
	$condition		.= ")";
	$query_str		= "SELECT ID_CLIENT,CLIENT_NAME,CLIENT_DESCRIPTIONS,CLIENT_APP,CLIENT_ADDRESS FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND ".$condition." ORDER BY ID_CLIENT";
	#echo $query_str;
	$q_user 		= $db->query($query_str);
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
.boxit{
	font-family:Verdana, Geneva, sans-serif; 
	text-align:center; 
	border:1px solid #333; 
}
.
</style>
<body>
    <div id="print_wrapper">
	 <?php while($dt_user	= $db->fetchNextObject($q_user)){ ?>
     <div style="float:left; ">
     	<?php if(!empty($size)){?>
        <div class="boxit" style="padding:4px 0 4px 0; font-size:13px; width:469px; text-align:center; color:#A31458; font-weight:bold">
        	<?php echo "https:<small>//</small>".$website_name."<small>/</small>coin<small>/</small>".$dt_user->CLIENT_APP; ?>
        </div>
        <?php } ?>
        <div class="boxit"  style="padding:4px 35px 4px 35px; margin:4px 6px 15px 0; width:400px; height:75px; ">
            <table align="center">
                <tr>
                    <td width="80%" style='vertical-align:top; width:50px; border-right:none;'>
                        <?php echo getclientlogo($dt_user->ID_CLIENT," class='thumbnail' style='width:50px'"); ?>
                    </td>
                    <td width="991" style="vertical-align:top; border-left:none; ">
                        <b style="color:#C00; font-size:12px; "><?php echo $dt_user->CLIENT_NAME; ?></b>
                        <br>
                        <div style="font-size:8px; <?php echo @$padding; ?>">
                            <?php 
                                echo cutext($dt_user->CLIENT_DESCRIPTIONS,200);
                            ?>
                        </div>
                        <div style="font-size:8px; color:#0D621C; <?php echo @$padding; ?>">
                            <?php 
                                echo cutext($dt_user->CLIENT_ADDRESS,190);
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
   </div>
	<?php } ?>
    </div>
</body>


<script language="javascript">
	window.print();
</script>
<?php
}
?>
