<?php
			if(!empty($all_discount) && $all_discount > 0){ 
					//COMMUNITY LIST
					$str_list_comm	= "SELECT 
											a.ID_CLIENT,
											b.ID_COMMUNITY,
											b.NAME
										FROM 
											".$tpref."communities_merchants a, 
											".$tpref."communities b 
										WHERE 
											a.ID_COMMUNITY = b.ID_COMMUNITY AND 
											a.ID_CLIENT 	 = '".$id_merchant."' 
										GROUP BY b.ID_COMMUNITY
										ORDER BY 
											a.ID_COMMUNITY ASC";
					$q_list_comm	= $db->query($str_list_comm);
					$num_community	= $db->numRows($q_list_comm);	
					$result["content"] .= $str_list_comm;
					while($dt_comm	= $db->fetchNextObject($q_list_comm)){ 
						$community_name = $dt_comm->NAME; 
						$lastID 		= $dt_comm->ID_COMMUNITY;
						$result["content"] .= '
						<div class="w-box-header" '.@$colour_1.'>
							<i class="icsw16-white icsw16-companies"></i> 
							'.@$community_name.'
						</div>';
						
						
						$result["content"] .= '
						<div class="w-box-content span4"  style="padding:0">
							<table class="table table-striped " style="margin-bottom:0; width:100%">
								<tbody>';
								
								//MERCHANT LIST
								$str_merchant	=
								"SELECT 
									c.CLIENT_NAME,
									c.CLIENT_LOGO,
									a.ID_CLIENT,
									a.ID_COMMUNITY,
									a.ID_COMMUNITY_MERCHANT
								FROM 
									".$tpref."communities_merchants a,
									".$tpref."clients_discounts b,
									".$tpref."clients c
								WHERE 
									a.ID_CLIENT     	= b.ID_CLIENT 	AND
									a.ID_CLIENT     	= c.ID_CLIENT 	AND
									b.DISCOUNT_SEGMENT 	= 'community' 	AND
									a.ID_COMMUNITY 		= '".$lastID."' AND 
									a.ID_CLIENT 	   != '1' 
								GROUP BY b.ID_CLIENT
								ORDER BY a.ID_COMMUNITY_MERCHANT ASC";
								$result['content'] .= $str_merchant;
								$q_merchant = $db->query($str_merchant);
								while($dt_merchant	= $db->fetchNextObject($q_merchant)){
									$map_discount_content = "";
									@$merchant_comm	= $dt_merchant->ID_COMMUNITY;
									@$merchant_id 	= $dt_merchant->ID_CLIENT;
									@$merchant_name	= $dt_merchant->CLIENT_NAME;
									@$merchant_logo = $dt_merchant->CLIENT_LOGO;
									if(is_file($basepath."/files/images/logos/".$merchant_logo)){
										$logo_path = $dirhost."/files/images/logos/".$merchant_logo;	
									}else{ 
										$logo_path = $dirhost."/files/images/no_image.jpg";	
									}
									
									//DISCOUNT LIST
									$disc_str 		="SELECT * 
													  FROM ".$tpref."clients_discounts 
													  WHERE 
															ID_CLIENT = '".$merchant_id."' AND 
															DISCOUNT_SEGMENT = 'community' AND
															DISCOUNT_STATUS = '3'";
									@$q_discount_2 	= $db->query($disc_str);
									@$num_discount	= $db->numRows($q_discount_2);
									if($num_discount > 0){
									$community_client[] = $merchant_id;
														
																			
									$result["content"] .= '
									  <tr id="tr_'.@$dt_merchant->ID_COMMUNITY_MERCHANT.'">
										<td width="127" style="padding:10px">
											<img src="'.$logo_path.'" class="thumbnail" style="width:100%">
										</td>
										<td width="835">
											<span class="code" style="font-weight:bold">'.@$merchant_name.'</span>
											<br />
											
											<div class="merchant-discounts">
												<b>Diskon Komunitas</b><br />
												<div class="merchant-discount-list">
													<div class="btn-group">';
														while($dt_discount_2 = $db->fetchNextObject($q_discount_2)){
														@$id_discount	= $dt_discount_2->ID_DISCOUNT;
														$discount		= $dt_discount_2->DISCOUNT;
														@$id_pattern	= $dt_discount_2->ID_DISCOUNT_PATTERN;
														@$pattern 		= $db->fob("DESCRIPTION",
																						$tpref."discount_patterns",
																					"WHERE 
																					 	ID_DISCOUNT_PATTERN = 
																					 	'".$id_pattern."'");
														$discount_content = '
															<a href="javascript:void()" class="btn btn-xs btn-warning" 
															   onclick="view_discount(\''.$id_discount.'\')">
															   <b>'.$discount.'%</b>
															   '.$pattern.'
															</a>';	
									$result["content"] .= $discount_content;
														}
                                                    	$map_discount_content .=  $discount_content;
									$result["content"] .= 
													'</div>
												</div>
											</div>
												
											<br />
											<a href="javascript:void()" class="btn btn-primary" onclick="enter_merchant(\''.@$merchant_comm.'\',\''.$merchant_id.'\')" id="id_cs">
												<i class="icsw16-walking-man icsw16-white" style="padding:0;margin:0"></i>
												Kunjungi
											</a>
										</td>
									  </tr>';
									  
										//MERCHANT LOCATION LIST
										$q_client_map		=	$db->query("SELECT * 
																			FROM 
																				".$tpref."clients_maps 
																			WHERE 
																				ID_CLIENT='".$merchant_id."' 
																			ORDER BY ID_CLIENT_MAP ASC");
										while($dt_client_map =	$db->fetchNextObject($q_client_map)){
											$marker_icon_path 	= ""; 	
											if(is_file($basepath."/files/images/logos/".$merchant_logo)){
												$marker_icon_path = $dirhost."/files/images/logos/".$merchant_logo;	
											}
											@$coord 			= explode(",",$dt_client_map->COORDINATES);
											@$coordinate[] 		= array($coord[0],$coord[1],@$dt_client_map->COORDINATE_DESCRIPTIONS,@$merchant_id,@$marker_icon_path,@$map_discount_content,$merchant_name);
										}
										$result['coordinate'] = @$coordinate;
										//END OF MERCHANT LOCATION LIST
									}
									//END OF DISCOUNT LIST
								 } 
								//END OF MERCHANT LIST
							$result["content"] .= '
								</tbody>
							</table>
						</div>';
					} 
					//END OF COMMUNITY LIST				
				$result["content"] .= '
					<br clear="all" />';          
			}
?>