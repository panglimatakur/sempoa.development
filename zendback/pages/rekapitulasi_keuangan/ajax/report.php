<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$periode 	= isset($_POST['periode']) 	? $_POST['periode'] : "";
	$in_out 	= isset($_POST['in_out']) 	? $_POST['in_out'] 	: "";
	$links 		= isset($_POST['links']) 	? $_POST['links'] 	: "";
	$links2			= explode("-",$links);
	if(!empty($periode) && $periode == "harian"){
		$condition		= "AND a.TGLUPDATE='".$links."'";
		$tgl			= $links2[2];
		$bln			= $links2[1];
		$thn			= $links2[0];
		$link			= '"tgl":'.$tgl.',"bln":'.$bln.',"thn":'.$thn;
		$link_print		= "&tgl=".$tgl."&bln=".$bln."&thn=".$thn;   
	}
	if(!empty($periode) && $periode == "bulanan"){
		$bln			= $links2[0];
		$thn			= $links2[1];
		$condition		= "AND MONTH(a.TGLUPDATE) = '".$bln."' AND YEAR(a.TGLUPDATE) = '".$thn."'";
		$link			= '"bln":'.$bln.',"thn":'.$thn;
		$link_print		= "&bln=".$bln."&thn=".$thn;   
	}
	if(!empty($periode) && $periode == "tahunan"){
		$thn			= $links2[0];
		$thn2			= $links2[1];
		$condition		= "AND YEAR(a.TGLUPDATE) BETWEEN '".$thn."' AND '".$thn2."'";
		$link			= '"thn":'.$thn.',"thn2":'.$thn2;
		$link_print		= "&thn=".$thn."&thn2=".$thn2;   
	}	
	$query_str		= "SELECT * FROM ".$tpref."cash_type WHERE IN_OUT = '".$in_out."' AND (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') AND ID_PARENT='0' ORDER BY IN_OUT ASC";
	$q_transaksi 	= $db->query($query_str);
}else{
	defined('mainload') or die('Restricted Access');
}


function transaction_type($parent){
	global $db;
	global $id_root;
	global $lparam;
	global $tpref;
	global $periode;
	global $link;
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
		$total_t			= $db->sum("a.PAID",$tpref."cash_flow a"," WHERE a.ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND (a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").") ".@$condition."");
		if($dt->IS_FOLDER == 1){
			$class			= "file";	
		}else{
			$class			= "folder";	
		}
		?>
			<li <?php echo $style; ?> id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
				<div id='link_list'>
					<p class='link1' style="float:left">
						<a href='javascript:void()' onclick="get_detail('div_report','div_detail','<?php echo $dt->ID_CASH_TYPE; ?>')" class="<?php echo $class; ?>">
							<?php echo $dt->NAME; ?>
						</a>
					</p>
					<p class='buttons1' style="float:right; margin-right:5px;">
					<a href='javascript:void()' onclick="get_detail('div_report','div_detail','<?php echo $dt->ID_CASH_TYPE; ?>')"><?php echo money("Rp.",$total_t); ?></a>
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
    <div class="ibox-content">
        <div class="ibox-title">
            <div class="pull-right">
                <div class="toggle-group">
                    
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo $inc_dir; ?>/print_report.php?page=<?php echo $page; ?>&periode=<?php echo $periode; ?><?php echo $link_print; ?>" target="_blank">
                            <i class="icsw16-printer" style="margin:-2px 4px 0 0"></i>Cetak Data
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul id="browser" class="filetree" style="margin:0; list-style:none">
            <?php
            $t = 0;
            while($dt 	= $db->fetchNextObject($q_transaksi)){
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
                @$total_t			= $db->sum("a.PAID",$tpref."cash_flow a"," WHERE a.ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND (a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").") ".@$condition."");
            ?>
                <li <?php echo $style; ?> id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
                    <div id='link_list'>
                        <p class='link1' style="float:left">
                        <a href='javascript:void()' onclick="get_detail('div_report','div_detail','<?php echo $dt->ID_CASH_TYPE; ?>')" class="<?php echo $class; ?>">
                            <?php echo $dt->NAME; ?>
                        </a>
                        </p>
                        <p class='buttons1' style="float:right; margin-right:5px; <?php echo $close; ?>">
                            <a href='javascript:void()' onclick="get_detail('div_report','div_detail','<?php echo $dt->ID_CASH_TYPE; ?>')">
                                <?php echo money("Rp.",$total_t); ?>
                            </a>
                            <input type='hidden' id='links_<?php echo $dt->ID_CASH_TYPE; ?>' value='<?php echo $link; ?>' />
                        </p>
                     </div>
                     <br clear="all" />
                    <?php echo transaction_type($dt->ID_CASH_TYPE); ?>
                </li>
            <?php } ?>	
            <li style="background:#f6f6f6">
            	<p class='link1' style="float:left">
                	<b>TOTAL</b>
                </p>
				<?php
                $q_total 	= $db->query("SELECT 
											SUM(a.PAID) JUMLAHNYA 
										  FROM 
											".$tpref."cash_flow a,
											".$tpref."cash_type b
										  WHERE 
										  	a.ID_CASH_TYPE = b.ID_CASH_TYPE AND
											b.IN_OUT = '".$in_out."' AND 
										  	(a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").") ".$condition." ");
                $dt_total	= $db->fetchNextObject($q_total); 
                    if(empty($dt_total->JUMLAHNYA)){
                        $total = 0;	
                    }else{
                        $total = $dt_total->JUMLAHNYA;	
                    }
                ?>
                <p class='buttons1' style="float:right; margin-right:5px;">
                    <b><?php echo money("Rp.",$total); ?></b>
                </p>
                <br clear="all" />
            </li>
            <br clear="all" />
        </ul>
    </div>
