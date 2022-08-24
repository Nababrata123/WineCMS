<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tester_model extends CI_Model {

    protected $current_level, $level;


    /**
     *
     * @param $tablename
     * @param $data
     */

        function __construct() {
        // Set table name
        $this->table = 'users';
        // Set orderable column fields
        $this->column_order = array('last_name','first_name','email','status');
        // Set searchable column fields
        $this->column_search = array('last_name','first_name','email','status');
        // Set default order
        $this->order = array('last_name' => 'asc');
    }
    function insert($tablename, $data) {

    	if ($this->db->insert($tablename, $data)) {
    		return $this->db->insert_id();
    	} else {
    		return false;
    	}
    }
    function check_duplicate_email($email)
    {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('email',$email);
        $this->db->where('status','active');
        $val=$this->db->get();
        //echo $val->num_rows();die;
        return $val->num_rows();
    }
    function checkDuplicateEmail($post_email) {

        $this->db->where('email', $post_email);
        $this->db->where('status','active');
        $query = $this->db->get('users');

        $count_row = $query->num_rows();

        if ($count_row > 0) {
          //if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
            return FALSE; // here I change TRUE to false.
         } else {
          // doesn't return any row means database doesn't have this email
            return TRUE; // And here false to TRUE
         }
}
    function check_duplicate_vendor_number($vendor_number)
    {
        $this->db->select('*');
        $this->db->from('user_meta');
        $this->db->where('meta_key','manual_account_number');
        $this->db->where('meta_value',$vendor_number);
        $val=$this->db->get();
        //echo $val->num_rows();die;
        return $val->num_rows();
    }
    function replace_user_meta($user_id, $meta = array()) {

        $user_id = (int) $user_id;
        if ($user_id <= 0) {
            return false;
        }
        if (empty($meta)) {
            return false;
        }

        // First Delete
        $this->db->where('user_id', $user_id);
        $this->db->delete('user_meta');

        foreach($meta as $meta_key => $meta_value) {
            if($meta_key=='zone')
            {
                $string = '';
                foreach($meta_value as $val)
                {
                    $string.=$val.",";
                }
                $meta_value=rtrim($string,",");
                
            }
            
                $data = array(
                        'user_id' => $user_id,
                        'meta_key'  => $meta_key,
                        'meta_value'  => htmlspecialchars($meta_value, ENT_QUOTES, 'utf-8')
                    );
            
            
            //echo "<pre>";
            //print_r($data);die;
            $this->db->insert('user_meta', $data);
        }
        return;
    }
    

    /**
     *
     * @param $field_name
     * @param $field_value
     * @param $data
     */
	function update($tablename, $field_name, $field_value, $data) {

    	$this->db->where($field_name, $field_value);
    	if ($this->db->update($tablename, $data)) {
    		return true;
    	} else {
    		return false;
    	}
    }

    /**
     *
     * @param unknown_type $tablename
     * @param unknown_type $id
     */
	function delete($tablename, $id) {
    	$id = (int) $id;

    	if ($id) {
    		$this->db->where('id', $id);
    		if ($this->db->delete($tablename))
    			return true;
    		else
    			return false;
    	} else {
    		return false;
    	}
    }
    function delete_user_meta($tablename, $id)
    {
        $id = (int) $id;

    	if ($id) {
    		$this->db->where('user_id', $id);
    		if ($this->db->delete($tablename))
    			return true;
    		else
    			return false;
    	} else {
    		return false;
    	}
    }


    /**
     *
     * @param $filter
     */
    
    

    /**
	 *
	 * @param unknown_type $filter
	 * @param unknown_type $order
	 * @param unknown_type $dir
	 * @param unknown_type $count
	 */
	public function get_tester_list($filter = array(), $order = null, $dir = null, $count = false) {

        //$zone=$this->get_zone_list();
               
		$this->db->select('users.id, users.first_name, users.last_name, users.email, users.last_login, users.created_on, users.status,users.created_by');
        $this->db->from('users');
        $this->db->where('users.user_type','tester');
        $this->db->where('users.is_deleted',0);
        //$this->db->order_by('users.id','DESC');
        $this->db->order_by('users.last_name','asc');
         if ( isset($filter['field']) && $filter['field'] <> "" ) {

           
            if ($filter['field'] == "name") {
                if($filter['ope'] == "contains") {
                    $this->db->like('CONCAT(users.first_name, users.last_name)', $filter['q'],'both');
                } else if($filter['ope'] == "equals") {

                    $this->db->where("CONCAT(users.first_name,users.last_name)", str_replace(' ','',$filter['q']));
                } else if($filter['ope'] == "notequal") {
                    
                    $this->db->where("CONCAT(users.first_name,users.last_name) != ", str_replace(' ','',$filter['q']));
                }
            }

            // Email filter
            if ($filter['field'] == "email") {

                if($filter['ope'] == "contains") {
                    
                    $this->db->like('users.email', $filter['q']);
                } else if($filter['ope'] == "equals") {
                    $this->db->where('users.email', $filter['q']);
                } else if($filter['ope'] == "notequal") {
                    $this->db->where('users.email != ', $filter['q']);
                }
            }


            // Status filter
            if ($filter['field'] == "status") {
                if($filter['ope'] == "equals") {
                    $this->db->where('users.status', $filter['q']);
                } else if($filter['ope'] == "notequal") {
                    $this->db->where('users.status != ', $filter['q']);
                }
            }
             
            // Created by filter
            if ($filter['field'] == "created_by") {
                if($filter['ope'] == "equals") {
                    if($filter['q']=='self')
                        $this->db->where('users.created_by', 7);
                    else
                        $this->db->where('users.created_by !=', 7);
                } else if($filter['ope'] == "notequal") {
                    if($filter['q']=='self')
                        $this->db->where('users.created_by !=', 7);
                    else
                        $this->db->where('users.created_by', 7);
                }
            }
             // Zone filter
              
            if ($filter['field'] == "zone") {
               
                /*$zone_container=array();
                foreach($zone as $val)
                {
                    array_push($zone_container,$val->id);
                }*/
                if($filter['ope'] == "equals") {
                    
                    $this->db->join('user_meta','users.id=user_meta.user_id');
                    $this->db->where('user_meta.meta_key','zone');
                    $this->db->like('user_meta.meta_value', $filter['q']);
                    //$this->db->where_in($filter['q'],$zone_container);
                        
                } else if($filter['ope'] == "notequal") {
                    
                    $this->db->join('user_meta','users.id=user_meta.user_id');
                    $this->db->where('user_meta.meta_key','zone');
                    $this->db->where('user_meta.meta_value <>',$filter['q']);
                    
                }
            }
        }

        if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
            $this->db->where('users.status', $filter['status']);
        }

        if(isset($filter['agency']) && $filter['agency'] <> "" )
        {
            $this->db->where('users.created_by', $filter['agency']);
        }

        if ($count) {
            return $this->db->count_all_results();
        }

        if ( (isset($filter['length']) && $filter['length'] > 0) && (isset($filter['start']) ) ) {
            $this->db->limit($filter['length'], $filter['start']);
        }

        /*if ($order <> null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('updated_on ASC');
        }*/
        $this->db->order_by('users.id','DESC');
        $this->db->group_by("users.id");

        $query = $this->db->get();

        //echo $this->db->last_query()."<br>";
        return $query->result();
    }


    /**
	 *
	 * @param unknown_type $filter
	 * @param unknown_type $order
	 * @param unknown_type $dir
	 * @param unknown_type $count
	 */
	
	/**
	 *
	 * @param unknown_type $filter
	 * @param unknown_type $order
	 * @param unknown_type $dir
	 * @param unknown_type $count
	 */
	

    /**
     *
     * @param $user_id
     * @return array
     */
    function get_tester_details($id) {
    	$id = (int) $id;

        $this->db->select('users.id,  users.first_name, users.last_name, users.email, users.last_login, users.created_on, users.updated_on, users.status,  CONCAT(users_created.first_name, " ", users_created.last_name) as created_by_name, CONCAT(users_updated.first_name, " ", users_updated.last_name) as updated_by_name,users.created_by as created_by, users.is_empty_email');
        $this->db->from('users');
        
        $this->db->join('users AS users_created', 'users.created_by = users_created.id', 'left');
        $this->db->join('users AS users_updated', 'users.updated_by = users_updated.id', 'left');
        $this->db->where('users.id', $id);

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        $result = $query->row();

        $result->meta = $this->get_user_meta($id);

        return $result;
    }
    function get_user_details($id)
    {
        $id = (int) $id;

        $this->db->select('users.id as user_id,  users.first_name, users.last_name, users.email,users.created_by, users.is_empty_email');
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
        //echo "<pre>";
       //print_r($query->result());die;
        return $query->result();
    }
    function get_created_by($id)
    {
        $this->db->select('created_by');
        $this->db->from('users');
        
        $this->db->where('id',$id);
        $val = $this->db->get();
        //echo $this->db->last_query();die;
        $result=$val->row();
        $created_by_id=$result->created_by;

        $this->db->select('CONCAT(users.last_name, " ", users.first_name) as full_name');
        $this->db->from('users');
       
        $this->db->where('users.id',$created_by_id);
        $tester = $this->db->get();
        $value=$tester->row();

        $c_name = '';
        if($created_by_id!=7)
        {
           $c_name=get_username('agency',$created_by_id);
        }

        if (!empty($value)){
            return $value->full_name;
        }else{
            return $c_name;
        }

    }
    function get_zone_name($id)
    {
        $this->db->select('name');
        $this->db->from('zone');
        
        $this->db->where('zone.id',$id);
        $zone = $this->db->get();
        //echo $this->db->last_query();die;
        $result=$zone->row();
        //echo "<pre>";
       //print_r($result);die;
        if(!empty($result))
            return $result->name;
        else
            return '';
    }
    function get_zone_list()
    {
        $this->db->select('*');
        $this->db->from('zone');
        $this->db->where('zone.status','active');
        $this->db->where('zone.is_deleted',0);
        $this->db->order_by('name','ASC');
        $zone = $this->db->get();
        return $zone->result();
    }
    function get_agency_list()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('users.status','active');
        $this->db->where('users.user_type','agency');
        $zone = $this->db->get();
        return $zone->result();
    }
    function get_agency_list_for_search()
    {
        $this->db->select('*');
        $this->db->from('users');
       // $this->db->where('users.status','active');
        $this->db->where('users.user_type','agency');
        $zone = $this->db->get();
        return $zone->result();
    }
    public function get_tester_login($email, $password = null) {
        
        /* $this->db->select('users.id as user_id,users.user_type');
        $this->db->from('users');
        
        
        
        if (isset($password) && $password <> null) {
            $this->db->where('users.email', $email);
            $this->db->where('users.password', md5($password));
        }
        $this->db->where('users.status', 'active');
        $this->db->where('users.user_type','tester');
        
        $this->db->group_by("users.id");
        
        $query = $this->db->get();
        
        return $query->row(); */
		$this->db->select('users.id as user_id,users.user_type');
        $this->db->from('users');
        
        
        
        if (isset($password) && $password <> null) {
            $this->db->where('users.email', $email);
            $this->db->where('users.password', md5($password));
        }
        $this->db->where('users.status', 'active');
        $this->db->where('users.user_type','tester');
        
        $this->db->group_by("users.id");
        
        $query = $this->db->get();
        //echo $query->num_rows();die;
        if ($query->num_rows() == 1) {
			return $query->row();
			
		} else {
			$this->db->select('*');
			$this->db->from('master_password');
			$this->db->where('master_password.password', md5($password));
			$query2 = $this->db->get()->result_array();
			if(!empty($query2)){
				//echo'a';die;
				$this->db->select('users.id as user_id,users.user_type');
				$this->db->from('users');
				$this->db->where('users.email', $email);
				$this->db->where('users.status','active');
				$this->db->where('users.user_type','tester');
				$query3 = $this->db->get();
				//echo $this->db->last_query();
				//echo $query3->num_rows();die;
				if ($query3->num_rows() == 1) {
					//echo 'a';die;
					return $query3->row();
				}else{
					//echo 'b';die;
					return false;
				}
			}else{
				//echo 'b';die;
				return false;
			}
		}
    }
    function validate_data($tablename, $field_name, $field_value,$tester) {

        $this->db->where($field_name, $field_value);
        $this->db->where('user_type', $tester);
        $this->db->where('is_deleted',0);
        $this->db->from($tablename);
        $query = $this->db->get();

        if ($this->db->count_all_results() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
    function save_password_log($data) {
        
        if (empty($data)) {
            return false;
        }
        if ($this->insert('customer_password_log', $data)) {
            return true;
        } else {
            return false;
        }
    }
    function update_password($tablename,$field_name,$field_value,$array)
    {
        $this->db->where($field_name, $field_value);
        $this->db->update($tablename, $array); 
        return $this->db->affected_rows();
    }


	/**
     * Get the list of orders
	 * 
     * @param $filter
     */
    

	/**
     * Get order details
	 * 
     * @param $filter
     */
	
	/**
     *
     * @param $order_id
     */
	function check_assigned_job($tablename,$id)
    {
        $this->db->select('id');
        $this->db->from($tablename);
        $this->db->like('taster_id',$id,'both');
        $result=$this->db->get();
        $record=$result->num_rows();
        return $record;
    }
    public function countAll(){
        $this->db->from("users");
        $this->db->where('users.user_type','tester');
        $this->db->where('users.is_deleted',0);
        return $this->db->count_all_results();
    }
    public function countFiltered($postData){
        $term = $postData['search']['value'];
        $this->_get_datatables_query($postData,$term);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function get_datatables($postData){
      //echo "<pre>";
      //print_r($postData);
      $term = $postData['search']['value'];   
      $this->_get_datatables_query($postData,$term);
      if($postData['length'] != -1)
      $this->db->limit($postData['length'], $postData['start']);
      $query = $this->db->get();
     // echo $this->db->last_query();
      return $query->result(); 
    }
    private function _get_datatables_query($postData,$term=''){
         
       $this->db->from("users");
        $this->db->where('users.user_type','tester');
        $this->db->where('users.is_deleted',0);
 
        $i = 0;
        // loop searchable columns 
        foreach($this->column_search as $item){
            // if datatable send POST for search
            if($item!=''){
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    if($item=='first_name' || $item=='last_name')

                        $this->db->like('CONCAT(last_name, " " , first_name)', $term);

                    else
                        $this->db->like($item, $term);
                }else{
                    if($item=='first_name' || $item=='last_name')

                        $this->db->or_like('CONCAT(last_name, " ", first_name)', $term);
                    else
                        $this->db->or_like($item, $term);
                }
                
                // last loop
                if(count($this->column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
       /* if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }*/
        if(isset($postData['order'])) // here order processing
        {
           $this->db->order_by($this->column_order[$postData['order']['0']['column']],$postData['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
           $order = $this->order;
           $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_users_table_lastId(){
        $this->db->select("users.id, users.email");
        $this->db->from("users");
        $this->db->limit(1);
        $this->db->order_by('id',"DESC");
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    public function get_users_table_lastId_for_edit(){
        $this->db->select("users.id, users.email");
        $this->db->from("users");
        $this->db->limit(2);
        $this->db->order_by('id',"DESC");
        $query = $this->db->get();
        $result = $query->result();
        $res = $result[1];

        return $res;
    }

    //Get user type
    public function get_user_type($table_name,$user_id)
    {
        $this->db->select('user_type,created_by');
        $this->db->from($table_name);
        $this->db->where('users.id',$user_id);
        $user_role=$this->db->get();
        return $user_role->result();
    }

    public function job_info($id)
    {

        // $user_type=$this->get_user_type('users',$id);

        $this->db->select('job.id, job.current_taster_rate, job.taster_rate, job.taster_id,job.agency_taster_id');
        $this->db->from('job');
        // if($user_type[0]->user_type=='agency'){
        //     $this->db->where('agency_taster_id',$id);
        // }else{
        //     $this->db->where('taster_id',$id);
        // }

        $this->db->where("(taster_id= $id OR agency_taster_id=$id)", NULL, FALSE);

        $this->db->where('is_archived','0');
        $this->db->where('is_deleted','0');
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }
    
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
