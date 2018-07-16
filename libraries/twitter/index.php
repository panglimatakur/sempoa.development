<?php
	require_once 'twitter.php';
	/* Call login link */
	twitterLogin();
	// Create object
	$tweet = new EpiTwitter($consumer_key, $consumer_secret, $access_token, $access_tokenSecret);
	// Set status message
	$tweetMessage = 'This is a tweet to my Twitter account via PHP.';	
	// Check for 140 characters
	if(strlen($tweetMessage) <= 140){
		echo "tetsong";
		// Post the status message
		$tweet->post('statuses/update', array('status' => $tweetMessage));
	}
?>
