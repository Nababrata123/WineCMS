<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends CI_Model {

    protected $current_level, $level;

    function get_user_details($id) {
        $id = (int) $id;

        $this->db->select('users.id,users.password,users.created_by');
        $this->db->from('users');
        
        $this->db->where('users.id', $id);

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        $result = $query->result_array();

        

        return $result;
    }
    function update($tablename, $field_name, $field_value, $data) {

        $this->db->where($field_name, $field_value);
        if ($this->db->update($tablename, $data)) {
            return true;
        } else {
            return false;
        }
    }
    function update_user_meta($tablename, $field_name, $field_value, $data)
    {

        //Select Manual account number
        $this->db->select('meta_value');
        $this->db->from($tablename);
        $this->db->where('user_id',$field_value);
        $this->db->where('meta_key','manual_account_number');
        $query_account = $this->db->get();
        $result_account=$query_account->result_array();
        $manual_account_number=$result_account[0]['meta_value'];
        $data['manual_account_number']=$manual_account_number;

        $this->db->where($field_name, $field_value);
        $this->db->delete($tablename); 

        //echo "<pre>";
       // print_r($data);die;
        $string='';
        foreach($data as $meta_key => $meta_value) {

            if($meta_key=='zone')
            {
                $meta_value=explode(",",$meta_value);
                foreach($meta_value as $val)
                {
                    $string.=$val.",";
                }
                $meta_value=rtrim($string,",");
                
            }
            
                $data_m = array(
                        'user_id' => $field_value,
                        'meta_key'  => $meta_key,
                        'meta_value'  => htmlspecialchars($meta_value, ENT_QUOTES, 'utf-8')
                    );

            $this->db->insert('user_meta', $data_m);
        }
        return;
    }
	
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
