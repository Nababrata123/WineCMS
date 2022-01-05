<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Questions_answers extends Application_Controller {

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
	private $tablename = 'question_answer';
    private $url = '/app/questions_answers';
	private $reference_url = '';
	private $permissionValues = array(

		'index' => 'App.QuestionsAnswers.View',
		'add' => 'App.QuestionsAnswers.Add',
		'edit' => 'App.QuestionsAnswers.Edit',
        'delete' => 'App.QuestionsAnswers.Delete',
        
    );

    //private $allowed_roles = array('bar_admin');

	public function __construct() {

        
		
        parent::__construct();

		// Validate Login
		parent::checkLoggedin();

		$this->module_dir = APPPATH.'modules/'.$this->router->fetch_module();
        $this->load->config('config');

		$this->session->set_userdata('page_data', array('url' => $this->url, 'permissions' => $this->permissionValues));
        $this->load->model('questions_answers_model');
        $this->load->library('user_agent');
        
    }

	public function index() {

    // Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        
        $filter = array();
		$default_uri = array('name','page');
    	$uri = $this->uri->uri_to_assoc(4, $default_uri);

        $pegination_uri = array();
    	
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

    	// Create the filters
	    

	    // Get the total rows without limit
	    /*$total_rows = $this->questions_answers_model->get_questions_answers_list($filter, null, null, true);
        //echo $total_rows;die;
        $config = $this->init_pagination('App/questions_answers/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/',7,$total_rows);
       
        
       // echo "<pre>";
        //print_r($config);die;

        

		$limit_end = ($page * $config['per_page']) - $config['per_page'];

	    if ($limit_end < 0){
	        $limit_end = 0;
	    }
       // echo $limit_end;die;

	    $filter['limit'] = $config['per_page'];
	    $filter['offset'] = $limit_end;*/

	    // Get the questions_answerss List
	    $data['questions_answers'] = $this->questions_answers_model->get_questions_answers_list($filter, 'id', 'asc');
        $data['filter'] = $filter;
        $data['page'] = 'questions_answers';
    	$data['page_title'] = SITE_NAME.' :: Questions Answers Management';

    	$data['main_content'] ='questions_answers/list';
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

    	//$data['user_meta'] = $config['questions_answerss']['meta'];

    	//if save button was clicked, get the data sent via post
    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{

     		//form validation
     		//$this->form_validation->set_rules('role_id', 'Role', 'trim|required');

    		$this->form_validation->set_rules('question', 'Question', 'trim|required');
            if($this->input->post('answer_type')=='multiple')
            {
                $this->form_validation->set_rules('answer_one', 'Answer one', 'trim|required');
                $this->form_validation->set_rules('answer_two', 'Answer two', 'trim|required');
                /*$this->form_validation->set_rules('answer_three', 'Answer three', 'trim|required');
                $this->form_validation->set_rules('answer_four', 'Answer four', 'trim|required');*/
            
            }
			
			
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    		//if the form has passed through the validation

    		if($this->form_validation->run())
    		{
    			
				

    			$questions_answers = array(
					'question' => htmlspecialchars($this->input->post('question'), ENT_QUOTES, 'utf-8'),
					'answer_one' => htmlspecialchars($this->input->post('answer_one'), ENT_QUOTES, 'utf-8'),
                    'answer_two' => htmlspecialchars($this->input->post('answer_two'), ENT_QUOTES, 'utf-8'),
                    
                    'answer_three' => htmlspecialchars($this->input->post('answer_three'), ENT_QUOTES, 'utf-8'),
                    
                    'answer_four' => htmlspecialchars($this->input->post('answer_four'), ENT_QUOTES, 'utf-8'),
                    
    				'answer_type' => htmlspecialchars($this->input->post('answer_type'), ENT_QUOTES, 'utf-8'),
    				
    				
    				'created_by' => $this->session->userdata('id'),
     				'created_on' => date('Y-m-d H:i:s')
    			);

    			
    			

    			//if the insert has returned true then we show the flash message
    			if ($questions_answers_id = $this->questions_answers_model->insert($this->tablename, $questions_answers)) {

    				

    				$this->session->set_flashdata('message_type', 'success');
    				$this->session->set_flashdata('message', '<strong>Well done!</strong> Question Answers have been added successfully.');
    			} else {

    				$this->session->set_flashdata('message_type', 'danger');
    				$this->session->set_flashdata('message', '<strong>Oh snap!</strong>  already exists.');
    			}
    			redirect('/App/questions_answers');
                
    		} //validation run
    	}

		

    	$data['page'] = 'questions_answers';
    	$data['page_title'] = SITE_NAME.' :: Question answers Management &raquo; Add Question answers';

    	$data['main_content'] = 'questions_answers/add';
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

    	//$data['user_meta'] = $config['questions_answerss']['meta'];

     	//if save button was clicked, get the data sent via post
     	if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
     		//form validation
     		
			$this->form_validation->set_rules('question', 'Question', 'trim|required');
            if($this->input->post('answer_type')=='multiple')
            {
                $this->form_validation->set_rules('answer_one', 'Answer one', 'trim|required');
                $this->form_validation->set_rules('answer_two', 'Answer two', 'trim|required');
                /*$this->form_validation->set_rules('answer_three', 'Answer three', 'trim|required');
                $this->form_validation->set_rules('answer_four', 'Answer four', 'trim|required');*/
            
            }
            
     		

     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
     		//if the form has passed through the validation

     		if ($this->form_validation->run())
     		{
     			$user = array(
    				'question' => htmlspecialchars($this->input->post('question'), ENT_QUOTES, 'utf-8'),
                    'answer_one' => htmlspecialchars($this->input->post('answer_one'), ENT_QUOTES, 'utf-8'),
                    'answer_two' => htmlspecialchars($this->input->post('answer_two'), ENT_QUOTES, 'utf-8'),
                    
                    'answer_three' => htmlspecialchars($this->input->post('answer_three'), ENT_QUOTES, 'utf-8'),
                    
                    'answer_four' => htmlspecialchars($this->input->post('answer_four'), ENT_QUOTES, 'utf-8'),
                    
                    'answer_type' => htmlspecialchars($this->input->post('answer_type'), ENT_QUOTES, 'utf-8'),
     				'updated_by' => $this->session->userdata('id'),
     				'updated_on' => date('Y-m-d H:i:s')
    			);

    			

     			//if the insert has returned true then we show the flash message
     			if ($this->questions_answers_model->update($this->tablename, 'id', $id, $user)) {

     				
     				$this->session->set_flashdata('message_type', 'success');
     				if ($this->input->post('ref') == 'profile') {
     					$this->session->set_flashdata('message', '<strong>Well done!</strong> Profile successfully updated.');
     				} else {
     					$this->session->set_flashdata('message', '<strong>Well done!</strong> Question Answers successfully updated.');
     				}
     			} else{
     				$this->session->set_flashdata('message_type', 'danger');
     				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
     			}

     			// If from profile page - redirect there
     			if ($this->input->post('ref') == 'profile') {
     				redirect('/profile');
     			}

     			redirect('/App/questions_answers');
     		} //validation run
     	}

     	$data['questions_answers']  = $this->questions_answers_model->get_questions_answers_details($id);
     	//echo "<pre>";
     	//print_r($data['questions_answers']);die;

     	if (!is_numeric($id) || $id == 0 || empty($data['questions_answers'])) {
     		redirect('/app/questions_answers');
     	}

		
     	$data['page'] = 'questions_answers';
    	$data['page_title'] = SITE_NAME.' :: Questions answers Management &raquo; Edit Questions answers';

    	$data['main_content'] = 'questions_answers/edit';
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

    				if ($operation == 'delete') {
                        //echo "ff";die;
    					if ($this->questions_answers_model->delete($this->tablename, $id)) {
	    					$count++;
	    				}
    				} else {

	    				if ($this->questions_answers_model->update($this->tablename, 'id', $id, $data_to_store)) {
	    					$count++;
	    				}
    				}
    			}

    			$msg = ($operation=='delete')?'deleted.':'updated.';

    			$this->session->set_flashdata('message_type', 'success');
    			$this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' Questions answers successfully '.$msg);

    		} else {
    			$this->session->set_flashdata('message_type', 'danger');
    			$this->session->set_flashdata('message', validation_errors());
    		}
    		redirect('/App/questions_answers');
    	}
    }
    public function profile() {

		$id = $this->session->userdata('id');

		// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    	$config = $this->config->item('module_config');

    	$data['user_meta'] = $config['questions_answerss']['meta'];

     	$data['user']  = $this->Admin_model->get_user_details($id);

     	if (!is_numeric($id) || $id == 0 || empty($data['user'])) {
     		redirect('/dashboard');
     	}

		// Roles List (for dropdown)
    	$data['roles'] = $this->Admin_model->get_roles_list();

     	$data['page'] = 'profile';
    	$data['page_title'] = SITE_NAME.' :: Update Profile';

    	$data['main_content'] = 'questions_answerss/profile';
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
    	/*if ($id == $this->session->userdata('id')) {
    		$this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');

            redirect('/App/questions_answers');
    	}*/
       // echo "fff";exit;

    	$data['info'] = $this->questions_answers_model->get_questions_answers_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/app/questions_answers');
    	}

      	if ($this->questions_answers_model->delete($this->tablename, $id)) {
            $this->session->set_flashdata('message_type', 'success');
    		$this->session->set_flashdata('message', '<strong>Well done!</strong> Questions answers successfully deleted.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
    	redirect('/App/questions_answers');
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
    private function init_pagination($uri,$segment=4,$total_rows) {
        
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

    public function search_submit() {

        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $name = $this->clean_value($this->input->post('name'));

            $url = "App/questions_answers/index/";

            if ($name != '') {
                $url .= "name/". urlencode($name)."/";
            }

            if ($size_id != '') {
                $url .= "size_id/". urlencode($size_id)."/";
            }
            redirect($url);

            
        }
    }
    private function clean_value($str) {

        $str = str_replace('/', '~', $str);
        return preg_replace('/[^A-Za-z0-9_\-~]/', '', $str);
    }
}
