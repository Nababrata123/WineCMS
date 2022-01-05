<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Application_Controller {

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
	private $tablename = 'cms';
	private $url = '/app/cms';
	private $reference_url = '';
	private $permissionValues = array(

		'index' => 'App.Cms.View',
		'add' => 'App.Cms.Add',
		'edit' => 'App.Cms.Edit',
        'delete' => 'App.Cms.Delete',
        
    );

    //private $allowed_roles = array('bar_admin');

	public function __construct() {

        
		
        parent::__construct();

		// Validate Login
		parent::checkLoggedin();

		$this->module_dir = APPPATH.'modules/'.$this->router->fetch_module();
        $this->load->config('config');

		$this->session->set_userdata('page_data', array('url' => $this->url, 'permissions' => $this->permissionValues));
        $this->load->model('Cms_model');
        $this->load->library('user_agent');
        
    }

	public function index() {

    // Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        // Set the filters
        $filter = array('is_deleted' => 0);
		$default_uri = array( 'name', 'page','status');
    	$uri = $this->uri->uri_to_assoc(4, $default_uri);
        $pegination_uri = array();
    	$status = $uri['status'];
		// Process the filters
        if (isset($uri['name']) && trim(urldecode($uri['name'])) <> "") {
            $filter['name'] = $uri['name'];
            $pegination_uri['name'] = $uri['name'];
        } else {
            $filter['name'] = "";
            $pegination_uri['name'] = " ";
        }

        

        if (isset($uri['page']) && $uri['page'] > 0) {
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
	    $total_rows = $this->Cms_model->get_cms_list($filter, null, null, true);
        $config = $this->init_pagination('App/cms/index/page/', $total_rows);
        


		$limit_end = ($page * $config['per_page']) - $config['per_page'];

	    if ($limit_end < 0){
	        $limit_end = 0;
	    }

	    $filter['limit'] = $config['per_page'];
	    $filter['offset'] = $limit_end;

	    // Get the zones List
	    $data['zones'] = $this->Cms_model->get_cms_list($filter, 'id', 'asc');

	    
    	$data['filters'] = $uri;
	    $data['page'] = 'cms';
    	$data['page_title'] = SITE_NAME.' :: Cms Management';

    	$data['main_content'] ='cms/list';
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
    	//$config = $this->config->item('module_config');

    	//$data['user_meta'] = $config['zones']['meta'];

    	//if save button was clicked, get the data sent via post
    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{

     		//form validation
     		//$this->form_validation->set_rules('role_id', 'Role', 'trim|required');

    		$this->form_validation->set_rules('title', 'Zone Name', 'trim|required');
			
			
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    		//if the form has passed through the validation

    		if($this->form_validation->run())
    		{
    			
				$name=htmlspecialchars($this->input->post('title'), ENT_QUOTES, 'utf-8');
                $slug=$this->Cms_model->create_slug($name);

    			$cms = array(
					'title' => htmlspecialchars($this->input->post('title'), ENT_QUOTES, 'utf-8'),
                    'slug' => $slug,
					'content' => htmlspecialchars($this->input->post('content'), ENT_QUOTES, 'utf-8'),
    				
    				'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
    				
    				'created_by' => $this->session->userdata('id'),
     				'created_on' => date('Y-m-d H:i:s'),

    			);

    			//echo "<pre>";
                //print_r($cms);die;
    			

                
        			//if the insert has returned true then we show the flash message
        			if ($zone_id = $this->Cms_model->insert($this->tablename, $cms)) {

        				

        				$this->session->set_flashdata('message_type', 'success');
        				$this->session->set_flashdata('message', '<strong>Well done!</strong> Cms have been added successfully.');
        			}
        			redirect('/App/cms');
               
    		} //validation run
    	}

		

    	$data['page'] = 'cms';
    	$data['page_title'] = SITE_NAME.' :: Cms Management &raquo; Add Cms';

    	$data['main_content'] = 'cms/add';
    	$this->load->view(TEMPLATE_PATH, $data);
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

		// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    	$config = $this->config->item('module_config');

    	//$data['user_meta'] = $config['zones']['meta'];

     	//if save button was clicked, get the data sent via post
     	if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
     		//form validation
     		
			$this->form_validation->set_rules('title', 'Cms', 'trim|required');

     		

     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
     		//if the form has passed through the validation

     		if ($this->form_validation->run())
     		{
                $name=htmlspecialchars($this->input->post('title'), ENT_QUOTES, 'utf-8');
                $slug=$this->Cms_model->create_slug($name);
     			$cms = array(
    				'title' => htmlspecialchars($this->input->post('title'), ENT_QUOTES, 'utf-8'),
                    'slug' => $slug,
                    'content' => htmlspecialchars($this->input->post('content'), ENT_QUOTES, 'utf-8'),
                    
                    'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
     				'updated_by' => $this->session->userdata('id')
     				
    			);

    			

     			//if the insert has returned true then we show the flash message
     			if ($this->Cms_model->update($this->tablename, 'id', $id, $cms)) {

     				
     				$this->session->set_flashdata('message_type', 'success');
     				if ($this->input->post('ref') == 'profile') {
     					$this->session->set_flashdata('message', '<strong>Well done!</strong> Profile successfully updated.');
     				} else {
     					$this->session->set_flashdata('message', '<strong>Well done!</strong> Cms successfully updated.');
     				}
     			} else{
     				$this->session->set_flashdata('message_type', 'danger');
     				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
     			}

     			// If from profile page - redirect there
     			if ($this->input->post('ref') == 'profile') {
     				redirect('/profile');
     			}

     			redirect('/App/cms/edit/'.$id);
     		} //validation run
     	}

     	$data['cms']  = $this->Cms_model->get_cms_details($id);
     	

     	if (!is_numeric($id) || $id == 0 || empty($data['cms'])) {

     		redirect('/App/cms');
     	}

		
     	$data['page'] = 'cms';
    	$data['page_title'] = SITE_NAME.' :: Cms Management &raquo; Edit Cms';

    	$data['main_content'] = 'cms/edit';
    	$this->load->view(TEMPLATE_PATH, $data);
    }
    public function update_status() {

    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
    		//form validation
    		$this->form_validation->set_rules('operation', 'Operation', 'required');
    		$this->form_validation->set_rules('item_id[]', 'Cms', 'trim|required');

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
    				/*if ($id == $this->session->userdata('id')) {
    					continue;
    				}*/

    				if ($operation == 'delete') {
                        //Check a zone is assotiated with store or not
                        
    					if ($this->Cms_model->delete($this->tablename, $id)) {
	    					$count++;
	    				}
    				} else {

	    				if ($this->Cms_model->update($this->tablename, 'id', $id, $data_to_store)) {
	    					$count++;
	    				}
    				}
    			}

    			$msg = ($operation=='delete')?'deleted.':'updated.';

    			$this->session->set_flashdata('message_type', 'success');
    			$this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' Cms(s) successfully '.$msg);

    		} else {
    			$this->session->set_flashdata('message_type', 'danger');
    			$this->session->set_flashdata('message', validation_errors());
    		}
    		redirect('/App/cms');
    	}
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

            redirect('/App/cms');
    	}

    	

    	if (!is_numeric($id) || $id == 0) {
    		redirect('/App/cms');
    	}
        
      	if ($this->Cms_model->delete($this->tablename, $id)) {
            $this->session->set_flashdata('message_type', 'success');
    		$this->session->set_flashdata('message', '<strong>Well done!</strong> Cms successfully deleted.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
    	redirect('/App/cms');
    }


	/**
     *
     */
    

	
	
	
	
	



    /**
     * Clean up by removing unwanted characters
     *
     * @param unknown_type $str
     */
    

	/**
     *
     * @param unknown_type $uri
     * @param unknown_type $total_rows
     * @param unknown_type $segment
     */
    private function init_pagination($uri, $total_rows) {

    	$this->config->load('pagination');
    	$this->load->library('pagination');

    	$config = $this->config->item('pagination');

       	$ci                          =& get_instance();
       	
       	$config['base_url']          = base_url().$uri;
       	$config['total_rows']        = $total_rows;

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
