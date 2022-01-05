<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends Application_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 * 	- or -
	 * 		http://example.com/index.php/welcome/index
	 * 	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

     /**
 	 *
 	 * @var unknown_type
 	 */
    private $tablename = 'category';
    private $url = '/App/category';
    private $permissionValues = array(
 		'index' => 'App.Category.View',
 		'add' => 'App.Category.Add',
 		'edit' => 'App.Category.Edit',
        'delete' => 'App.Category.Delete',
        'update_status' => 'App.Category.UpdateStatus',
    );

    public function __construct() {

        parent::__construct();

        // Validate Login
		parent::checkLoggedin();


        $this->session->set_userdata('page_data', array('url' => $this->url, 'permissions' => $this->permissionValues));
        $this->load->model('Category_model');
        $this->load->helper('template');
    }

    /**
     *
     */
    public function index() {


        //echo $this->router->method;die;
        // Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        $default_uri = array( 'name','status', 'page');
        $uri = $this->uri->uri_to_assoc(4, $default_uri);
    	//echo $this->router->class."/".$this->router->method;die;

        $status = $uri['status'];
        // Process the filters
        if (isset($uri['name']) && trim(urldecode($uri['name'])) <> "") {
            $search['name'] = $uri['name'];
            
        } else {
            $search['name'] = "";
            
        }

        
        $search['deleted'] = 0;
        $search['parent_id'] = '0';
      
        $search['tag'] = "&ndash;";
        $data['filters'] = $uri;
        $data['list'] = $this->Category_model->get_category_list($search);
        $data['page'] = 'category';
    	$data['page_title'] = SITE_NAME.' :: Category Management ';

    	$data['main_content'] = 'category/list';
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
            $this->form_validation->set_rules('parent_id', 'Parent Category', 'trim|required');
    		$this->form_validation->set_rules('name', 'Category', 'trim|required');

    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

    		//if the form has passed through the validation
    		if ($this->form_validation->run())
    		{
    			$data_to_store = array(
                    'parent_id' => htmlspecialchars($this->input->post('parent_id'), ENT_QUOTES, 'utf-8'),
    				'name' => htmlspecialchars($this->input->post('name'), ENT_QUOTES, 'utf-8'),
    				'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
                    'created_by' => $this->session->userdata('id'),
     				'created_on' => date('Y-m-d H:i:s')
    			);

				print_r($data_to_store);die;

    			//if the insert has returned true then we show the flash message
    			if ($this->Category_model->insert($this->tablename, $data_to_store)) {
    				$this->session->set_flashdata('message_type', 'success');
    				$this->session->set_flashdata('message', '<strong>Well done!</strong> Category have been added successfully.');
    			} else {
    				$this->session->set_flashdata('message_type', 'danger');
    				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Please try again.');
    			}
    			redirect($this->url);
    		} //validation run
    	}

        $data['page'] = 'category';
    	$data['page_title'] = SITE_NAME.' Category Management &raquo; Add Category';

        $data['main_content'] = 'category/add';
    	$this->load->view(TEMPLATE_PATH, $data);
    }


	/**
	 *
	 * @param unknown_type $id
	 */
	public function edit($id = 0) {

        // Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        $is_deleted = check_is_deleted('category',$id);
        if($is_deleted==false)
        {
          redirect($this->url);
        }

     	//if save button was clicked, get the data sent via post
     	if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
     		//form validation
    		$this->form_validation->set_rules('parent_id', 'Parent Category', 'trim|required');
            $this->form_validation->set_rules('name', 'Category', 'trim|required');

     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

     		//if the form has passed through the validation
     		if ($this->form_validation->run())
     		{
     			$data_to_store = array(
                    'parent_id' => htmlspecialchars($this->input->post('parent_id'), ENT_QUOTES, 'utf-8'),
    				'name' => htmlspecialchars($this->input->post('name'), ENT_QUOTES, 'utf-8'),
    				'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
                    'updated_by' => $this->session->userdata('id'),
     				'updated_on' => date('Y-m-d H:i:s')
     			);


     			//if the insert has returned true then we show the flash message
     			if ($this->Category_model->update($this->tablename, 'id', $id, $data_to_store)) {
     				$this->session->set_flashdata('message_type', 'success');
     				$this->session->set_flashdata('message', '<strong>Well done!</strong> Category successfully updated.');
     			} else{
     				$this->session->set_flashdata('message_type', 'danger');
     				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
     			}
     			redirect($this->url);
     		} //validation run
     	}

     	$data['category'] = $this->Category_model->get_category_details($id);

     	if (!is_numeric($id) || $id == 0 || empty($data['category'])) {
     		redirect($this->url);
     	}

        $search['parent_id'] = '0';
        $search['deleted'] = 0;
        $search['tag'] = "&ndash;";
        $data['list'] = $this->Category_model->get_category_list($search);
        //print "<pre>"; print_r($data['list']); print "</pre>"; die;
     	$data['page'] = 'category';
    	$data['page_title'] = SITE_NAME.' Category Management &raquo; Edit Category';

        $data['main_content'] = 'category/edit';
    	$this->load->view(TEMPLATE_PATH, $data);
    }


    /**
     *
     * @param int $id
     */
    public function delete($id = null) {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	$data['info'] = $this->Category_model->get_category_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect($this->url);
    	}

		$data_to_store = array(
			'is_deleted' => 1
		);

      	if ($this->Category_model->update($this->tablename, 'id', $id, $data_to_store)) {
            $this->session->set_flashdata('message_type', 'success');
    		$this->session->set_flashdata('message', '<strong>Well done!</strong> Category successfully deleted.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
    	redirect($this->url);
    }


	/**
     *
     */
    public function update_status() {

        // Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
    		//form validation
    		$this->form_validation->set_rules('operation', 'Operation', 'required');
    		$this->form_validation->set_rules('item_id[]', 'Size', 'trim|required');

    		$this->form_validation->set_error_delimiters('', '');

    		//if the form has passed through the validation
    		if ($this->form_validation->run())
    		{
    			//print "<pre>"; print_r($_POST);die;
    			$count = 0;
    			$items = $this->input->post('item_id');
    			$operation = $this->input->post('operation');

    			foreach ($items as $id=>$value) {

					if ($operation == 'delete') {

						$data_to_store = array(
				    		'is_deleted' => 1
				    	);
    				} else {
						$data_to_store = array(
				    		'status' => ($operation == "active")?'1':'0'
				    	);
    				}

					if ($this->Category_model->update($this->tablename, 'id', $id, $data_to_store)) {
						$count++;
					}
    			}

    			$msg = ($operation=='delete')?'deleted.':'updated.';

    			$this->session->set_flashdata('message_type', 'success');
				if($count>1){
					$this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' categories successfully '.$msg);
				}else{
					$this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' category successfully '.$msg);
				}    			

    		} else {
    			$this->session->set_flashdata('message_type', 'danger');
    			$this->session->set_flashdata('message', validation_errors());
    		}
    		redirect($this->url);
    	}
    }
    public function search_submit() {

        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $name = $this->clean_value($this->input->post('name'));

            $url = "App/category/index/";

            if ($name != '') {
                //echo $name;
                //echo urlencode($name);die;
                $url .= "name/". urlencode($name)."/";
            }

            if ($size_id != '') {
                $url .= "size_id/". urlencode($size_id)."/";
            }
            redirect($url);

            
        }
    }
    private function clean_value($str) {

        $str = str_replace(' ', '-', trim($str));
        return $str;
       // return preg_replace('/[^A-Za-z0-9_\-~]/', '', $str);
    }
}

/* End of file users.php */
/* Location: ./application/controllers/admin/users.php */
