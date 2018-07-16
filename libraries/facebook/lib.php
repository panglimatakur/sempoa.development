<?php
require $basepath.'/libraries/facebook/src/facebook.php';
$facebook = new Facebook(array('appId'  => $fb_api,'secret' => $fb_secret,));
?>
<div id="fb-root"></div>
<!--<div id="medsos_loader"></div>
<div id="status"></div>
<div id="message"></div>
<div id="result_friends"></div>

<a href='javascript:void()'  id="fb_button" onClick="Login()">
	<img src="<?php echo $dirhost; ?>/files/images/login-FB.png"/></div>
</a>
<br />
<a href='javascript:void()'  onClick="getUserInfo()">Get User Info</a> - 
<a href='javascript:void()'  onClick="getFriendList()">Get Friend List</a>-->
<script>
	window.fbAsyncInit = function() {
		FB.init({
		  appId      : '<?php echo $fb_api; ?>', // App ID
		  channelUrl : 'https://sempoa.biz/libraries/facebook/channel.html', // Channel File
		  status     : true, // check login status
		  cookie     : true, // enable cookies to allow the server to access the session
		  xfbml      : true  // parse XFBML
		});
		
		FB.Event.subscribe('auth.authResponseChange', function(response) {
			if (response.status === 'connected') {
				//SUCCESS
			}else if (response.status === 'not_authorized'){
				//FAILED
			} else{
				//document.getElementById("medsos_loader").innerHTML +=  "<br>Logged Out";
			}
		});	
	
	};
	
	//function fb_share('Kartu Member Digital BadAss Baby','Discoin BadAss Baby','http://sempoa.biz/files/images/logos/838e8-BadAss.png',"http://sempoa.biz/badassbaby.coin")
	function fb_share(message,title,imgsrc,direction,deskripsi) {
	 FB.ui({
		 method	: 'stream.publish',
		 message: message,
		 attachment: {
		   name: title,
		   caption: message,
		   media: [{  
				type	: "image", 
				src		: imgsrc,    
				href	: direction  // Go here if user click the picture
			}], 
		   description: (
			 deskripsi
		   ),
		   href: direction
		 }
	   },
	   function(response) {
		 if (response && response.post_id) {
		   //alert('Post was published.');
		 } else {
		   bootbox.alert('Terimakasih Sudah Berbagi...');
		 }
	   }); 
	   
 	}
  	/*function getUserInfo() {
	    FB.api('/me', function(response) {
	  	var str="<b>Name</b> : "+response.name+"<br>";
			  str +="<b>Link: </b>"+response.link+"<br>";
			  str +="<b>Username:</b> "+response.username+"<br>";
			  str +="<b>id: </b>"+response.id+"<br>";
			  str +="<b>Email:</b> "+response.email+"<br>";
			  str +="<input type='button' value='Get Photo' onclick='getPhoto();'/>";
			  str +="<input type='button' value='Logout' onclick='Logout();'/>";
			  document.getElementById("fb_button").innerHTML=str;
					
    	});
  	}*/
  // Load the SDK asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));

</script>

