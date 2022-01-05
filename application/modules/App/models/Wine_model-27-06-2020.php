<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wine_model extends CI_Model {

    protected $current_level, $level;


    /**
     *
     * @param $tablename
     * @param $data
     */
    function getRows($params = array()){
        $this->db->select('*');
        $this->db->from('wine');
        
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
            $insert = $this->db->insert('wine', $data);
            
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
            $update = $this->db->update('wine', $data, $condition);
            
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
    function check_job($id)
    {
        $id = (int) $id;
        $this->db->select('id');
        $this->db->from('job');
        $this->db->where('job.is_deleted',0);
        //$this->db->where('job.status <>','completed');
        $this->db->where("(wine_id LIKE '%$id%')");
        $result=$this->db->get();
        //echo $this->db->last_query();die;
        $record=$result->num_rows();
        return $record;
    }

    /**
     *
     * @param $filter
     */
  


    /**
     *
     * @param $user_id
     * @return array
     */
    


    public function insert_product_pic($wine_id, $images) {

        //$this->db->where('product_id', $product_id);
        //$this->db->delete('product_image');

        foreach ($images as $image)
        {
            $data = array('wine_id' => $wine_id, 'image' => $image);
            $this->db->insert('wine_images', $data);
        }
    }

    

    /**
     *
     * @param $user_id
     * @return array
     */
    function get_wine_details($id) {
        $id = (int) $id;

        $this->db->select("wine.*, CONCAT(products_created.first_name, ' ', products_created.last_name) as created_by_name, CONCAT(products_updated.first_name, ' ', products_updated.last_name) as updated_by_name");
        $this->db->from('wine');
        
        $this->db->join('users AS products_created', 'wine.created_by = products_created.id', 'left');
        $this->db->join('users AS products_updated', 'wine.updated_by = products_updated.id', 'left');
        $this->db->where('wine.id', $id);
        $this->db->group_by("wine.id");

        $query = $this->db->get();
        
        //echo $this->db->last_query()."<br>";die;
        return $query->row();
   }
    public function get_wine($category_id,$wine_sell_type)
    {
        $category_id = (int) $category_id;

        $this->db->select("wine.id as id,wine.name as name,wine.description,UPPER(wine.flavour)  as type,wine.company_type,wine.upc_code as upc,wine.brand,wine.year,CONCAT(wine.size,' ',wine.UOM)as size,wine.category_id");
        $this->db->from('wine');
        $this->db->where('wine.is_deleted','0');
        
        if($category_id!='')
        {
            $this->db->where('wine.category_id', $category_id);
        }
        
        if(strtolower($wine_sell_type)=='royal')
        {
            $this->db->where('wine.flavour','royal');
        }
        else if(strtolower($wine_sell_type)=='mix')
        {
            $this->db->where('wine.flavour','mix');
        }
        
        
        $this->db->group_by("wine.id");

        $query = $this->db->get();
        
       // echo $this->db->last_query()."<br>";die;
        
        $wine_array=$query->result_array();
       
        /*$c=0;
        foreach($wine_array as $single_array)
        {
           
            foreach($single_array as $key=>$values)
            {

                if($key=='type')
                {
                    if(strpos($values,",") !== false)
                    {
                        $values=str_replace(',', '/',$values);
                        $wine_array[$c]['type']=$values;
                    }
                    
                }
            }
            $c++;
        }*/
        
        for($i=0;$i<count($wine_array);$i++)
        {
            //array_push($wine_array_id_container,$val['id']);

            //get wine images
            $this->db->select('image');
            $this->db->from('wine_images');
            $this->db->where('is_deleted',0);
            $this->db->where('wine_id',$wine_array[$i]['id']);
            $query_image=$this->db->get();
            $wine_image_array=$query_image->result_array();
            //echo "<pre>";
            //print_r($wine_image_array);die;
            $image_name='';
            for($j=0;$j<count($wine_image_array);$j++)
            {
                $image_name.=$wine_image_array[$j]['image'].",";
            }
            $image_name=rtrim($image_name,",");
            //echo "<pre>";
            //print_r($wine_image_array);die;
            $wine_array[$i]['images']=$image_name;
            $image_name='';

        }

        
        
        return $wine_array;
    }
    public function search_wine($search_text,$wine_sell_type)
    {
        

        $this->db->select("wine.id as id,wine.name as name,wine.description,UPPER(wine.flavour)  as type,wine.company_type,wine.upc_code as upc,wine.brand,wine.year,CONCAT(wine.size,' ',wine.UOM)as size,wine.category_id");
        $this->db->from('wine');
        $this->db->join('category', 'category.id = wine.category_id');
        $this->db->where('wine.is_deleted','0');

        if(strtolower($wine_sell_type)=='royal')
        {
            $this->db->where('wine.flavour','royal');
        }
        else if(strtolower($wine_sell_type)=='mix')
        {
            $this->db->where('wine.flavour','mix');
        }
        
        if($search_text!='')
        {
            $this->db->like('wine.name',$search_text,'both');
            $this->db->or_like('wine.upc_code', $search_text,'both');
            $this->db->or_like('wine.brand', $search_text,'both');
            $this->db->or_like('category.name', $search_text,'both');
        }
        
        
        
        
        $this->db->group_by("wine.id");
        $this->db->order_by("wine.id",'DESC');
        $query = $this->db->get();
        
       // echo $this->db->last_query()."<br>";die;
        
        $wine_array=$query->result_array();
       
        
        
        for($i=0;$i<count($wine_array);$i++)
        {
            //array_push($wine_array_id_container,$val['id']);

            //get wine images
            $this->db->select('image');
            $this->db->from('wine_images');
            $this->db->where('is_deleted',0);
            $this->db->where('wine_id',$wine_array[$i]['id']);
            $query_image=$this->db->get();
            $wine_image_array=$query_image->result_array();
            //echo "<pre>";
            //print_r($wine_image_array);die;
            $image_name='';
            for($j=0;$j<count($wine_image_array);$j++)
            {
                $image_name.=$wine_image_array[$j]['image'].",";
            }
            $image_name=rtrim($image_name,",");
            //echo "<pre>";
            //print_r($wine_image_array);die;
            $wine_array[$i]['images']=$image_name;
            $image_name='';

        }

        
        
        return $wine_array;
    }

    /**
     *
     * @param unknown_type $filter
     * @param unknown_type $order
     * @param unknown_type $dir
     * @param unknown_type $count
     */
    public function get_wine_list($filter = array(), $order = null, $dir = null, $count = false) {
        

        $this->db->select('wine.*,  CONCAT(users_created.first_name, " ", users_created.last_name) as created_by_name, wine_images.image');
        $this->db->from('wine');
        $this->db->where('wine.is_deleted',0);
        $this->db->join('wine_images', 'wine.id = wine_images.wine_id AND wine_images.is_deleted = 0', 'left outer');
        
        $this->db->join('users AS users_created', 'wine.created_by = users_created.id', 'left');
        $this->db->order_by('wine.name','asc');
        if (isset($filter['deleted']) && $filter['deleted'] !== "") {
            $this->db->where('wine.is_deleted', $filter['deleted']);
        }

        if (isset($filter['name']) && $filter['name'] != "") {
            $name=substr($filter['name'],0,4);
            //$this->db->like('wine.name', $filter['name'],'both');
            $this->db->like('wine.name', $name,'both');
        }
        if (isset($filter['upc_code']) && $filter['upc_code'] != "") {
            
            $this->db->where('wine.upc_code', $filter['upc_code']);
        }
        if (isset($filter['sampling_date']) && $filter['sampling_date'] != "") {
            $this->db->join('job','wine.id=job.wine_id');
            //$this->db->join('job','job.tasting_date='.$filter['sampling_date']);
            $this->db->where('job.tasting_date', $filter['sampling_date']);
        }
        if (isset($filter['sampling_status']) && $filter['sampling_status'] != "") {
            if($filter['sampling_status']=='done')
            {
                $this->db->join("completed_job_wine_details","wine.id=completed_job_wine_details.wine_id");
            }
        }
        if (isset($filter['bottles']) && $filter['bottles'] != "") {
            $this->db->join("completed_job_wine_details d","wine.id=d.wine_id");
            $this->db->where('d.bottles_sampled',$filter['bottles']);
        }
        

        if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
            $this->db->where('wine.status', $filter['status']);
        }

        if ($count) {
            $this->db->order_by('updated_on ASC');
            $this->db->group_by("wine.id");
            $query = $this->db->get();
            return $query->num_rows();
        }

        if ( (isset($filter['limit']) && $filter['limit'] > 0) && (isset($filter['offset']) ) ) {
            $this->db->limit($filter['limit'], $filter['offset']);
        }

        if ($order <> null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('updated_on ASC');
        }

        //$this->db->having('MIN(product_images.order) = 1');

        $this->db->group_by("wine.id");

        $query = $this->db->get();

        //echo $this->db->last_query()."<br>";die;
        return $query->result();
    }
    public function get_wine_list_for_csv($items_to_export)
    {
        $ids = array();
        foreach($items_to_export as $key=>$val) {
         array_push($ids, $key);
        }
        $this->db->select('id,upc_code,name,brand,year,type,description,size,UOM,category_id,flavour as company,company_type');
        $this->db->from('wine');
        //$this->db->where('store.is_deleted',0);
        $this->db->where_in('wine.id',$ids);
        

        //$this->db->group_by("store.id");

        $query = $this->db->get();

        //echo $this->db->last_query()."<br>";die;
        return $query->result_array();
        
    }

    /**
     *
     * @param unknown_type $filter
     * @param unknown_type $order
     * @param unknown_type $dir
     * @param unknown_type $count
     */
    public function get_wine_images_list($wine_id) {

        $this->db->select('wine_images.*');
        $this->db->from('wine_images');
        $this->db->where('wine_images.is_deleted', 0);
        $this->db->where('wine_images.wine_id', $wine_id);
        $this->db->order_by('order ASC');

        $query = $this->db->get();

        //echo $this->db->last_query()."<br>";die;
        return $query->result();
    }
    
    /**
     *
     * @param unknown_type $filter
     * @param unknown_type $order
     * @param unknown_type $dir
     * @param unknown_type $count
     */
    public function get_product_size_list($product_id) {

        $this->db->select("sizes.name, sizes.id");
        $this->db->from('sizes');
        $this->db->join('product_size', 'product_size.size_id = sizes.id');
        $this->db->where(array('product_size.product_id' => $product_id));

        $query = $this->db->get();

        //echo $this->db->last_query()."<br>";die;
        return $query->result();
    }

    /**
     *
     * @param $user_id
     * @return array
     */
    function get_images_details($id) {
        $id = (int) $id;

        $this->db->select('wine_images.*');
        $this->db->from('wine_images');
        $this->db->where('wine_images.id', $id);
        $this->db->order_by('order ASC');
        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        return $query->row();
    }



    /**
     * Get the list of orders
     * 
     * @param $filter
     */
    function get_order_list($filter = array()) {

        $this->db->select("orders.*, count(order_products.id) as total_items, CONCAT(customers.first_name, ' ', customers.last_name) as customer_name");
        $this->db->from('orders');
        $this->db->join('order_products', 'orders.id = order_products.order_id');
        $this->db->join('customers', 'customers.id = orders.customer_id');

        if (isset($filter['customer_id']) && $filter['customer_id'] != "") {
            $this->db->where('orders.customer_id', $filter['customer_id']);
        }

        $this->db->order_by('created_on DESC');
        $this->db->group_by("orders.id");

        $query = $this->db->get();

        //echo $this->db->last_query()."<br>";die;
        $data = array ();
        $i = 0;
        foreach ($query->result() as $key => $row) {

            $data[$i] = $row;
            $data[$i]->items = $this->get_order_items($row->id);
            $i++;
        }
        return $data;
    }

    /**
     * Get order details
     * 
     * @param $filter
     */
    function get_order_details($filter = array()) {

        $this->db->select("orders.*, CONCAT(customers.first_name, ' ', customers.last_name) as customer_name, customers.email as customer_email, order_address.name, order_address.phone, order_address.address, order_address.city, order_address.zipcode, count(order_products.id) as total_items, states.name as state_name, countries.name as country_name");
        $this->db->from('orders');
        $this->db->join('order_products', 'orders.id = order_products.order_id');
        $this->db->join('order_address', 'orders.address_id = order_address.id');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join('states', 'states.id = order_address.state');
        $this->db->join('countries', 'countries.id = order_address.country');

        if (isset($filter['customer_id']) && $filter['customer_id'] != "") {
            $this->db->where('orders.customer_id', $filter['customer_id']);
        }
           
        if (isset($filter['order_id']) && $filter['order_id'] != "") {
            $this->db->where('orders.id', $filter['order_id']);
        }
        $this->db->having('count(order_products.id) > ', 0);

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";
        $row = $query->row();

        if (isset($row->id) && $row->id != "") {
            $row->items = $this->get_order_items($row->id);
        }
        
        return $row;
    }

    /**
     *
     * @param $order_id
     */
    function get_order_items($order_id = null) {

        if ($order_id == null) {
            return false;
        }

        $this->db->select("order_products.id, order_products.qnty, order_products.price, order_products.size, products.name, product_images.image");
        $this->db->from('order_products');
        $this->db->join('products', 'products.id = order_products.product_id');
        $this->db->join('product_images', 'products.id = product_images.product_id AND product_images.is_deleted = 0', 'left outer');

        $this->db->where('order_products.order_id', $order_id);

        //$this->db->order_by('created_on DESC');
        $this->db->group_by("order_products.id");

        $query = $this->db->get();
        return $query->result();
    }
    //Check duplicate upc code
    function check_duplicate_upccode($upc_code,$id)
    {
        $this->db->select('id');
        $this->db->from('wine');
        $this->db->where('upc_code',$upc_code);
        $this->db->where('status','active');
        $this->db->where('is_deleted',0);
        if($id!=null)
        {
            $this->db->where('id <>',$id);
        }
        $value=$this->db->get();
        return $value->num_rows();
    }
    function checkDuplicateUpccode($upc_code)
    {
		//echo $upc_code;die;
        $this->db->select('id');
        $this->db->from('wine');
        $this->db->where('upc_code',$upc_code);
        $this->db->where('status','active');
        $this->db->where('is_deleted',0);
        
        $value=$this->db->get();
        $count=$value->num_rows();
		
        if ($count > 0) {
          //if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
            return FALSE; // here I change TRUE to false.
         } else {
          // doesn't return any row means database doesn't have this email
            return TRUE; // And here false to TRUE
         }
    }
	function checkDuplicateUpccodeWithId($id,$upc_code)
    {
		//echo $upc_code;die;
        $this->db->select('id');
        $this->db->from('wine');
		$this->db->where("id !=",$id);
        $this->db->where('upc_code',$upc_code);
        $this->db->where('status','active');
        $this->db->where('is_deleted',0);
        
        $value=$this->db->get();
        $count=$value->num_rows();
		
        if ($count > 0) {
          //if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
            return FALSE; // here I change TRUE to false.
         } else {
          // doesn't return any row means database doesn't have this email
            return TRUE; // And here false to TRUE
         }
    }
	function checkValidCategory($categoryId){
		$this->db->select('id');
        $this->db->from('category');
		$this->db->where("id",$categoryId);
        $this->db->where('status','active');
        $this->db->where('is_deleted',0);
        
        $value=$this->db->get();
        $count=$value->num_rows();
		
        if ($count > 0) {
            return TRUE; 
        } else {
            return FALSE;
        }
	}
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
