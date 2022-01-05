<?php
class Cron extends Application_Controller {
	//private $allowed_roles = array('bar_admin');
    public function __construct() {
        parent::__construct();
        // Validate Login
        
        $this->load->model('Job_model');
        $this->load->library('mail_template');
    }
    public function index() {
    	$current_date=date('Y-m-d');
    	//Get todays approved jobs
    	$this->load->helper('download');
    	$filename = 'todays_approved_job_'.date('Ymd').'.csv';
        
        //header("Content-Description: File Transfer");
        
        //header("Content-Disposition: attachment; filename=$filename");
        
       // header("Content-Type: application/csv; ");
        
        $data['todays_jobs']=$this->Job_model->get_todays_approved_jobs($current_date);

        $filea=fopen(DIR_PROFILE_PICTURE.$filename, 'w+');

        //$file = fopen('php://output', 'w');
        $delimiter = ',';
        $header = array_keys((array)$data['todays_jobs'][0]);
        /*$header[1]="user";
        $header[5]="store";
        $header[8]="wine";*/
        fputcsv($filea, $header,$delimiter);

        foreach ($data['todays_jobs'] as $line){
        	fputcsv($filea,(array)$line,$delimiter);
        }
        fclose($filea);
        $data = file_get_contents(DIR_PROFILE_PICTURE.$filename); // Read the file's contents
        if(!empty($data['todays_jobs'][0]))
        {
            $email="rajasardar93@gmail.com";
            $name="Raja";

            $fullpath=DIR_PROFILE_PICTURE.$filename;
            $loc=$_SERVER['DOCUMENT_ROOT']."/".$fullpath;
            //echo $fullpath;die;
            $file_size = filesize($loc);
            $handle = fopen($loc, "r");
            $content = fread($handle, $file_size);
            $content = chunk_split(base64_encode($content));
            fclose($handle);
            $this->mail_template->send_approved_job_email($name,$email,$content,$fullpath);
        }

        force_download($filename, $data);
        exit;

    }
    public function move_to_archive()
    {
        $current_date=date('Y-m-d');
        $previous_date=date('Y-m-d', strtotime(" -2 months"));
        //get two month old job 
        $this->db->select('*');
        $this->db->from('job');
        $this->db->where('job.is_deleted',0);
        $this->db->where('job.ready_for_billing',1);
        $this->db->where('tasting_date >=', $previous_date);
        $this->db->where('tasting_date <=', $current_date);
        $value=$this->db->get();
        $result=$value->result_array();
        $id_container=array();
        foreach($result as $val)
        {
            array_push($id_container,$val['id']);
        }
        //Move to archive
        $this->Job_model->move_to_archive($id_container);
    }
    public function uPeCDOY7835B93dTi1LdhrN3Q()
    {
        //Get todays job
        $this->load->library('push_notifications');
        
        $this->load->model('Notifications_model');
        $todays_date=date('Y-m-d');
        $todays_job=$this->Job_model->get_todays_job($todays_date);
        if(!empty($todays_job))
        {
            foreach($todays_job as $job)
            {
                $taster_id=$job['taster_id'];
                $job_id=$job['id'];
                //get user details
                $userDetails=$this->Job_model->get_user_details($taster_id);
                
                /***
                **** Send notifications and email at 9:30 am of tasting day
                ***/
                
                //Send email to taster about the job
                $name=$userDetails->first_name;
                $email=$userDetails->email;
                //echo $email;die;
                $this->mail_template->early_job_notification_email($name,$email);
                
                //End
                //Send notifications to taster about the job
                $notifications = $this->Notifications_model->send_early_job_notifications($userDetails,$job_id);
                //end
                
                
                
                
                
            }
            
        }
        
    }
    public function a9e67025f84ce608ee8db0b0ddddb032()
    {
        /***
        **** Send notifications and email if job start time is after 12:30 pm of tasting day
        ***/
         //Get todays job
        $this->load->library('push_notifications');
        
        $this->load->model('Notifications_model');
        $todays_date=date('Y-m-d');
        $current_server_time=date("H:i:s",time());
        $next_two_hour_time = date('H:i:s', strtotime($current_server_time . ' + 2 hour'));
        $todays_job=$this->Job_model->get_todays_job($todays_date);
        
        if(!empty($todays_job))
        {
            foreach($todays_job as $job)
            {       
                $start_time=$job['start_time'];
                
                //Check job start time
                $check=$this->Job_model->check_job_between_two_hour($current_server_time,$next_two_hour_time,$start_time);
                if($check=='true')
                {
                    
                    $taster_id=$job['taster_id'];
                    $job_id=$job['id'];
                    //get user details
                    $userDetails=$this->Job_model->get_user_details($taster_id);

                    /***
                    **** Send notifications and email at every two hour of tasting day
                    ***/

                    //Send email to taster about the job
                    $name=$userDetails->first_name;
                    $email=$userDetails->email;
                    //echo $email;die;
                    $this->mail_template->job_notification_email_between_two_hour($name,$email,$start_time);

                    //End
                    //Send notifications to taster about the job
                    $notifications = $this->Notifications_model->job_notifications_between_two_hour($userDetails,$job_id,$start_time);
                    //end
                }
            }
            
        }
    }
    public function bulk_schedule_notification()
    {
        $current_date=strtotime(date('Y-m-d'));
        //Next 2 days date
        $date = strtotime("+2 day", $current_date);
        $next_date=date('Y-m-d', $date);
       // echo $next_date;die;
        //get next two days schedule 
        $this->db->select('*');
        $this->db->from('bulk_schedule_job');
        $this->db->where('bulk_schedule_job.is_deleted',0);
        $this->db->where('bulk_schedule_job.tasting_date',$next_date);
        $this->db->where('bulk_schedule_job.status','not_published');
        $value=$this->db->get();
        if($value->num_rows() > 0)
        {
            $result=$value->result_array();
            foreach($result as $value)
            {
                $job_date=$value['tasting_date'];
                $sales_rep_id=$value['user_id'];
                $admin_id=7;
                $sales_rep_details=$this->Job_model->get_user_details($sales_rep_id);
                $sales_rep_email=$sales_rep_details->email;
                $sales_rep_name=$sales_rep_details->first_name." ".$sales_rep_details->last_name;
                $admin_details=$this->Job_model->get_user_details($admin_id);
                $admin_email=$admin_details->email;
                $admin_name=$admin_details->first_name." ".$admin_details->last_name;
                
                //Send mail to sales rep
                $this->mail_template->bulk_schedule_notification_mail($sales_rep_name,$sales_rep_email,$job_date);
                
                //Send mail to admin
                $this->mail_template->bulk_schedule_notification_mail($admin_name,$admin_email,$job_date);
            }
        }
    }
    public function update_taster_rate()
    {
        $this->db->select('*');
        $this->db->from('job');
        
        $qr="(status='accepted' OR status='approved' OR status='completed' OR ready_for_billing ='1' OR is_archived ='1')";
        $this->db->where($qr);
        $result=$this->db->get();
        $jobdetails=$result->result();
        foreach($jobdetails as $value)
        {
            $job_id=$value->id;
            if($value->agency_taster_id==0)
                $taster_id=$value->taster_id;
            else
                $taster_id=$value->agency_taster_id;
            $this->db->select('created_by');
            $this->db->from('users');
            $this->db->where('users.id',$taster_id);
            $value=$this->db->get()->row();
            $created_by=$value->created_by;
            if($created_by==7)
            {
                $meta_key='rate_per_hour';
            }
            else
            {
                $meta_key='tasters_rate';
            }
            $this->db->select('meta_value');
            $this->db->from('user_meta');
           // $this->db->where('meta_key','rate_per_hour');
            $this->db->where('meta_key',$meta_key);
            $this->db->where('user_id',$taster_id);
            $user_meta=$this->db->get()->row();

            if(!empty($user_meta->meta_value))
                $rate_per_hr=$user_meta->meta_value;
            else
                $rate_per_hr=0;
            $taster_rate_array=array('taster_rate'=>$rate_per_hr);

            //Update
            $array = array('id' => $job_id);
            $this->db->where($array);
            $this->db->update('job', $taster_rate_array);  
        }
    }
    //Delete all unnessary users
    public function delete_users()
    {
        $users=array('debaleenamou@gmail.com','dragency@gmail.com','fredi@gmail.com');
        $this->db->select('id,email');
        $this->db->from('users');
        $this->db->where_in('email',$users);
        $value=$this->db->get();
        $result=$value->result_array();
        echo "<pre>";
        print_r($result);die;
    }
}