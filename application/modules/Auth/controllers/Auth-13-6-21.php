<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Application_Controller {

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
		// print_r($this->session->userdata());die;
        
		if ($this->session->userdata('is_logged_in')) {
            if($this->session->userdata('role')=='agency')
            {

                redirect('agency/dashboard');
            }else if($this->session->userdata('role')=='brand_wise_users')
            {

                redirect('App/billing/get_expenses_brandwise');
            }
            else
            {
                redirect('auth/dashboard');
            }
        } 
        /*else if ($this->session->userdata('is_public_logged_in')) {
			redirect('dashboard');
		} */
        else {

        	$this->data['page_title'] = $this->lang->line('auth_login_page_title');
        	$this->load->view('login', $this->data);
        }
	}

	/**
     * Login into account
     * Sets sesstion data
     */
    public function do_login() {

    	$this->load->library('form_validation');

    	$this->data['page_title'] = $this->lang->line('auth_login_page_title');

    	if ($this->session->userdata('is_logged_in')) {
			// echo "<pre>";
			// 	print_r("Naba");die;
			
        //    echo $this->session->userdata('role_token');die;
            if($this->session->userdata('role_token')=='agency')
            {
                redirect('agency/dashboard');
            }else if($is_valid_details->role_token=='brand_wise_users')
			{
				// echo "<pre>";
				// print_r("Naba");die;
			  redirect('App/billing/get_expenses_brandwise');
			  
			}
            else
            {
                redirect('auth/dashboard');
            }

        } else {
			
        	$email = $this->input->post('email');
        	$password = $this->input->post('password');

            $this->form_validation->set_rules('email', $this->lang->line('auth_login_form_email_label'), 'required');
            $this->form_validation->set_rules('password', $this->lang->line('auth_login_form_password_label'), 'required');

            if ($this->form_validation->run() == FALSE) {

            	$this->data['error'] = $this->lang->line('auth_login_validation_error');
                $this->load->view('login', $this->data);

            } else {

            	$this->load->model('Auth_model');
            	$this->load->helper('date');

            	if($is_valid_details = $this->Auth_model->validate($email, $this->_encrip_password($password)))
            	{
                    $data = array(
            			'id' => $is_valid_details->id,
            			'name' => $is_valid_details->name,
            			'email' => $is_valid_details->email,
            			'last_login' => mdate("%m/%d/%Y - %h:%i %a", strtotime($is_valid_details->last_login)),
                    	'role' => $is_valid_details->role_token,
                    	'permissions' => $this->Auth_model->get_user_permissions($is_valid_details->id),
                    	'navmenu' => $this->Auth_model->get_main_menu($is_valid_details->role_id),
            			'is_logged_in' => true
            		);
                  
					// echo "<pre>";
					// print_r($is_valid_details);
					// print_r($data['role']);die;
            		$this->session->set_userdata($data);

            		$this->Auth_model->update('id', $is_valid_details->id, array('last_login' => date('Y-m-d H:i:s')));
                    //Check agency or not
                    if($is_valid_details->role_token=='administrator' || $is_valid_details->role_token=='super_administrator')
                    {
                        
                        redirect('auth/dashboard');
                    }
					else if($is_valid_details->role_token=='agency')
                    {
            		  redirect('agency/dashboard');
                    }
					else if($is_valid_details->role_token=='brand_wise_users')
                    {
						
            		  redirect('App/billing/get_expenses_brandwise');
                    }
            		else
                    {
                        $this->session->sess_destroy();
                        $this->data['error'] = $this->lang->line('auth_login_error');
                        $this->load->view('login', $this->data);
                    }
            	}
                else {

                	$this->data['error'] = $this->lang->line('auth_login_error');
                    $this->load->view('login', $this->data);
                }
            }
        }
    }

    /**
     *  Admin Forgot Password
     */
    /* function forgot_password() {

        $this->data['page_title'] = $this->lang->line('auth_forgot_password_page_title');

    	if ($this->input->server('REQUEST_METHOD') === 'POST') {

    		$this->load->library('form_validation');

    		//form validation
    		$this->form_validation->set_rules('email', $this->lang->line('auth_forgot_form_email_label'), 'required');
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

    		//if the form has passed through the validation
    		if ($this->form_validation->run()) {

    			$email = $this->input->post('email');

    			$this->load->model('Auth_model');

    			if ($info = $this->Auth_model->is_valid_data('users', array('email' => $email))) {

    				$user_id = $info->id;
    				$user_info = $this->Auth_model->get_user_by_id($user_id);

    				$data_to_insert = array(
    					'user_id' => $user_info->id,
    					'token' => md5(time()),
    					'create_date' => date('Y-m-d H:i:s')
    				);

    				$this->Auth_model->save_password_log($data_to_insert);

    				// Send Email to reset admin password starts
    				$activation_link = BASE_URL.'auth/recover_password/'.$data_to_insert['token'];
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

    		//redirect('forgot_password');
    	}

    	$this->load->view('forgot_password', $this->data);
    } */
	function forgot_password() {

        $this->data['page_title'] = $this->lang->line('auth_forgot_password_page_title');

    	if ($this->input->server('REQUEST_METHOD') === 'POST') {

    		$this->load->library('form_validation');

    		//form validation
    		$this->form_validation->set_rules('email', $this->lang->line('auth_forgot_form_email_label'), 'required');
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

    		//if the form has passed through the validation
    		if ($this->form_validation->run()) {

    			$email = $this->input->post('email');

    			$this->load->model('Auth_model');

    			if ($info = $this->Auth_model->is_valid_data('users', array('email' => $email))) {

    				$user_id = $info->id;
    				$user_info = $this->Auth_model->get_user_by_id($user_id);

    				$data_to_insert = array(
    					'user_id' => $user_info->id,
    					'token' => md5(time()),
    					'create_date' => date('Y-m-d H:i:s')
    				);

    				//$this->Auth_model->save_password_log($data_to_insert);
                    $user_id=base64_encode($user_info->id);
    				// Send Email to reset admin password starts
                    $activation_link = BASE_URL.'auth/recover_password/'.$data_to_insert['token'].'/'.$user_id;
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

    		//redirect('forgot_password');
    	}

    	$this->load->view('forgot_password', $this->data);
    }

   /**
    *
    * @param unknown_type $enc_str
    */
   /* function recover_password ($encrypted_string) {

   		$this->load->library('encrypt');
   		$this->load->model('Auth_model');

   		$this->data['page_title'] = $this->lang->line('auth_recover_password_page_title');

        if ($this->input->server('REQUEST_METHOD') === 'POST') {

        	$this->load->library('form_validation');

        	//form validation
        	$this->form_validation->set_rules('new_pwd', $this->lang->line('auth_recover_password_form_password_label'), 'trim|required|matches[re_new_pwd]');
        	$this->form_validation->set_rules('re_new_pwd', $this->lang->line('auth_recover_password_form_confirm_password_label'), 'trim|required');
        	$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

        	//if the form has passed through the validation
        	if ($this->form_validation->run()) {

        		$key = $this->input->post('key');
        		$user_id = $this->encrypt->decode($key);
        		$pwd = $this->_encrip_password($this->input->post('new_pwd'));

        		if ($this->Auth_model->update('id', $user_id, array('password' => $pwd))) {

        			$pass_info = $this->Auth_model->is_valid_data('user_password_log', array('token' => $encrypted_string, 'user_id' => $id));
        			$this->Auth_model->update_password_log($pass_info->id, array('visited' => 1));

        			$this->session->set_flashdata('message_type', 'success');
        			$this->session->set_flashdata('message', $this->lang->line('auth_recover_password_success_msg'));
        		}
        		redirect(BASE_URL);
        	}
        }

    	if ($encrypted_string) {

    		if ($this->data['link_info'] = $this->Auth_model->is_valid_data('user_password_log', array('token' => $encrypted_string, 'visited' => 0))) {

    			if (empty($this->data['link_info'])) {
    				redirect(BASE_URL);
    			}

    			// Check for validity of the link (1 hr)
    			$time = strtotime($this->data['link_info']->create_date);
    			$now = time();
    			if ($now - $time > RESET_PASSWORD_LINK_VALIDITY) {
    				$this->session->set_flashdata('message_type', 'danger');
    				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Allowed timelimit exceed.');
    				redirect(BASE_URL);
    			}

    			$this->data['link_info']->enc_key = $this->encrypt->encode($this->data['link_info']->user_id);
    			$this->load->view('recover_password', $this->data);

    		} else {

    			$this->session->set_flashdata('message_type', 'danger');
    			$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Not a valid link.');

    			redirect(BASE_URL);
    		}
    	} else {
    		redirect(BASE_URL);
    	}

    } */

	function recover_password() {

   		//$this->load->library('encrypt');
   		$this->load->model('Auth_model');

   		$this->data['page_title'] = $this->lang->line('auth_recover_password_page_title');

        if ($this->input->server('REQUEST_METHOD') === 'POST') {



        	$this->load->library('form_validation');

                $key = $this->input->post('key');
               //$user_id = $this->encrypt->decode($key);
               $user_id = $this->input->post('user_id');

        	//form validation
        	$this->form_validation->set_rules('new_pwd', $this->lang->line('auth_recover_password_form_password_label'), 'trim|required|matches[re_new_pwd]');
        	$this->form_validation->set_rules('re_new_pwd', $this->lang->line('auth_recover_password_form_confirm_password_label'), 'trim|required');
        	$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

        	//if the form has passed through the validation
        	if ($this->form_validation->run()) {

               $original_user_id=base64_decode($user_id);
        		$pwd = $this->_encrip_password($this->input->post('new_pwd'));

        		if ($this->Auth_model->update('id', $original_user_id, array('password' => $pwd))) {

        			//$pass_info = $this->Agency_model->is_valid_data('user_password_log', array('token' => $encrypted_string, 'user_id' => $id));
        			//$this->Auth_model->update_password_log($pass_info->id, array('visited' => 1));

        			$this->session->set_flashdata('message_type', 'success');
        			$this->session->set_flashdata('message', $this->lang->line('auth_recover_password_success_msg'));
        		}
        		redirect(BASE_URL);
                //$this->load->view('recover_password', $this->data);
        	}
            else
            {
                //$this->load->library('user_agent');
                //redirect($_SERVER['HTTP_REFERER']);
                $this->load->view('recover_password', $this->data);
                

            }
        }
        else
        {
            $this->load->view('recover_password', $this->data);
        }

    	/*if ($encrypted_string) {

    		if ($this->data['link_info'] = $this->Agency_model->is_valid_data('user_password_log', array('token' => $encrypted_string, 'visited' => 0))) {

    			if (empty($this->data['link_info'])) {
    				redirect(BASE_URL);
    			}

    			// Check for validity of the link (1 hr)
    			$time = strtotime($this->data['link_info']->create_date);
    			$now = time();
    			if ($now - $time > RESET_PASSWORD_LINK_VALIDITY) {
    				$this->session->set_flashdata('message_type', 'danger');
    				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Allowed timelimit exceed.');
    				redirect(BASE_URL);
    			}

    			$this->data['link_info']->enc_key = $this->encrypt->encode($this->data['link_info']->user_id);
    			$this->load->view('recover_password', $this->data);

    		} else {

    			$this->session->set_flashdata('message_type', 'danger');
    			$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Not a valid link.');

    			redirect(BASE_URL);
    		}
    	} else {
    		redirect(BASE_URL);
    	}*/

    }
    /**
     * encript the password
     * @return mixed
     */
    function _encrip_password($password) {
    	return md5($password);
    }

    /**
     * Logout from account
     */
    public function logout() {

        $this->session->sess_destroy();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");

        redirect('', 'refresh');
    }

    /**
     *
     */
    public function dashboard() {

        //echo "I am here";die;

    	// Validate Login
		parent::checkLoggedin();

		$this->data['dashboard'] = array();

		$this->load->model('Auth_model');
        $this->load->library('twentythree');

		//echo $this->session->userdata('role');
		if ($this->session->userdata('role') == "super_administrator") {
			$this->data['dashboard'] = $this->Auth_model->get_admin_dashboard();
		} else {
			$this->data['dashboard'] = $this->Auth_model->get_dashboard();
            
		}

        $this->data['page'] = 'dashboard';
        $this->data['page_title'] = $this->lang->line('auth_dashboard_page_title');

    	$this->data['main_content'] = 'dashboard';
    	$this->load->view(TEMPLATE_PATH, $this->data);
    }



}
