<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

	/**
	 *
	 * @param unknown_type $filter
	 * @param unknown_type $order
	 * @param unknown_type $dir
	 * @param unknown_type $count
	 */
	public function get_users_list($filter = array(), $order = null, $dir = null, $count = false) {

		$this->db->select('users.id, users.role_id, users.first_name, users.last_name, users.email, users.last_login, users.created_on, users.status, roles.role_name');
		$this->db->from('users');
		$this->db->join('roles', 'users.role_id = roles.id');

		if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
    		$this->db->where('users.status', $filter['status']);
   		}

		if (isset($filter['role']) && $filter['role'] <> 0) {
    		$this->db->where('users.role_id', $filter['role']);
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
    public function get_roles_list($filter = array(), $order = null, $dir = null, $count = false) {

    	$this->db->select('roles.id, roles.role_token, roles.role_name, roles.description, roles.default, roles.can_delete, (SELECT count(id) FROM users WHERE role_id = roles.id) as no_of_users');
		$this->db->from('roles');

		if (isset($filter['default']) && $filter['default'] <> "") {
    		$this->db->where('roles.default', $filter['default']);
   		}

		if (isset($filter['token']) && $filter['token'] <> "") {
    		$this->db->where('roles.role_token', $filter['token']);
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
   			$this->db->order_by('role_name ASC');
    	}

		$query = $this->db->get();
		return $query->result();
    }


	public function get_permissions_list($filter = array(), $order = null, $dir = null, $count = false) {

    	$this->db->select('permissions.id, permissions.name, permissions.description, permissions.status');
		$this->db->from('permissions');

   		if ($count) {
   			return $this->db->count_all_results();
   		}

		if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
    		$this->db->where('permissions.status', $filter['status']);
   		}

		if ( (isset($filter['limit']) && $filter['limit'] > 0) && (isset($filter['offset']) ) ) {
    		$this->db->limit($filter['limit'], $filter['offset']);
   		}

   		if ($order <> null) {
    		$this->db->order_by($order, $dir);
    	} else {
   			$this->db->order_by('id ASC');
    	}

		$query = $this->db->get();//echo $this->db->last_query()."<br>";
		return $query->result();
    }




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
			$this->db->where('id', $id);
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
     * @param $user_id
     * @return array
     */
    function get_user_details($id) {
    	$id = (int) $id;

    	$this->db->select('users.id, users.role_id, users.first_name, users.last_name, users.email, users.last_login, users.created_on, users.updated_on, users.status, roles.role_name, roles.role_token, CONCAT(users_created.first_name, " ", users_created.last_name) as created_by_name, CONCAT(users_updated.first_name, " ", users_updated.last_name) as updated_by_name');
		$this->db->from('users');
		$this->db->join('roles', 'users.role_id = roles.id');
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

	/**
     *
     * @param $user_id
     * @return array
     */
    function get_role_details($id) {
    	$id = (int) $id;

    	$this->db->select('roles.id, roles.role_token, roles.role_name, roles.description, roles.default, roles.can_delete, roles.created_on, roles.updated_on, CONCAT(users_created.first_name, " ", users_created.last_name) as created_by_name, CONCAT(users_updated.first_name, " ", users_updated.last_name) as updated_by_name');
		$this->db->from('roles');
		$this->db->join('users AS users_created', 'roles.created_by = users_created.id', 'left');
		$this->db->join('users AS users_updated', 'roles.updated_by = users_updated.id', 'left');
		$this->db->where('roles.id', $id);

		$query = $this->db->get();
		//echo $this->db->last_query()."<br>";die;
   		return $query->row();
    }

	/**
     *
     * @param $user_id
     * @return array
     */
    function get_permission_details($id) {
    	$id = (int) $id;

    	$this->db->select('permissions.id, permissions.name, permissions.description, permissions.status');
		$this->db->from('permissions');
		$this->db->where('permissions.id', $id);

		$query = $this->db->get();
		//echo $this->db->last_query()."<br>";die;
   		return $query->row();
    }

	/**
	 *
	 * @param unknown_type $filter
	 */
    function get_role_permission($filter = array()) {

		$this->db->from('role_permissions');

    	if (isset($filter['role_id']) && $filter['role_id'] > 0) {
    		$this->db->where('role_id', $filter['role_id']);
   		}

    	if (isset($filter['permission_id']) && $filter['permission_id'] > 0) {
    		$this->db->where('permission_id', $filter['permission_id']);
   		}

		$query = $this->db->get();
		//echo $this->db->last_query()."<br>";die;

		//$result = new stdClass();
		$result = array();


		foreach ($query->result() as $row) {

			$this->db->select('permission_id');
			$this->db->from('role_permissions');
			$this->db->where('role_id', $row->role_id);

			$query = $this->db->get();

			//$result[$row->role_id] = array_values($query->result_array());
			foreach ($query->result() as $rowP) {
				$result[$row->role_id][$rowP->permission_id] = 'Y';
			}
		}
   		return $result;
    }

    /**
     *
     * @param $data
     */
    function update_role_permission($data = array()) {

    	if (empty($data)) {
    		return false;
    	}

    	// TRUNCATE table first
    	$this->db->from('role_permissions');
		$this->db->truncate();

    	foreach ($data as $role_id => $permissions) {

    		foreach ($permissions as $permission_id => $value) {

    			$role_permissions = array('role_id' => $role_id, 'permission_id' => $permission_id);

    			// Insert it again
    			$this->insert('role_permissions', $role_permissions);
    			//echo "role = ".$role_id." -> permission = ".$permission_id." -> value = ".$value."<br>";
    		}
    	}
    	return true;
    }
    /**
     *
     * @param $tablename
     * @param $field_name
     * @param $field_value
     */
	function validate_data($tablename, $field_name, $field_value) {

		$this->db->where($field_name, $field_value);
		$this->db->from($tablename);

		if ($this->db->count_all_results() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 *
	 * @param unknown_type $user_id
	 * @param unknown_type $meta
	 */
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



}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
