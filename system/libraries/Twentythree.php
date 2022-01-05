<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Calendar Class
 *
 * This class enables the creation of calendars
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/libraries/calendar.html
 */
class CI_Twentythree {

	public function twentythreeapi($endpoint, $params = array(), $method){

		// $endpoint = '/api/photo/get-upload-token';
		// $params = array();
		// Add in the option of outputting php
		$params['format']='php';
		//$params['file'] = 'test.jpg';
		// $params['user_id'] = '20312345';
		// $params['photo_id'] = '21403474';
		// $params['token'] = 'nZVtzDPfdtJk2L7FJxec555VFIFJK9UZ0GA0MQ3pfOnZYfdfNQj4ZWzoEdcP4avA';

		// if(!empty($parameters)){
		// 	$params['return_url'] = isset($parameters['return_url'])?$parameters['return_url']:'';
		// 	$params['title'] = isset($parameters['title'])?$parameters['title']:'';
		// 	$params['tags'] = isset($parameters['return_url'])?$parameters['return_url']:'';
		// 	$params['description'] = isset($parameters['description'])?$parameters['description']:'';
		// 	$params['include_unpublished_p'] = isset($parameters['include_unpublished_p'])?$parameters['include_unpublished_p']:'';
		// }


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
		$visualplatform_config['key'] = '21314640-VWONmzMhTlIABd9S7NmA';
		$visualplatform_config['secret'] = 'WHQoRFdDpPogHtmkiwl2RO000rG32qAJmzdoNq7UQsoSAobgxY';
		$visualplatform_config['token'] = '83800-r6IywacGfzzkLmoSTIxp';
		$visualplatform_config['token_secret'] = 'GJfWPjhaL7UzMTS8RH1aFTojbUQsynUOPJy7jRvxq4OSe59CvW';
		$visualplatform_config['domain'] = 'r6frpp9k.videomarketingplatform.co';
		// Set up OAuth consumer
		$request = new HTTP_OAuth_Consumer_Request;
		$request->accept($httpRequest);
		$consumer = new HTTP_OAuth_Consumer($visualplatform_config['key'], $visualplatform_config['secret'], $visualplatform_config['token'], $visualplatform_config['token_secret']);
		$consumer->accept($request);

		// Make request
		$response = $consumer->sendRequest("http://" . $visualplatform_config['domain'] . $endpoint, $params, $method);
		$data = $response->getBody();

		return json_decode($data);

		// if ( !$output_php_p ) {
		//   print($data);
		// } else {
		//   print_r(json_decode($data));
		// }
		// exit;
	}
}
