<?php
	require_once("./config.php");

	$output = shell_exec('curl -ik -X POST "https://graph.facebook.com/v2.6/me/subscribed_apps?access_token='.$config['fb-config']['token'].'"');
	echo $output;