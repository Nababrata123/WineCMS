<?php defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';
class Job extends REST_Controller {
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
    public function __construct() {
        parent::__construct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['do_login_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['send_otp_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->load->model('Job_model');
        $this->load->library('push_notifications');
        $this->load->model('Notifications_model');
    }
    public function create_job_post() {
        $user_id = $this->post('user_id');
        $tasting_date = date("Y-m-d",strtotime($this->post('job_date')));
        if($this->post('start_time')!='')
        {
            $start_time = date("H:i", strtotime($this->post('start_time')));
        }
        else
        {
            $start_time='';
        }
        if($this->post('end_time')!='')
        {
            $end_time = date("H:i", strtotime($this->post('end_time')));
        }
        else
        {
            $end_time='';
        }
        $store_id = $this->post('store_id');
        //Check job for same store and same time
        $check_store=$this->Job_model->check_store('job',$store_id,$start_time,$end_time,$tasting_date,0);

        $admin_note = $this->post('admin_note');
        $taster_id = $this->post('taster_id');
        //Check the tester is available or not for the job
        if($taster_id!='')
        {
            $count_job=$this->Job_model->check_tester_availablity('job',$taster_id,$tasting_date,$start_time,$end_time);
        }
        else
        {
            $count_job=0;
        }
        //Check tester for the particular job
        if($taster_id!='')
        {
            $check_tester=$this->Job_model->check_tester_zonewise($store_id,$taster_id);
        }
        else
        {
            $check_tester=1;
        }
        $taster_note = $this->post('taster_note');
        $wine_details_array=$this->post('wine_details');
        $result = array_map(function($v){
            return [$v[0] => $v[1]];
        }, $wine_details_array);
        //Make wine id array to string
        $wine_id='';
        for($i=0;$i<count($result);$i++)
        {
            $wine_id.=$result[$i]['wine_id'].",";
        }
        $wine_id=rtrim($wine_id,",");  
        
        //Based on taster set job status
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
            // If a user exists in the data store e.g. database
            if (!empty($job))
            {
                /*if($check_store == 0)
                {*/


                    if($check_tester==1)
                    {
                        if($count_job==0)
                        {
                            $insert=$this->Job_model->create_job('job',$job);
                            if($taster_id!='')
                            {
                                $this->load->library('push_notifications');
                                $this->load->model('Notifications_model');
                        
                                $notifications = $this->Notifications_model->send_notifications_for_republish_job($taster_id,$insert);

                                $this->load->library('mail_template');
                        
                                /*
                                //get tester id and name
                                 $result['tester_info']=$this->Job_model->get_tester_details($taster_id);
                                //echo "<pre>";
                               // print_r($result['tester_info']);die;
                                $name=$result['tester_info']->first_name;
                                $email=$result['tester_info']->email;
                                //echo $email;die;
                                $this->mail_template->assigned_job_email($name,$email); */

                                $samplingDate = date("F d, Y", strtotime($tasting_date));
                                $startTime = date("h:i a",strtotime($start_time));
                                $finish_time = date("h:i a",strtotime($end_time));

                                $wine_id_array = explode(',', $wine_id);
                                $wineNames=$this->Job_model->get_assign_mail_wine_names($wine_id_array);

                                $store = $this->Job_model->get_store_name($store_id);
                                $store_name = $store->name;
                                $store_address = $store->adress;

                                $salesrep = $this->Job_model->get_mail_selsrep_name($insert);
                                $salesrep_name = $salesrep->sales_rep_name;

                                //get tester id and name
                                $result['tester_info']=$this->Job_model->get_tester_details($taster_id);
                                $name=$result['tester_info']->first_name;
                                $email=$result['tester_info']->email;
        
                                //echo $email;die;
                                $this->mail_template->assigned_job_email($name,$email,$samplingDate,$startTime,$finish_time,$wineNames,$store_name,$store_address,$salesrep_name);
                            }

                            $this->set_response([
                                'success' => TRUE,
                            ], REST_Controller::HTTP_OK);
                         // OK (200) being the HTTP response code
                        }
                        else
                        {
                            $this->set_response([
                            'success' => FALSE,
                            'error' => 'The tester has already assigned with other job'
                        ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                        }
                    }
                    else
                    {
                            $this->set_response([
                            'success' => FALSE,
                            'error' => 'The tester you want to assign have not belong at the zone'
                        ], REST_Controller::HTTP_OK);
                    }
                /*}
                else
                {
                    $this->set_response([
                            'success' => FALSE,
                            'error' => 'Some jobs are already assigned with the store at this particular time'
                        ], REST_Controller::HTTP_OK);
                }*/
            }
            else
            {
                $this->set_response([
                    'success' => FALSE,
                    'error' => 'Job not created'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        return false;
    }
    public function update_job_post() {
        $user_id = $this->post('user_id');
        $job_id=$this->post('job_id');
        $tasting_date = date("Y-m-d",strtotime($this->post('job_date')));
        if($this->post('start_time')!='')
        {
            $start_time = date("H:i", strtotime($this->post('start_time')));
        }
        else
        {
            $start_time='';
        }
        if($this->post('end_time')!='')
        {
            $end_time = date("H:i", strtotime($this->post('end_time')));
        }
        else
        {
            $end_time='';
        }
        $store_id = $this->post('store_id');
        $check_store=$this->Job_model->check_store('job',$store_id,$start_time,$end_time,$tasting_date,$job_id);
        $admin_note = $this->post('admin_note');
        $taster_id = $this->post('taster_id');
        //get pre assigned tester id
        $this->db->select('taster_id');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result_job=$this->db->get()->row();
        if(isset($result_job->taster_id)){
            $pre_tester_id=$result_job->taster_id;
        }else{
            $pre_tester_id='';
        }
        $pre_tester_id_array=explode(",",$pre_tester_id);
        if(in_array($taster_id,$pre_tester_id_array))
        {
            $count_job=0;
        }
        else
        {
            $count_job=$this->Job_model->check_tester_availablity('job',$taster_id,$tasting_date,$start_time,$end_time);
        }
        //Check tester for the particular job
        //Check tester for the particular job
        if($taster_id!='')
        {
            $check_tester=$this->Job_model->check_tester_zonewise($store_id,$taster_id);
        }
        else
        {
            $check_tester=1;
        }    
        $taster_note = $this->post('taster_note');
        $wine_details_array=$this->post('wine_details');
        $result = array_map(function($v){
            return [$v[0] => $v[1]];
        }, $wine_details_array);
        //Make wine id array to string
        $wine_id='';
        for($i=0;$i<count($result);$i++)
        {
            $wine_id.=$result[$i]['wine_id'].",";
        }
        $wine_id=rtrim($wine_id,",");    
        $job['user_id']=$user_id;
        $job['tasting_date']=$tasting_date;
        $job['start_time']=$start_time;
        $job['end_time']=$end_time;
        $job['store_id']=$store_id;
        $job['admin_note']=$admin_note;
        $job['taster_id']=$taster_id;
        $job['taster_note']=$taster_note;
        $job['wine_id']=$wine_id;
        //Check job status and update
        $job_status=$this->Job_model->check_job_status($job_id);
        $current_job_state=$this->Job_model->job_info($job_id);
        $current_job_state=$current_job_state->job_state;
        if($job_status=='rejected')
        {
            $job['status']='assigned';
			$job['job_status']=2;
			
        }
        else
        {
            if($job_status=='pending' || $job_status=='assigned' || $job_status=='approved' && $job['taster_id']!='' && $current_job_state<1){
                $job['status']='assigned';
                $job['job_status']=2;
                $job['agency_taster_id'] = 0;
                $this->Job_model->delete_accept_reject_data('job_accept_reject',$job_id);
            }else{
                $job['status']=$job_status;
            }
        }
            // If a user exists in the data store e.g. database
            if (!empty($job))
            {
                /* if($check_store == 0)
                { */
                    if($check_tester==1)
                    {
                        if($count_job==0)
                        {
							$data['job_info']  = $this->Job_model->job_info($job_id);
							$pre_tasting_date=$data['job_info']->tasting_date;
							$pre_start_time=$data['job_info']->start_time;
							$pre_end_time=$data['job_info']->end_time;
							
                            $insert=$this->Job_model->update_job('job','id',$job_id,$job);
                            $this->load->library('mail_template');
							if($taster_id!=''){
								$result['pre_tester_info']=$this->Job_model->get_tester_details($taster_id);
								if(isset($result['pre_tester_info']->first_name))
									$name=$result['pre_tester_info']->first_name;
								if(isset($result['pre_tester_info']->email))
									$email=$result['pre_tester_info']->email;
								$pre_store_data = $this->Job_model->get_store_name($store_id);
								$currentjobData=$this->Job_model->job_info($job_id);
								$this->mail_template->update_job_email($name,$email, $pre_tasting_date, $pre_start_time, $pre_end_time, $pre_store_data, $currentjobData, $pre_store_data);
								$notifications = $this->Notifications_model->send_notifications_for_change_info($taster_id,$job_id);
							}
                            $this->set_response([
                                'success' => TRUE,
                            ], REST_Controller::HTTP_OK);
                         // OK (200) being the HTTP response code
                        }
                        else
                        {
                            $this->set_response([
                            'success' => FALSE,
                            'error' => 'The tester has already assigned with other job'
                        ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                        }
                    }
                    else
                    {
                        $this->set_response([
                            'success' => FALSE,
                            'error' => 'The tester you want to assign have not belong at the zone'
                        ], REST_Controller::HTTP_OK);
                    }
                /* }
                else
                {
                    $this->set_response([
                            'success' => FALSE,
                            'error' => 'Some jobs are already assigned with the store at this particular time.'
                        ], REST_Controller::HTTP_OK);
                } */
            }
            else
            {
                $this->set_response([
                    'success' => FALSE,
                    'error' => 'Job not updated'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        return false;
    }
    public function delete_job_post()
    {
        $user_id = $this->post('user_id');
        $job_id=$this->post('job_id');
        $delete=$this->Job_model->delete_job('job',$user_id,$job_id);
        if($delete)
        {
            $this->set_response([
                        'success' => TRUE,
                    ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->set_response([
                    'success' => FALSE,
                    'error' => 'Job not deleted'
                ], REST_Controller::HTTP_OK);
        }
    }
    public function get_joblist_get()
    {
        $user_id=$this->get('user_id');
        $details=$this->Job_model->get_joblist($user_id);
        if (!empty($details))
        {
            $this->set_response([
                'success' => TRUE,
                'data' => $details,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Job could not be found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
        return false;
    }
    public function get_jobdetails_get()
    {
        $user_id=$this->get('user_id');
        $job_id=$this->get('job_id');
        $details=$this->Job_model->get_jobdetails($user_id,$job_id);
        if (!empty($details))
        {
            $this->set_response([
                'success' => TRUE,
                'data' => $details,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Job could not be found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
        return false;
    }
    public function submit_general_notes_post()
    {
        $user_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $general_note=$this->post('general_note');
        $qa=$this->Job_model->check_data('general_notes',$job_id);
        $data=array(
                'user_id'=>$user_id,
                'job_id'=>$job_id,
                'general_note'=>$general_note
        );
        if($user_id && $job_id && $general_note)
        {
            if($qa==0)
            {
                $note_id=$this->Job_model->submit_general_notes('general_notes',$data);
            }
            else
            {
                //First delete old data then re insert the details
                $this->Job_model->delete_data('general_notes',$job_id);
                $note_id=$this->Job_model->submit_general_notes('general_notes',$data);
            }
            if($note_id)
            {
                $this->set_response([
                    'success' => TRUE,
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                    'success' => FALSE,
                    'error' => 'General note not submitted'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }
        else
        {
            $this->set_response([
                    'success' => FALSE,
                    'error' => 'Fields value is missing,all fields are mandatory'
                ], REST_Controller::HTTP_OK);
        }
        return false;
    }
    public function question_answers_get()
    {
        $job_id=$this->get('job_id');
        $details=$this->Job_model->question_answers($job_id);
        if (!empty($details))
        {
            $this->set_response([
                'success' => TRUE,
                'data' => $details,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Job could not be found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
        return false;
    }
    public function accept_job_post()
    {
        $tester_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $accept_status=$this->post('accept');
        if($accept_status==1)
        {
            $accepted_or_rejected_by=$tester_id;
        }
        else
        {
            $accepted_or_rejected_by=$tester_id;
        }
        //check tester is assigned or not
        $check=$this->Job_model->check_tester('job',$job_id);
        if($check==true)
        {
            //check tester role
            $tester_role=$this->Job_model->check_tester_role('users',$tester_id);
            if($tester_role=='tester' || $tester_role=='agency')
            {
                if($tester_id && $job_id && $accept_status!='')
                {
                    $job_id=$this->Job_model->check_job('job',$tester_id,$job_id);
                    if($job_id)
                    {
                        //Check job is alredy accepted or not
                        $accepted_no=$this->Job_model->check_job_accept_no('job_accept_reject',$job_id);
                        if($accept_status==1 && $accepted_no>0)
                        {
                            $this->set_response([
                                'success' => FALSE,
                                'error' => 'The job is accepted by other tester.You can not accept the job'
                            ], REST_Controller::HTTP_OK);
                        }
                        else
                        {
                            //$this->db->delete('job_accept_reject', array('job_id' => $job_id)); 
                            $accept=$this->Job_model->accept_or_reject('job',$job_id,$accept_status,$accepted_or_rejected_by);
                            if($accept)
                            {
                                $flag=$this->Job_model->accept_reject_status($job_id);
                                if($flag==0)
                                {
                                    $data['status']='rejected';
                                    $data['job_status']=2;
                                    $array = array('id' => $job_id);
                                    $this->db->where($array);
                                    $this->db->update('job', $data);
                                    //$this->db->delete('job_accept_reject', array('job_id' => $job_id)); 
                                }
                                $this->set_response([
                                    'success' => TRUE,
                                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                            }
                            else
                            {
                                $this->set_response([
                                'success' => FALSE,
                                'error' => 'Job not accepted or rejected! Try again'
                            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                            }
                        }
                    }
                    else
                    {
                        $this->set_response([
                            'success' => FALSE,
                            'error' => 'Job not found'
                        ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                    }
                }
                else
                {
                    $this->set_response([
                            'success' => FALSE,
                            'error' => 'Fields value is missing,all fields are mandatory'
                        ], REST_Controller::HTTP_OK);
                }
            }
            else
            {
                $this->set_response([
                        'success' => FALSE,
                        'error' => 'The user id you want to input is not a id of tester'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }
        else
        {
            $this->set_response([
                        'success' => FALSE,
                        'error' => 'No tester are assigned with the job.Contact with administrator'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
        return false;
    }
    public function get_accepted_job_get()
    {
        $tester_id=$this->get('user_id');
        $todays_job=$this->get('todays_job');
        $details=$this->Job_model->get_accepted_joblist($tester_id,$todays_job);
        if (!empty($details))
        {
            $this->set_response([
                'success' => TRUE,
                'data' => $details,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Job could not be found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
        return false;
    }
    public function cancel_job_post()
    {
        $taster_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $reason=$this->post('reason');
        $cancel_date=date('Y-m-d');
        $details=$this->Job_model->cancel_job($taster_id,$job_id,$reason,$cancel_date);
        if (!empty($details))
        {
            $this->set_response([
                'success' => TRUE,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Job not cancelled'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
        return false;
    }

    public function out_of_range_post(){
        //get job id, latitude and longitude from the app
        $job_id=$this->post('job_id');
        $latitude=$this->post('latitude');
        $longitude=$this->post('longitude');
        $start_end=$this->post('start_end');

        if($start_end=='2'){
            $start_end=2;
        }else{
            $start_end=1;
        }
        
        $update_array=array(
            'latitude'=>$latitude,
            'longitude'=>$longitude,
            'is_out_of_range'=>$start_end,
            'status'=>'problems',
            'job_status'=>4
        );
        $update_id=$this->Job_model->set_job_state($update_array,$job_id);
        if ($update_id){
            $this->set_response([
                'success' => TRUE,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        }else{
            $this->set_response([
            'success' => FALSE,
            'error' => 'location updation failed'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }

    
    }

    public function update_finish_time_post(){

        $job_id=$this->post('job_id');
        //$time = date("H:i", strtotime($this->post('time')));
        $get_endtime=$this->post('time');

        $this->db->select('start_time, end_time, job_start_time');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get()->row();

        //$difference_schedule_Time = strtotime($result->end_time) - strtotime($result->start_time);
        //$difference_time=date('h:i',$difference_schedule_Time);
        // print_r($difference_time);die;

        // $actual_difference_Time = strtotime($result->finish_time) - strtotime($result->job_start_time);
        // $actual_difference_Time = date('h:i', abs($actual_difference_Time));

        $job_schedule_end_time = strtotime($result->end_time); 
        $job_schedule_start_time = strtotime($result->start_time); 
        $schedule_difference_time_minite = round(abs($job_schedule_start_time - $job_schedule_end_time) / 60,2);
        //echo $difference_time_minite;die;


        $job_actual_end_time = strtotime($get_endtime); 
        $job_actual_start_time = strtotime($result->job_start_time); 
        $actual_difference_time_minite = round(abs($job_actual_start_time - $job_actual_end_time) / 60,2);
        $exceedTimeSlot=0;
         //print_r($result);die;
        // echo $actual_difference_time_minite;die;
        $update_array['endtime_state']=4;
        if($schedule_difference_time_minite > $actual_difference_time_minite){
            $update_array['finish_time']= date("H:i",$job_actual_end_time );

        }else{
            // $exceedTimeSlot= $actual_difference_time_minite - $schedule_difference_time_minite;
            // if($exceedTimeSlot<10){
            //     $jobStartTime = date("H:i", strtotime('+'.$exceedTimeSlot.' minutes', $job_actual_start_time));
            //     //$update_array['job_start_time']=$time;
            // }else{
            //     $jobStartTime = date("H:i", strtotime('+10 minutes', $job_actual_start_time));
            //     //$update_array['job_start_time']=$time;
            // }
            $jobStartTime = date("H:i", strtotime('+0 minutes', $job_actual_start_time));
            $update_array['job_start_time']=$jobStartTime;

            //$calculated_time = strtotime('+'.$schedule_difference_time_minite.' minutes', $job_actual_start_time);
            $calculated_time = strtotime('+'.$schedule_difference_time_minite.' minutes',  strtotime($jobStartTime));
            $updatedtime = date("H:i", $calculated_time);
            $update_array['finish_time']=$updatedtime;
        }

        if($exceedTimeSlot!=0){
            $difference=strtotime($update_array['finish_time']) -  strtotime($jobStartTime);
        }else{
            $difference=strtotime($update_array['finish_time']) - $job_actual_start_time;
        }

        //Calculate time difference with custom time

        //$time=finish time and $job_start_time=actual start time 

        //$difference=strtotime($update_array['finish_time']) - $job_actual_start_time;
                
        //echo $difference;die;
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
        
        //echo $working_hour = round(abs($difference - $total_pause_time) / 3600,2);die;
        $working_hour=gmdate("H:i", ($difference - $total_pause_time));
        $update_array['working_hour']=$working_hour;
        $job=$this->Job_model->get_job_details($job_id);
                if($job->status!='problems')
                {
                    $update_array['ready_for_billing'] = 1;
                }

        $this->Job_model->set_job_state($update_array,$job_id);
        $manager_name=$this->Job_model->getManagerName($job_id);
        $manager_name=$manager_name[0]['manager_name'];
        $completedJobData= $this->Job_model->get_completed_job_info($job_id);
        $samplingDate = $completedJobData->tasting_date;
        $samplingDate = date("F d, Y", strtotime($samplingDate));
        // $samplingDate = date("m-d-Y", strtotime($samplingDate));
        $jobStartTime = $completedJobData->job_start_time;
        if($completedJobData->agency_taster_id){
            $tasterName=$this->Job_model->getTasterName($completedJobData->agency_taster_id);
        }else{
            $tasterName=$this->Job_model->getTasterName($completedJobData->taster_id);
        }
       $tasterName=$tasterName->taster_name;
       $startTime=$completedJobData->job_start_time;
       $finish_time=$completedJobData->finish_time;
       $wineNames=$this->Job_model->get_mail_wine_names($job_id);
       $storeMangerMailAddress = $this->Job_model->get_store_mail($job_id);
       $store = $this->Job_model->get_store_name_mail($job_id);
       $store_name = $store[0]['store_name'];
       $store_address = $store[0]['store_address'];
       $salesrep = $this->Job_model->get_mail_selsrep_name($job_id);
       $salesrep_name = $salesrep->sales_rep_name;
       $this->load->library('mail_template');
       //$data=$this->jobRatingMailTemplate($job_id, $manager_name, $samplingDate, $tasterName, $startTime, $finish_time, $wineNames);
       $data=$this->jobRatingMailTemplate($job_id, $manager_name, $samplingDate, $tasterName, $startTime, $finish_time, $wineNames,$salesrep_name,$store_name,$store_address);
       $this->mail_template->email_to_store($storeMangerMailAddress, 'Wine Sampling - '.$samplingDate, $data);


        $this->set_response([
            'success' => TRUE,
        ], REST_Controller::HTTP_OK);

    }

    public function set_job_state_post()
    {
        
        $taster_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $job_state=$this->post('job_state');
        $time = date("H:i", strtotime($this->post('time')));

        $is_out_of_range=$this->Job_model->get_out_range_status($job_id); //added rahul 
        if($is_out_of_range!=0){
            $this->db->select('latitude,longitude');
            $this->db->from('job');
            $this->db->where('id',$job_id);
            $result=$this->db->get()->row();
                $latitude=$result->latitude;
                $longitude=$result->longitude;
        }else{
        $latitude=$this->post('latitude');
        $longitude=$this->post('longitude');
        }


        $overtime=$this->post('overtime');
        //$late=$this->post('late');
        //get job status
        $this->db->select('status,start_time,end_time,job_start_time,pause_time,resume_time,finish_time,taster_id,agency_taster_id');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get()->row();
        $status=$result->status;
        $start_time=$result->start_time;
        $end_time=$result->end_time;
        $actual_start_time=$result->job_start_time;
        //$pause_time=$result->pause_time;
        //$resume_time=$result->resume_time;
        $job_start_time=$result->job_start_time;
        $finish_time=$result->finish_time;
        if($result->agency_taster_id==0)
        {
            $taster_id=$result->taster_id;
        }
        else
        {
            $taster_id=$result->agency_taster_id;
        }
        if( $status=='approved' || $status=='completed' || $is_out_of_range!=0 )
        {
            $update_array=array(
                'job_state'=>$job_state,
                'latitude'=>$latitude,
                'longitude'=>$longitude,
                'overtime'=>$overtime
            );
            

            if($job_state==1)
            {
                //$minusTenmintime = date("H:i", strtotime('-10 minutes',strtotime($this->post('time'))));
                $minusTenmintime = date("H:i", strtotime($this->post('time')));
                //$update_array['job_start_time']=$time;
                $update_array['job_start_time']=$minusTenmintime;
                $update_array['endtime_state']=2;
                $update_id=$this->Job_model->set_job_state($update_array,$job_id);
            }
            else if($job_state==2)
            {
                $update_array['status']='completed';
                $update_array['endtime_state']=3;
                //$update_array['finish_time']=$time;
                //Calculate working hour
                //$start_time = strtotime($start_time);
                //$end_time = strtotime($end_time);
                
                //Calculate time difference with custom time
                //$difference=strtotime($time)-strtotime($job_start_time);
                
                //echo $difference;die;
                //Calculate total pause time

                $job_schedule_end_time = strtotime($result->end_time); 
                $job_schedule_start_time = strtotime($result->start_time); 
                $schedule_difference_time_minite = round(abs($job_schedule_start_time - $job_schedule_end_time) / 60,2);

                $job_actual_end_time = strtotime($this->post('time')); 
                $job_actual_start_time = strtotime($result->job_start_time); 
                $actual_difference_time_minite = round(abs($job_actual_start_time - $job_actual_end_time) / 60,2);
            

                if($schedule_difference_time_minite > $actual_difference_time_minite){
                    //$update_array['finish_time']= date("H:i",$job_actual_end_time );
                }else{
                    $calculated_time = strtotime('+'.$schedule_difference_time_minite.' minutes', $job_actual_start_time);
                    $updatedtime = date("H:i", $calculated_time);
                    //$update_array['finish_time']=$updatedtime;
                }

                //$difference=strtotime($update_array['finish_time'])-strtotime($job_start_time);

                

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
                
                //echo $working_hour = round(abs($difference - $total_pause_time) / 3600,2);die;
                //$working_hour=gmdate("H:i", ($difference - $total_pause_time));
                //$update_array['working_hour']=$working_hour;

                //set the job for problem state
                // $selectedTime = $start_time;
                // $calculated_time = strtotime("+15 minutes", strtotime($selectedTime));
                // $difference_time=date('h:i:s', $calculated_time);
                // if(date("h:i",strtotime($actual_start_time))>$difference_time){
                //     $update_id=$this->Job_model->set_job_state($update_array,$job_id);
                //     $this->Job_model->set_job_for_problems($job_id);
                // }
                // else
                // {
                    $update_id=$this->Job_model->set_job_state($update_array,$job_id);

                    $this->load->library('push_notifications');
                    $this->load->model('Notifications_model');
                    //Get data to send push notifications
                    $user_details=$this->Notifications_model->get_notification_details_for_completed_job($job_id,$taster_id);
                        
                            
                        if($is_out_of_range!=0){
                            $this->Job_model->set_job_for_problems($job_id); 
                        }


                    //Send notifications for completed job
                    $user_details->job_id=$job_id;
                    $notifications = $this->Notifications_model->send_notifications_for_completed_job($user_details);

                    //Send notifications for early finished or start job
                    //Get number of row
                    $number_of_jobs=$this->Job_model->check_job_earlier_or_later($taster_id);
                    if($number_of_jobs >=3)
                    {
                        $this->load->library('push_notifications');
                        $this->load->model('Notifications_model');
                        $notifications = $this->Notifications_model->send_notifications_for_start_or_finish_job($taster_id);
                    }

                //}
                
                

                
                
            }
            else if($job_state==3)
            {
                //$update_array['pause_time']=$time;
                $update_id=$this->Job_model->set_job_state($update_array,$job_id);
                //set pause  time
                $update_id=$this->Job_model->set_pause_time($time,$job_id);
            }
            else if($job_state==4)
            {
                //$update_array['resume_time']=$time;
                $update_id=$this->Job_model->set_job_state($update_array,$job_id);
                //Set resume time
                $update_id=$this->Job_model->set_resume_time($time,$job_id);
            }
            $this->set_response([
                'success' => TRUE,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

            
        }
        else
        {
            if($job_status=='problems')
            {
                $this->set_response([
                    'success' => FALSE,
                    'error' => 'Your end time is past today\'s date. So, your job has been moved to the Problem Section.'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }else{
                $this->set_response([
                    'success' => FALSE,
                    'error' => 'Your job has been canceled. Please contact Admin for details.'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
            
        }
    }

    //submit wine details for completed job
    public function submit_wine_details_post()
    {
        $taster_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $wine_details_array=$this->post('wine_details');
        //get job state
        $this->db->select('job_state');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get()->row();
        $job_state=$result->job_state;
        $qa=$this->Job_model->check_data('completed_job_wine_details',$job_id);
        if($job_state!='' && $job_state==2)
        {
            if($qa==0)
            {
                $insert_id=$this->Job_model->submit_wine_details($wine_details_array,$taster_id,$job_id);
            }
            else
            {
                //First delete old data then re insert the details
                $this->Job_model->delete_data('completed_job_wine_details',$job_id);
                $insert_id=$this->Job_model->submit_wine_details($wine_details_array,$taster_id,$job_id);
            }
            if ($insert_id)
            {
                $this->set_response([
                    'success' => TRUE,
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                'success' => FALSE,
                'error' => 'Wine not submitted'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'The job is not completed'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //Get completed job list according to sales representative
    public function get_completed_job_get()
    {
        $sales_rep_id=$this->get('user_id');
        $details=$this->Job_model->get_completed_job($sales_rep_id);
        if (!empty($details))
        {
            $this->set_response([
                'success' => TRUE,
                'data' => $details,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Job could not be found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
        return false;
    }
    //Submit manager verification details for completed job
    public function submit_manager_verification_details_post()
    {
        $taster_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $name=$this->post('name');
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
        /*$first_name=$this->post('first_name');
        $last_name=$this->post('last_name');*/
        $cell_number=$this->post('cell_number');
        $comment=$this->post('comment');
        //get job status
        $this->db->select('status');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get();

        $r=$result->row();
        
        $job_status=$r->status;
        $date=date('Y-m-d');
        //Check user type
        $user_type=$this->Job_model->get_user_type('users',$taster_id);
        
        if($user_type[0]->user_type=='tester')
        {
            if(!empty($_FILES))
            {
                $signature_img=$_FILES['signature_img']['name'];
                //Start signature image upload to directory
                if (!empty($_FILES)) {
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
                //End Upload
                $manager_verification_array=array(
                    'taster_id'=>$taster_id,
                    'job_id'=>$job_id,
                    'first_name'=>$first_name,
                    'last_name'=>$last_name,
                    //'cell_number'=>$cell_number,
                    'comment'=>$comment,
                    'signature_img'=>$uploaded_pics,
                    'date'=>$date
                );
                $qa=$this->Job_model->check_data('manager_verification_details',$job_id);
                if($job_status=='completed' || $job_status=='problems')
                {
                    
                    if($qa==0)
                    {
                        $submit_id=$this->Job_model->submit_manager_verification_details($manager_verification_array);
                    }
                    else
                    {
                        $qaID=$this->Job_model->get_id('manager_verification_details',$job_id);
                        $submit_id=$this->Job_model->update_table('manager_verification_details',$manager_verification_array,$qaID);
                    }
                    if ($submit_id)
                    {
                        $this->set_response([
                            'success' => TRUE,
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    }
                }
                else
                {
                    $this->set_response([
                    'success' => FALSE,
                    'error' => 'The job is not complete'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                }
            }
            else
            {
                $this->set_response([
                    'success' => FALSE,
                    'error' => 'Signature is missing,Please upload your signature'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Please input a tester id.The user is not tester'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            return false;
        }
    }
    //get manager verification details
    public function get_manager_verification_details_get()
    {
        $job_id=$this->get('job_id');
        if($job_id || $job_id!='')
        {
            $details=$this->Job_model->get_manager_verification_details($job_id);
            if(!empty($details))
            {
                $this->set_response([
                        'success' => TRUE,
                        'data'=>$details
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                    $this->set_response([
                    'success' => FALSE,
                    'error' => 'No verification data could not be found for this job'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                    return false;
            }
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Please input a valid job id'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            return false;
        }
    }
    //Submit expense details for completed job
    
    //Submit expense details old
/*    public function submit_expense_details_post()
    {
        $taster_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $exp_amount="$".$this->post('exp_amount');
        $exp_reason=$this->post('exp_reason');
        //get job status
        $this->db->select('status');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get()->row();
        $job_status=$result->status;
        $date=date('Y-m-d');
        if(!empty($_FILES))
        {
            $supported_imgs=$_FILES['supported_imgs'];
            //echo count($supported_imgs['name']);die;
            $expense_array=array(
                'taster_id'=>$taster_id,
                'job_id'=>$job_id,
                'exp_amount'=>$exp_amount,
                'exp_reason'=>$exp_reason,
                'date'=>$date
            );
            
            if($job_status=='completed' || $job_status=='problems')
            {
                $expense_id=$this->Job_model->submit_expense_details($expense_array);
                //Upload multiple images for expenses
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
                //End Upload
                if ($image_id)
                {
                    $this->set_response([
                        'success' => TRUE,
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
            }
            else
            {
                $this->set_response([
                'success' => FALSE,
                'error' => 'The job is not complete'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Supported images are missing'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }*/
    
    //Submit expense details new
    public function submit_expense_details_post()
    {
        $taster_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $exp_amount="$".$this->post('exp_amount');
        $exp_reason=$this->post('exp_reason');
        //get job status
        $this->db->select('status');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get()->row();
        $job_status=$result->status;
        $date=date('Y-m-d');
        $qa=$this->Job_model->check_data('expense_details',$job_id);
        /*if(!empty($_FILES))
        {*/
            if(!empty($_FILES))
            {
                $supported_imgs=$_FILES['supported_imgs'];
            }
                
            //echo count($supported_imgs['name']);die;
            $expense_array=array(
                'taster_id'=>$taster_id,
                'job_id'=>$job_id,
                'exp_amount'=>$exp_amount,
                'exp_reason'=>$exp_reason,
                'date'=>$date
            );
            
            if($job_status=='completed' || $job_status=='problems')
            {
                if($qa==0)
                {
                    $expense_id=$this->Job_model->submit_expense_details($expense_array);
                }
                else
                {
                    $qaID=$this->Job_model->get_id('expense_details',$job_id);
                    $expense_id=$this->Job_model->update_table('expense_details',$expense_array,$qaID);
                }
                //Upload multiple images for expenses
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
                //End Upload
                if ($expense_id)
                {
                    $this->set_response([
                        'success' => TRUE,
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
            }
            else
            {
                    $this->set_response([
                    'success' => FALSE,
                    'error' => 'The job is not complete'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        /*}
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Supported images are missing'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }*/
    }
    //Get expense details for a completed job
    public function get_expense_details_get()
    {
        $job_id=$this->get('job_id');
        if($job_id || $job_id!='')
        {
            $details=$this->Job_model->get_expense_details($job_id);
            $details[0]['support_imgs']=implode(',', $details[0]['support_imgs']);
            if(!empty($details))
            {
                $this->set_response([
                        'success' => TRUE,
                        'expense_image_path'=>BASE_URL.DIR_EXPENSE_IMAGE,
                        'data'=>$details
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                    $this->set_response([
                    'success' => FALSE,
                    'error' => 'No expense details could not be found for this job'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                    return false;
            }
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Please input a valid job id'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            return false;
        }
    }
    //Get billing information
    public function get_billing_information_get()
    {
        $taster_id=$this->get('user_id');
        $month=strtolower($this->get('month'));
        $year=$this->get('year');
        $month_number=date('m',strtotime($month));
        
        $details=$this->Job_model->get_billing_information($taster_id,$month_number,$year);
        $indx=-1;
        foreach($details as $dtl){
            ++$indx;
            $details[$indx]['expense_details'][0]['support_imgs']=implode(',', $details[$indx]['expense_details'][0]['support_imgs']);
        }
        
        if(!empty($details))
        {
            
            $this->set_response([
                'success' => TRUE,
                
                'data'=>$details
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
            'success' => FALSE,
            'error' => 'No billing information found'
        ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            return false;
        }
    }
    //Submit question and answers for a particular job
//Submit question answers old
/*    public function submit_question_answers_post()
    {
        $taster_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $question_id=$this->post('question_id');
        $ans_type=$this->post('ans_type');
        $ans_text=$this->post('ans_text');
        $ans_id=$this->post('ans_id');
        $date=date('Y-m-d');
        if(!empty($_FILES))
        {
            $supported_imgs=$_FILES['support_imgs'];
            //echo count($supported_imgs['name']);die;
            $question_answer_array=array(
                'taster_id'=>$taster_id,
                'job_id'=>$job_id,
                'question_id'=>$question_id,
                'ans_type'=>$ans_type,
                'ans_text'=>$ans_text,
                'ans_id'=>$ans_id,
                'date'=>$date
            );
            $insert_id=$this->Job_model->submit_question_answers($question_answer_array);
            if($insert_id)
            {
                //Upload multiple images for expenses
                if (!empty($supported_imgs['name'][0])) {
                        // Update Product Image
                        $config['upload_path'] = DIR_QUESTION_ANSWER_IMAGE;
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
                                $config_thumb['source_image'] = DIR_QUESTION_ANSWER_IMAGE.$this->upload->file_name;
                                $config_thumb['create_thumb'] = FALSE;
                                $config_thumb['maintain_ratio'] = TRUE;
                                $config_thumb['master_dim'] = 'auto';
                                $config_thumb['width'] = DIR_QUESTION_ANSWER_IMAGE_SIZE; // image re-size  properties
                                $config_thumb['height'] = DIR_QUESTION_ANSWER_IMAGE_SIZE; // image re-size  properties
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
                                $image_id=$this->Job_model->insert_question_answer_supported_images($insert_id, $uploaded_pics);
                            } else {
                                $this->upload->display_errors(); die;
                            }
                        }
                }
                //End Upload
                if ($image_id)
                {
                    $this->set_response([
                        'success' => TRUE,
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
            }
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Supported images are missing'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }*/
    
//Submit question answers new
    public function submit_question_answers_post()
    {
        $taster_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $question_id=$this->post('question_id');
        $ans_type=$this->post('ans_type');
        $ans_text=$this->post('ans_text');
        $ans_id=$this->post('ans_id');
        $date=date('Y-m-d');
        
        //Get previous question answer details
        
        $qa=$this->Job_model->check_data('question_answer_for_job',$job_id);
        /*if($qa > 0)
        {
            //Get question answer id
            $qaID=$this->Job_model->get_id('question_answer_for_job',$job_id);
            $this->Job_model->delete_data('question_answer_for_job',$job_id);
            
            //Check for question answer image and delete
            $qaImage=$this->Job_model->check_data_image('question_answer_images','question_answer_id',$qaID);
            if($qaImage > 0)
            {
                $this->Job_model->delete_images('question_answer_images','question_answer_id',$qaID);
            }
        }*/
        
        
        
        /*if(!empty($_FILES))
        {*/
            if(!empty($_FILES))
            {
                $supported_imgs=$_FILES['support_imgs'];
            }
                
            //echo count($supported_imgs['name']);die;
            $question_answer_array=array(
                'taster_id'=>$taster_id,
                'job_id'=>$job_id,
                'question_id'=>$question_id,
                'ans_type'=>$ans_type,
                'ans_text'=>$ans_text,
                'ans_id'=>$ans_id,
                'date'=>$date
            );
            /*if($qa==0)
            {
                $insert_id=$this->Job_model->submit_question_answers($question_answer_array);
            }
            else
            {
                $qaID=$this->Job_model->get_id('question_answer_for_job',$job_id);
                $insert_id=$this->Job_model->update_table('question_answer_for_job',$question_answer_array,$qaID);
            }*/
            $insert_id=$this->Job_model->submit_question_answers($question_answer_array);
            if($insert_id)
            {
                //Upload multiple images for expenses
                if (!empty($supported_imgs['name'][0])) {
                        // Update Product Image
                        $config['upload_path'] = DIR_QUESTION_ANSWER_IMAGE;
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
                                $config_thumb['source_image'] = DIR_QUESTION_ANSWER_IMAGE.$this->upload->file_name;
                                $config_thumb['create_thumb'] = FALSE;
                                $config_thumb['maintain_ratio'] = TRUE;
                                $config_thumb['master_dim'] = 'auto';
                                $config_thumb['width'] = DIR_QUESTION_ANSWER_IMAGE_SIZE; // image re-size  properties
                                $config_thumb['height'] = DIR_QUESTION_ANSWER_IMAGE_SIZE; // image re-size  properties
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
                                $image_id=$this->Job_model->insert_question_answer_supported_images($insert_id, $uploaded_pics);
                            } else {
                                $this->upload->display_errors(); die;
                            }
                        }
                }
                //End Upload
                if ($insert_id)
                {
                    $this->set_response([
                        'success' => TRUE,
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
                else
                {
                    $this->set_response([
                        'success' => FALSE,
                        'error' => 'Not uploaded'
                    ], REST_Controller::HTTP_OK);
                }
            }
        /*}
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Supported images are missing'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }*/
    }
    
//Submit tasting setup
    public function submit_tasting_setup_post()
    {
        $taster_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $tasting_type=$this->post('tasting_type'); 
        
        if(!empty($_FILES))
        {
            //echo "<pre>";
            //print_r($_FILES);die;
            $supported_imgs=$_FILES['setup_imgs'];
            if(count($supported_imgs['name']) >=1)
            {
                

                //echo count($supported_imgs['name']);die;
                $question_answer_array=array(
                    'taster_id'=>$taster_id,
                    'job_id'=>$job_id,

                );
                $insert_id=$this->Job_model->submit_tasting_setup($question_answer_array);
                if($insert_id)
                {
                    //Upload multiple images for expenses
                    if (!empty($supported_imgs['name'][0])) {
                            // Update Product Image
                            $config['upload_path'] = DIR_TASTING_SETUP_IMAGE;
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
                                
                                if ($tasting_type == "store") {
                                    $config['file_name'] = 'store-'.rand().date('YmdHis');
                                   }else{
                                    $config['file_name'] = 'setup-'.rand().date('YmdHis');
                                   }
                                $images[] = $config['file_name'];
                                $this->upload->initialize($config);
                                if ($this->upload->do_upload('images[]')) {
                                    $config_thumb['image_library'] = 'gd2';
                                    $config_thumb['source_image'] = DIR_TASTING_SETUP_IMAGE.$this->upload->file_name;
                                    $config_thumb['create_thumb'] = FALSE;
                                    $config_thumb['maintain_ratio'] = TRUE;
                                    $config_thumb['master_dim'] = 'auto';
                                    $config_thumb['width'] = DIR_QUESTION_ANSWER_IMAGE_SIZE; // image re-size  properties
                                    $config_thumb['height'] = DIR_QUESTION_ANSWER_IMAGE_SIZE; // image re-size  properties
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
                                    $image_id=$this->Job_model->insert_tasting_setup_images($insert_id, $uploaded_pics,$tasting_type);
                                } else {
                                    $this->upload->display_errors(); die;
                                }
                            }
                    }
                    //End Upload
                    if ($image_id)
                    {
                        $this->set_response([
                            'success' => TRUE,
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    }
                }
            }
            else
            {
                $this->set_response([
                    'success' => FALSE,
                    'error' => 'You have to upload minimum 2 images'
                ], REST_Controller::HTTP_OK);
            }
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'Setup images are missing'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //Confirm or unavailable wine
    public function confirm_wine_post()
    {
        $taster_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $wine_id=$this->post('wine_id');
        $confirm_status=$this->post('confirm_status');
        $date=date('Y-m-d');
        $this->db->select('wine_id');
        $this->db->from('job');
        $this->db->like('wine_id',$wine_id);
        $this->db->where('id',$job_id);
        $result=$this->db->get()->result_array();
        if(!empty($result))
        {
            $confirm_data=array(
                'taster_id'=>$taster_id,
                'job_id'=>$job_id,
                'wine_id'=>$wine_id,
                'confirm_status'=>$confirm_status,
                'date'=>$date
            );
            $confirm_id=$this->Job_model->confirm_wine($confirm_data);
            if($confirm_id)
            {
                $this->set_response([
                        'success' => TRUE,
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                    $this->set_response([
                    'success' => FALSE,
                    'error' => 'Wine not confirmed'
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                    return false;
            }
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'The wine are not assigned with the job'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            return false;
        }
    }
    //Add wine Manually
    public function add_wine_post()
    {
        $created_by=$this->post('user_id');
        $flavour=strtolower($this->post('wine_flavour'));
        $company_type=strtolower($this->post('company_type'));
        
        $size=preg_replace("/[^0-9]/", "", $this->post('wine_size') );
        $status='active';        
        $data = array(
            'upc_code' => htmlspecialchars($this->post('wine_upc'), ENT_QUOTES, 'utf-8'),
            'name' => htmlspecialchars($this->post('wine_name'), ENT_QUOTES, 'utf-8'),
            'brand' => $this->post('wine_brand'),
            'year' => htmlspecialchars($this->post('wine_year'), ENT_QUOTES, 'utf-8'),
            'category_id' => htmlspecialchars($this->post('wine_category_id'), ENT_QUOTES, 'utf-8'),
            'type' => htmlspecialchars($this->post('wine_type'), ENT_QUOTES, 'utf-8'),
            'description' => $this->post('wine_description'),
            'size' => htmlspecialchars($size, ENT_QUOTES, 'utf-8'),
            'flavour' => htmlspecialchars($flavour, ENT_QUOTES, 'utf-8'),
            'company_type'=>htmlspecialchars($company_type, ENT_QUOTES, 'utf-8'),
            'status' => htmlspecialchars($status, ENT_QUOTES, 'utf-8'),
            'created_by' => $created_by,
            'created_on' => date('Y-m-d H:i:s')
        );
        $wine_id=$this->Job_model->add_wine($data);
        if($wine_id)
        {
            if(!empty($_FILES))
            {
                $wine_images=$_FILES['wine_images'];
                //Upload multiple images for wine
                if (!empty($wine_images['name'][0])) {
                        // Update Wine Image
                        $config['upload_path'] = DIR_WINE_PICTURE;
                        $config['max_size'] = '10000';
                        $config['allowed_types'] = 'jpg|png|jpeg';
                        $config['overwrite'] = FALSE;
                        $config['remove_spaces'] = TRUE;
                        $this->load->library('upload', $config);
                        $images = array();
                        foreach ($wine_images['name'] as $key => $image) {
                            $_FILES['images[]']['name']= $wine_images['name'][$key];
                            $_FILES['images[]']['type']= $wine_images['type'][$key];
                            $_FILES['images[]']['tmp_name'] = $wine_images['tmp_name'][$key];
                            $_FILES['images[]']['error']= $wine_images['error'][$key];
                            $_FILES['images[]']['size']= $wine_images['size'][$key];
                            $config['file_name'] = 'wine-'.rand().date('YmdHis');
                            $images[] = $config['file_name'];
                            $this->upload->initialize($config);
                            if ($this->upload->do_upload('images[]')) {
                                $config_thumb['image_library'] = 'gd2';
                                $config_thumb['source_image'] = DIR_WINE_PICTURE.$this->upload->file_name;
                                $config_thumb['create_thumb'] = FALSE;
                                $config_thumb['maintain_ratio'] = TRUE;
                                $config_thumb['master_dim'] = 'auto';
                                $config_thumb['width'] = WINE_PICTURE_THUMB_SIZE; // image re-size  properties
                                $config_thumb['height'] = WINE_PICTURE_THUMB_SIZE; // image re-size  properties
                                $config_thumb['new_image'] = DIR_WINE_PICTURE_THUMB.$this->upload->file_name; // image re-size  properties
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
                                $image_id=$this->Job_model->add_wine_images($wine_id, $uploaded_pics);
                            } else {
                                $this->upload->display_errors(); die;
                            }
                        }
                }
            }
                //End Upload
            if($wine_id)
            {
                $this->set_response([
                    'success' => TRUE,
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        }
        else
        {
                $this->set_response([
                'success' => FALSE,
                'error' => 'Wine not added'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                return false;
        }
    }
    //Request an accepted job to other tester
    public function request_job_post()
    {
        $user_id=$this->post('user_id');
        $job_id=$this->post('job_id');
        $reason=$this->post('reason');
        $taster_id=$this->post('taster_id');
        $date=date('Y-m-d');
        //Check a job is acceped or not
        $job_status=$this->Job_model->check_job_status($job_id);
        if($job_status=='accepted')
        {
            $data=array(
                'tester_id'=>$user_id,
                'job_id'=>$job_id,
                'reason'=>$reason,
                'requested_tester_id'=>$taster_id,
                'date'=>$date
            );
            $id=$this->Job_model->request_job_to_tester($data);
            if($id)
            {
                $this->set_response([
                    'success' => TRUE,
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                'success' => FALSE,
                'error' => 'Job not requested to tester'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                return false;
            }
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'The job you have requested is not yet accepted'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                return false;
        }
    }
    //Get completed job details
    public function completed_job_details_get()
    {
        $job_id=$this->get('job_id');
        $job_status=$this->Job_model->check_job_status($job_id);
        if($job_status=='completed')
        {
            $details=$this->Job_model->get_completed_job_details($job_id);
            if(!empty($details))
            {
                $this->set_response([
                    'success' => TRUE,
                    'data'=>$details
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                'success' => FALSE,
                'error' => 'No job details found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                return false;
            }
        }
        else
        {
            $this->set_response([
                'success' => FALSE,
                'error' => 'The job is not completed yet'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                return false;
        }
    }
    //Job Started 
    public function job_start_post(){
        $job_id=$this->post('job_id');
        $start=$this->post('start');
        $update_array = array('endtime_state' => $start);
        $this->Job_model->set_job_state($update_array,$job_id);
        
        $this->set_response(['success' => TRUE], REST_Controller::HTTP_OK);
    }
    function get_tasting_setup_image_get(){
        $job_id=$this->get('job_id');
        $this->db->select('id');
        $this->db->from('tasting_setup');
        $this->db->where('job_id',$job_id);
        $result=$this->db->get()->result_array();
        $items=array();
        $data=array();
        if(isset($result) && count($result)>0){
            foreach($result as $item){
                $items[]=$item['id'];
            }
            
            $this->db->select('image, id, tasting_type');
            $this->db->from('tasting_setup_images');
            $this->db->where_in('tasting_setup_id',$items);
            $result=$this->db->get()->result_array();

            $items=array();
            $indx=-1;
            foreach($result as $item){
                $items['img']=BASE_URL.DIR_TASTING_SETUP_IMAGE.$item['image'];
                $items['id']=$item['id'];
                $items['tasting_type']=$item['tasting_type'];
                $data[]=$items;
            }
        }
        $this->set_response([
            'success' => TRUE,
            'data' =>$data
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

    }
    public function tasting_setup_image_delete_post(){
        $image_id=$this->post('id');
        $this->db->select('image, tasting_setup_id');
        $this->db->from('tasting_setup_images');
        $this->db->where('id',$image_id);
        $result=$this->db->get()->result_array();
        //print_r($result);die;
        if(isset($result) && count($result)>0){
            $base_dir = realpath($_SERVER["DOCUMENT_ROOT"]);
            $file_name= $result[0]['image'];
            $file_delete = "$base_dir/assets/wine_tasting_setup_image/$file_name";
            if (file_exists($file_delete)) {
                unlink($file_delete);
            }
            $this->db->delete('tasting_setup_images', array('id' => $image_id));
            $this->set_response([ 'success' => TRUE, 'msg'=>'Done'], REST_Controller::HTTP_OK);
        }else{
            $this->set_response([ 'success' => FALSE,'data' =>'Please send a valid image id'], REST_Controller::HTTP_OK);
        }

    }
    public function jobRatingMailTemplate($job_id, $manager_name, $samplingDate, $tasterName, $startTime, $finish_time, $wineNames, $salesrep_name, $store_name, $store_address)
    {
       $data='';
       $data.='<!DOCTYPE html>
       <html lang="en">
           <head>
               <title></title>
               <meta charset="utf-8">
               <meta name="viewport" content="width=device-width, initial-scale=1">
               
               <style>
                   .container{border: 5px solid #c48f29;width:70%;}
                   .logo{width:100px;}
                   .time{width:35%;}
                   .staricon{width:45%; display:block; margin-left:25%;}
                   .small{
                       font-size: 59%;
                       font-weight: 400;
                   }
                   .img-thumbnail {
                       padding: .25rem;
                       background-color: #fff;
                       border: 1px solid #dee2e6;
                       border-radius: .25rem;
                       max-width: 100%;
                       height: auto;
                   }
                   .size{
                       width:50px;
                   }
                   .size:hover {
                     color: white;
                   }
                   .wine{margin-left: 15px;
                       font-size: 16px;}
                   
                   
                   @media screen and (max-width: 600px) {
                       
                       .container{border: 5px solid #c48f29;width:100%;}
                       .logo{width:30%;}
                       .wine{margin-left: 15px;
                       font-size: 12px;}
                       .tim{font-size:14px;}
                   }
               </style>
           </head>
           <body>
               <center>
                   <div>
                       <table class="container">
                           <tr>
                               <td colspan="3" style="text-align:center;">
                                    <center>
                                        <img src="'.BASE_URL.'assets/wine/thumb/Wine_Logo.png" width="100">
                                    </center>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3" style="text-align:center;">
                                   <h1 style="margin-top:auto;">Tasting Information</h1>
                               </td>
                           </tr>
                         <tr>
                            <td colspan="3">
                                <h3 style="margin-top:auto;">Tasting Date - '.$samplingDate.'</h3>
                            </td>
                       </tr>
                           <tr>
                                <td colspan="3">
                                    <h3 style="margin-top:auto;">Store name & address - '.$store_name.', '.$store_address.'</h3>
                                </td>
                         </tr>
                         <tr>
                                <td colspan="3">
                                    <h3 style="margin-top:auto;">Sales Rep - '.$salesrep_name.'</h3>
                                </td>
                         </tr>
                           <tr>
                               <td colspan="3">
                                   <h3 style="margin-top:auto;">Taster - '.$tasterName.'</h3>
                               </td>
                           </tr>
                           <tr>
                               <td width="100">
                                   <h3 style="margin-top:auto;" class="tim">Job Start Time - '.date("g:i a", strtotime($startTime)).'</h3>
                               </td>
                               <td width="100">
                                   <h3 style="margin-top:auto;" class="tim">Job End Time - '.date("g:i a", strtotime($finish_time)).'</h3>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3">
                                   <h3 style="margin-top:auto;">Wines Sold : </h3>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3">
                                   <table>';
                                if (!empty($wineNames)) {
                                    foreach ($wineNames as $wine) {

                                        if($wine["soldwine"] > 1){
                                            $bottle_sold ='bottles sold';
                                        }else{
                                            $bottle_sold = 'bottle sold';
                                        }
                                        if($wine["usedwine"] > 1){
                                            $bottle_used ='bottles used';
                                        }else{
                                            $bottle_used = 'bottle used';
                                        }
                                    $data.='<tr>
                                           <td style="width:10%;">
                                               <img style="max-width:100px;" src="'.$wine["image"].'" width="75">
                                           </td>
                                           <td>
                                           <p  class="wine">'. $wine["name"] .' - ' . $wine["soldwine"] . ' '.$bottle_sold.',  ' . $wine["usedwine"] . ' '.$bottle_used.'</p>
                                           </td>
                                       </tr>';
                                    }
                                }
                                $data.='</table>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3">
                                   <hr style="border-top: 2px solid rgba(0,0,0,.1);">
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3" style="text-align:center;">
                                   <h1>Please rate the tasting</h1>
                                   <p>Honesty is important, it will help us to improve the quality of the tastings.<br>
                                   (this information will not be shared with the taster)</p>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3" style="text-align:center;">
                                    <center>
                                        <a href="'.BASE_URL.'jobrating/'.$job_id.'/1">
                                            <img src="'.BASE_URL.'assets/wine/thumb/verypoor.png" width="55" height="49" style="border-radius:8px;"/>
                                        </a>
                                        <a href="'.BASE_URL.'jobrating/'.$job_id.'/2">
                                            <img src="'.BASE_URL.'assets/wine/thumb/poor.png" width="55" height="49" style="border-radius:8px;"/>
                                        </a>
                                        <a href="'.BASE_URL.'jobrating/'.$job_id.'/3">
                                            <img src="'.BASE_URL.'assets/wine/thumb/acceptable.png" width="55" height="49" style="border-radius:8px;"/>
                                        </a>
                                        <a href="'.BASE_URL.'jobrating/'.$job_id.'/4">
                                             <img src="'.BASE_URL.'assets/wine/thumb/good.png" width="55" height="49" style="border-radius:8px;"/>
                                        </a>
                                        <a href="'.BASE_URL.'jobrating/'.$job_id.'/5">
                                            <img src="'.BASE_URL.'assets/wine/thumb/verygood.png" width="55" height="49" style="border-radius:8px;"/>
                                        </a>
                                    </center>
                               </td>
                           </tr>
                           <tr>
                               <td colspan="3">
                                   <br>
                               </td>
                           </tr>
                       </table>
                   </div>
               </center>
           </body>
       </html>';
       return $data;
    }


}
