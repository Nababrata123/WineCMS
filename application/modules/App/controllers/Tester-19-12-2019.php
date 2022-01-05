<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tester extends Application_Controller {

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
	private $url = '/app/tester';
	private $reference_url = '';
	private $permissionValues = array(

		'index' => 'App.Tester.View',
		'add' => 'App.Tester.Add',
		'edit' => 'App.Tester.Edit',
        'delete' => 'App.Tester.Delete',
        'reset_pass' => 'App.Tester.ResetPass',
    );

    //private $allowed_roles = array('bar_admin');

	public function __construct() {
		

        parent::__construct();

		// Validate Login
		parent::checkLoggedin();
        $this->load->model('Tester_model');

		$this->module_dir = APPPATH.'modules/'.$this->router->fetch_module();
        $this->load->config('config');
        //echo "11";die;
		$this->session->set_userdata('page_data', array('url' => $this->url, 'permissions' => $this->permissionValues));
       
        $this->load->library('user_agent');
        $this->load->helper('template');
    }

	public function index() {
        

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        // Include the Module JS file.
        add_js('assets/modules/'.$this->router->fetch_module().'/js/AppTester.js');
        add_js('assets/js/plugins/colResizable-1.6.min.js');
        $filter = array('is_deleted' => 0);
		$default_uri = array( 'page', 'status','field', 'ope', 'q','action','agency','view');
    	$uri = $this->uri->uri_to_assoc(4, $default_uri);
        $pegination_uri = array();
    	//$role = $uri['role'];
		$status = $uri['status'];

		if ($uri['page'] > 0) {
    		$page = $uri['page'];
    	} else {
    		$page = 0;
    	}

    	//echo 1;die;
        
        // Create the filters
	    /*if ($uri['view'] <> "") {
            $filter['view'] 		= $uri['view'];
			$pegination_uri['view'] = $uri['view'];
        } else {
			$filter['view'] 		= 10;
			$pegination_uri['view'] = 10;
		}*/
        if ($uri['field'] <> "") {
            $filter['field'] = $uri['field'];
            $pegination_uri['field'] = $uri['field'];
        } else {
            $filter['field'] = "";
            $pegination_uri['field'] = "~";
        }
        if ($uri['ope'] <> "") {
            $filter['ope'] = $uri['ope'];
            $pegination_uri['ope'] = $uri['ope'];
        } else {
            $filter['ope'] = "";
            $pegination_uri['ope'] = "~";
        }
        if ($uri['q'] <> "") {
            $filter['q'] = trim(urldecode($uri['q']));
            $pegination_uri['q'] = $uri['q'];
        } else {
            $filter['q'] = "";
            $pegination_uri['q'] = "~";
        }

        if ($status <> '') {
            $filter['status'] = $status;
        } else {
            $status = 0;
        }

	    

	    if ($status <> '') {
		    $filter['status'] = $status;
	    } else {
	    	$status = 0;
	    }
        
        if ($uri['agency'] <> "") {
            $filter['agency'] = $uri['agency'];
            //$pegination_uri['agency'] = $uri['agency'];
        } else {
            $filter['agency'] = "";
            //$pegination_uri['agency'] = "~";
        }

	    // Get the total rows without limit
	    $total_rows = $this->Tester_model->get_tester_list($filter, null, null, true);

	    /*$config = $this->init_pagination('App/tester/index/'.$this->uri->assoc_to_uri($pegination_uri).'//page/',13, $total_rows,$filter['view']);


		//$limit_end = ($page * $config['per_page']) - $config['per_page'];
        $limit_end 			= ($page * $filter['view']) - $filter['view'];
	    if ($limit_end < 0){
	        $limit_end = 0;
	    }

	    //$filter['limit'] = $config['per_page'];
        $filter['limit'] = $filter['view'];
	    $filter['offset'] = $limit_end;*/

	    // Get the Users List
	    //$data['users'] = $this->Tester_model->get_tester_list($filter, 'id', 'asc');

	    //Get agency list for search
        //$data['all_agency'] = $this->Tester_model->get_agency_list_for_search();
        //echo "<pre>";
        //print_r($data['all_agency']);die;
    	$data['filter'] = $filter;
	    $data['page'] = 'tester';
    	$data['page_title'] = SITE_NAME.' :: Tester Management';

    	$data['main_content'] ='tester/list';
    	$this->load->view(TEMPLATE_PATH, $data);
	}

    public function test()
    {
        //echo 1;die;
        $data = array();
        //print "<pre>";print_r($this->input->post());print "</pre>";
        // Get the stores List
        $memData=$this->Tester_model->get_datatables($this->input->post());
        

        //$i = $this->input->post('start');
        foreach($memData as $member){
            $row = array();
           // $i++;
           // $created = date( 'jS M Y', strtotime($member->created));
            if ($member->status == "active") 
            {
                $status= '<span class="label label-success">Active</span>';
            } 
            else {
                $status= '<span class="label label-warning">In-active</span>';
            }
            $id=$member->id;
            $CI =& get_instance();
            $CI->load->helper('template');
            $zone=get_user_meta($id);
            if($member->last_login)
            {
                $last_login=datetime_display($member->last_login);
            }
            else
            {
                $last_login='--';
            }
            $details='<a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_modal('.$id.')">
                            <span class="glyphicon glyphicon-eye-open"></span> View
                        </a>';
            $created_by=$member->created_by;
                    if($created_by==7)
                    {
                       // $name=get_username('admin',$created_by);
                        $c_name='Self';
                    }
                    else
                    {
                        $c_name=get_username('agency',$created_by);
                        
                    }
            $reset_password_link=base_url('App/tester/reset_pass/'.$id);
            $edit_link=base_url('App/tester/edit/'.$id);
            if($id==$this->session->userdata('id'))
            {
                $p='disabled';
            }
            else
            {
                $p='';
            }
            $confirm="return confirm('Do you really want to reset the password for this user?');";
            $delete_script='<script>$(".delete_button").click(function(){var id=$(this).data("id");$("#permanent_delete").data("id",id);$("#delete").data("id", id);$("#myDeleteModal").modal("show");});</script>';
            $action='<a class="btn btn-info btn-xs" href="'.$reset_password_link.'" onclick="'.$confirm.'" title="Reset Password">
                            <span class="glyphicon glyphicon-lock"></span> Reset Password
                        </a>
                        <a class="btn btn-primary btn-xs" href="'.$edit_link.'" title="Edit">
                            <span class="glyphicon glyphicon-edit"></span> Edit
                        </a>
                        
                        <a class="btn btn-danger btn-xs delete_button '.$p.'" href="javascript:void(0)"  title="Delete" data-id="'.$id.'">
                            <span class="glyphicon glyphicon-trash"></span> Delete
                        </a>'.$delete_script;
            $checked="item_id[".$id."]";
            $checkop='<input type="checkbox" name="'.$checked.'" class="checkbox-item" value="Y">';
           // echo $zone;
            $name= $member->first_name." ".$member->last_name;
            $row[]=$checkop;
            $row[]=$name;
            $row[]=$member->email;
            $row[]=$zone;
            $row[]=$last_login;
            $row[]=$status;
            $row[]=$details;
            $row[]=$c_name;
            $row[]=$action;
            $data[]=$row;
            //$data[] = array( $checkop,$name, $member->email,$zone,$last_login, $status,$details,$c_name,$action);
        }
        //echo "<pre>";
       // print_r($data);
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->Tester_model->countAll(),
            "recordsFiltered" => $this->Tester_model->countFiltered($this->input->post()),
            "data" => $data,
        );
        
        
        // Output to JSON format
        echo json_encode($output);

    }


    /**
     *
     */
    public function add() {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/AppTester.js');
    	$config = $this->config->item('module_config');

    	//$data['user_meta'] = $config['users']['meta'];

    	//if save button was clicked, get the data sent via post
    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            
            $input=$this->input->post();
            $input_meta=$this->input->post('meta');
            
            //Set input data to session
            $newdata = array(

                   'first_name'  => $input['first_name'],
                   'last_name'  => $input['last_name'],
                   'email'  => $input['email'],
                   'city'  => $input_meta['city'],
                   'state'  => $input_meta['state'],
                   'zipcode'  => $input_meta['zipcode'],
                   'address'  => $input_meta['address'],
                   'suite_number'  => $input_meta['suite_number'],
                   'phone'  => $input_meta['phone'],
                   'rate_per_hour'  => $input_meta['rate_per_hour'],
                   'zone'  => $input_meta['zone'],
                   'vendor_number'  => $input_meta['manual_account_number'],
               );

            $this->session->set_userdata('inputdata',$newdata);

            $data['user_meta'] = $this->input->post('meta');
            $agency_id=$this->input->post('agency_id');

     		//form validation
     		//$this->form_validation->set_rules('role_id', 'Role', 'trim|required');

    		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
    		$this->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email|xss_clean');
	    	$this->form_validation->set_rules('password', 'Password', 'matches[c_password]');
	    	$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required');

			// Custom field validation
            $meta = $this->input->post('meta');
			if(is_array($data['user_meta'])){

		    	foreach ($data['user_meta'] as $key=>$value) {
		    		$field_name = $key;
		    		$field_label = $key;
                    if($agency_id=="")
                    {
                        if($field_name=='city')
                        {
                            
                            if($meta['city']=='')
                            {
                                $this->form_validation->set_rules($field_name, 'City', 'required');
                            }
                            
                        }
                        if($field_name=='state')
                        {
                            
                            if($meta['state']=='')
                            {
                                $this->form_validation->set_rules($field_name, 'State', 'required');
                            }
                            
                        }
                        if($field_name=='zipcode')
                        {
                            
                            if($meta['zipcode']=='')
                            {
                                $this->form_validation->set_rules($field_name, 'Zipcode', 'required');
                            }
                            
                        }
                        //Check duplicate vendor number
                        if($field_name=='manual_account_number')
                        {
                            $vendor_number=$meta['manual_account_number'];
                            $vendor=$this->Tester_model->check_duplicate_vendor_number($vendor_number);
                            if($vendor!=0)
                            {
                                $this->session->set_flashdata('message_type', 'danger');
                                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Vendor number already exists.');
                                redirect('/App/tester/add');
                            }
                            
                        }
                        
                    }
		    		if (isset($value['required']) && $value['required'] == true) {
                        
		    			$this->form_validation->set_rules($field_name, $field_label, 'required');
		    		}
		    	}
	    	}

    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    		//if the form has passed through the validation

    		if($this->form_validation->run())
    		{
    			
				$raw_password = htmlspecialchars($this->input->post('password'), ENT_QUOTES, 'utf-8');

    			$user = array(
					'first_name' => htmlspecialchars($this->input->post('first_name'), ENT_QUOTES, 'utf-8'),
					'last_name' => htmlspecialchars($this->input->post('last_name'), ENT_QUOTES, 'utf-8'),
    				'user_type'=>htmlspecialchars($this->input->post('user_type'), ENT_QUOTES, 'utf-8'),
    				'email' => htmlspecialchars($this->input->post('email'), ENT_QUOTES, 'utf-8'),
    				'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
    				'password' => md5($raw_password),
    				//'created_by' => $this->session->userdata('id'),
     				'created_on' => date('Y-m-d H:i:s')
    			);

                if($this->input->post('agency_id')!="")
                {
                    $user['created_by']=$this->input->post('agency_id');
                }
                else
                {
                    $user['created_by']=$this->session->userdata('id');
                }

    			$meta = $this->input->post('meta');
              // echo "<pre>";
              // print_r($meta);die;
    			
                $email=$this->input->post('email');
                $response=$this->Tester_model->check_duplicate_email($email);
    			//if the insert has returned true then we show the flash message
                if($response==0)
                {
        			if ($user_id = $this->Tester_model->insert($this->tablename, $user)) {

        				// Insert the Mata Data
        				$this->Tester_model->replace_user_meta($user_id, $meta);

                        $this->session->unset_userdata('inputdata');

    					// Send Email to users
    					$this->load->library('mail_template');
                        //$activation_link = BASE_URL.'recover_password/'.md5(time()).'/'.base64_encode($user_id);
        				$this->mail_template->new_user_email($user['first_name'] . " " .$user['last_name'], $user['email'], $raw_password);

        				$this->session->set_flashdata('message_type', 'success');
        				$this->session->set_flashdata('message', '<strong>Well done!</strong> User have been added successfully.');
        			} else {

        				$this->session->set_flashdata('message_type', 'danger');
        				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> User already exists.');
        			}
        			redirect('/App/tester');
                }
                else
                {
                    $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Email ID already exists.');
                    redirect('/App/tester/add');
                }
    		} //validation run
    	}

		//get zone
        $data['zone']=$this->Tester_model->get_zone_list();
        //Get agency
        $data['agency']=$this->Tester_model->get_agency_list();

    	$data['page'] = 'tester';
    	$data['page_title'] = SITE_NAME.' :: Sales Representative Management &raquo; Add Sales Representative';
        
    	$data['main_content'] = 'tester/add';
    	$this->load->view(TEMPLATE_PATH, $data);
    }
    // My callback function
    public function check_duplicate_email($post_email) {

        return $this->Tester_model->checkDuplicateEmail($post_email);

    }

	/**
	 *
	 * @param unknown_type $id
	 */
	public function edit($id = 0) {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

		if ($id === 0) {
			$id = $this->session->userdata('id');
		}
        $is_deleted = check_is_deleted('users',$id);
        if($is_deleted==false)
        {
           redirect('/App/tester');
        }
		// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/AppTester.js');
    	

    	//get usermeta
        $this->db->select('*');
        $this->db->from('user_meta');
        $this->db->where('user_meta.user_id',$id);
        
        $user_meta = $this->db->get();

        
        $data['user_meta']=$user_meta->result_array();
        $meta_array=array();
        foreach($data['user_meta'] as $m)
        {
            $key=$m['meta_key'];
            $value=$m['meta_value'];
            //array_push($meta_array,$meta_array[$key] = $value);
            $meta_array[$key] = $value;
        }
        /*echo "<pre>";
        print_r($meta_array);die;*/
        $data['user_meta']=$meta_array;



     	//if save button was clicked, get the data sent via post
     	if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
     		//form validation
            $data['user_meta'] = $this->input->post('meta');
     		
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email|xss_clean');
            

    		if ($this->input->post('password')) {
	    		$this->form_validation->set_rules('password', 'Password', 'matches[c_password]');
	    		$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required');
    		}

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

     			//if the insert has returned true then we show the flash message
     			if ($this->Tester_model->update($this->tablename, 'id', $id, $user)) {

     				// Insert the Mata Data
    				$this->Tester_model->replace_user_meta($id, $meta);

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
     				redirect('/profile');
     			}

     			redirect('/App/tester');
                //redirect($this->agent->referrer());
                
     		} //validation run
     	}

     	$data['tester']  = $this->Tester_model->get_tester_details($id);
     	//echo "<pre>";
     	//print_r($data['tester']);die;

     	if (!is_numeric($id) || $id == 0 || empty($data['tester'])) {
     		redirect('/App/tester');
     	}

        //get zone
        $this->db->select('*');
        $this->db->from('zone');
        $this->db->where('zone.status','active');
        $this->db->where('zone.is_deleted',0);
        $zone = $this->db->get();

        
        $data['zone']=$zone->result();
        //Get agency
        $data['agency']=$this->Tester_model->get_agency_list();
		
        //Get tester under agency
        $data['taster_name']=$this->Tester_model->get_created_by($id);
     	$data['page'] = 'tester';
    	$data['page_title'] = SITE_NAME.' :: Tester Management &raquo; Edit Sales Representative';

    	$data['main_content'] = 'tester/edit';
    	$this->load->view(TEMPLATE_PATH, $data);
    }
    public function update_status() {


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

    			foreach ($items as $id=>$value) {

    				// Restrict to update yourself
    				if ($id == $this->session->userdata('id')) {
    					continue;
    				}
                    $check=$this->Tester_model->check_assigned_job('job', $id);
    				if ($operation == 'delete') {
                        if($check==0)
                        {
                            if ($this->Tester_model->delete($this->tablename, $id)) {
                            $count++;
                            }
                        }
    					
    				} else {
                        if($check==0)
                        {
    	    				if ($this->Tester_model->update($this->tablename, 'id', $id, $data_to_store)) {
    	    					$count++;
    	    				}
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
    		redirect('/App/tester');
    	}
    }
    public function profile() {

		$id = $this->session->userdata('id');

		// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/AppTester.js');
    	$config = $this->config->item('module_config');

    	$data['user_meta'] = $config['users']['meta'];

     	$data['user']  = $this->Admin_model->get_user_details($id);

     	if (!is_numeric($id) || $id == 0 || empty($data['user'])) {
     		redirect('/dashboard');
     	}

		// Roles List (for dropdown)
    	$data['roles'] = $this->Admin_model->get_roles_list();

     	$data['page'] = 'profile';
    	$data['page_title'] = SITE_NAME.' :: Update Profile';

    	$data['main_content'] = 'users/profile';
    	$this->load->view(TEMPLATE_PATH, $data);
    }
	/**
     *
     * @param int $id
     */
    public function delete($id = null) {
        
		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Can't delete yourself
    	if ($id == $this->session->userdata('id')) {
    		$this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');

            redirect('/App/tester');
    	}

    	$data['info'] = $this->Tester_model->get_tester_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/App/tester');
    	}
        $check=$this->Tester_model->check_assigned_job('job', $id);
        if($check==0)
        {
            if ($this->Tester_model->delete($this->tablename, $id)) 
            {
                //Delete user meta
                if($this->Tester_model->delete_user_meta('user_meta', $id))
                {
                
                    $this->session->set_flashdata('message_type', 'success');
                    $this->session->set_flashdata('message', '<strong>Well done!</strong> Taster successfully deleted.');
                } else {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
                }
            }
        }
      	else
        {
            $this->session->set_flashdata('message_type', 'danger');
                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The taster is associated with a job.');
        }
    	redirect('/App/tester');
    }
    public function temp_delete($id = null) {

		// Permission Checking
		//parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Can't delete yourself
    	if ($id == $this->session->userdata('id')) {
    		$this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');

            redirect('/App/tester');
    	}

    	$data['info'] = $this->Tester_model->get_tester_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/App/tester');
    	}
        $check=$this->Tester_model->check_assigned_job('job', $id);
        if($check==0)
        {
            $data=array(
                'is_deleted'=>1
            );
            if ($this->Tester_model->update($this->tablename,'id', $id,$data)) {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', '<strong>Well done!</strong> Taster successfully deleted.');
            } else {
                $this->session->set_flashdata('message_type', 'danger');
                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
            }
        }
      	else
        {
            $this->session->set_flashdata('message_type', 'danger');
                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The taster is associated with a job.');
        }
    	redirect('/App/tester');
    }

	/**
     *
     */
    
    function view_user_details()
    {
        $user_id=$this->input->post('user_id');
        $data['user']=$this->Tester_model->get_user_details($user_id);
        $data['user_meta']=$this->Tester_model->get_user_meta($user_id);
        $zone='';
        foreach($data['user_meta'] as $meta)
        {
            if($meta->meta_key=='zone')
            {
                $zone_id=$meta->meta_value;

                $zone_id_array=explode(",",$zone_id);
                //print_r($zone_id_array);die;
                foreach($zone_id_array as $id)
                {
                    $zone_name=$this->Tester_model->get_zone_name($id);
                    $zone.=$zone_name.",";
                }
                $zone=trim($zone,",");
                $meta->meta_value=$zone;
            }
        }
        /*echo "<pre>";
        print_r($data['user_meta']);die;*/
        $blank_array=array();
        foreach($data['user_meta'] as $value)
        {
            $key=$value->meta_key;
            $vlaue=$value->meta_value;
            $blank_array[$key]=$vlaue;
        }
        $data['metadata']=$blank_array;
        $this->load->view('tester/user_details_modal', $data);
    }
	
	
	function reset_pass($id = null) {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

		$data['info'] = $this->Tester_model->get_tester_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/App/tester');
    	}

		$password = $this->random_string();
		$user['password'] = md5($password);

		//if the insert has returned true then we show the flash message
		if ($this->Tester_model->update($this->tablename, 'id', $id, $user)){

			$name = $data['info']->first_name . " " . $data['info']->last_name;
			$email = $data['info']->email;

			// Send Email to users
			$this->load->library('mail_template');
			$this->mail_template->new_password_email($name, $email, $password);

			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', '<strong>Well done!</strong> Password successfully updated and emailed to taster.');
		} else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
    	redirect('/App/tester');
	}
	
	public function search_submit() {

    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
    		$s_field = $this->clean_value($this->input->post('field'));
    		$operator = $this->clean_value($this->input->post('operator'));
			$q = $this->clean_value($this->input->post('q'));
			
			$url = "App/tester/index/";

            if ($s_field != '') {
                $url .= "field/". urlencode($s_field)."/";
            }

			if ($operator != '') {
				$url .= "ope/". urlencode($operator)."/";
			}

			if ($q != '') {
				$url .= "q/". $q ."/";
			}

			if ($sort_by != '') {
				$url .= "s_by/". urlencode($sort_by)."/";
			}

			if ($sort_dir != '') {
				$url .= "s_dir/". urlencode($sort_dir)."/";
			}

			redirect($url);
    	}
    }
    //Search using agency
    public function search_submit_agency() {

    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            //echo 1;die;
    		$agency = $this->input->post('agency');
    		
			
			$url = "App/tester/index/";

            if ($agency != '') {
                $url .= "agency/". $agency."/";
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
            
                //Search field for created by
                case 'created_by':
					$data['search_field'] = '<select name="q" class="form-control" id="inputSearch">
						<option value="" selected>- Select a user -</option>
						<option value="self" ';
						if ($q == 'self') {
							$data['search_field'] .= ' selected ';
						}
						$data['search_field'] .= ' >Self</option>';
						$data['search_field'] .= '<option value="agency" ';
						if ($q == 'agency') {
							$data['search_ope'] .= ' selected ';
						}
						$data['search_field'] .= '>Agency</option>
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
                    
                //Search field for zone
                
                case 'zone':
                    $zone=$this->Tester_model->get_zone_list();
					$data['search_field'] = '<select name="q" class="form-control" id="inputSearch">
						<option value="" selected>- Select a zone -</option>';
						foreach($zone as $value){
						$data['search_field'] .= '<option value="'.$value->id.'" ';
						if ($q == $value->id) {
							$data['search_ope'] .= ' selected ';
						}
						$data['search_field'] .= '>'.$value->name.'</option>
                        ';
                        }'
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
		return preg_replace('/[^A-Za-z0-9_@.\-~]/', '', $str);
    }

	/**
     *
     * @param unknown_type $uri
     * @param unknown_type $total_rows
     * @param unknown_type $segment
     */
    private function init_pagination($uri, $segment=4, $total_rows,$view=NULL) {

    	$this->config->load('pagination');
    	$this->load->library('pagination');

    	$config = $this->config->item('pagination');

       	$ci                          =& get_instance();
       	$config['uri_segment']       = $segment;
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
}
