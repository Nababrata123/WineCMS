<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Public_model extends CI_Model {

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
	 * Validate customer
	 * @param string $email
	 * @return boolean
	 */
	/*function validate_customer($email) {

		if ($email == "" || $email === NULL) {
			return false;
		}

		$this->db->where('email', $email);
		$this->db->from('customers');
		$result = $this->db->count_all_results();

		if ($result > 0) {
			return false;
		} else {
			return true;
		}
	}

	function validate_customer_fbid($fb_id) {

		if ($fb_id == "" || $fb_id === NULL) {
			return false;
		}

		$this->db->where('fb_id', $fb_id);
		$this->db->from('customers');
		$result = $this->db->count_all_results();

		if ($result > 0) {
			return false;
		} else {
			return true;
		}
	}

	function validate_customer_gmid($gm_id) {

		if ($gm_id == "" || $gm_id === NULL) {
			return false;
		}

		$this->db->where('gm_id', $gm_id);
		$this->db->from('customers');
		$result = $this->db->count_all_results();

		if ($result > 0) {
			return false;
		} else {
			return true;
		}
	}


	public function get_customer_login($email, $password = '', $fb_id = '', $gm_id = '') {

		$this->db->select('customers.*');
		$this->db->from('customers');
		//$this->db->join('locations', 'customers.location_id = locations.id', 'left');

		if (isset($fb_id) && $fb_id <> "") {
			$this->db->where('customers.email', $email);
			$this->db->where('customers.fb_id', $fb_id);
		}

		if (isset($gm_id) && $gm_id <> "") {
			$this->db->where('customers.email', $email);
			$this->db->where('customers.gm_id', $gm_id);
		}

		if (isset($password) && $password <> "") {
			$this->db->where('customers.email', $email);
			$this->db->where('customers.password', md5($password));
		}
		$this->db->where('customers.status', 'active');
		$this->db->group_by("customers.id");

		$query = $this->db->get();
		return $query->row();
	}

	public function update_user_data($user_id, $gcm) {

		if (!$user_id) {
			return false;
		}

		$this->db->where('user_id', $user_id);
		$this->db->where('meta_key', 'gcm');
		$this->db->from('user_meta');
		$result = $this->db->count_all_results(); // Produces an integer, like 17

		if ($result > 0) {
			// Update
			$data = array(
		        'meta_value' => $gcm
			);
			$this->db->where('meta_key', 'gcm');
			$this->db->where('user_id', $user_id);
			$this->db->update('user_meta', $data);

		} else {
			// Insert
			$data = array(
		        'user_id' => $user_id,
		        'meta_key' => 'gcm',
		        'meta_value' => $gcm
			);

			$this->db->insert('user_meta', $data);

		}
		return true;
	}


	public function get_user_mobile($phone) {

		$this->db->select('users.id, users.name, users.status');
		$this->db->from('users');
		$this->db->join('user_meta', 'users.id = user_meta.user_id');

		$this->db->where('user_meta.meta_key', "phone");
		$this->db->where('user_meta.meta_value', $phone);
		$this->db->where('users.status', "active");
		$this->db->group_by("users.id");

		if ($this->db->count_all_results() > 0 ) {
			return true;
		} else {
			return false;
		}
	}


    public function get_user_details($id) {

		$this->db->select('users.id, users.role_id, users.name, users.email, roles.role_name, roles.role_token, user_meta.meta_value AS phone, users.status, users.last_login, users.created_on, users_created.name as created_by_name, users.updated_on, users_updated.name as updated_by_name');
		$this->db->from('users');
		$this->db->join('roles', 'users.role_id = roles.id');
		$this->db->join('user_meta', 'users.id = user_meta.user_id');
		$this->db->join('users AS users_created', 'users.created_by = users_created.id', 'left');
		$this->db->join('users AS users_updated', 'users.updated_by = users_updated.id', 'left');
		$this->db->where('user_meta.meta_key', "phone");
		$this->db->where('users.id', $id);

		$query = $this->db->get();
		return $query->row();
	}
*/

	public function get_cms($page = null) {
		if ($page == null) {
			return;
		}

		$this->db->from('cms');
		$this->db->where('cms.slug', $page);

		$query = $this->db->get();
		return $query->row();
	}

	public function get_countries() {

		try {
			$this->db->from('countries');

			$query = $this->db->get();
			$result = $query->result();

			if(!$result) {
			  	throw new exception("Country not found.");
			}

			$res = array();
			foreach ($result as $key => $resultSet) {
				$res[$resultSet->id] = $resultSet->name;
			}
			$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$res);
		} catch (Exception $e) {
			$data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
		} finally {
			return $data;
		}
	}

	public function get_states($country_id) {

		try {
			$this->db->from('states');
			$this->db->where('country_id', $country_id);

			$query = $this->db->get();
			$result = $query->result();

			if(!$result) {
				throw new exception("State not found.");
			}

			$res = array();
			foreach ($result as $key => $resultSet) {
				$res[$resultSet->id] = $resultSet->name;
			}
			$data = array('status' => 'success', 'tp'=> 1, 'msg' => "States fetched successfully.", 'result' => $res);
		} catch (Exception $e) {
			$data = array('status' => 'error', 'tp' => 0, 'msg' => $e->getMessage());
		} finally {
			return $data;
		}
	}

    public function getprogramType() {

      $this->db->select('program_type.*');
      $this->db->from('program_type');
      $this->db->where('program_type.is_deleted', 0);
      $this->db->order_by('id', 'DESC');
      $this->db->limit(6, 0);

      $query = $this->db->get();
      return $query->result();
    }

    public function getprogramPlan() {

        $this->db->select('program_plan.*, program_type.name as program_type_name, program_type.difficulty as program_type_difficulty');
        $this->db->from('program_plan');
        $this->db->join('program_type', 'program_type.id = program_plan.program_type_id', 'left');
        $this->db->where('program_plan.status', 'active');
      	$this->db->order_by('id', 'DESC');
      	$this->db->limit(4, 0);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

}

/* End of file Api_model.php */
/* Location: ./application/modules/App/models/Api_model.php */
