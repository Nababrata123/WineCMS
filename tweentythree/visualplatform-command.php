<?php
# Instructions:
#
# Requires preauthentication through visualplatform-authenticate.php
#
# Call the script:
#  ./visualplatform-command.php <endpoint> <param1> <value1> <param2> <value2>
#
# For example
#  ./visualplatform-command.php /api/echo iam here title "My title"
#
# This sample also shows how to build multipart requests and upload
# files to 23 Video through PHP.

error_reporting(E_ALL);

// Get key/token from args
//$args = $_SERVER['argv'];
$endpoint = '/api/photo/get-upload-token';

/*$i = 2;
$params = array();
while ($i<sizeof($args)) {
  $k = $args[$i];
  $v = $args[$i+1];
  $params[$k] = $v;
  $i += 2;
}*/
$params = array();
// Add in the option of outputting php
$params['format']='php';
//$params['file'] = 'test.jpg';
$params['user_id'] = '20312345';
$params['return_url'] = '';
$params['title'] = 'Test';


if (!isset($params['format'])) {
  $params['format'] = 'xml';
}
if ($params['format']=='php') {
   $output_php_p = true;
   $params['format'] = 'json';
   $params['raw'] = '1';
} else {
   $output_php_p = false;
}

// Load dependencies
include_once 'HTTP/OAuth/Consumer.php';
require_once 'HTTP/OAuth.php';
require_once 'HTTP/OAuth/Consumer/Request.php';
require_once 'HTTP/Request2.php';

// Set up HTTP request
$httpRequest = new HTTP_Request2;
$httpRequest->setHeader('Accept-Encoding', '.*');

// Handle file uploads
if (isset($params['file'])) {
   // Content of multipart forms isn't signed according to the OAuth specs.
   // We handle this case by manually building the content of the multipart request
   // and then sending no params to the OAuth lib for signing.
   foreach ($params as $key => $val) {
     if ($key=='file') {
        $httpRequest->addUpload($key, $val);
     } else {
        $httpRequest->addPostParameter($key, $val);
     }
   }
   $params = array();
}
// config details
$visualplatform_config = array();
$visualplatform_config['key'] = '21314658-qYKwxLJlt8FlaMz3OCkA';
$visualplatform_config['secret'] = '5Nb1KPZmo9JBFvsJqkByGazjNvhdRovzlr8HJfd36sBHNNzfdg	';
$visualplatform_config['token'] = '83753-Wak8bEwLDtW5yzY4ci5e';
$visualplatform_config['token_secret'] = 'MKByA6hpM63NfjTnqEDMtjbF8tpbCLhGdrcKHy8R4ZceByth6Q';
$visualplatform_config['domain'] = 'r6frpp9k.videomarketingplatform.co';
// Set up OAuth consumer
$request = new HTTP_OAuth_Consumer_Request;
$request->accept($httpRequest);
$consumer = new HTTP_OAuth_Consumer($visualplatform_config['key'], $visualplatform_config['secret'], $visualplatform_config['token'], $visualplatform_config['token_secret']);
$consumer->accept($request);

// Make request
$response = $consumer->sendRequest("http://" . $visualplatform_config['domain'] . $endpoint, $params, "POST");
$data = $response->getBody();

if ( !$output_php_p ) {
  print($data);
} else {
  print_r(json_decode($data));
}

?>
