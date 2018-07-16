<?php defined('mainload') or die('Restricted Access'); ?>
<?php
@$cur 	= isset($_REQUEST['cur']) ? $_REQUEST['cur'] : "";
if(!empty($pagesize)){
	$jmlperhalaman 	= $pagesize;
}else{
	$jmlperhalaman = 10;	
}
function phead($cur){
	global $jmlperhalaman;
	if($cur<1){
		$pg = 1; 
	} 
	else {
		$pg = $cur; 
	}
	$offset = (($pg * $jmlperhalaman) - $jmlperhalaman); 
	$result = $pg."|".$offset;
	return $result;
}

if(!empty($cur)){
	$pheadresult 	= explode("|",phead($cur));
	$pg 			= $pheadresult[0];
	$awal 			= $pheadresult[1];
	$limit 			= "LIMIT ".$awal.",".$jmlperhalaman;
}else{
	$limit = "LIMIT 0,".$jmlperhalaman;
}
function pfoot($query,$linkparam){
	global $jmlperhalaman;
	global $dirhost;
	global $cur;
	global $pg;
	
	
	$total_record   = mysql_num_rows(mysql_query($query));
	$total_halaman  = ceil($total_record / $jmlperhalaman);	
    $str = "
	<div class='pagination pagination-centered'>
		<ul>";

	$perhal=4;
	if($cur > 1){ 
		$prev = ($pg - 1); 
				$str .= "<li class='prev'>
							<a href='javascript:void()' title='Prev' onclick='paging(\"".$prev."\")'>&laquo; Prev</a>
							<input type='hidden' name='cur' value='".$prev."'>
						 </li>"; 
	}
	
	if($total_halaman<=10){
		$hal1 = 1;
		$hal2 = $total_halaman;
	}
	else{
		$hal1 = $cur-$perhal;
		$hal2 = $cur+$perhal;
	}
	
	if($cur<=5){ $hal1=1; }
	
	if($cur<$total_halaman){
		$hal2 = $cur+$perhal;
	}
	else{
		$hal2 = $cur;
	}
	if(empty($cur)){ $cur = 1; }
	
	for($i = $hal1; $i <= $hal2; $i++){ 
		if($cur == $i){ 
				$str .= "<li class='active'><a href='javascript:void()'>".$i."</a></li>"; 
		}
		else{ 
			if($i<=$total_halaman){
				$str .= "<li>
							<a href='javascript:void()' onclick='paging(\"".$i."\")'>".$i."</a>
						 </li>"; 
			}
		} 
	}
	
	if($cur < $total_halaman){ 
				$next = ($pg + 1); 
				$str .= "<li class='next'>
							<a href='javascript:void()' title='Next' onclick='paging(\"".$next."\")'>Next &raquo;</a>
						</li>"; 
	} 
	$str .="
		</ul>
	</div>
	<script language='javascript'>
		function paging(id_page){
			$('#cur').val(id_page);
			form_paging.submit();
		}
	</script>	
	<input type='hidden' id='cur' name='cur' value=''>";
	return $str;
}

?>
