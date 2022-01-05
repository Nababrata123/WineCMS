<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bulk_schedule_job_model extends CI_Model {
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
        $qr="((bulk_schedule_job.start_time < bulk_schedule_job.job_start_time) OR (bulk_schedule_job.end_time > bulk_schedule_job.end_time))";
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
        $this->db->select("bulk_schedule_job.id as job_id,bulk_schedule_job.working_hour,bulk_schedule_job.start_time as job_scheduled_start_time,bulk_schedule_job.end_time as job_scheduled_end_time,bulk_schedule_job.job_start_time as job_actual_start_time,bulk_schedule_job.finish_time as job_actual_end_time,DATE_FORMAT(bulk_schedule_job.tasting_date,'%d-%m-%Y') as tasting_date,bulk_schedule_job.store_id as store_id,store.name as store_name");
        $this->db->from('job');
        $this->db->join('store','bulk_schedule_job.store_id=store.id');
        $this->db->where('bulk_schedule_job.status','completed');
        $this->db->where('bulk_schedule_job.is_deleted',0);
        if($created_by==7)
        {
            $this->db->where('bulk_schedule_job.taster_id',$taster_id);
        }
        else
        {
            $this->db->where('bulk_schedule_job.agency_taster_id',$taster_id);
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
                $result[$i]['billing_date']=$expense_result->billing_date;
                $result[$i]['billing_amount']=$expense_result->billing_amount;
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
        
        
        //$this->db->where('bulk_schedule_job.status','completed');
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
        $this->db->select("bulk_schedule_job.id as job_id,bulk_schedule_job.status,bulk_schedule_job.job_state,DATE_FORMAT(bulk_schedule_job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as start_time,TIME_FORMAT(end_time, '%h:%i%p') as end_time,store.name as store_name,store.adress as address,store.suite_number as suite_number,store.appartment_number as appartment_number");
        $this->db->from('job');
        $this->db->join('store','bulk_schedule_job.store_id=store.id');
        if($user_type=='sales_rep')
        {
            $this->db->where('bulk_schedule_job.user_id',$user_id);
        }
        else 
        {
            //Check created by
            //$created_by=$this->get_user_created_by('users',$user_id);
            //get user type
            //$user_role=$this->get_user_role('users',$user_created_by);die;

            if($user_created_by==7)
            {
                $this->db->like('bulk_schedule_job.taster_id',$user_id);
                //$this->db->where('bulk_schedule_job.job_status <>',1);
            }
            else 
            {
                $this->db->where('bulk_schedule_job.agency_taster_id',$user_id);
                //$this->db->where('bulk_schedule_job.job_status <>',1);
            }
            
        }

        $this->db->where('bulk_schedule_job.is_deleted',0);
        $this->db->where('bulk_schedule_job.is_archived',0);
        //$qr="(bulk_schedule_job.status='assigned' OR bulk_schedule_job.status='accepted')";
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
            $this->db->select("bulk_schedule_job.id as job_id,bulk_schedule_job.status,DATE_FORMAT(bulk_schedule_job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as start_time,TIME_FORMAT(end_time, '%h:%i%p') as end_time,store.id as store_id,store.name as store_name,store.adress as address,store.suite_number as suite_and_apt,store.phone as store_phone,store.latitude as store_latitude,store.longitude as store_longitude,zone.id as zone_id,CONCAT(first_name, ' ',last_name) as sales_rep_name,users.email as sales_rep_email, user_meta.meta_value as sales_rep_phone,bulk_schedule_job.taster_note");
        }
        else
        {
            $this->db->select("bulk_schedule_job.id as job_id,bulk_schedule_job.status,DATE_FORMAT(bulk_schedule_job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as start_time,TIME_FORMAT(end_time, '%h:%i%p') as end_time,store.id as store_id,store.name as store_name,store.adress as address,store.suite_number as suite_and_apt,store.phone as store_phone,store.latitude as store_latitude,store.longitude as store_longitude,zone.id as zone_id,bulk_schedule_job.admin_note,bulk_schedule_job.taster_id,CONCAT(first_name, ' ',last_name) as taster_name,bulk_schedule_job.taster_note");
        }
        $this->db->from('job');
        $this->db->join('store','bulk_schedule_job.store_id=store.id');
        $this->db->join('zone','store.zone=zone.id');
        if($user_id!='')
        {
            $this->db->join('users','bulk_schedule_job.user_id=users.id');
            $this->db->join('user_meta','bulk_schedule_job.user_id=user_meta.user_id');
            $this->db->where('user_meta.meta_key','phone');
        }
        else
        {
            $this->db->join('users','bulk_schedule_job.taster_id=users.id');
        }
        //$this->db->join('users','bulk_schedule_job.taster_id=users.id');
        $this->db->where('bulk_schedule_job.id',$job_id);
        $this->db->where('bulk_schedule_job.is_deleted',0);
        $qr="(is_archived='0' OR is_archived='1')";
        $this->db->where($qr);
        $result=$this->db->get();
        $jobdetails=$result->result_array();
        //get wine id
        $this->db->select('wine_id');
        $this->db->from('job');
        $this->db->where('bulk_schedule_job.id',$job_id);
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
        //echo "<pre>";
        //print_r($filter);die;
        $this->db->select('bulk_schedule_job.*,s.name as store_name');
        $this->db->from('bulk_schedule_job');
		$this->db->join('store as s','bulk_schedule_job.store_id=s.id');
        //$this->db->order_by('bulk_schedule_job.id','DESC');
		$this->db->order_by('bulk_schedule_job.tasting_date','DESC');
        $this->db->where('bulk_schedule_job.is_deleted',0);
        $this->db->where('bulk_schedule_job.status','not_published');
        if (isset($filter['tasting_date']) && $filter['tasting_date'] != "") {
            $this->db->where('bulk_schedule_job.tasting_date', $filter['tasting_date']);
        }
        if (isset($filter['taster']) && $filter['taster'] != "") {
            $taster_id=$filter['taster'];
            $taster_id_array=explode("@",$taster_id);
            //echo "<pre>";
           // print_r($taster_id_array);die;
            //$this->db->where('bulk_schedule_job.tasting_date', $filter['tasting_date']);
            //$this->db->where("(bulk_schedule_job.taster_id LIKE '%$taster_id%')");
            $this->db->where_in('bulk_schedule_job.taster_id', $taster_id_array);
        }
        if (isset($filter['store']) && $filter['store'] != "") {
            
            $this->db->where('bulk_schedule_job.store_id', $filter['store']);
            
        }
        if (isset($filter['entry_date']) && $filter['entry_date'] != "") {
            $this->db->where("DATE_FORMAT(bulk_schedule_job.created_on,'%Y-%m-%d')", $filter['entry_date']);
        }
        if (isset($filter['status'])) {
           $job_status=$filter['status'];
           if($job_status=="pre_assigned" || $job_status=="")
           {
                $this->db->where('bulk_schedule_job.job_status',1);
           }
           if($job_status=="assigned")
           {
                $this->db->where('bulk_schedule_job.job_status',2);
                //$this->db->where('bulk_schedule_job.status','pending');
                $this->db->order_by('bulk_schedule_job.updated_on','DESC');
           }
           if($job_status=="accepted")
           {
                $this->db->where('bulk_schedule_job.job_status',3);
                //$this->db->where('bulk_schedule_job.accept_status',1);
                //$this->db->or_where('bulk_schedule_job.accept_status',0);
                $this->db->order_by('bulk_schedule_job.updated_on','DESC');
           }
           if($job_status=="problems")
           {
                $this->db->where('bulk_schedule_job.job_status',4);
           }
        }
        else
        {
            $this->db->where('bulk_schedule_job.job_status',1);
        }
        
        if ($count) {
            return $this->db->count_all_results();
        }
        if ( (isset($filter['limit']) && $filter['limit'] > 0) && (isset($filter['offset']) ) ) {
            $this->db->limit($filter['limit'], $filter['offset']);
        }
        /*if ($order <> null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('updated_on ASC');
        }*/
        $this->db->group_by("bulk_schedule_job.id");
        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        return $query->result();
    }
    //Get all Billing from admin end
    public function get_billing_list($filter = array(), $order = null, $dir = null, $count = false) {
        //print_r($filter);die;
        $this->db->select("bulk_schedule_job.*,expense_details.date as billing_date,expense_details.exp_amount,CONCAT(first_name, ' ',last_name) as taster_name,user_meta.meta_value as account_no");
        $this->db->from('job');
        $this->db->join('expense_details','bulk_schedule_job.id=expense_details.job_id');
        $this->db->join('users','expense_details.taster_id=users.id');
        $this->db->join('user_meta','expense_details.taster_id=user_meta.user_id');
        if ( isset($filter['field']) && $filter['field'] <> "" ) {
            //Search using wine flavour
            /*$this->db->join('store','bulk_schedule_job.store_id=store.id','left');
            $this->db->where('store.wine_sell_type',$filter['field']);*/
            $this->db->join('completed_job_wine_details','bulk_schedule_job.id=completed_job_wine_details.job_id','left');
            $this->db->join('wine','completed_job_wine_details.wine_id=wine.id','left');
            $this->db->where('wine.flavour',$filter['field']);

        }
        $this->db->order_by('bulk_schedule_job.id','DESC');
        
        $this->db->where('bulk_schedule_job.is_deleted',0);
        $this->db->where('bulk_schedule_job.ready_for_billing',1);
        $this->db->where('bulk_schedule_job.is_archived',0);
        $this->db->where('user_meta.meta_key','manual_account_number');
        if ($count) {
            $this->db->order_by('updated_on ASC');
            $this->db->group_by("bulk_schedule_job.id");
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
        $this->db->group_by("bulk_schedule_job.id");
        $query = $this->db->get();
       // echo $this->db->last_query()."<br>";die;
        
        return $query->result();
    }
    //Get all archive
    public function get_archive_list($filter = array(), $order = null, $dir = null, $count = false) {
        //print_r($filter);die;
        $this->db->select("bulk_schedule_job.*,expense_details.date as billing_date,expense_details.exp_amount,CONCAT(first_name, ' ',last_name) as taster_name,user_meta.meta_value as account_no");
        $this->db->from('job');
        $this->db->join('expense_details','bulk_schedule_job.id=expense_details.job_id');
        $this->db->join('users','expense_details.taster_id=users.id');
        $this->db->join('user_meta','expense_details.taster_id=user_meta.user_id');
        if ( isset($filter['field']) && $filter['field'] <> "" ) {
            //Search using wine flavour
            /*$this->db->join('store','bulk_schedule_job.store_id=store.id','left');
            $this->db->where('store.wine_sell_type',$filter['field']);*/
            $this->db->join('completed_job_wine_details','bulk_schedule_job.id=completed_job_wine_details.job_id','left');
            $this->db->join('wine','completed_job_wine_details.wine_id=wine.id','left');
            $this->db->where('wine.flavour',$filter['field']);

        }
        $this->db->order_by('bulk_schedule_job.id','DESC');
        
        $this->db->where('bulk_schedule_job.is_deleted',0);
        
        $this->db->where('bulk_schedule_job.is_archived',1);
        $this->db->where('user_meta.meta_key','manual_account_number');
        if ($count) {
            $this->db->order_by('updated_on ASC');
            $this->db->group_by("bulk_schedule_job.id");
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
        $this->db->group_by("bulk_schedule_job.id");
        $query = $this->db->get();
       //echo $this->db->last_query()."<br>";die;
        
        return $query->result();
    }
    public function get_csv($checked_id=array(),$filter = array(), $order = null, $dir = null, $count = false) {
        //Need to get an array of id's
          $ids = array();
          foreach($checked_id as $val) {
             array_push($ids, $val);
          }
          //print_r($ids);die;
        $this->db->select("bulk_schedule_job.*, expense_details.date as billing_date, expense_details.exp_amount,completed_job_wine_details.wine_id as sampled_and_sold_wine, CONCAT(first_name, ' ',last_name) as taster_name, user_meta.meta_value as account_no");
        $this->db->from('job');
        $this->db->join('completed_job_wine_details','bulk_schedule_job.id=completed_job_wine_details.job_id');
        $this->db->join('expense_details','bulk_schedule_job.id=expense_details.job_id');
        
        $this->db->join('users','expense_details.taster_id=users.id');
        $this->db->join('user_meta','expense_details.taster_id=user_meta.user_id');
        $this->db->order_by('bulk_schedule_job.id','DESC');
        $this->db->where_in('bulk_schedule_job.id', $ids);
        //$this->db->where('bulk_schedule_job.is_deleted',0);
        //$this->db->where('bulk_schedule_job.ready_for_billing',1);
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
        $this->db->group_by("bulk_schedule_job.id");
        $query = $this->db->get();
       //echo $this->db->last_query();die;
        $result=$query->result_array();
        
        for($i=0;$i<count($result);$i++)
        {
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
           // unset($result[$i]['wine_id']);
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

        return $result;
    }
    public function moved_to_archive($checked_id)
    {
        $id_array=explode(",",$checked_id);
        $data = array(
               'is_archived' =>1,
        );
        foreach($id_array as $id)
        {
            $this->db->where('id', $id);
            $this->db->update('job', $data); 
        }
        return $this->db->affected_rows();
    }
    public function get_todays_approved_jobs($current_date)
    {
        $this->db->select("bulk_schedule_job.*");
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
            $this->db->where('bulk_schedule_job.job_status',2);
            //$this->db->where('bulk_schedule_job.status','pending');
        }
        if($status==3)
        {
            $this->db->where("bulk_schedule_job.job_status",3);
            //$this->db->where('bulk_schedule_job.accept_status',1);
            //$this->db->where('bulk_schedule_job.status','approved');
        }
        if($status==4)
        {
            $this->db->where("bulk_schedule_job.job_status",4);
        }
        $this->db->where('is_deleted',0);
        $result=$this->db->get();
        return $result->num_rows();
    }
    function job_details($id)
    {
        $this->db->select('*');
        $this->db->from('bulk_schedule_job');
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
        $this->db->order_by('name','ASC');
        $store = $this->db->get();
        return $store->result();
    }
    function get_wine($store_id)
    {
        //Get store wine sell type
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
                array_push($wineTypeArray,'mix');
                break;
            }
        }

        $winetype =  array_unique($wineTypeArray);
   
        $this->db->where_in('wine.flavour',$winetype);

        $this->db->order_by('name','ASC');
        $store = $this->db->get();
    
        return $store->result();
    }
    function get_all_wine()
    {

        $this->db->select('*');
        $this->db->from('wine');
        $this->db->where('wine.is_deleted',0);
        
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
        $this->db->from('bulk_schedule_job');
        $this->db->where('bulk_schedule_job.id',$job_id);
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
        $this->db->order_by('last_name','asc');
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
       $this->db->order_by('last_name','asc');
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
        $this->db->order_by('last_name','asc');
        $qr="(user_type='sales_rep')";
       
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
        $this->db->select('bulk_schedule_job.question_id');
        $this->db->from('job');
        $this->db->where('bulk_schedule_job.id',$job_id);
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
        $this->db->where('bulk_schedule_job.id',$job_id);
       // $this->db->like('bulk_schedule_job.taster_id', $tester_id, 'both');
        $this->db->where('bulk_schedule_job.is_deleted',0);
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
            $this->db->where('bulk_schedule_job.id <>',$job_id);
        }
        
        $job = $this->db->get();
        //echo $this->db->last_query();die;
        return $job->num_rows();
        
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
            $rejected.=$au['first_name']." ".$au['last_name'].",";
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
        $this->db->select("reason,CONCAT(first_name, ' ',last_name) as taster_name");
        $this->db->from("cancelled_job");
        $this->db->join("users","cancelled_bulk_schedule_job.taster_id=users.id",'left');
        $this->db->where('cancelled_bulk_schedule_job.job_id',$job_id);
        $this->db->order_by('cancelled_bulk_schedule_job.id','desc');
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
        $this->db->select('COUNT(*) as count');
        //$this->db->select('*');
        $this->db->from($tablename);
        $this->db->where('tasting_date',$tasting_date);
        $this->db->where('is_deleted',0);
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
        $this->db->select("bulk_schedule_job.id as job_id,bulk_schedule_job.status,bulk_schedule_job.job_state,DATE_FORMAT(bulk_schedule_job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as start_time,TIME_FORMAT(end_time, '%h:%i%p') as end_time,store.name as store_name,store.adress as address,store.suite_number  as suite_and_apt");
        $this->db->from('job');
        $this->db->group_by('job_accept_reject.job_id');
        $this->db->join('store','bulk_schedule_job.store_id=store.id');
        $this->db->join('job_accept_reject','bulk_schedule_job.id=job_accept_reject.job_id');
        if(isset($todays_job) && $todays_job==1)
        {
            $this->db->where('job_accept_reject.date',$todays_date);
        }
        //$this->db->like('bulk_schedule_job.taster_id',$tester_id);
        //$this->db->where("(bulk_schedule_job.taster_id LIKE $tester_id)");
        $this->db->where("(bulk_schedule_job.taster_id = $tester_id OR bulk_schedule_job.agency_taster_id = $tester_id)");
        $this->db->where('bulk_schedule_job.accept_status',1);
        $this->db->where('bulk_schedule_job.is_deleted',0);
        $this->db->where('bulk_schedule_job.tasting_date >=', $previous_date);
        
        $qr="(bulk_schedule_job.status='accepted' OR bulk_schedule_job.status='approved' OR bulk_schedule_job.status='completed')";
        //$qr="(bulk_schedule_job.status='approved' OR bulk_schedule_job.status='accepted')";
        //$this->db->where('bulk_schedule_job.job_status','completed');
        $this->db->where($qr);
       // $this->db->or_where('bulk_schedule_job.status','approved');
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
        $this->db->select("bulk_schedule_job.id as job_id,bulk_schedule_job.status,store.name as store_name,taster_id,DATE_FORMAT(bulk_schedule_job.tasting_date, '%d-%m-%Y') as job_date");
        $this->db->from('job');
        $this->db->join('store','bulk_schedule_job.store_id=store.id');
        $this->db->where('bulk_schedule_job.user_id',$sales_rep_id);
        //$this->db->where('bulk_schedule_job.confirm_status',1);
        $this->db->where('bulk_schedule_job.status','completed');
        $this->db->where('bulk_schedule_job.is_deleted',0);
        $result=$this->db->get();
        //echo $this->db->last_query();die;
        //return $result->result_array();
        $final_result=$result->result_array();
        //echo "<pre>";
        //print_r($final_result);die;
        for($i=0;$i<count($final_result);$i++)
        {
            $taster_id=$final_result[$i]['taster_id'];
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
            $this->db->select("CONCAT(first_name, ' ',last_name) as taster_name");
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
                    $wine_name.=$result_wine[0]['name'].",";
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
                    $data = array(
                       'ready_for_billing' => 1,
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
        if($expense_id!='')
        {
            $this->db->select('images');
            $this->db->from('expense_details_images');
            $this->db->where('expense_id',$expense_id);
            $value_image=$this->db->get();
            $result_image=$value_image->result_array();

            $img='';
            foreach($result_image as $single_img)
            {
                $img.=$single_img['images'].",";
            }
            $img=rtrim($img,",");
            $result[0]['support_imgs']=$img;
        }
        else
        {
            $result[0]['support_imgs']='';
        }
        
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
    function insert_tasting_setup_images($insert_id, $uploaded_pics)
    {
        $question_images_array=array(
            'tasting_setup_id'=>$insert_id,
            'image'=>$uploaded_pics
        );
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
        $this->db->select('bulk_schedule_job.status');
        $this->db->from('job');
        $this->db->where('bulk_schedule_job.id',$job_id);
        $result=$this->db->get()->result_array();
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
        $this->db->select("bulk_schedule_job.id as job_id,bulk_schedule_job.status,DATE_FORMAT(bulk_schedule_job.tasting_date, '%d-%m-%Y') as job_date,TIME_FORMAT(start_time, '%h:%i%p') as actual_start_time,TIME_FORMAT(end_time, '%h:%i%p') as actual_end_time,bulk_schedule_job.job_start_time,bulk_schedule_job.finish_time as job_end_time,CONCAT(bulk_schedule_job.working_hour,' ','Hrs') as working_hour,store.id as store_id,store.name as store_name,store.adress as address,store.suite_number as suite_and_apt,store.phone as store_phone,store.latitude as store_latitude,store.longitude as store_longitude,store.zone as zone_id,bulk_schedule_job.taster_id,CONCAT(users.first_name,' ',users.last_name) as taster_name,bulk_schedule_job.taster_note,general_notes.general_note");
        $this->db->from('job');
        $this->db->join('store','bulk_schedule_job.store_id=store.id');
        $this->db->join('general_notes','general_notes.job_id=bulk_schedule_job.id','left');
        $this->db->join('users','bulk_schedule_job.taster_id=users.id','left');
        $this->db->where('bulk_schedule_job.id',$job_id);
        $result=$this->db->get()->result_array();
        //get completed job wine details
        $this->db->select("wine.id,wine.name,wine.description,wine.type as wine_type,wine.flavour as wine_flavour,wine.upc_code as upc,wine.brand,wine.year,CONCAT(wine.size,' ',' ml') as size,wine.category_id,wine_images.image as images,completed_job_wine_details.bottles_sampled,completed_job_wine_details.bottles_sold");
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
    function get_job_details($job_id)
    {
        $this->db->select('status,taster_id');
        $this->db->from('job');
        $this->db->where('id',$job_id);
        $result=$this->db->get()->row();
        return $result;
    }
    function job_info($job_id)
    {
        $this->db->select('tasting_date,taster_id,store_id');
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
        $this->db->select('question_answer_for_bulk_schedule_job.ans_type,question_answer_for_bulk_schedule_job.ans_text,question_answer.question');
        $this->db->from('question_answer_for_job');
        $this->db->join('question_answer','question_answer_for_bulk_schedule_job.question_id=question_answer.id');
        $this->db->where('question_answer_for_bulk_schedule_job.job_id',$job_id);
        $result=$this->db->get()->result_array();
        return $result;
    }
    function get_more_job_info($job_id)
    {
        //echo $job_id;die;
        $this->db->select("DATE(bulk_schedule_job.updated_on) as sampling_date,CONCAT_WS(' ',ua.first_name,ua.last_name) as taster_name,store.name as store_name,store.zipcode as store_zipcode,store.adress as store_adress,bulk_schedule_job.start_time,bulk_schedule_job.end_time,bulk_schedule_job.job_start_time,bulk_schedule_job.finish_time,bulk_schedule_job.working_hour,bulk_schedule_job.taster_id,bulk_schedule_job.agency_taster_id,bulk_schedule_job.taster_id,CONCAT_WS(' ',ub.first_name,ub.last_name) as sales_rep_name,user_meta.meta_value as phone");
        $this->db->from('job');
        $this->db->join('users ua','bulk_schedule_job.taster_id=ua.id','left');
        $this->db->join('store','bulk_schedule_job.store_id=store.id','left');
        $this->db->join('users ub','bulk_schedule_job.user_id=ub.id','left');
        $this->db->join('user_meta','ub.id=user_meta.user_id','left');
        $this->db->where('bulk_schedule_job.id',$job_id);
        $this->db->where('user_meta.meta_key','phone');
        $result=$this->db->get()->row();
        //Get sampling details
        $this->db->select('wine.name,wine.size,wine.brand,completed_job_wine_details.bottles_sampled,bottles_sold');
        $this->db->from('completed_job_wine_details');
        $this->db->join('wine','completed_job_wine_details.wine_id=wine.id');
        $this->db->where('completed_job_wine_details.job_id',$job_id);
        $result_wine=$this->db->get()->result_array();
        $result->wine_sampled_details=$result_wine;
        if($result->agency_taster_id==0)
            $taster_id=$result->taster_id;
        else
            $taster_id=$result->agency_taster_id;
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
        if($user_meta)
            $rate_per_hr=$user_meta->meta_value;
        else
            $rate_per_hr=0;
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
        $this->db->select('question_answer_for_bulk_schedule_job.*,question_answer.question');
        $this->db->from('question_answer_for_job');
        $this->db->join('question_answer','question_answer_for_bulk_schedule_job.question_id=question_answer.id');
        $this->db->where('question_answer_for_bulk_schedule_job.job_id',$job_id);
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
        $this->db->select('image');
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
        $this->db->order_by('name','ASC');
        $value=$this->db->get();
        //echo $this->db->last_query();die;
        return $value->result();
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
        $this->db->select('wine.name,wine.size,wine.brand,completed_job_wine_details.bottles_sampled,bottles_sold');
        $this->db->from('completed_job_wine_details');
        $this->db->join('wine','completed_job_wine_details.wine_id=wine.id');
        $this->db->where('completed_job_wine_details.job_id',$job_id);
        $result_wine=$this->db->get()->result_array();
        return $result_wine;
    }
    function get_expense_amount($job_id)
    {
        $this->db->select('exp_amount');
        $this->db->from('expense_details');
        $this->db->where('job_id',$job_id);
        $expense_details=$this->db->get()->row();
       // echo "<pre>";print_r($expense_details);die;
        return $expense_details->exp_amount;
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
    function get_brandwise_expense($brand,$from_date,$to_date)
    {
        //Get wine according to brand
        $this->db->select('wine.id as wine_id,wine.brand');
        $this->db->from('wine');
        $this->db->where('wine.brand',$brand);
        $this->db->where('wine.is_deleted',0);
        $this->db->where('wine.status','active');
        $val=$this->db->get();
        $result_wine=$val->result_array();
        $blank_wine_id_array=array();
        foreach($result_wine as $w)
        {
            array_push($blank_wine_id_array,$w['wine_id']);
        }
        //End
        $from_date=date('Y-m-d',strtotime($from_date));
        $to_date=date('Y-m-d',strtotime($to_date));
        $this->db->select("bulk_schedule_job.id as job_id,bulk_schedule_job.wine_id as job_wine_id,bulk_schedule_job.tasting_date,store.name as store_name,CONCAT_WS(' ',ub.first_name,ub.last_name) as sales_rep_name");
        $this->db->from('job');
        $this->db->join('store','bulk_schedule_job.store_id=store.id','left');
        $this->db->join('users ub','bulk_schedule_job.user_id=ub.id','left');
        $this->db->where('bulk_schedule_job.tasting_date >=',$from_date);
        $this->db->where('bulk_schedule_job.tasting_date <=',$to_date);
        $this->db->where('bulk_schedule_job.ready_for_billing',1);
        $this->db->order_by('bulk_schedule_job.tasting_date','DESC');
        $result=$this->db->get()->result_array();
        //Calculate expense amount
        //echo "<pre>";
        //print_r($result);die;
        for($i=0;$i<count($result);$i++)
        {
            $job_id=$result[$i]['job_id'];
            $this->db->select('expense_details.exp_amount');
            $this->db->from('expense_details');
            $this->db->where('job_id',$job_id);
            $expense_details=$this->db->get()->row();

            $expense_amount=$expense_details->exp_amount;
            $result[$i]['expense_amount']=$expense_amount;
            
            //Check brand exist or not for the job
            $wine_id=$result[$i]['job_wine_id'];
            $job_wine_id_array=explode(",",$wine_id);
            foreach($job_wine_id_array as $value_id)
            {
                if(in_array($value_id,$blank_wine_id_array))
                {
                    $result[$i]['has_wine']='yes';
                    break;
                }
                else
                {
                    $result[$i]['has_wine']='no';
                }
            }
            //End
        }
        //echo "<pre>";
       // print_r($result);die;
        return $result;
        
    }
    function get_todays_job($todays_date)
    {
        $this->db->select("*");
        $this->db->from('job');
        $this->db->where('bulk_schedule_job.tasting_date',$todays_date);
        $this->db->where('bulk_schedule_job.is_deleted',0);
        $qr="(status='accepted' OR status='approved')";
        $this->db->where($qr);
        $result=$this->db->get()->result_array();
        return $result;
    }
    public function check_job_between_two_hour($current_server_time,$next_two_hour_time,$start_time)
    {
        $bounded_time=date("H:i:s", strtotime("12:30 PM"));
        $this->db->select("*");
        $this->db->from('job');
        $this->db->where('bulk_schedule_job.start_time >',$bounded_time);
        $this->db->where('bulk_schedule_job.start_time >=',$current_server_time);
        $this->db->where('bulk_schedule_job.start_time <=',$next_two_hour_time);
        $result=$this->db->get();
        //echo $this->db->last_query();die;
        $row=$result->num_rows();
        if($row > 0)
            return 'true';
        else
            return 'false';
    }
    public function check_assign_status($job_id)
    {
        $this->db->select("bulk_schedule_job.taster_id,bulk_schedule_job.wine_id");
        $this->db->from('bulk_schedule_job');
        $this->db->where('bulk_schedule_job.id',$job_id);
        $result=$this->db->get();
        //echo $this->db->last_query();die;
        $row=$result->row();
        if($row->taster_id!='' && $row->wine_id!='')
            return 'true';
        else
            return 'false';
    }
    function withinTwoMonthjobwithTaster($table,$dateOne,$dateTwo){
		$this->db->select("*");
        $this->db->from($table);
		$this->db->where("taster_id !=","");
		$this->db->where("wine_id !=","");
		$this->db->where('status','not_published');
		$this->db->where('is_deleted',0);
		$this->db->where('tasting_date >=', $dateOne);
		$this->db->where('tasting_date <=', $dateTwo);
        $result=$this->db->get()->result_array();
        return $result;
	}
}
/* End of file user_model.php */
/* Location: ./application/models/user_model.php */