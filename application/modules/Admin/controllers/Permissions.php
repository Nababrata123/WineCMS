<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends Application_Controller {

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
	private $tablename = 'permissions';

	private $permissionValues = array(
		'index' => 'Admin.Permissions.View',
		'add' => 'Admin.Permissions.Add',
		'edit' => 'Admin.Permissions.Edit',
        'delete' => 'Admin.Permissions.Delete',
        'matrix' => 'Admin.Permissions.Matrix',
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

		$default_uri = array('page');
		$uri = $this->uri->uri_to_assoc(4, $default_uri);

		if ($uri['page'] > 0) {
    		$page = $uri['page'];
    	} else {
    		$page = 0;
    	}

	    // Get the total rows without limit
	    $total_rows = $this->Admin_model->get_permissions_list(array(), null, null, true);

	    $config = $this->init_pagination('admin/permissions/index/'.$this->uri->assoc_to_uri(array()).'/page/', 5, $total_rows);

		$limit_end = ($page * $config['per_page']) - $config['per_page'];
	    if ($limit_end < 0){
	        $limit_end = 0;
	    }

	    $filter['limit'] = $config['per_page'];
	    $filter['offset'] = $limit_end;

	    // Get the Permissions List
	    $data['permissions'] = $this->Admin_model->get_permissions_list($filter, 'name', 'asc');
    	//print "<pre>"; print_r($data);print "</pre>";

	    $data['page'] = 'permissions';
    	$data['page_title'] = SITE_NAME.' :: Manage Permissions';

    	$data['main_content'] = 'permissions/list';
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
    		$this->form_validation->set_rules('name', 'Name', 'trim|required');

    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    		//if the form has passed through the validation

    		if ($this->form_validation->run())
    		{
    			$permission = array(
    				'name' => htmlspecialchars($this->input->post('name'), ENT_QUOTES, 'utf-8'),
    				'description' => htmlspecialchars($this->input->post('description'), ENT_QUOTES, 'utf-8'),
    				'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8')
    			);

    			//if the insert has returned true then we show the flash message
    			if ($this->Admin_model->insert($this->tablename, $permission)) {

    				$this->session->set_flashdata('message_type', 'success');
    				$this->session->set_flashdata('message', '<strong>Well done!</strong> Permission have been added successfully. ');
    			} else {
    				$this->session->set_flashdata('message_type', 'danger');
    				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
    			}
    			redirect('/admin/permissions');
    		} //validation run
    	}

    	$data['page'] = 'permissions';
    	$data['page_title'] = SITE_NAME.' :: Manage Permissions &raquo; Add Permission';

    	$data['main_content'] = 'permissions/add';
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
     		$this->form_validation->set_rules('name', 'Name', 'trim|required');

     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
     		//if the form has passed through the validation

     		if ($this->form_validation->run())
     		{
     			$permission = array(
    				'name' => htmlspecialchars($this->input->post('name'), ENT_QUOTES, 'utf-8'),
    				'description' => htmlspecialchars($this->input->post('description'), ENT_QUOTES, 'utf-8'),
    				'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8')
    			);

     			//if the insert has returned true then we show the flash message
     			if ($this->Admin_model->update($this->tablename, 'id', $id, $permission)) {
     				$this->session->set_flashdata('message_type', 'success');
     				$this->session->set_flashdata('message', '<strong>Well done!</strong> Permission successfully updated.');
     			} else{
     				$this->session->set_flashdata('message_type', 'danger');
     				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
     			}
     			redirect('/admin/permissions');
     		} //validation run
     	}

     	$data['permission']  = $this->Admin_model->get_permission_details($id);

     	if (!is_numeric($id) || $id == 0 || empty($data['permission'])) {
     		redirect('/admin/permissions');
     	}

     	$data['page'] = 'permissions';
    	$data['page_title'] = SITE_NAME.' :: Manage Permissions &raquo; Edit Permissions';

    	$data['main_content'] = 'permissions/edit';
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

    				if ($operation == 'delete') {
    					if ($this->Admin_model->delete($this->tablename, $id)) {
	    					$count++;
	    				}
    				} else {

	    				if ($this->Admin_model->update($this->tablename, 'id', $id, $data_to_store)) {
	    					$count++;
	    				}
    				}
    			}

    			$msg = ($operation=='delete')?'deleted.':'updated.';

    			$this->session->set_flashdata('message_type', 'success');
    			$this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' permissions(s) successfully '.$msg);

    		} else {
    			$this->session->set_flashdata('message_type', 'danger');
    			$this->session->set_flashdata('message', validation_errors());
    		}
    		redirect('/admin/permissions');
    	}
    }


	/**
     *
     * @param int $id
     */
    public function delete($id = null) {

    	// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	$data['info'] = $this->Admin_model->get_permission_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/admin/permissions');
    	}

      	if ($this->Admin_model->delete($this->tablename, $id)) {
            $this->session->set_flashdata('message_type', 'success');
    		$this->session->set_flashdata('message', '<strong>Well done!</strong> Permissions successfully deleted.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
    	redirect('/admin/permissions');
    }


    /**
     *
     */
    public function matrix() {

    	// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
    		//form validation
    		$this->form_validation->set_rules('item_id[][]', 'Permission', 'required');

    		$this->form_validation->set_error_delimiters('', '');

    		//if the form has passed through the validation
    		if ($this->form_validation->run())
    		{
    			//print "<pre>"; print_r($_POST);die;
    			$role_permission = $this->input->post('item_id');

    			if ($this->Admin_model->update_role_permission($role_permission)) {
	    			$this->session->set_flashdata('message_type', 'success');
    				$this->session->set_flashdata('message', '<strong>Well done!</strong> Permissions successfully updated.');
	    		} else {
	    			$this->session->set_flashdata('message_type', 'danger');
            		$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
	    		}

    		} else {
    			$this->session->set_flashdata('message_type', 'danger');
    			$this->session->set_flashdata('message', validation_errors());
    		}
    		redirect('/admin/permissions/matrix');
    	}


    	$data['role_permission'] = $this->Admin_model->get_role_permission();
    	$data['roles'] = $this->Admin_model->get_roles_list(array(), 'id', 'asc');
    	$data['permissions'] = $this->Admin_model->get_permissions_list(array('status' => 'active'), 'name', 'asc');

    	$data['page'] = 'permissions';
    	$data['page_title'] = SITE_NAME.' :: Manage Permissions &raquo; Permission Matrix';

    	//print "<pre>"; print_r($data);print "</pre>";

    	$data['main_content'] = 'permissions/matrix';
    	$this->load->view(TEMPLATE_PATH, $data);
    }
    /**
     *
     * @param unknown_type $uri
     * @param unknown_type $total_rows
     * @param unknown_type $segment
     */
    private function init_pagination($uri, $segment=4, $total_rows) {

    	$this->config->load('pagination');
    	$this->load->library('pagination');

    	$config = $this->config->item('pagination');

       	$ci                          =& get_instance();
       	$config['uri_segment']       = $segment;
       	$config['base_url']          = base_url().$uri;
       	$config['total_rows']        = $total_rows;

       	$ci->pagination->initialize($config);
       	return $config;
   }
}
