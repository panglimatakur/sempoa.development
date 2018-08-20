<?php
	//ANDROID
	if(!is_dir($new_project."/Android"))				{ mkdir($new_project."/Android",0777); 					}
	
	//ANDROID SRC
	if(!is_dir($new_project."/Android/src"))			{ mkdir($new_project."/Android/src",0777); 				}
	if(!is_dir($new_project."/Android/src/com"))		{ mkdir($new_project."/Android/src/com",0777); 			}
	if(!is_dir($new_project."/Android/src/com/".$app))	{ mkdir($new_project."/Android/src/com/".$app,0777); 	}
	if(!is_dir($new_project."/Android/src/com/".$app."/coin"))	{ 
		mkdir($new_project."/Android/src/com/".$app."/coin",0777); 		
	}

	//ANDROID ASSETS
	if(!is_dir($new_project."/Android/assets"))			{ mkdir($new_project."/Android/assets",0777); 			}
	if(!is_dir($new_project."/Android/assets/www"))		{ mkdir($new_project."/Android/assets/www",0777); 		}
	if(!is_dir($new_project."/Android/assets/www/img"))	{ mkdir($new_project."/Android/assets/www/img",0777); 	}
	if(!is_dir($new_project."/Android/assets/www/js"))	{ mkdir($new_project."/Android/assets/www/js",0777); 	}
	//ANDROID RES
	if(!is_dir($new_project."/Android/res")){ mkdir($new_project."/Android/res",0777); }
	$android_res_folder 		= $new_project."/Android/res/";
	
	$info	   = pathinfo($_FILES['icon']["name"]); 
	$type 	   = $info['extension'];
	move_uploaded_file($_FILES['icon']["tmp_name"],"icon.".$type);
	$folder	   = array();
	$size	   = array();
	$folder[1] = "mipmap-ldpi";
	$size[1]   = "36";
	$folder[2] = "mipmap-hdpi";
	$size[2]   = "72";
	$folder[3] = "mipmap-mdpi";
	$size[3]   = "48";
	$folder[4] = "mipmap-xhdpi";
	$size[4]   = "96";
	$folder[5] = "mipmap-xxhdpi";
	$size[5]   = "144";
	$folder[6] = "mipmap-xxxhdpi";
	$size[6]   = "192";
	$t = 0;

		while($t<7){
			$t++;
				if(!is_dir($android_res_folder.$folder[$t])){ mkdir($android_res_folder.$folder[$t],0777); }
				resizeupload("icon.".$type,$android_res_folder.$folder[$t],$size[$t]);
		}

		copy("ic_launcher.".$type,$new_project."/Android/ic_launcher-web.".$type);
		resizeupload($new_project."/Android/ic_launcher-web.".$type,$new_project."Android",512);

		copy("~core/web/loader.gif",$new_project."/Android/assets/www/img/loader.gif");
		copy("~core/web/index.html",$new_project."/Android/assets/www/js/index.html");
		copy("~core/web/cordova.js",$new_project."/Android/assets/www/js/cordova.js");
		copy("~core/web/jquery.min.js",$new_project."/Android/assets/www/js/jquery.min.js");
		copy("~core/web/index.html",$new_project."/Android/assets/www/index.html");
		copy("~core/android/AndroidManifest.xml",$new_project."/Android/AndroidManifest.xml");
		copy("~core/android/COIN.JAVA",$new_project."/Android/src/com/".$app."/coin/".$app."COIN.JAVA");

		//WRITE ANDROID INDEX HTML
		$android_web = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
        <script language="javascript" src="js/cordova.js"></script>
        <script language="javascript" src="js/jquery.min.js"></script>
    </head>
    <script language="javascript">
			var ref = window.open(\'https://sempoa.biz/community/'.$id_project.'\',\'_blank\', \'enableViewportScale=yes\',\'location=no\');
    </script>
</html>';
		file_put_contents($new_project."/Android/assets/www/index.html",$android_web);
		
		$android_manifest = '<?xml version="1.0" encoding="utf-8"?>
<manifest xmlns:android="http://schemas.android.com/apk/res/android"
    package="com.'.$app.'.coin"
    android:versionCode="1"
    android:versionName="1.0" >
   <uses-sdk
        android:minSdkVersion="8"
        android:targetSdkVersion="18" />
<supports-screens android:largeScreens="true" android:normalScreens="true" android:smallScreens="true" android:resizeable="true" android:anyDensity="true"/>
 
<uses-permission android:name="android.permission.CAMERA" />
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION" />
<uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
<uses-permission android:name="android.permission.ACCESS_LOCATION_EXTRA_COMMANDS"/>
<uses-permission android:name="android.permission.READ_PHONE_STATE" />
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.RECEIVE_SMS" />
<uses-permission android:name="android.permission.RECORD_AUDIO" />
<uses-permission android:name="android.permission.MODIFY_AUDIO_SETTINGS" />
<uses-permission android:name="android.permission.READ_CONTACTS" />
<uses-permission android:name="android.permission.WRITE_CONTACTS" />
<uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" />
<uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
<uses-permission android:name="android.permission.GET_ACCOUNTS" />
<uses-permission android:name="android.permission.BROADCAST_STICKY" />
    <uses-sdk
        android:minSdkVersion="8"
        android:targetSdkVersion="18" />
    <application
        android:allowBackup="true"
        android:icon="@drawable/ic_launcher"
        android:label="'.$project.' COIN"
        android:theme="@style/splashScreenTheme">
        <activity
            android:name="com.'.$app.'.coin.'.$new_project.'"
            android:label="'.$project.' COIN" 
            android:configChanges="orientation|keyboardHidden|keyboard|screenSize|locale"
            android:screenOrientation="portrait">
            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>
        </activity>
    </application>
</manifest>';
		file_put_contents($new_project."/Android/AndroidManifest.xml",$android_manifest);
		
		$android_src = 'package com.'.$app.'.coin;
import android.os.Bundle;
import android.view.Menu;
import org.apache.cordova.*;

import com.'.$app.'.coin.R;

public class '.$new_project.' extends DroidGap {
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
        super.init();
        super.appView.clearCache(true);
        super.loadUrl("file:///android_asset/www/index.html",3000); 
	}
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}

}
';
		file_put_contents($new_project."/Android/src/com/".$app."/coin/".$new_project.".java",$android_src);
		
?>