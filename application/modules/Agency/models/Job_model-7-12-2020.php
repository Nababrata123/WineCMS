<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_model extends CI_Model {
    protected $current_level, $level;
    
    
    //Get all Jobs from admin end
    public function get_job_list($filter = array(), $order = null, $dir = null, $count = false) {
        $logged_in_agency_id=$this->session->userdata('id');
        $this->db->select("job.*,store.name as store_name, store.adress as address, store.state as state, store.city as city, store.zipcode as zipcode, CONCAT(u1.last_name,' ',u1.first_name) as SalesRepName, CONCAT(u2.last_name,' ',u2.first_name) as TasterName, CONCAT(u3.last_name,' ',u3.first_name) as AgencyTasterName");
        $this->db->from('job');
        $this->db->join('store','job.store_id=store.id','left');
        $this->db->join('users as u1', 'u1.id = job.user_id', 'left');
        $this->db->join('users as u2', 'u2.id = job.taster_id', 'left');
        $this->db->join('users as u3', 'u3.id = job.agency_taster_id', 'left');
        $this->db->order_by('job.id','DESC');
        $this->db->where('job.is_deleted',0);
         if (isset($filter['tasting_date']) && $filter['tasting_date'] != "") {
            $this->db->where('job.tasting_date', $filter['tasting_date']);
        }
        if (isset($filter['taster']) && $filter['taster'] != "") {
            $taster_id=$filter['taster'];
            //$this->db->where('job.tasting_date', $filter['tasting_date']);
            $this->db->where("(job.agency_taster_id LIKE '%$taster_id%')");
        }
        if (isset($filter['search_text']) && $filter['search_text'] != "~" && $filter['search_text'] != "") {
            $searchText=urldecode($filter['search_text']);
            $this->db->where("((store.name LIKE '%$searchText%') OR (store.adress LIKE '%$searchText%') OR (store.state LIKE '%$searchText%') OR (store.city LIKE '%$searchText%') OR (store.zipcode LIKE '%$searchText%') OR (CONCAT(u1.last_name,' ',u1.first_name) LIKE '%$searchText%') OR (CONCAT(u2.last_name,' ',u2.first_name) LIKE '%$searchText%') OR (CONCAT(u3.last_name,' ',u3.first_name) LIKE '%$searchText%') )");
        }
        $qr="(job.job_status='2' OR job.job_status='3' OR job.job_status='4')";
        $this->db->where($qr);
        //$this->db->where('job.job_status',2);
        $this->db->where("(taster_id LIKE '%$logged_in_agency_id%')");
        //$this->db->where('taster_id',$logged_in_agency_id);
        if ($count) {
            return $this->db->count_all_results();
        }
        if ( (isset($filter['limit']) && $filter['limit'] > 0) && (isset($filter['offset']) ) ) {
            $this->db->limit($filter['limit'], $filter['offset']);
        }
        if ($order <> null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('updated_on ASC');
        }
        $this->db->group_by("job.id");
        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        $arr=$query->result();
        
        return $arr;
    }
    
    function count_job($tablename,$status)
    {
        $this->db->select('id');
        $this->db->from($tablename);
        if($status==1)
        {
            $this->db->where('job_status',$status);
        }
        if($status==2)
        {
            $this->db->where('job.job_status',2);
            //$this->db->where('job.status','pending');
        }
        if($status==3)
        {
            $this->db->where("job.job_status",3);
            //$this->db->where('job.accept_status',1);
            //$this->db->where('job.status','approved');
        }
        if($status==4)
        {
            $this->db->where("job.job_status",4);
        }
        $this->db->where('is_deleted',0);
        $result=$this->db->get();
        return $result->num_rows();
    }
    
    function get_user_name($id)
    {
        $this->db->select("CONCAT(last_name, ' ',first_name) as sales_rep_name");
        $this->db->from('users');
        $this->db->where('id',$id);
        $result=$this->db->get();
        $value=$result->row();
        if(!empty($value))
            return $value->sales_rep_name;
    }
    function check_job_accept_no($tablename,$job_id)
    {
        $this->db->select('*');
        $this->db->from($tablename);
        $this->db->group_by('accepted_by');
        $this->db->where('job_id',$job_id);
        $this->db->where('rejected_by',0);
        $result=$this->db->get();
        $accepted_no=$result->num_rows();
		//echo $this->db->last_query()."<br>";die;
        return $accepted_no;
    }
    function accept_or_reject($tablename,$job_id,$accept,$accepted_or_rejected_by)
    {
        $data=array();
        if($accept==1)
        {
            $data['accept_status']=1;
            //$data['status']='accepted';
			$data['status']='approved';
            $data['job_status']=3;
			$data['taster_id']=$accepted_or_rejected_by;
        }
        else
        {
            $data['accept_status']=0;
        }
        $accept_array=array(
            'job_id'=>$job_id,
        );
        if($accept==1)
        {
            $accept_array['accepted_by']=$accepted_or_rejected_by;
        }
        else
        {
            $accept_array['rejected_by']=$accepted_or_rejected_by;
        }
        
        
        $accept_array['date']=date("Y-m-d");
		//print_r($accept_array);
		//print_r($data);die;
        $array = array('id' => $job_id);
        $this->db->where($array);
        if ($this->db->update($tablename, $data)) {
            if ($this->db->insert('job_accept_reject', $accept_array)) {
                return $this->db->insert_id();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    function accept_reject_status($job_id)
    {
        //Get assigned taster id from job
            $this->db->select('taster_id');
            $this->db->from('job');
            $this->db->where('id',$job_id);
            $result=$this->db->get()->row();
            $taster_id=$result->taster_id;
            $taster_id_array=explode(",",$taster_id);
            //Get accept or reject status for the job
            $this->db->select('rejected_by');
            $this->db->from('job_accept_reject');
             $this->db->group_by('rejected_by'); 
            $this->db->where('rejected_by <>',0);
            $this->db->where('accepted_by',0);
            $this->db->where('job_id',$job_id);
            $v=$this->db->get()->result_array();
            $accept_reject_string='';
            foreach($v as $val)
            {
                $accept_reject_string.=$val['rejected_by'].",";
            }
            $accept_reject_string=rtrim($accept_reject_string,",");
            $accept_reject_array=explode(",",$accept_reject_string);
            sort($taster_id_array);
            sort($accept_reject_array);
            if($taster_id_array==$accept_reject_array)
            {
                $flag=0;
                $this->db->delete('job_accept_reject', array('job_id' => $job_id));
            }
            else
            {
                $flag=1;
            }
            return $flag;
    }
    function check_approval_status($job_id)
    {
        $this->db->select('taster_id,status');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $value=$this->db->get();
        $result=$value->row();
        if($result->taster_id==$this->session->userdata('id'))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
	
    function get_tester($user_id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('created_by',$user_id);
        $this->db->where('status','active');
		$this->db->where('is_deleted',0);
        $this->db->order_by('users.last_name','ASC');
        $value=$this->db->get();
        
        return $value->result_array();
    }
    function assign_tester($tablename,$array,$job_id)
    {
        $this->db->where('id',$job_id);
        
        $this->db->update($tablename,$array);
        return $this->db->affected_rows();
    }
    function get_more_job_info($job_id)
    {
        //echo $job_id;die;
        $this->db->select("DATE(job.tasting_date) as sampling_date, CONCAT_WS(' ',ua.last_name,ua.first_name) as taster_name, store.name as store_name, store.zipcode as store_zipcode, store.adress as address, job.wine_id as wine, job.start_time, job.end_time, job.working_hour, job.taster_id, job.agency_taster_id, job.taster_rate, job.job_start_time as actual_start_time, job.finish_time as actual_end_time, CONCAT_WS(' ',ub.last_name,ub.first_name) as sales_rep_name");
        $this->db->from('job');
        $this->db->join('users ua','job.taster_id=ua.id','left');
        $this->db->join('store','job.store_id=store.id','left');
        $this->db->join('users ub','job.user_id=ub.id','left');
        $this->db->where('job.id',$job_id);
        $result=$this->db->get()->row();
        //Get sampling details
        $this->db->select('wine.name,completed_job_wine_details.bottles_sampled,bottles_sold');
        $this->db->from('completed_job_wine_details');
        $this->db->join('wine','completed_job_wine_details.wine_id=wine.id');
        $this->db->where('completed_job_wine_details.job_id',$job_id);
        $result_wine=$this->db->get()->result_array();
        $result->wine_sampled_details=$result_wine;

        /*if($result->agency_taster_id==0)
            $taster_id=$result->taster_id;
        else
            $taster_id=$result->agency_taster_id;
        
        $user_info=$this->get_user_type('users',$result->taster_id);
        
        $user_type_1=$user_info[0]->user_type;
        //echo $user_type_1;die;
        if($user_type_1=='agency')
        {
            $agency_name=$this->get_agency_name('user_meta',$result->taster_id);
            //echo $agency_name;die;
            
                $result->taster_name=$agency_name;
        }*/
        if($result->agency_taster_id==0)
        {
            $taster_id='';
            $result->taster_name='Not assigned';
        }
        else
        {
            $taster_id=$result->agency_taster_id;
            $result->taster_name=$this->get_user_name($taster_id);
        }

        //Calculate total amount
        /*$this->db->select('meta_value');
        $this->db->from('user_meta');
        $this->db->where('meta_key','rate_per_hour');
        $this->db->where('user_id',$taster_id);
        $user_meta=$this->db->get()->row();
        if($user_meta)
        {
            $rate_per_hr=$user_meta->meta_value;
            $result->rate_per_hr=$rate_per_hr;
        }
        else
        {
            $rate_per_hr=0;
        }*/
        $rate_per_hr=$result->taster_rate;
        $result->rate_per_hr=$rate_per_hr;

        //Calculate job actual time difference
        $difference=strtotime($result->end_time)-strtotime($result->start_time);
        $actual_time_difference=gmdate("H:i", $difference);
        //Convert working hour to minutes
        $time = explode(':', $result->working_hour);
        $total_minutes= ($time[0]*60) + ($time[1]) + ($time[2]/60);
        if($rate_per_hr)
        {
            $total_amount=number_format((($rate_per_hr / 60)*$total_minutes),2);
        }
        else
        {
            $total_amount=0;
        }
        $result->total_amount="$".$total_amount;
        $result->actual_time_difference=$actual_time_difference;
        //Calculate expense amount
        //$this->db->select('exp_amount');
        $this->db->select('expense_details.id as exp_id,exp_amount,exp_reason');
        $this->db->from('expense_details');
        $this->db->where('job_id',$job_id);
        $expense_details=$this->db->get()->row();
        if($expense_details)
            $expense_amount=$expense_details->exp_amount;
        else
            $expense_amount=0;
        $result->expense_amount=$expense_amount;
        if($result->actual_start_time !='00:00:00' && $result->actual_end_time !='00:00:00'){

            $expense_reason=$expense_details->exp_reason;
            $result->exp_reason=$expense_reason;
        
             //Get expense images
             $this->db->select('images as exp_images');
             $this->db->from('expense_details_images');
             $this->db->where('expense_id',$expense_details->exp_id);
             $image=$this->db->get()->result_array();
             $result->expense_images=$image;

        }else{
            $result->exp_reason = "N/A";
        }

        //Get general note
        $general_note=$this->get_general_note($job_id);
        $result->general_note=$general_note;
        //echo "<pre>";
        //print_r($result);die;
        $wine_id=$result->wine;
        $wine_id_array=explode(",",$wine_id);
        //Get wine
        $this->db->select('name');
        $this->db->from('wine');
        $this->db->where_in('id',$wine_id_array);
        $query = $this->db->get();
        $wine_array=$query->result_array();
        $wine_name='';
        foreach($wine_array as $v)
        {
            $wine_name.=$v['name'].",";
        }
        $wine_name=rtrim($wine_name,",");
        $result->wine=$wine_name;
        return $result;
    }
    function get_user_type($table_name,$user_id)
    {
        $this->db->select('user_type,created_by');
        $this->db->from($table_name);
        $this->db->where('users.id',$user_id);
        $user_role=$this->db->get();
        return $user_role->result();
    }
    //Get agency name
    function get_agency_name($table_name,$user_id)
    {
        $this->db->select('meta_value');
        $this->db->from($table_name);
        $this->db->where('user_id',$user_id);
        $this->db->where('meta_key','agency_name');
        $agency_name=$this->db->get()->row('meta_value');
        return $agency_name;
    }
    function check_tester_availablity($tablename,$taster_id,$tasting_date,$start_time,$end_time)
    {
		$this->db->select('COUNT(*) as count');
		//$this->db->select('*');
		$this->db->from($tablename);
		$this->db->where('tasting_date',$tasting_date);
		$this->db->where('is_deleted',0);
		$this->db->where('status !=','completed');
		//$this->db->where('start_time',$start_time);
		//$this->db->where('end_time',$end_time);
		/*$qr = " ( (`start_time` <= '".$start_time."' AND `end_time` >= '".$end_time."') OR (`start_time` >= '".$start_time."'  AND `start_time` <= '".$start_time."' AND `end_time` <= '".$end_time."') OR (`end_time` <= '".$end_time."' AND `end_time` >= '".$start_time."'  AND `start_time` <= '".$start_time."' ) OR (`start_time` >= '".$start_time."'  AND `start_time` <= '".$start_time."') OR (`start_time` > '".$start_time."'  AND `end_time` < '".$end_time."') OR (`start_time` < '".$start_time."'  AND `end_time` < '".$end_time."') OR (`end_time` <= '".$start_time."'))";
		$this->db->where($qr);*/

		//First phase of checking
		$where_1 = " ( (`start_time` <= '".$start_time."' AND `end_time` >= '".$end_time."')
		OR (`start_time` >= '".$start_time."'  AND `start_time` <= '".$end_time."' AND `end_time` <= '".$end_time."')  
		OR (`end_time` <= '".$end_time."' AND `end_time` >= '".$start_time."'  AND `start_time` <= '".$start_time."' )  
		OR (start_time >= '".$start_time."'  AND start_time <= '".$end_time."') )";
		$this->db->where($where_1);

		//Second phase of checking

		/*$where_12 = " ((start_time BETWEEN '".$start_time."' AND '".$end_time."') OR (end_time BETWEEN '".$start_time."' AND '".$end_time."'))";
			$this->db->where($where_12);*/


		//$this->db->like('taster_id',$taster_id,'both');
		$this->db->where('agency_taster_id',$taster_id);
		//$result=$this->db->get();
	   
		$result=$this->db->get()->row();
		//echo $this->db->last_query();die;
		return $result->count;
    }
    
    function get_general_note($job_id)
    {
        $this->db->select('general_note');
        $this->db->from('general_notes');
        $this->db->where('job_id',$job_id);
        $user_role=$this->db->get()->row('general_note');
        return $user_role;
    }
}
/* End of file user_model.php */
/* Location: ./application/models/user_model.php */