<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_model extends CI_Model {

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

    public function create_slug($name)
    {
        $count = 0;
        $name = url_title($name);
        $slug_name = $name;             // Create temp name
        while(true) 
        {
            $this->db->select('id');
            
            $this->db->where('slug', $slug_name);   // Test temp name
            $query = $this->db->get('cms');
            if ($query->num_rows() == 0) break;
            $slug_name = $name . '-' . (++$count);  // Recreate new temp name
        }
        return $slug_name;      // Return temp name
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
	public function get_cms_list($filter = array(), $order = null, $dir = null, $count = false) {

		$this->db->select("cms.*,  CONCAT(cms_created.first_name, ' ', cms_created.last_name) as created_by_name");
        $this->db->from('cms');
        $this->db->join('users AS cms_created', 'cms.created_by = cms_created.id', 'left');
        $this->db->order_by('id', 'DESC');
       $this->db->where('cms.is_deleted',0);

        if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
            $this->db->where('cms.status', $filter['status']);
        }

        if (isset($filter['name']) && $filter['name'] != "") {
            $this->db->like('cms.name', $filter['name']);
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

        $this->db->group_by("cms.id");

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
    function get_cms_details($id) {
    	$id = (int) $id;

        $this->db->select("cms.*,  CONCAT(cms_created.first_name, ' ', cms_created.last_name) as created_by_name, CONCAT(cms_updated.first_name, ' ', cms_updated.last_name) as updated_by_name");
        $this->db->from('cms');
        $this->db->join('users AS cms_created', 'cms.created_by = cms_created.id', 'left');
        $this->db->join('users AS cms_updated', 'cms.updated_by = cms_updated.id', 'left');
        $this->db->where('cms.id', $id);
        $this->db->group_by("cms.id");

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
