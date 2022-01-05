<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Push_notifications {

    private $CI;
	
    // (Android)API access key from Google API's Console.
    private static $API_ACCESS_KEY = PUSH_API_ACCESS_KEY;
    
    // (iOS) Private key's passphrase.
    private static $passphrase = PUSH_PASS_PHRASE;
    
    // (Windows Phone 8) The name of our push channel.
    private static $channelName = PUSH_CHANNEL_NAME;
    
    
    public function __construct() {

        //parent::__construct();
        $this->CI = &get_instance();        
    }

    public function test_notification($user_details,$array)
    {

        $url = 'https://fcm.googleapis.com/fcm/send';
         
        $job_id=$user_details->job_id;
      
        $message = array(
            'title'             => $array['title'],
            'body'              => $array['body'],
            'content_available' => true,
            'priority'          => 'high',
            'sound'             => 'default'
        );      

        $headers = array(
            'Authorization: key=' .self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );
               
        $data = array(  
            "job_id" => $job_id
        );
        $fields = array(
            'to' => $user_details->device_token,
            'notification' => $message,
            'data'          => $data,
            'priority' => 'high'

        );  


        //return $this->useCurl($url, $headers, json_encode($fields));
        $response=$this->useCurl($url, $headers, json_encode($fields));
        //echo "<pre>";
        //print_r($response);die;
    }
    public function send_approve_job_notification($user_details,$array)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
         
        
      
        $message = array(
            'title'             => $array['title'],
            'body'              => $array['body'],
            'content_available' => true,
            'priority'          => 'high',
            'sound'             => 'default'
        );      

        $headers = array(
            'Authorization: key=' .self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );
               
        $data = array(  
            "job_id" => $array['job_id']
        );
        $fields = array(

            'to' => $user_details->device_token,
            'notification' => $message,
            'data'          => $data,
            'priority' => 'high'

        );  


        //return $this->useCurl($url, $headers, json_encode($fields));
        $response=$this->useCurl($url, $headers, json_encode($fields));
        //echo "<pre>";
        //print_r($response);die;
    }
    public function send_cancelled_job_notification($user_details,$array)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
         
        
      
        $message = array(
            'title'             => $array['title'],
            'body'              => $array['body'],
            'content_available' => true,
            'priority'          => 'high',
            'sound'             => 'default'
        );      

        $headers = array(
            'Authorization: key=' .self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );
               
        $data = array(  
            "job_id" => $array['job_id']
        );
        $fields = array(
            'to' => $user_details->device_token,
            'notification' => $message,
            'data'          => $data,
            'priority' => 'high'

        );  


        //return $this->useCurl($url, $headers, json_encode($fields));
        $response=$this->useCurl($url, $headers, json_encode($fields));
    }
    public function send_job_info_notification($user_details,$array)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
         
        
      
        $message = array(
            'title'             => $array['title'],
            'body'              => $array['body'],
            'content_available' => true,
            'priority'          => 'high',
            'sound'             => 'default'
        );      

        $headers = array(
            'Authorization: key=' .self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );
               
        $data = array(  
            "job_id" => $array['job_id']
        );
        $fields = array(
            'to' => $user_details->device_token,
            'notification' => $message,
            'data'          => $data,
            'priority' => 'high'

        );  


        //return $this->useCurl($url, $headers, json_encode($fields));
        $response=$this->useCurl($url, $headers, json_encode($fields));
    }
    public function send_publish_job_notification($zone_details,$array)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
         
        
      
        $message = array(
            'title'             => $array['title'],
            'body'              => $array['body'],
            'content_available' => true,
            'priority'          => 'high',
            'sound'             => 'default'
        );      

        $headers = array(
            'Authorization: key=' .self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );
               
        $data = array(  
            "job_id" => $array['job_id']
        );
        $zone_id=$zone_details->zone;
        $zone_name=$zone_details->zone_name;
        if(preg_match('/[^A-Z]/i',$zone_name))
        {
            // Replaces all spaces or special characters with underscore.
            $zone=preg_replace('/[^A-Z]/i', '_', $zone_name); 
            
        }
        else
        {
            $zone=$zone_name;
            
        }
        $fields = array(
            'to' => "/topics/job_publish_".$zone."_".$zone_id,
            'notification' => $message,
            'data'          => $data,
            'priority' => 'high'

        );  


        //return $this->useCurl($url, $headers, json_encode($fields));
        $response=$this->useCurl($url, $headers, json_encode($fields));
    }
    public function send_republish_job_notification($user_details,$array)
    {
                $url = 'https://fcm.googleapis.com/fcm/send';
         
        
      
        $message = array(
            'title'             => $array['title'],
            'body'              => $array['body'],
            'content_available' => true,
            'priority'          => 'high',
            'sound'             => 'default'
        );      

        $headers = array(
            'Authorization: key=' .self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );
               
        $data = array(  
            "job_id" => $array['job_id']
        );
        
        $fields = array(
            'to' => $user_details->device_token,
            'notification' => $message,
            'data'          => $data,
            'priority' => 'high'

        );  


        //return $this->useCurl($url, $headers, json_encode($fields));
        $response=$this->useCurl($url, $headers, json_encode($fields));

    }
    public function send_start_or_finish_notification($user_details,$array)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
         
        
      
        $message = array(
            'title'             => $array['title'],
            'body'              => $array['body'],
            'content_available' => true,
            'priority'          => 'high',
            'sound'             => 'default'
        );      

        $headers = array(
            'Authorization: key=' .self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );
               
        $data = array(  
            "taster_id" => $array['taster_id']
        );
        
        $fields = array(
            'to' => $user_details->device_token,
            'notification' => $message,
            'data'          => $data,
            'priority' => 'high'

        );  


        //return $this->useCurl($url, $headers, json_encode($fields));
        $response=$this->useCurl($url, $headers, json_encode($fields));
    }
    public function send_early_job_notifications($userDetails,$array)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
         
        
      
        $message = array(
            'title'             => $array['title'],
            'body'              => $array['body'],
            'content_available' => true,
            'priority'          => 'high',
            'sound'             => 'default'
        );      

        $headers = array(
            'Authorization: key=' .self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );
               
        $data = array(  
            "job_id" => $array['job_id']
        );
        
        $fields = array(
            'to' => $userDetails->device_token,
            'notification' => $message,
            'data'          => $data,
            'priority' => 'high'

        );  


        //return $this->useCurl($url, $headers, json_encode($fields));
        $response=$this->useCurl($url, $headers, json_encode($fields));
    }
    public function job_notifications_between_two_hour($userDetails,$array)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
         
        
      
        $message = array(
            'title'             => $array['title'],
            'body'              => $array['body'],
            'content_available' => true,
            'priority'          => 'high',
            'sound'             => 'default'
        );      

        $headers = array(
            'Authorization: key=' .self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );
               
        $data = array(  
            "job_id" => $array['job_id']
        );
        
        $fields = array(
            'to' => $userDetails->device_token,
            'notification' => $message,
            'data'          => $data,
            'priority' => 'high'

        );  


        //return $this->useCurl($url, $headers, json_encode($fields));
        $response=$this->useCurl($url, $headers, json_encode($fields));
    }
    // Sends Push notification for Android users
    public function android($data, $reg_id, $device_type) {

		//$url = 'https://android.googleapis.com/gcm/send';
		$url = 'https://fcm.googleapis.com/fcm/send';
        
		
		
		$message = array(
            'title' 			=> $data['mtitle'],
			'body' 				=> $data['mdesc'],
			'content_available' => true,
			'priority' 			=> 'high',
			'sound' 			=> 'default'
        );
		
		$mtitle = $data['mtitle'];
		
		$title_array = explode('-', $data['mtitle']);
		
		$item_type 	= $title_array[1];
		$item_id	= $title_array[2];
		
		if($device_type == 'ios')
			$message = array(
				'title' 			=> $title_array[0],
				'body' 				=> $data['mdesc'],
				'content_available' => true,
				'priority' 			=> 'high',
				'sound' 			=> 'default'
			);
		else
			$message = array(
				'title' 			=> $mtitle,
				'body' 				=> $data['mdesc'],
				'content_available' => true,
				'priority' 			=> 'high',
				'sound' 			=> 'default'
			);		

    	$headers = array(
    		'Authorization: key=' .self::$API_ACCESS_KEY,
    		'Content-Type: application/json'
    	);
        
        if (is_array($reg_id)) {
            $registration_ids = $reg_id;
        } else {
             $registration_ids = array($reg_id);
		}

    	/*$fields = array(
    		'registration_ids' => $registration_ids,
    		'data' => $message,
            'priority' => 'high'
		);
		$fields = array(
    		'registration_ids' => $registration_ids,
    		'notification' => $message,
            'priority' => 'high'
    	);
		*/
		
		$data = array(	'content_type'=>$item_type, 
						'content_id'=>$item_id
					 );
		
		if($device_type == 'ios')
			$fields = array(
				'registration_ids' => $registration_ids,
				'notification' => $message,
				'data'			=> $data,
				'priority' => 'high'
			);			
		else
			$fields = array(
				'registration_ids' => $registration_ids,
				'data' => $message,
				'priority' => 'high'
			);	


    	return $this->useCurl($url, $headers, json_encode($fields));
    }
    
    // Sends Push's toast notification for Windows Phone 8 users
    public function WP($data, $uri) {
    	$delay = 2;
    	$msg =  "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
    			"<wp:Notification xmlns:wp=\"WPNotification\">" .
    			"<wp:Toast>" .
    			"<wp:Text1>".htmlspecialchars($data['mtitle'])."</wp:Text1>" .
    			"<wp:Text2>".htmlspecialchars($data['mdesc'])."</wp:Text2>" .
    			"</wp:Toast>" .
    			"</wp:Notification>";
    
    	$sendedheaders =  array(
    			'Content-Type: text/xml',
    			'Accept: application/*',
    			'X-WindowsPhone-Target: toast',
    			"X-NotificationClass: $delay"
    	);
    
    	$response = $this->useCurl($uri, $sendedheaders, $msg);
    
    	$result = array();
    	foreach(explode("\n", $response) as $line) {
    		$tab = explode(":", $line, 2);
    		if (count($tab) == 2)
    			$result[$tab[0]] = trim($tab[1]);
    	}
    
    	return $result;
    }
    
    // Sends Push notification for iOS users
    public function iOS($data, $devicetoken) {
    	$deviceToken = $devicetoken;
    	$ctx = stream_context_create();
    	// ck.pem is your certificate file
    	stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
    	stream_context_set_option($ctx, 'ssl', 'passphrase', self::$passphrase);
    	// Open a connection to the APNS server
    	$fp = stream_socket_client(
    			'ssl://gateway.sandbox.push.apple.com:2195', $err,
    			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
    	if (!$fp)
    		exit("Failed to connect: $err $errstr" . PHP_EOL);
    		// Create the payload body
    		$body['aps'] = array(
    				'alert' => array(
    						'title' => $data['mtitle'],
    						'body' => $data['mdesc'],
                            'sound' => 'default',
    			 ),
    				//'sound' => 'default'
    		);
    		// Encode the payload as JSON
    		$payload = json_encode($body);
    		// Build the binary notification
    		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
    		// Send it to the server
    		$result = fwrite($fp, $msg, strlen($msg));
    
    		// Close the connection to the server
    		fclose($fp);
    		if (!$result)
    			return 'Message not delivered' . PHP_EOL;
    		else
    			return 'Message successfully delivered' . PHP_EOL;
    }
    
    // Curl
    private function useCurl($url, $headers, $fields = null) {
    	// Open connection
    	
    	$ch = curl_init();
    	if ($url) {
    		// Set the url, number of POST vars, POST data
    		curl_setopt($ch, CURLOPT_URL, $url);
    		curl_setopt($ch, CURLOPT_POST, true);
    		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    		// Disabling SSL Certificate support temporarly
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		if ($fields) {
    			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    		}
    
    		// Execute post
    		$result = curl_exec($ch);
    		if ($result === FALSE) {
    			die('Curl failed: ' . curl_error($ch));
    		}
    
    		// Close connection
    		curl_close($ch);

    		return $result;
    	} 

    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */