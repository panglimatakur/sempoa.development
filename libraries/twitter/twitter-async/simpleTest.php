<?php
include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';
$consumer_key 		= 'uijMYkOx5Vq9YVfP8FsGZsFqZ';
$consumer_secret 	= 'izIi44IMLdCVQsDFcR0Gf5xG5hUkXqArMyCyyCxF5sGDEKcuPS';
$token 				= '2469683564-ofDXuWTiA8hpV3w4R55GkI5PIYijw3Kt4JwxjNZ';
$secret				= 'hLz0ejzwNmxQBLVVz6ZHZpp4DXWBC98D4UisA7TdS5zZm';
$twitterObj 		= new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth 	= new EpiTwitter($consumer_key, $consumer_secret);

$settings = array(
    'oauth_access_token' => "2469683564-ofDXuWTiA8hpV3w4R55GkI5PIYijw3Kt4JwxjN",
    'oauth_access_token_secret' => "hLz0ejzwNmxQBLVVz6ZHZpp4DXWBC98D4UisA7TdS5zZm",
    'consumer_key' => "uijMYkOx5Vq9YVfP8FsGZsFqZ",
    'consumer_secret' => "izIi44IMLdCVQsDFcR0Gf5xG5hUkXqArMyCyyCxF5sGDEKcuPS"
);
?>
<script> function viewSource() { 
	alert("tersting"); 
	document.getElementById('source').style.display = document.getElementById('source').style.display=='block'?'none':'block'; 
} 
</script>

<h1>Single test to verify everything works ok</h1>

<h2><a href="javascript:void(0);" onclick="viewSource();">View the source of this file</a></h2>
<div id="source" style="display:none; padding:5px; border: dotted 1px #bbb; background-color:#ddd;">
<?php //highlight_file(__FILE__); ?>
</div>

<hr>

<h2>Generate the authorization link</h2>
<?php //echo $twitterObjUnAuth->getAuthenticateUrl(); ?>

<hr>

<h2>Verify credentials</h2>
<?php
 // $creds = $twitterObj->get('/account/verify_credentials.json');
?>
<pre>
<?php //print_r($creds->response); ?>
</pre>

<hr>

<h2>Post status</h2>
<?php
//$url = $twitterObj->get('https://api.twitter.com/1.1/users/show.json?screen_name=Kikicalledtakur');
//print_r($url->response);

$status = $twitterObj->post('/statuses/update.json', array('status' => 'This a simple test from twitter-async at '.date('m-d-Y h:i:s')));
?>
<pre>
<?php print_r($status->response); ?>
</pre>

