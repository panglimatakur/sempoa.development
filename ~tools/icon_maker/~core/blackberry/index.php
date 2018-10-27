<?php
	//BLACKBERRY
	if(!is_dir($new_project."/Blackberry"))	{ mkdir($new_project."/Blackberry",0777); }
	//BLACKBERRY ASSETS
	if(!is_dir($new_project."/Blackberry/www"))			{ mkdir($new_project."/Blackberry/www",0777); }
	//BLACKBERRY RES
	if(!is_dir($new_project."/Blackberry/www/res")){ mkdir($new_project."/Blackberry/www/res",0777); }
	if(!is_dir($new_project."/Blackberry/www/res/icon")){ mkdir($new_project."/Blackberry/www/res/icon",0777); }
	if(!is_dir($new_project."/Blackberry/www/res/icon/blackberry")){ mkdir($new_project."/Blackberry/www/res/icon/blackberry",0777); }
	if(!is_dir($new_project."/Blackberry/www/res/screen")){ mkdir($new_project."/Blackberry/www/res/screen",0777); }
	if(!is_dir($new_project."/Blackberry/www/res/screen/blackberry")){ mkdir($new_project."/Blackberry/www/res/screen/blackberry",0777); }
	$blackberry_res_folder	 	= $new_project."/Blackberry/www/res/";

	copy("~core/blackberry/config.xml",$new_project."/Blackberry/www/config.xml");
		//WRITE BLACKBERRY CONFIG XML
	$bb_content ='<?xml version="1.0" encoding="UTF-8"?>
<widget xmlns="http://www.w3.org/ns/widgets" xmlns:rim="http://www.blackberry.com/ns/widgets" version="1.0.0.0">
  <name>'.strtoupper($project).' COIN</name>
  <author>Sempoa</author>
  <description>'.strtoupper($project).' COIN</description>
  <license href="http://opensource.org/licenses/alphabetical"></license>
  <access subdomains="true" uri="file:///store/home" />
  <access subdomains="true" uri="file:///SDCard" />
  <access subdomains="true" uri="https://sempoa.biz" />
  <icon rim:hover="false" src="res/icon/blackberry/icon-80.png" />
  <icon rim:hover="true" src="res/icon/blackberry/icon-80.png" />
  <rim:loadingScreen backgroundColor="#CFCFCF" foregroundImage="res/screen/blackberry/screen-225.png" onFirstLaunch="true">
  <rim:transitionEffect type="fadeOut" /></rim:loadingScreen>
  <rim:orientation mode="portrait" />
  <content src="https://sempoa.biz/community/'.$id_project.'" />
  <rim:permissions>
    <rim:permit>use_camera</rim:permit>
    <rim:permit>read_device_identifying_information</rim:permit>
    <rim:permit>access_shared</rim:permit>
    <rim:permit>read_geolocation</rim:permit>
    <rim:permit>record_audio</rim:permit>
    <rim:permit>access_pimdomain_contacts</rim:permit>
  </rim:permissions>
</widget>';
	file_put_contents($new_project."/Blackberry/www/config.xml",$bb_content);
	copy("~core/blackberry/screen-225.png",$blackberry_res_folder."/screen/blackberry/screen-225.png");
	resizeupload("ic_launcher.".$type,$blackberry_res_folder."/icon/blackberry","80");
	rename($blackberry_res_folder."/icon/blackberry/ic_launcher.".$type,$blackberry_res_folder."/icon/blackberry/icon-80.".$type);
?>