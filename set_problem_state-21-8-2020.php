<?php 
ob_start();
include('index.php');
ob_end_clean();

$CI =& get_instance();
$CI->load->database();

$current_date=date('Y-m-d');
//get current time
$current=date("g.i a", time());
$current_time=date("H:i:s", strtotime($current));
$current_time = '23:59:00';
//echo $current_time;die;


$CI->db->select("*");
$CI->db->from('job');
$CI->db->group_by('job.id'); 
$CI->db->where('job.is_deleted',0);
$CI->db->where('job.ready_for_billing',0);
$CI->db->where('job.tasting_date',$current_date);
//$CI->db->where(" (job.job_start_time > ADDTIME(job.start_time,'0:15:0'))");

/*$CI->db->where("((job.job_state = '0' AND job.end_time < '$current_time') OR (job.job_start_time > ADDTIME(job.start_time,'00:15:00')))");*/
//$CI->db->where("job.job_state = '0' AND job.end_time <= '$current_time'");
//$CI->db->where("job.job_state = '0' AND job_start_time != '00:00:00' AND job.end_time <= '$current_time'");
$CI->db->where("job_start_time != '00:00:00' AND job.end_time <= '$current_time'");
//echo "<pre>";
$job_details =$CI->db->get()->result_array();
//echo $CI->db->last_query();die;
//print_r($job_details);die;
foreach($job_details as $problem_jobs)
{
	$CI->db->where('id', $problem_jobs['id']);
	$CI->db->update('job',array('status'=>'problems','job_status'=>4)); 
}





?>