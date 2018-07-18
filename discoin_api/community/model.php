<?php
session_start();
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || 
   (!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765")) {
	define('mainload','SEMPOA',true); 
	include("../../includes/config.php");
	include("../../includes/classes.php");
	include("../../includes/functions.php");
	include("../../includes/declarations.php");

	$direction 			= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: "";
	$id_merchant 		= isset($_REQUEST['id_merchant']) 	? $_REQUEST['id_merchant'] 	: "";
	$id_customer 		= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer'] 	: "";
	$callback 			= 'mycallback';
	$callback 			= isset($_REQUEST['mycallback']) 	? $_REQUEST['mycallback'] 	: "";
	$lastID 			= isset($_REQUEST['lastID']) 		? $_REQUEST['lastID'] 		: "";
	$result['msg_log'] 	= "";
	$result['io_log']  	= "";
	
	if(empty($_SESSION['sidkey'])){
		$data 			 = relogin($id_merchant,$id_customer);
		$result['io_log'] 	= $data['io_log'];
		$result['msg_log']  = $data['msg_log'];
	}
	
	if(!empty($id_customer)){
	
		if(!empty($direction) && $direction == "load"){
			$community_client		= array();
			$q_info_merchant 		= $db->query("SELECT CLIENT_NAME,COLOUR FROM ".$tpref."clients 
												  WHERE ID_CLIENT='".$id_merchant."'");
			@$dt_info_merchant		= $db->fetchNextObject($q_info_merchant);
			@$nm_merchant 			= $dt_info_merchant->CLIENT_NAME;
			if(!empty($dt_info_merchant->COLOUR)){
				@$colour			= explode(";",$dt_info_merchant->COLOUR);
				@$colour_1			= "style='background:".$colour[0]."'";
				@$result['colour_1']= $colour[0];
			}
			
			
			$all_discount 		= $db->recount("SELECT * FROM ".$tpref."clients_discounts WHERE ID_CLIENT = '".$id_merchant."' AND DISCOUNT_STATUS='3'");		
			$result["content"]  = '
        <div class="panel blank-panel" style="background:none">
            <div class="panel-heading" style="padding:0">
                <div class="panel-options">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#tab-1" aria-expanded="true">
                            	<i class="fa fa-building-o"></i> Komunita Diskon
                            </a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#tab-2" aria-expanded="false">
                            	<i class="fa fa-map-marker"></i> Peta Komunitas
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="panel-body" style="padding:0">
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">';
		$result['content'] .= $call->inc("discoin_api/community/includes","discount_list.php");
		$result['content'] .='
					</div>
					<div id="tab-2" class="tab-pane">
						<div id="map_canvas" style="width:100%; height:800px;"></div>
					</div>
				</div>
			</div>';
			
								
		}
	}
	echo $callback.'('.json_encode($result).')';
}
?>