<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Front_Controller {

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
	protected $data = array();

	public function __construct() {
        parent::__construct();
    }

	public function index() {

		//redirect('/');
		// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');


		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'home';


		$this->data['main_content'] = 'home';
		//echo "<pre>";
		//print_r($this->data);die;
    	$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}


	


	

	public function pages($page = null) {

		if ($page == null) {
			show_404();
		}

		$this->load->model('Public_model');
		$this->data['page'] = $this->Public_model->get_cms($page);

		if (empty($this->data['page'])) {
			show_404();
		}

		$this->data['page_title'] = $this->data['page']->title;
		$this->data['meta_description'] = $this->data['page']->content;
		//$this->data['meta_keyword'] = $this->data['page']->meta_keyword;

		$nocache = $this->input->get('nocache');
		if (isset($nocache) && $nocache <> "") {
			$this->output->delete_cache();
		} else {
			$this->output->cache(60);
		}


		$this->data['main_content'] = 'cms';
    	$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	public function login() {

		if ($this->session->userdata('is_customer_logged_in')) {
        	redirect('account/dashboard');
        } else {

			$this->data['page'] = new stdClass();
			$this->data['page']->page_type = 'login';
        	$this->data['page_title'] = $this->lang->line('auth_login_page_title');

			$this->data['redirect'] = $this->input->get('redirect');
			$this->data['main_content'] = 'login';
	    	$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
        }
	}

	/**
     * Login into account
     * Sets sesstion data
     */
    public function do_login() {

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'login';
    	$this->data['page_title'] = $this->lang->line('auth_login_page_title');
		$this->data['error'] = "<strong>Oh snap!!</strong> Invalid email / password or check subscription status and try submitting again.";

    	if ($this->session->userdata('is_customer_logged_in')) {
            redirect('account/dashboard');
        } else {

        	$email = $this->input->post('email');
			$password = $this->input->post('password');
			$redirect = $this->input->post('redirect');

            $this->form_validation->set_rules('email', $this->lang->line('auth_login_form_email_label'), 'required');
            $this->form_validation->set_rules('password', $this->lang->line('auth_login_form_password_label'), 'required');

            if ($this->form_validation->run() === true) {

            	$this->load->model('../../App/models/Customer_model');
				if ($this->processLogin($email, $password)) {

					if ($redirect) {
						redirect($redirect);
					} else {
						redirect('account/dashboard');
					}
				}
			}
			
			$this->data['redirect'] = $this->input->get('redirect');
			
			$this->data['main_content'] = 'login';
			$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
        }
	}
	
	/**
     *  Customer Forgot Password
     */
	public function forgot_password() {

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'forgot_password';
		$this->data['page_title'] = $this->lang->line('auth_forgot_password_page_title');

    	if ($this->input->server('REQUEST_METHOD') === 'POST') {

    		//form validation
    		$this->form_validation->set_rules('email', $this->lang->line('auth_forgot_form_email_label'), 'required');
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

    		//if the form has passed through the validation
    		if ($this->form_validation->run()) {

    			$email = $this->input->post('email');

    			$this->load->model('../../App/models/Customer_model');

				if ($customer = $this->Customer_model->validate_data('customers', 'email', $email)) {

    				$data_to_insert = array(
    					'customer_id' => $customer->id,
    					'token' => md5(time()),
    					'create_date' => date('Y-m-d H:i:s')
    				);

    				$this->Customer_model->save_password_log($data_to_insert);

    				// Send Email to reset admin password starts
    				$activation_link = BASE_URL.'recover_password/'.$data_to_insert['token'];
    				$to_email = $email;

    				$this->load->library('mail_template');
    				$this->mail_template->password_reset_email($activation_link, $to_email);


    				$this->session->set_flashdata('message_type', 'success');
    				$this->session->set_flashdata('message', $this->lang->line('auth_forgot_password_success_msg'));
    			} else {
    				$this->session->set_flashdata('message_type', 'danger');
    				$this->session->set_flashdata('message', $this->lang->line('auth_forgot_password_failure_msg'));
    			}
    		}

    		redirect('forgot_password');
    	}

		$this->data['main_content'] = 'forgot_password';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}


   /**
    *
    * @param unknown_type $enc_str
    */
   	public function recover_password ($encrypted_string) {

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'recover_password';
   		$this->data['page_title'] = $this->lang->line('auth_recover_password_page_title');

		$this->load->model('../../App/models/Customer_model');
		$this->load->library('encrypt');

        if ($this->input->server('REQUEST_METHOD') === 'POST') {

        	//form validation
        	$this->form_validation->set_rules('new_pwd', $this->lang->line('auth_recover_password_form_password_label'), 'trim|required|matches[re_new_pwd]');
        	$this->form_validation->set_rules('re_new_pwd', $this->lang->line('auth_recover_password_form_confirm_password_label'), 'trim|required');
        	$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

        	//if the form has passed through the validation
        	if ($this->form_validation->run()) {

        		$key = $this->input->post('key');
        		$customer_id = $this->encrypt->decode($key);
        		$pwd = md5($this->input->post('new_pwd'));

        		if ($this->Customer_model->update('customers', 'id', $customer_id, array('password' => $pwd))) {

        			$pass_info = $this->Customer_model->is_valid_data('customer_password_log', array('token' => $encrypted_string, 'customer_id' => $customer_id));
        			$this->Customer_model->update_password_log($pass_info->id, array('visited' => 1));

        			$this->session->set_flashdata('message_type', 'success');
        			$this->session->set_flashdata('message', $this->lang->line('auth_recover_password_success_msg'));
        		}
        		redirect('login');
        	}
        }

    	if ($encrypted_string) {

    		if ($this->data['link_info'] = $this->Customer_model->is_valid_data('customer_password_log', array('token' => $encrypted_string, 'visited' => 0))) {

    			if (empty($this->data['link_info'])) {
    				redirect('login');
    			}

    			// Check for validity of the link (1 hr)
    			$time = strtotime($this->data['link_info']->create_date);
    			$now = time();
    			if ($now - $time > RESET_PASSWORD_LINK_VALIDITY) {
    				$this->session->set_flashdata('message_type', 'danger');
    				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Allowed timelimit exceed.');
    				redirect('login');
    			}

    			$this->data['link_info']->enc_key = $this->encrypt->encode($this->data['link_info']->customer_id);

				$this->data['main_content'] = 'recover_password';
				$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);

    		} else {

    			$this->session->set_flashdata('message_type', 'danger');
    			$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Not a valid link.');

    			redirect('login');
    		}
    	} else {
    		redirect('login');
		}
		
	}


	
	/**
     *  Customer Sign-up
     */
	public function signup() {

		if ($this->session->userdata('is_customer_logged_in')) {
        	redirect('account/dashboard');
		}
		
		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'signup';
		$this->data['page_title'] = "Signup here";

		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			//echo "<pre>";
			//print_r($this->input->post());die;

    		//form validation
			$this->form_validation->set_rules('email', 'Email address', 'required|trim|valid_email'); //|is_unique[customers.email]
			$this->form_validation->set_rules('password', 'Password', 'required|trim');
			$this->form_validation->set_rules('conf_password', 'Confirm Password', 'required|trim|matches[password]');
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

			//print "<pre>"; print_r($this->form_validation); die;
    		//if the form has passed through the validation
    		if ($this->form_validation->run() !== false) {

    			$email = $this->input->post('email');
				$password = $this->input->post('password');

				$_signup = array ( 
					'signup' => array(
						'email' => $email,
						'password' => $password
					)
				);

				$this->session->set_userdata($_signup);
				redirect('signup_step2');
    		}
		}
		
		$this->data['main_content'] = 'signup';
		//$this->load->view('templates/public/template_1', $this->data);
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	/**
     *  Customer Sign-up
     */
	public function signup2() {

		if (!$this->session->userdata('signup')) {
            redirect('signup', 'refresh');
        }

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'signup2';
		$this->data['page_title'] = "Signup Step 2";

		
		if ($this->input->server('REQUEST_METHOD') === 'POST') {

    		//form validation
			$this->form_validation->set_rules('first_name', 'First name', 'required|trim');
			$this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
			$this->form_validation->set_rules('dob', 'Date of birth', 'required|trim');
			$this->form_validation->set_rules('height', 'Height', 'required|trim|numeric');
			$this->form_validation->set_rules('height_inc', 'Height', 'required|trim|numeric');
			$this->form_validation->set_rules('weight', 'Weight', 'required|trim|numeric');
			$this->form_validation->set_rules('city', 'City', 'required|trim');
			$this->form_validation->set_rules('country', 'Country', 'required|trim');
			$this->form_validation->set_rules('state', 'State', 'required|trim');

    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

    		//if the form has passed through the validation
    		if ($this->form_validation->run()) {

    			$first_name = $this->input->post('first_name');
				$last_name = $this->input->post('last_name');
				$dob = $this->input->post('dob');
				$gender = $this->input->post('gender');
				$height = $this->input->post('height');
				$height_inc = $this->input->post('height_inc');
				$weight = $this->input->post('weight');
				$city = $this->input->post('city');
				$state = $this->input->post('state');
				$country = $this->input->post('country');
				
				// Get data from 1st step
				$_signup_data = $this->session->userdata('signup');

				$_signup2 = array_merge($_signup_data, array ( 
					'first_name' => $first_name,
					'last_name' => $last_name,
					'dob' => $dob,
					'gender' => $gender,
					'height' => $height,
					'height_inc' => $height_inc,
					'weight' => $weight,
					'city' => $city,
					'state' => $state,
					'country' => $country,
				));

				$this->session->set_userdata('signup', $_signup2);

				redirect('signup_step3');
    		}
		}

		$this->data['main_content'] = 'signup2';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}


	/**
     *  Customer Sign-up
     */
	public function signup3() {

		if (!$this->session->userdata('signup')) {
            redirect('signup', 'refresh');
        }

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'signup3';
		$this->data['page_title'] = "Signup Step 3";

		
		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$payment_type = $this->input->post('payment_type');
			$code = $this->input->post('code');
			$subscription = $this->input->post('subscription');
			$token = $this->input->post('stripToken');
			//print "<pre>"; print_r($_POST); 
			
			//form validation
			$this->form_validation->set_rules('payment_type', 'Payment type', 'required|trim');
			/*if ($code == "" && $subscription == "") {
				$this->form_validation->set_rules('code', 'Referal code or Subscription', 'required|trim');
			}*/

			if ($payment_type == "ref") {
				$this->form_validation->set_rules('code', 'Referal code', 'exact_length[10]|callback_code_check');
			}

			if ($payment_type == "sub") {
				$this->form_validation->set_rules('subscription', 'Subscription', 'numeric|greater_than_equal_to[9]|less_than[10]');
			}
			
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

    		//if the form has passed through the validation
    		if ($this->form_validation->run()) {

				// Get data from 2st step
				$_signup_data = $this->session->userdata('signup');
				
				// Add customer to Stripe
				$_signup_data['customer_stripe_id'] = $this->stripe_customer_signup($_signup_data);
				
				// Insert to DB
				//print "<pre>"; print_r($_signup_data); die;
				$this->load->model('../../../modules/App/models/Customer_model');

				if ($customer_id = $this->Customer_model->register_customer($_signup_data)) {

					// If CODE is entered, update the code table
					if ($payment_type == "ref") {
						$this->Customer_model->use_code($code, $customer_id);

						$subscription_type = "yearly";
						$subscription_amt = 99.00;
						$subscription_status = 1;

						// Add SUBSCRIPTION History
						$this->Customer_model->customer_subscribe($customer_id, $subscription_type, $subscription_amt, $subscription_status);
						
						redirect('home/thankyou');
					} 

					
					// ADD, PAYPAL Subscription
					if ($payment_type == "sub") {

						// Paypal subscription
						//$this->paypal_subscription();
						
						// Stript subscription
						$subscription = $this->stripe_subscription($_signup_data['customer_stripe_id'], $token);
						
						$subscription_type = "monthly";
						$subscription_amt = 9.99;
						$subscription_status = 1;
						
						//print_r($subscription);
						// Add SUBSCRIPTION History
						$this->Customer_model->customer_subscribe($customer_id, $subscription_type, $subscription_amt, $subscription_status, $subscription->id);
						
						redirect('home/thankyou');
					}
					
					/*$this->session->set_flashdata('message_type', 'success');
    				$this->session->set_flashdata('message', "Thank you for signing up to PHIT. Please activate your email address before logging.");*/
    			} else {
    				$this->session->set_flashdata('message_type', 'danger');
    				$this->session->set_flashdata('message', "Error! Please try again later.");
					
					redirect('signup');
    			}
				//redirect();
    		}
		}

		$this->data['main_content'] = 'signup3';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}
	

	private function paypal_subscription() {
		// Load the paypal library
		$this->load->library('paypal');

		$this->paypal->add_field('cmd','_s-xclick');
		$this->paypal->add_field('custom', $customer_id);
		//$this->paypal->add_field('hosted_button_id', "P67CNGDHHZAGS"); // Daily Sub
		$this->paypal->add_field('hosted_button_id', "MBMEDQN2QJ8KJ"); // Monthly Sub
						
		//$this->paypal->image($logo);
		$this->paypal->paypal_auto_form();
	}
	
	
	private function stripe_subscription($stripe_customer_id, $token) {
		
		require_once(APPPATH.'libraries/Stripe.php');

        try {
			\Stripe\Stripe::setVerifySslCerts(false);
            \Stripe\Stripe::setApiKey(STRIPE_API_KEY);

            // Create Subscription
			$subscription = \Stripe\Subscription::create(array(
				"customer" =>  $stripe_customer_id,
				"plan" => "phitapp_monthly",
				"source" => $token,
			));

			return $subscription;
			

        } catch(Exception $e) {

            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', 'Problem with registration. Please try again. '.$e->getMessage());

            redirect('signup');
        }
		
		
					
		
	}
	
	public function do_social_login() {

		$response = array (
			'success' => false
		);

		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$first_name = htmlspecialchars($this->input->post('first_name'), ENT_QUOTES, 'utf-8');
			$last_name = htmlspecialchars($this->input->post('last_name'), ENT_QUOTES, 'utf-8');
			$email = htmlspecialchars($this->input->post('email'), ENT_QUOTES, 'utf-8');
			//$dob = htmlspecialchars($this->input->post('dob'), ENT_QUOTES, 'utf-8');
			$source = $this->input->post('source');
			$social_id = $this->input->post('social_id');
			$password = $this->genaratePassword();

			/* Set a few basic form validation rules */
			$this->form_validation->set_rules('first_name', 'First name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
			$this->form_validation->set_rules('email', 'Email address', 'trim|required');
			$this->form_validation->set_rules('social_id', 'Social ID', 'trim|required|numeric');

			/* Check if form (and captcha) passed validation*/
			if ($this->form_validation->run() == TRUE)
			{				
				// Check email address exists in system
				$this->load->model('../../../modules/App/models/Customer_model');

				if ($this->Customer_model->validate_data('customers', 'email', $email)) {

					// If email found, log him in
					if($this->processLogin($email, null, $social_id))
					{
						$response = array (
							'success' => true,
							'message_type' => 'success',
							'message' => 'You have successfully logged in with Facebook. Redirecting please wait...'
						);

					} else {

						$response = array (
							'success' => false,
							'message_type' => 'danger',
							'message' => 'Wrong Credentials.'
						);
					}

				} else {
					// Else create an account and create logn session

					/* Set the registration data in session */
					$registration_data['first_name'] = $first_name;
					$registration_data['last_name'] = $last_name;
					$registration_data['email'] = $email;
					//$registration_data['dob'] = $dob;
					$registration_data['social_id'] = $social_id;
					$registration_data['password'] = $password;

					if ($this->Customer_model->register_customer($registration_data)) {

						// Now Login
						$this->processLogin($email, null, $social_id);

						$response = array (
							'success' => true,
							'message_type' => 'success',
							'message' => 'Congrats! You have successfully registered & logged in with Facebook. Redirecting please wait...'
						);

					} else {

						$response = array (
							'success' => false,
							'message_type' => 'danger',
							'message' => 'Problem with registration. Please try again.'
						);
					}
				}

			} else { // Validation failed
				$response = array (
					'success' => false,
					'message_type' => 'danger',
					'message' => 'Validation Error: '.validation_errors()
				);
			}
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}


	/**
	 *
	 * @param unknown $email
	 * @param string $password
	 * @return boolean
	 */
	private function processLogin($email, $password = null, $social_id = null) {

		if($customer = $this->Customer_model->get_customer_login($email, $password, $social_id))
        {
			// Check Subscription Status
			if (!$this->Customer_model->check_subscription_status($customer->id)) {
				return false;
			}

            $data = array(
            	'id' => $customer->id,
            	'name' => $customer->first_name." ".$customer->last_name,
				'email' => $customer->email,
				'picture' => $customer->profile_pic,
            	'is_customer_logged_in' => true
			);
			$this->session->set_userdata($data);

			$this->Customer_model->update('customers', 'id', $customer->id, array('is_login' => '1', 'last_login' => date('Y-m-d H:i:s')));

            return true;

		} else {
			return false;
		}
	}

	


	

	

	
}
