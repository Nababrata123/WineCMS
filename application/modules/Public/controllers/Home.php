<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Front_Controller {

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


	public function success() {
    	//get the transaction data
        $paypalInfo = $this->input->get();

        /*$this->data['item_name'] = $paypalInfo['item_name'];
		$this->data['item_number'] = $paypalInfo['item_number'];
        $this->data['txn_id'] = $paypalInfo["tx"];
        $this->data['payment_amt'] = $paypalInfo["amt"];
        $this->data['currency_code'] = $paypalInfo["cc"];
        $this->data['status'] = $paypalInfo["st"];*/

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'success';

        //pass the transaction data to view
		$this->data['main_content'] = 'paypal/success';
    	$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
    }

	function cancel() {
    	//get the transaction data
		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'cancel';

        //pass the transaction data to view
		$this->data['main_content'] = 'paypal/cancel';
    	$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
    }

	public function ipn() {

		$this->load->library('paypal');

	   	//paypal return transaction details array
	   	$paypalInfo = $this->input->post();

		$results = print_r($paypalInfo, true);
		file_put_contents(BASEPATH.'logs/filename.txt', $results);

		// Check Payment Type
		if ($paypalInfo['txn_type'] == "web_accept") {
			
			// For One time payment
			$this->data['purchase_id'] = $paypalInfo['custom'];
			$this->data['product_id'] = $paypalInfo["item_number"];
			$this->data['txn_id'] = $paypalInfo["txn_id"];
			$this->data['payment_gross'] = $paypalInfo["payment_gross"];
			$this->data['currency_code'] = $paypalInfo["mc_currency"];
			$this->data['payer_email'] = $paypalInfo["payer_email"];
			$this->data['payment_status'] = $paypalInfo["payment_status"];
			$paypalURL = $this->paypal->paypal_url;
			$result = $this->paypal->curlPost($paypalURL,$paypalInfo);

			//check whether the payment is verified
			if(preg_match("/VERIFIED/i", $result)){

				//insert the transaction data into the database
				$data_update = array(
					'txn_id' => $this->data['txn_id'],
					'payment_status' => 1,
					'updated_on' => date('Y-m-d H:i:s')
				);
				$this->db->where('id', $this->data['purchase_id']);
				$this->db->update('mvp_purchase', $data_update);
			}

		} else if ($paypalInfo['txn_type'] == "subscr_payment") {

			// For Subscription Payment
			$customer_id = $paypalInfo['custom'];
			$subscription_type = "monthly";
			$subscription_amt = $paypalInfo['payment_gross'];
			$subscription_status = ($paypalInfo["payment_status"]=="Completed")?1:0;

			$this->load->model('../../App/models/Customer_model');
			
			// Add SUBSCRIPTION History
			$this->Customer_model->customer_subscribe($customer_id, $subscription_type, $subscription_amt, $subscription_status);

		} 
	}


	public function buy() {

		// Load the paypal library
		$this->load->library('paypal');

		//Set variables for paypal form
        $returnURL = base_url('home/success'); //payment success url
        $cancelURL = base_url('home/cancel'); //payment cancel url
        $notifyURL = base_url('home/ipn'); //ipn url
		$logo = base_url('assets/images/logo.png');

		/**
		 *  NOTE: Define the Price & Shipping
		 */
		$this->data['price'] = 99.00;
		$this->data['tax'] = 0;
		$this->data['shipping'] = array(
			'usps' => array(
				'price' => 2.99,
				'name' => 'USPS Standard (2-5 days): $2.99',
				'default' => true,
			), 
			'ups' => array(
				'price' => 6.99,
				'name' => ' USPS Priority Mail (2-3 Days): $6.99',
				'default' => false,
			) 
		);
		// ......


		$default_ship_amt = 0;
		foreach ($this->data['shipping'] as $key => $ship) {
			if ($ship['default']) {
				$default_ship_amt = $ship['price'];
			}
		}

		//$this->data['total'] = 108.43;
		$this->data['total'] = ($this->data['price']+$this->data['tax']+$default_ship_amt);

		//if save button was clicked, get the data sent via post
    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
			//form validation
			$this->form_validation->set_rules('shipping_type', 'Shipping', 'trim|required');
     		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('address1', 'Street Line 1', 'trim|required');
			$this->form_validation->set_rules('city', 'City', 'trim|required');
			$this->form_validation->set_rules('state', 'State', 'trim|required');
			$this->form_validation->set_rules('zip', 'Zipcode', 'trim|required');
    		$this->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email');
			$this->form_validation->set_rules('shipping_first_name', 'Shipping First Name', 'trim|required');
			$this->form_validation->set_rules('shipping_last_name', 'Shipping Last Name', 'trim|required');
			$this->form_validation->set_rules('shipping_address1', 'Shipping Street Line 1', 'trim|required');
			$this->form_validation->set_rules('shipping_city', 'Shipping City', 'trim|required');
			$this->form_validation->set_rules('shipping_state', 'Shipping State', 'trim|required');
			$this->form_validation->set_rules('shipping_zip', 'Shipping Zipcode', 'trim|required');
			
	    	//$this->form_validation->set_rules('qnty', 'Quantity', 'trim|required|numeric');
			//$this->form_validation->set_rules('total_price', 'Total Price', 'trim|required');

    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    		//if the form has passed through the validation

    		if ($this->form_validation->run())
    		{
				$shipping_type = $this->input->post('shipping_type');
				
    			$data = array(
					'first_name' => htmlspecialchars($this->input->post('first_name'), ENT_QUOTES, 'utf-8'),
					'last_name' => htmlspecialchars($this->input->post('last_name'), ENT_QUOTES, 'utf-8'),
    				'address1' => htmlspecialchars($this->input->post('address1'), ENT_QUOTES, 'utf-8'),
					'address2' => htmlspecialchars($this->input->post('address2'), ENT_QUOTES, 'utf-8'),
    				'city' => htmlspecialchars($this->input->post('city'), ENT_QUOTES, 'utf-8'),
					'state' => htmlspecialchars($this->input->post('state'), ENT_QUOTES, 'utf-8'),
					'zip' => htmlspecialchars($this->input->post('zip'), ENT_QUOTES, 'utf-8'),
					'email' => htmlspecialchars($this->input->post('email'), ENT_QUOTES, 'utf-8'),
					'phone' => htmlspecialchars($this->input->post('phone'), ENT_QUOTES, 'utf-8'),
    				'shipping_first_name' => htmlspecialchars($this->input->post('shipping_first_name'), ENT_QUOTES, 'utf-8'),
					'shipping_last_name' => htmlspecialchars($this->input->post('shipping_last_name'), ENT_QUOTES, 'utf-8'),
    				'shipping_addres1' => htmlspecialchars($this->input->post('shipping_address1'), ENT_QUOTES, 'utf-8'),
    				'shipping_address2' => htmlspecialchars($this->input->post('shipping_address2'), ENT_QUOTES, 'utf-8'),
    				'shipping_city' => htmlspecialchars($this->input->post('shipping_city'), ENT_QUOTES, 'utf-8'),
					'shipping_state' => htmlspecialchars($this->input->post('shipping_state'), ENT_QUOTES, 'utf-8'),
					'shipping_zip' => htmlspecialchars($this->input->post('shipping_zip'), ENT_QUOTES, 'utf-8'),
					'quantity' => 1,
					'tax' => $this->data['tax'],
					'shipping_type' => $shipping_type,
					'shipping_amt' => $this->data['shipping'][$shipping_type]['price'],
					'price' => $this->data['price'],
     				'created_on' => date('Y-m-d H:i:s')
    			);

				//print "<pre>"; print_r($data); die;

    			//if the insert has returned true then we show the flash message
    			if ($this->db->insert('mvp_purchase', $data)) {
					$purchase_id = $this->db->insert_id();

					$this->paypal->add_field('return', $returnURL);
					$this->paypal->add_field('cancel_return', $cancelURL);
					$this->paypal->add_field('notify_url', $notifyURL);
					$this->paypal->add_field('item_name', "PHIT APP 1 Year Subscription");
					$this->paypal->add_field('custom', $purchase_id);
					$this->paypal->add_field('item_number', 'PHITAPP-SUB-001');
					$this->paypal->add_field('shipping', $this->data['shipping'][$shipping_type]['price']);
					$this->paypal->add_field('quantity', 1);
					$this->paypal->add_field('tax', $this->data['tax']);
					$this->paypal->add_field('amount', $this->data['price']);

					$this->paypal->add_field('first_name', $data['first_name']);
					$this->paypal->add_field('last_name', $data['last_name']);
					$this->paypal->add_field('address1', $data['address1']);
					$this->paypal->add_field('address2', $data['address2']);
					$this->paypal->add_field('city', $data['city']);
					$this->paypal->add_field('state', $data['state']);
					$this->paypal->add_field('country', 'US');
					$this->paypal->add_field('zip', $data['zip']);

					$this->paypal->add_field('cmd','_xclick');
					
					$this->paypal->image($logo);
					$this->paypal->paypal_auto_form();
    			}
    		} //validation run
    	}

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'buynow';

		$this->data['main_content'] = 'buy';
		$this->load->view('templates/public/template_1', $this->data);
	}

	public function cms($page = null) {

		if ($page == null) {
			show_404();
		}

		$this->load->model('Public_model');
		$this->data['page'] = $this->Public_model->get_cms($page);

		if (empty($this->data['page'])) {
			show_404();
		}

		$this->data['page_title'] = $this->data['page']->title;
		$this->data['meta_description'] = $this->data['page']->meta_description;
		$this->data['meta_keyword'] = $this->data['page']->meta_keyword;

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


	public function thank_you(){
		$this->load->view('feedback');
	}


	public function job_rating(){

		$this->load->model('../../App/models/Job_model');
		$job_id = $this->uri->segment(2);
		
		$rating = $this->uri->segment(3);
		$data['rating'] = $this->base64url_decode($rating);

		$completedJobData = $this->Job_model->get_completed_job_info($job_id);
	 
		if($completedJobData->agency_taster_id){
			 $tasterName = $this->Job_model->getTasterName($completedJobData->agency_taster_id);
		}else{
		     $tasterName = $this->Job_model->getTasterName($completedJobData->taster_id);
		}

		$data['tasterName'] = $tasterName->taster_name;
		$data['job_start_time'] = $completedJobData->job_start_time;
		$data['finish_time'] = $completedJobData->finish_time;
		$data['wineNames'] = $this->Job_model->get_mail_wine_names($job_id);
		$data['taster_id'] = $completedJobData->taster_id;
		$data['store_id'] = $completedJobData->store_id;
		$data['job_id'] = $job_id;
		
		$this->load->view('jobrating',$data);
	}

	public function submit_rating() {

		$this->load->model('../../App/models/Job_model');
		if ($this->input->server('REQUEST_METHOD') === 'POST') {

		$job_id = $this->input->post('job_id');
		$completedJobData = $this->Job_model->get_completed_job_info($job_id );
	 
		// print_r($completedJobData);die;
		if($completedJobData->agency_taster_id){
			 $tasterName = $this->Job_model->getTasterName($completedJobData->agency_taster_id);
		}else{
		     $tasterName = $this->Job_model->getTasterName($completedJobData->taster_id);
		}

		$data['tasterName'] = $tasterName->taster_name;
		$data['job_start_time'] = $completedJobData->job_start_time;
		$data['finish_time'] = $completedJobData->finish_time;
		$data['wineNames'] = $this->Job_model->get_mail_wine_names($job_id );
		$tastingDate = $completedJobData->tasting_date;
		$tastingDate = date("F d, Y", strtotime($tastingDate));
		// $tastingDate = date("m-d-Y", strtotime($tastingDate));
		$store = $this->Job_model->get_store_name_mail($job_id);
       $store_name = $store[0]['store_name'];
       $store_address = $store[0]['store_address'];

    //    $salesrep = $this->Job_model->get_mail_selsrep_name($job_id);
    //    $salesrep_name = $salesrep->sales_rep_name;
	   
	    $salesIdArray = explode(',', $completedJobData->user_id);
		$salesrep_name = $this->Job_model->get_salesrep_name($completedJobData->user_id);

		$this->db->select("email, first_name, last_name");
		$this->db->from('users');
		$this->db->where_in('id',$salesIdArray);
		$m_result=$this->db->get()->result_array();
		
	/*	$salesrep_name='';
		foreach ($m_result as $res){
			$salesrep_name.=$res['first_name']." ".$res['last_name'].", ";
		}
		$salesrep_name=rtrim($salesrep_name,", ");
		*/

			$stars = $this->input->post('rating');
			$feedback = $this->input->post('feedback');
			if($feedback==''){
				$feedback= "N/A";
			}
			$insData =array('job_id'=>$this->input->post('job_id'),
							'store_id'=>$this->input->post('store_id'),
							'taster_id'=>$this->input->post('taster_id'),
							'rating'=>$this->input->post('rating'),
							'feedback'=>$feedback,
							'created_at'=>date("Y-m-d H:i:s")
			);

			// print_r($insData);die;
			if ($this->db->insert('job_rating', $insData)) {
	

				$dataforadmin = $this->jobRatingMailTemplate($job_id, 'Admin', $tastingDate, $data['tasterName'], $data['job_start_time'],$data['finish_time'], $data['wineNames'], $store_name, $store_address, $salesrep_name, $stars, $feedback);
				// $salesRepMailAddress = $m_result['email'];

				foreach ($m_result as $res){

					$salesFirstName = $res['first_name'];
					$salesrepName.=$res['first_name']." ".$res['last_name'];

					$dataforsalesrep = $this->jobRatingMailTemplate($job_id, $salesFirstName, $tastingDate, $data['tasterName'], $data['job_start_time'], $data['finish_time'], $data['wineNames'], $store_name, $store_address, $salesrep_name, $stars, $feedback);

					$this->email_to_user($res['email'], 'Wine Sampling - '.$tastingDate, $dataforsalesrep);
				}

				// $this->email_to_user('rr.avalgate@gmail.com', 'Wine Sampling - '.$tastingDate, $dataforadmin); //admin mail for Development.
				$this->email_to_user('fraidy@thekgroupny.com', 'Wine Sampling - '.$tastingDate, $dataforadmin); //admin mail for Clone Live.
				//$this->email_to_user('tastingresults@gmail.com', 'Wine Sampling - '.$tastingDate, $dataforadmin); //admin mail for Live.

				   redirect(BASE_URL."thank_you");
			}else{
				$this->session->set_flashdata('message_type', 'danger');
				   $this->session->set_flashdata('message','Operation Unsuccessfully');
				   redirect(BASE_URL."jobrating/".$job_id."/".$data['rating']);
			}
		}
	}

	function base64url_decode($base64url) {
        return base64_decode(strtr($base64url, '-_', '+/'));
    }

   	public function recover_password() {

   		$this->data['page_title'] = $this->lang->line('auth_recover_password_page_title');

         $this->load->view('recover_password', $this->data);
        //$this->data['main_content'] = 'recover_password';
        //$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
		$this->load->model('../../../modules/App/models/Tester_model');

        if ($this->input->server('REQUEST_METHOD') === 'POST') {

        	//echo "<pre>";
        	//print_r($this->input->post());die;
        	$key = $this->input->post('key');
        	$this->load->library('session');
        	//form validation
        	$this->form_validation->set_rules('new_pwd', $this->lang->line('auth_recover_password_form_password_label'), 'trim|required|matches[re_new_pwd]');
        	$this->form_validation->set_rules('re_new_pwd', $this->lang->line('auth_recover_password_form_confirm_password_label'), 'trim|required');
        	$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

        	if ($this->form_validation->run()) {

       			$key = $this->input->post('key');
        		$customer_id = base64_decode($this->input->post('user_id'));
        		$pwd = md5($this->input->post('new_pwd'));

        		if ($this->Tester_model->update_password('users', 'id', $customer_id, array('password' => $pwd))) {

	       			

	        		$this->session->set_flashdata('message_type', 'success');
	        		//$this->session->set_flashdata('message', $this->lang->line('auth_recover_password_success_msg'));
	       			$this->session->set_flashdata('message','Password has been changed successfully');
	       			
					
        		}
        		//redirect(BASE_URL.'recover_password');
				redirect(BASE_URL."recover_password/".$key."/".$this->input->post('user_id'));
				//redirect(BASE_URL.'agency');
         	}
         	else
         	{
         		redirect(BASE_URL."recover_password/".$key."/".$this->input->post('user_id'));
         	}
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

	public function check_email() {

		$email = $this->input->get('email'); 
		//echo php_sapi_name();
		if ($email <> "") {
			$this->load->model('../../../modules/App/models/Customer_model');

			if ($this->Customer_model->validate_data('customers', 'email', $email)) {
				header('HTTP/1.1 409 Email already exists. Please login or enter another.', true, 409);
			} else {
				return true;
			}
		}
		
	}

	public function location() {

		try {
			$this->load->model('Public_model');

			$type = $this->input->get('type');

			if ($type == "getCountries") {
				$data = $this->Public_model->get_countries();
			}

			if ($type == "getStates") {
				$countryId = $this->input->get('countryId');
				if(!isset($countryId) || empty($countryId)) {
					throw new exception("Country Id is not set.");
				}
				$countryId = $_GET['countryId'];
				$data = $this->Public_model->get_states($countryId);
			}

			if ($type == "getCities") {
				if(!isset($_GET['stateId']) || empty($_GET['stateId'])) {
					throw new exception("State Id is not set.");
				}
				$stateId = $_GET['stateId'];
				$data = $this->Public_model->get_cities($stateId);
			}

		} catch (Exception $e) {
			$data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
		 } finally {
		   echo json_encode($data);
		 }
	}

	public function activate($encrypted_string = null) {

		if ($encrypted_string == null) {
			return false;
		}
		//echo $encrypted_string;die;
		$this->load->library('encrypt');
		
		$base64 = strtr($encrypted_string, '-_', '+/');
		$email = $this->encrypt->decode($base64);
		
		$this->load->model('../../../modules/App/models/Customer_model');

		if ($this->Customer_model->verify_email($email)) {
			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', "You email is now activated. You can login to your account now.");
		} else {
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', "Error! Please try again later.");
		}
		redirect('login');
	}

	public function make_payment($encrypted_string = null) {

		if ($encrypted_string == null) {
			return false;
		}
		//echo $encrypted_string;die;
		$this->load->library('encrypt');
		
		$base64 = strtr($encrypted_string, '-_', '+/');
		$customer_id = $this->encrypt->decode($base64);
		
		$this->load->model('../../../modules/App/models/Customer_model');
				
		// Get Customer details
		$customer = $this->Customer_model->get_customer_details($customer_id);
		//echo $customer_id; print "<pre>"; print_r($customer); die;
		if (!is_numeric($customer_id) || $customer_id == 0 || empty($customer) || $customer->subscription_id > 0) {
     		redirect();
     	}
		
				
		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$payment_type = $this->input->post('payment_type');
			$code = $this->input->post('code');
			$subscription = $this->input->post('subscription');
			$token = $this->input->post('stripToken');
			//print "<pre>"; print_r($_POST); 
			
			//form validation
			$this->form_validation->set_rules('payment_type', 'Payment type', 'required|trim');

			if ($payment_type == "ref") {
				$this->form_validation->set_rules('code', 'Referal code', 'exact_length[10]|callback_code_check');
			}

			if ($payment_type == "sub") {
				$this->form_validation->set_rules('subscription', 'Subscription', 'numeric|greater_than_equal_to[9]|less_than[10]');
			}
			
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

    		//if the form has passed through the validation
    		if ($this->form_validation->run()) {
				
				// Add customer to Stripe
				if ($customer->customer_stripe_id == "") {
					$customer_stripe_id = $this->stripe_customer_signup(array('email'=>$customer->email));
					
					// Update customer with new Stripe ID
					$this->Customer_model->update('customers', 'id', $customer_id, array('customer_stripe_id' => $customer_stripe_id));
				}


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

					
				// ADD Subscription
				if ($payment_type == "sub") {

					// Stript subscription
					$subscription = $this->stripe_subscription($customer_stripe_id, $token);
						
					$subscription_type = "monthly";
					$subscription_amt = 9.99;
					$subscription_status = 1;
						
					// Add SUBSCRIPTION History
					$this->Customer_model->customer_subscribe($customer_id, $subscription_type, $subscription_amt, $subscription_status, $subscription->id);
						
					redirect('home/thankyou');
				}
    		}
		}
		
		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'payment';
		$this->data['page_title'] = "Make Payment";
		
		$this->data['customer'] = $customer;
		$this->data['main_content'] = 'payment';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}
	
	public function logout() {

        // Delete the fb cookie
        $this->load->helper('cookie');
        delete_cookie('user_acess_token');

		$this->session->unset_userdata('id');
	   	$this->session->unset_userdata('is_customer_logged_in');

	   	$this->session->sess_destroy();

	   	$this->session->set_flashdata('message_type', 'warning');
		$this->session->set_flashdata('message', 'You are successfully logged out.');

	   	redirect('', 'refresh');
	}

	public function thankyou() {
		
		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'thankyou';
		$this->data['page_title'] = "Thank you";

		$this->data['main_content'] = 'thankyou';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}
	
	
	private function genaratePassword() {
		return substr(base64_encode(time()), 10, 8);
	}


	public function code_check($code)
	{
		$this->load->model('../../../modules/App/models/Customer_model');

		if ($this->Customer_model->validate_code($code)) {
			$this->form_validation->set_message('code_check', 'The {field} can not be a used one. Enter valid {field}.');
			return false;
		} else {
			return true;
		}
	}

	 private function stripe_customer_signup($signupdata) {

        require_once(APPPATH.'libraries/Stripe.php');

        try {
			\Stripe\Stripe::setVerifySslCerts(false);
            \Stripe\Stripe::setApiKey(STRIPE_API_KEY);

            $customer = \Stripe\Customer::create(array(
                "email" => $signupdata['email'],
            ));

            return $customer->id;

        } catch(Exception $e) {

            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', 'Problem with registration. Please try again. '.$e->getMessage());

            redirect('signup');
        }
	}
	
	function email_to_user($to_email = NULL, $subject = NULL, $message = NULL)
    {
        
        $CI = &get_instance();
        $CI->load->library('mail_template');
        // $CI->lang->load('application');

        $from = $CI->lang->line('app_site_name') . " <" . NO_REPLY_EMAIL . ">";
        //SMTP & mail configuration
        $host = "ssl://smtp.gmail.com";
        $port = "465";
        $username = "noreply@karossonline.com";
        $password = "lAmQMI8enGzUDqd";

        $headers = array(
            'MIME-Version' => '1.0rn',
            'Content-Type' => "text/html; charset=ISO-8859-1rn",
            'From' => $from,
            'To' => $to_email,
            'Subject' => $subject,
        );

        $smtp = Mail::factory(
            'smtp',
            array(
                'host' => $host,
                'port' => $port,
                'auth' => true,
                'username' => $username,
                'password' => $password
            )
        );

        //$message .= "<p>Thank you,<br /><i><strong>" . $CI->lang->line('app_site_name') . "</strong></i></p>";
        $htmlContent =  $message;
        $smtp->send($to_email, $headers, $htmlContent);
    }
	
	public function jobRatingMailTemplate($job_id, $manager_name, $tastingDate, $tasterName, $startTime, $finish_time, $wineNames, $store_name, $store_address, $salesrep_name, $starscount, $feedback)
    {

        $stars ='';

        for ($i=0; $i<$starscount; $i++){
            $stars.='<img src="'.BASE_URL.'assets/wine/thumb/star.png" width="40" height="40"/>';
        }
        
        
        $data='';
        $data .= '<!DOCTYPE html>
        <html lang="en">
            <head>
                <title>Job Rating</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                
                <style>
                    .container{border: 5px solid #c48f29;width:60%;}
                    .logo{width:13%;}
                    .time{width:35%;}
                    .staricon{width:45%;}
                    .small{
                        font-size: 59%;
                        font-weight: 400;
                    }
                    .img-thumbnail {
                        padding: .25rem;
                        background-color: #fff;
                        border: 1px solid #dee2e6;
                        border-radius: .25rem;
                        max-width: 100%;
                        height: auto;
                    }
                    .size{
                        width:50px;
                    }
                    .size:hover {
                      color: white;
                    }
                    .wine{margin-left: 15px;
                        font-size: 16px;}
                    .btn{display: inline-block;
                        color: white;
                        text-align: center;
                        vertical-align: middle;
                        background-color: transparent;
                        border: 1px solid transparent;
                        padding: 5px;
                        font-size: 10px;
                        line-height: 1.5;
                        border-radius: .25rem;
                        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
                    }
                    
                    
                    @media screen and (max-width: 600px) {
                        
                        .container{border: 5px solid #c48f29;width:100%;}
                        .logo{width:30%;}
                        .wine{margin-left: 15px;
                        font-size: 12px;}
                        .btn{display: inline-block;
                            color: white;
                            text-align: center;
                            vertical-align: middle;
                            background-color: transparent;
                            border: 1px solid transparent;
                            padding: 3px;
                            font-size: 8px;
                            line-height: 1.5;
                            border-radius: .25rem;
                            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
                        }
                        .tim{font-size:14px;}
                    }
                </style>
            </head>
            <body>
                <center>
                    <div>
                        <table class="container">
                            <tr>
                                <td colspan="3" style="text-align:center;">
                                <center>
                                <img src="'.BASE_URL.'assets/wine/thumb/Wine_Logo.png" width="100">
                                </center>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:center;">
                                    <h1 style="margin-top:auto;">Tasting Information</h1>
                                </td>
							</tr>
							<tr>
								<td colspan="3">
									<h3 style="margin-top:auto;">Tasting Date - '.$tastingDate.'</h3>
								</td>
							</tr>
                            <tr>
                                <td colspan="3">
                                    <h3 style="margin-top:auto;">Store name & address - '.$store_name.', '.$store_address.'</h3>
                                </td>
                          </tr>
                          <tr>
                                <td colspan="3">
                                    <h3 style="margin-top:auto;">Sales Rep - '.$salesrep_name.'</h3>
                                </td>
                           </tr>
                            <tr>
                                <td colspan="3">
                                <h3 style="margin-top:auto;">Taster - '.$tasterName.'</h3>
                                </td>
							</tr>
                            <tr>
                                <td width="100">
                                <h3 style="margin-top:auto;" class="tim">Job Start Time - '.date("g:i a", strtotime($startTime)).'</h3>
                                </td>
                                <td width="100">
                                <h3 style="margin-top:auto;" class="tim">Job End Time - '.date("g:i a", strtotime($finish_time)).'</h3>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <h3 style="margin-top:auto;">Wines Sold : </h3>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                <table>';
                                if (!empty($wineNames)) {
                                    foreach ($wineNames as $wine) {
                                        if($wine["soldwine"] > 1){
                                            $bottle_sold ='Bottles sold';
                                        }else{
                                            $bottle_sold = 'Bottle sold';
                                        }
                                        if($wine["usedwine"] > 1){
                                            $bottle_used ='Bottles used';
                                        }else{
                                            $bottle_used = 'Bottle used';
                                        }
										if($wine["open_bottles_sampled"] > 1){
                                            $bottle_sampled ='Opened bottles sampled';
                                        }else{
                                            $bottle_sampled = 'Opened bottle sampled';
                                        }
                                    $data.='<tr>
                                           <td style="width:10%;">
                                               <img style="max-width:100px;" src="'.$wine["image"].'" width="75">
                                           </td>
                                           <td>
                                           <p  class="wine">'. $wine["name"] .' - ' . $wine["soldwine"] . ' '.$bottle_sold.',  ' . $wine["usedwine"] . ' '.$bottle_used.',  '. $wine["open_bottles_sampled"] . ' '.$bottle_sampled.'</p>
                                           </td>
                                       </tr>';
                                    }
                                }
                                $data.='</table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <hr style="border-top: 2px solid rgba(0,0,0,.1);">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:center;">
                                    <h1>Job Rating</h1>
                                    
                                    <p class="text-center" style="text-align:center; color: #FFA500; margin:0px; font-size:30px;">'.$stars.'</p><br>
                                    <h2 class="text-center" style="text-align:center; margin:0px;">Store feedback</h2>
                                    <p class="text-center" style="text-align:center;  font-size:20px; margin:2px;">'.$feedback.'</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <br>
                                </td>
                            </tr>
                        </table>
                    </div>
                </center>
            </body>
        </html>';
        return $data;
    }
}
