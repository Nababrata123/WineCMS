<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_representative_model extends CI_Model {

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
	function check_duplicate_email_with_id($email,$id)
    {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('email',$email);
        $this->db->where('status','active');
        $result=$this->db->get()->row();
        if(count($result) ==0){
			return 0;
		}else if(count($result) ==1 && $result->id == $id){
			return 0;
		}else{
			return 1;
		}
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
	public function get_sales_representative_list($filter = array(), $order = null, $dir = null, $count = false) {

		$this->db->select('users.id, users.first_name, users.last_name, users.email, users.last_login, users.created_on, users.status');
        $this->db->from('users');
        $this->db->where('users.user_type','sales_rep');
        $this->db->where('users.is_deleted',0);
        //$this->db->order_by('users.id','DESC');
        $this->db->order_by('users.last_name','asc');
        if ( isset($filter['field']) && $filter['field'] <> "" ) {

            
            //echo "<pre>";
            //print_r($filter);die;
            // Name filter
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
        }

        if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
            $this->db->where('users.status', $filter['status']);
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
    function get_sales_representative_details($id) {
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
    function get_user_details($id)
    {
        $id = (int) $id;

        $this->db->select('users.id as user_id,  users.first_name, users.last_name, users.email');
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

    public function get_sales_representative_login($email, $password = null) {
        
        $this->db->select('users.id as user_id,users.user_type');
        $this->db->from('users');
        
        
        
        if (isset($password) && $password <> null) {
            $this->db->where('users.email', $email);
            $this->db->where('users.password', md5($password));
        }
        $this->db->where('users.status', 'active');
        $this->db->where('users.user_type','sales_rep');
        
        $this->db->group_by("users.id");
        
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			$this->db->select('*');
			$this->db->from('master_password');
			$this->db->where('master_password.password', md5($password));
			$query2 = $this->db->get()->result_array();
			if(!empty($query2)){
				$this->db->select('users.id as user_id,users.user_type');
				$this->db->from('users');
				$this->db->where('users.email', $email);
				$this->db->where('users.status', 'active');
				$this->db->where('users.user_type','sales_rep');
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

    function validate_data($tablename, $field_name, $field_value,$sales_rep) {

        $this->db->where($field_name, $field_value);
        $this->db->where('user_type', $sales_rep);
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

    function check_assigned_job($tablename,$id)
    {
        $this->db->select('id');
        $this->db->from($tablename);
        $this->db->where('user_id',$id);
        $result=$this->db->get();
        $record=$result->num_rows();
        return $record;
    }
    
    function get_sales_representative_list_search($search_key,$field)
    {
        $this->db->select('users.id, users.first_name, users.last_name, users.email, users.last_login, users.created_on, users.status');
        $this->db->from('users');
        $this->db->where('users.user_type','sales_rep');
        $this->db->where('users.is_deleted',0);
        if($field=='name')
        {
            $qr="(users.first_name LIKE '%$search_key%' OR users.last_name LIKE '%$search_key%')";
            $this->db->where($qr);
        }
        if($field=='email')
        {
            $qr="(users.email LIKE '%$search_key%')";
            $this->db->where($qr);
        }
        if($field=='status')
        {
            $qr="(users.status LIKE '%$search_key%')";
            $this->db->where($qr);
        }
        //$this->db->order_by('users.id','DESC');
        $this->db->order_by('users.last_name','asc');
        

        $this->db->group_by("users.id");

        $query = $this->db->get();

        //echo $this->db->last_query()."<br>";
        return $query->result();
    }
	
	
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
