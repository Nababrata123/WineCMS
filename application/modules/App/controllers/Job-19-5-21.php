<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Job extends Application_Controller {
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
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
    private $tablename = 'job';
    private $url = '/App/job';
    private $reference_url = '';
    private $permissionValues = array(
        'index' => 'App.Job.View',
        'publish_job'=>'App.Job.Publishjob',
        'add'=>'App.Job.Add'
    );
    //private $wine_id_array=array();
    //private $allowed_roles = array('bar_admin');
    public function __construct() {
        parent::__construct();
        // Validate Login
        parent::checkLoggedin();
        $this->module_dir = APPPATH.'modules/'.$this->router->fetch_module();
        $this->load->config('config');
        $this->session->set_userdata('page_data', array('url' => $this->url, 'permissions' => $this->permissionValues));
        $this->load->model('Job_model');
        $this->load->library('user_agent');
        $this->load->model('store_model');
    }
    public function index() {
    // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        //$default_uri = array( 'page','status','tasting_date','taster','store','entry_date','sales_rep','search_text', 'search_by_rating');
        $default_uri = array( 'page','status','tasting_date','taster','store', 'search_by_status', 'sort_by_date', 'entry_date','sales_rep','search_text', 'search_by_rating');
        $uri = $this->uri->uri_to_assoc(4, $default_uri);
    //     echo "<pre>";
    //    print_r($uri);die;
        $status = $uri['status'];
        $pegination_uri = array();
        $pegination_uri['status'] = $uri['status'];
        $filter=array();
         if (isset($uri['tasting_date']) && trim(urldecode($uri['tasting_date'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['tasting_date'] = $uri['tasting_date'];
            $pegination_uri['tasting_date'] = $uri['tasting_date'];
            
        } 
        else
        {
            $filter['tasting_date'] = "";
            $pegination_uri['tasting_date'] = "~";
        }
        if (isset($uri['taster']) && trim(urldecode($uri['taster'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['taster'] = $uri['taster'];
            //echo $uri['taster'];die;
            $pegination_uri['taster'] = $uri['taster'];
            
        }
        else
        {
            $filter['taster'] = "";
            $pegination_uri['taster'] = "~";
        }
        if (isset($uri['store']) && trim(urldecode($uri['store'])) <> "") {
            
            $filter['store'] = $uri['store'];
            $pegination_uri['store'] = $uri['store'];
            
        }
        else
        {
            $filter['store'] = "";
            $pegination_uri['store'] = "~";
        }
        if (isset($uri['entry_date']) && trim(urldecode($uri['entry_date'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['entry_date'] = $uri['entry_date'];
            $pegination_uri['entry_date'] = $uri['entry_date'];
            
        }
        else
        {
            $filter['entry_date'] = "";
            $pegination_uri['entry_date'] = "~";
        }
       
        if (isset($uri['sales_rep']) && trim(urldecode($uri['sales_rep'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['sales_rep'] = $uri['sales_rep'];
            $pegination_uri['sales_rep'] = $uri['sales_rep'];
            
        }
        else
        {
            $filter['sales_rep'] = "";
            $pegination_uri['sales_rep'] = "~";
        }

        if (isset($uri['search_text']) && trim(urldecode($uri['search_text'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['search_text'] = $uri['search_text'];
            $pegination_uri['search_text'] = $uri['search_text'];
            
        }
        else
        {
            $filter['search_text'] = "";
            $pegination_uri['search_text'] = "~";
        }

        if (isset($uri['search_by_status']) && trim(urldecode($uri['search_by_status'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['search_by_status'] = $uri['search_by_status'];
            $pegination_uri['search_by_status'] = $uri['search_by_status'];
            
        }
        else
        {
            $filter['search_by_status'] = "";
            $pegination_uri['search_by_status'] = "~";
        }

        if (isset($uri['sort_by_date']) && trim(urldecode($uri['sort_by_date'])) <> "") {
            
            $filter['sort_by_date'] = $uri['sort_by_date'];
            $pegination_uri['sort_by_date'] = $uri['sort_by_date'];
            
        }
        else
        {
            $filter['sort_by_date'] = "";
            $pegination_uri['sort_by_date'] = "~";
        }
        if (isset($uri['search_by_rating']) && trim(urldecode($uri['search_by_rating'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['search_by_rating'] = $uri['search_by_rating'];
            $pegination_uri['search_by_rating'] = $uri['search_by_rating'];
            
        }
        else
        {
            $filter['search_by_rating'] = "";
            $pegination_uri['search_by_rating'] = "~";
        }
        if ($uri['page'] > 0) {
            $page = $uri['page'];
        } else {
            $page = 0;
        }
       // echo $page;die;
        // Create the filters
        //$filter = array();
        if ($status <> '') {
            $filter['status'] = $status;
            $pegination_uri['status'] = $status;
        } else {
            $status = 'jobs';
            $filter['status'] = $status;
            $pegination_uri['status'] = $status;
        }
        // if ($status <> '') {
        //     $filter['status'] = $status;
        //     $pegination_uri['status'] = $status;
        // } else {
        //     $status = 0;
        // }
    //    echo "<pre>";
    //     print_r($filter);die;
        // Get the total rows without limit
        $total_rows = $this->Job_model->get_job_list($filter, null, null, true);
       // echo $total_rows;die;
       $config = $this->init_pagination('App/Job/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/' ,25, $total_rows); 
       //$config = $this->init_pagination('App/Job/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/',21, $total_rows);
       // echo "<pre>";
       // print_r($config);die;
        /*$seg= $this->uri->segment(6);
        if($seg=='page')
        {
            $page_pos=7;
            $config = $this->init_pagination('App/Job/index/'.$this->uri->assoc_to_uri($pegination_uri).'//page/',$page_pos, $total_rows);
        }
        else if($seg=='')
        {
            $config = $this->init_pagination('App/Job/index/'.$this->uri->assoc_to_uri($pegination_uri).'//page/',5, $total_rows);
        }
        else
        {
            $page_pos=9;
            $config = $this->init_pagination('App/Job/index/'.$this->uri->assoc_to_uri($pegination_uri).'//page/',$page_pos, $total_rows);
        }*/

        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        }
        $filter['limit'] = $config['per_page'];
        $filter['offset'] = $limit_end;
    //    echo "<pre>";
    //    print_r($filter);die;
        // Get the jobs List
        //$data['jobs'] = $this->Job_model->get_job_list($filter, 'id', 'asc');
        $data['jobs'] = $this->Job_model->get_job_list($filter, '', '');
        /* Pre assigned jobs */
        $data['count_pre_assigned']=$this->Job_model->count_job('job',1);
        /* assigned jobs */
        $data['count_assigned']=$this->Job_model->count_job('job',2);
        /* accepted jobs */
        $data['count_accepted']=$this->Job_model->count_job('job',3);
        /* problems jobs */
        $data['count_problems']=$this->Job_model->count_job('job',4);

        $data['jobs_count']= $data['count_pre_assigned']+ $data['count_assigned']+$data['count_accepted'];
        //Get tester according to zone for 
        //$data['filters'] = $uri;
		
        $data['taster']=$this->Job_model->get_taster();
        $data['store']=$this->Job_model->get_store();
        $data['sales_rep']=$this->Job_model->get_sales_rep();
        $data['filters'] = $uri;
        $data['page'] = 'Job';
        $data['page_title'] = SITE_NAME.' :: Job Management';
        $data['main_content'] ='job/list';
        $this->load->view(TEMPLATE_PATH, $data);
    }
    
        public function index_old() {
    // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        $default_uri = array( 'page','status','tasting_date','taster' );
        $uri = $this->uri->uri_to_assoc(4, $default_uri);
        $status = $uri['status'];
        $pegination_uri = array();
        $pegination_uri['status'] = $uri['status'];
        $filter=array();
        if (isset($uri['tasting_date']) && trim(urldecode($uri['tasting_date'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['tasting_date'] = $uri['tasting_date'];
            $pegination_uri['tasting_date'] = $uri['tasting_date'];
            
        } 
        if (isset($uri['taster']) && trim(urldecode($uri['taster'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['taster'] = $uri['taster'];
            $pegination_uri['taster'] = $uri['taster'];
            
        }
        /*else {
            //$pegination_uri['status']="~";
            $filter['tasting_date'] = "";
            $pegination_uri['tasting_date'] = "";
            
        }*/
        //$status = $uri['status'];
        if ($uri['page'] > 0) {
            $page = $uri['page'];
        } else {
            $page = 0;
        }
        // Create the filters
        //$filter = array();
        if ($status <> '') {
            $filter['status'] = $status;
        } else {
            $status = 0;
        }
       // echo "<pre>";
        //print_r($filter);die;
        // Get the total rows without limit
        $total_rows = $this->Job_model->get_job_list($filter, null, null, true);
        //echo $total_rows;die;
        $config = $this->init_pagination('App/Job/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/',7, $total_rows);
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        }
        $filter['limit'] = $config['per_page'];
        $filter['offset'] = $limit_end;
        // Get the jobs List
        $data['jobs'] = $this->Job_model->get_job_list($filter, 'id', 'asc');
        /* Pre assigned jobs */
        $data['count_pre_assigned']=$this->Job_model->count_job('job',1);
        /* assigned jobs */
        $data['count_assigned']=$this->Job_model->count_job('job',2);
        /* accepted jobs */
        $data['count_accepted']=$this->Job_model->count_job('job',3);
        /* problems jobs */
        $data['count_problems']=$this->Job_model->count_job('job',4);
        //Get tester according to zone for 
        //$data['filters'] = $uri;

        $data['taster']=$this->Job_model->get_taster();
        $data['filters'] = $uri;
        $data['page'] = 'Job';
        $data['page_title'] = SITE_NAME.' :: Job Management';
        $data['main_content'] ='job/list';
        $this->load->view(TEMPLATE_PATH, $data);
    }
    //Create job from admin pannel
    public function add($id = 0) {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    	$config = $this->config->item('module_config');

    	$data['user_meta'] = $config['users']['meta'];

    	//if save button was clicked, get the data sent via post
    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
 
            if($this->input->post('tasting_date')!='')
            {
                $td=date("Y-m-d",strtotime($this->input->post('tasting_date')));
            }
            else
            {
                $td='';
            }
            //Set input data to session
			
			$tid = $this->input->post('taster_id');
            $newdata = array(
					'user_id'  => $this->input->post('user_id'),
					'tasting_date'  => $td,
					'start_time_hour'=>$this->input->post('start_time_hour'),					
					'start_time_minute'=>$this->input->post('start_time_minute'),
					'time_one'=>$this->input->post('time_one'),
					'end_time_hour'=>$this->input->post('end_time_hour'),					
					'end_time_minute'=>$this->input->post('end_time_minute'),
					'time_two'=>$this->input->post('time_two'),
					'store_id'  => $this->input->post('store_id'),
					'admin_note'  => $this->input->post('admin_note'),
					'taster_note'  => $this->input->post('taster_note'),
					'wine_id'  => $this->input->post('wine_id'),
					'taster_id'  => $tid[0],
					
               );

            $this->session->set_userdata('inputdata',$newdata);

    		$this->form_validation->set_rules('user_id', 'Sales representative', 'trim|required');
			$this->form_validation->set_rules('tasting_date', 'Tasting date', 'trim|required');
    		//$this->form_validation->set_rules('start_time', 'Start time', 'trim|required');
	    	//$this->form_validation->set_rules('end_time', 'End time', 'trim|required');
            $this->form_validation->set_rules('start_time_hour', 'Start time', 'trim|required');
	    	$this->form_validation->set_rules('end_time_hour', 'End time', 'trim|required');
            $this->form_validation->set_rules('start_time_minute', 'Start time', 'trim|required');
	    	$this->form_validation->set_rules('end_time_minute', 'End time', 'trim|required');
	    	$this->form_validation->set_rules('store_id', 'Store', 'trim|required');
            //$this->form_validation->set_rules('admin_note', 'Admin note', 'trim|required');
	    	//$this->form_validation->set_rules('taster_id[]', 'Taster', 'trim|required');
	    	$this->form_validation->set_rules('wine_id[]', 'At least one wine', 'trim|required');

			
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    		//if the form has passed through the validation

    		if($this->form_validation->run())
    		{
    			$user_id = $this->input->post('user_id');
                $tasting_date = date("Y-m-d",strtotime($this->input->post('tasting_date')));
                if($this->input->post('start_time_hour')!='')
                {
                    $first_time=$this->input->post('time_one');
                    $start_hour=$this->input->post('start_time_hour');
                    $start_minute=$this->input->post('start_time_minute');
                    $actual_start_time=$start_hour.":".$start_minute;
                    $start_time = date("H:i", strtotime($actual_start_time.$first_time));
                }
                else
                {
                    $start_time='';
                }
                if($this->input->post('end_time_hour')!='')
                {
                    $second_time=$this->input->post('time_two');
                    $end_hour=$this->input->post('end_time_hour');
                    $end_minute=$this->input->post('end_time_minute');
                    $actual_end_time=$end_hour.":".$end_minute;
                    $end_time = date("H:i", strtotime($actual_end_time.$second_time));
                }
                else
                {
                    $end_time='';
                }
             
                $store_id = $this->input->post('store_id');
                //Check job for same store and same time
                $check_store=$this->Job_model->check_store('job',$store_id,$start_time,$end_time,$tasting_date,0);

                $admin_note = $this->input->post('admin_note');
                 //Get tester or agency
                $tester_array=$this->input->post('taster_id[]');
                $taster_id='';
                foreach($tester_array as $val)
                {
                    $taster_id.=$val.',';
                }
                $taster_id=rtrim($taster_id,',');
                //Check the tester is available or not for the job
                if($taster_id!='')
                {
                    $count_job=$this->Job_model->check_tester_availablity('job',$taster_id,$tasting_date,$start_time,$end_time);
                }
                else
                {
                    $count_job=0;
                }
              
                if($taster_id!='')
                {
                    $check_tester=1;
                }
                else
                {
                    $check_tester=1;
                }

                $taster_note = $this->input->post('taster_note');
                $wine_details_array=$this->input->post('wine_id');
 
                /*
                $wine_flavours=$this->get_wine_flavour($wine_details_array);
                if($wine_flavours)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> You can not select different types of wine together.');
                    redirect('/App/job/add/');
                }*/

				if($end_time < $start_time)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> End time should be greater than start time.');
                    redirect('/App/job/add/');
                }
                $datetime1 = strtotime($start_time);
                $datetime2 = strtotime($end_time);
                $interval  = abs($datetime2 - $datetime1);
                $minutes   = round($interval / 60);
				
                if($minutes<30)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The start time and the end time should not be same. There should be a gap of minimum 30 min between start and end time.');
                    redirect('/App/job/add/');
                }
  
                //Make wine id array to string
                $wine_id='';
                if(!empty($wine_details_array))
                {
                    for($i=0;$i<count($wine_details_array);$i++)
                    {
                        $wine_id.=$wine_details_array[$i].",";
                    }
                    $wine_id=rtrim($wine_id,",");
                } 
                
                //Based on taster set job status
                if($taster_id!='')
                {

                    $status='assigned';
                    $job_status=2;
                  /*
                    $user_info=$this->Job_model->get_user_type('users',$taster_id);
                    $user_type=$user_info[0]->user_type;
                    if($user_type!=''){
                        $status='pending';
                        $job_status=1;
                    }else{
                        $status='assigned';
                        $job_status=2;
                    }*/
                }
                else
                {
                    $status='pending';
                    $job_status=1;
                }
                $job['user_id']=$user_id;
                $job['tasting_date']=$tasting_date;
                $job['start_time']=$start_time;
                $job['end_time']=$end_time;
                $job['store_id']=$store_id;
                $job['admin_note']=$admin_note;
                $job['taster_id']=$taster_id;
                $job['taster_note']=$taster_note;
                $job['wine_id']=$wine_id;
                $job['status']=$status;
                $job['job_status']=$job_status;
                //Get user role
                $user_role=$this->Job_model->get_user_role('users',$user_id);
                if($user_role==3)
                {
                    $job['confirm_status']=1;
                }

                if (!empty($job))
                {

                        if($check_tester==1)
                        {
                            if($count_job == 0)
                            {
                                //echo "u";die;
                                $insert_id=$this->Job_model->create_job('job',$job);
                                if($insert_id)
                                {
                                    $this->load->library('push_notifications');
                                    $this->load->model('Notifications_model');
                                    $this->load->library('mail_template');
                                    $taster_id_array=explode(",",$taster_id);
                                    foreach($taster_id_array as $id)
                                    {
                                        $notifications = $this->Notifications_model->send_notifications_for_republish_job($id,$insert_id);
                                    }
                                    foreach($tester_array as $taster_id)
                                    {
                                        /*
                                        //get tester id and name
                                        $result['tester_info']=$this->Job_model->get_tester_details($tester_id);
                                        //echo "<pre>";
                                       // print_r($result['tester_info']);die;
                                        $name=$result['tester_info']->first_name;
                                        $email=$result['tester_info']->email;
                                        //echo $email;die;
                                        $this->mail_template->assigned_job_email($name,$email); */

                                        $samplingDate = date("F d, Y", strtotime($tasting_date));
                                        $startTime = date("h:i a",strtotime($start_time));
                                        $finish_time = date("h:i a",strtotime($end_time));
    
                                        $wineNames=$this->Job_model->get_assign_mail_wine_names($wine_details_array);
                        
                                        // $store = $this->Job_model->get_store_name_mail($insert_id);
                                        // $store_name = $store[0]['store_name'];
                                        // $store_address = $store[0]['store_address'];
    
                                        $store = $this->Job_model->get_store_name($store_id);
                                        $store_name = $store->name;
                                        $store_address = $store->adress;
                                        
                                        $salesrep = $this->Job_model->get_mail_selsrep_name($insert_id);
                                        $salesrep_name = $salesrep->sales_rep_name;
                                        
                                        //get tester id and name
                                        $result['tester_info']=$this->Job_model->get_tester_details($taster_id);
                                        $name=$result['tester_info']->first_name;
                                        $email=$result['tester_info']->email;
                                        //echo $email;die;
                                        
                                        $this->mail_template->assigned_job_email($name,$email,$samplingDate,$startTime,$finish_time,$wineNames,$store_name,$store_address,$salesrep_name);

                                    }
                                    $this->session->unset_userdata('wine_ids');
                                    $this->session->unset_userdata('inputdata');
                                    
                                    $this->session->set_flashdata('message_type', 'success');
                                    $this->session->set_flashdata('message', 'Job has been created successfully.');
                                    redirect('/App/job');
                                }
                            }
                            else
                            {
                               //echo "v";die;
                                $this->session->set_flashdata('message_type', 'danger');
                                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The taster has assigned with other job.');
                                redirect('/App/job/add/');
                            }
                        }
                        else
                        {
                            
                            $this->session->set_flashdata('message_type', 'danger');
                            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The taster you want to assign does not belong to the zone.');
                            redirect('/App/job/add/');
                            
                                
                        }
                  
                }
                else
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Job not created.');
                    redirect('/App/job/');
                }
				
    		} 
    	}

    	$data['page'] = 'add_job';
    	$data['page_title'] = SITE_NAME.' :: Job Management &raquo; Add Job';
        //Get sales representative
        $data['sales_rep']=$this->Job_model->get_sales_rep();
        //get store
        $data['store']=$this->Job_model->get_store();
        //get wine
        
        $data['wine']=$this->Job_model->get_all_wine();
        //get tester or agency
        $data['tester']=$this->Job_model->get_taster();

        $data['id'] = $id;
        $data['page'] = 'add_job';
        $data['page_title'] = SITE_NAME.' :: Job Management &raquo; Add Job';
        //Get sales representative
        if($id!=0){
            $data['sales_rep']=$this->Job_model->get_sales_rep_by_store_id($id);
        }else{
            $data['sales_rep']=$this->Job_model->get_sales_rep();
        }
        //get store
        if($id!=0){
            $data['store']=$this->Job_model->get_store_by_id($id);
        }else{
        $data['store']=$this->Job_model->get_store(); 
        }
        //get wine
        if($id!=0){
            $data['wine']=$this->Job_model->get_wine($id);
  
        }else{
            $data['wine']=$this->Job_model->get_all_wine();
        }
        //get tester or agency
        if($id!=0){
            $data['tester']=$this->Job_model->get_tester_or_agency_ajax($id);
        }else{
        $data['tester']=$this->Job_model->get_taster();
        }
		$data['main_content'] = 'job/addjob';
    	$this->load->view(TEMPLATE_PATH, $data);
    }

    
    //End create job section
    public function publish_job($job_id=0,$assign_status=0) 
    {
        
        // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        //if save button was clicked, get the data sent via post
		
		
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            if($this->input->post('tasting_date')!='')
			{
				$td=date("Y-m-d",strtotime($this->input->post('tasting_date')));
			}
			else
			{
				$td='';
			}
			$tid = $this->input->post('taster_id');
			$newdata = array(
				'user_id'  => $this->input->post('user_id'),
				'tasting_date'  => $td,
				'start_time_hour'=>$this->input->post('start_time_hour'),					
				'start_time_minute'=>$this->input->post('start_time_minute'),
				'time_one'=>$this->input->post('time_one'),
				'end_time_hour'=>$this->input->post('end_time_hour'),					
				'end_time_minute'=>$this->input->post('end_time_minute'),
				'time_two'=>$this->input->post('time_two'),
				'store_id'  => $this->input->post('store_id'),
				'admin_note'  => $this->input->post('admin_note'),
				'taster_note'  => $this->input->post('taster_note'),
				'wine_id'  => $this->input->post('wine_id'),
				'taster_id'  => $tid[0],
				
		   );

			$this->session->set_userdata('inputdata',$newdata);
            //form validation
            $this->form_validation->set_rules('tasting_date', 'Job Date', 'trim|required');
            /*$this->form_validation->set_rules('start_time', 'Start Time', 'trim|required');
            $this->form_validation->set_rules('end_time', 'End Time', 'trim|required');*/
            $this->form_validation->set_rules('start_time_hour', 'Start time', 'trim|required');
	    	$this->form_validation->set_rules('end_time_hour', 'End time', 'trim|required');
            $this->form_validation->set_rules('start_time_minute', 'Start time', 'trim|required');
	    	$this->form_validation->set_rules('end_time_minute', 'End time', 'trim|required');
            $this->form_validation->set_rules('store_id', 'Store', 'trim|required');
            //$this->form_validation->set_rules('admin_note', 'Admin Note', 'trim|required');
            //$this->form_validation->set_rules('taster_note', 'Taster Note', 'trim|required');
            $this->form_validation->set_rules('taster_id[]', 'Tester or Agency required', 'trim|required');
            $this->form_validation->set_rules('wine_id[]', 'Wine', 'trim|required');
           // $this->form_validation->set_rules('question_id[]', 'Question', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $tasting_date = date("Y-m-d",strtotime($this->input->post('tasting_date')));
                $store_id = $this->input->post('store_id');

                if($this->input->post('start_time_hour')!='')
                {
                    $first_time=$this->input->post('time_one');
                    $start_hour=$this->input->post('start_time_hour');
                    $start_minute=$this->input->post('start_time_minute');
                    $actual_start_time=$start_hour.":".$start_minute;
                    $start_time = date("H:i", strtotime($actual_start_time.$first_time));
                }
                else
                {
                    $start_time='';
                }
                if($this->input->post('end_time_hour')!='')
                {
                    $second_time=$this->input->post('time_two');
                    $end_hour=$this->input->post('end_time_hour');
                    $end_minute=$this->input->post('end_time_minute');
                    $actual_end_time=$end_hour.":".$end_minute;
                    $end_time = date("H:i", strtotime($actual_end_time.$second_time));
                }
                else
                {
                    $end_time='';
                }
                //Get tester or agency
                $tester_array=$this->input->post('taster_id[]');
                $taster_id='';
                foreach($tester_array as $val)
                {
                    $taster_id.=$val.',';
                }
                $taster_id=rtrim($taster_id,',');
                //Get wine
               $wine_array=$this->input->post('wine_id[]');
                $wine_flavours=$this->get_wine_flavour($wine_array);
                if($wine_flavours)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> You can not select different types of wine together.');
                    redirect('/App/job/publish_job/'.$job_id);
                }
                $datetime1 = strtotime($start_time);
                $datetime2 = strtotime($end_time);
                $interval  = abs($datetime2 - $datetime1);
                $minutes   = round($interval / 60);
                if($minutes<30)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The start time and the end time should not be same. There should be a gap of minimum 30 min between start and end time.');
                    redirect('/App/job/publish_job/'.$job_id);
                }
            
                $wine_id='';
                foreach($wine_array as $val)
                {
                    $wine_id.=$val.',';
                }
                $wine_id=rtrim($wine_id,',');
                //Get question
                $question_array=$this->input->post('question_id[]');
                $question_id='';
                if(!empty($question_array)){
                    
                    foreach($question_array as $val)
                    {
                        $question_id.=$val.',';
                    }
                    $question_id=rtrim($question_id,',');
                }
                else
                {
                    $question_id='';
                }


                $job = array(
                    'tasting_date' => htmlspecialchars($tasting_date, ENT_QUOTES, 'utf-8'),
                    'start_time' => htmlspecialchars($start_time, ENT_QUOTES, 'utf-8'),
                    'end_time' => htmlspecialchars($end_time, ENT_QUOTES, 'utf-8'),
                    'store_id' => htmlspecialchars($this->input->post('store_id'), ENT_QUOTES, 'utf-8'),
                    'admin_note' => htmlspecialchars($this->input->post('admin_note'), ENT_QUOTES, 'utf-8'),
                    'taster_note' => htmlspecialchars($this->input->post('taster_note'), ENT_QUOTES, 'utf-8'),
                    'taster_id' => $taster_id,
                    'wine_id' => $wine_id,
                    'question_id'=>$question_id,
                    'status'=>'assigned',
                    'job_status'=>2,
                );

				//echo $taster_id;die();
                /*
                $user_info=$this->Job_model->get_user_type('users',$taster_id);
                $user_type=$user_info[0]->user_type;

                if($user_type == 'agency'){
                    $job = array(
                        'tasting_date' => htmlspecialchars($tasting_date, ENT_QUOTES, 'utf-8'),
                        'start_time' => htmlspecialchars($start_time, ENT_QUOTES, 'utf-8'),
                        'end_time' => htmlspecialchars($end_time, ENT_QUOTES, 'utf-8'),
                        'store_id' => htmlspecialchars($this->input->post('store_id'), ENT_QUOTES, 'utf-8'),
                        'admin_note' => htmlspecialchars($this->input->post('admin_note'), ENT_QUOTES, 'utf-8'),
                        'taster_note' => htmlspecialchars($this->input->post('taster_note'), ENT_QUOTES, 'utf-8'),
                        'taster_id' => $taster_id,
                        'agency_taster_id' => 0,
                        'wine_id' => $wine_id,
                        'question_id'=>$question_id,
                        'status'=>'pending',
                        'job_status'=>1,
                    );
                }else{
                    $job = array(
                        'tasting_date' => htmlspecialchars($tasting_date, ENT_QUOTES, 'utf-8'),
                        'start_time' => htmlspecialchars($start_time, ENT_QUOTES, 'utf-8'),
                        'end_time' => htmlspecialchars($end_time, ENT_QUOTES, 'utf-8'),
                        'store_id' => htmlspecialchars($this->input->post('store_id'), ENT_QUOTES, 'utf-8'),
                        'admin_note' => htmlspecialchars($this->input->post('admin_note'), ENT_QUOTES, 'utf-8'),
                        'taster_note' => htmlspecialchars($this->input->post('taster_note'), ENT_QUOTES, 'utf-8'),
                        'taster_id' => $taster_id,
                        'wine_id' => $wine_id,
                        'question_id'=>$question_id,
                        'status'=>'assigned',
                        'job_status'=>2,
                    );
                }*/

                if($end_time < $start_time)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> End time should be greater that start time.');
                    redirect('/App/job/publish_job/'.$job_id);
                }
                //Check job status
                $status=$this->Job_model->check_job_status($job_id);
                $todays_date=date("Y-m-d");
                if($tasting_date < $todays_date)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> You can not publish the job because the tasting date is already over.');
                    redirect('/App/job/publish_job/'.$job_id);
                }

                //if the insert has returned true then we show the flash message
                //Check job for same store and same time
				$count_job=$this->Job_model->check_tester_availablity_with_jobid($job_id,'job',$taster_id,$tasting_date,$start_time,$end_time);
                if($count_job == 0)
                {
                    $this->load->library('push_notifications');
                    $this->load->model('Notifications_model');

                    if($status=="pending")
                    {
                        
                        //Send push notification to all agency/taster of the same zone
                    //Get zone id and name
                        $zone_details=$this->Job_model->get_zone_details($this->input->post('store_id'));
                        if($taster_id=='')
                        {
                            $notifications = $this->Notifications_model->send_notifications_for_publish_job($zone_details,$job_id);
                        }
                        else
                        {
                            $taster_id_array=explode(",",$taster_id);
							
                            foreach($taster_id_array as $id)
                            {
                                $notifications = $this->Notifications_model->send_notifications_for_republish_job($id,$job_id);
                            }
                            
                        }
                    }
					
                    if($status=="assigned" || $status=="rejected")
                    {
                        //Delete accept and reject data
                        $this->Job_model->delete_accept_reject_data('job_accept_reject',$job_id);
                        $taster_id_array=explode(",",$taster_id);
                        foreach($taster_id_array as $id)
                        {
                            $notifications = $this->Notifications_model->send_notifications_for_republish_job($id,$job_id);
                        }
                    }

                    if ($this->Job_model->update_job($this->tablename, 'id', $job_id, $job)) {

                    //Send Email to testers/agency who are assigned
                    $this->load->library('mail_template');
                    foreach($tester_array as $taster_id)
                    {
                        /*
                        //get tester id and name
                        $result['tester_info']=$this->Job_model->get_tester_details($tester_id);
                        $name=$result['tester_info']->first_name;
                        $email=$result['tester_info']->email;
                        $this->mail_template->assigned_job_email($name,$email);*/

                        $samplingDate = date("F d, Y", strtotime($tasting_date));
                        $startTime = date("h:i a",strtotime($start_time));
                        $finish_time = date("h:i a",strtotime($end_time));


                        $wineNames=$this->Job_model->get_assign_mail_wine_names($wine_array);
                        $store = $this->Job_model->get_store_name($store_id);
                        $store_name = $store->name;
                        $store_address = $store->adress;
                        $salesrep = $this->Job_model->get_mail_selsrep_name($job_id);
                        $salesrep_name = $salesrep->sales_rep_name;
                      
                        //get tester id and name
                        $result['tester_info']=$this->Job_model->get_tester_details($taster_id);
                        $name=$result['tester_info']->first_name;
                        $email=$result['tester_info']->email;

                        //echo $email;die;
                        $this->mail_template->assigned_job_email($name,$email,$samplingDate,$startTime,$finish_time,$wineNames,$store_name,$store_address,$salesrep_name);

                    }
                    $this->session->unset_userdata('wine_ids');
					$this->session->unset_userdata('inputdata');
                    $this->session->set_flashdata('message_type', 'success');
                    $this->session->set_flashdata('message', '<strong>Well done!</strong> Job successfully published.');
                    } else{
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
                    }
                    redirect('/App/job');
                }
                else
                {
                    $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The taster has been assigned with other job.');
                    redirect('/App/job/publish_job/'.$job_id);
                }
            } //validation run
        }

        $data['assign_status']=$assign_status;
        //get details of job
        $data['job'] = $this->Job_model->job_details($job_id);
        $sales_rep_id=$data['job']->user_id;
        $data['store']=$this->Job_model->get_store_for_sales_rep($sales_rep_id);
        //get wine
        $store_id=$data['job']->store_id;
        $data['wine']=$this->Job_model->get_wine($store_id);
        //get tester or agency
        $data['tester']=$this->Job_model->get_tester_or_agency($job_id);
        //get question answers
        $data['question_answers']=$this->Job_model->get_question_answers();
        //Get sales representaive name
        $data['sales_rep']=$this->Job_model->get_user_name($data['job']->user_id);
        if (!is_numeric($job_id) || $job_id == 0 || empty($data['job'])) {
            redirect('/App/job');
        }        
        $data['page'] = 'Publish job';
        $data['page_title'] = SITE_NAME.' :: Job Management &raquo; Publish job';
        $data['main_content'] = 'job/publish_job';
        $this->load->view(TEMPLATE_PATH, $data);
    }


    public function edit_job($job_id=0)
    {
       
        $this->load->library('push_notifications');
        $this->load->model('Notifications_model');
        $data['job_info']  = $this->Job_model->job_info($job_id);
        $pre_tasting_date=$data['job_info']->tasting_date;
        $pre_taster=$data['job_info']->taster_id;
        $pre_store=$data['job_info']->store_id;
        $pre_start_time=$data['job_info']->start_time;
        $pre_end_time=$data['job_info']->end_time;
        $pre_store_data = $this->Job_model->get_store_name($pre_store);

        $selectWine=$this->input->post('wine');

        $wineList = implode(',', $selectWine);

        $agency_taster = $data['job_info']->agency_taster_id;
// echo "<pre>";
//    print_r($pre_tasting_date);
//    print_r($pre_start_time);
//    print_r($pre_end_time);
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
           
            //form validation
            $this->form_validation->set_rules('tasting_date', 'Job Date', 'trim|required');
            $this->form_validation->set_rules('start_time_hour', 'Start time', 'trim|required');
            $this->form_validation->set_rules('end_time_hour', 'End time', 'trim|required');
            $this->form_validation->set_rules('start_time_minute', 'Start time', 'trim|required');
            $this->form_validation->set_rules('end_time_minute', 'End time', 'trim|required');
            $this->form_validation->set_rules('store_id', 'Store', 'trim|required');
            // $this->form_validation->set_rules('taster_id', 'Tester or Agency required', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $tasting_date = date("Y-m-d",strtotime($this->input->post('tasting_date')));

                if($this->input->post('start_time_hour')!='')
                {
                    $first_time=$this->input->post('time_one');
                    $start_hour=$this->input->post('start_time_hour');
                    $start_minute=$this->input->post('start_time_minute');
                    $actual_start_time=$start_hour.":".$start_minute;
                    $start_time = date("H:i", strtotime($actual_start_time.$first_time));
                }
                else
                {
                    $start_time='';
                }
                if($this->input->post('end_time_hour')!='')
                {
                    $second_time=$this->input->post('time_two');
                    $end_hour=$this->input->post('end_time_hour');
                    $end_minute=$this->input->post('end_time_minute');
                    $actual_end_time=$end_hour.":".$end_minute;
                    $end_time = date("H:i", strtotime($actual_end_time.$second_time));
                }
                else
                {
                    $end_time='';
                }

                // print_r($start_time);
                // print_r($end_time);die;

                //Get tester or agency
                $taster_id=$this->input->post('taster_id');
                $store_id=$this->input->post('store_id');


                if ($tester_id != ''){
                    $status='assigned';
                    $job_status='2'; 
                }else{
                    $status='pending';
                    $job_status='1';
                }

               
               
            
                if($agency_taster != ''){
                    $job = array(
                        'tasting_date' => htmlspecialchars($tasting_date, ENT_QUOTES, 'utf-8'),
                        'start_time' => htmlspecialchars($start_time, ENT_QUOTES, 'utf-8'),
                        'end_time' => htmlspecialchars($end_time, ENT_QUOTES, 'utf-8'),
                        'store_id' => htmlspecialchars($this->input->post('store_id'), ENT_QUOTES, 'utf-8'),
                        'admin_note' => htmlspecialchars($this->input->post('admin_note'), ENT_QUOTES, 'utf-8'),
                        'taster_note' => htmlspecialchars($this->input->post('taster_note'), ENT_QUOTES, 'utf-8'),
                        'taster_id' => $taster_id,
                        'agency_taster_id'=>$agency_taster,
                        'status'=>$status,
                        'job_status'=>$job_status,
                        'wine_id'=>$wineList
                    );
                }else{
                    $job = array(
                        'tasting_date' => htmlspecialchars($tasting_date, ENT_QUOTES, 'utf-8'),
                        'start_time' => htmlspecialchars($start_time, ENT_QUOTES, 'utf-8'),
                        'end_time' => htmlspecialchars($end_time, ENT_QUOTES, 'utf-8'),
                        'store_id' => htmlspecialchars($this->input->post('store_id'), ENT_QUOTES, 'utf-8'),
                        'admin_note' => htmlspecialchars($this->input->post('admin_note'), ENT_QUOTES, 'utf-8'),
                        'taster_note' => htmlspecialchars($this->input->post('taster_note'), ENT_QUOTES, 'utf-8'),
                        'taster_id' => $taster_id,
                        'agency_taster_id'=>0,
                        'status'=>$status,
                        'job_status'=>$job_status,
                        'wine_id'=>$wineList
                    );
                }
           
                if($end_time < $start_time && $data['job_info']->state!=1)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> End time should be greater that start time.');
                    if($data['job_info']->job_status== 4){
                        redirect('App/Job/index/status/problems');
                    }else{
                        redirect('App/Job');
                    }
                }
                
                $datetime1 = strtotime($start_time);
                $datetime2 = strtotime($end_time);
                $interval  = abs($datetime2 - $datetime1);
                $minutes   = round($interval / 60);
                if($minutes<30 && $data['job_info']->state!=1)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The start time and the end time should not be same. There should be a gap of minimum 30 min between start and end time.');
                    if($data['job_info']->job_status== 4){
                        redirect('App/Job/index/status/problems');
                    }else{
                        redirect('App/Job');
                    }
                }
               
                //if the insert has returned true then we show the flash message
                //Check job for same store and same time
                $count_job = 0;
                if ($taster_id != ''){
                    $count_job=$this->Job_model->check_tester_availablity_with_jobid($job_id,'job',$taster_id,$tasting_date,$start_time,$end_time);
                }
                // print_r($count_job);die;

                if($count_job == 0)
                {
                    //Delete accept and reject data
                    if($data['job_info']->job_state < 1){
                        $this->Job_model->delete_accept_reject_data('job_accept_reject',$job_id);
                    }
                   
                    if($this->Job_model->update_job($this->tablename, 'id', $job_id, $job)) {
                        //Delete accept and reject data
                    //Send Email to testers/agency who are assigned
                    $this->load->library('mail_template');
                    
                    if ($taster_id != '') {

                        //get tester id and name
                        $result['tester_info']=$this->Job_model->get_tester_details($taster_id);
                      
                        $name=$result['tester_info']->first_name;
                        $email=$result['tester_info']->email;
                       
                        $jobInfo = $this->Job_model->get_completed_job_info($job_id);
                        $samplingDate = '';
                        if ($tasting_date !=''){
                            $samplingDate = date("F d, Y", strtotime($tasting_date));
                        }else{
                            $samplingDate = date("F d, Y", strtotime($jobInfo->tasting_date));
                        }

                        $startTime = '';
                        if ($start_time != ''){
                            $startTime = date("h:i a",strtotime($start_time));
                        }else{
                            $startTime= date("h:i a",strtotime($jobInfo->start_time));
                        }

                        $finish_time = '';
                        if($end_time != ''){
                            $finish_time = date("h:i a",strtotime($end_time));
                        }else{
                            $finish_time= date("h:i a",strtotime($jobInfo->end_time));
                        }

                        $wine_id_array = explode(',', $jobInfo->wine_id);
                        $wineNames=$this->Job_model->get_assign_mail_wine_names($wine_id_array);
                        $store = $this->Job_model->get_store_name($store_id);
                        $store_name = $store->name;
                        $store_address = $store->adress;
                       
                        $salesrep = $this->Job_model->get_mail_selsrep_name($job_id);
                        $salesrep_name = $salesrep->sales_rep_name;
                       
                        if(($tasting_date!=$pre_tasting_date) || ($pre_store!=$store_id))
                        {
                            // $this->mail_template->assigned_job_email($name,$email);

                            $this->mail_template->assigned_job_email($name,$email,$samplingDate,$startTime,$finish_time,$wineNames,$store_name,$store_address,$salesrep_name);
                            
                            $result['tester_info']=$this->Job_model->get_tester_details($taster_id);
                         
                            $name=$result['tester_info']->first_name;
                            $email=$result['tester_info']->email;
                            $this->mail_template->job_change_email($name,$email,$tasting_date);
                            //send notification for change information
                            $notifications = $this->Notifications_model->send_notifications_for_change_info($pre_taster,$job_id);
                        }
                    }
                        
                        if($taster_id!=$pre_taster)
                        {
                            // $this->mail_template->assigned_job_email($name,$email);

                            $this->mail_template->assigned_job_email($name,$email,$samplingDate,$startTime,$finish_time,$wineNames,$store_name,$store_address,$salesrep_name);

                            $result['pre_tester_info']=$this->Job_model->get_tester_details($pre_taster);
                            $name=$result['pre_tester_info']->first_name;
                            $email=$result['pre_tester_info']->email;
                            //echo $email;die;
                            $this->mail_template->cancelled_job_email($name,$email);
                            $notifications = $this->Notifications_model->send_notifications_for_cancelled_job($pre_taster,$job_id);

                        }
                        $currentjobData=$this->Job_model->job_info($job_id);

                        // print_r($currentjobData->agency_taster_id);

                        // Previous checking for update job mail..
                        // if($taster_id==$pre_taster && $pre_start_time==$currentjobData->start_time && $pre_end_time!=$currentjobData->end_time)
                        if($taster_id==$pre_taster){
                            $result['pre_tester_info']=$this->Job_model->get_tester_details($pre_taster);
                        
                            $name=$result['pre_tester_info']->first_name;
                            $email=$result['pre_tester_info']->email;
                           
                            $currentStoreData = $this->Job_model->get_store_name($currentjobData->store_id);
                            $this->mail_template->update_job_email($name,$email, $pre_tasting_date, $pre_start_time, $pre_end_time, $pre_store_data, $currentjobData, $currentStoreData, $wineNames);
                            $notifications = $this->Notifications_model->send_notifications_for_change_info($pre_taster,$job_id);
                            
                            if($currentjobData->agency_taster_id!=0){
                                $result['pre_tester_info']=$this->Job_model->get_tester_details($currentjobData->agency_taster_id);
                                $name=$result['pre_tester_info']->first_name;
                                $email=$result['pre_tester_info']->email;
                                $currentStoreData = $this->Job_model->get_store_name($currentjobData->store_id);
                                $this->mail_template->update_job_email($name,$email, $pre_tasting_date, $pre_start_time, $pre_end_time, $pre_store_data, $currentjobData, $currentStoreData, $wineNames);
                                $notifications = $this->Notifications_model->send_notifications_for_change_info($currentjobData->agency_taster_id,$job_id);

                            }
                        }
                    
                        $this->session->set_flashdata('message_type', 'success');
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> Job successfully updated.');
                    } else{
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
                    }
                   
                    if($data['job_info']->job_status== 4){
                        redirect('App/Job/index/status/problems');
                    }else{
                        redirect('App/Job');
                    }
                }
                else
                {
                    $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The taster has assigned with other job.');
                   
                    if($data['job_info']->job_status== 4){
                        redirect('App/Job/index/status/problems');
                    }else{
                        redirect('App/Job');
                    }
                }
            } //validation run
        }
    }

   
    public function completed_edit_job($job_id=0)
    {

        $exp_reason=$this->input->post('exp_reason');
        $taster_id=$this->input->post('taster_id');
        $tasting_date=$this->input->post('tasting_date');

        $start_time_hour=$this->input->post('start_time_hour');
        $start_time_minute=$this->input->post('start_time_minute');
        $time_one=$this->input->post('time_one');

        $end_time_hour=$this->input->post('end_time_hour');
        $end_time_minute=$this->input->post('end_time_minute');
        $time_two=$this->input->post('time_two');

        $Comments=$this->input->post('Comments');
        $general_notes=$this->input->post('general_note');
        $exp_amount="$".$this->input->post('exp_amount');

        $wine=$this->input->post('wine');
        $bottles_sampled=$this->input->post('bottles_sampled');
        $open_bottles_sampled=$this->input->post('open_bottles_sampled');
        $bottles_sold=$this->input->post('bottles_sold');

        /*
        // Merge Completed Wine Info and New Wine Info...
        $completedWineInfo=$this->Job_model->get_wines_sampled_sold_details($job_id);

        foreach($completedWineInfo as $wineinfo){

            if (in_array($wineinfo['id'], $wine))
                {
                    // print_r("Found");die;
                }
                else
                {
                    array_push($wine,$wineinfo['id']);
                    array_push($bottles_sampled,$wineinfo['bottles_sampled']);
                    array_push($open_bottles_sampled,$wineinfo['open_bottles_sampled']);
                    array_push($bottles_sold,$wineinfo['bottles_sold']);
                }
        }*/

        $actual_start_hour_min=$start_time_hour.":".$start_time_minute;
        $actual_start_time = date("H:i", strtotime($actual_start_hour_min.$time_one));

        $actual_end_hour_min=$end_time_hour.":".$end_time_minute;
        $actual_end_time = date("H:i", strtotime($actual_end_hour_min.$time_two));
        $dateDiff = intval((strtotime($actual_end_time)-strtotime($actual_start_time))/60);

        $hours = intval($dateDiff/60);
        $minutes = $dateDiff%60;
        $working_hour = date("H:i", strtotime($hours.':'.$minutes));
        //$start = strtotime($actual_start_time);
        //$end = strtotime($actual_end_time);
        //$working_hour= date('h:i', $end - $start);
        //echo $actual_start_time.'-:strt:- '.$hours.':'.$minutes.' end:'.$actual_end_time ;die;
        $mtasting_date = date("Y-m-d",strtotime($this->input->post('tasting_date')));
            $job = array(
        'tasting_date' => $mtasting_date,
        'job_start_time' => htmlspecialchars($actual_start_time, ENT_QUOTES, 'utf-8'),
        'finish_time' => htmlspecialchars($actual_end_time, ENT_QUOTES, 'utf-8'),
        'working_hour' => $working_hour,
        );
       // print_r( $job);die;
        $this->Job_model->update_job($this->tablename, 'id', $job_id, $job);

        $general_note_array=array(
            'general_note'=>$general_notes
        );
        $this->db->where('job_id',$job_id);
        $this->db->update('general_notes',$general_note_array);

        // $manager_verification_array=array(
        //     'comment'=>$Comments
        // );
        // $this->db->where('job_id',$job_id);
        // $this->db->update('manager_verification_details',$manager_verification_array);

        // $testerFeedback=array(
        //     'general_note'=>$Comments
        // );
        // $this->db->where('job_id',$job_id);
        // $this->db->update('general_notes',$testerFeedback);
        // $expense_array=array(
        //     'exp_amount'=>$exp_amount,
        //     'exp_reason'=>$exp_reason
        // );
        // $this->db->where('job_id',$job_id);
        // $this->db->update('expense_details',$expense_array);
        $date=date("Y-m-d");
        $expense_array=array(
            'taster_id'=>$taster_id,
            'job_id'=>$job_id,
            'exp_amount'=>$exp_amount,
            'exp_reason'=>$exp_reason,
            'date'=>$date
        );

        $data['expense_details']=$this->Job_model->get_expense_details($job_id);

        if(isset($data['expense_details'][0]['job_id']) && isset($data['expense_details'][0]['exp_amount']) && isset($data['expense_details'][0]['exp_reason'])){
            $this->Job_model->update_data('expense_details','id', $data['expense_details'][0]['exp_id'], $expense_array);
        }else{
            $this->db->insert('expense_details', $expense_array);
        }

        $this->db->select('wine_id, job_id');
        $this->db->from('completed_job_wine_details');
        $this->db->where('job_id',$job_id);
        $result=$this->db->get();
        $final_result=$result->result_array();
        //print_r($final_result);die;
        foreach($final_result as $fr){
            $this -> db -> where('job_id', $fr['job_id']);
            $this -> db -> where('wine_id', $fr['wine_id']);
            $this -> db -> delete('completed_job_wine_details');
        }
        $i=0;
        foreach($wine as $w){
            $data=array('wine_id'=>$w, 'bottles_sampled'=> $bottles_sampled[$i], 'open_bottles_sampled'=>$open_bottles_sampled[$i], 'bottles_sold'=>$bottles_sold[$i], 'job_id'=> $job_id, 'taster_id'=> $taster_id );
            $this->db->insert('completed_job_wine_details',$data);
                ++$i;
        }
        redirect('App/Job');
      
/*
        $exp_reason=$this->input->post('exp_reason');
        $taster_id=$this->input->post('taster_id');
        $tasting_date=$this->input->post('tasting_date');

        $start_time_hour=$this->input->post('start_time_hour');
        $start_time_minute=$this->input->post('start_time_minute');
        $time_one=$this->input->post('time_one');

        $end_time_hour=$this->input->post('end_time_hour');
        $end_time_minute=$this->input->post('end_time_minute');
        $time_two=$this->input->post('time_two');

        $Comments=$this->input->post('Comments');
        $general_notes=$this->input->post('general_note');
        $exp_amount="$".$this->input->post('exp_amount');

        $wine=$this->input->post('wine');
        $bottles_sampled=$this->input->post('bottles_sampled');
        $open_bottles_sampled=$this->input->post('open_bottles_sampled');
        $bottles_sold=$this->input->post('bottles_sold');

            $actual_start_hour_min=$start_time_hour.":".$start_time_minute;
            $actual_start_time = date("H:i", strtotime($actual_start_hour_min.$time_one));

            $actual_end_hour_min=$end_time_hour.":".$end_time_minute;
            $actual_end_time = date("H:i", strtotime($actual_end_hour_min.$time_two));
            $dateDiff = intval((strtotime($actual_end_time)-strtotime($actual_start_time))/60);

            $hours = intval($dateDiff/60);
            $minutes = $dateDiff%60;
            $working_hour = date("H:i", strtotime($hours.':'.$minutes));
            //$start = strtotime($actual_start_time);
            //$end = strtotime($actual_end_time);
            //$working_hour= date('h:i', $end - $start);
            //echo $actual_start_time.'-:strt:- '.$hours.':'.$minutes.' end:'.$actual_end_time ;die;
            $mtasting_date = date("Y-m-d",strtotime($this->input->post('tasting_date')));
             $job = array(
            'tasting_date' => $mtasting_date,
            'job_start_time' => htmlspecialchars($actual_start_time, ENT_QUOTES, 'utf-8'),
            'finish_time' => htmlspecialchars($actual_end_time, ENT_QUOTES, 'utf-8'),
            'working_hour' => $working_hour,
            );
       // print_r( $job);die;
        $this->Job_model->update_job($this->tablename, 'id', $job_id, $job);

        $general_note_array=array(
            'general_note'=>$general_notes
        );
        $this->db->where('job_id',$job_id);
        $this->db->update('general_notes',$general_note_array);

        $date=date("Y-m-d");
        $expense_array=array(
            'taster_id'=>$taster_id,
            'job_id'=>$job_id,
            'exp_amount'=>$exp_amount,
            'exp_reason'=>$exp_reason,
            'date'=>$date
        );

        $data['expense_details']=$this->Job_model->get_expense_details($job_id);

        if(isset($data['expense_details'][0]['job_id']) && isset($data['expense_details'][0]['exp_amount']) && isset($data['expense_details'][0]['exp_reason'])){
            $this->Job_model->update_data('expense_details','id', $data['expense_details'][0]['exp_id'], $expense_array);
        }else{
            $this->db->insert('expense_details', $expense_array);
        }

        $this->db->select('wine_id, job_id');
        $this->db->from('completed_job_wine_details');
        $this->db->where('job_id',$job_id);
        $result=$this->db->get();
        $final_result=$result->result_array();
        //print_r($final_result);die;
        foreach($final_result as $fr){
            $this -> db -> where('job_id', $fr['job_id']);
            $this -> db -> where('wine_id', $fr['wine_id']);
            $this -> db -> delete('completed_job_wine_details');
        }
        $i=0;
        foreach($wine as $w){
            $data=array('wine_id'=>$w, 'bottles_sampled'=> $bottles_sampled[$i], 'open_bottles_sampled'=>$open_bottles_sampled[$i], 'bottles_sold'=>$bottles_sold[$i], 'job_id'=> $job_id, 'taster_id'=> $taster_id );
            $this->db->insert('completed_job_wine_details',$data);
                ++$i;
        }

      
     redirect('App/Job');
        */
    }


    public function get_wine()
    {
        $this->session->unset_userdata('wine_ids');
        $store_id=$this->input->post('store_id');
        //$wine_id=$this->input->post('wine_id');
        //$data['wine_id']=$wine_id;
        $data['wine']=$this->Job_model->get_wine($store_id);
        $this->load->view('job/display_wine_without_search',$data);
    }

    public function get_wine_by_storeId()
    {
        
        $store_id=$this->input->post('store_id');
        $data['get_wine_list']=json_decode(json_encode($this->Job_model->get_wine($store_id)), true);
        // $data['wine']=$this->Job_model->get_wine($store_id);
        
        $wine='<option value="">Select Wine</option>';
        $wineList = $data['get_wine_list'];
        foreach($wineList as $w){
            $wine.='<option value="'.$w['id'].'">'.addslashes($w['name']).'</option>';
        }

       echo $wine;
    }

    public function get_wine_flavour($wine_array)
    {
        $wine_id=$wine_array;
        $royal=array();
        $mix=array();
        foreach($wine_id as $key=>$val)
        {
            $this->db->select('flavour');
            $this->db->from('wine');
            $this->db->where('id',$val);
            $value=$this->db->get()->row();
            $result=$value->flavour;
            if($result=='royal')
            {
                array_push($royal,$result);
            }
            if($result=='mix')
            {
                array_push($mix,$result);
            }

        }
        
        if(empty($royal) || empty($mix))
        {
                
            return false;
        }
        else
        {
            return true;
        }
    }
    /**
     *Open Job approve modal
     */
    public function open_approve_modal()
    {
        $job_id=$this->input->post('job_id');
        $tester_id=$this->input->post('tester_id');
        $data['job_id']=$job_id;
        $data['taster_id']=$tester_id;
        //get tester for the job
        $data['tester']=$this->Job_model->get_tester_or_agency($job_id);
        //Get tester who can accept 
        $data['accepted_user']=$this->Job_model->accepted_user($job_id);
        //Get tester who reject
        $data['rejected_user']=$this->Job_model->rejected_user($job_id);
        //Get job status
        $job_details=$this->Job_model->job_details($job_id);
        $data['job_status']=$job_details->status;
        $this->load->view('job/job_modal',$data);
    }
    public function set_question_modal()
    {
        $job_id=$this->input->post('job_id');
        $data['job_id']=$job_id;
        $data['question_answers']=$this->Job_model->get_question_answers();
        $this->load->view('job/set_questions_modal',$data);
    }
    public function set_question()
    {
        $job_id=$this->input->post('job_id');
        $questions_id_array=$this->input->post('question_id');
        $question_id='';
        foreach($questions_id_array as $value)
        {
            $question_id.=$value.",";
        }
        $question_id=rtrim($question_id,",");
        $data=array('question_id'=>$question_id);
        $update=$this->Job_model->update_data('job','id',$job_id,$data);
        if($update)
        {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', '<strong>Well done!</strong> Questions has been set successfully.');
            redirect('App/Job/index/status/accepted');
        }
        else
        {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Question has not been set');
            redirect('App/Job/index/status/accepted');
                
        }
    }
    public function view_setup_modal()
    {
        $job_id=$this->input->post('job_id');
        //Get setup id
        $setup_id=$this->Job_model->get_tasting_setup_id('tasting_setup',$job_id);
        //Get setup images
        $id_container=array();
        if(!empty($setup_id))
        {
            foreach($setup_id as $val)
            {
                array_push($id_container,$val['id']);
            }
        }
        else
        {
            $id_container='';
        }
        // $data['images']=$this->Job_model->get_setup_image('tasting_setup_images',$id_container);
        $images=$this->Job_model->get_setup_image('tasting_setup_images',$id_container);

        $tasting_image=array();
        $store_image=array();
        foreach($images as $imageType){
            if($imageType['tasting_type'] == 'tasting'){
                array_push($tasting_image,$imageType);
            }else{
                array_push($store_image,$imageType);
            }
        }
        $data['store_images'] = $store_image;
        $data['tasting_images'] = $tasting_image;

        $this->load->view('job/show_tasting_setup',$data);
        
    }
    public function open_problem_one_modal()
    {
        $job_id=$this->input->post('job_id');
        $taster_id=$this->input->post('taster_id');
        $data['job_details']=$this->Job_model->job_details($job_id);
        $data['expense_details']=$this->Job_model->get_expense_details($job_id);
        $data['manager_verification_details']=$this->Job_model->get_manager_verification_details($job_id);
        $data['general_note']=$this->Job_model->get_general_note($job_id);
        // get already sampled wine and get all the wine list 
        $data['get_wine_info']=$this->Job_model->get_wines_sampled_sold_details($job_id);
        // $data['get_wine_list']=json_decode(json_encode($this->Job_model->get_all_wine()), true);
        $data['get_wine_list']=json_decode(json_encode($this->Job_model->get_wine($data['job_details']->store_id)), true);
        $store_name = $this->store_model->get_store_name($data['job_details']->store_id);
        $data['store_name'] = $store_name->name;
       
        $data['job_id']=$job_id;
        $data['taster_id']=$taster_id;
        $this->load->view('job/problem_one_modal',$data);
    }
    public function open_problem_two_modal()
    {
        $job_id=$this->input->post('job_id');
        $data['job_id']=$job_id;
        $this->load->view('job/problem_two_modal',$data);
    }
    


    public function open_edit_job_modal()
    {
        $job_id=$this->input->post('job_id');
        //Get job details
        
        $data['job']  = $this->Job_model->job_details($job_id);
        $storeId = $data['job']->store_id;
        $sales_rep_id=$data['job']->user_id;
        $wineId = $data['job']->wine_id;

       $wineIdArray = explode(",",$wineId);

        //get tester or agency
        $data['tester']=$this->Job_model->get_tester_or_agency($job_id);
       
        if($data['job']->job_state==2){
            $data['store']=json_decode(json_encode($this->Job_model->get_store()), true);
            $data['expense_details']=$this->Job_model->get_expense_details($job_id);
            $data['general_note']=$this->Job_model->get_general_note($job_id);
            $data['sales_rep']=$this->Job_model->get_sales_rep();
            $data['get_wine_info']=$this->Job_model->get_wines_sampled_sold_details($job_id);
            //  $data['get_wine_list']=json_decode(json_encode($this->Job_model->get_all_wine()), true);
            $data['get_wine_list']=json_decode(json_encode($this->Job_model->get_wine($storeId)), true);
            $data['expence_amount']=$this->Job_model->get_expense_amount($job_id);
            $data['manager_verification_details']=$this->Job_model->get_manager_verification_details($job_id);
           
            $wineList = $data['get_wine_list'];
            $wineList_array = array();
            foreach($wineList as $wine){
                array_push($wineList_array,$wine['id']);
            }

            $tastingWine = $data['get_wine_info'];

            $storeTypeChange = 0;
            foreach($tastingWine as $wine){ 
              
                if (in_array($wine['id'], $wineList_array)){
                    ++$storeTypeChange;
                }
            }

            if(count($tastingWine) != $storeTypeChange) {
                $data['get_wine_info'] = array();
            }

            $this->load->view('job/completed_edit_job_modal',$data);

        }else if($data['job']->job_state==1){
    
            $data['store']=$this->Job_model->get_store_for_wine_tpye($sales_rep_id, $data['job']->wine_id);
            $data['get_wine_list']=json_decode(json_encode($this->Job_model->get_wine($storeId)), true);
            $wineList=json_decode(json_encode($this->Job_model->get_wine_names($wineIdArray)), true);

            // Tasting wine info...
            $wineInfo = array();
            foreach($wineList as $wine) {
                
                $wine['bottles_sampled'] = 0;
                $wine['open_bottles_sampled'] = 0;
                $wine['bottles_sold'] = 0;
                array_push($wineInfo,$wine);
             
            }
           
           $data['get_wine_info'] =  $wineInfo;

            $this->load->view('job/job_start_edit_job_modal',$data);
        }else{
            $data['store']=$this->Job_model->get_store_for_wine_tpye($sales_rep_id, $data['job']->wine_id);
            $data['get_wine_list']=json_decode(json_encode($this->Job_model->get_wine($storeId)), true);
            $wineList=json_decode(json_encode($this->Job_model->get_wine_names($wineIdArray)), true);

            // Tasting wine info...
            $wineInfo = array();
            foreach($wineList as $wine) {
                
                $wine['bottles_sampled'] = 0;
                $wine['open_bottles_sampled'] = 0;
                $wine['bottles_sold'] = 0;
                array_push($wineInfo,$wine);
             
            }
           
           $data['get_wine_info'] =  $wineInfo;

            $this->load->view('job/edit_job_modal',$data);
        }
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
                //echo $this->tablename;
                // print "<pre>"; print_r($_POST);die;
                $count = 0;
                $items = $this->input->post('item_id');
                $currenttab = $this->input->post('currenttab');
                //echo $currenttab; die;

                if($currenttab=='bulk_schedule_job'){
                    $tableName='bulk_schedule_job';
                }else{
                    $tableName=$this->tablename;
                }
        
                $operation = $this->input->post('operation');
                foreach ($items as $id=>$value) {
                        $this->Job_model->delete_jobs($tableName, $id);
                        ++$count;     
                }

                $msg = ($operation=='delete')?'deleted.':'updated.';
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' Job(s) successfully '.$msg);
                

            } else {
                $this->session->set_flashdata('message_type', 'danger');
                $this->session->set_flashdata('message', validation_errors());
            }
            //redirect('/App/job');
            if($currenttab=='archive' || $currenttab=='bulk_schedule_job'){
                redirect('/App/'.$currenttab.'');
            }else
                redirect('/App/job/index/status/'.$currenttab.'');
        }
    }

    public function create_billing_one_old()
    {
        $this->load->model('Job_model');
        $job_id=$this->input->post('job_id');
        $taster_id=$this->input->post('taster_id');
        $first_time=$this->input->post('time_one');
        $second_time=$this->input->post('time_two');
        //$job_start_time=date("H:i", strtotime($this->input->post('job_start_time').$first_time));
       // $job_end_time=date("H:i", strtotime($this->input->post('finish_time').$second_time));
        $start_hour=$this->input->post('start_time_hour');
        $start_minute=$this->input->post('start_time_minute');
        $actual_start_time=$start_hour.":".$start_minute;
        $job_start_time = date("H:i", strtotime($actual_start_time.$first_time));
        
        $end_hour=$this->input->post('end_time_hour');
        $end_minute=$this->input->post('end_time_minute');
        $actual_end_time=$end_hour.":".$end_minute;
        $job_end_time = date("H:i", strtotime($actual_end_time.$second_time));

        $exp_amount="$".$this->input->post('exp_amount');
        $exp_reason=$this->input->post('exp_reason');
        $comment=$this->input->post('comment');
        $signature_img=$_FILES['signature_img']['name'];
        $date=date("Y-m-d");
        //get user details
        $user=$this->Job_model->get_user_details($taster_id);
        $user_meta=$this->Job_model->get_user_meta($taster_id);
       
        $name=$this->input->post('name');
        if ($name == trim($name) && strpos($name, ' ') !== false) {
            
            $v=explode(" ",$name);
            $first_name=$v[0];
            $last_name=$v[1];
        }
        else
        {
            $first_name=$name;
            $last_name='';
        }
        $first_name=$first_name;
        $last_name=$last_name;
        
        //Create expense details array
        $expense_array=array(
            'taster_id'=>$taster_id,
            'job_id'=>$job_id,
            'exp_amount'=>$exp_amount,
            'exp_reason'=>$exp_reason,
            'date'=>$date
        );
        if($job_end_time < $job_start_time)
        {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> End time should be greater that start time.');
           redirect($this->agent->referrer());
                    
        }
     
        //Start signature image upload to directory
        if (!empty($_FILES['signature_img']['name'])) {
            // Update Product Image
            $config['upload_path'] = DIR_SIGNATURE_IMAGE;
            $config['max_size'] = '10000';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['overwrite'] = FALSE;
            $config['remove_spaces'] = TRUE;
            $this->load->library('upload', $config);
            $images = array();
                $_FILES['images']['name']= $_FILES['signature_img']['name'];
                $_FILES['images']['type']= $_FILES['signature_img']['type'];
                $_FILES['images']['tmp_name'] = $_FILES['signature_img']['tmp_name'];
                $_FILES['images']['error']= $_FILES['signature_img']['error'];
                $_FILES['images']['size']= $_FILES['signature_img']['size'];
                $config['file_name'] = 'signature-'.rand().date('YmdHis');
                $images = $config['file_name'];
                $this->upload->initialize($config);
                if ($this->upload->do_upload('images')) {
                    $config_thumb['image_library'] = 'gd2';
                    $config_thumb['source_image'] = DIR_SIGNATURE_IMAGE.$this->upload->file_name;
                    $config_thumb['create_thumb'] = FALSE;
                    $config_thumb['maintain_ratio'] = TRUE;
                    $config_thumb['master_dim'] = 'auto';
                    $config_thumb['width'] = DIR_SIGNATURE_IMAGE_SIZE; // image re-size  properties
                    $config_thumb['height'] = DIR_SIGNATURE_IMAGE_SIZE; // image re-size  properties
                    $this->load->library('image_lib', $config_thumb); //codeigniter default function
                    $this->image_lib->initialize($config_thumb);
                    if (!$this->image_lib->resize()) {
                         echo $this->image_lib->display_errors();
                    }
                    $this->image_lib->clear();
                    $upload_data =  $this->upload->data();
                    $uploaded_pics = array();
                    $uploaded_pics = $upload_data['file_name'];
                } else {
                   echo  $this->upload->display_errors(); die;
                }
        }
        else
        {
            $uploaded_pics=$this->input->post('old_image');
        }
        
        //Create manager verification 
        $manager_verification_array=array(
            'taster_id'=>$taster_id,
            'job_id'=>$job_id,
            'first_name'=>$first_name,
            'last_name'=>$last_name,
           // 'cell_number'=>$cell_number,
            'comment'=>$comment,
            'signature_img'=>$uploaded_pics,
            'date'=>$date
        );
        $admin_note=$this->input->post('admin_note');
        $general_note=$this->input->post('general_note');
        $admin_note_array=array(
            'job_id'=>$job_id,
            'admin_note'=>$admin_note
        );
        $general_note_array=array(
            'user_id'=>$taster_id,
            'job_id'=>$job_id,
            'general_note'=>$general_note
        );
        //Delete old expense data
        $data['expense_details']=$this->Job_model->get_expense_details($job_id);
       
        $data['manager_verification_details']=$this->Job_model->get_manager_verification_details($job_id);
       // print_r($data['manager_verification_details']);die;
        $this->Job_model->setInvoiceNumber($job_id);
        if(isset($data['expense_details'][0]['job_id']) && isset($data['expense_details'][0]['exp_amount']) && isset($data['expense_details'][0]['exp_reason']))
        {
            //Update expense data
           
            $expense_id=$this->Job_model->update_data('expense_details','id', $data['expense_details'][0]['exp_id'], $expense_array);
        }
        else
        {
           
            $expense_id=$this->Job_model->submit_expense_details_from_cms($expense_array);
        }
      
        //Submit expense details images
        $supported_imgs=$_FILES['expense_images'];
        if (!empty($supported_imgs['name'][0])) {
                        // Update Product Image
                        $config['upload_path'] = DIR_EXPENSE_IMAGE;
                        $config['max_size'] = '10000';
                        $config['allowed_types'] = 'jpg|png|jpeg';
                        $config['overwrite'] = FALSE;
                        $config['remove_spaces'] = TRUE;
                        $this->load->library('upload', $config);
                        $images = array();
                        foreach ($supported_imgs['name'] as $key => $image) {
                            $_FILES['images[]']['name']= $supported_imgs['name'][$key];
                            $_FILES['images[]']['type']= $supported_imgs['type'][$key];
                            $_FILES['images[]']['tmp_name'] = $supported_imgs['tmp_name'][$key];
                            $_FILES['images[]']['error']= $supported_imgs['error'][$key];
                            $_FILES['images[]']['size']= $supported_imgs['size'][$key];
                            $config['file_name'] = 'expense-'.rand().date('YmdHis');
                            $images[] = $config['file_name'];
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('images[]')) {
                                $config_thumb['image_library'] = 'gd2';
                                $config_thumb['source_image'] = DIR_EXPENSE_IMAGE.$this->upload->file_name;
                                $config_thumb['create_thumb'] = FALSE;
                                $config_thumb['maintain_ratio'] = TRUE;
                                $config_thumb['master_dim'] = 'auto';
                                $config_thumb['width'] = DIR_EXPENSE_IMAGE_SIZE; // image re-size  properties
                                $config_thumb['height'] = DIR_EXPENSE_IMAGE_SIZE; // image re-size  properties
                                $this->load->library('image_lib', $config_thumb); //codeigniter default function
                                $this->image_lib->initialize($config_thumb);
                                if (!$this->image_lib->resize()) {
                                     echo $this->image_lib->display_errors();
                                }
                                $this->image_lib->clear();
                                $upload_data =  $this->upload->data();
                                $uploaded_pics = array();
                                $uploaded_pics = $upload_data['file_name'];
                                // Update database here
                                $image_id=$this->Job_model->insert_expense_supported_images($expense_id, $uploaded_pics);
                            } else {
                                $this->upload->display_errors(); die;
                            }
                        }
        }
        else
        {
            //echo $expense_id;die;
            $old_exp_images=$this->input->post('old_exp_image[]');
            if(empty($old_exp_images))
            {
                foreach($old_exp_images as $val)
                {
                    $image_id=$this->Job_model->insert_expense_supported_images($expense_id,$val);
                }
            }
            else
            {
                //Delete old expense images
                $this->Job_model->delete_old_expense_images('expense_details_images',$expense_id);
                foreach($old_exp_images as $val)
                {
                    $image_id=$this->Job_model->insert_expense_supported_images($expense_id,$val);
                }
            }
            
            
        }

        //End to sumit expense details images
        if($expense_id)
        {
            //Delete old manager verification data
            if(!empty($data['manager_verification_details'][0]))
            {
                //Update
                $verifiction=$this->Job_model->update_data('manager_verification_details','id', $data['manager_verification_details'][0]['id'], $manager_verification_array);

            }
            else
            {
                $verifiction=$this->Job_model->submit_manager_verification_details($manager_verification_array);
            }
            
            if($verifiction)
            {
                $admin_note=$this->Job_model->submit_admin_note($job_id,$admin_note_array);
                $general_note=$this->Job_model->submit_general_notes('general_notes',$general_note_array);
                if($general_note)
                {
                    //Set job to ready for billing
                    $difference=strtotime($job_end_time)-strtotime($job_start_time);
                    //Calculate total pause time
                    $time_array=$this->Job_model->calculate_pause_time($job_id);
                    $total_pause_time=0;
                    if(!empty($time_array))
                    {
                        foreach($time_array as $value)
                        {
                            $pause_time=strtotime($value['resume_time'])-strtotime($value['pause_time']);
                            $total_pause_time=$total_pause_time+$pause_time;
                        }
                     
                    }
                    else
                    {
                        $total_pause_time=0;
                    }
                    $working_hour=gmdate("H:i", ($difference - $total_pause_time));
                    $billing_status=$this->Job_model->move_to_billing($job_id,$job_start_time,$job_end_time,$working_hour);
                    
                        $wine=$this->input->post('wine');
                        $bottles_sampled=$this->input->post('bottles_sampled');
                        $open_bottles_sampled=$this->input->post('open_bottles_sampled');
                        $bottles_sold=$this->input->post('bottles_sold');
                        $this->db->select('wine_id, job_id');
                        $this->db->from('completed_job_wine_details');
                        $this->db->where('job_id',$job_id);
                        $result=$this->db->get();
                        $final_result=$result->result_array();
                        //print_r($final_result);die;
                        foreach($final_result as $fr){
                            $this -> db -> where('job_id', $fr['job_id']);
                            $this -> db -> where('wine_id', $fr['wine_id']);
                            $this -> db -> delete('completed_job_wine_details');
                        }
                        $i=0;
                        foreach($wine as $w){
                            $data=array('wine_id'=>$w, 'bottles_sampled'=> $bottles_sampled[$i], 'open_bottles_sampled'=> $open_bottles_sampled[$i], 'bottles_sold'=>$bottles_sold[$i], 'job_id'=> $job_id, 'taster_id'=> $taster_id );
                            $this->db->insert('completed_job_wine_details',$data);
                                ++$i;
                        }

                    if($billing_status)
                    {
                        $this->session->set_flashdata('message_type', 'success');
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> Job has been moved to billing successfully.');
                    }
                }
            }
            
            
        }
        redirect('/App/Job/index/status/problems');
    }


    
    public function create_billing_one()
    {
        $this->load->model('Job_model');
        $job_id=$this->input->post('job_id');
        $taster_id=$this->input->post('taster_id');
        $first_time=$this->input->post('time_one');
        $second_time=$this->input->post('time_two');
        //$job_start_time=date("H:i", strtotime($this->input->post('job_start_time').$first_time));
       // $job_end_time=date("H:i", strtotime($this->input->post('finish_time').$second_time));
        $start_hour=$this->input->post('start_time_hour');
        $start_minute=$this->input->post('start_time_minute');
        $actual_start_time=$start_hour.":".$start_minute;
        $job_start_time = date("H:i", strtotime($actual_start_time.$first_time));
        
        $end_hour=$this->input->post('end_time_hour');
        $end_minute=$this->input->post('end_time_minute');
        $actual_end_time=$end_hour.":".$end_minute;
        $job_end_time = date("H:i", strtotime($actual_end_time.$second_time));

        $exp_amount="$".$this->input->post('exp_amount');
        $exp_reason=$this->input->post('exp_reason');
        $comment=$this->input->post('comment');
        $signature_img=$_FILES['signature_img']['name'];
        $date=date("Y-m-d");
        //get user details
        $user=$this->Job_model->get_user_details($taster_id);
        $user_meta=$this->Job_model->get_user_meta($taster_id);
        /*$first_name=$user->first_name;
        $last_name=$user->last_name;
        foreach($user_meta as $meta)
        {
            if($meta->meta_key=='phone')
            {
                $cell_number=$meta->meta_value;
            }
        }*/
        
        $name=$this->input->post('name');
        if ($name == trim($name) && strpos($name, ' ') !== false) {
            
            $v=explode(" ",$name);
            $first_name=$v[0];
            $last_name=$v[1];
        }
        else
        {
            $first_name=$name;
            $last_name='';
        }
        $first_name=$first_name;
        $last_name=$last_name;
        //$cell_number=$this->input->post('cell_number');
        //Create expense details array
        $expense_array=array(
            'taster_id'=>$taster_id,
            'job_id'=>$job_id,
            'exp_amount'=>$exp_amount,
            'exp_reason'=>$exp_reason,
            'date'=>$date
        );
        if($job_end_time < $job_start_time)
        {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> End time should be greater that start time.');
           redirect($this->agent->referrer());
                    
        }
       // echo "<pre>";
       // print_r($_FILES);die;
        //Start signature image upload to directory
        if (!empty($_FILES['signature_img']['name'])) {
            // Update Product Image
            $config['upload_path'] = DIR_SIGNATURE_IMAGE;
            $config['max_size'] = '10000';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['overwrite'] = FALSE;
            $config['remove_spaces'] = TRUE;
            $this->load->library('upload', $config);
            $images = array();
                $_FILES['images']['name']= $_FILES['signature_img']['name'];
                $_FILES['images']['type']= $_FILES['signature_img']['type'];
                $_FILES['images']['tmp_name'] = $_FILES['signature_img']['tmp_name'];
                $_FILES['images']['error']= $_FILES['signature_img']['error'];
                $_FILES['images']['size']= $_FILES['signature_img']['size'];
                $config['file_name'] = 'signature-'.rand().date('YmdHis');
                $images = $config['file_name'];
                $this->upload->initialize($config);
                if ($this->upload->do_upload('images')) {
                    $config_thumb['image_library'] = 'gd2';
                    $config_thumb['source_image'] = DIR_SIGNATURE_IMAGE.$this->upload->file_name;
                    $config_thumb['create_thumb'] = FALSE;
                    $config_thumb['maintain_ratio'] = TRUE;
                    $config_thumb['master_dim'] = 'auto';
                    $config_thumb['width'] = DIR_SIGNATURE_IMAGE_SIZE; // image re-size  properties
                    $config_thumb['height'] = DIR_SIGNATURE_IMAGE_SIZE; // image re-size  properties
                    $this->load->library('image_lib', $config_thumb); //codeigniter default function
                    $this->image_lib->initialize($config_thumb);
                    if (!$this->image_lib->resize()) {
                         echo $this->image_lib->display_errors();
                    }
                    $this->image_lib->clear();
                    $upload_data =  $this->upload->data();
                    $uploaded_pics = array();
                    $uploaded_pics = $upload_data['file_name'];
                } else {
                   echo  $this->upload->display_errors();
                }
        }
        else
        {
            $uploaded_pics=$this->input->post('old_image');
        }
        
        //Create manager verification 
        $manager_verification_array=array(
            'taster_id'=>$taster_id,
            'job_id'=>$job_id,
            'first_name'=>$first_name,
            'last_name'=>$last_name,
           // 'cell_number'=>$cell_number,
            'comment'=>$comment,
            'signature_img'=>$uploaded_pics,
            'date'=>$date
        );
        $admin_note=$this->input->post('admin_note');
        $general_note=$this->input->post('general_note');
        $admin_note_array=array(
            'job_id'=>$job_id,
            'admin_note'=>$admin_note
        );
        $general_note_array=array(
            'user_id'=>$taster_id,
            'job_id'=>$job_id,
            'general_note'=>$general_note
        );
        //Delete old expense data
        $data['expense_details']=$this->Job_model->get_expense_details($job_id);
       
        $data['manager_verification_details']=$this->Job_model->get_manager_verification_details($job_id);
       // print_r($data['manager_verification_details']);die;
        $this->Job_model->setInvoiceNumber($job_id);
        if(isset($data['expense_details'][0]['job_id']) && isset($data['expense_details'][0]['exp_amount']) && isset($data['expense_details'][0]['exp_reason']))
        {
            //Update expense data
            //$this->Job_model->delete_data('expense_details',$job_id);
            
            $expense_id=$this->Job_model->update_data('expense_details','id', $data['expense_details'][0]['exp_id'], $expense_array);
        }
        else
        {
           
            $expense_id=$this->Job_model->submit_expense_details_from_cms($expense_array);
        }
        
        //echo $expense_id;die;
        //Submit expense details images
        $supported_imgs=$_FILES['expense_images'];
        if (!empty($supported_imgs['name'][0])) {
                        // Update Product Image
                        $config['upload_path'] = DIR_EXPENSE_IMAGE;
                        $config['max_size'] = '10000';
                        $config['allowed_types'] = 'jpg|png|jpeg';
                        $config['overwrite'] = FALSE;
                        $config['remove_spaces'] = TRUE;
                        $this->load->library('upload', $config);
                        $images = array();
                        foreach ($supported_imgs['name'] as $key => $image) {
                            $_FILES['images[]']['name']= $supported_imgs['name'][$key];
                            $_FILES['images[]']['type']= $supported_imgs['type'][$key];
                            $_FILES['images[]']['tmp_name'] = $supported_imgs['tmp_name'][$key];
                            $_FILES['images[]']['error']= $supported_imgs['error'][$key];
                            $_FILES['images[]']['size']= $supported_imgs['size'][$key];
                            $config['file_name'] = 'expense-'.rand().date('YmdHis');
                            $images[] = $config['file_name'];
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('images[]')) {
                                $config_thumb['image_library'] = 'gd2';
                                $config_thumb['source_image'] = DIR_EXPENSE_IMAGE.$this->upload->file_name;
                                $config_thumb['create_thumb'] = FALSE;
                                $config_thumb['maintain_ratio'] = TRUE;
                                $config_thumb['master_dim'] = 'auto';
                                $config_thumb['width'] = DIR_EXPENSE_IMAGE_SIZE; // image re-size  properties
                                $config_thumb['height'] = DIR_EXPENSE_IMAGE_SIZE; // image re-size  properties
                                $this->load->library('image_lib', $config_thumb); //codeigniter default function
                                $this->image_lib->initialize($config_thumb);
                                if (!$this->image_lib->resize()) {
                                     echo $this->image_lib->display_errors();
                                }
                                $this->image_lib->clear();
                                $upload_data =  $this->upload->data();
                                $uploaded_pics = array();
                                $uploaded_pics = $upload_data['file_name'];
                                // Update database here
                                $image_id=$this->Job_model->insert_expense_supported_images($expense_id, $uploaded_pics);
                            } else {
                               echo $this->upload->display_errors();
                            }
                        }
        }
        else
        {
            //echo $expense_id;die;
            $old_exp_images=$this->input->post('old_exp_image[]');
            //echo "<pre>";
           // print_r($old_exp_images);die;
            if(empty($old_exp_images))
            {
                foreach($old_exp_images as $val)
                {
                    $image_id=$this->Job_model->insert_expense_supported_images($expense_id,$val);
                }
            }
            else
            {
                //Delete old expense images
                $this->Job_model->delete_old_expense_images('expense_details_images',$expense_id);
                foreach($old_exp_images as $val)
                {
                    $image_id=$this->Job_model->insert_expense_supported_images($expense_id,$val);
                }
            }
            
            
        }

        //End to sumit expense details images
        if($expense_id)
        {
            //Delete old manager verification data
            if(!empty($data['manager_verification_details'][0]))
            {
                //$this->Job_model->delete_data('manager_verification_details',$job_id);
                //Update
                $verifiction=$this->Job_model->update_data('manager_verification_details','id', $data['manager_verification_details'][0]['id'], $manager_verification_array);

            }
            else
            {
                $verifiction=$this->Job_model->submit_manager_verification_details($manager_verification_array);
            }
            
            if($verifiction)
            {
                $admin_note=$this->Job_model->submit_admin_note($job_id,$admin_note_array);
                $general_note=$this->Job_model->submit_general_notes('general_notes',$general_note_array);
                if($general_note)
                {
                    //Set job to ready for billing
                    // $schedule_difference_time_minite = round(abs($job_schedule_start_time - $job_schedule_end_time) / 60,2);
                    $difference=strtotime($job_end_time)-strtotime($job_start_time);


                    //Calculate total pause time
                    $time_array=$this->Job_model->calculate_pause_time($job_id);
                    $total_pause_time=0;
                    if(!empty($time_array))
                    {
                        foreach($time_array as $value)
                        {
                            $pause_time=strtotime($value['resume_time'])-strtotime($value['pause_time']);
                            $total_pause_time=$total_pause_time+$pause_time;
                        }
                        //echo $total_pause_time;die;
                        //$total_pause_time=strtotime($resume_time)-strtotime($pause_time);
                    }
                    else
                    {
                        $total_pause_time=0;
                    }
                  
                    $working_hour=gmdate("H:i", ($difference - $total_pause_time));

                    // // print_r($working_hour1);die;
                    // print_r($working_hour);die;
                    $billing_status=$this->Job_model->move_to_billing($job_id,$job_start_time,$job_end_time,$working_hour);
                    
                        $wine=$this->input->post('wine');

                        // print_r($wine);die;
                        $bottles_sampled=$this->input->post('bottles_sampled');
                        $open_bottles_sampled=$this->input->post('open_bottles_sampled');
                        $bottles_sold=$this->input->post('bottles_sold');
                        $this->db->select('wine_id, job_id');
                        $this->db->from('completed_job_wine_details');
                        $this->db->where('job_id',$job_id);
                        $result=$this->db->get();
                        $final_result=$result->result_array();
                        //print_r($final_result);die;
                        foreach($final_result as $fr){
                            $this -> db -> where('job_id', $fr['job_id']);
                            $this -> db -> where('wine_id', $fr['wine_id']);
                            $this -> db -> delete('completed_job_wine_details');
                        }

                        $i=0;

                        foreach($wine as $w){

                            $bottleSampled = $bottles_sampled[$i];
                            $bottleSold = $bottles_sold[$i];
                            $openBottleSampled = $open_bottles_sampled[$i];
        
                            if ($bottleSampled == ''){
                                $bottleSampled = 0;
                            }  
                            if ($bottleSold == ''){
                                $bottleSold = 0;
                            }
                            if ($openBottleSampled == ''){
                                $openBottleSampled = 0;
                            }    

                            $data=array('wine_id'=>$w, 'bottles_sampled'=> $bottleSampled, 'open_bottles_sampled'=> $openBottleSampled, 'bottles_sold'=>$bottleSold, 'job_id'=> $job_id, 'taster_id'=> $taster_id );
                            $this->db->insert('completed_job_wine_details',$data);
                                ++$i;
                        }

                    if($billing_status)
                    {
                        $this->session->set_flashdata('message_type', 'success');
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> Job has been moved to billing successfully.');
                    }
                }
            }
            
            
        }
        redirect('/App/Job/index/status/problems');
    }


    public function create_billing_two()
    {
        $job_id=$this->input->post('job_id');
        $admin_note=$this->input->post('admin_note');
        $array=array(
            'job_id'=>$job_id,
            'admin_note'=>$admin_note
        );
        $response=$this->Job_model->submit_admin_note($job_id,$array);
        if($response)
        {

            $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', '<strong>Well done!</strong> Job has been moved to billing successfully.');
            
        }
        redirect('/App/Job/index/status/problems');
    }
    public function open_activity_modal()
    {
        $job_id=$this->input->post('job_id');
        //Get Cancelled user
        $data['cancelled_job_details']=$this->Job_model->cancelled_job_details($job_id);
        //Get requested user
       // $data['requested_job_details']=$this->Job_model->requested_job_details($job_id);
        $data['accepted_user']=$this->Job_model->accepted_user($job_id);
        //Get all rejected users
        $data['rejected_users']=$this->Job_model->rejected_job_details($job_id);
        $data['job_status']=$this->Job_model->check_job_status($job_id);
		//my addition
		$data['rej_user']= $this->Job_model->get_rej_username($job_id);
        $this->load->view('job/activity_job_modal',$data);
    }
    public function map_render_modal(){
        $latitude=$this->input->post('latitude');
        $longitude=$this->input->post('longitude');
        $store_id=$this->input->post('store_id');
        $result=$this->store_model->get_store_location($store_id);
        $data=array();
        $data['latitude']=$latitude;
        $data['longitude']=$longitude;
        $data['store_latitude']=$result->latitude;
        $data['store_longitude']=$result->longitude;
        $this->load->view('job/map_render_modal',$data);
    }
    public function open_sr_modal()
    {
        $store_id=$this->input->post('store_id');
        $data['special_request']=$this->Job_model->get_special_request($store_id);
        $this->load->view('job/special_request_modal',$data);
    }
    public function open_sales_rep_details_modal()
    {
        $id=$this->input->post('user_id');
        $data['details']=$this->Job_model->get_sales_rep_details($id);
        $this->load->view('job/sales_rep_details',$data);
    }
    public function get_tester()
    {
        $store_id=$this->input->post('store_id');
        $taster_id=$this->input->post('taster_id');
        $data['tester']=$this->Job_model->get_tester_or_agency_ajax($store_id);
        $data['taster_id']=$taster_id;
        $this->load->view('job/display_tester',$data);
    }
    public function approve_job($job_id)
    {
        $input_taster_id=$this->input->post('taster_id[]');
        $accepted_tester_id=$this->input->post('accepted_tester_id');
        //get job status and taster id
        $result=$this->Job_model->get_job_details($job_id);
        $status=$result->status;
        $taster_id=$result->taster_id;
        $new_tester_id=explode(",",$taster_id);
        $result_tester_id=array_diff($input_taster_id,$new_tester_id);
       //echo "<pre>";
        //print_r($result_tester_id);die;
        if(empty($result_tester_id))
        {
            $result_tester_id=$new_tester_id;
        }
        $updated_tester_id='';
        if($status=='cancelled')
        {
            /*$job=array(
                'job_status'=>2,
                'status'=>'pending',
                'accept_status'=>0,
            );*/
            //echo "<pre>";
                //print_r($result_tester_id);die;
                foreach($result_tester_id as $id)
                {
                        $updated_tester_id.=$id.",";
                }
                $updated_tester_id=rtrim($updated_tester_id,",");
                //echo "<pre>";
               // print_r($updated_tester_id);die;

                $job=array(
                    'job_status'=>2,
                    'status'=>'pending',
                    'accept_status'=>0,
                    'taster_id'=>$updated_tester_id
                );
                //Delete accept or reject data for each job
                $this->Job_model->delete_accept_reject_data('job_accept_reject',$job_id);
        }
        else
        {
            if($this->input->post('approve') == "Approve") {
                $job=array(
                    'job_status'=>3,
                    'status'=>'approved',
                    'taster_id'=>$accepted_tester_id
                );
            }
            else
            {
                //echo "<pre>";
                //print_r($result_tester_id);die;
                foreach($result_tester_id as $id)
                {
                        $updated_tester_id.=$id.",";
                }
                $updated_tester_id=rtrim($updated_tester_id,",");
                //echo "<pre>";
               // print_r($updated_tester_id);die;

                $job=array(
                    'job_status'=>2,
                    'status'=>'pending',
                    'accept_status'=>0,
                    'taster_id'=>$updated_tester_id
                );
                //Delete accept or reject data for each job
                $this->Job_model->delete_accept_reject_data('job_accept_reject',$job_id);
            }
        }
        //echo $job_id;die;
        //echo "<pre>";
        //print_r($job);die;
        if ($this->Job_model->update_job($this->tablename, 'id', $job_id, $job)) 
        {
            //Send push notification to taster when job will be approved
            if($job['status']=="approved")
            {
                $this->load->library('push_notifications');
                $this->load->model('Notifications_model');

                $notifications = $this->Notifications_model->send_notifications_for_approved_job($accepted_tester_id,$job_id);
            }
            
            //Send email to new tester for job request       
            if($status=='accepted')
            {
                $this->load->library('mail_template');
                   // echo "<pre>";
                    //print_r($result_tester_id);die;
                    foreach($result_tester_id as $tester_id)
                    {
                        //get email of tester
                        $tester_details=$this->Job_model->get_tester_details($tester_id);
                       // print_r($tester_details);die;
                        $email=$tester_details->email;
                        $name=$tester_details->first_name;
                        $this->mail_template->approve_job_email($name,$email);
                    }
            }
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', '<strong>Well done!</strong> Job successfully approved.');
        } 
        else
        {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
                redirect('/App/Job/index/status/accepted');
    }
    public function approve_request()
    {
        $job_id=$this->input->post('job_id');
        $job=array('request_job_approval_status'=>'approved');
        if($this->Job_model->update_job($this->tablename, 'id', $job_id, $job))
        {
            echo true;
        }
    }
    public function get_store_for_sales_rep()
    {
        $sales_rep_id=$this->input->post('sales_rep_id');
        $hidden_store_id=$this->input->post('hidden_store_id');
        $data['hidden_store']=$hidden_store_id;
        $data['store']=$this->Job_model->get_store_for_sales_rep($sales_rep_id);
        $this->load->view('job/get_store', $data);
    }
    /**
     *
     * @param unknown_type $id
     */
    /**
     *
     * @param int $id
     */
    /**
     *
     */
    // public function search_submit() {
    //     if ($this->input->server('REQUEST_METHOD') === 'POST')
    //     {
    //         $sampling_date = $this->clean_value($this->input->post('sampling_date'));
           
    //         $search_text = base64_encode($this->input->post('search_text'));
            
    //         // if (strpos($search_text, '/') !== false) {
    //         //     $search_text = substr($search_text, 0, strpos($search_text, '/'));
    //         // }
    //         // if (strpos($search_text, '\'') !== false) {
    //         //     $search_text = substr($search_text, 0, strpos($search_text, '\''));
    //         // }
    //         // if (strpos($search_text, '%') !== false) {
    //         //     $search_text = substr($search_text, 0, strpos($search_text, '%'));
    //         // }
           
    //         $taster=$this->input->post('search_by_taster');
    //         $sales_rep=$this->input->post('sales_rep');
    //         $tasterarray = array();
            

    //         if ($taster)
    //         {
    //             foreach ($taster as $value)
    //             {
    //                 array_push($tasterarray,$value);
    //             }
    //         }
    //         $st='';
    //         foreach($tasterarray as $i)
    //         {
    //             $st.=$i."@";
    //         }
    //         $st=rtrim($st,"@");
    //         //echo $st;die;
    //        $store = $this->input->post('search_by_store');
    //        $search_by_rating = $this->input->post('search_by_rating');
    //        $search_by_status = $this->input->post('search_by_status');
    //        $sort_by_date = $this->input->post('sort_by_date');
    //        $entry_date = $this->clean_value($this->input->post('entry_date'));
    //        $status=$this->input->post('status');
    //        //print_r($this->input->post());die;
    //         $url = "App/Job/index/";
    //         if ($status != '') {
    //             $url .= "status/". urlencode($status)."/";
    //         }else{
    //             $url .= "status/". urlencode('pre_assigned')."/";
    //         }
    //         if ($sampling_date != '') {
    //             $url .= "tasting_date/". urlencode($sampling_date)."/";
    //         }
    //         if ($taster != '') {
    //             $url .= "taster/".$st."/";
    //         }
            
    //         if ($sales_rep != '') {
    //         $url .= "sales_rep/".$sales_rep."/";
    //         }
    //         if ($store != '') {
    //             $url .= "store/".$store."/";
    //         }
    //         if ($entry_date != '') {
    //             $url .= "entry_date/". urlencode($entry_date)."/";
    //         }
    //         if ($search_text != '') {
    //             $url .= "search_text/".$search_text."/";
    //         }
    //         if ($search_by_rating != '') {
    //             $url .= "search_by_rating/".$search_by_rating."/";
    //         }
    //         if ($search_by_status != '') {
    //             $url .= "search_by_status/".$search_by_status."/";
    //         }
    //         if ($sort_by_date != '') {
    //             $url .= "sort_by_date/".$sort_by_date."/";
    //         }
    //         redirect($url);
    //     }
    // }
    /*public function search_submit() {
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
           $sampling_date = $this->clean_value($this->input->post('sampling_date'));
           $taster=$this->input->post('search_by_taster');
           $status = $this->clean_value($this->input->post('status'));

           //print_r($this->input->post());die;
            $url = "App/Job/index/";
            if ($status != '') {
                $url .= "status/". urlencode($status)."/";
            }
            if ($sampling_date != '') {
                $url .= "tasting_date/". urlencode($sampling_date)."/";
            }
            if ($taster != '') {
                $url .= "taster/".$taster."/";
            }
            redirect($url);
        }
    }*/
    public function search_submit() {
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $sampling_date = $this->clean_value($this->input->post('sampling_date'));
           
            $search_text = base64_encode($this->input->post('search_text'));
            
            // if (strpos($search_text, '/') !== false) {
            //     $search_text = substr($search_text, 0, strpos($search_text, '/'));
            // }
            // if (strpos($search_text, '\'') !== false) {
            //     $search_text = substr($search_text, 0, strpos($search_text, '\''));
            // }
            // if (strpos($search_text, '%') !== false) {
            //     $search_text = substr($search_text, 0, strpos($search_text, '%'));
            // }
           
            $taster=$this->input->post('search_by_taster');
            $sales_rep=$this->input->post('sales_rep');
            $tasterarray = array();
            

            if ($taster)
            {
                foreach ($taster as $value)
                {
                    array_push($tasterarray,$value);
                }
            }
            $st='';
            foreach($tasterarray as $i)
            {
                $st.=$i."@";
            }
            $st=rtrim($st,"@");
            //echo $st;die;
           $store = $this->input->post('search_by_store');
           $search_by_rating = $this->input->post('search_by_rating');
           $search_by_status = $this->input->post('search_by_status');
           $sort_by_date = $this->input->post('sort_by_date');
           $entry_date = $this->clean_value($this->input->post('entry_date'));
           $status = $this->input->post('status');
// print_r($search_by_status);die;
            $url = "App/Job/index/";

            if ($status == "problems"){
                if ($status != '') {
                    $url .= "status/". urlencode($status)."/";
                }
                if ($search_by_status != '') {
                    $url .= "search_by_status/".$search_by_status."/";
                }
            }else{
                if ($search_by_status != '') {
                    $url .= "search_by_status/".$search_by_status."/";
                }
                // else{
                //     if ($status != '') {
                //         $url .= "status/". urlencode($status)."/";
                //     }
                // }
            }

            if ($sampling_date != '') {
                $url .= "tasting_date/". urlencode($sampling_date)."/";
            }
            if ($taster != '') {
                $url .= "taster/".$st."/";
            }
            
            if ($sales_rep != '') {
            $url .= "sales_rep/".$sales_rep."/";
            }
            if ($store != '') {
                $url .= "store/".$store."/";
            }
            if ($entry_date != '') {
                $url .= "entry_date/". urlencode($entry_date)."/";
            }
            if ($search_text != '') {
                $url .= "search_text/".$search_text."/";
            }
            if ($search_by_rating != '') {
                $url .= "search_by_rating/".$search_by_rating."/";
            }
            
            if ($sort_by_date != '') {
                $url .= "sort_by_date/".$sort_by_date."/";
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
    public function get_wine_using_search_key()
    {
        $store_id=$this->input->post('store_id');
        $search_key=$this->input->post('search_key');
        $data['wine']=$this->Job_model->get_wine_using_search_key($store_id,$search_key);
        $data['wine_id']='';
        $this->load->view('job/display_wine',$data);
    }
    //Set wine id to session
    public function set_wine_id()
    {
        
        $wine_id_array=$this->input->post('wine_id_array');
        
        /*$old_ids=$this->session->userdata('wine_ids');
        if(isset($old_ids) && !empty($old_ids))
        {
            $pre_wine_ids=$this->session->userdata('wine_ids');
            $wine_id_array=array_merge($wine_id_array,$pre_wine_ids);
        }*/
        
        $cv=array_count_values($wine_id_array);
        foreach($cv as $key=>$value)
        {
            if(($value%2)==0)
            {
                $actual_value=$key;
                foreach($wine_id_array as $k=>$v)
                {
                    if($v==$actual_value)
                    {
                        unset($wine_id_array[$k]);
                    }
                }
            }
        }
        
        $this->session->set_userdata('wine_ids',$wine_id_array);
        
        //Get only wine name
        if(!empty($wine_id_array)){
            $names=$this->Job_model->get_wine_names($wine_id_array);

            $str='';
            foreach($names as $val)
            {
                $str.=$val['name'].",";
            }
            $str=rtrim($str,",");
            $str = implode(',',array_unique(explode(',', $str)));
            
            echo $str;
        }
        else
        {
            echo '';
        }
        
            
        
        $this->session->set_userdata('wine_ids',$wine_id_array);
        
        
    }
    public function set_wine_id_ajax()
    {
        
        $wine_id_array=$this->input->post('wine_id_array');
        
        $cv=array_count_values($wine_id_array);
        foreach($cv as $key=>$value)
        {
            if(($value%2)==0)
            {
                $actual_value=$key;
                foreach($wine_id_array as $k=>$v)
                {
                    if($v==$actual_value)
                    {
                        unset($wine_id_array[$k]);
                    }
                }
            }
        }
        $this->session->set_userdata('wine_ids',$wine_id_array);
        
        //echo "<pre>";
       // print_r($wine_id_array);
        //Get only wine name
        if(!empty($wine_id_array)){
        $names=$this->Job_model->get_wine_names($wine_id_array);
        
        $str='';
        foreach($names as $val)
        {
            $str.=$val['name'].",";
        }
        $str=rtrim($str,",");
        $str = implode(',',array_unique(explode(',', $str)));
        echo $str;
        }
        else
        {
            echo '';
        }
        
        $this->session->set_userdata('wine_ids',$wine_id_array);
        
        
    }
    
    //Clone any job
    public function clone_job($job_id=null) 
    {
        
        // Permission Checking
        //parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            
            //form validation
            $this->form_validation->set_rules('tasting_date', 'Job Date', 'trim|required');
            /*$this->form_validation->set_rules('start_time', 'Start Time', 'trim|required');
            $this->form_validation->set_rules('end_time', 'End Time', 'trim|required');*/
            $this->form_validation->set_rules('start_time_hour', 'Start time', 'trim|required');
	    	$this->form_validation->set_rules('end_time_hour', 'End time', 'trim|required');
            $this->form_validation->set_rules('start_time_minute', 'Start time', 'trim|required');
	    	$this->form_validation->set_rules('end_time_minute', 'End time', 'trim|required');
            $this->form_validation->set_rules('store_id', 'Store', 'trim|required');
            //$this->form_validation->set_rules('admin_note', 'Admin Note', 'trim|required');
            //$this->form_validation->set_rules('taster_note', 'Taster Note', 'trim|required');
            //$this->form_validation->set_rules('taster_id[]', 'Tester or Agency required', 'trim|required');
            $this->form_validation->set_rules('wine_id[]', 'Wine', 'trim|required');
           // $this->form_validation->set_rules('question_id[]', 'Question', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $user_id=$this->input->post('user_id');
                $tasting_date = date("Y-m-d",strtotime($this->input->post('tasting_date')));
                $store_id=$this->input->post('store_id');

                if($this->input->post('start_time_hour')!='')
                {
                    $first_time=$this->input->post('time_one');
                    $start_hour=$this->input->post('start_time_hour');
                    $start_minute=$this->input->post('start_time_minute');
                    $actual_start_time=$start_hour.":".$start_minute;
                    $start_time = date("H:i", strtotime($actual_start_time.$first_time));
                }
                else
                {
                    $start_time='';
                }
                if($this->input->post('end_time_hour')!='')
                {

                    $second_time=$this->input->post('time_two');
                    $end_hour=$this->input->post('end_time_hour');
                    $end_minute=$this->input->post('end_time_minute');
                    $actual_end_time=$end_hour.":".$end_minute;
                    $end_time = date("H:i", strtotime($actual_end_time.$second_time));
                }
                else
                {
                    $end_time='';
                }
                //Get tester or agency
                $taster_id=$this->input->post('taster_id');

                /*
                $taster = '';
                if($taster_id != ''){
                $taster_type=$this->Job_model->get_user_type('users',$taster_id);

                foreach ($taster_type as $type) {
                        $taster = $type->user_type;
                    }
                }*/

                //Get wine
                $wine_array=$this->input->post('wine_id[]');
               
                //Check wine type
                $wine_flavours=$this->get_wine_flavour($wine_array);
                if($wine_flavours)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> You can not select different types of wine together.');
                    redirect('/App/job/clone_job/'.$job_id);
                }
                $datetime1 = strtotime($start_time);
                $datetime2 = strtotime($end_time);
                $interval  = abs($datetime2 - $datetime1);
                $minutes   = round($interval / 60);
                //echo 'Diff. in minutes is: '.$minutes; die;
                if($minutes<30)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The start time and the end time should not be same. There should be a gap of minimum 30 min between start and end time.');
                    redirect('App/job/clone_job/'.$job_id);
                }
           
                $wine_id='';
                foreach($wine_array as $val)
                {
                    $wine_id.=$val.',';
                }
                $wine_id=rtrim($wine_id,',');
                //Get question
                $question_array=$this->input->post('question_id[]');
                $question_id='';
                if(!empty($question_array)){
                    
                    foreach($question_array as $val)
                    {
                        $question_id.=$val.',';
                    }
                    $question_id=rtrim($question_id,',');
                }
                else
                {
                    $question_id='';
                }


                if($taster_id!='')
                {
                    $status='assigned';
                    $job_status=2;
                }
                else
                {
                    $status='pending';
                    $job_status=1;
                }

                /*
                if ($taster == 'agency'){
                    if($taster_id!='')
                    {
                        $status='pending';
                        $job_status=1;
                    }
                }else{
                    if($taster_id!='')
                    {
                        $status='assigned';
                        $job_status=2;
                    }
                    else
                    {
                        $status='pending';
                        $job_status=1;
                    }
                }*/

                $job = array(
                    'user_id' => htmlspecialchars($user_id, ENT_QUOTES, 'utf-8'),
                    'tasting_date' => htmlspecialchars($tasting_date, ENT_QUOTES, 'utf-8'),
                    'start_time' => htmlspecialchars($start_time, ENT_QUOTES, 'utf-8'),
                    'end_time' => htmlspecialchars($end_time, ENT_QUOTES, 'utf-8'),
                    'store_id' => htmlspecialchars($this->input->post('store_id'), ENT_QUOTES, 'utf-8'),
                    'admin_note' => htmlspecialchars($this->input->post('admin_note'), ENT_QUOTES, 'utf-8'),
                    'taster_note' => htmlspecialchars($this->input->post('taster_note'), ENT_QUOTES, 'utf-8'),
                    'taster_id' => $taster_id,
                    'wine_id' => $wine_id,
                    'question_id'=>$question_id,
                    'status'=> $status,
                    'job_status'=>$job_status,
                );
                if($end_time < $start_time)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> End time should be greater that start time.');
                    redirect('/App/job/clone_job/'.$job_id);
                }

                //if the insert has returned true then we show the flash message
                //Check job for same store and same time
				$count_job=$this->Job_model->check_tester_availablity('job',$taster_id,$tasting_date,$start_time,$end_time);
                if($count_job == 0)
                {
                    
                    $this->load->library('push_notifications');
                    $this->load->model('Notifications_model');
        
                    $notifications = $this->Notifications_model->send_notifications_for_republish_job($taster_id,$job_id);
                    if ($this->Job_model->create_job($this->tablename,$job)) {

                        //Send Email to testers/agency who are assigned
                    $this->load->library('mail_template');

                    /*
                        //get tester id and name
                        $result['tester_info']=$this->Job_model->get_tester_details($taster_id);
                        $name=$result['tester_info']->first_name;
                        $email=$result['tester_info']->email;
                        $this->mail_template->assigned_job_email($name,$email); */


                        $samplingDate = date("F d, Y", strtotime($tasting_date));
                        $startTime = date("h:i a",strtotime($start_time));
                        $finish_time = date("h:i a",strtotime($end_time));
                        
                        $wineNames=$this->Job_model->get_assign_mail_wine_names($wine_array);

                        $store = $this->Job_model->get_store_name($store_id);
                        $store_name = $store->name;
                        $store_address = $store->adress;
                        $salesrep = $this->Job_model->get_mail_selsrep_name($job_id);
                        $salesrep_name = $salesrep->sales_rep_name;
                       
                        //get tester id and name
                        $result['tester_info']=$this->Job_model->get_tester_details($taster_id);
                        $name=$result['tester_info']->first_name;
                        $email=$result['tester_info']->email;

                        $this->mail_template->assigned_job_email($name,$email,$samplingDate,$startTime,$finish_time,$wineNames,$store_name,$store_address,$salesrep_name);

                    $this->session->unset_userdata('wine_ids');
                    $this->session->set_flashdata('message_type', 'success');
                    $this->session->set_flashdata('message', '<strong>Well done!</strong> Job successfully cloned.');
                    } else{
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
                    }

                    redirect('/App/Job');
                   /*
                    if($taster_id!='')
                    {
                        redirect('/App/Job/index/status/assigned');
                    }
                    else
                    {
                        redirect('/App/Job/index/status/pre_assigned');
                    }*/
                }
                else
                {
                    $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The taster has assigned with other job.');
                    redirect('/App/job/clone_job/'.$job_id);
                }
            } //validation run
        }
        //get details of job
        $data['job']  = $this->Job_model->job_details($job_id);
        $sales_rep_id=$data['job']->user_id;
        $data['sales_rep_id']=$sales_rep_id;
        $data['store']=$this->Job_model->get_store_for_sales_rep($sales_rep_id);
        //get wine
        $store_id=$data['job']->store_id;
        $data['wine']=$this->Job_model->get_wine($store_id);
        //get tester or agency
        $data['tester']=$this->Job_model->get_tester_or_agency($job_id);
        //get question answers
        $data['question_answers']=$this->Job_model->get_question_answers();
        //Get sales representaive name
        $data['sales_rep']=$this->Job_model->get_user_name($data['job']->user_id);
        if (!is_numeric($job_id) || $job_id == 0 || empty($data['job'])) {
            redirect('/App/job');
        }        
        $data['page'] = 'Clone job';
        $data['page_title'] = SITE_NAME.' :: Job Management &raquo; Clone job';
        $data['main_content'] = 'job/clone_job';
        $this->load->view(TEMPLATE_PATH, $data);
    }


	public function deleteJob(){
		$jobId = $this->input->post('jobId');
		$res = $this->Job_model->deleteJob($jobId);
		if($res){
			echo 1;
		}else{
			echo 0;
		}
		
	}
	public function check_tester_availablity(){
		$tasting_date = date("Y-m-d",strtotime($this->input->post('job_date')));
		$taster_id = $this->input->post('taster_id');
		$start_time = $this->input->post('start_time');
		$end_time = $this->input->post('end_time');
		$count_job=$this->Job_model->check_tester_availablity('job',$taster_id,$tasting_date,$start_time,$end_time);
		echo $count_job;
	}
	public function check_tester_availablity_with_jobid(){
		$job_id = $this->input->post('job_id');
		$tasting_date = date("Y-m-d",strtotime($this->input->post('job_date')));
		$taster_id = $this->input->post('taster_id');
		$start_time = $this->input->post('start_time');
		$end_time = $this->input->post('end_time');
		$count_job=$this->Job_model->check_tester_availablity_with_jobid($job_id,'job',$taster_id,$tasting_date,$start_time,$end_time);
		echo $count_job;
	}
    public function get_my_tester_wine(){
        $store_id=$this->input->post('store_id');
        $data['tester']=$this->Job_model->get_tester_or_agency_ajax($store_id);
		$data['wine']=$this->Job_model->get_wine($store_id);
		// create dynamic taster and wine html
		$html['tasHtml']='<option value="">Select a taster</option>';
		$html['wineHtml']='';
		foreach($data['tester'] as $tes){
			$role_id=$this->Job_model->get_user_role('users',$tes['id']);
			if($role_id=='5')
			{
				$name=$this->Job_model->get_agency_name('user_meta',$tes['id']);
			}else{
				$name = $tes['last_name']." ".$tes['first_name'];
			}
			$html['tasHtml'].='<option value="'.$tes['id'].'">'.$name.'</option>';
		}
		//wine html
		foreach($data['wine'] as $win){
			$html['wineHtml'].='<option value="'.$win->id.'">'.$win->name.'</option>';
		}
		echo json_encode($html);
    }
    public function completed_job_details_view_modal()
    {
        $job_id=$this->input->post('job_id');
        //Get job details
        
        $data['job']  = $this->Job_model->job_details($job_id);
        $sales_rep_id=$data['job']->user_id;

        $taster_id=$data['job']->taster_id;
        $agency_taster_id=$data['job']->agency_taster_id;
    
        $data['taster_name'] = 'N/A';
        $data['agency_name'] = 'N/A';
        if($agency_taster_id>0){
            $data['taster_name'] = $this->Job_model->getTasterName($agency_taster_id);
            $data['agency_name'] = $this->Job_model->get_agency_name('user_meta',$taster_id);
        }else{
            $data['taster_name'] = $this->Job_model->getTasterName($taster_id);
        }
        //get tester or agency
        $data['tester']=$this->Job_model->get_tester_or_agency($job_id);
        //$data['tester_rate']=$this->Job_model->get_tester_rate($job_id);

        $setup_id=$this->Job_model->get_tasting_setup_id('tasting_setup',$job_id);
        //Get setup images
        $id_container=array();
        if(!empty($setup_id))
        {
            foreach($setup_id as $val)
            {
                array_push($id_container,$val['id']);
            }
        }
        else
        {
            $id_container='';
        }
        // $data['images']=$this->Job_model->get_setup_image('tasting_setup_images',$id_container);

        $images=$this->Job_model->get_setup_image('tasting_setup_images',$id_container);

        $tasting_image=array();
        $store_image=array();
        foreach($images as $imageType){
            if($imageType['tasting_type'] == 'tasting'){
                array_push($tasting_image,$imageType);
            }else{
                array_push($store_image,$imageType);
            }
        }
        $data['store_images'] = $store_image;
        $data['tasting_images'] = $tasting_image;


        $data['manager_verification_details']=$this->Job_model->get_manager_verification_details($job_id);
       
        if($data['job']->job_state==2){
            $data['store']=json_decode(json_encode($this->Job_model->get_store()), true);
            $data['expense_details']=$this->Job_model->get_expense_details($job_id);
             $data['general_note']=$this->Job_model->get_general_note($job_id);
             $data['sales_rep']=$this->Job_model->get_sales_rep();
             $data['get_wine_info']=$this->Job_model->get_wines_sampled_sold_details($job_id);
             $data['get_wine_list']=json_decode(json_encode($this->Job_model->get_all_wine()), true);
             $data['expence_amount']=$this->Job_model->get_expense_amount($job_id);
             $data['manager_verification_details']=$this->Job_model->get_manager_verification_details($job_id);
            // echo "<pre>";
            // print_r($data['get_wine_list']);
            // print_r($data['get_wine_info']);die;
            $this->load->view('job/completed_view_details_modal',$data);
        }else{
            $data['store']=$this->Job_model->get_store_for_sales_rep($sales_rep_id);
            $this->load->view('job/completed_view_details_modal',$data);
        }
    }
}