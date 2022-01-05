<?php
class Notifications_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	## --- Method to create / edit notifications -- ##
	
	public function get_notification_details_for_completed_job($job_id,$taster_id)
	{
		//Select sales rep id
		$this->db->select('user_id');
		$this->db->from('job');
		$this->db->where('job.id',$job_id);
		$result=$this->db->get()->row();

		$sales_rep_id=$result->user_id;
		$this->db->select('users.device_type, users.device_token');
		$this->db->from('users');
		$this->db->where('users.id', $sales_rep_id);
		$details=$this->db->get()->row();

		//Get tester details
		$this->db->select("CONCAT(first_name, ' ',last_name) as taster_name");
		$this->db->from('users');
		$this->db->where('users.id', $taster_id);
		$taster_details=$this->db->get()->row();

		$details->taster_name=$taster_details->taster_name;
		return $details;

	}
	public function send_notifications_for_completed_job($user_details)
	{
		//echo "<pre>";
		//print_r($user_details);die;
		$taster_name=$user_details->taster_name;
		$body="A job has been completed by ".$taster_name;
		$array=array("title"=>"Job Completed","body"=>$body);

		$res = $this->push_notifications->test_notification($user_details,$array);
		if($res)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function send_notifications_for_approved_job($accepted_tester_id,$job_id)
	{
		$this->db->select('users.device_type, users.device_token');
		$this->db->from('users');
		$this->db->where('users.id', $accepted_tester_id);
		$user_details=$this->db->get()->row();
		$body="A job has been confirmed";
		$array=array("title"=>"Job Confirmed","body"=>$body,"job_id"=>$job_id);

		$res = $this->push_notifications->send_approve_job_notification($user_details,$array);
		if($res)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function send_notifications_for_cancelled_job($pre_taster,$job_id)
	{
		$this->db->select('users.device_type, users.device_token');
		$this->db->from('users');
		$this->db->where('users.id', $pre_taster);
		$user_details=$this->db->get()->row();
		$body="A job has been cancelled by the admin";
		$array=array("title"=>"Job Cancelled","body"=>$body,"job_id"=>$job_id);

		$res = $this->push_notifications->send_cancelled_job_notification($user_details,$array);
		if($res)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function send_notifications_for_change_info($taster_id,$job_id)
	{
		$this->db->select('users.device_type, users.device_token');
		$this->db->from('users');
		$this->db->where('users.id', $taster_id);
		$user_details=$this->db->get()->row();
		$body="A job details has been changed";
		$array=array("title"=>"Job details changed","body"=>$body,"job_id"=>$job_id);

		$res = $this->push_notifications->send_job_info_notification($user_details,$array);
		if($res)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function send_notifications_for_republish_job($taster_id,$job_id)
	{
		/*$taster_id_array=explode(",",$taster_id);
		//echo "<pre>";
		//print_r($taster_id_array);die;
		foreach($taster_id_array as $id)
		{*/
			$this->db->select('users.device_type, users.device_token');
			$this->db->from('users');
			$this->db->where('users.id', $taster_id);
			$user_details=$this->db->get()->row();
			$body="A job has been published";
			$array=array("title"=>"Job Published","body"=>$body,"job_id"=>$job_id);

			$res = $this->push_notifications->send_republish_job_notification($user_details,$array);
			if($res)
			{
				return true;
			}
			else
			{
				return false;
			}
		//}
	}
	public function send_notifications_for_publish_job($zone_details,$job_id)
	{

		$body="A job has been published";
		$array=array("title"=>"Job Published","body"=>$body,"job_id"=>$job_id);

		$res = $this->push_notifications->send_publish_job_notification($zone_details,$array);
		if($res)
		{
			return true;
		}
		else
		{
			return false;
		}
	}	
	public function send_notifications_for_start_or_finish_job($taster_id)
	{
		$this->db->select('users.device_type, users.device_token');
		$this->db->from('users');
		$this->db->where('users.id', $taster_id);
		$user_details=$this->db->get()->row();
		$body="You had started late jobs / finished early 3 times in a row!";
		$array=array("title"=>"Job late start warning","body"=>$body,"taster_id"=>$taster_id);

		$res = $this->push_notifications->send_start_or_finish_notification($user_details,$array);
		if($res)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	
}


?>