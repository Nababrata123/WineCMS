<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {

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
    function get_category_list($filter = array(), $order = null, $dir = null, $count = false) {
        

        $this->db->select("category.*,  CONCAT(category_created.first_name, ' ', category_created.last_name) as created_by_name, CONCAT(category_updated.first_name, ' ', category_updated.last_name) as updated_by_name");
        $this->db->from('category');
        $this->db->join('users AS category_created', 'category.created_by = category_created.id', 'left');
        $this->db->join('users AS category_updated', 'category.updated_by = category_updated.id', 'left');

        if (isset($filter['deleted']) && $filter['deleted'] !== "") {
            $this->db->where('category.is_deleted', $filter['deleted']);
   		}

		if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
    		$this->db->where('category.status', $filter['status']);
   		}
        if (isset($filter['name']) && $filter['name'] != "") {
            $name=str_replace('-', ' ', trim($filter['name']));
            $this->db->where("(category.name LIKE '%".$name."%')");
        }
        if (isset($filter['parent_id']) && $filter['parent_id'] !== "") {
            $this->db->where('category.parent_id', $filter['parent_id']);
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
   			$this->db->order_by('category.updated_on DESC');
    	}

    	$this->db->group_by("category.id");

		$query = $this->db->get();

   		//echo $this->db->last_query()."<br>";die;
        $result = array();

        if (!isset($search['level'])) {
            $filter['level'] = 0;
        } else {
            $filter['level']++;
        }

        if($query->num_rows())
        {
            foreach ($query->result() as $row)
            {
                if (!isset($filter['tag'])) {
                    $filter['tag'] = '';
                }

                if ($filter['parent_id'] <> 0) {
                    $row->name = $filter['tag']." ".$row->name;
                }

                //$no_of_product = $this->get_product_count($row->id);
                
                $no_of_subcategory = $this->get_subcategory_count($row->id);

                $result[] = array (
                    'id' => $row->id,
                    'name' => $row->name,
                    'status' => $row->status,
                    'parent_id' => $row->parent_id,
                    
                    'created_by_name' => $row->created_by_name,
                    'created_on' => $row->created_on,
                    'updated_by_name' => $row->updated_by_name,
                    'updated_on' => $row->updated_on,
                    
                );


                $res = array();

                //if ($search['parent_id'] == 0) {

                $arg['parent_id'] = $row->id;
                if (isset($filter['status']) && $filter['status'] <> "") {
                       $arg['status'] = $filter['status'];
                }
                /*if (isset($filter['name']) && $filter['name'] != "") {
                    $name=str_replace('-', ' ', trim($filter['name']));
                     $this->db->where("(category.name LIKE '%".$name."%')");
                }*/
                if (isset($filter['deleted']) && $filter['deleted'] !== "") {
                       $arg['deleted'] = $filter['deleted'];
                }
                
                $arg['tag'] = $filter['tag'].$filter['tag'];
                //print "<pre>"; print_r($arg); print "</pre>";
                $res = $this->get_category_list($arg);
                //}

                $result = array_merge($result, $res);


            }
        }
        //echo $this->db->last_query()."<br>";die;
   		return $result;
    }

    public function get_subcategory_count($category_id) {

        $this->db->select('count(*) as count');
        $this->db->from('category');
        $this->db->where('parent_id', $category_id);

        $query = $this->db->get();
        $result = $query->row();
        return $result->count;
    }


    /**
     *
     * @param $user_id
     * @return array
     */
    function get_category_details($id) {
    	$id = (int) $id;
       //echo $id;die;

        $this->db->select("category.*,  CONCAT(category_created.first_name, ' ', category_created.last_name) as created_by_name, CONCAT(category_updated.first_name, ' ', category_updated.last_name) as updated_by_name");
    	$this->db->from('category');
        $this->db->join('users AS category_created', 'category.created_by = category_created.id', 'left');
		$this->db->join('users AS category_updated', 'category.updated_by = category_updated.id', 'left');
        $this->db->where('category.id', $id);
        $this->db->group_by("category.id");

		$query = $this->db->get();
		//echo $this->db->last_query()."<br>";die;
   		return $query->row();
    }
    public function get_category($category_id)
    {
        $id = (int) $category_id;
        $this->db->select("category.id,category.name");
        $this->db->from('category');
    
        $this->db->where('category.id', $id);
        $this->db->group_by("category.id");

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        return $query->row();
    }
    public function search_category($category_id_container)
    {
        
        $this->db->select("category.id,category.parent_id,category.name");
        $this->db->from('category');
    
        $this->db->where_in('category.id', $category_id_container);
        //$this->db->group_by("category.id");

        $query = $this->db->get();
        //echo $this->db->last_query()."<br>";die;
        return $query->result_array();
    }


    public function insert_product_pic($product_id, $images) {

    	//$this->db->where('product_id', $product_id);
		//$this->db->delete('product_image');

    	foreach ($images as $image)
        {
        	$data = array('product_id' => $product_id, 'image' => $image);
        	$this->db->insert('product_images', $data);
        }
    }

    public function insert_product_size($product_id, $sizes) {

    	$this->db->where('product_id', $product_id);
		$this->db->delete('product_size');

    	foreach ($sizes as $size_id)
        {
        	$data = array('product_id' => $product_id, 'size_id' => $size_id);
        	$this->db->insert('product_size', $data);
        }
    }

    /**
     *
     * @param $user_id
     * @return array
     */
    function get_product_details($id) {
    	$id = (int) $id;

    	$this->db->select("products.*, GROUP_CONCAT(DISTINCT sizes.name) as size_names, GROUP_CONCAT(DISTINCT product_size.size_id) as size_ids,  CONCAT(products_created.first_name, ' ', products_created.last_name) as created_by_name, CONCAT(products_updated.first_name, ' ', products_updated.last_name) as updated_by_name");
		$this->db->from('products');
        $this->db->join('product_size', 'products.id = product_size.product_id');
        $this->db->join('sizes', 'product_size.size_id = sizes.id');
		$this->db->join('users AS products_created', 'products.created_by = products_created.id', 'left');
		$this->db->join('users AS products_updated', 'products.updated_by = products_updated.id', 'left');
		$this->db->where('products.id', $id);
        $this->db->group_by("products.id");

		$query = $this->db->get();
        
		//echo $this->db->last_query()."<br>";die;
   		return $query->row();
    }

    /**
	 *
	 * @param unknown_type $filter
	 * @param unknown_type $order
	 * @param unknown_type $dir
	 * @param unknown_type $count
	 */
	public function get_product_list($filter = array(), $order = null, $dir = null, $count = false) {

		$this->db->select('products.*, GROUP_CONCAT(DISTINCT sizes.name) as sizes_name, CONCAT(users_created.first_name, " ", users_created.last_name) as created_by_name, product_images.image');
		$this->db->from('products');
        $this->db->join('product_size', 'products.id = product_size.product_id');
        $this->db->join('product_images', 'products.id = product_images.product_id AND product_images.is_deleted = 0', 'left outer');
        $this->db->join('sizes', 'product_size.size_id = sizes.id');
        $this->db->join('users AS users_created', 'products.created_by = users_created.id', 'left');

		if (isset($filter['deleted']) && $filter['deleted'] !== "") {
            $this->db->where('products.is_deleted', $filter['deleted']);
   		}

        if (isset($filter['name']) && $filter['name'] != "") {
            $this->db->like('products.name', $filter['name']);
   		}

        if (isset($filter['size_id']) && $filter['size_id'] > 0) {
            $this->db->where('product_size.size_id', $filter['size_id']);
   		}

		if (isset($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
    		$this->db->where('products.status', $filter['status']);
   		}

   		if ($count) {
            $this->db->group_by("products.id");
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

    	$this->db->group_by("products.id");

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
	public function get_product_images_list($product_id) {

		$this->db->select('product_images.*');
		$this->db->from('product_images');
        $this->db->where('product_images.is_deleted', 0);
        $this->db->where('product_images.product_id', $product_id);
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

    	$this->db->select('product_images.*');
		$this->db->from('product_images');
		$this->db->where('product_images.id', $id);
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
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
