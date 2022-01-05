<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends Application_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	/**
	 *
	 * @var unknown_type
	 */
	private $tablename = 'store';
	private $url = '/app/store';
	private $reference_url = '';
	private $permissionValues = array(

		'index' => 'App.Store.View',
		'add' => 'App.Store.Add',
		'edit' => 'App.Store.Edit',
        'delete' => 'App.Store.Delete',
        
    );

    //private $allowed_roles = array('bar_admin');

	public function __construct() {

        
		
        parent::__construct();

		// Validate Login
		parent::checkLoggedin();

		$this->module_dir = APPPATH.'modules/'.$this->router->fetch_module();
        $this->load->config('config');

		$this->session->set_userdata('page_data', array('url' => $this->url, 'permissions' => $this->permissionValues));
        $this->load->model('Store_model');
        $this->load->library('geocode');
        $this->load->library('user_agent');
        $this->load->library('CSVReader');
        $this->load->helper('file');
        
    }

	public function index() {

    // Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

		$default_uri = array( 'name','status', 'page','view');
    	$uri = $this->uri->uri_to_assoc(4, $default_uri);
        $pegination_uri = array();
    	
		$status = $uri['status'];
        // Process the filters
        /*if ($uri['view'] <> "") {
            $filter['view'] 		= $uri['view'];
			$pegination_uri['view'] = $uri['view'];
        } else {
			$filter['view'] 		= 10;
			$pegination_uri['view'] = 10;
		}*/
        if (isset($uri['name']) && trim(urldecode($uri['name'])) <> "") {
            $filter['name'] = $uri['name'];
            $pegination_uri['name'] = $uri['name'];
        } else {
            $filter['name'] = "";
            $pegination_uri['name'] = "~";
        }

		if ($uri['page'] > 0) {
    		$page = $uri['page'];
    	} else {
    		$page = 0;
    	}
        
    	// Create the filters
	    //$filter = array();

	    

	    if ($status <> '') {
		    $filter['status'] = $status;
	    } else {
	    	$status = 0;
	    }

	    // Get the total rows without limit
	    $total_rows = $this->Store_model->get_store_list($filter, null, null, true);
        /*$config = $this->init_pagination('App/store/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/',9, $total_rows,$filter['view']);
        


		//$limit_end = ($page * $config['per_page']) - $config['per_page'];
        $limit_end 			= ($page * $filter['view']) - $filter['view'];
	    if ($limit_end < 0){
	        $limit_end = 0;
	    }

	    //$filter['limit'] = $config['per_page'];
        $filter['limit'] = $filter['view'];
	    $filter['offset'] = $limit_end;*/

	    // Get the stores List
	    $data['stores'] = $this->Store_model->get_store_list($filter, 'id', 'asc');

	    
    	$data['filters'] = $uri;
	    $data['page'] = 'store';
    	$data['page_title'] = SITE_NAME.' :: Store Management';

    	$data['main_content'] ='store/list';
    	$this->load->view(TEMPLATE_PATH, $data);
	}



    /**
     *
     */
    public function add() {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    	//$config = $this->config->item('module_config');

    	//$data['user_meta'] = $config['stores']['meta'];

    	//if save button was clicked, get the data sent via post
        
    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{

     		//form validation


    		$this->form_validation->set_rules('name', 'Store Name', 'trim|required');
            $this->form_validation->set_rules('adress', 'Store Adress', 'trim|required');
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('state', 'State', 'trim|required');
            $this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|required');
            //$this->form_validation->set_rules('phone', 'Phone', 'trim|required');

            //$this->form_validation->set_rules('store_manager', 'Manager', 'trim|required');
            $this->form_validation->set_rules('sales_rep[]', 'Sales Representative', 'trim|required');
            //$this->form_validation->set_rules('account_number', 'Account Number', 'trim|required');
            //$this->form_validation->set_rules('special_request', 'Request', 'trim|required');
            $this->form_validation->set_rules('zone', 'Zone', 'trim|required');
            $this->form_validation->set_rules('wine_sell_type[]', 'Wine sell type', 'trim|required');
            
			
			
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    		//if the form has passed through the validation

    		if($this->form_validation->run())
    		{
    			
				$wine_sell_type_array=$this->input->post('wine_sell_type[]');
                $wine_sell_type='';
                foreach($wine_sell_type_array as $val)
                {
                    $wine_sell_type.=$val.'/';
                }
                $wine_sell_type=rtrim($wine_sell_type,'/');

                $address = $this->input->post('adress').' '.$this->input->post('city').' '.$this->input->post('zipcode').' '.$this->input->post('state');
               $result = $this->geocode->get_lat_long($address);
              // echo "<pre>";
              // print_r($result);die;

               if ($result['status']=='OK') {
                   $lat = $result['results']['0']['geometry']['location']['lat'];
                   $long = $result['results']['0']['geometry']['location']['lng'];
               } else { 
                   $lat =0;
                   $long = 0;   
               }
              // echo $lat."<br>".$long;
               //exit;
               $string='';
               $sales_rep_array=$this->input->post('sales_rep');
               foreach($sales_rep_array as $val)
                {
                    $string.=$val.",";
                }
                $sales_rep=rtrim($string,",");

                // Upload store logo
                    /*if(!empty($_FILES))
                    {
                         $pics = $_FILES['pics'];
                    }*/
                //echo "<pre>";
               // print_r($_FILES);die;
                    

                    if (!empty($_FILES['pics']['name'])) {
                        $pics = $_FILES['pics'];

                        // Update Product Image
                        $config['upload_path'] = DIR_STORE_LOGO;
                        $config['max_size'] = '10000';
                        $config['allowed_types'] = 'jpg|png|jpeg|gif|rtf';
                        $config['overwrite'] = FALSE;
                        $config['remove_spaces'] = TRUE;

                        $this->load->library('upload', $config);

                        $images = array();

                        

                            $_FILES['images']['name']= $pics['name'];
                            $_FILES['images']['type']= $pics['type'];
                            $_FILES['images']['tmp_name'] = $pics['tmp_name'];
                            $_FILES['images']['error']= $pics['error'];
                            $_FILES['images']['size']= $pics['size'];

                            $config['file_name'] = 'store-'.rand().date('YmdHis');
                            $images = $config['file_name'];

                            
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('images')) {

                                $config_thumb['image_library'] = 'gd2';
                                $config_thumb['source_image'] = DIR_STORE_LOGO.$this->upload->file_name;
                                $config_thumb['create_thumb'] = FALSE;
                                $config_thumb['maintain_ratio'] = TRUE;
                                $config_thumb['master_dim'] = 'auto';
                                $config_thumb['width'] = STORE_LOGO_SIZE; // image re-size  properties
                                $config_thumb['height'] = STORE_LOGO_SIZE; // image re-size  properties
                                $config_thumb['new_image'] = DIR_STORE_LOGO_THUMB.$this->upload->file_name; // image re-size  properties

                                $this->load->library('image_lib', $config_thumb); //codeigniter default function

                                $this->image_lib->initialize($config_thumb);
                                if (!$this->image_lib->resize()) {
                                     echo $this->image_lib->display_errors();
                                }
                                $this->image_lib->clear();

                                $upload_data =  $this->upload->data();
                                $uploaded_pics = array();
                                $uploaded_pics = $upload_data['file_name'];

                                

                            } else {
                                $this->upload->display_errors(); die;
                            }
                            //echo 1;die;
                        
                    }
                    else
                    {
                        //echo 2; die;
                        $uploaded_pics='';
                    }
                    
    			$store = array(
					'name' => htmlspecialchars($this->input->post('name'), ENT_QUOTES, 'utf-8'),
					'adress' => htmlspecialchars($this->input->post('adress'), ENT_QUOTES, 'utf-8'),
                    'suite_number' => htmlspecialchars($this->input->post('suite_number'), ENT_QUOTES, 'utf-8'),
                    'appartment_number' => htmlspecialchars($this->input->post('appartment_number'), ENT_QUOTES, 'utf-8'),
                    'city' => htmlspecialchars($this->input->post('city'), ENT_QUOTES, 'utf-8'),
                    'state' => htmlspecialchars($this->input->post('state'), ENT_QUOTES, 'utf-8'),
                    'zipcode' => htmlspecialchars($this->input->post('zipcode'), ENT_QUOTES, 'utf-8'),
                    'phone' => htmlspecialchars($this->input->post('phone'), ENT_QUOTES, 'utf-8'),
                    'store_manager' => htmlspecialchars($this->input->post('store_manager'), ENT_QUOTES, 'utf-8'),
                    'account_number' => htmlspecialchars($this->input->post('account_number'), ENT_QUOTES, 'utf-8'),
                    'special_request' => htmlspecialchars($this->input->post('special_request'), ENT_QUOTES, 'utf-8'),
                    'zone' => htmlspecialchars($this->input->post('zone'), ENT_QUOTES, 'utf-8'),
                    'sales_rep'=>$sales_rep,
                    'latitude'=>$lat,
                    'longitude'=>$long,
    				'wine_sell_type' => htmlspecialchars($wine_sell_type, ENT_QUOTES, 'utf-8'),
                    'logo'=>$uploaded_pics,
    				'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
    				
    				'created_by' => $this->session->userdata('id'),
     				'created_on' => date('Y-m-d H:i:s')
    			);

    			
    			

    			//if the insert has returned true then we show the flash message
    			if ($store_id = $this->Store_model->insert($this->tablename, $store)) {

    				

    				$this->session->set_flashdata('message_type', 'success');
    				$this->session->set_flashdata('message', '<strong>Well done!</strong> Store have been added successfully.');
    			} else {

    				$this->session->set_flashdata('message_type', 'danger');
    				$this->session->set_flashdata('message', '<strong>Oh snap!</strong>  already exists.');
    			}
    			redirect('/App/store');
    		} //validation run
    	}

		//get sales rep
        $data['sales_rep']=$this->Store_model->get_salesRepresentative_list();
        
        //get zone
        $data['zone']=$this->Store_model->get_zone_list();
        
    	$data['page'] = 'store';
    	$data['page_title'] = SITE_NAME.' :: Store Management &raquo; Add Store';

    	$data['main_content'] = 'store/add';
    	$this->load->view(TEMPLATE_PATH, $data);
    }


	/**
	 *
	 * @param unknown_type $id
	 */
	public function edit($id = 0) {
        //phpinfo();die;
		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

		if ($id === 0) {
			$id = $this->session->userdata('id');
		}

		// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    	$config = $this->config->item('module_config');

    	//$data['user_meta'] = $config['stores']['meta'];

     	//if save button was clicked, get the data sent via post
     	if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
     		//form validation
     		
			$this->form_validation->set_rules('name', 'Store Name', 'trim|required');
            $this->form_validation->set_rules('adress', 'Store Adress', 'trim|required');
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('state', 'State', 'trim|required');
            $this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|required');
            //$this->form_validation->set_rules('phone', 'Phone', 'trim|required');

            //$this->form_validation->set_rules('store_manager', 'Manager', 'trim|required');
            $this->form_validation->set_rules('sales_rep[]', 'Sales Representative', 'trim|required');
           // $this->form_validation->set_rules('account_number', 'Account Number', 'trim|required');
           // $this->form_validation->set_rules('special_request', 'Request', 'trim|required');
            $this->form_validation->set_rules('zone', 'Zone', 'trim|required');
            $this->form_validation->set_rules('wine_sell_type[]', 'Wine sell type', 'trim|required');

     		

     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
     		//if the form has passed through the validation

     		if ($this->form_validation->run())
     		{
                $wine_sell_type_array=$this->input->post('wine_sell_type[]');
                $wine_sell_type='';
                foreach($wine_sell_type_array as $val)
                {
                    $wine_sell_type.=$val.'/';
                }
                $wine_sell_type=rtrim($wine_sell_type,'/');
                $address = $this->input->post('adress').' '.$this->input->post('city').' '.$this->input->post('zipcode').' '.$this->input->post('state');
               $result = $this->geocode->get_lat_long($address);
               //echo "<pre>";
               //print_r($result);die;

               if ($result['status']=='OK') {
                   $lat = $result['results']['0']['geometry']['location']['lat'];
                   $long = $result['results']['0']['geometry']['location']['lng'];
               } else { 
                   $lat =0;
                   $long = 0;   
               }
               $sales_rep_array=$this->input->post('sales_rep');
               $string='';
               foreach($sales_rep_array as $val)
                {
                    $string.=$val.",";
                }
                $sales_rep=rtrim($string,",");
                // Upload store logo
                    $pics = $_FILES['pics'];
                    

                    if (!empty($pics)) {

                        // Update Product Image
                        $config['upload_path'] = DIR_STORE_LOGO;
                        $config['max_size'] = '10000';
                        $config['allowed_types'] = 'jpg|png|jpeg';
                        $config['overwrite'] = FALSE;
                        $config['remove_spaces'] = TRUE;

                        $this->load->library('upload', $config);

                        $images = array();

                        

                            $_FILES['images']['name']= $pics['name'];
                            $_FILES['images']['type']= $pics['type'];
                            $_FILES['images']['tmp_name'] = $pics['tmp_name'];
                            $_FILES['images']['error']= $pics['error'];
                            $_FILES['images']['size']= $pics['size'];

                            $config['file_name'] = 'store-'.rand().date('YmdHis');
                            $images = $config['file_name'];

                            
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('images')) {

                                $config_thumb['image_library'] = 'gd2';
                                $config_thumb['source_image'] = DIR_STORE_LOGO.$this->upload->file_name;
                                $config_thumb['create_thumb'] = FALSE;
                                $config_thumb['maintain_ratio'] = TRUE;
                                $config_thumb['master_dim'] = 'auto';
                                $config_thumb['width'] = STORE_LOGO_SIZE; // image re-size  properties
                                $config_thumb['height'] = STORE_LOGO_SIZE; // image re-size  properties
                                $config_thumb['new_image'] = DIR_STORE_LOGO_THUMB.$this->upload->file_name; // image re-size  properties

                                $this->load->library('image_lib', $config_thumb); //codeigniter default function

                                $this->image_lib->initialize($config_thumb);
                                if (!$this->image_lib->resize()) {
                                     echo $this->image_lib->display_errors();
                                }
                                $this->image_lib->clear();

                                $upload_data =  $this->upload->data();
                                $uploaded_pics = array();
                                $uploaded_pics = $upload_data['file_name'];

                                

                            } else {
                                $this->upload->display_errors(); 
                                
                            }
                        
                    }
                
     			$store = array(
                    'name' => htmlspecialchars($this->input->post('name'), ENT_QUOTES, 'utf-8'),
                    'adress' => htmlspecialchars($this->input->post('adress'), ENT_QUOTES, 'utf-8'),
                    'suite_number' => htmlspecialchars($this->input->post('suite_number'), ENT_QUOTES, 'utf-8'),
                    'appartment_number' => htmlspecialchars($this->input->post('appartment_number'), ENT_QUOTES,'utf-8'), 
                    'city' => htmlspecialchars($this->input->post('city'), ENT_QUOTES, 'utf-8'),
                    'state' => htmlspecialchars($this->input->post('state'), ENT_QUOTES, 'utf-8'),
                    'zipcode' => htmlspecialchars($this->input->post('zipcode'), ENT_QUOTES, 'utf-8'),
                    'phone' => htmlspecialchars($this->input->post('phone'), ENT_QUOTES, 'utf-8'),
                    'store_manager' => htmlspecialchars($this->input->post('store_manager'), ENT_QUOTES, 'utf-8'),
                    'account_number' => htmlspecialchars($this->input->post('account_number'), ENT_QUOTES, 'utf-8'),
                    'special_request' => htmlspecialchars($this->input->post('special_request'), ENT_QUOTES, 'utf-8'),
                    'zone' => htmlspecialchars($this->input->post('zone'), ENT_QUOTES, 'utf-8'),
                    'sales_rep'=>$sales_rep,
                    'latitude'=>$lat,
                    'longitude'=>$long,
                    'wine_sell_type' => htmlspecialchars($wine_sell_type, ENT_QUOTES, 'utf-8'),
                    'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
                    
                    'updated_by' => $this->session->userdata('id'),
                    'updated_on' => date('Y-m-d H:i:s')
                );
    			
                //echo $uploaded_pics;die;
     			//if the insert has returned true then we show the flash message
     			if ($this->Store_model->update($this->tablename, 'id', $id, $store)) {
                    if($uploaded_pics!='')
                    {

     				   $logo_array=array('logo'=>$uploaded_pics);
                      
                       $this->Store_model->update($this->tablename, 'id', $id, $logo_array);
                    }

     				$this->session->set_flashdata('message_type', 'success');
     				if ($this->input->post('ref') == 'profile') {
     					$this->session->set_flashdata('message', '<strong>Well done!</strong> Profile successfully updated.');
     				} else {
     					$this->session->set_flashdata('message', '<strong>Well done!</strong> Store successfully updated.');
     				}
     			} else{
     				$this->session->set_flashdata('message_type', 'danger');
     				$this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
     			}

     			// If from profile page - redirect there
     			if ($this->input->post('ref') == 'profile') {
     				redirect('/profile');
     			}

     			redirect('/App/Store/edit/'.$id);
                //redirect($this->agent->referrer());
     		} //validation run
     	}

     	$data['store']  = $this->Store_model->get_store_details($id);
     	//echo "<pre>";
     	//print_r($data['store']);die;

     	if (!is_numeric($id) || $id == 0 || empty($data['store'])) {
     		redirect('/App/store');
     	}
        //get sales rep
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('users.status','active');
        $this->db->where('users.user_type','sales_rep');
        $tester = $this->db->get();
        $data['sales_rep']=$tester->result();


        //get zone
        $this->db->select('*');
        $this->db->from('zone');
        $this->db->where('zone.status','active');
        $this->db->where('zone.is_deleted',0);
        $zone = $this->db->get();
        $data['zone']=$zone->result();
		
     	$data['page'] = 'store';
    	$data['page_title'] = SITE_NAME.' :: Store Management &raquo; Edit store';

    	$data['main_content'] = 'store/edit';
    	$this->load->view(TEMPLATE_PATH, $data);
    }
    public function update_status() {

    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
    		//form validation
    		$this->form_validation->set_rules('operation', 'Operation', 'required');
    		$this->form_validation->set_rules('item_id[]', 'User', 'trim|required');

    		$this->form_validation->set_error_delimiters('', '');

    		//if the form has passed through the validation
    		if ($this->form_validation->run())
    		{
    			//print "<pre>"; print_r($_POST);die;
    			$count = 0;
    			$items = $this->input->post('item_id');
                
    			$operation = $this->input->post('operation');
                if($operation=='export')
                {
                    $items_to_export = $this->input->post('item_id[]');
                    //echo "<pre>";
                    //print_r($items_to_export);die;
                    $filename = 'report_export_store_'.date('m-d-Y-His').'.csv';
        
                    header("Content-Description: File Transfer");

                    header("Content-Disposition: attachment; filename=$filename");

                    header("Content-Type: application/csv; ");

                    
                    $data['students'] =  $this->Store_model->get_store_list_for_csv($items_to_export);
                    
                     $file = fopen('php://output', 'w');
                    

                    $delimiter = ',';
                    if(!empty($data['students'][0]))
                    {
                        $header = array_keys($data['students'][0]);
                        //$header[0]='SL No';
                        
                        
                        fputcsv($file, $header,$delimiter);

                        foreach ($data['students'] as $key=>$line){
                        fputcsv($file,$line);
                        }
                    }
                    fclose($file);

                    exit;
                }
                else
                {
                    $data_to_store = array(
                        'status' => ($operation == "active")?'active':'inactive'
                    );

                    foreach ($items as $id=>$value) {

                        // Restrict to update yourself
                        /*if ($id == $this->session->userdata('id')) {
                            continue;
                        }*/

                        if ($operation == 'delete') {
                            if ($this->Store_model->delete($this->tablename, $id)) {
                                $count++;
                            }
                        } else {

                            if ($this->Store_model->update($this->tablename, 'id', $id, $data_to_store)) {
                                $count++;
                            }
                        }
                    }

                    $msg = ($operation=='delete')?'deleted.':'updated.';

                    $this->session->set_flashdata('message_type', 'success');
                    $this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' Stores(s) successfully '.$msg);
                }

    		} else {
    			$this->session->set_flashdata('message_type', 'danger');
    			$this->session->set_flashdata('message', validation_errors());
    		}
    		redirect('/App/store');
    	}
    }
    public function import()
    {
        //echo 2;die;
        $data = array();
        $memData = array();
        
        // If import request is submitted
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //echo "I am here";die;
            $import=$this->input->post('importSubmit');
            if($import){
            
            //echo 1;die;
            // Form field validation rules
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');
            
            // Validate submitted form data
            if($this->form_validation->run() == true){
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;
                
                // If file uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    //echo $this->upload->display_errors();die;
                    // Load CSV reader library
                    $this->load->library('CSVReader');
                    
                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);
                    //echo "<pre>";
                    //print_r($csvData);die;
                    
                    // Insert/update CSV data into database
                    if(!empty($csvData)){
                        foreach($csvData as $row){ $rowCount++;
                            
                            // Prepare data for DB insertion
                            $memData = array(
                                'id' => $row[' id'],
                                'name' => $row['name'],
                                'adress' => $row['adress'],
                                'suite_number' => $row['suite_number'],
                                'appartment_number' => $row['appartment_number'],
                                'city' => $row['city'],
                                'state' => $row['state'],
                                'zipcode' => $row['zipcode'],
                                'phone' => $row['phone'],
                                'account_number' => $row['account_number'],
                                'special_request' => $row['special_request']
                                
                            );
                            
                            // Check whether email already exists in the database
                            $con = array(
                                'where' => array(
                                    'id' => $row[' id']
                                ),
                                'returnType' => 'count'
                            );
                            $prevCount = $this->Store_model->getRows($con);
                            
                            if($prevCount > 0){
                                // Update member data
                                $condition = array('id' => $row[' id']);
                                $update = $this->Store_model->update_data($memData, $condition);
                                
                                if($update){
                                    $updateCount++;
                                }
                            }else{
                                // Insert member data
                                $insert = $this->Store_model->insert_data($memData);
                                
                                if($insert){
                                    $insertCount++;
                                }
                            }
                        }
                        
                        // Status message with imported data count
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Store imported successfully. Total Rows ('.$rowCount.') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.')';
                        //$this->session->set_userdata('success_msg', $successMsg);
                        $this->session->set_flashdata('message_type', 'success');
                        $this->session->set_flashdata('message', $successMsg);
                    }
                }else{
                    echo $this->upload->display_errors();die;
                    $this->session->set_flashdata('message_type', 'danger');
                    //$this->session->set_userdata('error_msg', 'Error on file upload, please try again.');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Error on file upload, please try again.');
                }
            }else{
                $this->session->set_flashdata('message_type', 'danger');
                //$this->session->set_userdata('error_msg', 'Invalid file, please select only CSV file.');
                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Invalid file, please select only CSV file.');
            }
        }
            redirect('App/store');
        }
    }
    public function file_check($str){
        $allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ""){
            $mime = get_mime_by_extension($_FILES['file']['name']);
            $fileAr = explode('.', $_FILES['file']['name']);
            $ext = end($fileAr);
            if(($ext == 'csv') && in_array($mime, $allowed_mime_types)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only CSV file to upload.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'Please select a CSV file to upload.');
            return false;
        }
    }
    public function profile() {

		$id = $this->session->userdata('id');

		// Include the Module JS file.
    	add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    	$config = $this->config->item('module_config');

    	$data['user_meta'] = $config['stores']['meta'];

     	$data['user']  = $this->Admin_model->get_user_details($id);

     	if (!is_numeric($id) || $id == 0 || empty($data['user'])) {
     		redirect('/dashboard');
     	}

		// Roles List (for dropdown)
    	$data['roles'] = $this->Admin_model->get_roles_list();

     	$data['page'] = 'profile';
    	$data['page_title'] = SITE_NAME.' :: Update Profile';

    	$data['main_content'] = 'stores/profile';
    	$this->load->view(TEMPLATE_PATH, $data);
    }
	/**
     *
     * @param int $id
     */
    public function delete($id = null) {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Can't delete yourself
    	if ($id == $this->session->userdata('id')) {
    		$this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');

            redirect('/App/store');
    	}

    	$data['info'] = $this->Store_model->get_store_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/App/store');
    	}

      	if ($this->Store_model->delete($this->tablename, $id)) {
            $this->session->set_flashdata('message_type', 'success');
    		$this->session->set_flashdata('message', '<strong>Well done!</strong> Store successfully deleted.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
    	redirect('/App/store');
    }
     public function temp_delete($id = null) {

		// Permission Checking
		//parent::checkMethodPermission($this->permissionValues[$this->router->method]);

    	// Can't delete yourself
    	if ($id == $this->session->userdata('id')) {
    		$this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');

            redirect('/App/store');
    	}

    	$data['info'] = $this->Store_model->get_store_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/App/store');
    	}
         $data=array(
                'is_deleted'=>1
            );
      	if ($this->Store_model->update($this->tablename,'id',$id,$data)) {
            $this->session->set_flashdata('message_type', 'success');
    		$this->session->set_flashdata('message', '<strong>Well done!</strong> Store has been deleted successfully.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
    	redirect('/App/store');
    }


	/**
     *
     */
    

	
	
	function reset_pass($id = null) {

		// Permission Checking
		parent::checkMethodPermission($this->permissionValues[$this->router->method]);

		$data['info'] = $this->Store_model->get_store_details($id);

    	if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
    		redirect('/app/store');
    	}

		$password = $this->random_string();
		$user['password'] = md5($password);

		//if the insert has returned true then we show the flash message
		if ($this->Store_model->update($this->tablename, 'id', $id, $user)) {

			$name = $data['info']->first_name . " " . $data['info']->last_name;
			$email = $data['info']->email;

			// Send Email to stores
			//$this->load->library('mail_template');
			//$this->mail_template->new_password_email($name, $email, $password);

			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', '<strong>Well done!</strong> Password successfully updated and emailed to sales representative.');
		} else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
    	redirect('/app/store');
	}
	
	public function search_submit() {

    	if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
    		//$name = $this->clean_value($this->input->post('name'));
            $name = $this->input->post('name');
            

			$url = "App/store/index/";

            if ($name != '') {
                $url .= "name/". urlencode($name)."/";
            }

            if ($size_id != '') {
                $url .= "size_id/". urlencode($size_id)."/";
            }
            redirect($url);

			
    	}
    }

	public function get_search_options() {

		if ($this->input->server('REQUEST_METHOD') === 'POST')
    	{
    		//
    		$field = $this->input->post('field');
			$ope = $this->input->post('ope');
			$q = $this->input->post('q');

			switch($field) {
				case 'name':
				case 'email':
				case 'phone':
				case 'social_id':
				case 'latest_version':
					$data['search_field'] = '<input type="text" class="form-control" id="inputSearch" name="q" placeholder="Search here" value="'.$q.'" required="">';

					$data['search_ope'] = '<select name="operator" id="inputOperator" class="form-control" required >
	  						<option value="" selected>Select an operator</option>
	  						<option value="contains" ';
							if ($ope == 'contains') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= ' >Contains</option>';
							$data['search_ope'] .= '<option value="equals" ';
							if ($ope == 'equals') {
								$data['search_ope'] .= ' selected ';
							}
	  						$data['search_ope'] .= ' >Equals</option>';
	  						$data['search_ope'] .= '<option value="notequal" ';
							if ($ope == 'notequal') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= '>Doesn\'t Equal</option>
	  					</select>';
				break;

				case 'dob':
				case 'created_on':
				case 'last_opened':

					$q = str_replace('~', '/', $q);

					$data['search_field'] = '<div class="input-group">
      						<input type="text" name="q" class="form-control calender-control" id="inputSearch" placeholder="Search here" value="'.$q.'" required="" >
      						<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
    					  </div>';

					$data['search_ope'] = '<select name="operator" id="inputOperator" class="form-control" required >
	  						<option value="" selected>Select an operator</option>
							<option value="before" ';
							if ($ope == 'before') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= ' >Before</option>';
							$data['search_ope'] .= '<option value="after" ';
							if ($ope == 'after') {
								$data['search_ope'] .= ' selected ';
							}
	  						$data['search_ope'] .= ' >After</option>';
	  						$data['search_ope'] .= '<option value="between" ';
							if ($ope == 'between') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= '>Between</option>
	  					</select>';

				break;

				case 'gender':
					$data['search_field'] = '<select name="q" class="form-control" id="inputSearch">
						<option value="" selected>- Select a gender -</option>
						<option value="M" ';
						if ($q == 'M') {
							$data['search_field'] .= ' selected ';
						}
						$data['search_field'] .= ' >Male</option>';
						$data['search_field'] .= '<option value="F" ';
						if ($q == 'F') {
							$data['search_ope'] .= ' selected ';
						}
						$data['search_field'] .= '>Female</option>
					</select>';

					$data['search_ope'] = '<select name="operator" id="inputOperator" class="form-control" required >
	  						<option value="" selected>Select an operator</option>
							<option value="equals" ';
							if ($ope == 'equals') {
								$data['search_ope'] .= ' selected ';
							}
	  						$data['search_ope'] .= ' >Equals</option>';
	  						$data['search_ope'] .= '<option value="notequal" ';
							if ($ope == 'notequal') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= '>Doesn\'t Equal</option>
	  					</select>';
				break;

				case 'status':
					$data['search_field'] = '<select name="q" class="form-control" id="inputSearch">
						<option value="" selected>- Select a status -</option>
						<option value="active" ';
						if ($q == 'active') {
							$data['search_field'] .= ' selected ';
						}
						$data['search_field'] .= ' >Active</option>';
						$data['search_field'] .= '<option value="inactive" ';
						if ($q == 'inactive') {
							$data['search_ope'] .= ' selected ';
						}
						$data['search_field'] .= '>Inactive</option>
					</select>';

					$data['search_ope'] = '<select name="operator" id="inputOperator" class="form-control" required >
	  						<option value="" selected>Select an operator</option>
							<option value="equals" ';
							if ($ope == 'equals') {
								$data['search_ope'] .= ' selected ';
							}
	  						$data['search_ope'] .= ' >Equals</option>';
	  						$data['search_ope'] .= '<option value="notequal" ';
							if ($ope == 'notequal') {
								$data['search_ope'] .= ' selected ';
							}
							$data['search_ope'] .= '>Doesn\'t Equal</option>
	  					</select>';
				break;
			}

			$this->output
	        	->set_content_type('application/json')
	        	->set_output(json_encode($data));
		}
	}



    /**
     * Clean up by removing unwanted characters
     *
     * @param unknown_type $str
     */
    private function clean_value($str) {

		$str = str_replace('/', '~', $str);
		return preg_replace('/[^A-Za-z0-9_\-~]/', '', $str);
    }

	/**
     *
     * @param unknown_type $uri
     * @param unknown_type $total_rows
     * @param unknown_type $segment
     */
    private function init_pagination($uri,$segment=4,$total_rows,$view=NULL) {

    	$this->config->load('pagination');
    	$this->load->library('pagination');

    	$config = $this->config->item('pagination');

       	$ci                          =& get_instance();
       	$config['uri_segment']       = $segment;
       	$config['base_url']          = base_url().$uri;
       	$config['total_rows']        = $total_rows;
         $config['per_page']        = $view;
       	$ci->pagination->initialize($config);
       	return $config;
   }

   private function format_date($date) {
	   if ($date == "")
	   	return "";

	   $newdate = date_create($date);
	   return date_format($newdate,"Y-m-d");
   }
   
   
	private function redirectToURL() {
		
		// Get the reference URL
		$this->reference_url = $this->input->get('ref');
		
		if ($this->reference_url <> "") {
			redirect($this->reference_url);
		} else {
			redirect($this->url);
		}
	}
	
	/**
	 * @param int $limit
	 */
   	private function random_string($limit = 10) {

	    $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789!@#$%^&*()'); // and any other characters
	    shuffle($seed); // probably optional since array_is randomized; this may be redundant
		$rand = '';
		foreach (array_rand($seed, $limit) as $k) $rand .= $seed[$k];

		return $rand;
	}
}
