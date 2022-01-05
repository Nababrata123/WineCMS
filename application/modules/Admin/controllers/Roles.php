<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends Application_Controller {
	
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
	private $tablename = 'roles';
	
	private $permissionValues = array(
		'index' => 'Admin.Roles.View',
		'add' => 'Admin.Roles.Add',
		'edit' => 'Admin.Roles.Edit',
        'delete' => 'Admin.Roles.Delete',
    );

    
	public function __construct() {

        parent::__construct();
        
		// Validate Login
		parent::checkLoggedin();
                
        $this->load->model('Admin_model');
    }
    
	public function index() {
	    
		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);
	        	
	    // Get the Roles List (for tab)
	    $data['roles'] = $this->Admin_model->get_roles_list();
    	//print "<pre>"; print_r($data);print "</pre>"; 
    	
	    $data['page'] = 'roles';
    	$data['page_title'] = SITE_NAME.' :: Manage User Roles';    	
    	
    	$data['main_content'] = 'roles/list';
    	$this->load->view(TEMPLATE_PATH, $data);
	}
    
    
    /**
     *
     */
    public function add() {
    	
		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);
		
    	//if save button was clicked, get the data sent via post
    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{     		
     		//form validation
    		$this->form_validation->set_rules('role_name', 'Role Name', 'trim|required');
    		
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    		//if the form has passed through the validation

    		if ($this->form_validation->run())
    		{
    			$role = array(
    				'role_name' => htmlspecialchars($this->input->post('role_name'), ENT_QUOTES, 'utf-8'),
    				'role_token' => $this->make_token($this->input->post('role_name')),
    				'description' => htmlspecialchars($this->input->post('description'), ENT_QUOTES, 'utf-8'),
    				'default' => htmlspecialchars($this->input->post('default'), ENT_QUOTES, 'utf-8'),
     				'can_delete' => htmlspecialchars($this->input->post('can_delete'), ENT_QUOTES, 'utf-8'),
    				'created_by' => $this->session->userdata('id'),
     				'created_on' => date('Y-m-d H:i:s')
    			);

    			//if the insert has returned true then we show the flash message
    			if ($this->Admin_model->insert($this->tablename, $role)) {
    				
    				$this->session->set_flashdata('message_type', 'success');
    				$this->session->set_flashdata('message', '<strong>Well done!</strong> Role have been added successfully. '.$msg);
    			} else {
    				$this->session->set_flashdata('message_type', 'danger');
    				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
    			}
    			redirect('/admin/roles');
    		} //validation run
    	}
    	    	
    	$data['page'] = 'roles';
    	$data['page_title'] = SITE_NAME.' :: Manage User Roles &raquo; Add Role';

    	$data['main_content'] = 'roles/add';
    	$this->load->view(TEMPLATE_PATH, $data);
    }
    
    
	/**
	 * 
	 * @param unknown_type $id
	 */
	public function edit($id = 0) {
		
		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);
		
     	//if save button was clicked, get the data sent via post
     	if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{     		
     		//form validation
     		$this->form_validation->set_rules('role_name', 'Role Name', 'trim|required');
    		
    		if ($this->input->post('password')) {
	    		$this->form_validation->set_rules('password', 'Password', 'matches[c_password]');
	    		$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required');
    		}

     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
     		//if the form has passed through the validation

     		if ($this->form_validation->run())
     		{
     			$role = array(
    				'role_name' => htmlspecialchars($this->input->post('role_name'), ENT_QUOTES, 'utf-8'),
    				'description' => htmlspecialchars($this->input->post('description'), ENT_QUOTES, 'utf-8'),
    				'default' => htmlspecialchars($this->input->post('default'), ENT_QUOTES, 'utf-8'),
     				'can_delete' => htmlspecialchars($this->input->post('can_delete'), ENT_QUOTES, 'utf-8'),
     				'updated_by' => $this->session->userdata('id'),
     				'updated_on' => date('Y-m-d H:i:s')
    			);

     			//if the insert has returned true then we show the flash message
     			if ($this->Admin_model->update($this->tablename, 'id', $id, $role)) {
     				$this->session->set_flashdata('message_type', 'success');
     				$this->session->set_flashdata('message', '<strong>Well done!</strong> Role successfully updated.');
     			} else{
     				$this->session->set_flashdata('message_type', 'danger');
     				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
     			}
     			redirect('/admin/roles');
     		} //validation run
     	}
		
     	$data['role']  = $this->Admin_model->get_role_details($id);

     	if (!is_numeric($id) || $id == 0 || empty($data['role'])) {
     		redirect('/admin/roles');
     	}
    	
     	$data['page'] = 'roles';
    	$data['page_title'] = SITE_NAME.' :: Manage User Roles &raquo; Edit Role';

    	$data['main_content'] = 'roles/edit';
    	$this->load->view(TEMPLATE_PATH, $data);
    }

    
	/**
     *
     * @param int $id
     */
    public function delete($id = null) {
        
    	// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);
		
    	$data['info'] = $this->Admin_model->get_role_details($id);
    	
    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/admin/roles');
    	}

    	// Only delete flagged roles
    	if ($data['info']->can_delete == 0) {
    		$this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> This role is not removable.');
            
            redirect('/admin/roles');
    	}
    	
      	if ($this->Admin_model->delete($this->tablename, $id)) {
            $this->session->set_flashdata('message_type', 'success');
    		$this->session->set_flashdata('message', '<strong>Well done!</strong> Role successfully deleted.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
    	redirect('/admin/roles');
    }
    
    
    function make_token($string) {

		$token = strtolower($string);
		$token = str_replace(" ", "_", $token);
		
		return preg_replace('/[^A-Za-z0-9_\-]/', '', $token);
    }
}
