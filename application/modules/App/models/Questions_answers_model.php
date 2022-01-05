<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Questions_answers_model extends CI_Model {

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

             $this->db->where('id',$id);
            $this->db->delete($tablename);
            return true;
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
	public function get_questions_answers_list($filter = array(), $order = null, $dir = null, $count = false) {

		$this->db->select("question_answer.*,  CONCAT(question_answer_created.first_name, ' ', question_answer_created.last_name) as created_by_name");
        $this->db->from('question_answer');
        $this->db->join('users AS question_answer_created', 'question_answer.created_by = question_answer_created.id', 'left');
        $this->db->order_by('id', 'DESC');
       
        if($count) {
            return $this->db->count_all_results();
        }

        if ( (isset($filter['limit']) && $filter['limit'] > 0) && (isset($filter['offset']) ) ) {
            $this->db->limit($filter['limit'], $filter['offset']);
        }
        if (isset($filter['name']) && $filter['name'] != "") {
            $this->db->like('question_answer.answer_type', $filter['name']);
        }
        

        $this->db->group_by("question_answer.id");

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
    function get_questions_answers_details($id) {
    	$id = (int) $id;

        $this->db->select("*");
        $this->db->from('question_answer');
        $this->db->where('question_answer.id', $id);
        

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
