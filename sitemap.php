<?php
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once('includes/config.php');
include_once('includes/classes.php');
include $call->inc("includes/classes","class.templates.php");
include_once('includes/functions.php');
include_once('includes/declarations.php');
header("Content-type: text/xml");
echo"<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset
      xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
      xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
      xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">";

function top_menu($id_parent){
	global $dirhost;
	global $db;
	@$child = $db->fob("ID_PAGE_DISCOIN","system_pages_discoin","WHERE ID_PARENT='".$id_parent."' AND STATUS='1'");
	if(!empty($child)){
		$qmenu 	= $db->query("SELECT * FROM system_pages_discoin WHERE ID_PARENT = '".$id_parent."' AND STATUS='1' ORDER BY SERI ASC");
		while($dtmenu = $db->fetchNextObject($qmenu)){ 
			$lpage 	 = "";
			if($dtmenu->IS_FOLDER == 1){
				$url_link = "javascript:void()";	
			}else{
				if($dtmenu->TYPE == "statis"){ $lpage = "statis/"; }
				$url_link = $dirhost."/website/".@$lpage."".$dtmenu->PAGE;	
			}
			if($dtmenu->IS_FOLDER != 1){
echo "
<url>
  <loc>".$url_link."</loc>
  <lastmod>2014-10-29T16:24:35+00:00</lastmod>
  <changefreq>always</changefreq>
  <priority>0.80</priority>
</url>";
			}
echo top_menu($dtmenu->ID_PAGE_DISCOIN);
		}
	}
}
?>

<?php
		$qtop_menu = $db->query("SELECT * FROM system_pages_discoin WHERE ID_PARENT = '0' AND POSITION ='top' AND STATUS='1' AND ID_PAGE_DISCOIN != '188' ORDER BY SERI");
		while($dtop_menu = $db->fetchNextObject($qtop_menu)){
			$id_parent = "";
			$lpage 	 = "";
			$id_parent = $dtop_menu->ID_PAGE_DISCOIN;
			if($dtop_menu->IS_FOLDER == 1){
				$url_link = "javascript:void()";	
			}else{
				if($dtop_menu->TYPE == "statis"){ $lpage = "statis/"; }
				$url_link = $dirhost."/website/".@$lpage."".$dtop_menu->PAGE;	
			}
			if($dtop_menu->IS_FOLDER != 1){
echo "
<url>
  <loc>".$url_link."</loc>
  <lastmod>2014-10-29T16:24:35+00:00</lastmod>
  <changefreq>always</changefreq>
  <priority>0.80</priority>
</url>";
			}
echo top_menu($id_parent);
		} ?>
<?php
		$str_client = " SELECT 
							a.CLIENT_APP 
						FROM 
							".$tpref."clients a, 
							".$tpref."client_discounts b
						WHERE 
							a.ID_CLIENT = b.ID_CLIENT AND
							a.ID_CLIENT != '1'
						GROUP BY b.ID_CLIENT
						ORDER BY 
							a.ID_CLIENT";
		$q_client = $db->query($str_client);
		while($dt_client = $db->fetchNextObject($q_client)){
			$url_link = $dirhost."/".$dt_client->CLIENT_APP.".coin";	
echo "
<url>
  <loc>".$url_link."</loc>
  <lastmod>2014-10-29T16:24:35+00:00</lastmod>
  <changefreq>always</changefreq>
  <priority>0.80</priority>
</url>";
		} ?>
<?php

echo "</urlset>";
?>