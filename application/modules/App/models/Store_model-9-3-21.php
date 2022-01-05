<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store_model extends CI_Model {

    protected $current_level, $level;


    /**
     *
     * @param $tablename
     * @param $data
     */
    function __construct() {
        // Set table name
        $this->table = 'store';
        // Set orderable column fields
        $this->column_order = array('name','city','status');
        // Set searchable column fields
        //$this->column_search = array('name','city','status');
        $this->column_search = array('name','city','adress','account_number','status');
        // Set default order
        $this->order = array('name' => 'asc');
    }
    
    /*
     * Fetch members data from the database
     * @param array filter data based on the passed parameters
     */
    function getRows($params = array()){
        $this->db->select('*');
        $this->db->from('store');
        
        if(array_key_exists("where", $params)){
            foreach($params['where'] as $key => $val){
                $this->db->where($key, $val);
            }
        }
        
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
            $result = $this->db->count_all_results();
        }else{
            if(array_key_exists("id", $params)){
                $this->db->where('id', $params['id']);
                $query = $this->db->get();
                $result = $query->row_array();
            }else{
                $this->db->order_by('id', 'desc');
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit'],$params['start']);
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit']);
                }
                
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
        }
        
        // Return fetched data
        return $result;
    }
    
    /*
     * Insert members data into the database
     * @param $data data to be insert based on the passed parameters
     */
    public function insert_data($data = array()) {
        if(!empty($data)){
            // Add created and modified date if not included
            
            
            // Insert member data
            $insert = $this->db->insert('store', $data);
            
            // Return the status
            return $insert?$this->db->insert_id():false;
        }
        return false;
    }
    
    /*
     * Update member data into the database
     * @param $data array to be update based on the passed parameters
     * @param $condition array filter data
     */
    public function update_data($data, $condition = array()) {
        if(!empty($data)){
            // Add modified date if not included
            
            
            // Update member data
            $update = $this->db->update('store', $data, $condition);
            
            // Return the status
            return $update?true:false;
        }
        return false;
    }
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
	public function get_store_list($filter = array(), $order = null, $dir = null, $count = false) {

		//echo "<pre>";print_r($filter);die;

		$this->db->select('*');
        $this->db->from('store');
       $this->db->where('store.is_deleted',0);

        if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
            $this->db->where('store.status', $filter['status']);
        }

        if (isset($filter['name']) && $filter['name'] != "" && $filter['name'] != "~") {
            $name = str_replace("+"," ", $filter['name']);
            $this->db->like('store.name', $name,'both');
        }

        if ($count) {
            return $this->db->count_all_results();
        }

        if ( (isset($filter['length']) && $filter['length'] > 0) && (isset($filter['start']) ) ) {
            $this->db->limit($filter['length'], $filter['start']);
        }
        
        /*if ($order <> null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('updated_on ASC');
        }*/
        //$this->db->order_by('id','DESC');
        $this->db->order_by('store.name','asc');
        $this->db->group_by("store.id");

        $query = $this->db->get();

        //echo $this->db->last_query()."<br>";die;
        return $query->result();
    }
    public function get_store_list_for_csv($items_to_export)
    {
        $ids = array();
        foreach($items_to_export as $key=>$val) {
         array_push($ids, $key);
        }
        $this->db->select('store.id,store.name,store.adress as address,store.suite_number,store.city,store.state,store.zipcode,store.phone,store.email,store.account_number,store.special_request,zone.id as zone,store.wine_sell_type as product_type,store.sales_rep');
        $this->db->from('store');
        $this->db->join('zone', 'store.zone = zone.id AND zone.is_deleted=0', 'left ');
        //$this->db->where('store.is_deleted',0);
        $this->db->where_in('store.id',$ids);

        //$this->db->group_by("store.id");

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die; 
        //return $query->result_array();
        foreach($query->result_array() as $val){
            if($val['product_type'] =='royal/mix'){
                $val['product_type']='royal/myx';
            }
            if($val['product_type'] =='mix'){
                $val['product_type']='myx';
            }
            
            $data[]=$val;
        }
        return $data; 
    }

    public function get_store($user_id='') {

        
            
        //$this->db->select('store.id,store.name,store.adress as address,suite_number as suite_and_apt,city,state,zipcode,phone,sales_rep as Sales_representative,zone as zone_id,zone.name as zone_name,UPPER(wine_sell_type) as wine_sell_type,logo');
        $this->db->select('store.id, store.name, store.adress as address, store.account_number, suite_number as suite_and_apt, city, state,  zipcode, phone, sales_rep as Sales_representative, zone as zone_id, zone.name as zone_name, UPPER(wine_sell_type) as wine_sell_type,logo');
        $this->db->from('store');
        $this->db->join('zone','store.zone = zone.id');
        if($user_id)
        {
            
            $this->db->like('store.sales_rep', $user_id, 'both'); 
        }
        $this->db->where('store.status','active');
        $this->db->where('store.is_deleted','0');
        $this->db->group_by("store.id");

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
    function get_store_details($id) {
    	$id = (int) $id;

        $this->db->select("store.*,  CONCAT(store_created.first_name, ' ', store_created.last_name) as created_by_name, CONCAT(store_updated.first_name, ' ', store_updated.last_name) as updated_by_name");
        $this->db->from('store');
        $this->db->join('users AS store_created', 'store.created_by = store_created.id', 'left');
        $this->db->join('users AS store_updated', 'store.updated_by = store_updated.id', 'left');
        $this->db->where('store.id', $id);
        $this->db->group_by("store.id");

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        $result = $query->row();

        return $result;
    }
    
    function get_salesRepresentative_list()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('users.status','active');
        $this->db->where('users.user_type','sales_rep');
		$this->db->where('users.is_deleted',0);
        $this->db->order_by('last_name','asc');
        $sales_rep = $this->db->get();
        return $sales_rep->result();
    }

    function get_zone_list()
    {
        $this->db->select('*');
        $this->db->from('zone');
        $this->db->where('zone.status','active');
        $this->db->where('zone.is_deleted',0);
        $this->db->order_by('name','ASC');
        $zone = $this->db->get();
        return $zone->result();

    }
    public function get_store_location($store_id){
        $this->db->select('store.latitude, store.longitude');
        $this->db->from('store');
        $this->db->where('store.id',$store_id);
        $data = $this->db->get();
            $result = $data->row();
             return $result;
    }

	public function getName($ids){
		$id=explode('#',$ids);
		$name=array();
		foreach($id as $i){
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where('users.id',$i);
			$sales_rep = $this->db->get();
			$result = $sales_rep->row();
			if($result){
				array_push($name,$result->first_name.' '.$result->last_name);
			}
			
		}
		return implode(',',$name);
		
	}
    public function countAll(){
        $this->db->from("store");
        return $this->db->count_all_results();
    }
    public function countFiltered($postData){
        $term = $postData['search']['value'];
        $this->_get_datatables_query($postData,$term);
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->num_rows();
    }
    function get_datatables($postData){
      //echo "<pre>";
      //print_r($postData);
      $term = $postData['search']['value'];   
      $this->_get_datatables_query($postData,$term);
      if($postData['length'] != -1)
      $this->db->limit($postData['length'], $postData['start']);
      $query = $this->db->get();
     // echo $this->db->last_query();
      return $query->result(); 
    }
    private function _get_datatables_query($postData,$term=''){
        //echo $term;die;
        $this->db->select('*'); 
        $this->db->from('store');
        $this->db->where('is_deleted',0);
        $i = 0;
        //echo "<pre>";
        //print_r($this->column_search);
        //print_r($postData);die;
        // loop searchable columns 
        foreach($this->column_search as $item){
            // if datatable send POST for search
            if($item!=''){
                //$keyword=$postData['search']['value'];
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $term);

                }else{
                    $this->db->or_like($item, $term);
                }
                
                // last loop
                if(count($this->column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
                
            }
            $i++;
        }
        
         
        /*if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }*/
        if(isset($postData['order'])) // here order processing
        {
           $this->db->order_by($this->column_order[$postData['order']['0']['column']],$postData['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
           $order = $this->order;
           $this->db->order_by(key($order), $order[key($order)]);
        }
    }
	public function check_salesRep($sales_repIds){
		//print_r($sales_repIds);
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('users.user_type','sales_rep');
		$this->db->where('users.is_deleted',0);
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();die;
		
		$sales_rep = array_column($result,'id');
		//print_r($sales_rep);
		if(count(array_intersect($sales_repIds, $sales_rep)) == count($sales_repIds)){
			return false;
		}else{
			return true;
		}
	}
	public function check_zone($zone_id){
		//echo $zone_id;die;
		$this->db->select('*');
		$this->db->from('zone');
		$this->db->where('zone.id',$zone_id);
		$this->db->where('zone.is_deleted',0);
		if($this->db->count_all_results()==1){
			return false;
		}else{
			return true;
		}
	}
	public function getStoreDetails($store_id){
		$this->db->select('*');
		$this->db->from('store');
		$this->db->where('store.id',$store_id);
		return $this->db->get()->row();
    }
    public function get_store_name($store_id){
        $this->db->select('store.name');
        $this->db->from('store');
        $this->db->where('store.id',$store_id);
        $data = $this->db->get();
        $result = $data->row();
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
