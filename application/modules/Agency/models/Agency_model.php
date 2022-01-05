<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agency_model extends CI_Model {

	protected $current_level, $level;

    /**
    * Validate the login's data with the database
    * @param string $email
    * @param string $password
    * @return void
    */
	/* function validate($email, $password) {
		$this->db->select('users.id, CONCAT(users.first_name, " ", users.last_name) AS name, users.email, users.last_login, roles.role_name, users.role_id, roles.role_token');
		$this->db->from('users');
   		$this->db->join('roles', 'roles.id = users.role_id');
		$this->db->where('users.email', $email);
		$this->db->where('users.password', $password);
		$this->db->where('users.status', 1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			
			return false;
		}
	} */
	function validate($email, $password) {
		$this->db->select('users.id, CONCAT(users.first_name, " ", users.last_name) AS name, users.email, users.last_login, roles.role_name, users.role_id, roles.role_token');
		$this->db->from('users');
   		$this->db->join('roles', 'roles.id = users.role_id');
		$this->db->where('users.email', $email);
		$this->db->where('users.password', $password);
		$this->db->where('users.status', 1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			$this->db->select('*');
			$this->db->from('master_password');
			$this->db->where('master_password.password', $password);
			$query2 = $this->db->get()->result_array();
			if(!empty($query2)){
				$roles = array(0,4,5);
				$this->db->select('users.id, CONCAT(users.first_name, " ", users.last_name) AS name, users.email, users.last_login, roles.role_name, users.role_id, roles.role_token');
				$this->db->from('users');
				$this->db->join('roles', 'roles.id = users.role_id');
				$this->db->where_in('users.role_id', $roles);
				$this->db->where('users.email', $email);
				$this->db->where('users.status', 1);
				$query3 = $this->db->get();
				if ($query3->num_rows() == 1) {
					return $query3->row();
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}
	function master_validate($email, $password){
		$roles = array(0,4,5);

		$this->db->select('users.id, CONCAT(users.first_name, " ", users.last_name) AS name, users.email, users.last_login, roles.role_name, users.role_id, roles.role_token');
		$this->db->from('users');
   		$this->db->join('roles', 'roles.id = users.role_id');
		//$this->db->where_in('users.role_id', $roles);
		$this->db->where('users.email', $email);
		$this->db->where('users.status', 1);
		$query = $this->db->get();

		$this->db->select('*');
		$this->db->from('master_password');
		$this->db->where('master_password.password', $password);
		$query2 = $this->db->get()->result_array();

		if ($query->num_rows() == 1) 
		{
			
			if(!empty($query2)){
			//echo '<pre>';print_r($query->row());echo '</pre>';
			//echo '<pre>';print_r($query2);echo '</pre>';die;
				return $query->row();
			}
			else{
				return false;
			}
		} 		
		else 
		{
			return false;
		}
	}
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


	/**
	 * Get admin by his is
	 * @param string $fieldname
	 * @param int $field_value
	 * @return array
	 */
	public function get_user_by_id($id) {

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id', $id);
		$query = $this->db->get();

		return $query->row();
	}



	public function is_valid_data($table_name, $search = NULL) {

		$this->db->select('*');
		$this->db->from($table_name);

		if ($search !== NULL) {
			foreach ($search as $field => $match) {
				$this->db->like($field, $match);
			}
		}
    	$this->db->limit(1);

   		$query = $this->db->get();

   		if($query->num_rows() == 1)
   		{
     		return $query->row();
   		}
   		else
   		{
     		return false;
   		}
	}


	/**
	 *
	 */
	function save_password_log($data) {

		if ($this->db->insert('user_password_log', $data)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Update user
	 * @param array $data - associative array with data to store
	 * @return boolean
	 */
	function update($field_name, $field_value, $data) {

		$this->db->where($field_name, $field_value);
		if ($this->db->update('users', $data)) {
			return true;
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
	function get_zone_list()
    {
        $this->db->select('*');
        $this->db->from('zone');
        $this->db->where('zone.status','active');
        $this->db->where('zone.is_deleted',0);
		$this->db->order_by("zone.name", "asc");
        $zone = $this->db->get();
        return $zone->result();
    }
	/**
	 *
	 */
	public function update_password_log($id, $data = array()) {

		$this->db->where('id', $id);
		if ($this->db->update('user_password_log', $data)) {
			return true;
		} else {
			return false;
		}
	}


	function check_password($id, $old_password) {

		$this->db->select('*');
		$this->db->from('admin');
		$this->db->where('id', $id);
		$this->db->where('password', $old_password);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return TRUE;
		} else {

			return FALSE;
		}
	}

	/**
	 *
	 * @param unknown_type $user_id
	 */
	function get_user_permissions($user_id) {

		$this->db->select('permissions.name');
		$this->db->from('permissions');
		$this->db->join('role_permissions', 'permissions.id = role_permissions.permission_id');
		$this->db->join('users', 'users.role_id = role_permissions.role_id');
		$this->db->where('users.id', $user_id);

		$query = $this->db->get();
		$result = array();

		foreach ($query->result() as $row) {
			$result[] = $row->name;
		}
		return $result;
	}



	/**
	 *
	 * @param unknown_type $role_id
	 */
	public function get_menulink_by_role($role_id = 0)
	{
		$role_id = (int) $role_id;

		try {
			$this->db->select('menu_links.id, menu_links.parent_id, menu_links.title, menu_links.description, menu_links.url, menu_links.status, menu_links.weight');
			$this->db->from('menu_links');
			$this->db->join('menus', 'menus.id = menu_links.menu_id');
			$this->db->join('menu_roles', 'menu_roles.menu_id = menus.id');
			$this->db->where('menu_roles.role_id', $role_id);

			$this->db->where('menu_links.status', 'active');
			$this->db->where('menus.status', 'active');
			$this->db->order_by('weight ASC');

			$query = $this->db->get();

			return $query->result();
		}
        catch (Exception $ex) {
			echo $ex->getMessage(); die;
		}
	}


	/**
	 *
	 * @param $role_id
	 */
	public function get_main_menu($role_id = 0)
    {
        $table = array();
        $items = $this->get_menulink_by_role($role_id);
        
        foreach($items as $item){
            $table[$item->parent_id][$item->id] = array('title' => $item->title, 'description' => $item->description, 'url' => $item->url);
        }

        //print "<pre>"; print_r($table); print "</pre>";
        $output = $this->get_menu_branch(0, $table, 0, 0);

        // Close off nested lists
        for ($nest = 0; $nest <= $this->current_level-1; $nest++) {
            $output .= '</ul>';
        }
        return $output;
    }


	/**
	 *
	 * @param unknown_type $parent
	 * @param unknown_type $table
	 * @param unknown_type $level
	 * @param unknown_type $maxlevel
	 */
	protected function get_menu_branch($parent, $table, $level, $maxlevel)
	{
		$list = array();
		if (isset($table[$parent])) {
			$list = $table[$parent];
		}

		$output = "";
		while(list($id, $val) = each($list)){

			if ($this->current_level != $level) {

				if ($this->current_level < $level) {
						$output .= "\n".'<ul class="dropdown-menu">';
				} else {
					for ($nest = 1; $nest <= ($this->current_level - $level); $nest++) {
						$output .= '</ul></li>'."\n";
					}
				}
				$this->current_level = $level;
			}

			if ($this->has_sub_menu($id)) {
				$hasSubMenu = true;
			} else {
				$hasSubMenu = false;
			}

			if ($hasSubMenu) {
				$output .= '<li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" ';
			} else {
				$output .= '<li><a href="'.base_url($val['url']).'" ';
			}

			$output .= 'title="'.$val['description'].'">'.$val['title'];

			if ($hasSubMenu) {
				$output .= ' <span class="caret"></span></a>';
			} else {
				$output .= '</a>';
			}

			$this->level++;

			if (!$hasSubMenu) {
				$output .= '</li>'."\n";
			}


			if ((isset($table[$id])) && (($maxlevel > $level + 1) || ($maxlevel == '0'))) {
				$output .= $this->get_menu_branch($id, $table, $level + 1, $maxlevel);
			}

		} // End while loop

		return $output;
	}


	/**
	 * Getting the child existance ..
	 * @param $id
	 */
	public function has_sub_menu($id)
	{
		$id = (int) $id;

		try {
			$this->db->select('id');
			$this->db->from('menu_links');
			$this->db->where('parent_id', $id);
			$query = $this->db->get();

   			if($query->num_rows() > 0) {
				return true;
   			} else {
				return false;
			}
		}
        catch (\Exception $ex) {
			echo $ex->getMessage();die;
		}
	}


	public function get_admin_dashboard()
	{
		try {

			$result = array();

			// Total Customers
			/*$this->db->where('is_deleted', 0);
			$this->db->from('customers');
			$result['total_customers'] = $this->db->count_all_results();

			// Total Rewards
			$this->db->where('is_deleted', 0);
			$this->db->from('rewards');
			$result['total_rewards'] = $this->db->count_all_results();

			// Total Bars
			$this->db->where('is_deleted', 0);
			$result['total_bars'] = $this->db->count_all_results('bars');

			// Total Users
			$result['total_users'] = $this->db->count_all_results('users');

			// Number of users per role
			$this->db->select('roles.id, roles.role_name, count(users.id) as total_users');
			$this->db->from('roles');
			$this->db->join('users', 'roles.id = users.role_id', 'LEFT OUTER');
			$this->db->group_by("roles.id");

			$query = $this->db->get();
			$result['role_users'] = $query->result();

			// Get pending rewards customers
			$this->db->select('customer_rewards.id, rewards.points as points, customer_rewards.status, CONCAT(customers.first_name, " ", customers.last_name) as customer_name, CONCAT(rewards_created.first_name, " ", rewards_created.last_name) as created_by_name');
			$this->db->from('customer_rewards');
			$this->db->join('rewards', "customer_rewards.reward_id = rewards.id");
			$this->db->join('customers', 'customer_rewards.customer_id = customers.id');
			$this->db->join('users AS rewards_created', 'customer_rewards.created_by = rewards_created.id', 'left');
			$this->db->where('customer_rewards.status', 0);
			//$this->db->group_by("customers.id");

			$query = $this->db->get();
			$result['pending_rewards'] = $query->result();

			// Get pending bar request
			$this->db->select('bar_requests.id, bar_requests.bar_id, bar_requests.user_id, bar_requests.status, bars.bar_name, CONCAT(users.first_name, " ", users.last_name) as user_name');
			$this->db->from('bar_requests');
			$this->db->join('bars', "bar_requests.bar_id = bars.id");
			$this->db->join('users', 'bar_requests.user_id = users.id');
			$this->db->where('bar_requests.status', 1);
			//$this->db->group_by("customers.id");

			$query = $this->db->get();
			$result['pending_requests'] = $query->result();*/

			return $result;
		}
		catch (\Exception $ex) {
			echo $ex->getMessage();die;
		}
	}

	public function get_dashboard()
	{
		try {

			$result = array();

			// Total Tester
			$this->db->select('count(users.id) as total_tester');
			$this->db->from('users');
			$this->db->where('users.status','active');
			$this->db->where('users.is_deleted',0);
			$this->db->where('users.user_type','tester');
			$this->db->where('created_by',$this->session->userdata('id'));
			
			$result['total_tester'] = $this->db->count_all_results();

			//Total Job
			$logged_in_agency_id=$this->session->userdata('id');
			$this->db->select('count(job.id) as total_job');
			$this->db->from('job');
			$this->db->where('job.is_deleted',0);
			$qr="(job.job_status='2' OR job.job_status='3')";
        	$this->db->where($qr);
        	//$this->db->where('job.job_status',2);
        	$this->db->where("(taster_id LIKE '%$logged_in_agency_id%')");
			
			$result['total_job'] = $this->db->count_all_results();
			return $result;
		}
		catch (\Exception $ex) {
			echo $ex->getMessage();die;
		}
	}

}

/* End of file admin_model.php */
/* Location: ./application/models/admin_model.php */
