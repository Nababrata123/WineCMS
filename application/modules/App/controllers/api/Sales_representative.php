<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Sales_representative extends REST_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	/**
	 *
	 * @var unknown_type
	 */


	public function __construct() {

        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['do_login_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['send_otp_get']['limit'] = 500; // 500 requests per hour per user/key

        $this->load->model('Sales_representative_model');
    }





	public function do_login_post() {



    	// Get the id parameter value

		$email = $this->post('email');
		$password = $this->post('password');
		$device_type = $this->post('device_type');
		$device_token = $this->post('device_token');
		




    	// If NULL, then check the id passed as users/:id

    	if ($email === NULL && $password === NULL)

    	{

    		$this->set_response([

          		'success' => FALSE,

          		'error' => 'Required details not found'

        	], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code



        	return false;

    	}
    	if ($email == NULL || $password == NULL)

    	{

    		$this->set_response([

          		'success' => FALSE,

          		'error' => 'Required details not found'

        	], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code



        	return false;

    	}

    	


		$customer = array();








    	// If email & password found, login via email

		if ($email !== NULL && $password !== NULL)

    	{

			$customer = $this->Sales_representative_model->get_sales_representative_login($email, $password);

			//echo "<pre>";
			//print_r($customer);die;
   		}


    	// If a user exists in the data store e.g. database

    	if (!empty($customer))

    	{

    		$cur_datetime = date('Y-m-d H:i:s');
    		$update_data = array(
    			
    			'last_login' => $cur_datetime,
    		);

    		if(isset($device_type) && $device_type != ''){
    			$update_data['device_type'] = $device_type;
    			
    			//$customer->device_type = $device_type;
    		}
    		if(isset($device_token) && $device_token != ''){
    			$update_data['device_token'] = $device_token;
    			
    			//$customer->device_token = $device_token;
    		}
    		

    		$this->Sales_representative_model->update('users', 'id',$customer->user_id, $update_data);

    		
    		//$customer->last_login = $cur_datetime;

    		$this->set_response([

    			'success' => TRUE,
				'data' => $customer

    		], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

    	}

    	else

    	{

    		$this->set_response([

    			'success' => FALSE,

    			'error' => 'Invalid login credentials'

    		], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

    	}

    }
    public function do_reset_pass_post() {



		// Get the id parameter value

		$email = $this->post('email');



    	// If NULL, then check the email

    	if ($email === NULL)

    	{

    		$this->set_response([

          		'success' => FALSE,

          		'error' => 'Required details not found'

        	], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code



        	return false;

    	}



		$customer = $this->Sales_representative_model->validate_data('users', 'email', $email,'sales_rep');

        

    	// If a user exists in the data store e.g. database

    	if ($customer)

    	{

			// Sent the email

			$data_to_insert = array(

				'customer_id' => $customer->id,

				'token' => md5(time()),

				'create_date' => date('Y-m-d H:i:s')

			);
            $user_id=base64_encode($customer->id);


			//$this->Sales_representative_model->save_password_log($data_to_insert);



			// Send Email to reset admin password starts

			$activation_link = BASE_URL.'recover_password/'.$data_to_insert['token'].'/'.$user_id;



			$this->load->library('mail_template');

			$this->mail_template->password_reset_email($activation_link, $email);

			///



    		$this->set_response([

    			'success' => TRUE,

    			'data' => 'Please check your email.'

    		], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

    	}

    	else

    	{

    		$this->set_response([

    			'success' => FALSE,

    			'error' => 'Invalid credentials.'

    		], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

    	}

	}

	public function get_profile_get() {



		$user_id = $this->get('user_id');
		$user_type = $this->get('user_type');



    	// get the Customer details

    	$details = $this->Sales_representative_model->get_user_details($user_id);
    	$meta=$this->Sales_representative_model->get_user_meta($user_id);
    	
    	$meta_array = json_decode(json_encode($meta), True);
    	foreach($meta_array as $meta_value)
    	{
    		if($meta_value['meta_key']=='phone')
    		{
    			$details->$meta_value['meta_key']=$meta_value['meta_value'];
    		}
    	}

    	// If a user exists in the data store e.g. database

    	if (!empty($details))

    	{

    		$this->set_response([

    			'success' => TRUE,

    			'data' => $details,

    		], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

    	}

    	else

    	{

    		$this->set_response([

    			'success' => FALSE,

    			'error' => 'Customer could not be found'

    		], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

    	}



    	return false;

    }
}



	




	








	




	



	




	

	



