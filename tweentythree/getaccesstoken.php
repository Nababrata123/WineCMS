<?php
// Load dependencies
include_once 'HTTP/OAuth/Consumer.php';
require_once 'HTTP/OAuth.php';
require_once 'HTTP/OAuth/Consumer/Request.php';
require_once 'HTTP/Request2.php';

// Set up HTTP request
// $httpRequest = new HTTP_Request2;
// $httpRequest->setHeader('Accept-Encoding', '.*');
// $request = new HTTP_OAuth_Consumer_Request;
// $request->accept($httpRequest);


$consumer_key = '21314640-VWONmzMhTlIABd9S7NmA';
$consumer_secret = 'WHQoRFdDpPogHtmkiwl2RO000rG32qAJmzdoNq7UQsoSAobgxY';
$domain = "api.visualplatform.net";

// Set up OAuth consumer
$consumer = new HTTP_OAuth_Consumer($consumer_key, $consumer_secret);
$consumer->accept($request);

$response = $consumer->sendRequest("http://".$domain."/oauth/access_token", array("oauth_verifier" => $GET['oauth_verifier']), "GET");
$data     = $response->getDataFromBody();

echo "<Pre>";print_r($response);exit;


if (empty($data['oauth_token']) || empty($data['oauth_token_secret'])) {
    throw new HTTP_OAuth_Consumer_Exception_InvalidResponse(
       'Failed getting token and token secret from response', $response
    );
}

print_r($data);

$consumer->setToken($data['oauth_token']);
$consumer->setTokenSecret($data['oauth_token_secret']);


// 5. Save credentials to config file
$handle = @fopen("visualplatform.config.php", "w");
$_conf = array(
                'domain'       => $data['domain'],
                'user_id'      => $data['user_id'],
                'key'          => $consumer->getKey(),
                'secret'       => $consumer->getSecret(),
                'token'        => $consumer->getToken(),
                'token_secret' => $consumer->getTokenSecret()
        );
fwrite($handle, '<? $visualplatform_config = unserialize(\'' . serialize($_conf) . '\'); ?>');
fclose($handle);
