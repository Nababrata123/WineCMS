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
            $email="vj.avalgate@gmail.com";
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
        //$todays_job=$this->Job_model->get_todays_job($todays_date);
		$todays_job=$this->Job_model->get_todays_jobs($todays_date);
        if(!empty($todays_job))
        {
            foreach($todays_job as $job)
            {
                $taster_id=$job['taster_id'];
				$start_time=$job['start_time'];
				$testerJobCount = $job['tester_jobs'];
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
                $this->mail_template->early_job_notification_email($name,$email,$testerJobCount);
                
                //End
                //Send notifications to taster about the job
                $notifications = $this->Notifications_model->send_early_job_notifications($userDetails,$job_id,$testerJobCount);
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
        /* $job_id_container=array();
        $expense_id_container=array();
        $question_id_container=array();
        $tasting_setup_id_container=array();
        $users=array('rajasardar93@gmail.com');
        $this->db->select('id,email,user_type');
        $this->db->from('users');
        $this->db->where_in('email',$users);
        $value=$this->db->get();
        $result=$value->result_array();

        foreach($result as $v)
        {
            //Delete data from job table
            $user_type=$v['user_type'];
            $id=$v['id'];
            $this->db->select('id as job_id');
            $this->db->from('job');
            if($user_type=='sales_rep')
            {
                $this->db->where('job.user_id',$id);
            }
            else
            {
                $qr="(taster_id='$id' OR agency_taster_id='$id')";
                $this->db->where($qr);
                //$this->db->where('job.taster_id',$id);
            }
            $job_value=$this->db->get();
        //echo $this->db->last_query();die;
            $job_result=$job_value->result_array();
            
            foreach($job_result as $jv)
            {
                array_push($job_id_container,$jv['job_id']);
            }
            if(!empty($job_id_container))
            {
                //From job table
               
                //From job accept reject table
                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('job_accept_reject');

                //From expense details table
                $this->db->select('id as expense_id');
                $this->db->from('expense_details');
                $this->db->where_in('job_id',$job_id_container);
                $expense_value=$this->db->get();
                $expense_result=$expense_value->result_array();
                if(!empty($expense_result))
                {
                    foreach($expense_result as $er)
                    {
                        array_push($expense_id_container,$er['expense_id']);
                    }
                    //From expense details images
                    $this->db->where_in('expense_id', $expense_id_container);
                    $this->db->delete('expense_details_images');
                    $expense_id_container=array();
                }
                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('expense_details');



                //From general_notes
                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('general_notes');
                //From manager verification details
                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('manager_verification_details');
                //From question answer for job
                $this->db->select('id as question_id');
                $this->db->from('question_answer_for_job');
                $this->db->where_in('job_id',$job_id_container);
                $q_value=$this->db->get();
                $q_result=$q_value->result_array();
                if(!empty($q_result))
                {
                    foreach($q_result as $qr)
                    {
                        array_push($question_id_container,$qr['question_id']);
                    }
                    //From question answer images
                    $this->db->where_in('question_answer_id', $question_id_container);
                    $this->db->delete('question_answer_images');

                    $question_id_container=array();
                }
                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('question_answer_for_job');
                
                //From completed job wi9ne details
                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('completed_job_wine_details');

                //From confirm or unavailable wine details
                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('confirm_or_unavailable_wine');

                //From admin note for billing
                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('admin_note_for_billing');

                //From calcelled job
                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('cancelled_job');

                //From set job state
                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('set_job_state');

                //From tasting setup
                $this->db->select('id as tasting_setup_id');
                $this->db->from('tasting_setup');
                $this->db->where_in('job_id',$job_id_container);
                $t_value=$this->db->get();
                $t_result=$t_value->result_array();
                if(!empty($t_result))
                {
                    foreach($t_result as $tr)
                    {
                        array_push($tasting_setup_id_container,$tr['tasting_setup_id']);
                    }
                    //From tasting set up images
                    $this->db->where_in('tasting_setup_id', $tasting_setup_id_container);
                    $this->db->delete('tasting_setup_images');

                    $tasting_setup_id_container=array();
                }

                $this->db->where_in('job_id', $job_id_container);
                $this->db->delete('tasting_setup');

                /**
                    Finally Delete from job table
                **/
                /*$this->db->where_in('id', $job_id_container);
                $this->db->delete('job');    
                $job_id_container=array();
            }

            //Delete from users password log
            $this->db->where('user_id',$v['id']);
            $this->db->delete('user_password_log');

            //Delete from users
            $this->db->where('id',$v['id']);
            $this->db->delete('users');

            //Delete from usermeta
            $this->db->where('user_id',$v['id']);
            $this->db->delete('user_meta');

        } */
    }

    //Delete all unnessary zones
    public function delete_zones()
    {
        $zone_container=array('Andaman','Bahamas','Bangalore','Brooklyn.','Calcutta','Chennai','Hydrabad','Kerala','Kochi','Kolkata','Las-Vegas','Manhattan','Nagpur','New York','New York');
        $this->db->where_in('name', $zone_container);
        $this->db->delete('zone');
    }
    //Delete all products
    public function delete_wines()
    {
        $wine_container=array('UPC34563458976','UPC34563458976','UPC3456345234','WINE201810050500','UPC34563428189','RUM201810110542','UPC34568789','189000000067','736040011798','201810080251','UPC34563428117','123456789125','UPC 08224229493');
        $this->db->where_in('upc_code', $wine_container);
        $this->db->delete('wine');
    }

    //Delete store
    public function delete_store()
    {
        $store_container=array('Beach Daisy','Big Country Liquors','Cheers Wine and Spirits','Corkscrew Wines','Golden Kru','Grand Kru','High Land Bar','High Street Bar','House Of Spirit','Liquid Empire','Liquorland','Martini Glass Liquors','New store for test','Orchid FL SHOP','Pick-Me-Up Liquors','Pompette Wine Shop','Rupasi Bangla F L Off Counter','Silver Sand','Streetlights Liquor Shop','The Irish House','The Liquor Store','The Wine Shop','Topshelf Wines','Uptown Liquors','VIP WINES','Wine Parlour','Wine Plaza','WINE RESIDENCY','Wine Two');

        $this->db->select('store.logo');
        $this->db->from('store');
        $this->db->where_in('name',$store_container);
        $value=$this->db->get();
        $result=$value->result_array();
        foreach($result as $s)
        {
            $this->load->helper("url");
            $logo=$s['logo'];
            if($logo!='')
            {
                $logo_path=BASE_URL.DIR_STORE_LOGO.$logo;
                $logo_thumb_path=BASE_URL.DIR_STORE_LOGO_THUMB.$logo;
                unlink($logo_path);
                unlink($logo_thumb_path);
            }
        }
        $this->db->where_in('name', $store_container);
        $this->db->delete('store');
    }
	public function twoMonthJobPublishFromBulkschedule(){
		//load bulk_schedule_job_model
		$this->load->model('Bulk_schedule_job_model');
		//calculate next two month tasting date
		$date = date('Y-m-d', strtotime("+60 days"));
		//get all two month jobs
		$this->db->select("*");
        $this->db->from('bulk_schedule_job');
		$this->db->where("taster_id !=","");
		$this->db->where("wine_id !=","");
		$this->db->where('status','not_published');
		$this->db->where('is_deleted',0);
		$this->db->where('tasting_date', $date);
		$data=$this->db->get();
        if($data->num_rows() > 0){
            $jobs=$data->result_array();
            foreach($jobs as $job){
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
						$this->mail_template->assigned_job_email($name,$email);
						
					}
				}
			}
		}
	}

}