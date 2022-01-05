<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Application_Controller {

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
	private $tablename = 'users';
	private $url = '/App/users';
	private $reference_url = '';

	private $permissionValues = array(

		'index' => 'App.Users.View',
		'add' => 'App.Users.Add',
		'edit' => 'App.Users.Edit',
        'delete' => 'App.Users.Delete',
		'edit_profile' => 'App.Users.Edit',
        
    );


	// private $permissionValues = array(

	// 	'index' => 'App.Agency.View',
	// 	'add' => 'App.Agency.Add',
	// 	'edit' => 'App.Agency.Edit',
    //     'delete' => 'App.Agency.Delete',
        
    // );

    //private $allowed_roles = array('bar_admin');

	public function __construct() {
		


        parent::__construct();

		// Validate Login
		parent::checkLoggedin();
        // $this->load->model('Agency_model');
		$this->load->model('Users_model');
        $this->load->model('Tester_model');
		$this->load->model('Job_model');
		$this->module_dir = APPPATH.'modules/'.$this->router->fetch_module();
        $this->load->config('config');
        // echo "<pre>";
		// print_r($this->module_dir);die;
		$this->session->set_userdata('page_data', array('url' => $this->url, 'permissions' => $this->permissionValues));
        $this->load->library('user_agent');
        $this->load->helper('template');
        
    }

	public function index() {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);
       
		$default_uri = array( 'name','page', 'status','view');
    	$uri = $this->uri->uri_to_assoc(4, $default_uri);
        $pegination_uri = array();
    	//$role = $uri['role'];
		$status = $uri['status'];

        

        // Process the filters
        // Create the filters
	    /*if ($uri['view'] <> "") {
            $filter['view'] 		= $uri['view'];
			$pegination_uri['view'] = $uri['view'];
        } else {
			$filter['view'] 		= 10;
			$pegination_uri['view'] = 10;
		}*/
        if (isset($uri['name']) && trim(urldecode($uri['name'])) <> "") {
            $filter['name'] = $uri['name'];
            $pegination_uri['name'] = $uri['name'];
        } else {
            $filter['name'] = "";
            $pegination_uri['name'] = " ";
        }

		if ($uri['page'] > 0) {
    		$page = $uri['page'];
    	} else {
    		$page = 0;
    	}

    	
	    if ($status <> '') {
		    $filter['status'] = $status;
	    } else {
	    	$status = 0;
	    }

	    // Get the total rows without limit
	    $total_rows = $this->Users_model->get_users_list($filter, null, null, true);

	    /*$config = $this->init_pagination('App/agency/index/page/',$total_rows,$filter['view']);

		//$limit_end = ($page * $config['per_page']) - $config['per_page'];
         $limit_end = ($page * $filter['view']) - $filter['view'];
	    if ($limit_end < 0){
	        $limit_end = 0;
	    }

	    //$filter['limit'] = $config['per_page'];
        $filter['limit'] = $filter['view'];
	    $filter['offset'] = $limit_end;*/
        if(!$this->session->userdata('from_begining') || $this->session->userdata('from_begining')=='yes')
        {
            //$this->session->unset_userdata('from_begining');
            $this->session->set_userdata('from_begining','yes');
            
        }
        else
        {
            //$this->session->unset_userdata('from_begining');
            $this->session->set_userdata('from_begining','no');
           
        }
	    // Get the Users List
	    $data['users'] = $this->Users_model->get_users_list($filter, 'id', 'DESC');

	    
    	$data['filters'] = $uri;
	    $data['page'] = 'users';
    	$data['page_title'] = SITE_NAME.' :: Users Management';

    	$data['main_content'] ='users/users_list';
		
    	$this->load->view(TEMPLATE_PATH, $data);
	}



    /**
     *
     */
    public function add() {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    	$config = $this->config->item('module_config');

    	//if save button was clicked, get the data sent via post
    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{

            $input=$this->input->post();
            $input_meta=$this->input->post('meta');

			$wine_id_array = $this->input->post('wine_id');
			$sales_rep_id = $this->input->post('user_id');

			//Make wine id array to string
			$wine_id='';
			if(!empty($wine_id_array))
			{
				for($i=0;$i<count($wine_id_array);$i++)
				{
					$wine_id.=$wine_id_array[$i].",";
				}
				$wine_id=rtrim($wine_id,",");
			} 

            //Set input data to session
            $newUserdata = array(

                   'first_name'  => $input['first_name'],
                   'last_name'  => $input['last_name'],
                   'email'  => $input['email'],
                   'brand_id'  => $input['wine_id']
                   
               );
            $this->session->set_userdata('inputdata',$newUserdata);


            //Set input data to Meta Table
			$metadata = array(
				'brand_id'  => $wine_id,
				'sales_rep_id' => $sales_rep_id				
			);

			// print_r($metadata);die;

     		//form validation
    		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
    		$this->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email|xss_clean');
	    	$this->form_validation->set_rules('password', 'Password', 'matches[c_password]');
	    	$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required');


    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    		//if the form has passed through the validation

    		if($this->form_validation->run())
    		{
				$raw_password = htmlspecialchars($this->input->post('password'), ENT_QUOTES, 'utf-8');

    			$user = array(
					'first_name' => htmlspecialchars($this->input->post('first_name'), ENT_QUOTES, 'utf-8'),
					'last_name' => htmlspecialchars($this->input->post('last_name'), ENT_QUOTES, 'utf-8'),
                    'role_id' => '6',
    				'user_type'=> htmlspecialchars($this->input->post('user_type'), ENT_QUOTES, 'utf-8'),
    				'email' => htmlspecialchars($this->input->post('email'), ENT_QUOTES, 'utf-8'),
    				'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
    				'password' => md5($raw_password),
    				'created_by' => $this->session->userdata('id'),
     				'created_on' => date('Y-m-d H:i:s')
    			);

    
    			//Check duplicate email
                $email=$this->input->post('email');
                $response=$this->Users_model->check_duplicate_email($email);
                
                if($response==0)
                {

                    //if the insert has returned true then we show the flash message
                    if ($user_id = $this->Users_model->insert($this->tablename, $user)) {

                        // Insert the Mata Data
                        $this->Users_model->replace_user_meta($user_id, $metadata);
                        $this->session->unset_userdata('inputdata');

                        $this->session->set_flashdata('message_type', 'success');
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> User have been added successfully.');
                    } else {
						$this->session->unset_userdata('inputdata');
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> User already exists.');
                    }
                    redirect('/App/users');
                }
                else
                {
					
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Email ID already exists.');
                    redirect('App/users/add');
                }
    			
    		} //validation run
    	}
        //get zone
        // $data['zone']=$this->Tester_model->get_zone_list();
		//get tester
        // $data['tester']=$this->Users_model->get_tester_list();
		//get wine
		$data['wine']=$this->Users_model->get_all_wine();
		//Get sales representative
        $data['sales_rep']=$this->Job_model->get_sales_rep();
		$data['id'] = 0;
		// echo "Welcome";die;
		// echo "<pre>";
		// print_r($data['sales_rep']);die;

        $data['page'] = 'agency';
    	$data['page_title'] = SITE_NAME.' :: Agency Management &raquo; Add Agency';

    	$data['main_content'] = 'users/add_user';
    	$this->load->view(TEMPLATE_PATH, $data);
    }


	/**
	 *
	 * @param unknown_type $id
	 */
	public function edit($id = 0) {
        $this->session->set_userdata('from_begining','no');
 
		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

		if ($id === 0) {
			$id = $this->session->userdata('id');
		}
        $is_deleted = check_is_deleted('users',$id);
        if($is_deleted==false)
        {
          redirect('/App/users');
        }
		// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    	
     	//if save button was clicked, get the data sent via post
     	if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
     		//form validation


			 $input=$this->input->post();
			 $input_meta=$this->input->post('meta');
 
			 $wine_id_array = $this->input->post('wine_id');
			 $sales_rep_id = $this->input->post('user_id');

			 //Make wine id array to string
			 $wine_id='';
			 if(!empty($wine_id_array))
			 {
				 for($i=0;$i<count($wine_id_array);$i++)
				 {
					 $wine_id.=$wine_id_array[$i].",";
				 }
				 $wine_id=rtrim($wine_id,",");
			 } 
 
			 //Set input data to session
			 $newUserdata = array(
 
					'first_name'  => $input['first_name'],
					'last_name'  => $input['last_name'],
					'email'  => $input['email'],
					'brand_id'  => $input['wine_id']
					
				);
			 $this->session->set_userdata('inputdata',$newUserdata);
 
 
			 //Set input data to Meta Table
			 $metadata = array(
				 'brand_id'  => $wine_id,
				 'sales_rep_id' => $sales_rep_id					
			 );
     		
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
    		$this->form_validation->set_rules('email', 'Email address', 'trim|required');
    		if ($this->input->post('password')) {
	    		$this->form_validation->set_rules('password', 'Password', 'matches[c_password]');
	    		$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required');
    		}
			
			// echo "<pre>";
			// print_r($data);die;

            // if(is_array($data['user_meta'])){

            //     foreach ($data['user_meta'] as $key=>$value) {
            //         $field_name = $key;
            //         $field_label = $key;
            //         if (isset($value['required']) && $value['required'] == true) {
            //             $this->form_validation->set_rules($field_name, $field_label, 'required');
            //         }
            //     }
            // }
     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
     		//if the form has passed through the validation

     		if ($this->form_validation->run())
     		{
     			$user = array(
    				'first_name' => htmlspecialchars($this->input->post('first_name'), ENT_QUOTES, 'utf-8'),
                    'last_name' => htmlspecialchars($this->input->post('last_name'), ENT_QUOTES, 'utf-8'),
                    'user_type'=>htmlspecialchars($this->input->post('user_type'), ENT_QUOTES, 'utf-8'),
                    'email' => htmlspecialchars($this->input->post('email'), ENT_QUOTES, 'utf-8'),
                    'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
     				'updated_by' => $this->session->userdata('id'),
     				'updated_on' => date('Y-m-d H:i:s')
    			);

    			$meta = $this->input->post('meta');

    			if ($this->input->post('password')) {
    				$user['password'] = md5(htmlspecialchars($this->input->post('password'), ENT_QUOTES, 'utf-8'));
    			}
				$email=$this->input->post('email');
                $response=$this->Users_model->check_duplicate_email_with_id($email,$id);
			
     			//if the insert has returned true then we show the flash message
				if($response == 0){
					if ($this->Users_model->update($this->tablename, 'id', $id, $user)) {

						// Insert the Mata Data
						$this->Users_model->replace_user_meta($id, $metadata);

						$this->session->set_flashdata('message_type', 'success');
						if ($this->input->post('ref') == 'profile') {
							$this->session->set_flashdata('message', '<strong>Well done!</strong> Profile successfully updated.');
						} else {
							$this->session->set_userdata('from_begining','yes');
							$this->session->set_flashdata('message', '<strong>Well done!</strong> User successfully updated.');
						}
					} else{
						$this->session->set_flashdata('message_type', 'danger');
						$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
					}

					// If from profile page - redirect there
					if ($this->input->post('ref') == 'profile') {
						redirect('/profile');
					}

					redirect('/App/users');
				}else{
					$this->session->set_flashdata('message_type', 'danger');
					$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Email ID already exists.');
					redirect('App/users/edit');
				}
        
     		} //validation run
     	}
		
     	$data['usersDetails']  = $this->Users_model->get_users_details($id);

     	if (!is_numeric($id) || $id == 0 || empty($data['usersDetails'])) {
     		redirect('/App/users');
     	}

		
		$userMeta = $this->Users_model->get_user_meta($id);
		$data['sales_rep_id']=$userMeta[1]->meta_value;
		//get wine
		$data['wine']=$this->Users_model->get_all_wine();
		$data['sales_rep']=$this->Job_model->get_sales_rep();

     	$data['page'] = 'users';
    	$data['page_title'] = SITE_NAME.' :: Users Management &raquo; Edit User';

    	$data['main_content'] = 'users/edit_user';

    	$this->load->view(TEMPLATE_PATH, $data);
    }

    public function update_status() {
        $this->session->set_userdata('from_begining','no');
    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
    		//form validation
    		$this->form_validation->set_rules('operation', 'Operation', 'required');
    		$this->form_validation->set_rules('item_id[]', 'User', 'trim|required');

    		$this->form_validation->set_error_delimiters('', '');

    		//if the form has passed through the validation
    		if ($this->form_validation->run())
    		{
    			//print "<pre>"; print_r($_POST);die;
    			$count = 0;
    			$items = $this->input->post('item_id');
    			$operation = $this->input->post('operation');


	    		$data_to_store = array(
		    		'status' => ($operation == "active")?'active':'inactive'
		    	);

				echo "<pre>";
				print_r("Welcome");die;
    			foreach ($items as $id=>$value) {

    				// Restrict to update yourself
    				if ($id == $this->session->userdata('id')) {
    					continue;
    				}
                    $check=$this->Users_model->check_assigned_job('job', $id);
    				if ($operation == 'delete') {
                        if($check==0)
                        {


        					if ($this->Users_model->delete($this->tablename, $id)) {
    	    					$count++;
    	    				}
                        }
    				} else {
                        /*if($check==0)
                        {
    	    				if ($this->Agency_model->update($this->tablename, 'id', $id, $data_to_store)) {
    	    					$count++;
    	    				}
                        }*/
                        if ($this->Users_model->update($this->tablename, 'id', $id, $data_to_store)) {
                                $count++;
                            }
    				}
    			}

    			$msg = ($operation=='delete')?'deleted.':'updated.';

    			$this->session->set_flashdata('message_type', 'success');
    			$this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' user(s) successfully '.$msg);

    		} else {
    			$this->session->set_flashdata('message_type', 'danger');
    			$this->session->set_flashdata('message', validation_errors());
    		}
    		redirect('/App/users/profile');
    	}
    }

	public function profile() {
        $id = $this->session->userdata('id');
        // print_r($id);die;
        $this->load->model('Users_model');
        // Include the Module JS file.
        add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
        $config = $this->config->item('module_config');

        //get usermeta
        $this->db->select('*');
        $this->db->from('user_meta');
        $this->db->where('user_meta.user_id',$id);
        
        $user_meta = $this->db->get();
        $data['zone']=$this->Users_model->get_zone_list();
        
        $data['user_meta']=$user_meta->result_array();

        $data['user']  = $this->Users_model->get_user_details($id);

        // if (!is_numeric($id) || $id == 0 || empty($data['user'])) {
        //     redirect('Agency/dashboard');
        // }

        // Roles List (for dropdown)
        $data['roles'] = $this->Users_model->get_roles_list();

		$data['page'] = 'profile';
    	$data['page_title'] = SITE_NAME.' :: Update Profile';

    	$data['main_content'] = 'users/users_profile';
    	$this->load->view(TEMPLATE_PATH, $data);

    }



	public function edit_profile($id = 0) {

        
        // print_r($this->permissionValues[$this->router->method]);die;
        // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        // print_r("Hello");die;
        $this->load->model('Users_model');
        if ($id === 0) {
            $id = $this->session->userdata('id');
        }

        // Include the Module JS file.
        add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
        $config = $this->config->item('module_config');

        //get usermeta
        $this->db->select('*');
        $this->db->from('user_meta');
        $this->db->where('user_meta.user_id',$id);
        
        $user_meta = $this->db->get();

        
        $data['user_meta']=$user_meta->result_array();
        // echo "<pre>";
        // print_r($data['user_meta']);

        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
            $this->form_validation->set_rules('role_id', 'Role', 'trim|required');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email address', 'trim|required');

            if ($this->input->post('password')) {
                // echo "Hi Change password";die;
                $this->form_validation->set_rules('password', 'Password', 'matches[c_password]');
                $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required');
            }

            // print_r($this->input->post('password'));die;
            // Custom field validation
            if(is_array($data['user_meta'])){

                foreach ($data['user_meta'] as $key=>$value) {
                    $field_name = $key;
                    $field_label = $key;
                    if (isset($value['required']) && $value['required'] == true) {
                        $this->form_validation->set_rules($field_name, $field_label, 'required');
                    }
                }
            }

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            //if the form has passed through the validation

            if ($this->form_validation->run())
            {
                // echo "Hi Everything is done";die;
                $user = array(
                    'first_name' => htmlspecialchars($this->input->post('first_name'), ENT_QUOTES, 'utf-8'),
                    'last_name' => htmlspecialchars($this->input->post('last_name'), ENT_QUOTES, 'utf-8'),
                    'role_id' => htmlspecialchars($this->input->post('role_id'), ENT_QUOTES, 'utf-8'),
                    'email' => htmlspecialchars($this->input->post('email'), ENT_QUOTES, 'utf-8'),
                    'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
                    'updated_by' => $this->session->userdata('id'),
                    'updated_on' => date('Y-m-d H:i:s')
                );

                $meta = $this->input->post('meta');
                // $data['user_meta'][0]['meta_value'] = $meta['phone'];
                // $data['user_meta'][1]['meta_value'] = $meta['adress'];
 
                // print_r($data['user_meta']);die;
               
                	 //Set input data to Meta Table
			 $metadata = array(
                // 'phone'  => $meta['phone'],
                // 'adress'  => $meta['adress'],
                'brand_id'  => $data['user_meta'][0]['meta_value'],				
            );
            



                if ($this->input->post('password')) {
                    $user['password'] = md5(htmlspecialchars($this->input->post('password'), ENT_QUOTES, 'utf-8'));
                }

                //if the insert has returned true then we show the flash message
                if ($this->Users_model->update_profile('id', $id, $user)) {

                    // Insert the Mata Data
                    $this->Users_model->replace_user_meta($id, $metadata);

                    $this->session->set_flashdata('message_type', 'success');
                    if ($this->input->post('ref') == 'profile') {
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> Profile successfully updated.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> User successfully updated.');
                    }
                } else{
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
                }

                // If from profile page - redirect there
                if ($this->input->post('ref') == 'profile') {
                    redirect('App/users/profile');
                }

                redirect('App/users/profile');
            } //validation run
        }

        $data['user'] = $this->Users_model->get_user_details($id);

        if (!is_numeric($id) || $id == 0 || empty($data['user'])) {
            redirect('App/users/profile');
        }

        // Roles List (for dropdown)
        $data['roles'] = $this->Users_model->get_roles_list();
        $data['zone']=$this->Users_model->get_zone_list();
        $data['page'] = 'users';
        $data['page_title'] = SITE_NAME.' :: User Management &raquo; Edit User';

        $data['main_content'] = 'users/users_profile';
        $this->load->view(TEMPLATE_PATH, $data);
    }

	/**
     *
     * @param int $id
     */
    public function delete($id = null) {
        $this->session->set_userdata('from_begining','no');
		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Can't delete yourself
    	if ($id == $this->session->userdata('id')) {
    		$this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');

            redirect('/App/users');
    	}

    	$data['info'] = $this->Users_model->get_users_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/App/users');
    	}
        $check=$this->Users_model->check_assigned_job('job', $id);
        if($check==0)
        {
            if ($this->Users_model->delete($this->tablename, $id)) {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', '<strong>Well done!</strong> Agency successfully deleted.');
            } else {
                $this->session->set_flashdata('message_type', 'danger');
                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
            }
        }
      	else
        {
            $this->session->set_flashdata('message_type', 'danger');
                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The agency is assotiated with a job.');
        }
    	redirect('/App/users');
    }

        public function temp_delete($id = null) {
            $this->session->set_userdata('from_begining','no');
		// Permission Checking
		//parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Can't delete yourself
    	if ($id == $this->session->userdata('id')) {
    		$this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');

            redirect('/App/users');
    	}

    	$data['info'] = $this->Users_model->get_users_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/App/users');
    	}
        $check=$this->Users_model->check_assigned_job('job', $id);
        if($check==0)
        {
            $data=array(
                'is_deleted'=>1
            );
            if ($this->Users_model->update($this->tablename,'id', $id,$data)) {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', '<strong>Well done!</strong> Agency successfully deleted.');
            } else {
                $this->session->set_flashdata('message_type', 'danger');
                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
            }
        }
      	else
        {
            $this->session->set_flashdata('message_type', 'danger');
                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The agency is assotiated with a job.');
        }
    	redirect('/App/users');
    }

	/**
     *
     */
    
    function view_user_details()
    {
        $user_id=$this->input->post('user_id');
		
        $data['user']=$this->Users_model->get_users_details($user_id);
        $zone=array();
        // foreach($data['user']->meta as $meta)
        // {
        //     if($meta->meta_key=='zone')
        //     {
        //         $zone_id=$meta->meta_value;

        //         $zone_id_array=explode(",",$zone_id);
		// 		foreach($zone_id_array as $id)
        //         {
		// 			$zone_name=$this->Users_model->get_zone_name($id);
		// 			array_push($zone,$zone_name);
		// 		} 
        //         $meta->meta_value=implode(', ',$zone);
        //     }
        // }
		// echo "<pre>";
		// print_r($data);die;
		
        $this->load->view('users/user_details_modal', $data);
    }

/*
	function taster_under_agency()
    {
        $user_id=$this->input->post('user_id');
		//Get tester under agency
        $data['tester_details']=$this->Agency_model->get_tester_under_agency($user_id);
        $this->load->view('agency/taster_under_agency_modal', $data);
    }*/
	
	function reset_pass($id = null) {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

		$data['info'] = $this->Agency_model->get_agency_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/app/agency');
    	}

		$password = $this->random_string();
		$user['password'] = md5($password);

		//if the insert has returned true then we show the flash message
		if ($this->Agency_model->update($this->tablename, 'id', $id, $user)){

			$name = $data['info']->first_name . " " . $data['info']->last_name;
			$email = $data['info']->email;

			// Send Email to users
			//$this->load->library('mail_template');
			//$this->mail_template->new_password_email($name, $email, $password);

			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', '<strong>Well done!</strong> Password successfully updated and emailed to sales representative.');
		} else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
    	redirect('/app/agency');
	}
	
	public function search_submit() {

    	if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //$name = $this->clean_value($this->input->post('name'));
            $name = $this->input->post('name');

            $url = "App/users/index/";

            if ($name != '') {
                $url .= "name/". urlencode($name)."/";
            }

            if ($size_id != '') {
                $url .= "size_id/". urlencode($size_id)."/";
            }
            redirect($url);

            
        }
    }

	public function get_search_options() {

		if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
    		//
    		$field = $this->input->post('field');
			$ope = $this->input->post('ope');
			$q = $this->input->post('q');

			switch($field) {
				case 'name':
				case 'email':
				case 'phone':
				case 'social_id':
				case 'latest_version':
					$data['search_field'] = '<input type="text" class="form-control" id="inputSearch" name="q" placeholder="Search here" value="'.$q.'" required="">';

					$data['search_ope'] = '<select name="operator" id="inputOperator" class="form-control" required >
	  						<option value="" selected>Select an operator</option>
	  						<option value="contains" ';
							if ($ope == 'contains') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= ' >Contains</option>';
							$data['search_ope'] .= '<option value="equals" ';
							if ($ope == 'equals') {
								$data['search_ope'] .= ' selected ';
							}
	  						$data['search_ope'] .= ' >Equals</option>';
	  						$data['search_ope'] .= '<option value="notequal" ';
							if ($ope == 'notequal') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= '>Doesn\'t Equal</option>
	  					</select>';
				break;

				case 'dob':
				case 'created_on':
				case 'last_opened':

					$q = str_replace('~', '/', $q);

					$data['search_field'] = '<div class="input-group">
      						<input type="text" name="q" class="form-control calender-control" id="inputSearch" placeholder="Search here" value="'.$q.'" required="" >
      						<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
    					  </div>';

					$data['search_ope'] = '<select name="operator" id="inputOperator" class="form-control" required >
	  						<option value="" selected>Select an operator</option>
							<option value="before" ';
							if ($ope == 'before') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= ' >Before</option>';
							$data['search_ope'] .= '<option value="after" ';
							if ($ope == 'after') {
								$data['search_ope'] .= ' selected ';
							}
	  						$data['search_ope'] .= ' >After</option>';
	  						$data['search_ope'] .= '<option value="between" ';
							if ($ope == 'between') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= '>Between</option>
	  					</select>';

				break;

				case 'gender':
					$data['search_field'] = '<select name="q" class="form-control" id="inputSearch">
						<option value="" selected>- Select a gender -</option>
						<option value="M" ';
						if ($q == 'M') {
							$data['search_field'] .= ' selected ';
						}
						$data['search_field'] .= ' >Male</option>';
						$data['search_field'] .= '<option value="F" ';
						if ($q == 'F') {
							$data['search_ope'] .= ' selected ';
						}
						$data['search_field'] .= '>Female</option>
					</select>';

					$data['search_ope'] = '<select name="operator" id="inputOperator" class="form-control" required >
	  						<option value="" selected>Select an operator</option>
							<option value="equals" ';
							if ($ope == 'equals') {
								$data['search_ope'] .= ' selected ';
							}
	  						$data['search_ope'] .= ' >Equals</option>';
	  						$data['search_ope'] .= '<option value="notequal" ';
							if ($ope == 'notequal') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= '>Doesn\'t Equal</option>
	  					</select>';
				break;

				case 'status':
					$data['search_field'] = '<select name="q" class="form-control" id="inputSearch">
						<option value="" selected>- Select a status -</option>
						<option value="active" ';
						if ($q == 'active') {
							$data['search_field'] .= ' selected ';
						}
						$data['search_field'] .= ' >Active</option>';
						$data['search_field'] .= '<option value="inactive" ';
						if ($q == 'inactive') {
							$data['search_ope'] .= ' selected ';
						}
						$data['search_field'] .= '>Inactive</option>
					</select>';

					$data['search_ope'] = '<select name="operator" id="inputOperator" class="form-control" required >
	  						<option value="" selected>Select an operator</option>
							<option value="equals" ';
							if ($ope == 'equals') {
								$data['search_ope'] .= ' selected ';
							}
	  						$data['search_ope'] .= ' >Equals</option>';
	  						$data['search_ope'] .= '<option value="notequal" ';
							if ($ope == 'notequal') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= '>Doesn\'t Equal</option>
	  					</select>';
				break;
			}

			$this->output
	        	->set_content_type('application/json')
	        	->set_output(json_encode($data));
		}
	}



    /**
     * Clean up by removing unwanted characters
     *
     * @param unknown_type $str
     */
    private function clean_value($str) {

		$str = str_replace('/', '~', $str);
		return preg_replace('/[^A-Za-z0-9_\-~]/', '', $str);
    }

	/**
     *
     * @param unknown_type $uri
     * @param unknown_type $total_rows
     * @param unknown_type $segment
     */
    private function init_pagination($uri,$total_rows,$view=NULL) {

    	$this->config->load('pagination');
    	$this->load->library('pagination');

    	$config = $this->config->item('pagination');

       	$ci                          =& get_instance();
       //	$config['uri_segment']       = $segment;
       	$config['base_url']          = base_url().$uri;
       	$config['total_rows']        = $total_rows;
        $config['per_page']        		= $view;
       	$ci->pagination->initialize($config);
       	return $config;
   }

   private function format_date($date) {
	   if ($date == "")
	   	return "";

	   $newdate = date_create($date);
	   return date_format($newdate,"Y-m-d");
   }
   
   
	private function redirectToURL() {
		
		// Get the reference URL
		$this->reference_url = $this->input->get('ref');
		
		if ($this->reference_url <> "") {
			redirect($this->reference_url);
		} else {
			redirect($this->url);
		}
	}
	
	/**
	 * @param int $limit
	 */
   	private function random_string($limit = 10) {

	    $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789!@#$%^&*()'); // and any other characters
	    shuffle($seed); // probably optional since array_is randomized; this may be redundant
		$rand = '';
		foreach (array_rand($seed, $limit) as $k) $rand .= $seed[$k];

		return $rand;
	}
    public function refresh()
    {
        $this->session->set_userdata('from_begining','yes');
        redirect($this->url);
    }
}
