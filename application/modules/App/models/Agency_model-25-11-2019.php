<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agency_model extends CI_Model {

    protected $current_level, $level;


    /**
     *
     * @param $tablename
     * @param $data
     */
    function insert($tablename, $data) {

    	if ($this->db->insert($tablename, $data)) {
    		return $this->db->insert_id();
    	} else {
    		return false;
    	}
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
            if($meta_key=='tester' || $meta_key=='zone')
            {
                $string='';
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
            $meta_value='';
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
	public function get_agency_list($filter = array(), $order = null, $dir = null, $count = false) {


		$this->db->select('users.id, users.first_name, users.last_name, users.email, users.last_login, users.created_on, users.status');
        $this->db->from('users');
        $this->db->where('users.user_type','agency');
        $this->db->where('users.is_deleted',0);
        if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
            $this->db->where('users.status', $filter['status']);
        }
        if (isset($filter['name']) && $filter['name'] != "") {
            //$name=substr($filter['name'],0,4);
            //$this->db->join('user_meta','users.id=user_meta.user_id');
            //$this->db->where('user_meta.meta_key','agency_name');
            //$this->db->like('user_meta.meta_value', $name,'both');
            $name = str_replace("+"," ", $filter['name']);
            $this->db->like("CONCAT(users.first_name, ' ',users.last_name)", $name);
            
        }
        

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
    function get_agency_details($id) {
    	$id = (int) $id;

        $this->db->select('users.id,  users.first_name, users.last_name, users.email, users.last_login, users.created_on, users.updated_on, users.status,  CONCAT(users_created.first_name, " ", users_created.last_name) as created_by_name, CONCAT(users_updated.first_name, " ", users_updated.last_name) as updated_by_name');
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
    function get_user_meta($user_id) {
        $user_id = (int) $user_id;

        $this->db->from('user_meta');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();
        return $query->result();
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
        return $result->name;
    }
    function get_tester_list()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('users.status','active');
        $this->db->where('users.user_type','tester');
        $tester = $this->db->get();
        return $tester->result();
    }
    function get_tester_under_agency($id)
    {
        $this->db->select('CONCAT(users.first_name, " ", users.last_name) as full_name');
        $this->db->from('users');
        $this->db->where('users.status','active');
        $this->db->where('users.user_type','tester');
        $this->db->where('users.created_by',$id);
        $tester = $this->db->get();
        return $tester->result();
    }
    function get_agency_id($zone_id,$store_id)
    {
        if($store_id!='')
        {
            $this->db->select('zone');
            $this->db->from('store');
            $this->db->where('store.id',$store_id);
            
            $zone = $this->db->get();
            $result=$zone->result_array();
            $zone_id_from_store=$result[0]['zone'];
        }
        $this->db->select('user_id');
        $this->db->from('user_meta');
        
        $this->db->where('user_meta.meta_key','zone');
        if($store_id!='')
        {
            $this->db->where('user_meta.meta_value',$zone_id_from_store);
        }
        
        $this->db->like('user_meta.meta_value',$zone_id,'both');
        
        $result = $this->db->get();
        $ids=$result->result_array();
        $agency_id_container=array();
        foreach ($ids as  $value) {
            
            $id=$value['user_id'];
            //check agency or tester
            $this->db->select('user_type');
            $this->db->from('users');
            $this->db->where('users.id',$id);
            $this->db->where('users.status','active');
            
            $result = $this->db->get();
            $role=$result->result_array();
            if(!empty($role))
            {
                if($role[0]['user_type']=='agency')
                {
                    array_push($agency_id_container,$id);
                }
            }
        }
        return $agency_id_container;
    }
    function get_tester_id($zone_id,$store_id)
    {
        if($store_id!='')
        {
            $this->db->select('zone');
            $this->db->from('store');
            $this->db->where('store.id',$store_id);
            
            $zone = $this->db->get();
            $result=$zone->result_array();

            $zone_id_from_store=$result[0]['zone'];
            

        }
        $this->db->select('user_id');
        $this->db->from('user_meta');
       
        $this->db->where('user_meta.meta_key','zone');
        
       // $this->db->like('user_meta.meta_value',$zone_id,'both');
        if($store_id!='')
        {
            $this->db->like('user_meta.meta_value',$zone_id_from_store);
        }
        else
        {
            $this->db->like('user_meta.meta_value',$zone_id);
        }
        
        $result = $this->db->get();
        $ids=$result->result_array();

        $tester_id_container=array();
        foreach ($ids as  $value) {
            
            $id=$value['user_id'];
            //check agency or tester
            $this->db->select('user_type,created_by');
            $this->db->from('users');
            $this->db->where('users.id',$id);
            $this->db->where('users.status','active');
            
            $result = $this->db->get();
            $role=$result->result_array();
            if(!empty($role))
            {
                if($role[0]['user_type']=='tester' && $role[0]['created_by']==7)
                {
                    array_push($tester_id_container,$id);
                }
            }
        }
        return $tester_id_container;
    }
    function get_tester_id_for_agency($zone_id,$store_id)
    {
        if($store_id!='')
        {
            $this->db->select('zone');
            $this->db->from('store');
            $this->db->where('store.id',$store_id);
            
            $zone = $this->db->get();
            $result=$zone->result_array();

            $zone_id_from_store=$result[0]['zone'];
            

        }
        $this->db->select('user_id');
        $this->db->from('user_meta');
       
        $this->db->where('user_meta.meta_key','zone');
        
       // $this->db->like('user_meta.meta_value',$zone_id,'both');
        if($store_id!='')
        {
            $this->db->like('user_meta.meta_value',$zone_id_from_store);
        }
        else
        {
            $this->db->like('user_meta.meta_value',$zone_id);
        }
        
        $result = $this->db->get();
        $ids=$result->result_array();

        $tester_id_container=array();
        foreach ($ids as  $value) {
            
            $id=$value['user_id'];
            //check agency or tester
            $this->db->select('user_type,created_by');
            $this->db->from('users');
            $this->db->where('users.id',$id);
            $this->db->where('users.status','active');
            
            $result = $this->db->get();
            $role=$result->result_array();
            if(!empty($role))
            {
                if($role[0]['user_type']=='tester' && $role[0]['created_by']!=7)
                {
                    array_push($tester_id_container,$id);
                }
            }
        }
        return $tester_id_container;
    }
    function fetch_agency_details($agency_ids)
    {
        if(!empty($agency_ids))
        {
            $this->db->select('users.id as user_id,  users.first_name, users.last_name, users.email, users.user_type');
        $this->db->from('users');
        
        
        $this->db->where_in('users.id', $agency_ids);

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        $result = $query->result_array();
        $new_agency_array=$result;
        $result['meta'] = $this->get_all_user_meta($agency_ids);

        for($i=0;$i<count($new_agency_array);$i++)
        {
            for($j=0;$j<count($result['meta']);$j++) {
                if($result['meta'][$j]['user_id']==$new_agency_array[$i]['user_id'])
                {
                    $key=$result['meta'][$j]['meta_key'];
                    if($key=='zone')
                    {
                        $key='zone_id';
                        $zone_id=$result['meta'][$j]['meta_value'];
                        //get zone name
                        $this->db->select('name');
                        $this->db->from('zone');
                        $this->db->where('zone.id',$zone_id);
                        
                        $value = $this->db->get();
                        $res=$value->result_array();
                        $zone_name=$res['0']['name'];
                        $new_agency_array[$i]['zone_name']=$zone_name;
                    }
                    $new_agency_array[$i][$key]=$result['meta'][$j]['meta_value'];

                }
            }
        }
        return $new_agency_array;
        }
        
    }
    function fetch_tester_details($tester_ids)
    {
        if(!empty($tester_ids))
        {
            $this->db->select('users.id as user_id,  users.first_name, users.last_name, users.email, users.user_type');
        $this->db->from('users');
        
        
        $this->db->where_in('users.id', $tester_ids);

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        $result = $query->result_array();
        $new_tester_array=$result;
        $result_tester['meta'] = $this->get_all_user_meta($tester_ids);

        for($i=0;$i<count($new_tester_array);$i++)
        {
            for($j=0;$j<count($result_tester['meta']);$j++) {
                if($result_tester['meta'][$j]['user_id']==$new_tester_array[$i]['user_id'])
                {
                    $key=$result_tester['meta'][$j]['meta_key'];
                    if($key=='zone')
                    {
                        $key='zone_id';
                        $zone_id=$result_tester['meta'][$j]['meta_value'];
                        $zone_id_array=explode(",", $zone_id);
                        //get zone name
                        $this->db->select('name');
                        $this->db->from('zone');
                        $this->db->where_in('zone.id',$zone_id_array);
                        
                        $value = $this->db->get();
                        $res=$value->result_array();
                        
                        $zone_name='';
                        foreach($res as $v)
                        {
                            $zone_name.=$v['name'].",";
                        }
                        $zone_name=rtrim($zone_name,",");
                        $new_tester_array[$i]['zone_name']=$zone_name;
                    }
                    $new_tester_array[$i][$key]=$result_tester['meta'][$j]['meta_value'];

                }
            }
        }
        return $new_tester_array;
        }
        
    }
    function get_all_user_meta($agency_ids) {
        
        $this->db->from('user_meta');
        $this->db->where_in('user_id', $agency_ids);
        $this->db->order_by('user_id','ASC');
        $query = $this->db->get();
        return $query->result_array();
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
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
