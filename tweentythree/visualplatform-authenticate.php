
<?php

# Instructions:
#
# Register your application by following the instruction on 
#   http://community.23video.com/api/
#
# Call the script with your key and secret:
#  ./visualplatform-authenticate.php <consumer_key> <consumer_secret>
#
# Upon successful authentication, you'll get a visualplatform.config.php
# file with your OAuth credentials


// Get key/token from args
// $args = 'https://mvp.managed.center/tweentythree/';
$consumer_key = '21314640-VWONmzMhTlIABd9S7NmA';
$consumer_secret = 'WHQoRFdDpPogHtmkiwl2RO000rG32qAJmzdoNq7UQsoSAobgxY';
$domain = "api.visualplatform.net";

$oauth_callback = 'https://mvp.managed.center/tweentythree/';


// Load dependencies
include_once 'HTTP/OAuth/Consumer.php';
require_once 'HTTP/OAuth.php';
require_once 'HTTP/OAuth/Consumer/Request.php';
require_once 'HTTP/Request2.php';

// Set up HTTP request
$httpRequest = new HTTP_Request2;
$httpRequest->setHeader('Accept-Encoding', '.*');
$request = new HTTP_OAuth_Consumer_Request;
$request->accept($httpRequest);

// Set up OAuth consumer
$consumer = new HTTP_OAuth_Consumer($consumer_key, $consumer_secret);
$consumer->accept($request);

// 1. Get request token
$get = $consumer->getRequestToken("http://".$domain."/oauth/request_token", "oob", array(), "GET");
//echo "<pre>";
//print_r($consumer);die;
// 2. Build authorize url and redirect the user
$authorize_url = $consumer->getAuthorizeUrl("http://".$domain."/oauth/authorize");
echo("Please visit the following URL in your browser to authorize your application, then enter the 4 character security code when done:\n\n  " . $authorize_url . "\n\nVerification code: ");


?>
