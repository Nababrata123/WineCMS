<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Bulk_schedule_job extends Application_Controller {
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
    private $tablename = 'bulk_schedule_job';
    private $url = '/App/bulk_schedule_job';
    private $reference_url = '';
    private $permissionValues = array(
        'index' => 'App.Bulk_schedule_job.View',
        'publish_job'=>'App.Bulk_schedule_job.Publishjob',
        'add'=>'App.Bulk_schedule_job.Add',
        'edit_job'=>'App.Bulk_schedule_job.Edit',
        'delete'=>'App.Bulk_schedule_job.Delete'
    );
    //private $allowed_roles = array('bar_admin');
    public function __construct() {
        parent::__construct();
        // Validate Login
        parent::checkLoggedin();
        $this->module_dir = APPPATH.'modules/'.$this->router->fetch_module();
        $this->load->config('config');
        $this->session->set_userdata('page_data', array('url' => $this->url, 'permissions' => $this->permissionValues));
        $this->load->model('Bulk_schedule_job_model');
        $this->load->model('Job_model');
        $this->load->library('user_agent');
        $this->load->helper('template');
    }
    public function index() {
    // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        $default_uri = array( 'page','status','tasting_date','taster','store','entry_date','search_text');
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
            //echo $uri['taster'];die;
            $pegination_uri['taster'] = $uri['taster'];
            
        }
        if (isset($uri['store']) && trim(urldecode($uri['store'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['store'] = $uri['store'];
            $pegination_uri['store'] = $uri['store'];
            
        }
        if (isset($uri['entry_date']) && trim(urldecode($uri['entry_date'])) <> "") {
            $filter['entry_date'] = $uri['entry_date'];
            $pegination_uri['entry_date'] = $uri['entry_date'];
            
        }
        if (isset($uri['search_text']) && trim(urldecode($uri['search_text'])) <> "") {
            $filter['search_text'] = $uri['search_text'];
            $pegination_uri['search_text'] = $uri['search_text'];       
        }
        else
        {
            $filter['search_text'] = "";
            $pegination_uri['search_text'] = "~";
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
        $total_rows = $this->Bulk_schedule_job_model->get_job_list($filter, null, null, true);
        //echo $total_rows;die;
        //$config = $this->init_pagination('App/Bulk_schedule_job/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/',5, $total_rows);
        /*$config = $this->init_pagination('App/Bulk_schedule_job/index/page/',6, $total_rows);
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        }
        $filter['limit'] = $config['per_page'];
        $filter['offset'] = $limit_end;*/
        // Get the jobs List
        $data['jobs'] = $this->Bulk_schedule_job_model->get_job_list($filter, 'id', 'asc');
        
        $data['filters'] = $uri;
        $data['pagestatus']='bulk_schedule_job';
        $data['page'] = 'Bulk schedule';
        $data['page_title'] = SITE_NAME.' :: Bulk schedule Management';
        $data['main_content'] ='job/bulk_schedule_list';
        $this->load->view(TEMPLATE_PATH, $data);
    }
    
    //Create job from admin pannel
    public function add() {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    	$config = $this->config->item('module_config');

    	$data['user_meta'] = $config['users']['meta'];

    	//if save button was clicked, get the data sent via post
    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            
            //Set input data to session
            $newdata = array(
				'user_id'  => $this->input->post('user_id'),
				'store_id'  => $this->input->post('store_id'),
				'start_time_hour'  => $this->input->post('start_time_hour'),
				'start_time_minute'  => $this->input->post('start_time_minute'),
				'time_one'  => $this->input->post('time_one'),
				'end_time_hour'  => $this->input->post('end_time_hour'),
				'end_time_minute'  => $this->input->post('end_time_minute'),
				'time_two'  => $this->input->post('time_two'),
				);

            $this->session->set_userdata('inputdata',$newdata);
            
     		//form validation
     		$this->form_validation->set_rules('rules', 'Schedule rules', 'trim|required');
            $this->form_validation->set_rules('user_id', 'Sales representative', 'trim|required');
			//$this->form_validation->set_rules('tasting_date', 'Tasting date', 'trim|required');
    		//$this->form_validation->set_rules('start_time', 'Start time', 'trim|required');
	    	//$this->form_validation->set_rules('end_time', 'End time', 'trim|required');
            $this->form_validation->set_rules('start_time_hour', 'Start time', 'trim|required');
	    	$this->form_validation->set_rules('end_time_hour', 'End time', 'trim|required');
            $this->form_validation->set_rules('start_time_minute', 'Start time', 'trim|required');
	    	$this->form_validation->set_rules('end_time_minute', 'End time', 'trim|required');
	    	$this->form_validation->set_rules('store_id', 'Store', 'trim|required');
            //$this->form_validation->set_rules('admin_note', 'Admin note', 'trim|required');
	    	//$this->form_validation->set_rules('taster_id', 'Taster', 'trim|required');
	    	//$this->form_validation->set_rules('wine_id[]', 'At least one wine', 'trim|required');

			
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    		//if the form has passed through the validation

    		if($this->form_validation->run())
    		{
				//print_r($_POST);die;
    			$user_id = $this->input->post('user_id');
                $rules = $this->input->post('rules');
                $start_date = date('Y-m-d',strtotime($this->input->post('start_date')));
                $end_date = date('Y-m-d',strtotime($this->input->post('end_date')));
				$taster = $this->input->post('taster');
				if($taster !=''){
					$wine_id = implode(',',$this->input->post('wine'));
				}else{
					$wine_id = '';
				}
				
                if($end_date<=$start_date)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> End date should be greater than start date.');
                    redirect('/App/Bulk_schedule_job/add');
                }
				if($taster !='' && $wine_id==''){
					$this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Select a wine.');
                    redirect('/App/Bulk_schedule_job/add');
				}
                /***
                    Implement bulk schedule job based on month or week basis
                
                ***/
                
                $count=0;
                while($start_date <= $end_date) {
                    
                    $first_date=$start_date;
                    if($count==0)
                    {
                        $tasting_date=$first_date;
                    }
                    else
                    {
                        $start_date = strtotime($start_date);
                        if($rules=='weekly')
                        {
                           $start_date = strtotime("+7 day", $start_date);
                        }
                        else if($rules=='biweekly')
                        {
                           $start_date = strtotime("+14 day", $start_date);
                        }
                        else
                        {
                            $start_date = strtotime("+30 day", $start_date);
                        }

                        $start_date=date('Y-m-d', $start_date);
                        if($count==0)
                        {
                            $tasting_date=$tasting_date;
                        }
                        else
                        {
                            $tasting_date=$start_date;
                        }
                    }
                    
                    //$tasting_date = date("Y-m-d",strtotime($this->input->post('tasting_date')));
                    if($this->input->post('start_time_hour')!='')
                    {
                        //echo 1;die;
                        $first_time=$this->input->post('time_one');
                        //$start_time = date("H:i", strtotime($this->input->post('start_time')));
                        $start_hour=$this->input->post('start_time_hour');
                        $start_minute=$this->input->post('start_time_minute');
                        $actual_start_time=$start_hour.":".$start_minute;
                       // $start_time = date("H:i", strtotime($this->input->post('start_time').$first_time));
                        $start_time = date("H:i", strtotime($actual_start_time.$first_time));
                    }
                    else
                    {
                        //echo 2;die;
                        $start_time='';
                    }
                    if($this->input->post('end_time_hour')!='')
                    {
                        $second_time=$this->input->post('time_two');
                        //$end_time = date("H:i", strtotime($this->input->post('end_time')));
                        $end_hour=$this->input->post('end_time_hour');
                        $end_minute=$this->input->post('end_time_minute');
                        $actual_end_time=$end_hour.":".$end_minute;
                        //$end_time = date("H:i", strtotime($this->input->post('end_time').$second_time));
                        $end_time = date("H:i", strtotime($actual_end_time.$second_time));
                    }
                    else
                    {
                        $end_time='';
                    }
                    $datetime1 = strtotime($start_time);
                    $datetime2 = strtotime($end_time);
                    $interval  = abs($datetime2 - $datetime1);
                    $minutes   = round($interval / 60);
                    if($minutes<30)
                    {
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> The start time and the end time should not be same. There should be a gap of minimum 30 min between start and end time.');
                        redirect('/App/Bulk_schedule_job/add');
                    }
                    $store_id = $this->input->post('store_id');


                    if($start_date <= $end_date)
                    {
                        $count++;
                        $job['user_id']=$user_id;
                        $job['tasting_date']=$tasting_date;
                        $job['start_time']=$start_time;
                        $job['end_time']=$end_time;
                        $job['store_id']=$store_id;
                        $job['schedule_type']=$rules;
						$job['taster_id'] = $taster;
						$job['wine_id'] = $wine_id;
                            //echo "u";die;
                        $insert=$this->Bulk_schedule_job_model->create_job('bulk_schedule_job',$job);  
                        
                    }  
                    }

                    if($insert)
                    {
						//automatic publish within two month
						//current date
						$dateOne = date('Y-m-d');
						// after 60th days date
						$dateTwo = date('Y-m-d', strtotime("+60 days"));
						//get all job with in two month with taster id and wine id
						$withinTwojob = $this->Bulk_schedule_job_model->withinTwoMonthjobwithTaster('bulk_schedule_job',$dateOne,$dateTwo);
						if(count($withinTwojob) > 0){
							foreach($withinTwojob as $job){
								$job_id= $job['id'];
								$data=array(
									'status'=>'published',
								);
								if ($this->Bulk_schedule_job_model->update_job('bulk_schedule_job','id', $job_id,$data)) {
                
									//Create a copy of the job and moved to job table
									$job_details=$this->Bulk_schedule_job_model->job_details($job_id);
									//echo "<pre>";
									//print_r($job_details);die;
									if($job_details->taster_id!='')
									{
										$status='assigned';
										$job_status=2;
									}
									else
									{
										$status='pending';
										$job_status=1;
									}
									
									$pubjob['user_id']=$job_details->user_id;
									$pubjob['tasting_date']=$job_details->tasting_date;
									$pubjob['start_time']=$job_details->start_time;
									$pubjob['end_time']=$job_details->end_time;
									$pubjob['store_id']=$job_details->store_id;
									$pubjob['admin_note']=$job_details->admin_note;
									$pubjob['taster_id']=$job_details->taster_id;
									$pubjob['taster_note']=$job_details->taster_note;
									$pubjob['wine_id']=$job_details->wine_id;
									$pubjob['status']=$status;
									$pubjob['job_status']=$job_status;
									//End
									//print_r();
									//Copy and create a job from bulk schedule to job table
									$copy_id=$this->Bulk_schedule_job_model->create_job('job',$pubjob);
									if($copy_id)
									{
										$this->load->library('push_notifications');
										$this->load->model('Notifications_model');
										
										$notifications = $this->Notifications_model->send_notifications_for_republish_job($job_details->taster_id,$copy_id);

										$this->load->library('mail_template');
										
										//get tester id and name
										$result['tester_info']=$this->Job_model->get_tester_details($job_details->taster_id);
										//echo "<pre>";
									   // print_r($result['tester_info']);die;
										$name=$result['tester_info']->first_name;
										$email=$result['tester_info']->email;
										//echo $email;die;
                                        // $this->mail_template->assigned_job_email($name,$email);
                                        
                                        $samplingDate = date("F d, Y", strtotime($job_details->tasting_date));
                                        $startTime = date("h:i a",strtotime($job_details->start_time));
                                        $finish_time = date("h:i a",strtotime($job_details->end_time));

                                        $wine_id_array = explode(',', $job_details->wine_id);
                                        $wineNames=$this->Job_model->get_assign_mail_wine_names($wine_id_array);
                        
                                        $store = $this->Job_model->get_store_name($job_details->store_id);
                                        $store_name = $store->name;
                                        $store_address = $store->adress;

                                        $salesrep = $this->Job_model->get_mail_selsrep_name($copy_id);
                                        $salesrep_name = $salesrep->sales_rep_name;
                                        
                                        //echo $email;die;
                                        $this->mail_template->assigned_job_email($name,$email,$samplingDate,$startTime,$finish_time,$wineNames,$store_name,$store_address,$salesrep_name);
										
									}
								}
							}
						}
                        $this->session->unset_userdata('inputdata');
						if(count($withinTwojob) > 0){
							$this->session->set_flashdata('message_type', 'success');
							$this->session->set_flashdata('message', 'Schedule has been created successfully. Jobs having schedule date before 2 months has been published and assigned to the taster.');
						}else{
							$this->session->set_flashdata('message_type', 'success');
							$this->session->set_flashdata('message', 'Schedule has been created successfully.');
						}
						
						redirect('/App/Bulk_schedule_job');
                    }
                    else
                    {
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Job not created.');
                        redirect('/App/Bulk_schedule_job/');
                    } 
                /**
                    End
                **/
                
				
    		} 
    	}

		

    	$data['page'] = 'add_job';
    	$data['page_title'] = SITE_NAME.' :: Bulk Schedule Management &raquo; Add Bulk Schedule';
        //Get sales representative
        $data['sales_rep']=$this->Bulk_schedule_job_model->get_sales_rep();
        //get store
        $data['store']=$this->Bulk_schedule_job_model->get_store();
        //get wine
        
        $data['wine']=$this->Bulk_schedule_job_model->get_all_wine();
        //get tester or agency
        $data['tester']=$this->Bulk_schedule_job_model->get_taster();

    	$data['main_content'] = 'job/add_bulk_schedule';
    	$this->load->view(TEMPLATE_PATH, $data);
    }

    
    public function publish_job($job_id=NULL) 
    {
        
        // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        //if save button was clicked, get the data sent via post
        //echo $id;die;
        // Can't delete yourself
    	if ($job_id == $this->session->userdata('id')) {
    		$this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');

            redirect('/App/bulk_schedule_job');
    	}

    	

    	if (!is_numeric($job_id) || $job_id == 0) {
    		redirect('/App/bulk_schedule_job');
    	}
        //Check assign wine and taster
        $check=$this->Bulk_schedule_job_model->check_assign_status($job_id);
        if($check=='true')
        {
            $data=array(
            'status'=>'published',

            );
            if ($this->Bulk_schedule_job_model->update_job($this->tablename,'id', $job_id,$data)) {
                
                //Create a copy of the job and moved to job table
                $job_details=$this->Bulk_schedule_job_model->job_details($job_id);
                //echo "<pre>";
                //print_r($job_details);die;
                if($job_details->taster_id!='')
                {
                    $status='assigned';
                    $job_status=2;
                }
                else
                {
                    $status='pending';
                    $job_status=1;
                }
                
                $job['user_id']=$job_details->user_id;
                $job['tasting_date']=$job_details->tasting_date;
                $job['start_time']=$job_details->start_time;
                $job['end_time']=$job_details->end_time;
                $job['store_id']=$job_details->store_id;
                $job['admin_note']=$job_details->admin_note;
                $job['taster_id']=$job_details->taster_id;
                $job['taster_note']=$job_details->taster_note;
                $job['wine_id']=$job_details->wine_id;
                $job['status']=$status;
                $job['job_status']=$job_status;
                //End
                //Copy and create a job from bulk schedule to job table
                $copy_id=$this->Bulk_schedule_job_model->create_job('job',$job);
                if($copy_id)
                {
                    $this->load->library('push_notifications');
                    $this->load->model('Notifications_model');
                    
                    $notifications = $this->Notifications_model->send_notifications_for_republish_job($job_details->taster_id,$copy_id);

                    $this->load->library('mail_template');
                    
                    //get tester id and name
                    $result['tester_info']=$this->Job_model->get_tester_details($job_details->taster_id);
                    $name=$result['tester_info']->first_name;
                    $email=$result['tester_info']->email;
                    //echo $email;die;
                    // $this->mail_template->assigned_job_email($name,$email);

                    $samplingDate = date("F d, Y", strtotime($job_details->tasting_date));
                    $startTime = date("h:i a",strtotime($job_details->start_time));
                    $finish_time = date("h:i a",strtotime($job_details->end_time));

                    $wine_id_array = explode(',', $job_details->wine_id);
                    $wineNames=$this->Job_model->get_assign_mail_wine_names($wine_id_array);
    
                    $store = $this->Job_model->get_store_name($job_details->store_id);
                    $store_name = $store->name;
                    $store_address = $store->adress;

                    $salesrep = $this->Job_model->get_mail_selsrep_name($copy_id);
                    $salesrep_name = $salesrep->sales_rep_name;
                   
                    //echo $email;die;
                    $this->mail_template->assigned_job_email($name,$email,$samplingDate,$startTime,$finish_time,$wineNames,$store_name,$store_address,$salesrep_name);
                    
                    
                    $this->session->set_flashdata('message_type', 'success');
                    $this->session->set_flashdata('message', '<strong>Well done!</strong> Schedule  successfully published and moved to job section.');
                }
                else
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Schedule are not published');
                }
                
            } else {
                $this->session->set_flashdata('message_type', 'danger');
                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
            }
        }
        else
        {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Taster or wine are not selected for the job.Please select Taster and wine for the job and publish.');
        }
        
        redirect('/App/bulk_schedule_job');
        
    }

    public function edit_job($job_id=0)
    {
// Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        $is_deleted = check_is_deleted('bulk_schedule_job',$job_id);
        if($is_deleted==false)
        {
          redirect('App/bulk_schedule_job');
        }
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
            $this->form_validation->set_rules('taster_id[]', 'Tester or Agency required', 'trim|required');
            $this->form_validation->set_rules('wine_id[]', 'Wine', 'trim|required');
           // $this->form_validation->set_rules('question_id[]', 'Question', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $tasting_date = date("Y-m-d",strtotime($this->input->post('tasting_date')));
                if($this->input->post('start_time_hour')!='')
                {
                    /*$first_time=$this->input->post('time_one');
                    $start_time = date("H:i", strtotime($this->input->post('start_time').$first_time));*/
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
                    /*$second_time=$this->input->post('time_two');
                    $end_time = date("H:i", strtotime($this->input->post('end_time').$second_time));*/
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
                //echo "<pre>";
                //print_r($tester_array);die;
                $taster_id='';
                foreach($tester_array as $val)
                {
                    $taster_id.=$val.',';
                }
                $taster_id=rtrim($taster_id,',');
                $wine_array = $this->input->post('wine_id');
                $wine_flavours=$this->get_wine_flavour($wine_array);
                if($wine_flavours)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> You can not select different types of wine together.');
                    redirect('/App/bulk_schedule_job/edit_job/'.$job_id);
                }
                //echo "<pre>";
                //print_r($wine_array);die;
                $wine_id='';
                foreach($wine_array as $val)
                {
                    $wine_id.=$val.',';
                }
                $wine_id=rtrim($wine_id,',');
                //Get question
                if($this->input->post('tasting_date')!='')
                {
                    //echo 1;die;
                    $td=$tasting_date;
                }
                else
                {
                    //echo 2;die;
                    $td='';
                }
                            //Set input data to session
                $newdata = array(

                       'user_id'  => $this->input->post('user_id'),
                       'tasting_date'  => $td,
                       'store_id'  => $this->input->post('store_id'),
                       'admin_note'  => $this->input->post('admin_note'),
                       'taster_note'  => $this->input->post('taster_note'),
                       'wine_id'  => $this->input->post('wine_id'),
                       'taster_id'  => $this->input->post('taster_id'),

                   );

                $this->session->set_userdata('inputdata',$newdata);
                $job = array(
                    'tasting_date' => htmlspecialchars($tasting_date, ENT_QUOTES, 'utf-8'),
                    'start_time' => htmlspecialchars($start_time, ENT_QUOTES, 'utf-8'),
                    'end_time' => htmlspecialchars($end_time, ENT_QUOTES, 'utf-8'),
                    'store_id' => htmlspecialchars($this->input->post('store_id'), ENT_QUOTES, 'utf-8'),
                    'admin_note' => htmlspecialchars($this->input->post('admin_note'), ENT_QUOTES, 'utf-8'),
                    'taster_note' => htmlspecialchars($this->input->post('taster_note'), ENT_QUOTES, 'utf-8'),
                    'taster_id' => $taster_id,
                    'wine_id' => implode(',',$this->input->post('wine_id')),
                    
                    
                );
				
				//print_r($newdata);
				//print_r($job);die;
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
                //echo "<pre>";
                //print_r($job);die;
                //die;
                //echo $uploaded_pics;die;
                //if the insert has returned true then we show the flash message
                //Check job for same store and same time
                /* $check_store=$this->Bulk_schedule_job_model->check_store('bulk_schedule_job',$this->input->post('store_id'),$start_time,$end_time,$tasting_date,$job_id);
                if($check_store == 0)
                { */
                    

                    if ($this->Bulk_schedule_job_model->update_job($this->tablename, 'id', $job_id, $job)) 
                    {
                        $this->session->unset_userdata('inputdata');
                        $this->session->set_flashdata('message_type', 'success');
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> Job successfully updated.');
                    } else{
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
                    }
                    redirect('/App/bulk_schedule_job');
                /* }
                else
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Some jobs are already assigned with the store at this particular time');
                    redirect('/App/bulk_schedule_job/edit_job/'.$job_id);
                } */
            } //validation run
        }
        //get details of job
        $data['job']  = $this->Bulk_schedule_job_model->job_details($job_id);
        $sales_rep_id=$data['job']->user_id;
        //get store
       // $data['store']=$this->Bulk_schedule_job_model->get_store();
        $data['store']=$this->Bulk_schedule_job_model->get_store_for_sales_rep($sales_rep_id);
        //get wine
        $store_id=$data['job']->store_id;
        $data['wine']=$this->Bulk_schedule_job_model->get_wine($store_id);
        //get tester or agency
        $data['tester']=$this->Bulk_schedule_job_model->get_tester_or_agency($job_id);
       // echo "<pre>";
       // print_r($data['tester']);die;
        //get question answers
        //$data['question_answers']=$this->Bulk_schedule_job_model->get_question_answers();
        //Get sales representaive name
        $data['sales_rep']=$this->Bulk_schedule_job_model->get_user_name($data['job']->user_id);
        if (!is_numeric($job_id) || $job_id == 0 || empty($data['job'])) {
            redirect('/App/bulk_schedule_job');
        }        
        $data['page'] = 'Edit job';
        $data['page_title'] = SITE_NAME.' :: Bulk schedule Management &raquo; Edit schedule';
        $data['main_content'] = 'job/edit_schedule';
        $this->load->view(TEMPLATE_PATH, $data);
        
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
    
    public function temp_delete($id = null)
    {
        //echo $id;die;
        // Can't delete yourself
    	if ($id == $this->session->userdata('id')) {
    		$this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');

            redirect('/App/bulk_schedule_job');
    	}

    	

    	if (!is_numeric($id) || $id == 0) {
    		redirect('/App/bulk_schedule_job');
    	}
        
        $data=array(
            'is_deleted'=>1,

        );
        if ($this->Bulk_schedule_job_model->update_job($this->tablename,'id', $id,$data)) {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', '<strong>Well done!</strong> Schedule  successfully deleted.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
        redirect('/App/bulk_schedule_job');
    }
        public function get_wine_using_search_key()
    {
        $this->load->model('Job_model');
        $store_id=$this->input->post('store_id');
        $search_key=$this->input->post('search_key');
        $data['wine']=$this->Job_model->get_wine_using_search_key($store_id,$search_key);
        $data['wine_id']='';
        //$this->load->view('job/display_wine',$data);
		$this->load->view('job/display_wine_my_test',$data);
    }
    //Set wine id to session
    public function set_wine_id()
    {
        $this->load->model('Job_model');
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
       //print_r($wine_id_array);
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
        $this->load->model('Job_model');
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
        
        //$this->session->set_userdata('wine_ids',$wine_id_array);
        
        
    }
    public function get_tester()
    {
        $this->load->model('Job_model');
        $store_id=$this->input->post('store_id');
        $taster_id=$this->input->post('taster_id');
        $data['tester']=$this->Bulk_schedule_job_model->get_tester_or_agency_ajax($store_id);
        $data['taster_id']=$taster_id;
        $this->load->view('job/display_tester',$data);
    }
    public function get_wine()
    {
        $this->load->model('Job_model');
        $this->session->unset_userdata('wine_ids');
        $store_id=$this->input->post('store_id');
        $wine_id=$this->input->post('wine_id');
        $data['wine_id']=$wine_id;
        $data['wine']=$this->Job_model->get_wine($store_id);
        $this->load->view('job/display_wine_without_search',$data);
    }
	public function get_my_wine()
    {
        $this->load->model('Job_model');
        $store_id=$this->input->post('store_id');
        $wine_id=$this->input->post('wine_id');
        $data['wine_id']=$wine_id;
        $data['wine']=$this->Job_model->get_wine($store_id);
        $this->load->view('job/display_wine_my_test',$data);
    }
	public function get_my_tester_wine(){
		$this->load->model('Job_model');
        $store_id=$this->input->post('store_id');
        $data['tester']=$this->Bulk_schedule_job_model->get_tester_or_agency_ajax($store_id);
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
    public function get_wine_flavour($wine_array)
    {
        $wine_id=$wine_array;
        $royal=array();
        $mix=array();
        foreach($wine_id as $id)
        {
            $this->db->select('flavour');
            $this->db->from('wine');
            $this->db->where('id',$id);
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
    public function search_submit() {
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {    
           $sampling_date = $this->clean_value($this->input->post('sampling_date'));
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
            $store = $this->input->post('search_by_store');
            $entry_date = $this->clean_value($this->input->post('entry_date'));
            $search_text = $this->clean_value($this->input->post('search_text'));
            $status=$this->input->post('status');
            $url = "App/Bulk_schedule_job/index/";
            if ($status != '') {
                $url .= "status/". urlencode($status)."/";
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
                $url .= "search_text/". urlencode($search_text)."/";
            }
            redirect($url);
        }

    }
    public function multiple_delete() {
        
        //print "<pre> ".$this->tablename; print_r($_POST);die;
        $count = 0;
        $data=array( 'is_deleted'=>1 );
        $items = $this->input->post('item_id');               
        foreach ($items as $id) {
            $this->Bulk_schedule_job_model->update_job($this->tablename,'id', $id,$data);
                ++$count;     
        }

        $msg = 'deleted.';
        $this->session->set_flashdata('message_type', 'success');
        $this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' Job(s) successfully '.$msg);
        echo $count;
    
    }
}