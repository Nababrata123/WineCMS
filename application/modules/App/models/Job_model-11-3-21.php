<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_model extends CI_Model {
    protected $current_level, $level;
    function create_job($tablename, $data) {
        if ($this->db->insert($tablename, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    function update_job($tablename, $field_name, $field_value, $data) 
    {
        //echo "<pre>";
        //echo $tablename.$field_name."<br>".$field_value;

       // print_r($data);die;
        $this->db->where($field_name, $field_value);
        if($this->db->update($tablename, $data)) {
            return true;
        } else {
            return false;
        }
    }
    function update_data($tablename, $field_name, $field_value, $data) 
    {
        //echo "<pre>";
        //echo $tablename.$field_name."<br>".$field_value;

       // print_r($data);die;
        $this->db->where($field_name, $field_value);
        if($this->db->update($tablename, $data)) {
            return $field_value;
        } else {
            return false;
        }
    }
    function check_job_earlier_or_later($taster_id)
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from('job');
        $this->db->where('is_deleted',0);
        $this->db->where('taster_id',$taster_id);
        $qr="((job.start_time < job.job_start_time) OR (job.end_time > job.end_time))";
        $value=$this->db->get();
        return $value->num_rows();
    }
    function delete_job($tablename, $user_id,$job_id)
    {
        if ($user_id && $job_id) {
            $data = array(
               'is_deleted' => 1,
            );
             $this->db->where('id',$job_id);
             $this->db->where('user_id',$user_id);
            $this->db->update($tablename,$data);
            return $this->db->affected_rows();;
        } else {
            return false;
        }
    }
   
    function submit_admin_note($job_id,$array)
    {
        $this->db->insert('admin_note_for_billing',$array);
        $insert_id=$this->db->insert_id();
        //Update job billing status
        $data = array(
               'ready_for_billing'=>1,
               'status'=>'completed',
               'job_state'=> 2
            );
        $this->db->where('id',$job_id);
        $this->db->update('job',$data);
        return $insert_id;

    }
    function move_to_billing($job_id,$job_start_time,$job_end_time,$working_hour)
    {

        $data = array(
            'ready_for_billing' => 1,
            'status'=>'completed',
            'job_state'=> 2 ,
            'job_status'=> 3,
            'job_start_time'=>$job_start_time,
            'finish_time'=>$job_end_time,
            'working_hour'=>$working_hour
        );
        $this->db->where('id',$job_id);
        $this->db->update('job',$data);

        $qr="UPDATE `job` set `job_state` = '2' where
        `id` = '$job_id'";
        $this->db->query($qr);
        return $this->db->affected_rows();
    }
    function get_billing_information($taster_id,$month_number,$year)
    {
        //Check taster is agencies taster or not
        $record=$this->get_user_type('users',$taster_id);
        //echo "<pre>";
        //print_r($record);die;
        $created_by=$record[0]->created_by;
        $this->db->select("job.id as job_id,job.working_hour,job.taster_rate,job.start_time as job_scheduled_start_time,job.end_time as job_scheduled_end_time,job.job_start_time as job_actual_start_time,job.finish_time as job_actual_end_time,DATE_FORMAT(job.tasting_date,'%d-%m-%Y') as tasting_date,job.store_id as store_id,store.name as store_name");
        $this->db->from('job');
        $this->db->join('store','job.store_id=store.id');
        $this->db->where('job.status','completed');
        $this->db->where('job.is_deleted',0);
        if($created_by==7)
        {
            $this->db->where('job.taster_id',$taster_id);
        }
        else
        {
            $this->db->where('job.agency_taster_id',$taster_id);
        }
        $value=$this->db->get();
        $result=$value->result_array();
        //echo count($result);die;
        for($i=0;$i<count($result);$i++)
        {
            $job_id=$result[$i]['job_id'];
            //Get expense Details
            $this->db->select("DATE_FORMAT(expense_details.date,'%d-%m-%Y') as billing_date,expense_details.exp_amount as billing_amount");
            $this->db->from('expense_details');
            $this->db->where('job_id',$job_id);
            if($taster_id)
            {
                $this->db->where('expense_details.taster_id',$taster_id);
                
            }
            if($month_number)
            {
                $this->db->where('MONTH(date)', $month_number);
            }
            if($year)
            {
                $this->db->where('YEAR(date)', $year );
            }
            $expense_value=$this->db->get();
            $expense_result=$expense_value->row();
            if(!empty($expense_result))
            {
                $time = explode(':',$result[$i]['working_hour']);
                $total_minutes= ($time[0]*60) + ($time[1]) + ($time[2]/60);
                
                $rate_per_hr=$result[$i]['taster_rate'];
                $exp_amount=ltrim($expense_result->billing_amount, '$'); 
                $total_amount=number_format((($rate_per_hr / 60)*$total_minutes),2)+$exp_amount;
                $result[$i]['billing_date']=$expense_result->billing_date;
                //$result[$i]['billing_amount']=$expense_result->billing_amount;
                $result[$i]['billing_amount']="$".$total_amount;
            }
            else
            {
                $result[$i]['billing_date']='';
                $result[$i]['billing_amount']='';
            }
            //Get expense details of the Job
            
            $expense=$this->get_expense_details($job_id);
            $result[$i]['expense_details']=$expense;
            $result[$i]['expense_image_path']=BASE_URL.DIR_EXPENSE_IMAGE;
           
        }
        
        
        //$this->db->where('job.status','completed');
        //$value=$this->db->get();
        //$result=$value->result_array();
        //echo count($result);die;
        return $result;

    }
    function get_joblist($user_id)
    {
        //check user type
        $user_info=$this->get_user_type('users',$user_id);
        $user_type=$user_info[0]->user_type;
        $user_created_by=$user_info[0]->created_by;
        $this->db->select("job.id as job_id,job.status,job.job_state,DATE_FORMAT(job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as start_time,TIME_FORMAT(end_time, '%h:%i%p') as end_time,store.name as store_name,store.adress as address,store.suite_number as suite_number,store.appartment_number as appartment_number");
        $this->db->from('job');
        $this->db->join('store','job.store_id=store.id');
        if($user_type=='sales_rep')
        {
            $this->db->where('job.user_id',$user_id);
        }
        else 
        {
            //Check created by
            //$created_by=$this->get_user_created_by('users',$user_id);
            //get user type
            //$user_role=$this->get_user_role('users',$user_created_by);die;

            if($user_created_by==7)
            {
                $this->db->like('job.taster_id',$user_id);
                //$this->db->where('job.job_status <>',1);
            }
            else 
            {
                $this->db->where('job.agency_taster_id',$user_id);
                //$this->db->where('job.job_status <>',1);
            }
            
        }

        $this->db->where('job.is_deleted',0);
        $this->db->where('job.is_archived',0);
        //$qr="(job.status='assigned' OR job.status='accepted')";
        //$this->db->where($qr);
        $result=$this->db->get();
        $value=$result->result_array();
        for($i=0;$i<count($value);$i++)
        {
            if($user_type=='sales_rep')
            {
                $value[$i]['rejected_by_taster']='no';
            }
            else
            {
                $job_id=$value[$i]['job_id'];
                //check the job is rejected by tsater or not
                $this->db->select('*');
                $this->db->from('job_accept_reject');
                $this->db->where('job_id',$job_id);
                $this->db->where('rejected_by',$user_id);
                $v=$this->db->get();
                $number=$v->num_rows();
                if($number > 0)
                {
                    $value[$i]['rejected_by_taster']='yes';
                }
                else
                {
                    $value[$i]['rejected_by_taster']='no';
                }
            }
        }
        return $value;
    }
    function get_user_details($id)
    {
        $id = (int) $id;

        $this->db->select('users.id as user_id,  users.first_name, users.last_name, users.email,users.device_token');
        $this->db->from('users');
        
        $this->db->join('users AS users_created', 'users.created_by = users_created.id', 'left');
        $this->db->join('users AS users_updated', 'users.updated_by = users_updated.id', 'left');
        $this->db->where('users.id', $id);

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        $result = $query->row();

        //$result->meta = $this->get_user_meta($id);

        return $result;
    }
    function get_user_meta($user_id) {
        $user_id = (int) $user_id;

        $this->db->from('user_meta');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();
        return $query->result();
    }
    //Get user role
    function get_user_type($table_name,$user_id)
    {
        $this->db->select('user_type,created_by');
        $this->db->from($table_name);
        $this->db->where('users.id',$user_id);
        $user_role=$this->db->get();
        return $user_role->result();
    }
    //Get user created by
    /*function get_user_created_by($table_name,$user_id)
    {
        //echo "hello";die;
        $this->db->select('created_by');
        $this->db->from($table_name);
        $this->db->where('users.id',$user_id);

        $id=$this->db->get();
        echo $this->db->last_query();die;
        //$created_by=$created_by->row('created_by');

        return $created_by;
    }*/
    function get_jobdetails($user_id,$job_id)
    {
        if($user_id!='')
        {
            $this->db->select("job.id as job_id, job.taster_id as taster_id, job.agency_taster_id as agency_tester_id,job.endtime_state as is_job_start, job.status,DATE_FORMAT(job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as start_time,TIME_FORMAT(end_time, '%h:%i%p') as end_time,store.id as store_id,store.name as store_name,store.adress as address,store.suite_number as suite_and_apt,store.phone as store_phone,store.latitude as store_latitude,store.longitude as store_longitude,zone.id as zone_id,CONCAT(last_name, ' ',first_name) as sales_rep_name,users.email as sales_rep_email, user_meta.meta_value as sales_rep_phone,job.taster_note");
        }
        else
        {
            $this->db->select("job.id as job_id, job.taster_id as taster_id, job.agency_taster_id as agency_tester_id, job.endtime_state as is_job_start, job.status,DATE_FORMAT(job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as start_time,TIME_FORMAT(end_time, '%h:%i%p') as end_time,store.id as store_id,store.name as store_name,store.adress as address,store.suite_number as suite_and_apt,store.phone as store_phone,store.latitude as store_latitude,store.longitude as store_longitude,zone.id as zone_id,job.admin_note,job.taster_id,CONCAT(last_name, ' ',first_name) as taster_name,job.taster_note, job.job_state as job_state ");
        }
        $this->db->from('job');
        $this->db->join('store','job.store_id=store.id');
        $this->db->join('zone','store.zone=zone.id');
        if($user_id!='')
        {
            $this->db->join('users','job.user_id=users.id');
            $this->db->join('user_meta','job.user_id=user_meta.user_id');
            $this->db->where('user_meta.meta_key','phone');
        }
        else
        {
            $this->db->join('users','job.taster_id=users.id');
        }
        //$this->db->join('users','job.taster_id=users.id');
        $this->db->where('job.id',$job_id);
        $this->db->where('job.is_deleted',0);
        $qr="(is_archived='0' OR is_archived='1')";
        $this->db->where($qr);
        $result=$this->db->get();
        $jobdetails=$result->result_array();
        //get wine id
        $this->db->select('wine_id');
        $this->db->from('job');
        $this->db->where('job.id',$job_id);
        $wine_id=$this->db->get()->row('wine_id');
        $wine_id_array=explode(",",$wine_id);
        //get wine details
        $this->db->select("wine.id as id,wine.name as name,wine.description,UPPER(wine.flavour)  as type,wine.company_type,wine.upc_code as upc,wine.brand,wine.year,CONCAT(wine.size,' ','ml')as size,wine.category_id");
        $this->db->from('wine');
        $this->db->where_in('id',$wine_id_array);
        $query = $this->db->get();
        $wine_array=$query->result_array();
        for($i=0;$i<count($wine_array);$i++)
        {
            //array_push($wine_array_id_container,$val['id']);
            //get wine images
            $this->db->select('image');
            $this->db->from('wine_images');
            $this->db->where('is_deleted',0);
            $this->db->where('wine_id',$wine_array[$i]['id']);
            $query_image=$this->db->get();
            $wine_image_array=$query_image->result_array();
            //echo "<pre>";
            //print_r($wine_image_array);die;
            $image_name='';
            for($j=0;$j<count($wine_image_array);$j++)
            {
                $image_name.=$wine_image_array[$j]['image'].",";
            }
            $image_name=rtrim($image_name,",");
            //echo "<pre>";
            //print_r($wine_image_array);die;
            $wine_array[$i]['images']=$image_name;
            $image_name='';
            if($user_id!='')
            {
                //get user role
                $user_role=$this->get_user_role('users',$user_id);
                //Get confirm Status
                $confirm_status=$this->get_confirm_status('confirm_or_unavailable_wine',$user_id,$job_id,$wine_array[$i]['id']);
                if($user_role==3)
                {
                    $wine_array[$i]['set_by']='Sales Rep';
                    $wine_array[$i]['confirm_status']=$confirm_status;
                }
                if($user_role==4)
                {
                    $wine_array[$i]['set_by']='Tester';
                    $wine_array[$i]['confirm_status']=$confirm_status;
                }
            }
        }
        $jobdetails[0]['wine_details']=$wine_array;
        return $jobdetails;
    }
    function get_confirm_status($tablename,$user_id,$job_id,$wine_id)
    {
        $this->db->select('confirm_status');
        $this->db->from($tablename);
        $this->db->where('taster_id',$user_id);
        $this->db->where('job_id',$job_id);
        $this->db->where('wine_id',$wine_id);
        $value=$this->db->get();
        if($value->num_rows() > 0)
        {
            $confirm_status=$value->row('confirm_status');
        }
        else
        {
            $this->db->select('wine_confirm_status');
            $this->db->from('job');
            $this->db->where('id',$job_id);
            $result=$this->db->get();
            $confirm_status=$result->row('wine_confirm_status');
        }
        return $confirm_status;
    }
    //Submit general notes for completed job
    function submit_general_notes($tablename, $data) {
        $this->db->where('job_id', $data['job_id']);
        $this->db->delete('general_notes');
        if ($this->db->insert($tablename, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    function get_general_note($job_id)
    {
        $this->db->select('general_note');
        $this->db->from('general_notes');
        $this->db->where('job_id',$job_id);
        $user_role=$this->db->get()->row('general_note');
        return $user_role;
    }
    function get_admin_note($job_id)
    {
        $this->db->select('admin_note');
        $this->db->from('admin_note_for_billing');
        $this->db->where('job_id',$job_id);
        $user_role=$this->db->get()->row('admin_note');
        return $user_role;
    }
    //Get user role
    function get_user_role($table_name,$user_id)
    {
        $this->db->select('role_id');
        $this->db->from($table_name);
        $this->db->where('users.id',$user_id);
        $user_role=$this->db->get()->row('role_id');
        return $user_role;
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
    //Get all Jobs from admin end
    public function get_job_list($filter = array(), $order = null, $dir = null, $count = false) {
        // echo "<pre>";
        // print_r($filter);die;
        $this->db->select("job.*,store.name as store_name, store.adress as address, store.state as state, store.city as city, store.zipcode as zipcode,  CONCAT(u1.last_name,' ',u1.first_name) as SalesRepName, CONCAT(u2.last_name,' ',u2.first_name) as TasterName, CONCAT(u3.last_name,' ',u3.first_name) as AgencyTasterName, job_rating.rating as rating");
        $this->db->from('job');
        $this->db->join('store','job.store_id=store.id','left');
        $this->db->join('users as u1', 'u1.id = job.user_id', 'left');
        $this->db->join('users as u2', 'u2.id = job.taster_id', 'left');
        $this->db->join('users as u3', 'u3.id = job.agency_taster_id', 'left');
        $this->db->join('job_rating', 'job_rating.job_id = job.id', 'left');
        if (isset($filter['search_text']) && $filter['search_text'] != "~" && $filter['search_text'] != "") {
            $this->db->join('user_meta agency_name','u2.id=agency_name.user_id', 'left outer');
        }
        
        
        
        //$this->db->order_by('job.id','DESC');
        // $this->db->order_by('job.tasting_date','DESC');
        
        $this->db->where('job.is_deleted',0);
       // $this->db->where('job.job_status',1);
        if (isset($filter['tasting_date']) && $filter['tasting_date'] != "~" && $filter['tasting_date']!="") {
            $this->db->where('job.tasting_date', $filter['tasting_date']);
        }
        if (isset($filter['taster']) && $filter['taster'] != "~" && $filter['taster'] !="") {
            $taster_id=$filter['taster'];
            $taster_id_array=explode("@",$taster_id);
           
            $this->db->where_in('job.taster_id', $taster_id_array);
        }
        if (isset($filter['store']) && $filter['store'] != "~" && $filter['store'] != "") {
            
            $this->db->where('job.store_id', $filter['store']);
            
        }
        if (isset($filter['sales_rep']) && $filter['sales_rep'] != "~" && $filter['sales_rep'] != "") {
            
            $this->db->where('job.user_id', $filter['sales_rep']);
            
        }
        if (isset($filter['search_by_rating']) && $filter['search_by_rating'] != "~" && $filter['search_by_rating'] != "") {
            
            $this->db->where('job_rating.rating', $filter['search_by_rating']);
            
        }
        if (isset($filter['search_by_status']) && $filter['search_by_status'] != "~" && $filter['search_by_status'] != "") {
            
            if($filter['search_by_status']=="pre_assigned" || $filter['search_by_status']=="")
           {
                $this->db->where('job.job_status',1);
           }
           if($filter['search_by_status']=="assigned")
           {
                $this->db->where('job.job_status',2);
                $this->db->where('job.status <>','rejected');
                // $this->db->order_by('job.updated_on','DESC');
           }
           if($filter['search_by_status']=="accepted")
           {
                $this->db->where('job.job_status',3);
                $this->db->where('job.accept_status',1);
                $this->db->where('job.status <>','completed');
                $this->db->where('job.status <>','cancelled');
                // $this->db->order_by('job.updated_on','DESC');
           }

           if($filter['search_by_status']=="canceled")
           {
                $this->db->where('job.job_status',3);
                $this->db->where('job.accept_status',1);
                $this->db->where('job.status','cancelled');
                // $this->db->order_by('job.updated_on','DESC');
           }

           if($filter['search_by_status']=="completed")
           {
                $this->db->where('job.job_status',3);
                $this->db->where('job.accept_status',1);
                $this->db->where('job.status','completed');
                // $this->db->order_by('job.updated_on','DESC');
           }

           if($filter['search_by_status']=="rejected")
           {
                $this->db->where('job.job_status',2);
                $this->db->where('job.accept_status',0);
                $this->db->where('job.status','rejected');
                // $this->db->order_by('job.updated_on','DESC');
           }

           if($filter['search_by_status']=="outOfRangeStart")
           {
                $this->db->where('job.job_status',4);
                $this->db->where('job.accept_status',1);
                $this->db->where('job.status','problems');
                $this->db->where('job.is_out_of_range',1);
                // $this->db->order_by('job.updated_on','DESC');
           }

           if($filter['search_by_status']=="outOfRangeEnd")
           {
                $this->db->where('job.job_status',4);
                $this->db->where('job.accept_status',1);
                $this->db->where('job.status','problems');
                $this->db->where('job.is_out_of_range',2);
                // $this->db->order_by('job.updated_on','DESC');
           }

           if($filter['search_by_status']=="notCompleted")
           {
                $this->db->where('job.job_status',4);
                $this->db->where('job.accept_status',1);
                $this->db->where('job.status','problems');
                $this->db->where('job.is_out_of_range <>',2);
                $this->db->where('job.is_out_of_range <>',1);
                // $this->db->order_by('job.updated_on','DESC');
           }
            
        }

        if (isset($filter['sort_by_date']) && $filter['sort_by_date'] != "~" && $filter['sort_by_date']!="") {
        
            if($filter['sort_by_date']=='entryDate'){
                $this->db->order_by('job.created_on','DESC');

            }else{
                $this->db->order_by('job.tasting_date','DESC');
                
            }
        }else{
            $this->db->order_by('job.created_on','DESC');
        }


        if (isset($filter['entry_date']) && $filter['entry_date'] != "~" && $filter['entry_date'] != "") {
            $this->db->where("DATE_FORMAT(job.created_on,'%Y-%m-%d')", $filter['entry_date']);
        }
        if (isset($filter['search_text']) && $filter['search_text'] != "~" && $filter['search_text'] != "") {
            $searchText=base64_decode($filter['search_text']);
            //$searchText=$filter['search_text'];
            if($searchText=="n/a" || $searchText=="n/" || $searchText=="N/A" || $searchText=="N/" )
            {
                $this->db->where('job.agency_taster_id','');
            }else{
                $this->db->where("( (store.name LIKE '%$searchText%') OR (store.adress LIKE '%$searchText%') OR (store.state LIKE '%$searchText%') OR (store.city LIKE '%$searchText%') OR (store.zipcode LIKE '%$searchText%') OR (CONCAT(u1.last_name,' ',u1.first_name) LIKE '%$searchText%') OR (CONCAT(u2.last_name,' ',u2.first_name) LIKE '%$searchText%') OR (CONCAT(u3.last_name,' ',u3.first_name) LIKE '%$searchText%') OR (agency_name.meta_value LIKE '%$searchText%'))");
            }
        }

        if (isset($filter['status'])) {
            $job_status=$filter['status'];
            if($job_status=="problems")
            {
                 $this->db->where('job.job_status',4);
            }else{
                 $this->db->where('job.job_status <>',4);
            }
         }
         else
         {
             $this->db->where('job.job_status <>',4);
         }
         
        if ($count) {
            $this->db->group_by("job.id");
            return $this->db->count_all_results();
        }
        if ( (isset($filter['limit']) && $filter['limit'] > 0) && (isset($filter['offset']) ) ) {
            $this->db->limit($filter['limit'], $filter['offset']);
        }
        if ($order <> null) {
            $this->db->order_by($order, $dir);
        } 
        /*
        else {
            $this->db->order_by('updated_on ASC');
        }*/

        $this->db->group_by("job.id");
        $query = $this->db->get();
        //print_r($this->db->last_query());
        $arr=$query->result();
        //reconstruct array for rejected job
        // $reconstruct_array=array();
        // foreach($arr as $v)
        // {
        //     if($v->status=='rejected')
        //     {
        //         array_push($reconstruct_array,$v);
        //     }
        // }
        // foreach($arr as $m)
        // {
        //     if($m->status!='rejected')
        //     {
        //         array_push($reconstruct_array,$m);
        //     }
        // }
        // return $reconstruct_array;
        return $arr;
    }
    
    //Get all Billing from admin end

    public function get_billing_list($filter = array(), $order = null, $dir = null, $count = false) {
        //print_r($filter);die;
        $this->db->select("job.*,expense_details.date as billing_date,expense_details.exp_amount,CONCAT(taster.last_name, ' ',taster.first_name) as taster_name,acc.meta_value as account_no,store.name as store_name, CASE WHEN job.agency_taster_id = '' THEN taster.last_name ELSE agency_taster.last_name END AS taster_last_name");
        $this->db->from('job');
        $this->db->join('expense_details','job.id=expense_details.job_id','left');
        $this->db->join('users taster','expense_details.taster_id=taster.id','left');
        $this->db->join('user_meta acc','expense_details.taster_id=acc.user_id','left');
        $this->db->join('users agency','job.taster_id=agency.id','left');
        $this->db->join('user_meta agency_name','agency.id=agency_name.user_id','left');
        $this->db->join('users agency_taster','job.agency_taster_id=agency_taster.id','left outer');
        $this->db->join('users sales_rep','job.user_id=sales_rep.id','left');
        $this->db->join('store','job.store_id=store.id','left');
        
        if ( isset($filter['field']) && $filter['field'] <> "" && $filter['field'] <> "~" ) {
            //Search using wine flavour
            
            /*$this->db->where('store.wine_sell_type',$filter['field']);*/
            if($filter['field']=='royal' || $filter['field']=='mix'){
            $this->db->join('completed_job_wine_details','job.id=completed_job_wine_details.job_id','left');
            $this->db->join('wine','completed_job_wine_details.wine_id=wine.id','left');
            $this->db->where('wine.flavour',$filter['field']);
            $this->db->order_by('job.id','DESC');
            }else{
                if (isset($filter['field']) && $filter['field'] <> "" ) {
                    if($filter['field']=='date'){
                        $this->db->order_by('job.tasting_date','DESC');
                    }else if($filter['field']=='store'){
                        $this->db->order_by('store.name','ASC');
                    }else if($filter['field']=='taster'){
                        //$this->db->order_by('taster_display_name','ASC');
                        //$this->db->order_by('agency_taster.last_name','ASC');
                        //$this->db->order_by('taster.last_name','ASC');
                        $this->db->order_by('taster_last_name','ASC');
                        //$this->db->order_by("(CASE job.agency_taster_id WHEN '' THEN 1 ELSE 0 END), taster.last_name asc, agency_taster.last_name asc"); 
                    }else if($filter['field']=='agency'){   
                        $this->db->order_by("(CASE agency_name.meta_key WHEN 'agency_name' THEN 0 ELSE 1 END), agency_name.meta_value asc");                  
                    }else if($filter['field']=='salesrep'){
                        $this->db->order_by('sales_rep.last_name','ASC');
                    }
                    //print_r($filter['sort']);die;
                }else{
                    $this->db->order_by('job.id','DESC');
                }
            }

        }else{
            $this->db->order_by('job.id','DESC');
        }
        
        
        $this->db->where('job.is_deleted',0);
        $this->db->where('job.ready_for_billing',1);
        $this->db->where('job.is_archived',0);
        $this->db->where('acc.meta_key','manual_account_number');
        if ($count) {
            $this->db->order_by('updated_on ASC');
            $this->db->group_by("job.id");
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
       // echo $this->db->last_query()."<br>";die;
        //echo "<pre>";
        //print_r($query->result());die;
        return $query->result();
    }

//     public function get_billing_list($filter = array(), $order = null, $dir = null, $count = false) {
//         //print_r($filter);die;
//         $this->db->select("job.*,expense_details.date as billing_date,expense_details.exp_amount,CONCAT(last_name, ' ',first_name) as taster_name,user_meta.meta_value as account_no,store.name as store_name");
//         $this->db->from('job');
//         $this->db->join('expense_details','job.id=expense_details.job_id');
//         $this->db->join('users','expense_details.taster_id=users.id');
//         $this->db->join('user_meta','expense_details.taster_id=user_meta.user_id');
// 		$this->db->join('store','job.store_id=store.id','left');
//         if ( isset($filter['field']) && $filter['field'] <> "" ) {
//             //Search using wine flavour
            
//             /*$this->db->where('store.wine_sell_type',$filter['field']);*/
//             $this->db->join('completed_job_wine_details','job.id=completed_job_wine_details.job_id','left');
//             $this->db->join('wine','completed_job_wine_details.wine_id=wine.id','left');
//             $this->db->where('wine.flavour',$filter['field']);

//         }
//         $this->db->order_by('job.id','DESC');
        
//         $this->db->where('job.is_deleted',0);
//         $this->db->where('job.ready_for_billing',1);
//         $this->db->where('job.is_archived',0);
//         $this->db->where('user_meta.meta_key','manual_account_number');
//         if ($count) {
//             $this->db->order_by('updated_on ASC');
//             $this->db->group_by("job.id");
//             return $this->db->count_all_results();
//         }
//         if ( (isset($filter['limit']) && $filter['limit'] > 0) && (isset($filter['offset']) ) ) {
//             $this->db->limit($filter['limit'], $filter['offset']);
//         }
//         if ($order <> null) {
//             $this->db->order_by($order, $dir);
//         } else {
//             $this->db->order_by('updated_on ASC');
//         }
//         $this->db->group_by("job.id");
//         $query = $this->db->get();
//        // echo $this->db->last_query()."<br>";die;
// //echo "<pre>";
//         //print_r($query->result());die;
//         return $query->result();
//     }
    
    //Get all archive
    public function get_archive_list($filter = array(), $order = null, $dir = null, $count = false) {
        //print_r($filter);die;
        //$this->db->select("job.*,expense_details.date as billing_date,expense_details.exp_amount,CONCAT(last_name, ' ',first_name) as taster_name,user_meta.meta_value as account_no,store.name as store_name");
        
        $this->db->select("job.*,expense_details.date as billing_date,expense_details.exp_amount, CONCAT(u1.last_name,' ',u1.first_name) as seleRepName, CONCAT(u2.last_name,' ',u2.first_name) as taster_name, CONCAT(u3.last_name,' ',u3.first_name) as agency_taster_name ,user_meta.meta_value as account_no,store.name as store_name");
        $this->db->from('job');
        $this->db->join('expense_details','job.id=expense_details.job_id');
        $this->db->join('users','expense_details.taster_id=users.id');
        $this->db->join('user_meta','expense_details.taster_id=user_meta.user_id');
        $this->db->join('store','job.store_id=store.id','left');
        
        $this->db->join('users as u1', 'u1.id = job.user_id', 'left');
        $this->db->join('users as u2', 'u2.id = job.taster_id', 'left');
        $this->db->join('users as u3', 'u3.id = job.agency_taster_id', 'left');

        $this->db->join('users agency','job.taster_id=agency.id');
        $this->db->join('user_meta agency_name','agency.id=agency_name.user_id');

        if ( isset($filter['field']) && $filter['field'] <> "" ) {
            //Search using wine flavour
            /*$this->db->join('store','job.store_id=store.id','left');
            $this->db->where('store.wine_sell_type',$filter['field']);*/
            $this->db->join('completed_job_wine_details','job.id=completed_job_wine_details.job_id','left');
            $this->db->join('wine','completed_job_wine_details.wine_id=wine.id','left');
            $this->db->where('wine.flavour',$filter['field']);

        }
        if (isset($filter['tasting_date']) && $filter['tasting_date'] != "~" && $filter['tasting_date']!="") {
            $this->db->where('job.tasting_date', $filter['tasting_date']);
        }
        
        if (isset($filter['search_text']) && $filter['search_text'] != "~" && $filter['search_text'] != "") {
            $searchText=urldecode($filter['search_text']);
            //$this->db->where("((store.name LIKE '%$searchText%') OR (store.adress LIKE '%$searchText%') OR (store.state LIKE '%$searchText%') OR (store.city LIKE '%$searchText%') OR (store.suite_number LIKE '%$searchText%') OR (store.appartment_number LIKE '%$searchText%') OR (store.zipcode LIKE '%$searchText%')");
            $this->db->where("( (store.name LIKE '%$searchText%') OR (agency_name.meta_value LIKE '%$searchText%') OR (CONCAT(u1.last_name,' ',u1.first_name) LIKE '%$searchText%') OR (CONCAT(u2.last_name,' ',u2.first_name) LIKE '%$searchText%') OR (CONCAT(u3.last_name,' ',u3.first_name) LIKE '%$searchText%') OR (job.invoice_number LIKE '%$searchText%') )");
        }
        $this->db->order_by('job.id','DESC');
        
        $this->db->where('job.is_deleted',0);
        
        $this->db->where('job.is_archived',1);
        $this->db->where('user_meta.meta_key','manual_account_number');
        if ($count) {
            $this->db->order_by('updated_on ASC');
            $this->db->group_by("job.id");
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
        
        return $query->result();
    }
    // public function get_archive_list($filter = array(), $order = null, $dir = null, $count = false) {
    //     //print_r($filter);die;
    //     $this->db->select("job.*,expense_details.date as billing_date,expense_details.exp_amount,CONCAT(last_name, ' ',first_name) as taster_name,user_meta.meta_value as account_no,store.name as store_name");
    //     $this->db->from('job');
    //     $this->db->join('expense_details','job.id=expense_details.job_id');
    //     $this->db->join('users','expense_details.taster_id=users.id');
    //     $this->db->join('user_meta','expense_details.taster_id=user_meta.user_id');
	// 	$this->db->join('store','job.store_id=store.id','left');
    //     if ( isset($filter['field']) && $filter['field'] <> "" ) {
    //         //Search using wine flavour
    //         /*$this->db->join('store','job.store_id=store.id','left');
    //         $this->db->where('store.wine_sell_type',$filter['field']);*/
    //         $this->db->join('completed_job_wine_details','job.id=completed_job_wine_details.job_id','left');
    //         $this->db->join('wine','completed_job_wine_details.wine_id=wine.id','left');
    //         $this->db->where('wine.flavour',$filter['field']);

    //     }

    //     $this->db->order_by('job.id','DESC');
        
    //     $this->db->where('job.is_deleted',0);
        
    //     $this->db->where('job.is_archived',1);
    //     $this->db->where('user_meta.meta_key','manual_account_number');
    //     if ($count) {
    //         $this->db->order_by('updated_on ASC');
    //         $this->db->group_by("job.id");
    //         return $this->db->count_all_results();
    //     }
    //     if ( (isset($filter['limit']) && $filter['limit'] > 0) && (isset($filter['offset']) ) ) {
    //         $this->db->limit($filter['limit'], $filter['offset']);
    //     }
    //     if ($order <> null) {
    //         $this->db->order_by($order, $dir);
    //     } else {
    //         $this->db->order_by('updated_on ASC');
    //     }
    //     $this->db->group_by("job.id");
    //     $query = $this->db->get();
    //    //echo $this->db->last_query()."<br>";die;
        
    //     return $query->result();
    // }
    
    public function get_csv($checked_id=array(),$filter = array(), $order = null, $dir = null, $count = false) {
        //Need to get an array of id's
          $ids = array();
          foreach($checked_id as $val) {
             array_push($ids, $val);
          }
          //print_r($ids);die;
       // $this->db->select("job.*, expense_details.date as billing_date, expense_details.exp_amount,completed_job_wine_details.wine_id as sampled_and_sold_wine, CONCAT(first_name, ' ',last_name) as taster_name, user_meta.meta_value as account_no");
        $this->db->select("job.id,job.store_id,job.wine_id,job.user_id,job.taster_id,job.agency_taster_id,job.updated_on,job.tasting_date, expense_details.date as billing_date, expense_details.exp_amount,completed_job_wine_details.wine_id as sampled_and_sold_wine, CONCAT(first_name, ' ',last_name) as taster_name, user_meta.meta_value as account_no");
        $this->db->from('job');
        $this->db->join('completed_job_wine_details','job.id=completed_job_wine_details.job_id');
        $this->db->join('expense_details','job.id=expense_details.job_id');
        
        $this->db->join('users','expense_details.taster_id=users.id');
        $this->db->join('user_meta','expense_details.taster_id=user_meta.user_id');
        $this->db->order_by('job.id','DESC');
        $this->db->where_in('job.id', $ids);
        //$this->db->where('job.is_deleted',0);
        //$this->db->where('job.ready_for_billing',1);
        $this->db->where('user_meta.meta_key','manual_account_number');
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
       //echo $this->db->last_query();die;
        $result=$query->result_array();
        $new_data_array=array();
        for($i=0;$i<count($result);$i++)
        {
            //Get total amount
            $job_id=$result[$i]['id'];
            $total_result=$this->get_more_job_info($job_id);
            $total_amount=$total_result->total_amount;
            
            $amount=ltrim($result[$i]['exp_amount'],"$");
            $amount_on_rate=ltrim($total_amount,"$");
            $actual_amount=$amount+$amount_on_rate;
            $result[$i]['exp_amount']="$".$actual_amount;
            //End
            
            //generate invoice id using tasting date
                $array=explode("-",$result[$i]['tasting_date']);
                $invoice_id=$array[0].$array[1].$array[2].'va';
            $result[$i]['invoice_number']=$invoice_id;
            
            //End
            $sales_rep_id=$result[$i]['user_id'];
            //get name
            $sales_rep_name=$this->get_user_name($sales_rep_id);
            $result[$i]['user_id']=$sales_rep_name;
            

            //Get store
            $store_id=$result[$i]['store_id'];
            $this->db->select('name');
            $this->db->from('store');
            $this->db->where('store.id',$store_id);
            
            $value = $this->db->get()->row();
            $store_name=$value->name;
            $result[$i]['store_id']=$store_name;
            
            //unset($result[$i]['store_id']);
            //get wine
            $wine_id=$result[$i]['wine_id'];
            $wine_array=explode(",",$wine_id);
            $wine_name='';
            foreach($wine_array as $id)
            {
                $this->db->select('name');
                $this->db->from('wine');
                $this->db->where('wine.id',$id);
                
                $wine = $this->db->get()->row();
                $wine_name.=$wine->name.",";
            }
            $wine_name=rtrim($wine_name,",");
            $result[$i]['wine_id']=$wine_name;

            //get sold and sampled wine
            $sampled_wine_id=$result[$i]['sampled_and_sold_wine'];
            $this->db->select('name');
            $this->db->from('wine');
            $this->db->where('wine.id',$sampled_wine_id);
                
            $s_wine = $this->db->get()->row();
            $s_wine_name=$s_wine->name;
            $result[$i]['sampled_and_sold_wine']=$s_wine_name;
            unset($result[$i]['taster_id']);
             unset($result[$i]['agency_taster_id']);
            unset($result[$i]['id']);
            unset($result[$i]['wine_id']);
            unset($result[$i]['user_id']);
            unset($result[$i]['updated_on']);
            unset($result[$i]['billing_date']);
            unset($result[$i]['sampled_and_sold_wine']);
            unset($result[$i]['taster_name']);
           
           // unset($result[$i]['wine_id']);

            $arr=array();
            $arr['account_no']=$result[$i]['account_no'];
            $arr['invoice_number']=$result[$i]['invoice_number'];
            $arr['tasting_date']=$result[$i]['tasting_date'];
            $arr['exp_amount']=$result[$i]['exp_amount'];
            $arr['store_id']=$result[$i]['store_id'];
            array_push($new_data_array,$arr);

        }
        //echo "<pre>";
        //print_r($result);die;
        /*foreach($result as $key => $value)
        {
            if($result[$key]=='user_id')
            {
                $result[$key]='user';
            }
            
            unset($result[$key]);
        }*/
        
        return $new_data_array;
    }
    public function moved_to_archive($checked_id)
    {
        $id_array=explode(",",$checked_id);
		$data = array('is_archived'=>1);
        /* foreach($id_array as $id)
        {
            $this->db->where('id', $id);
            $this->db->update('job', $data); 
        } */
		
        $this->db->where_in('id', $id_array);
        $this->db->update('job', $data);
        return $this->db->affected_rows();
    }
    public function get_todays_approved_jobs($current_date)
    {
        $this->db->select("job.*");
        $this->db->from('job');
        $this->db->where('DATE(updated_on)',$current_date);
        $qr="(status='approved' OR status='completed')";
        $this->db->where($qr);
        $this->db->where('is_deleted',0);
        $value=$this->db->get();
        //echo $this->db->last_query();die;
        $result=$value->result_array();

        return $result;
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
    function job_details($id)
    {
        $this->db->select('*');
        $this->db->from('job');
        $this->db->where('id',$id);
        $result=$this->db->get();
        return $result->row();
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
    function get_store()
    {
        $this->db->select('*');
        $this->db->from('store');
        $this->db->where('store.status','active');
        $this->db->where('store.is_deleted',0);
        $this->db->order_by('store.name','ASC');
        $store = $this->db->get();
        return $store->result();
    }
    function get_wine($store_id)
    {
        $this->db->select('wine_sell_type');
        $this->db->from('store');
        $this->db->where('id',$store_id);
        $val=$this->db->get()->row();

        $result=$val->wine_sell_type;
        $wineTypeArray = explode("/",$result);

        $this->db->select('*');
        $this->db->from('wine');
        $this->db->where('wine.is_deleted',0);

        foreach ($wineTypeArray as $wineType) {
            if ($wineType == 'royal'){
                array_push($wineTypeArray,"mix");
                break;
            }
        }
        $winetype =  array_unique($wineTypeArray);
        $this->db->where_in('wine.flavour',$winetype);

        /*
        if($result=='royal')
        {
            $type = array('royal','mix');
            // $this->db->where('wine.flavour','royal');
            $this->db->where_in('wine.flavour',$type);
        }
        if($result=='mix')
        {
            $this->db->where('wine.flavour','mix');
        }
        if($result=='kayco')
        {
            $this->db->where('wine.flavour','kayco');
        }
        if($result=='other')
        {
            $this->db->where('wine.flavour','other');
        }*/


        $this->db->order_by('name','ASC');
        $store = $this->db->get();
    
        return $store->result();
    }
    function get_all_wine()
    {

        $this->db->select('*');
        $this->db->from('wine');
        $this->db->where('wine.is_deleted',0);
        $this->db->order_by('name','ASC');
        $store = $this->db->get();
        return $store->result();
    }
    function get_zone_details($store_id)
    {
        $this->db->select('zone');
        $this->db->from('store');
        $this->db->where('store.id',$store_id);
        $result2 = $this->db->get();
        $obj=$result2->row();
        if(!empty($obj))
            $zone_id=$obj->zone;
        else
            $zone_id=0;

        //Get name
        $this->db->select('name');
        $this->db->from('zone');
        $this->db->where('zone.id',$zone_id);
        $resultzone = $this->db->get();
        $value=$resultzone->row();
        $obj->zone_name=$value->name;
        return $obj;
    }
    function get_tester_or_agency($job_id)
    {
        //get store id
        $this->db->select('store_id');
        $this->db->from('job');
        $this->db->where('job.id',$job_id);
        $result = $this->db->get();
        $store_id=$result->row()->store_id;
       //echo $store_id;die;
        //get zone id
        $this->db->select('zone');
        $this->db->from('store');
        $this->db->where('store.id',$store_id);
        $result2 = $this->db->get();
        $obj=$result2->row();
        if(!empty($obj))
            $zone_id=$obj->zone;
        else
            $zone_id=0;
        //echo $zone_id;die;
        //get tester or agency id from user meta
        $this->db->select('user_id,meta_value');
        $this->db->from('user_meta');
        $this->db->where('meta_key','zone');
       // $this->db->like('meta_value', $zone_id,'after');
        $this->db->where("(meta_value LIKE '%$zone_id%')");
        $result3 = $this->db->get();
        //echo $this->db->last_query();die;
        $id_array=$result3->result_array();
        $zone_id_array=array();
        foreach($id_array as $value)
        {
            $arr=explode(",",$value['meta_value']);
            if(in_array($zone_id,$arr))
            {
                array_push($zone_id_array,$value['user_id']);
            }
        }
        $id_string=array();
        foreach($zone_id_array as $id)
        {
            //$id_string.=$id['user_id'].",";
            array_push($id_string,$id);
        }
        //echo "<pre>";
       //print_r($id_string);die;
        //$id_string=rtrim($id_string,",");
        $this->db->select('users.*');
        $this->db->from('users');
        //$this->db->join('user_meta','users.id=user_meta.user_id');
        //$this->db->where('user_meta.meta_key','agency_name');
        if(!empty($id_string))
            $this->db->where_in('users.id',$id_string);
        //$this->db->where('user_type','tester');
        //$this->db->or_where('user_type','agency');
        $this->db->where('users.status','active');
        $this->db->where('users.is_deleted',0);
        $this->db->where('users.created_by',7);
        $qr="(user_type='tester' OR user_type='agency')";
        $this->db->where($qr);
        $this->db->order_by('users.last_name','ASC');
        $result_user = $this->db->get();
       // echo $this->db->last_query();die;
        $user_details=$result_user->result_array();
        foreach($user_details as $details)
        {
            if($details['role_id']==5)
            {
                $agency_name=$this->get_agency_name('user_meta',$details['id']);
                //echo $agency_name;die;
                $details['agency_name']=$agency_name;
            }
        }
        //echo "<pre>";
        //print_r($user_details);die;
        return $user_details;
    }
    function get_tester_or_agency_ajax($store_id)
    {
        $this->db->select('zone');
        $this->db->from('store');
        $this->db->where('store.id',$store_id);
        $result2 = $this->db->get();
        $obj=$result2->row();
        if(!empty($obj))
            $zone_id=$obj->zone;
        else
            $zone_id=0;
        //echo $zone_id;die;
        //get tester or agency id from user meta
        $this->db->select('user_id,meta_value');
        $this->db->from('user_meta');
        $this->db->where('meta_key','zone');
        //$this->db->like('meta_value', $zone_id,'before');
        $this->db->where("(meta_value LIKE '%$zone_id%')");
        $result3 = $this->db->get();
        //echo $this->db->last_query();die;
        $id_array=$result3->result_array();
        $zone_id_array=array();
        foreach($id_array as $value)
        {
            $arr=explode(",",$value['meta_value']);
            if(in_array($zone_id,$arr))
            {
                array_push($zone_id_array,$value['user_id']);
            }
        }
        $id_string=array();
        foreach($zone_id_array as $id)
        {
            //$id_string.=$id['user_id'].",";
            array_push($id_string,$id);
        }
        //echo "<pre>";
       //print_r($id_string);die;
        //$id_string=rtrim($id_string,",");
        $this->db->select('*');
        $this->db->from('users');
        if(!empty($id_string))
            $this->db->where_in('id',$id_string);
        //$this->db->where('user_type','tester');
        //$this->db->or_where('user_type','agency');
        $this->db->where('status','active');
        $this->db->where('users.is_deleted',0);
        $this->db->where('users.created_by',7);
        $qr="(user_type='tester' OR user_type='agency')";
		$this->db->where($qr);
        $this->db->order_by('users.last_name','ASC');
        $result_user = $this->db->get();
       // echo $this->db->last_query();die;
        $user_details=$result_user->result_array();
        //echo "<pre>";
        //print_r($user_details);die;
        return $user_details;
    }
    function get_taster()
    {

        $this->db->select('*');
        $this->db->from('users');
        
        $this->db->where('status','active');
        $this->db->where('users.is_deleted',0);
        $this->db->where('users.created_by',7);
        $qr="(user_type='tester' OR user_type='agency')";
       $this->db->where($qr);
        $this->db->order_by('users.last_name','ASC');
        $result_user = $this->db->get();
       // echo $this->db->last_query();die;
        $user_details=$result_user->result_array();
        //echo "<pre>";
        //print_r($user_details);die;
        return $user_details;
    }
    function get_sales_rep()
    {
        $this->db->select('*');
        $this->db->from('users');
        
        $this->db->where('status','active');
        $this->db->where('users.is_deleted',0);
        $this->db->where('users.user_type','sales_rep');
        $qr="(user_type='sales_rep')";
       $this->db->order_by('users.last_name','ASC');
        $result_user = $this->db->get();
       // echo $this->db->last_query();die;
        $user_details=$result_user->result_array();
        //echo "<pre>";
        //print_r($user_details);die;
        return $user_details;
    }
    function get_sales_rep_details($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('users.id',$id);
        $result_user = $this->db->get();
       // echo $this->db->last_query();die;
        $user_details=$result_user->result_array();
        
        //Get meta value
        $this->db->select('*');
        $this->db->from('user_meta');
        $this->db->where('user_id',$id);
        $this->db->where('meta_key','phone');
        //$this->db->like('meta_value', $zone_id,'before');
        $result3 = $this->db->get();
        $id_array=$result3->result_array();
        
        $user_details['meta']=$id_array;
        //echo "<pre>";
      // print_r($user_details);die;
        return $user_details;
    }
    function get_question_answers()
    {
        $this->db->select('id,question');
        $this->db->from('question_answer');
        $this->db->order_by('id','DESC');
        $question = $this->db->get()->result_array();
        return $question;
    }
    //Get questions answers for particular job
    function question_answers($job_id)
    {
        //get question id
        $this->db->select('job.question_id');
        $this->db->from('job');
        $this->db->where('job.id',$job_id);
        $question = $this->db->get()->row();
        $question_id=$question->question_id;
        $question_id_array=explode(",",$question_id);
        //echo "<pre>";
        //print_r($question_id_array);die;
        $question_id_string=array();
        foreach($question_id_array as $id)
        {
            //$id_string.=$id['user_id'].",";
            array_push($question_id_string,$id);
        }
        //Get question and answer
        $this->db->select('question_answer.id as question_id,question,answer_type');
        $this->db->from('question_answer');
        $this->db->order_by('question_answer.id','DESC');
        $this->db->where_in('question_answer.id',$question_id_string);
        $question_answer = $this->db->get()->result_array();
        for($i=0;$i<count($question_answer);$i++)
        {
            $this->db->select('answer_one,answer_two,answer_three,answer_four');
            $this->db->from('question_answer');
            $this->db->where('question_answer.id',$question_answer[$i]['question_id']);
            $this->db->where('question_answer.answer_type','multiple');
            $answer = $this->db->get()->result_array();
            $question_answer[$i]['ans']=$answer;
        }
        return $question_answer;
    }
    function check_job($tablename,$tester_id,$job_id)
    {
        $this->db->select('*');
        $this->db->from($tablename);
        $this->db->where('job.id',$job_id);
       // $this->db->like('job.taster_id', $tester_id, 'both');
        $this->db->where('job.is_deleted',0);
        $job = $this->db->get()->row();
        return $job->id;
    }
    function check_tester($tablename,$job_id)
    {
        $this->db->select('taster_id');
        $this->db->from($tablename);
        $this->db->where('id',$job_id);
        $job = $this->db->get()->row();
        if($job->taster_id!='')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function check_store($tablename,$store_id,$start_time,$end_time,$tasting_date,$job_id)
    {
        $this->db->select('id');
        $this->db->from($tablename);
        $this->db->where('store_id',$store_id);
        $this->db->where('is_deleted',0);
        //$this->db->where('start_time',$start_time);
        //$this->db->where('end_time',$end_time);
        $qr = " ( (`start_time` <= '".$start_time."' AND `end_time` >= '".$end_time."') OR (`start_time` >= '".$start_time."'  AND `start_time` <= '".$start_time."' AND `end_time` <= '".$end_time."') OR (`end_time` <= '".$end_time."' AND `end_time` >= '".$start_time."'  AND `start_time` <= '".$start_time."' ) OR (start_time >= '".$start_time."'  AND start_time <= '".$start_time."') )";
        $this->db->where($qr);
        $this->db->where('tasting_date',$tasting_date);
        if($job_id!=0)
        {
            $this->db->where('job.id <>',$job_id);
        }
        
        $job = $this->db->get();
        //echo $this->db->last_query();die;
        return $job->num_rows();
        
    }
    function accept_or_reject($tablename,$job_id,$accept,$accepted_or_rejected_by)
    {
        //Get rate per hour of a taster and update the info into job table
        $this->db->select("job.taster_id,job.agency_taster_id");
        $this->db->from('job');
        $this->db->where('job.id',$job_id);
        $result=$this->db->get()->row();
        
        if($result->agency_taster_id==0)
            $taster_id=$result->taster_id;
        else
            $taster_id=$result->agency_taster_id;
       
        
        //Calculate total amount
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
        $this->db->update($tablename, $taster_rate_array);
        //End
        $data=array();
        if($accept==1)
        {
            $data['accept_status']=1;
            //$data['status']='accepted';
            $data['status']='approved';
            $data['job_status']=3;
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
            $data['taster_id']=$accepted_or_rejected_by;
        }
        else
        {
            $accept_array['rejected_by']=$accepted_or_rejected_by;
        }
        $accept_array['date']=date("Y-m-d");
        //$array = array('id' => $job_id);
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
                //$this->db->delete('job_accept_reject', array('job_id' => $job_id));
            }
            else
            {
                $flag=1;
            }
            return $flag;
    }
    function accepted_user($job_id)
    {
        //get accepted user
        $this->db->select('accepted_by');
        $this->db->from('job_accept_reject');
        $this->db->group_by('accepted_by');
        $this->db->where('rejected_by',0);
        $this->db->where('job_id',$job_id);
        $accepted_user_id=$this->db->get()->result_array();
        $accepted_id_string=array();
        foreach($accepted_user_id as $value)
        {
            //$id_string.=$id['user_id'].",";
            array_push($accepted_id_string,$value['accepted_by']);
        }
        //print_r($accepted_id_string);die;
        
        $this->db->select('id as user_id,first_name,last_name');
        $this->db->from('users');
        if(!empty($accepted_id_string))
            $this->db->where_in('users.id',$accepted_id_string);
        else
            $this->db->where_in('users.id','');
        $accepted_user = $this->db->get()->result_array();
        return $accepted_user;
    }
    function rejected_job_details($job_id)
    {
        //Get all rejected user
        $this->db->select('rejected_by');
        $this->db->from('job_accept_reject');
        $this->db->group_by('rejected_by');
        $this->db->where('accepted_by',0);
        $this->db->where('job_id',$job_id);
        $rejected_user_id=$this->db->get()->result_array();
        $rejected_id_string=array();
        foreach($rejected_user_id as $value)
        {
            //$id_string.=$id['user_id'].",";
            array_push($rejected_id_string,$value['rejected_by']);
        }
        $this->db->select('id as user_id,first_name,last_name');
        $this->db->from('users');
        if(!empty($rejected_id_string))
            $this->db->where_in('users.id',$rejected_id_string);
        else
            $this->db->where_in('users.id','');
        $rejected_user = $this->db->get()->result_array();
        
        $rejected='';
      foreach($rejected_user as $au)
      {
        $user_type=$this->get_user_type('users',$au['user_id']);
        if($user_type[0]->user_type=='agency')
        {
            $name=$this->get_agency_name('user_meta',$au['user_id']);
            $rejected.=$name.",";
        }
        else
        {
            $rejected.=$au['last_name']." ".$au['first_name'].",";
        }

      }
     $rejected=rtrim($rejected,",");
     return $rejected;
        
        
    }
    function rejected_user($job_id)
    {
        //get accepted user
        $this->db->select('rejected_by');
        $this->db->from('job_accept_reject');
        $this->db->group_by('rejected_by');
        $this->db->where('accepted_by',0);
        $this->db->where('job_id',$job_id);
        $rejected_user_id=$this->db->get()->result_array();
        $rejected_id_string=array();
        foreach($rejected_user_id as $value)
        {
            //$id_string.=$id['user_id'].",";
            array_push($rejected_id_string,$value['rejected_by']);
        }
        $this->db->select('first_name,last_name');
        $this->db->from('users');
        if(!empty($rejected_id_string))
        {
            $this->db->where_in('users.id',$rejected_id_string);
        }
        else
        {
            $this->db->where_in('users.id','');
        }
        $rejected_user = $this->db->get()->result_array();
        return $rejected_user;
    }
    function cancelled_job_details($job_id)
    {
        $this->db->select("reason,CONCAT(last_name, ' ',first_name) as taster_name");
        $this->db->from("cancelled_job");
        $this->db->join("users","cancelled_job.taster_id=users.id",'left');
        $this->db->where('cancelled_job.job_id',$job_id);
        $this->db->order_by('cancelled_job.id','desc');
        $this->db->limit(1);
        $cancelled_job_details=$this->db->get()->result_array();
        return $cancelled_job_details;
    }
    function requested_job_details($job_id)
    {
        $this->db->select("reason as requested_reason,requested_tester_id,CONCAT(first_name, ' ',last_name) as requested_taster_name");
        $this->db->from("request_accepted_job_to_tester");
        $this->db->group_by("request_accepted_job_to_tester.job_id");
        $this->db->join("users","request_accepted_job_to_tester.tester_id=users.id",'left');
        $this->db->where('request_accepted_job_to_tester.job_id',$job_id);
        $requested_job_details=$this->db->get()->result_array();
        return $requested_job_details;
    }
    function get_special_request($store_id)
    {
        $this->db->select('store.special_request');
        $this->db->from('store');
        $this->db->where('store.id',$store_id);
        $result=$this->db->get()->row();
        
        return $result->special_request;
    }
    function check_tester_availablity($tablename,$taster_id,$tasting_date,$start_time,$end_time)
    {
		
		$this->db->select('user_type');
        $this->db->from('users');
        $this->db->where('users.id',$taster_id);
        $user_type=$this->db->get()->row()->user_type;
		if($user_type == 'tester'){
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
			$this->db->where('taster_id',$taster_id);
			//$result=$this->db->get();
		   // echo $this->db->last_query();die;
			$result=$this->db->get()->row();
			return $result->count;
		}else{
			return 0;
		}
    }
	function check_tester_availablity_with_jobid($jobid,$tablename,$taster_id,$tasting_date,$start_time,$end_time)
    {
		$this->db->select('user_type');
        $this->db->from('users');
        $this->db->where('users.id',$taster_id);
        $user_type=$this->db->get()->row()->user_type;
		if($user_type == 'tester'){
			$this->db->select('COUNT(*) as count,id');
			//$this->db->select('*');
			$this->db->from($tablename);
			$this->db->where('tasting_date',$tasting_date);
			$this->db->where('is_deleted',0);
			$this->db->where('status !=','completed');
			$where_1 = " ( (`start_time` <= '".$start_time."' AND `end_time` >= '".$end_time."')
			OR (`start_time` >= '".$start_time."'  AND `start_time` <= '".$end_time."' AND `end_time` <= '".$end_time."')  
			OR (`end_time` <= '".$end_time."' AND `end_time` >= '".$start_time."'  AND `start_time` <= '".$start_time."' )  
			OR (start_time >= '".$start_time."'  AND start_time <= '".$end_time."') )";
			$this->db->where($where_1);
			$this->db->where('taster_id',$taster_id);
			//$result=$this->db->get();
		   // echo $this->db->last_query();die;
			$result=$this->db->get()->row();
			//return $result;
			if($result->count ==0){
				return 0;
			}else if($result->count ==1 && $result->id == $jobid){
				return 0;
			}else{
				return 1;
			}
		}else{
			return 0;
		}
    }
    function get_tester_details($tester_id)
    {
        $this->db->select('users.first_name,users.email');
        $this->db->from('users');
        $this->db->where('users.id',$tester_id);
        $result=$this->db->get()->row();
        return $result;
    }
    function get_accepted_joblist($tester_id,$todays_job)
    {
        $previous_date=date('Y-m-d', strtotime('-2 months'));
        $todays_date=date("Y-m-d");
        $this->db->select("job.id as job_id,job.status,job.job_state,DATE_FORMAT(job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as start_time,TIME_FORMAT(end_time, '%h:%i%p') as end_time,store.name as store_name,store.adress as address,store.suite_number  as suite_and_apt");
        $this->db->from('job');
        $this->db->group_by('job_accept_reject.job_id');
        $this->db->join('store','job.store_id=store.id');
        $this->db->join('job_accept_reject','job.id=job_accept_reject.job_id');
        if(isset($todays_job) && $todays_job==1)
        {
            $this->db->where('job_accept_reject.date',$todays_date);
        }
        //$this->db->like('job.taster_id',$tester_id);
        //$this->db->where("(job.taster_id LIKE $tester_id)");
        $this->db->where("(job.taster_id = $tester_id OR job.agency_taster_id = $tester_id)");
        $this->db->where('job.accept_status',1);
        $this->db->where('job.is_deleted',0);
        $this->db->where('job.tasting_date >=', $previous_date);
        
        $qr="(job.status='accepted' OR job.status='approved' OR job.status='completed')";
        //$qr="(job.status='approved' OR job.status='accepted')";
        //$this->db->where('job.job_status','completed');
        $this->db->where($qr);
       // $this->db->or_where('job.status','approved');
        $result=$this->db->get();
       // echo $this->db->last_query();die;
        return $result->result_array();
    }
    function cancel_job($tester_id,$job_id,$reason,$cancel_date)
    {
        //delete job accepted data
        $this->db->delete('job_accept_reject', array('job_id' => $job_id,'accepted_by' => $tester_id)); 
        $array=array(
            'id'=>$job_id
        );
        $data=array(
            'status'=>'cancelled'
        );
        $this->db->where($array);
        $this->db->update('job', $data);
        $cancel_array=array(
            'taster_id'=>$tester_id,
            'job_id'=>$job_id,
            'reason'=>$reason,
            'date'=>$cancel_date
        );
        $this->db->insert('cancelled_job', $cancel_array);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
    function set_job_for_problems($job_id)
    {
        $this->db->where('id',$job_id );
        $this->db->update('job',array('status'=>'problems','job_status'=>4));
    }
    function set_job_state($update_array,$job_id)
    {
        $array=array(
            'id'=>$job_id
        );
        $this->db->where($array);
        $this->db->update('job', $update_array);
        return $this->db->affected_rows();
    }
    function get_out_range_status($job_id){
        $this->db->select('is_out_of_range');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get()->row();
        return $result->is_out_of_range;
    }
    function set_pause_time($time,$job_id)
    {
        $array=array(
            'job_id'=>$job_id,
            'pause_time'=>$time
        );
        //$this->db->where($array);
        //$this->db->update('job', $update_array);
        $this->db->insert('set_job_state',$array);
        return $this->db->insert_id();
    }
    function set_resume_time($time,$job_id)
    {
        //Calculate the last inserted id
        $last = $this->db->order_by('id',"desc")->limit(1)->get('set_job_state')->row();
        $last_id=$last->id;
        $update_array=array(
            'resume_time'=>$time
        );
        $this->db->where(array('id'=>$last_id));
        $this->db->update('set_job_state', $update_array);
        return $this->db->affected_rows();
    }
    function calculate_pause_time($job_id)
    {
        $this->db->select('*');
        $this->db->from('set_job_state');
        $this->db->where('job_id',$job_id);
        $value=$this->db->get();
        return $value->result_array();
    }
    function submit_wine_details($wine_details_array,$taster_id,$job_id)
    {
        foreach($wine_details_array as $array)
        {
            $array['taster_id']=$taster_id;
            $array['job_id']=$job_id;
            $this->db->insert('completed_job_wine_details',$array);
        }
        return $this->db->insert_id();
    }
    function get_completed_job($sales_rep_id)
    {
        //$this->db->select("job.id as job_id,job.status,store.name as store_name,taster_id,DATE_FORMAT(job.tasting_date, '%d-%m-%Y') as job_date");
        //$this->db->select("job.id as job_id,job.status,store.name as store_name,taster_id,DATE_FORMAT(job.tasting_date, '%d-%m-%Y') as job_date, job.agency_taster_id as agency_taster_id, job_rating.rating as rating");
        $this->db->select("job.id as job_id,job.status,store.name as store_name, job.taster_id as taster_id,DATE_FORMAT(job.tasting_date, '%d-%m-%Y') as job_date, job.agency_taster_id as agency_taster_id, job_rating.rating as rating");
        $this->db->from('job');
        $this->db->join('store','job.store_id=store.id');
        $this->db->join('job_rating', 'job_rating.job_id = job.id', 'left');
        $this->db->where('job.user_id',$sales_rep_id);
        //$this->db->where('job.confirm_status',1);
        $this->db->where('job.status','completed');
        $this->db->where('job.is_deleted',0);
        $result=$this->db->get();
        //echo $this->db->last_query();die;
        //return $result->result_array();
        $final_result=$result->result_array();
        //echo "<pre>";
        //print_r($final_result);die;
        for($i=0;$i<count($final_result);$i++)
        {
            //$taster_id=$final_result[$i]['taster_id'];
            if($final_result[$i]['agency_taster_id']){
                $taster_id=$final_result[$i]['agency_taster_id'];
            }else{
                $taster_id=$final_result[$i]['taster_id'];
            }
            $taster_id_array=explode(",",$taster_id);
            //echo "<pre>";
            //print_r($question_id_array);die;
            $taster_id_string=array();
            foreach($taster_id_array as $id)
            {
                //$id_string.=$id['user_id'].",";
                array_push($taster_id_string,$id);
            }
            //get tester name
            $this->db->select("CONCAT(last_name, ' ',first_name) as taster_name");
            $this->db->from('users');
            //$this->db->where_in('id',$taster_id_string);
            $this->db->where('id',$taster_id);
            $tester_result=$this->db->get()->result_array();
            $taster_name='';
            foreach($tester_result as $tester)
            {
                $taster_name.=$tester['taster_name'].",";
            }
            $taster_name=rtrim($taster_name,",");
            $final_result[$i]['taster_name']=$taster_name;
            //Get wine name for completed job 
            $job_id=$final_result[$i]['job_id'];
            //Get wine id for completed job
            $this->db->select('wine_id');
            $this->db->from('completed_job_wine_details');
            $this->db->where('job_id',$job_id);
            $value=$this->db->get();
            $result=$value->result_array();
            $wine_name='';
            if(!empty($result))
            {
                foreach($result as $w)
                {
                    $wine_id=$w['wine_id'];
                    //Get wine name for completed job
                    $this->db->select('name');
                    $this->db->from('wine');
                    $this->db->where('id',$wine_id);
                    $value_wine=$this->db->get();
                    $result_wine=$value_wine->result_array();
					if($result_wine){
                    $wine_name.=$result_wine[0]['name'].",";
					}
                }
                $wine_name=rtrim($wine_name,",");
                $final_result[$i]['wine_name']=$wine_name;
                $wine_name='';
            }
            else
            {
                $final_result[$i]['wine_name']='';
            }
            
        }
        unset($taster_id_string);
        return $final_result;
    }
    function submit_manager_verification_details($manager_verification_array)
    {
        if ($this->db->insert('manager_verification_details', $manager_verification_array)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    function get_manager_verification_details($job_id)
    {
        $this->db->select('id,job_id,first_name,last_name,comment,signature_img');
        $this->db->from('manager_verification_details');
        $this->db->where('manager_verification_details.job_id',$job_id);
        $result=$this->db->get()->result_array();
        if(!empty($result))
        {
            $signature_img=$result[0]['signature_img'];
            $result[0]['signature_img']=BASE_URL.DIR_SIGNATURE_IMAGE.$signature_img;
            // $result[0]['signature_img']="https://img253.managed.center/".$signature_img;
            return $result;
        }
        else{
               return false;
           }   
    }
    function submit_expense_details($expense_array)
    {
        if ($this->db->insert('expense_details', $expense_array)) {
            //return $this->db->insert_id();
            $last_id=$this->db->insert_id();
            //echo $last_id;die;
            if($last_id)
            {
                $job_id=$expense_array['job_id'];
                //Get job status
                $job=$this->get_job_details($job_id);
                if($job->status!='problems')
                {
                    $this->Job_model->setInvoiceNumber($job_id);
                    $data = array(
                       'status'=>'completed'
                    );
                    $this->db->where('id',$job_id);
                     
                    $this->db->update('job',$data);
                    return $last_id;
                }
                else{
                    return $last_id;
                }

                    
            }
        } else {
            return false;
        }
    }
    function submit_expense_details_from_cms($expense_array)
    {
        if ($this->db->insert('expense_details', $expense_array)) {
            //return $this->db->insert_id();
            $last_id=$this->db->insert_id();
            //echo $last_id;die;
            if($last_id)
            {
                $job_id=$expense_array['job_id'];
                //Get job status
                
                    $data = array(
                       'ready_for_billing' => 1,
                       'status'=>'completed',
                        'job_state'=>2
                    );
                    $this->db->where('id',$job_id);
                     
                    $this->db->update('job',$data);
                return $last_id;
                    
            }
        } else {
            return false;
        }
    }
    //Get expense details for a particular job
    function get_expense_details($job_id)
    {
        $this->db->select('expense_details.id as exp_id,job_id,exp_amount,exp_reason');
        $this->db->from('expense_details');
        $this->db->where('job_id',$job_id);
        $value=$this->db->get();

        $result=$value->result_array();
        
        if(!empty($result))
            $expense_id=$result[0]['exp_id'];
        else
            $expense_id='';
        //get expense images
        $img=array();
        if($expense_id!='')
        {
            $this->db->select('images');
            $this->db->from('expense_details_images');
            $this->db->where('expense_id',$expense_id);
            $value_image=$this->db->get();
            $result_image=$value_image->result_array();

            $img=array();
            //BASE_URL.DIR_EXPENSE_IMAGE.
            $index=-1;
            foreach($result_image as $single_img)
            {
                $img[++$index]=$single_img['images'];
            }
            //$img=rtrim($img,",");
            $result[0]['support_imgs']=$img;
        }
        // else
        // {
        //     $result[0]['support_imgs']=$img;

        // }
        
        unset($result[0]['id']);
        return $result;
    }
    function insert_expense_supported_images($expense_id, $uploaded_pics)
    {
        $expense_images_array=array(
            'expense_id'=>$expense_id,
            'images'=>$uploaded_pics
        );

        if ($this->db->insert('expense_details_images', $expense_images_array)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    function update_expense_supported_images($expense_id,$val)
    {
        $expense_images_array=array(
            
            'images'=>$val
        );
        $this->db->where('expense_id',$expense_id);
        $this->db->update('expense_details_images',$expense_images_array);
        return $this->db->affected_rows();
    }
    function submit_question_answers($question_answer_array)
    {
        if ($this->db->insert('question_answer_for_job', $question_answer_array)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    function insert_question_answer_supported_images($insert_id, $uploaded_pics)
    {
        $question_images_array=array(
            'question_answer_id'=>$insert_id,
            'image'=>$uploaded_pics
        );
        if ($this->db->insert('question_answer_images', $question_images_array)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    function submit_tasting_setup($question_answer_array)
    {
        if ($this->db->insert('tasting_setup', $question_answer_array)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    
    function insert_tasting_setup_images($insert_id, $uploaded_pics, $tasting_type)
    {
    //    echo $tasting_type;die;if (empty($var))
       if (!empty($tasting_type)){
        $question_images_array=array(
            'tasting_setup_id'=>$insert_id,
            'image'=>$uploaded_pics,
            'tasting_type'=>$tasting_type
        );
       }else{
        $question_images_array=array(
            'tasting_setup_id'=>$insert_id,
            'image'=>$uploaded_pics
        );
       }
        
        if ($this->db->insert('tasting_setup_images', $question_images_array)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    
    function confirm_wine($confirm_data)
    {
        if ($this->db->insert('confirm_or_unavailable_wine', $confirm_data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    function add_wine($data)
    {
        if ($this->db->insert('wine', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    function add_wine_images($wine_id,$image)
    {
        $data = array('wine_id' => $wine_id, 'image' => $image);
        $this->db->insert('wine_images', $data);
    }
    function check_job_status($job_id)
    {
        $this->db->select('job.status');
        $this->db->from('job');
        $this->db->where('job.id',$job_id);
        $result=$this->db->get()->result_array();
        if(isset($result[0]['status']));
        return $result[0]['status'];
    }
    function request_job_to_tester($data)
    {
        if ($this->db->insert('request_accepted_job_to_tester', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    function get_completed_job_details($job_id)
    {
        //$this->db->select("job.id as job_id,job.status,DATE_FORMAT(job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as actual_start_time,TIME_FORMAT(end_time, '%h:%i%p') as actual_end_time,job.job_start_time,job.finish_time as job_end_time,CONCAT(job.working_hour,' ','Hrs') as working_hour,store.id as store_id,store.name as store_name,store.adress as address,store.suite_number as suite_and_apt,store.phone as store_phone,store.latitude as store_latitude,store.longitude as store_longitude,store.zone as zone_id,job.taster_id,CONCAT(users.first_name,' ',users.last_name) as taster_name,job.taster_note,general_notes.general_note");
        $this->db->select("job.id as job_id,job.status,DATE_FORMAT(job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as actual_start_time,TIME_FORMAT(end_time, '%h:%i%p') as actual_end_time,job.job_start_time,job.finish_time as job_end_time,CONCAT(job.working_hour,' ','Hrs') as working_hour,store.id as store_id,store.name as store_name,store.adress as address,store.suite_number as suite_and_apt,store.phone as store_phone,store.latitude as store_latitude,store.longitude as store_longitude,store.zone as zone_id,job.taster_id,CONCAT(users.last_name,' ',users.first_name) as taster_name,job.taster_note,general_notes.general_note, job.agency_taster_id as agency_taster_id, job_rating.rating as rating, job_rating.feedback as feedback");
        $this->db->from('job');
        $this->db->join('store','job.store_id=store.id');
        $this->db->join('general_notes','general_notes.job_id=job.id','left');
        $this->db->join('job_rating', 'job_rating.job_id = job.id', 'left');
        $this->db->join('users','job.taster_id=users.id','left');
        $this->db->where('job.id',$job_id);
        $result=$this->db->get()->result_array();
        for($i=0;$i<count($result);$i++)
        {
            if($result[$i]['agency_taster_id']){

                $taster_id=$result[$i]['agency_taster_id'];
                $this->db->select('first_name, last_name');
                $this->db->from('users');
                $this->db->where('id',$taster_id);
                $getresult=$this->db->get()->row_array();
                //print_r($result);die;
                $result[$i]['taster_name']= $getresult['last_name'].' '.$getresult['first_name'];
                //echo $taster_id;die;

            }else{
                $taster_id=$result[$i]['taster_id'];
            }
        }
        //get completed job wine details
        $this->db->select("wine.id,wine.name,wine.description,wine.type as wine_type,wine.flavour as wine_flavour,wine.upc_code as upc,wine.brand,wine.year,CONCAT(wine.size,' ',' ml') as size,wine.category_id,wine_images.image as images,completed_job_wine_details.bottles_sampled,completed_job_wine_details.bottles_sold, completed_job_wine_details.open_bottles_sampled");
        $this->db->from('wine');
        $this->db->group_by('wine.id');
        $this->db->join('wine_images','wine.id=wine_images.wine_id','left');
        $this->db->join('completed_job_wine_details','wine.id=completed_job_wine_details.wine_id');
        $this->db->where('completed_job_wine_details.job_id',$job_id);
        $result_wine=$this->db->get()->result_array();
        for($i=0;$i<count($result_wine);$i++)
        {
            //array_push($wine_array_id_container,$val['id']);
            //get wine images
            $this->db->select('image');
            $this->db->from('wine_images');
            $this->db->where('is_deleted',0);
            $this->db->where('wine_id',$result_wine[$i]['id']);
            $query_image=$this->db->get();
            $wine_image_array=$query_image->result_array();
            //echo "<pre>";
            //print_r($wine_image_array);die;
            $image_name='';
            for($j=0;$j<count($wine_image_array);$j++)
            {
                $image_name.=$wine_image_array[$j]['image'].",";
            }
            $image_name=rtrim($image_name,",");
            //echo "<pre>";
            //print_r($wine_image_array);die;
            $result_wine[$i]['images']=$image_name;
            $image_name='';
        }
        $result[0]['wine_details']=$result_wine;
        return $result;
    }
    function check_tester_role($tablename,$tester_id)
    {
        $this->db->select('user_type');
        $this->db->from($tablename);
        $this->db->where('id',$tester_id);
        $result=$this->db->get()->row();
        return $result->user_type;
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
        return $accepted_no;
    }
    function get_accpted_tester_id($tablename,$job_id)
    {
        $this->db->select('accepted_by');
        $this->db->from($tablename);
        $this->db->group_by('accepted_by');
        $this->db->where('job_id',$job_id);
        $this->db->where('rejected_by',0);
        $result=$this->db->get()->result_array();
        if(!empty($result))
            $accepted_by=$result[0]['accepted_by'];
        else
            $accepted_by=0;
        return $accepted_by;
    }
    //Check tester zonewise for each job
    function check_tester_zonewise($store_id,$taster_id)
    {
        $this->db->select('zone');
        $this->db->from('store');
        $this->db->where('store.id',$store_id);
        $result2 = $this->db->get();
        $obj=$result2->row();
        if(!empty($obj))
            $zone_id=$obj->zone;
        else
            $zone_id=0;
        //echo $zone_id;die;
        //get tester or agency id from user meta
        $this->db->select('user_id,meta_value');
        $this->db->from('user_meta');
        $this->db->where('meta_key','zone');
        //$this->db->like('meta_value', $zone_id,'before');
        $result3 = $this->db->get();
        $id_array=$result3->result_array();
        $zone_id_array=array();
        foreach($id_array as $value)
        {
            $arr=explode(",",$value['meta_value']);
            if(in_array($zone_id,$arr))
            {
                array_push($zone_id_array,$value['user_id']);
            }
        }
        $id_string=array();
        foreach($zone_id_array as $id)
        {
            //$id_string.=$id['user_id'].",";
            array_push($id_string,$id);
        }
        //echo "<pre>";
       //print_r($id_string);die;
        //$id_string=rtrim($id_string,",");
        $this->db->select('*');
        $this->db->from('users');
        if(!empty($id_string))
            $this->db->where_in('id',$id_string);
        $this->db->where('user_type','tester');
        $this->db->or_where('user_type','agency');
        $this->db->where('status','active');
        $result_user = $this->db->get();
        //echo $this->db->last_query();die;
        $user_details=$result_user->result_array();
        $user_id_array=array();
        foreach($user_details as $details)
        {
            array_push($user_id_array,$details['id']);
        }
        
        if(in_array($taster_id,$user_id_array))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    function setInvoiceNumber($job_id){
        $job=$this->get_job_details($job_id);
        //print_r($job);die;
        if(!$job->invoice_number){
                $invoice_date = date('Y-m-d H:i:s');
            //generate invoice id using tasting date
                $this->db->select("tasting_date, id");
            $result=$this->db->get_where('job', array('job.tasting_date' => $job->tasting_date, 'is_deleted' => 0, 'ready_for_billing' =>1))->result_array();
            // print_r($result);die;
            $no_of_jobs= count($result);
           // echo count($result);die;
            //print_r($more_job_info);die;
            //echo "current job id: ".$job_id." ";
            $invoice_number='';
            $array=explode("-",$job->tasting_date);
            $invoice_id=$array[0].$array[1].$array[2];

                if($no_of_jobs==0){
                    $invoice_number=$invoice_id;
                    
                }else{
                    $alphabet = $this->num2alpha(--$no_of_jobs);
                    $invoice_number= $invoice_id.$alphabet;
                    
                }
            
            $data = array(
                'invoice_date'=>$invoice_date,
                'invoice_number'=>$invoice_number
            );
            $this->db->where('id',$job_id);
            $this->db->update('job',$data);
        }


    }
    function get_job_details($job_id)
    {
        $this->db->select('status,taster_id,tasting_date,invoice_number');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get()->row();
        return $result;
    }
    function job_info($job_id)
    {
        $this->db->select('tasting_date, start_time, end_time, taster_id, agency_taster_id, store_id,status, accept_status, job_state, job_status');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get()->row();
        return $result;
    }
    function delete_accept_reject_data($tablename,$job_id)
    {
        $this->db->delete($tablename, array('job_id' => $job_id));
    }
    function check_data($tablename,$job_id)
    {
        $this->db->select('*');
        $this->db->from($tablename);
        $this->db->where('job_id',$job_id);
        $value=$this->db->get();
        return $value->num_rows();
    }
    function check_data_image($tablename,$fieldname,$qaID)
    {
        $this->db->select('*');
        $this->db->from($tablename);
        $this->db->where($fieldname,$qaID);
        $value=$this->db->get();
        return $value->num_rows();
    }
    function get_id($tablename,$job_id)
    {
        $this->db->select('id');
        $this->db->from($tablename);
        $this->db->where('job_id',$job_id);
        $value=$this->db->get();
        $result=$value->row();
        return $result->id;
        
    }
    function get_tasting_setup_id($tablename,$job_id)
    {
        $this->db->select('id');
        $this->db->from($tablename);
        $this->db->where('job_id',$job_id);
        $value=$this->db->get();
        $result=$value->result_array();
        if(!empty($result))
        {
            return $result;
        }
    }
    function delete_data($tablename,$job_id)
    {
        $this->db->delete($tablename, array('job_id' => $job_id));
        
    }
    function delete_images($tablename,$fieldname,$id)
    {
        $this->db->delete($tablename, array($fieldname => $id));
    }
    function update_table($tablename,$question_answer_array,$qaID)
    {
        
        $this->db->where('id', $qaID);
        $this->db->update($tablename, $question_answer_array); 
        return $qaID;
    }
    function delete_old_expense_images($tablename,$expense_id)
    {
        $this->db->delete($tablename, array('expense_id' => $expense_id));
    }
    function get_signature_and_comment($job_id)
    {
        $this->db->select('*');
        $this->db->from('manager_verification_details');
        $this->db->where('job_id',$job_id);
        $result=$this->db->get()->row();
        return $result;
    }
    function get_question_answer($job_id)
    {
        $this->db->select('question_answer_for_job.ans_type,question_answer_for_job.ans_text,question_answer.question');
        $this->db->from('question_answer_for_job');
        $this->db->join('question_answer','question_answer_for_job.question_id=question_answer.id');
        $this->db->where('question_answer_for_job.job_id',$job_id);
        $result=$this->db->get()->result_array();
        return $result;
    }
    function get_more_job_info($job_id)
    {
        //echo $job_id;die;
        $this->db->select("DATE(job.tasting_date) as sampling_date,CONCAT_WS(' ',ua.last_name,ua.first_name) as taster_name,store.name as store_name,store.zipcode as store_zipcode,store.adress as store_adress,job.start_time,job.end_time,job.job_start_time,job.finish_time,job.working_hour,job.taster_id,job.agency_taster_id,job.taster_rate,job.taster_id,CONCAT_WS(' ',ub.last_name,ub.first_name) as sales_rep_name,user_meta.meta_value as phone");
        $this->db->from('job');
        $this->db->join('users ua','job.taster_id=ua.id','left');
        $this->db->join('store','job.store_id=store.id','left');
        $this->db->join('users ub','job.user_id=ub.id','left');
        $this->db->join('user_meta','ub.id=user_meta.user_id','left');
        $this->db->where('job.id',$job_id);
        $this->db->where('user_meta.meta_key','phone');
        $result=$this->db->get()->row();
        //Get sampling details
        $this->db->select('wine.name, wine.size ,wine.brand, completed_job_wine_details.bottles_sampled,completed_job_wine_details.open_bottles_sampled, completed_job_wine_details.bottles_sold, UOM');
        $this->db->from('completed_job_wine_details');
        $this->db->join('wine','completed_job_wine_details.wine_id=wine.id');
        $this->db->where('completed_job_wine_details.job_id',$job_id);
        $result_wine=$this->db->get()->result_array();
        $result->wine_sampled_details=$result_wine;
        $result->actual_taster_name=$result->taster_name;
            if($result->agency_taster_id==0)
            $taster_id=$result->taster_id;
            else{
                $agency_taster_id=$result->agency_taster_id;
                $this->db->select('first_name');
                $this->db->select( 'last_name');
                $this->db->from('users');
                $this->db->where('users.id',$agency_taster_id);
                $fullName=$this->db->get()->row();
                //print_r($value);die;
                $result->actual_taster_name=$fullName->last_name.' '.$fullName->first_name;
               // echo $result->actual_taster_name;die;
            }
       //echo $result->taster_id;die;
        //Get taster or agency name
        $user_info=$this->get_user_type('users',$result->taster_id);
        /*echo "<pre>";
        print_r($user_info);die;*/
        $user_type_1=$user_info[0]->user_type;
        //echo $user_type_1;die;
        if($user_type_1=='agency')
        {
            $agency_name=$this->get_agency_name('user_meta',$result->taster_id);
            //echo $agency_name;die;
            $result->taster_name=$agency_name;
        }
        
/*        //Calculate total amount
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
        if($user_meta)
            $rate_per_hr=$user_meta->meta_value;
        else
            $rate_per_hr=0;*/
        $rate_per_hr=$result->taster_rate;
        $result->rate_per_hr=$rate_per_hr;
        //Calculate job actual time difference
        $difference=strtotime($result->end_time)-strtotime($result->start_time);
        $actual_time_difference=gmdate("H:i", $difference);
        //Convert working hour to minutes
        $time = explode(':', $result->working_hour);
        $total_minutes= ($time[0]*60) + ($time[1]) + ($time[2]/60);

        $total_amount=number_format((($rate_per_hr / 60)*$total_minutes),2);
        $result->total_amount="$".$total_amount;
        $result->actual_time_difference=$actual_time_difference;
        //Calculate expense amount
        $this->db->select('expense_details.id as exp_id,exp_amount,exp_reason');
        $this->db->from('expense_details');
        $this->db->where('job_id',$job_id);
        $expense_details=$this->db->get()->row();

        $expense_amount=$expense_details->exp_amount;
        $expense_reason=$expense_details->exp_reason;

        $result->expense_amount=$expense_amount;
        $result->expense_reason=$expense_reason;
        

        //Get expense images
        $this->db->select('images as exp_images');
        $this->db->from('expense_details_images');
        $this->db->where('expense_id',$expense_details->exp_id);
        $v=$this->db->get()->result_array();
        $result->expense_images=$v;

        //Get general note
        $general_note=$this->get_general_note($job_id);
        $result->general_note=$general_note;

        //Get admin note
        $admin_note=$this->get_admin_note($job_id);
        $result->admin_note=$admin_note;
        //echo "<pre>";
        //print_r($result);die;
        
        return $result;
    }
    function get_question_answers_for_job($job_id)
    {
        $this->db->select('question_answer_for_job.*,question_answer.question');
        $this->db->from('question_answer_for_job');
        $this->db->join('question_answer','question_answer_for_job.question_id=question_answer.id');
        $this->db->where('question_answer_for_job.job_id',$job_id);
        $result=$this->db->get()->result_array();
        for($i=0;$i<count($result);$i++)
        {
            $qa_id=$result[$i]['id'];
            //Get image
            $this->db->select('image');
            $this->db->from('question_answer_images');
            $this->db->where('question_answer_id',$qa_id);
            $value=$this->db->get();
            $result_iamge=$value->result_array();
            $result[$i]['image']=$result_iamge;
        }
        return $result;

    }
    function move_to_archive($id_container)
    {
        $data = array('is_archived'=>1);
        $this->db->where_in('id', $id_container);
        $this->db->update('job', $data); 
    }
    function get_setup_image($tablename,$setup_id)
    {
        $this->db->select('image,tasting_type');
        $this->db->from($tablename);
        $this->db->where_in('tasting_setup_id',$setup_id);
        $value=$this->db->get();
        return $value->result_array();
    }
    function get_store_for_sales_rep($sales)
    {
        $this->db->select('*');
        $this->db->from('store');
        $qr="(store.sales_rep LIKE '%$sales%')";
        $this->db->where($qr);
        $this->db->where('store.status','active');
        $this->db->where('store.is_deleted',0);
        $this->db->order_by('store.name','ASC');
        $value=$this->db->get();
        //echo $this->db->last_query();die;
        return $value->result_array();
    }
    function get_sales_rep_store($sales)
    {
        $this->db->select('*');
        $this->db->from('store');
        $qr="(store.sales_rep LIKE '%$sales%')";
        $this->db->where($qr);
        $this->db->where('store.status','active');
        $this->db->where('store.is_deleted',0);
        $value=$this->db->get();
        //echo $this->db->last_query();die;
        return $value->result();
    }
    function get_wine_using_search_key($store_id,$search_key)
    {
        if($store_id!='')
        {
            $this->db->select('wine_sell_type');
            $this->db->from('store');

            $this->db->where('id',$store_id);
            $val=$this->db->get()->row();
            $result=$val->wine_sell_type;
        }
            
        $this->db->select('*');
        $this->db->from('wine');
        $this->db->like('wine.name',$search_key);
        $this->db->where('wine.is_deleted',0);
        if($store_id!='')
        {
            if($result=='royal')
            {
                $this->db->where('wine.flavour','royal');
            }
            if($result=='mix')
            {
                $this->db->where('wine.flavour','mix');
            }
        }
        $this->db->order_by('name','ASC');
        $store = $this->db->get();
        return $store->result();
    }
    function get_expense_with_brand($job_id)
    {
        $this->db->select('wine.name,wine.size,wine.brand,completed_job_wine_details.bottles_sampled, completed_job_wine_details.open_bottles_sampled, completed_job_wine_details.bottles_sold, UOM');
        $this->db->from('completed_job_wine_details');
        $this->db->join('wine','completed_job_wine_details.wine_id=wine.id');
        $this->db->where('completed_job_wine_details.job_id',$job_id);
        $result_wine=$this->db->get()->result_array();
        return $result_wine;
    }
    function get_expense_amount($job_id)
    {
        $this->db->select('expense_details.id as exp_id,exp_amount,exp_reason');
        $this->db->from('expense_details');
        $this->db->where('job_id',$job_id);
        $expense_details=$this->db->get()->row();
       // echo "<pre>";print_r($expense_details);die;
        return $expense_details;
    }
    function get_brand()
    {
        $this->db->select('brand');
        $this->db->from('wine');
        $this->db->where('is_deleted',0);
        $this->db->group_by('wine.brand');
        $value=$this->db->get();
        return $value->result_array();
        
    }
    function get_taster_for_report()
    {

        $this->db->select('*');
        $this->db->from('users');
        
        $this->db->where('status','active');
        $this->db->where('users.is_deleted',0);
        $qr="(user_type='tester')";
        $this->db->where($qr);
        $this->db->order_by('users.last_name','ASC');
        $result_user = $this->db->get();
        $user_details=$result_user->result_array();
        return $user_details;
    }

    function get_agency_for_report()
    {

        $this->db->select('*');
        $this->db->from('users');
        
        $this->db->where('status','active');
        $this->db->where('users.is_deleted',0);
        $qr="(user_type='agency')";
        $this->db->where($qr);
        $this->db->order_by('users.last_name','ASC');
        $result_user = $this->db->get();
        $user_details=$result_user->result_array();
        return $user_details;
    }
    function get_brandwise_expense($brand, $from_date, $to_date, $taster, $agency, $store, $sales_rep, $wine_type, $size, $month)
    {


        //Get wine according to brand
        $this->db->select('wine.id as wine_id, wine.brand, wine.type, wine.name, wine.UOM');
        $this->db->from('wine');

        if(count($wine_type)>0){
            $this->db->where_in('wine.id',$wine_type);
        }

       
        $this->db->where('wine.is_deleted',0);
        $val=$this->db->get();
        $result_wine_arr=$val->result_array();
     
      
        $selected_wine_id_array=array();

        if(count(result_wine_arr)>0){
            foreach($result_wine_arr as $w)
            {
                array_push($selected_wine_id_array,$w['wine_id']);
            }
        }
    
        //End
        $from_date=date('Y-m-d',strtotime($from_date));
        $to_date=date('Y-m-d',strtotime($to_date));
        $this->db->select("job.id as job_id, job.wine_id as job_wine_id, job.tasting_date, job.working_hour, job.taster_rate, store.name as store_name, CONCAT(ub.last_name,' ',ub.first_name) as sales_rep_name, job.taster_id, job.agency_taster_id");
        $this->db->from('job');
        $this->db->join('store','job.store_id=store.id','left');
        $this->db->join('users ub','job.user_id=ub.id','left');
        $this->db->where('job.tasting_date >=',$from_date);
        $this->db->where('job.tasting_date <=',$to_date);
       
        if(count($store)>0){
            $this->db->where_in('job.store_id',$store);
        }

        if(count($taster)>0){

            $selected_tasters = implode(',', $taster);
            $this->db->where("(job.taster_id in($selected_tasters) OR job.agency_taster_id in($selected_tasters))");
        }

        if(count($agency)>0){
            $this->db->where_in('job.taster_id',$agency);
        }

        if(count($sales_rep)>0){
            $this->db->where_in('job.user_id',$sales_rep);
        }

        if(count($month)>0){
            $this->db->where_in('MONTH(job.tasting_date)', $month);
        }
       
        $this->db->where('job.ready_for_billing',1);
        $this->db->where('job.is_deleted',0);
        $this->db->order_by('job.tasting_date','DESC');
        $result=$this->db->get()->result_array();

        $result_wine = array();
    
        for($i=0;$i<count($result);$i++)
        {


            $job_id=$result[$i]['job_id'];
            
            // Sampled and Sold Bottles..
            $this->db->select('completed_job_wine_details.bottles_sampled, completed_job_wine_details.bottles_sold, completed_job_wine_details.open_bottles_sampled, completed_job_wine_details.wine_id,wine.brand, wine.name as wine_type, wine.UOM as wine_uom, wine.size as wine_size');
            $this->db->from('completed_job_wine_details');
            $this->db->join('wine','completed_job_wine_details.wine_id=wine.id','left');
            $this->db->where('completed_job_wine_details.job_id=',$job_id);
            $expense_bottles=$this->db->get()->result_array();

           

            $expense_amount=$this->get_expense_amount($job_id);
            if(!empty($expense_amount))
            {
                $time = explode(':',$result[$i]['working_hour']);
                $total_minutes= ($time[0]*60) + ($time[1]) + ($time[2]/60);
                
                $rate_per_hr=$result[$i]['taster_rate'];
                $dta=json_decode(json_encode($expense_amount), true);
                $exp_amount=ltrim($dta['exp_amount'], '$');
                $total_amount=number_format((($rate_per_hr / 60)*$total_minutes),2)+$exp_amount;
            }

            // Taster & Agency...
            $taster_id=$result[$i]['taster_id'];
            $agency_taster_id=$result[$i]['agency_taster_id'];
            $user_type=$this->get_user_type('users',$taster_id);
           
            $agency_name='N/A';
            $taster_name='N/A';
            if($taster_id>0){ 
                 //get tester name
                 $this->db->select("CONCAT(last_name, ' ',first_name) as taster_name");
                 $this->db->from('users');
                 if($agency_taster_id > 0){
                    $this->db->where('id',$agency_taster_id);
                   
                 }else{
                    $this->db->where('id',$taster_id);
                 }
                 
                 $tester_result=$this->db->get()->row();
                 $taster_name=$tester_result->taster_name;
                 $result[$i]['taster_name']=$taster_name;

                 $result[$i]['agency_name']='N/A';
                 
                 if($agency_taster_id > 0){
                    $agency_name=$this->get_agency_name('user_meta',$taster_id);
                    $result[$i]['agency_name']=$agency_name;
                 }
                 
        }

          if(count($expense_bottles)>0){

            $tastingDate = $result[$i]['tasting_date'];
            $job_id=$result[$i]['job_id'];
            $store_name = $result[$i]['store_name'];
            $result_has_wine=$result[$i]['has_wine'];
            $sales_rep_name = $result[$i]['sales_rep_name'];
           

            for($a=0;$a<count($expense_bottles);$a++){

              $wine_count = count($expense_bottles);
              $individual_amount=number_format((float)$total_amount/$wine_count, 2, '.', '');
             
              $expense_bottles[$a]['tasting_date'] = $tastingDate;
              $expense_bottles[$a]['job_id'] = $job_id;
              $expense_bottles[$a]['store_name']= $store_name;
              $expense_bottles[$a]['taster_name']= $taster_name;
              $expense_bottles[$a]['agency_name']= $agency_name;
              $expense_bottles[$a]['sales_rep_name'] = $sales_rep_name;
              $expense_bottles[$a]['has_wine']='yes';
            
              $expense_bottles[$a]['expense_amount'] = '$'.$individual_amount;


              if(count($selected_wine_id_array)>0 && $expense_bottles[$a]['has_wine'] !='no'){

                $job_wineID = $expense_bottles[$a]['wine_id'];
               
                foreach($selected_wine_id_array as $blank_wineId){
                  if($job_wineID == $blank_wineId) {
                      $expense_bottles[$a]['has_wine']='yes';
                      break;
                    }else{
                      $expense_bottles[$a]['has_wine']='no';
                    }
                } 
            }

                if(count($size)>0 && $expense_bottles[$a]['has_wine'] !='no'){
                    
                    foreach ($size as $wine_size){
                        if (strtolower($expense_bottles[$a]['wine_uom']) == strtolower($wine_size)) {
                            $expense_bottles[$a]['has_wine']='yes';
                            break;
                        }else{
                            $expense_bottles[$a]['has_wine']='no';
                        }
                      }
                }

                if(count($brand)>0 && $expense_bottles[$a]['has_wine'] !='no'){

                    foreach ($brand as $selected_brand_name){

                        $brand_name = strtolower($expense_bottles[$a]['brand']);
                        $brand_name = trim($brand_name);

                        $selected_brand_name = strtolower($selected_brand_name);
                        $selected_brand_name = trim($selected_brand_name);

                        if ($brand_name == $selected_brand_name) {
                            $expense_bottles[$a]['has_wine']='yes';
                            break;
                        }else{
                            $expense_bottles[$a]['has_wine']='no';
                        }
                      }
                }

        }
    }
             array_push($result_wine,$expense_bottles);
            //End
        }
        return $result_wine;
    }

    public function get_csv_brandwise($checked_id=array(),$filter = array(), $order = null, $dir = null, $count = false) {


        //Need to get an array of id's
          $ids = array();
        
          foreach($checked_id as $val) {
             array_push($ids, $val);
          }

        
        $this->db->select("job.id,job.store_id,job.wine_id,job.user_id,job.taster_id,job.agency_taster_id,job.updated_on,job.tasting_date, job.working_hour, job.taster_rate, expense_details.date as billing_date, expense_details.exp_amount,completed_job_wine_details.wine_id as sampled_and_sold_wine, CONCAT(first_name, ' ',last_name) as taster_name, user_meta.meta_value as account_no");

        $this->db->from('job');
        $this->db->join('completed_job_wine_details','job.id=completed_job_wine_details.job_id');
        $this->db->join('expense_details','job.id=expense_details.job_id');
        
        $this->db->join('users','expense_details.taster_id=users.id');
        $this->db->join('user_meta','expense_details.taster_id=user_meta.user_id');
        $this->db->order_by('job.id','DESC');
        $this->db->where_in('job.id', $ids);
        //$this->db->where('job.is_deleted',0);
        //$this->db->where('job.ready_for_billing',1);
        $this->db->where('user_meta.meta_key','manual_account_number');
       
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
        $result=$this->db->get()->result_array();
  

        $new_data_array=array();
        for($i=0;$i<count($result);$i++)
        {
            //Get total amount
            $job_id=$result[$i]['id'];
       
            $sales_rep_id=$result[$i]['user_id'];
            //get name
            $sales_rep_name=$this->get_user_name($sales_rep_id);
            $result[$i]['sales_rep']=$sales_rep_name;
            
             // Taster & Agency...
             $taster_id=$result[$i]['taster_id'];
             $agency_taster_id=$result[$i]['agency_taster_id'];
             $user_type=$this->get_user_type('users',$taster_id);
             $agency_name='N/A';
             $taster_name='N/A';
 
             if($taster_id>0){ 
                 //get tester name
                 $this->db->select("CONCAT(first_name, ' ',last_name) as taster_name");
                 $this->db->from('users');
                 if($agency_taster_id > 0){
                    $this->db->where('id',$agency_taster_id);
                   
                 }else{
                    $this->db->where('id',$taster_id);
                 }
                 
                 $tester_result=$this->db->get()->row();
                 $taster_name=$tester_result->taster_name;
                 $result[$i]['taster_name']=$taster_name;
 
                 if($agency_taster_id > 0){
                    $agency_name=$this->get_agency_name('user_meta',$taster_id);
                    $result[$i]['agency_name']=$agency_name;
                 }
             }
 
 
            //Get store
            $store_id=$result[$i]['store_id'];
            $this->db->select('name');
            $this->db->from('store');
            $this->db->where('store.id',$store_id);
            
            $value = $this->db->get()->row();
            $store_name=$value->name;
            $result[$i]['store_name']=$store_name;
          
            // Change Date format
            $tasting_date=date('m-d-Y',strtotime($result[$i]['tasting_date']));
            $tasting_month=date('F',strtotime($result[$i]['tasting_date']));

            // Sampled and Sold Bottles..
            $this->db->select('completed_job_wine_details.bottles_sampled, completed_job_wine_details.bottles_sold, completed_job_wine_details.wine_id,wine.brand, wine.name as wine_type, wine.UOM as wine_uom, wine.size as wine_size');
            $this->db->from('completed_job_wine_details');
            $this->db->join('wine','completed_job_wine_details.wine_id=wine.id','left');
            $this->db->where('completed_job_wine_details.job_id=',$job_id);
            $expense_bottles=$this->db->get()->result_array();


            $expense_amount=$this->get_expense_amount($job_id);
            $more_job_info=$this->get_more_job_info($job_id);
            $no_of_wine_sampled=count($expense_bottles);
            $dta=json_decode(json_encode($expense_amount), true);
            $amount=ltrim($dta->exp_amount,"$");
            $amount_on_rate=ltrim($more_job_info->total_amount,"$");
            $actual_amount=$amount+$amount_on_rate;
            if($actual_amount>0){
                $individual_amount=number_format((float)$actual_amount/$no_of_wine_sampled, 2, '.', '');
            }
            

            $wine_data_array=array();
          if(count($expense_bottles)>0){

            for($a=0;$a<count($expense_bottles);$a++){


                $arr=array();
               
                $wine_size=(double) $expense_bottles[$a]['wine_size'].''.$expense_bottles[$a]['wine_uom'];
                $arr['tasting_date']=$tasting_date;
                $arr['tasting_month']=$tasting_month;
                $arr['wine_brand']=$expense_bottles[$a]['brand'];
                $arr['wine_type']=$expense_bottles[$a]['wine_type'];
                $arr['wine_size']=$wine_size;
                $arr['store_id']=$store_name;
                $arr['taster_name']=$taster_name;
                $arr['agency_name']=$agency_name;
                $arr['sales_rep']=$sales_rep_name;
                $arr['bottles_sampled']=$expense_bottles[$a]['bottles_sampled'];
                $arr['bottles_sold']=$expense_bottles[$a]['bottles_sold'];
                $arr['expense_amount'] = "$".$individual_amount;


                array_push($wine_data_array,$arr);
           
                    }
                }
    
             array_push($new_data_array,$wine_data_array);
           
    
        }

        return $new_data_array;
    }
    function get_todays_job($todays_date)
    {
        $this->db->select("*");
        $this->db->from('job');
        $this->db->where('job.tasting_date',$todays_date);
        $this->db->where('job.is_deleted',0);
        $qr="(status='accepted' OR status='approved')";
        $this->db->where($qr);
        $result=$this->db->get()->result_array();
        return $result; 
    }
	function get_todays_jobs($todays_date)
    {
      	$query = $this->db->query("SELECT *, count(`taster_id`) as tester_jobs FROM `job` WHERE `tasting_date`='$todays_date' and `is_deleted`=0 and (`status`='accepted' OR `status`='approved') group by `taster_id` HAVING count(`taster_id`)");
		return $query->result_array();
    }
    public function check_job_between_two_hour($current_server_time,$next_two_hour_time,$start_time)
    {
        $bounded_time=date("H:i:s", strtotime("12:30 PM"));
        $this->db->select("*");
        $this->db->from('job');
        $this->db->where('job.start_time >',$bounded_time);
        $this->db->where('job.start_time >=',$current_server_time);
        $this->db->where('job.start_time <=',$next_two_hour_time);
        $result=$this->db->get();
        //echo $this->db->last_query();die;
        $row=$result->num_rows();
        if($row > 0)
            return 'true';
        else
            return 'false';
    }
    public function get_wine_names($wine_id_array)
    {
        $this->db->select("wine.name");
        $this->db->from('wine');
        $this->db->where_in('wine.id',$wine_id_array);
        $result=$this->db->get();
        return $result->result_array();
    }
	public function deleteJob($jobId){
		$this->db->set('is_deleted', 1); //value that used to update column  
		$this->db->where('id', $jobId); //which row want to upgrade  
		if($this->db->update('job')){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function get_rej_username($jobId){
		$this->db->select("taster_id");
        $this->db->from('job');
        $this->db->where('id',$jobId);
        $result=$this->db->get()->row();
		
		$this->db->select("*");
        $this->db->from('users');
        $this->db->where('id',$result->taster_id);
        $user=$this->db->get()->row();
		if($user->user_type=='agency'){
			$this->db->select('meta_value');
			$this->db->from('user_meta');
			$this->db->where('user_id',$result->taster_id);
			$this->db->where('meta_key','agency_name');
			$name=$this->db->get()->row('meta_value');
		}else{
			$name = $user->first_name.''.$user->last_name;
		}
		
        return $name;
    }
    public function num2alpha($n) { 
        $r = ''; 
        for ($i = 1; $n >= 0 && $i < 10; $i++) { 
            $r = chr(0x41 + ($n % pow(26, $i) / pow(26, $i - 1))) . $r; $n -= pow(26, $i); 
            
        } 
        $a = strtolower($r);
        return $a; 
        
    }
    function get_store_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('store');
        $this->db->where('store.status','active');
        $this->db->where('store.is_deleted',0);
        $this->db->where('store.id',$id);
        $this->db->order_by('store.name','ASC');
        $store = $this->db->get();
        return $store->result();
    }
    function get_sales_rep_by_store_id($id=0)
    {
        $this->db->select('sales_rep');
        $this->db->from('store');
        $this->db->where('store.status','active');
        $this->db->where('store.is_deleted',0);
        $this->db->where('store.id',$id);
        $store = $this->db->get();
        $sales_rep= $store->result();
        $sales_rep_array=explode('#', $sales_rep[0]->sales_rep);
        //print_r($sales_rep_array);die;
        //$salesRepDetails=array();
        $sales_rep_details_array=array();
        foreach($sales_rep_array as $slsrp){
            $salesRepDetails=$this->get_sales_rep_details_by_id($slsrp);
            array_push($sales_rep_details_array, $salesRepDetails);
        } 
        return $sales_rep_details_array;
    }

    function get_sales_rep_details_by_id($id){

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('users.id',$id);
        $this->db->where('status','active');
        $this->db->where('users.is_deleted',0);
        $this->db->where('users.user_type','sales_rep');
        $result_user = $this->db->get()->row();
        $user_details=json_decode(json_encode($result_user), true);
        return $user_details;
    }
    function get_store_name($store_id){
        $this->db->select('name, adress');
        $this->db->from('store');
        $this->db->where('store.id',$store_id);
        $value = $this->db->get()->row();
        return $value;
    }
    function get_store_name_mail($job_id){
        // $this->db->select("job.*,store.name as store_name");
        $this->db->select("job.*,store.name as store_name, store.adress as store_address");
        $this->db->from('job');
        $this->db->join('store','job.store_id=store.id','left');
        $this->db->where('job.id',$job_id);
        $value = $this->db->get()->result_array();
    
        return $value;
    }
    function get_wines_sampled_sold_details($job_id){
        $this->db->select('wine.name,wine.id,wine.size,wine.brand, completed_job_wine_details.bottles_sampled,completed_job_wine_details.open_bottles_sampled, completed_job_wine_details.bottles_sold, wine.UOM');
        $this->db->from('completed_job_wine_details');
        $this->db->join('wine','completed_job_wine_details.wine_id=wine.id');
        $this->db->where('completed_job_wine_details.job_id',$job_id);
        $result_wine=$this->db->get()->result_array();
        return $result_wine;
    }
    function delete_jobs($tablename,$job_id)
    {
        $this->db->where('id', $job_id);
        $this->db->update($tablename, array('is_deleted' => 1)); 
    }
    function get_completed_job_info($job_id)
    {
    //$this->db->select('tasting_date,job_start_time,finish_time, taster_id, agency_taster_id,wine_id');
    $this->db->select('user_id,tasting_date,job_start_time,finish_time, taster_id, agency_taster_id,wine_id,store_id');
    $this->db->from('job');
    $this->db->where('id',$job_id);
    $result=$this->db->get()->row();
    return $result;
    }

    function getTasterName($tester_id)
    {
        $this->db->select("CONCAT(last_name, ' ',first_name) as taster_name");
        $this->db->from('users');
        $this->db->where('users.id',$tester_id);
        $result=$this->db->get()->row();
        return $result;
    }

    public function get_mail_wine_names($job_id)
    {
    $this->db->select("wine_id");
    $this->db->from('completed_job_wine_details');
    $this->db->where_in('job_id',$job_id);
    $this->db->order_by('wine_id','ASC');
    $result=$this->db->get();
    $result= $result->result_array();
    
    $index=-1;
    for($indx=0;$indx< count($result);$indx++){
        $wine_id_array[++$index]=$result[$indx]['wine_id'];
    }

    $this->db->select("wine.name,wine.id");
    $this->db->from('wine');
    $this->db->where_in('wine.id',$wine_id_array);
    $this->db->order_by('wine.id','ASC');
    $result=$this->db->get();
    $data=$result->result_array();
    //print_r($data);die;

    $this->db->select('image,wine_id');
    $this->db->from('wine_images');
    $this->db->where('is_deleted',0);
    $this->db->where_in('wine_id',$wine_id_array);
    $this->db->order_by('wine_id','ASC');
    $query_image=$this->db->get();
    $query_image = $query_image->result_array();

    $this->db->select('bottles_sold');
    $this->db->select('bottles_sold,bottles_sampled,open_bottles_sampled');
    $this->db->from('completed_job_wine_details');
    // $this->db->where('is_deleted',0);
    $this->db->order_by('wine_id','ASC');
    $this->db->where('job_id',$job_id);
    $sold_wine=$this->db->get();
    $sold_wine=($sold_wine->result_array());

    $wine_name_array=array();
    $index=-1;
    for($indx=0;$indx< count($data);$indx++){
        ++$index;
        $wine_name_array[$index]['name']=$data[$indx]['name'];
        $wine_name_array[$index]['soldwine']=$sold_wine[$indx]['bottles_sold'];
        $wine_name_array[$index]['usedwine']=$sold_wine[$indx]['bottles_sampled'];
        $wine_name_array[$index]['open_bottles_sampled']=$sold_wine[$indx]['open_bottles_sampled'];
        $wine_name_id=$data[$indx]['id'];

        $wine_img_url=BASE_URL('assets/images/dummy-wine.jpg');
        for($idx=0;$idx< count($query_image);$idx++){
            if($wine_name_id==$query_image[$idx]['wine_id'])
            {
                $wine_img_url=BASE_URL.DIR_WINE_PICTURE.$query_image[$idx]['image'];
               break;
            }

        }
        $wine_name_array[$index]['image']=$wine_img_url;
        // if(count($query_image)>$indx){
        //     $wine_name_array[$index]['image']=BASE_URL.DIR_WINE_PICTURE.$query_image[$indx]['image'];
        //     //$wine_name_array[$index]['image']=BASE_URL.DIR_WINE_PICTURE.'expense-5122902520190124023236.jpg';
        // }else{
        //     $wine_name_array[$index]['image']=BASE_URL('assets/images/dummy-wine.jpg');
        // }
    }
    // $index=-1;C:\xampp\htdocs\wine\assets\wine\expense-5122902520190124023236.jpg
    // for($indx=0;$indx< count($query_image);$indx++){
    //     $wine_image_array[++$index]=BASE_URL.DIR_WINE_PICTURE.$query_image[$indx]['image'];
    // }
    //base_url(DIR_WINE_PICTURE.$product->image);
    return ($wine_name_array);
        //print_r($wine_name_array);die;
    }


    function getManagerName($jobid)
    {
        $this->db->select("first_name as manager_name");
        $this->db->from('manager_verification_details');
        $this->db->where('manager_verification_details.job_id',$jobid);
        $result=$this->db->get()->result_array();
        return $result;
    }

    function get_store_mail($job_id){
        $this->db->select("store_id");
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get()->row_array();
         $result= $result['store_id'];

        $this->db->select("email");
        $this->db->from('store');
        $this->db->where('id',$result);
        $result=$this->db->get()->row_array();
        $result= $result['email'];
        return $result;
    }
    function get_mail_selsrep_name($job_id){
        $this->db->select("CONCAT_WS(' ',ub.last_name,ub.first_name) as sales_rep_name");
        $this->db->from('job');
        $this->db->join('users ub','job.user_id=ub.id','left');
        $this->db->where('job.id',$job_id);
        $result=$this->db->get()->row();
      //   print_r($result);die;
        return $result;
  }


  public function get_assign_mail_wine_names($wine_id_array)
  {
 
  $this->db->select("wine.name,wine.id");
  $this->db->from('wine');
  $this->db->where_in('wine.id',$wine_id_array);
  $this->db->order_by('wine.id','ASC');
  $result=$this->db->get();
  $data=$result->result_array();
  //print_r($data);die;

  $this->db->select('image,wine_id');
  $this->db->from('wine_images');
  $this->db->where('is_deleted',0);
  $this->db->where_in('wine_id',$wine_id_array);
  $this->db->order_by('wine_id','ASC');
  $query_image=$this->db->get();
  $query_image = $query_image->result_array();


  $wine_name_array=array();
  $index=-1;
  for($indx=0;$indx< count($data);$indx++){
      ++$index;
      $wine_name_array[$index]['name']=$data[$indx]['name'];
      $wine_name_id=$data[$indx]['id'];

      $wine_img_url=BASE_URL('assets/images/dummy-wine.jpg');
      for($idx=0;$idx< count($query_image);$idx++){
          if($wine_name_id==$query_image[$idx]['wine_id'])
          {
              $wine_img_url=BASE_URL.DIR_WINE_PICTURE.$query_image[$idx]['image'];
             break;
          }

      }
      $wine_name_array[$index]['image']=$wine_img_url;
     
  }
//   echo "<pre>";
//   print_r($wine_name_array);die;
  return ($wine_name_array);
      
  }

}
/* End of file user_model.php */
/* Location: ./application/models/user_model.php */