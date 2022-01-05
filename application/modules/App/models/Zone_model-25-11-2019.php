<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zone_model extends CI_Model {

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
    function check_zone($id)
    {
        $id = (int) $id;
        $this->db->select('id');
        $this->db->from('store');
        $this->db->where('store.is_deleted',0);
        $this->db->where('store.zone',$id);
        
        $result=$this->db->get();
        //echo $this->db->last_query();die;
        $record=$result->num_rows();
        return $record;
    }
    function check_duplicate_zone($tablename, $zone_name)
    {
        $this->db->select('id');
        $this->db->from($tablename);
        $this->db->where('name',$zone_name);
        $this->db->where('is_deleted',0);
        $val=$this->db->get();
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
            $data = array(
                        'user_id' => $user_id,
                        'meta_key'  => $meta_key,
                        'meta_value'  => htmlspecialchars($meta_value, ENT_QUOTES, 'utf-8')
                    );
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

            $data = array(
               'is_deleted'=>1
            );

            $this->db->where('id', $id);
            if($this->db->update($tablename, $data))
            {
                return true;
            }
            else
            {
                return false;
            }
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
	public function get_zone_list($filter = array(), $order = null, $dir = null, $count = false) {

		$this->db->select("zone.*,  CONCAT(zone_created.first_name, ' ', zone_created.last_name) as created_by_name");
        $this->db->from('zone');
        $this->db->join('users AS zone_created', 'zone.created_by = zone_created.id', 'left');
        $this->db->order_by('id', 'DESC');
       $this->db->where('zone.is_deleted',0);

        if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
            $this->db->where('zone.status', $filter['status']);
        }

        if (isset($filter['name']) && $filter['name'] != "") {
            $name = str_replace("+"," ", $filter['name']);
            $this->db->like('zone.name', $name);
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

        $this->db->group_by("zone.id");

        $query = $this->db->get();

        //echo $this->db->last_query()."<br>";
        return $query->result();
    }

    public function get_zone($zone_id='')
    {
        
        $this->db->select("zone.id as zone_id,zone.name as zone_name");
        $this->db->from('zone');
       
        $this->db->order_by('zone.id', 'DESC');
       $this->db->where('zone.is_deleted',0);
       if($zone_id!='')
       {
         $this->db->where('zone.id',$zone_id);
       }

       $query = $this->db->get();

        
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
    function get_zone_details($id) {
    	$id = (int) $id;

        $this->db->select("zone.*,  CONCAT(zone_created.first_name, ' ', zone_created.last_name) as created_by_name, CONCAT(zone_updated.first_name, ' ', zone_updated.last_name) as updated_by_name");
        $this->db->from('zone');
        $this->db->join('users AS zone_created', 'zone.created_by = zone_created.id', 'left');
        $this->db->join('users AS zone_updated', 'zone.updated_by = zone_updated.id', 'left');
        $this->db->where('zone.id', $id);
        $this->db->group_by("zone.id");

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        $result = $query->row();

        return $result;
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
	
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
