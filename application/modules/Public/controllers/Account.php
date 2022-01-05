<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends Front_Controller {

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
		
		$this->load->model('../../App/models/Customer_model');

		if (!$this->session->userdata('is_customer_logged_in')) {
			redirect('login');
		}else{
			$cData = $this->Customer_model->get_customer_islogin($this->session->userdata('id'));
			if($cData->is_login != '1'){
				redirect('Auth/logout');
			}
		}
		$this->load->model('public_model');
    }

	public function index() {

		redirect('account/dashboard');
	}

	function dashboard() {

        $this->load->library('twentythree');
		$this->load->model('../../App/models/App_model');

        $condition = array('include_unpublished_p' => '1', 'album_id' => '28365699');
        $condition['oder'] = "desc";
		$condition['size'] = '20';
        $videoData = $this->twentythree->twentythreeapi('/api/photo/list', $condition, 'GET');

        // Get Motivationa and QA videos
		$this->load->model('../../App/models/Motivational_qa_videos_model', 'mqa');
        $mqa = $this->mqa->get_all_mqaVideos();
        $video_id = array_column($mqa, 'video_id');

        $this->data['videoData'] = array();
        $this->data['total_count'] = 0;
        if($videoData->status == 'ok'){
        	$this->data['total_count'] = $videoData->total_count;
            $videos = $videoData->photos;
            $i = 0;
            foreach ($videos as $key => $val) {
            	if($i < 6){
	                if(($val->published_p == 1) && !in_array($val->photo_id, $video_id)){
	                    $this->data['videoData'][] = $val;
	                    $i++;
	                }
	            }
            }
        }

        $programData = $this->public_model->getprogramType();

        // echo "<pre>";print_r($programData);exit;

        $this->data['programData'] = $programData;

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'dashboard';

		$this->data['main_content'] = 'account/dashboard';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	function myaccount() {

		
		$id = $this->session->userdata('id');

		if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
			//print "<pre>"; print_r($_POST); die;
     		//form validation
     		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
	    	$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required');
			$this->form_validation->set_rules('height', 'Height', 'trim|required|numeric');
			$this->form_validation->set_rules('height_inc', 'Height', 'trim|required|numeric');
			$this->form_validation->set_rules('weight', 'Weight', 'trim|required|numeric');
			$this->form_validation->set_rules('city', 'City', 'trim|required');
			$this->form_validation->set_rules('state', 'State', 'trim|required');
			$this->form_validation->set_rules('country', 'Country', 'trim|required');

     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
     		//if the form has passed through the validation

     		if ($this->form_validation->run())
     		{
     			$customer = array(
    				'first_name' => htmlspecialchars($this->input->post('first_name'), ENT_QUOTES, 'utf-8'),
					'last_name' => htmlspecialchars($this->input->post('last_name'), ENT_QUOTES, 'utf-8'),
					'dob' => $this->format_date($this->input->post('dob')),
					'gender' => htmlspecialchars($this->input->post('gender'), ENT_QUOTES, 'utf-8'),
					'height' => htmlspecialchars($this->input->post('height'), ENT_QUOTES, 'utf-8'),
					'height_inc' => htmlspecialchars($this->input->post('height_inc'), ENT_QUOTES, 'utf-8'),
					'weight' => htmlspecialchars($this->input->post('weight'), ENT_QUOTES, 'utf-8'),
					'city' => htmlspecialchars($this->input->post('city'), ENT_QUOTES, 'utf-8'),
					'state' => htmlspecialchars($this->input->post('state'), ENT_QUOTES, 'utf-8'),
					'country' => htmlspecialchars($this->input->post('country'), ENT_QUOTES, 'utf-8'),
     				'updated_on' => date('Y-m-d H:i:s')
				);
				
				if (!empty($_FILES['profile_pic']['name'])) {
					
					$config['upload_path'] = DIR_PROFILE_PICTURE;
					$config['filename'] = 'pic';
					$config['mime'] = 'image';
					
					$this->load->library('utility', $config);
					if ($file_name = $this->utility->do_uploads($_FILES['profile_pic'])) {
						$customer['profile_pic'] = $file_name;
					} else {
						$this->session->set_flashdata('message_type', 'danger');
						$this->session->set_flashdata('message', $this->utility->display_errors());
						redirect('/account/myaccount');
					}
				}
				 

     			//if the insert has returned true then we show the flash message
     			if ($this->Customer_model->update('customers', 'id', $id, $customer)) {
					// Update Session data
					$data = array(
						'name' => $customer['first_name']." ".$customer['last_name'],
					);

					if (isset($customer['profile_pic']) && $customer['profile_pic'] <> "") {
						$data['picture'] = $customer['profile_pic'];
					}
					$this->session->set_userdata($data);

     				$this->session->set_flashdata('message_type', 'success');
     				$this->session->set_flashdata('message', '<strong>Well done!</strong> Account successfully updated.');
     			} else{
     				$this->session->set_flashdata('message_type', 'danger');
     				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
     			}
     			redirect('/account/myaccount');
     		} //validation run
		 }
		 
		// Get the profile data
		$this->data['profile'] = $this->Customer_model->get_customer_details($id);

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'myaccount';

		//print "<pre>"; print_r($this->data); die;
		$this->data['main_content'] = 'account/myaccount';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}


	function payments() {

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'payments';

		
		$customer_id = $this->session->userdata('id');

		$this->data['payments'] = $this->Customer_model->get_customer_subsciptions($customer_id);

		$this->data['main_content'] = 'account/payments';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	function notifications() {

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'notifications';
		
		
		$customer_id = $this->session->userdata('id');

		if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
			$notification['email'] = htmlspecialchars($this->input->post('email'), ENT_QUOTES, 'utf-8');				 
			$notification['push'] = htmlspecialchars($this->input->post('push'), ENT_QUOTES, 'utf-8');
			$notification['customer_id'] = $customer_id;

     		//if the insert has returned true then we show the flash message
     		if ($this->Customer_model->update_notification($notification)) {
     			$this->session->set_flashdata('message_type', 'success');
     			$this->session->set_flashdata('message', '<strong>Well done!</strong> Notification successfully updated.');
     		} else{
     			$this->session->set_flashdata('message_type', 'danger');
     			$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
     		}
     		redirect('/account/notifications');
     		
		}

		
		$this->data['notification'] = $this->Customer_model->get_customer_notifications($customer_id);

		$this->data['main_content'] = 'account/notifications';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	function update_password() {

		
		$id = $this->session->userdata('id');

		if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
     		//form validation
     		$this->form_validation->set_rules('password', 'Password', 'matches[conf_password]');
	    	$this->form_validation->set_rules('conf_password', 'Confirm Password', 'trim|required');

     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
     		//if the form has passed through the validation

     		if ($this->form_validation->run())
     		{
				$customer['password'] = md5(htmlspecialchars($this->input->post('password'), ENT_QUOTES, 'utf-8'));				 

     			//if the insert has returned true then we show the flash message
     			if ($this->Customer_model->update('customers', 'id', $id, $customer)) {
     				$this->session->set_flashdata('message_type', 'success');
     				$this->session->set_flashdata('message', '<strong>Well done!</strong> Password successfully updated.');
     			} else{
     				$this->session->set_flashdata('message_type', 'danger');
     				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
     			}
     			redirect('/account/update_password');
     		} //validation run
		}

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'update_password';

		$this->data['main_content'] = 'account/update_pass';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	function delete() {

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'delete';

		$this->data['main_content'] = 'account/delete';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	function orders() {

		$this->load->model('../../App/models/Product_model');

		$filter = array('customer_id' => $this->session->userdata('id'));
		// Get the total rows without limit
		$this->data['orders'] = $this->Product_model->get_order_list($filter);
		
		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'orders';

		$this->data['main_content'] = 'account/orders';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}


	function order_details($id = null) {

		if ($id == null) {
			redirect('/account/orders');
		}

		$this->load->model('../../App/models/Product_model');

		$filter = array('order_id' => $id, 'customer_id' => $this->session->userdata('id'));

		$this->data['order'] = $this->Product_model->get_order_details($filter);
		if (empty($this->data['order'])) {
			redirect('/account/orders');
		}

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'orders';

		$this->data['main_content'] = 'account/order_details';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	/**
	 * 
	 * 
	 **/
	private function format_date($date) {
		if ($date == "")
			return "";
 
		$newdate = date_create($date);
		return date_format($newdate,"Y-m-d");
	}


	public function library(){

		$search = $this->input->get('s');
		$search2 = $this->input->get('cs');
		
        $this->load->library('twentythree');
        $this->load->library('pagination');

        $condition = array(
        	'include_unpublished_p' => '1',
        	'album_id' => '28365699',
        );
		$condition2 = array(
        	'include_unpublished_p' => '1',
        	'album_id' => '28365699',
        );
        $condition['size'] = '500';
        $condition2['size'] = '500';
        
        if(isset($search)){
        	$condition['search'] = $search;
			$condition['custom_variable_mode'] = 'title';
        }

        //print_r($condition);

		if($this->input->get('optbtn')==2){
			$condition['search'] = $search2;
			if($search2==1||$search2==2||$search2==3){
				$condition['custom_variable_mode'] = 'difficulty';
			}else{
				$condition['custom_variable_mode'] = 'tags';	
			}
		}
        $countVideoData = $this->twentythree->twentythreeapi('/api/photo/list', $condition, 'GET');
		
		$allVideoData = $this->twentythree->twentythreeapi('/api/photo/list', $condition2,'GET');
	   // echo "<pre>";
    //    print_r($countVideoData);die;
		$tagarray = array();
		$p=0;
		foreach($allVideoData->photos as $values){
			$tagarray[$p] = $values->tags;
			$p++;
		}
		$z=0;
		for($y=0;$y<count($tagarray);$y++){
			for($h=0;$h<count($tagarray[$y]);$h++){
				
				$tags[$z]=$tagarray[$y][$h];
				$z++;
				
			}
		}
		$uniquetags = array_unique($tags);
		$newtags = array_values(array_filter($uniquetags));

		if(!empty($newtags)){
        	$this->data['tag_list'] = $newtags;
        }
		
        // Set array for pagination
		// $config = array();
		// $config["base_url"] = base_url() . "account/library";

  //       if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
  //       if (count($_GET) > 0) $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
        
        $paginationcount = 0;
//echo "<pre>";
//print_r($countVideoData);
//die();
        for($x=0;$x<count($countVideoData->photos);$x++) {

        	if ($countVideoData->photos[$x]->published_p == 1)
        	{
        		$paginationcount++;
        	}

        }

//echo $paginationcount;
//die();
		/*$total_row = $paginationcount;
		$config["total_rows"] = $total_row;
		$config["per_page"] = 6;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] = $total_row;
		$config['cur_tag_open'] = '&nbsp;<a class="current">';
		$config['cur_tag_close'] = '</a>';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['uri_segment'] = 3;

		//$this->pagination->initialize($config);
		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
		} else{
			$page = 1;
		}
*/
       	//$condition['p'] = $page;
       	//$condition['size'] = $config["per_page"];

		// echo "<pre>";print_r(http_build_query($_GET, '', "&"));exit;

        // $this->load->library('pagination');
        $inputGet = ($this->input->get('cs') && $this->input->get('optbtn')) ? '?cs='.$this->input->get('cs').'&optbtn='.$this->input->get('optbtn') : '';
        $srchQuery = $this->input->get('s') ? '?s='.$this->input->get('s') : '';

        $config = array();
        $config["base_url"] = base_url() . "account/library".$inputGet.$srchQuery;
        $config["total_rows"] = ceil($countVideoData->total_count/20);
        $config["per_page"] = 1;
        $config["page_query_string"] = true;
        $config['query_string_segment'] = 'page';
        $config["use_page_numbers"] = TRUE;


        // if (count($_GET) > 0) $config['suffix'] = '&' . http_build_query($_GET, '', "&");
        // if (count($_GET) > 0) $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

        $this->pagination->initialize($config);
        $this->data["page_link"] = $this->pagination->create_links();

        $condition['size'] ='20';
        $condition['p'] = $this->input->get('page') ? $this->input->get('page') : 0;
        $videoData = $this->twentythree->twentythreeapi('/api/photo/list', $condition, 'GET');
        
        // echo "<pre>";print_r($data["page_link"]);print_r($videoData);exit;

		$this->load->model('../../App/models/Motivational_qa_videos_model', 'mqa');

        // Get Motivationa and QA videos
        $mqa = $this->mqa->get_all_mqaVideos();
        $video_id = array_column($mqa, 'video_id');

        // if(!empty($videoData)){
        // 	$this->data['videoData'] = $videoData->photos;
        // }

        $this->data['videoData'] = array();
        if($videoData->status == 'ok'){
            $videos = $videoData->photos;
            foreach ($videos as $key => $val) {
                if(!in_array($val->photo_id, $video_id)){
                    $this->data['videoData'][] = $val; 
                }
            }
        }

		//$str_links = $this->pagination->create_links();
		//$this->data["links"] = explode('&nbsp;',$str_links );

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'Library';

		// echo "<pre>";print_r($this->data);exit;

		$this->data['main_content'] = 'account/library';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	public function library_details($video_id=''){

		if($video_id==''){
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
			redirect(base_url('account/library'));
		}

		$this->load->model('../../App/models/App_model');

        $this->load->library('twentythree');

        $videoData = $this->twentythree->twentythreeapi('/api/photo/list', array('photo_id'=>$video_id, 'include_unpublished_p' => '1'), 'GET');
        if(!empty($videoData)){
        	$this->data['videoData'] = $videoData->photos[0];

			$svData = $this->App_model->getDetailsById('save_video', array('customer_id' => $this->session->userdata('id'), 'video_id' => $this->data['videoData']->photo_id));
			$this->data['svData'] = !empty($svData)?$svData:array();
        }
        
		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'Library Details';

		$this->data['main_content'] = 'account/library_details';

		// echo "<pre>";print_r($this->data['svData']);exit;
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	public function program_plan1($program_type_id){

		$this->load->model('../../App/models/App_model');

        $this->load->library('twentythree');        
		
    	if($program_type_id == ''){
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
			redirect(base_url('account/program'));
    	}

    	$srch = array();
    	$srch['program_type_id'] = $program_type_id;

        $records = $this->App_model->getProgramPlan($srch);
	    $infoVideo = array();
	    $dayvideo = array();

        if(!empty($records)){
	        foreach ($records as $rkey => $record) {
	        	$vDatas = $this->App_model->getProgramVideoById($record->id);
	        	$dayData = $this->App_model->getProgramDayById($record->id);

	            if(!empty($vDatas)){
	                foreach ($vDatas as $vdkey => $vData) {
	                    
	                    $videoData = $this->twentythree->twentythreeapi('/api/photo/list', array('photo_id'=> $vData->video_id, 'include_unpublished_p' => '1'), 'GET');
	                    $datas = $videoData->photos;

	                    foreach ($datas as $key => $val) {
	                        if($val->published_p=='1'){
	                            $selectData = array(
	                                'video_id' => $val->photo_id,
	                                'token' => $val->token,
	                                'tree_id' => $val->tree_id,
	                                'title' => $val->title,
	                                'tags' => $val->tags,
	                                'body_group' => $val->body_group,
	                                'muscles_targeted' => $val->muscles_targeted,
	                                'reps' => $val->reps,
	                                'sets' => $val->sets,
	                                'difficulty' => $val->difficulty,
	                                'description' => $val->content_text,
	                                'duration' => gmdate("H:i:s", $val->video_length),
	                                'video_frame' => 'https://r6frpp9k.videomarketingplatform.co/'.$val->tree_id.'/'.$val->photo_id.'/'.$val->token.'/video_frame',
	                                'video_url' => 'https://r6frpp9k.videomarketingplatform.co/v.ihtml/player.html?token='.$val->token.'&source=embed&photo_id='.$val->photo_id,
	                                'published_p' => $val->published_p,
	                                'created_date' => $val->creation_date_ansi,
	                            );
	                            $infoVideo[] = $selectData;
	                        }
	                    }
	                }
	            }

	            // Get day wise videos            
	            if(!empty($dayData)){
	                // $dayArray = array();
	                foreach ($dayData as $dwkey => $dwData) {
	                    $dayVideo = $this->App_model->get_program_plan_day_video($dwData->id);
	                    
	                    foreach ($dayVideo as $key3 => $val3) {
	                        
	                        $getDayData = $this->twentythree->twentythreeapi('/api/photo/list', array('photo_id'=> $val3['video'], 'include_unpublished_p' => '1'), 'GET');
	                        
	                        if($getDayData->status == 'ok'){

	                            $viData = $getDayData->photos;
	                            foreach ($viData as $vdval) {
	                                $addarr = array(
	                                    'video_id' => $vdval->photo_id,
	                                    'tree_id' => $vdval->tree_id,
	                                    'token' => $vdval->token,
	                                    'title' => $vdval->title,
	                                    'tags' => $vdval->tags,
	                                    'body_group' => $vdval->body_group,
	                                    'muscles_targeted' => $vdval->muscles_targeted,
	                                    'reps' => $vdval->reps,
	                                    'sets' => $vdval->sets,
	                                    'difficulty' => $vdval->difficulty,
	                                    'description' => $vdval->content_text,
	                                    'duration' => gmdate("H:i:s", $vdval->video_length),
	                                    'video_frame' => 'https://r6frpp9k.videomarketingplatform.co/'.$vdval->tree_id.'/'.$vdval->photo_id.'/'.$vdval->token.'/video_frame',
	                                    'video_url' => 'https://r6frpp9k.videomarketingplatform.co/v.ihtml/player.html?token='.$vdval->token.'&source=embed&photo_id='.$vdval->photo_id,
	                                    'published_p' => $vdval->published_p,
	                                    'created_date' => $vdval->creation_date_ansi,
	                                );
	                                $dayvideo[] = $addarr;
	                            }
	                        }
	                    }
	                }
	            }

	        }
		}

		$mergeVideo = array_merge($dayvideo, $infoVideo);
		if(!empty($mergeVideo)){
			$records[0]->video_info = $mergeVideo;
		}
		// echo '<pre>';print_r($records);exit;
		$this->data['records'] = $records;

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'Program Plan';

		$this->data['main_content'] = 'account/program_plan';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}
	
	public function program_plan($program_type_id){

		$this->load->model('../../App/models/App_model');

        $this->load->library('twentythree');        
		
    	if($program_type_id == ''){
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
			redirect(base_url('account/program'));
    	}

    	$srch = array();
    	$srch['program_type_id'] = $program_type_id;

        $records = $this->App_model->getProgramPlan($srch);
        if(!empty($records)){
	        foreach ($records as $rkey => $record) {
	        	$vDatas = $this->App_model->getProgramVideoById($record->id);
	        	$dayData = $this->App_model->getProgramDayById($record->id);

	            if(!empty($vDatas)){
	                $arr1 = array();
	                foreach ($vDatas as $vdkey => $vData) {
	                    
	                    $videoData = $this->twentythree->twentythreeapi('/api/photo/list', array('photo_id'=> $vData->video_id, 'include_unpublished_p' => '1'), 'GET');
	                    
						
						if(!empty($videoData->photos))
						{
							$datas = $videoData->photos;
						}
                        
						if(!empty($datas)) 
						{							
	                    foreach ($datas as $key => $val) {
	                        if($val->published_p=='1'){
	                            $selectData = array(
	                                'video_id' => $val->photo_id,
	                                'token' => $val->token,
	                                'tree_id' => $val->tree_id,
	                                'title' => $val->title,
	                                'tags' => $val->tags,
	                                'body_group' => $val->body_group,
	                                'muscles_targeted' => $val->muscles_targeted,
	                                'reps' => $val->reps,
	                                'sets' => $val->sets,
	                                'difficulty' => $val->difficulty,
	                                'description' => $val->content_text,
	                                'duration' => gmdate("H:i:s", $val->video_length),
	                                'video_frame' => 'https://r6frpp9k.videomarketingplatform.co/'.$val->tree_id.'/'.$val->photo_id.'/'.$val->token.'/video_frame',
	                                'video_url' => 'https://r6frpp9k.videomarketingplatform.co/v.ihtml/player.html?token='.$val->token.'&source=embed&photo_id='.$val->photo_id,
	                                'published_p' => $val->published_p,
	                                'created_date' => $val->creation_date_ansi,
	                            );
	                            $arr1[] = $selectData;
	                        }
	                    }
						
						}
	                    $records[$rkey]->video_info = $arr1;
	                }
	            } else{
	                $records[$rkey]->video_info = array();
	            }
	        }
		}

		// echo '<pre>';print_r($records);exit;
		$this->data['records'] = $records;

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'Program Plan';

		$this->data['main_content'] = 'account/program_plan';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	public function program_plan_details($program_plan_id){

		$this->load->model('../../App/models/App_model');

        $this->load->library('twentythree');        
		
    	if($program_plan_id == ''){
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
			redirect(base_url('account/program'));
    	}

        $records = $this->App_model->getprogramPlanById($program_plan_id);

        if(!empty($records)){
        	$vDatas = $this->App_model->getProgramVideoById($records['id']);
        	$dayData = $this->App_model->getProgramDayById($records['id']);

            if(!empty($vDatas)){
                $arr1 = array();
                foreach ($vDatas as $vdkey => $vData) {
                    
                    $videoData = $this->twentythree->twentythreeapi('/api/photo/list', array('photo_id'=> $vData->video_id, 'include_unpublished_p' => '1'), 'GET');

					$datas = ($videoData->status == 'ok') ? $videoData->photos : array();
					
					if(!empty($datas))
					{
						foreach ($datas as $key => $val) {
							if($val->published_p=='1'){
								$selectData = array(
									'video_id' => $val->photo_id,
									'token' => $val->token,
									'tree_id' => $val->tree_id,
									'title' => $val->title,
									'tags' => $val->tags,
									'body_group' => $val->body_group,
									'muscles_targeted' => $val->muscles_targeted,
									'reps' => $val->reps,
									'sets' => $val->sets,
									'difficulty' => $val->difficulty,
									'description' => $val->content_text,
									'duration' => gmdate("H:i:s", $val->video_length),
									'video_frame' => 'https://r6frpp9k.videomarketingplatform.co/'.$val->tree_id.'/'.$val->photo_id.'/'.$val->token.'/video_frame',
									'video_url' => 'https://r6frpp9k.videomarketingplatform.co/v.ihtml/player.html?token='.$val->token.'&source=embed&photo_id='.$val->photo_id,
									'published_p' => $val->published_p,
									'created_date' => $val->creation_date_ansi,
									'program_sets' => $vData->program_sets,
									'program_reps' => $vData->program_reps,
									'program_desc' => $vData->description,
								);
								$arr1[] = $selectData;
							}
						}
					}
                    $records['video_info'] = $arr1;
                }
            } else{
                $records['video_info'] = array();
            }

            // Get day wise videos            
            if(!empty($dayData)){
                foreach ($dayData as $dwkey => $dwData) {
                    $dayVideo = $this->App_model->get_program_plan_day_video($dwData->id);
                    
                    $dayArray = array();
                    foreach ($dayVideo as $key3 => $val3) {                        
                        $getDayData = $this->twentythree->twentythreeapi('/api/photo/list', array('photo_id'=> $val3['video'], 'include_unpublished_p' => '1'), 'GET');
                        
                        if($getDayData->status == 'ok'){
                            $viData = $getDayData->photos;
                            foreach ($viData as $vdval) {
                                $addarr = array(
                                    'video_id' => $vdval->photo_id,
                                    'tree_id' => $vdval->tree_id,
                                    'token' => $vdval->token,
                                    'title' => $vdval->title,
                                    'tags' => $vdval->tags,
                                    'body_group' => $vdval->body_group,
                                    'muscles_targeted' => $vdval->muscles_targeted,
                                    'reps' => $vdval->reps,
                                    'sets' => $vdval->sets,
                                    'difficulty' => $vdval->difficulty,
                                    'description' => $vdval->content_text,
                                    'duration' => gmdate("H:i:s", $vdval->video_length),
                                    'video_frame' => 'https://r6frpp9k.videomarketingplatform.co/'.$vdval->tree_id.'/'.$vdval->photo_id.'/'.$vdval->token.'/video_frame',
                                    'video_url' => 'https://r6frpp9k.videomarketingplatform.co/v.ihtml/player.html?token='.$vdval->token.'&source=embed&photo_id='.$vdval->photo_id,
                                    'published_p' => $vdval->published_p,
                                    'created_date' => $vdval->creation_date_ansi,
                                );
                                $dayArray[] = $addarr;                                
                            }
                        }
                    }
                    $dayData[$dwkey]->video_info = $dayArray;
                }
            } else{
                //$records['video_info'] = array();
            }
            $records['day_info'] = $dayData;
		}

		// echo '<pre>';print_r($records);exit;
		$this->data['records'] = $records;

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'Program Plan';

		$this->data['main_content'] = 'account/program_plan_details';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}
	
	public function program(){

		$this->load->model('../../App/models/App_model');

        $this->load->library('twentythree');        
		
    	$srch = array();
    	$program_type_id = $this->input->get('program_type_id');
    	if(isset($program_type_id)){
    		$srch['program_type_id'] = $program_type_id;
    	}

        $records = $this->App_model->getprogramType();

		$this->data['records'] = $records;

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'Program';

		$this->data['main_content'] = 'account/program';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

    public function saved_videos(){

    	$customer_id = $this->session->userdata('id');

		$this->load->model('../../App/models/App_model');

		$records = $this->App_model->getDetails('save_video', array('customer_id' => $customer_id));
		
	    $this->load->library('twentythree');
	    $arr = array();
		foreach ($records as $key => $record) {
	        $condition = array(
	        	'include_unpublished_p' => '1',
	        	'photo_id' => $record->video_id,
	        );
	        $video_data = $this->twentythree->twentythreeapi('/api/photo/list', $condition, 'GET');

	        if($video_data->status == 'ok'){
	        	$arr[] = $video_data->photos[0];
	        }
	    }

		$this->data['videoData'] = $arr;

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'Saved Videos';

		$this->data['main_content'] = 'account/saved_videos';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
    }

    public function download($id) {

		$this->load->model('../../App/models/App_model');

        $record = $this->App_model->getProgramPlanById($id);

        $this->load->helper('download');
        if(!empty($record)){
            $data  = file_get_contents('assets/program_pdf/'.$record['file_name']);
        }
        $name   = $record['file_name'];
        force_download($name, $data);        
    }

    public function view($id) {

    	$is_login = $this->session->userdata('is_customer_logged_in');
		if(!$is_login){
    		redirect('/');
    	}

		$this->load->model('../../App/models/App_model');

        $record = $this->App_model->getProgramPlanById($id);
        
        $fullpath = base_url('assets/program_pdf/'.$record['file_name']);

		  header('Content-type: application/pdf');
		  header('Content-Disposition: inline; filename="' . $record['file_name'] . '"');
		  header('Content-Transfer-Encoding: binary');
		  header('Accept-Ranges: bytes');
		  echo file_get_contents($fullpath);
		exit;      
    }

    public function save_video(){

    	$customer_id = $this->input->post('user_id');
    	$video_id = $this->input->post('video_id');

		$this->load->model('../../App/models/App_model');

		$records = $this->App_model->getDetailsById('save_video', array('customer_id' => $customer_id, 'video_id' => $video_id));
		if(!empty($records)){

        	echo json_encode(array('msg' => 'Already saved this video'));exit;
		}

		$data_to_store = array(
			'customer_id' => $customer_id,
			'video_id' => $video_id,
			'status' => '1',
			'created_on' => date('Y-m-d H:i:s'),
		);

    	// Store the data
    	if ($this->App_model->insert('save_video', $data_to_store)){
    		echo json_encode(array('msg' => 'Video successfully saved'));exit;
    	} else{
    		echo json_encode(array('msg' => 'Unable to save video'));exit;
    	}
    }

    public function unsave_video(){

    	$customer_id = $this->input->post('user_id');
    	$video_id = $this->input->post('video_id');

		$this->load->model('../../App/models/App_model');

		$records = $this->App_model->getDetailsById('save_video', array('customer_id' => $customer_id, 'video_id' => $video_id));

		if(!empty($records)){
			if($this->App_model->delete('save_video', $records->id)){
        		echo json_encode(array('msg' => 'success'));exit;
        	}else{
        		echo json_encode(array('msg' => 'unsuccess'));exit;
        	}
		}
    }


    public function check_saveUnsave(){

    	$customer_id = $this->input->post('user_id');
    	$video_id = $this->input->post('video_id');

		$this->load->model('../../App/models/App_model');

		$records = $this->App_model->getDetailsById('save_video', array('customer_id' => $customer_id, 'video_id' => $video_id));

		// echo "<pre>";print_r($records);exit;
		if(!empty($records)){
        	echo json_encode(array('msg' => 'success'));exit;
    	}else{
    		echo json_encode(array('msg' => 'unsuccess'));exit;
    	}

    }

	function get_notifications() {

		$filter = array(
			'customer_id' => $this->session->userdata('id'),
			'limit' => 5
		);

		$this->load->model('../../../modules/App/models/Notify_model');
		$notifications = $this->Notify_model->get_notifications_list($filter);

		$filter['status'] = 'unread';
		$unread = $this->Notify_model->get_notifications_list($filter, null, null, true);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('notifications' => $notifications, 'unread' => $unread)));
	}


	function get_notification_details($id) {

		$this->load->model('../../../modules/App/models/Notify_model');
		$notification = $this->Notify_model->get_notification_details($id);

		$videos = $this->Notify_model->get_notification_videos($id);

		// Update the notification status to read
		$this->Notify_model->update_status($id, $this->session->userdata('id'));

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('notification'=>$notification,'videos'=>$videos)));
	}
}
