<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wine extends Application_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */

     /**
     *
     * @var unknown_type
     */
    private $tablename = 'wine';
    private $url = '/App/wine';
    private $permissionValues = array(

        'index' => 'App.Wine.View',
        'add' => 'App.Wine.Add',
        'edit' => 'App.Wine.Edit',
        'delete' => 'App.Wine.Delete',
        'update_status' => 'App.Wine.UpdateStatus',
        'images' => 'App.Wine.Images',

    );

    public function __construct() {

        parent::__construct();

        // Validate Login
        parent::checkLoggedin();

        $this->session->set_userdata('page_data', array('url' => $this->url, 'permissions' => $this->permissionValues));
        $this->load->model('Wine_model');
        $this->load->library('user_agent');
        $this->load->library('CSVReader');
        $this->load->helper('file');
        $this->load->helper('template');
    }
    

    public function index() {

        // Permission Checking
       // echo "<pre>";print_r($this->permissionValues);die;
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);

        // Include the Module JS file.
        add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');

        // Set the filters
        $filter = array('is_deleted' => 0);

        $default_uri = array('name','sampling_date','sampling_status','bottles' ,'page','upc_code','view');
        $uri = $this->uri->uri_to_assoc(4, $default_uri);
        
        $pegination_uri = array();

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
            $pegination_uri['name'] = " ";
        }
        if (isset($uri['upc_code']) && trim(urldecode($uri['upc_code'])) <> "") {
            $filter['upc_code'] = $uri['upc_code'];
            $pegination_uri['upc_code'] = $uri['upc_code'];
        } else {
            $filter['upc_code'] = "";
            $pegination_uri['upc_code'] = " ";
        }
        if (isset($uri['sampling_date']) && trim(urldecode($uri['sampling_date'])) <> "") {
            $filter['sampling_date'] = $uri['sampling_date'];
            $pegination_uri['sampling_date'] = $uri['sampling_date'];
        } else {
            $filter['sampling_date'] = "";
            $pegination_uri['sampling_date'] = " ";
        }
        if (isset($uri['sampling_status']) && trim(urldecode($uri['sampling_status'])) <> "") {
            $filter['sampling_status'] = $uri['sampling_status'];
            $pegination_uri['sampling_status'] = $uri['sampling_status'];
        } else {
            $filter['sampling_status'] = "";
            $pegination_uri['sampling_status'] = " ";
        }
        if (isset($uri['bottles']) && trim(urldecode($uri['bottles'])) <> "") {
            $filter['bottles'] = $uri['bottles'];
            $pegination_uri['bottles'] = $uri['bottles'];
        } else {
            $filter['bottles'] = "";
            $pegination_uri['bottles'] = " ";
        }
        

        if (isset($uri['page']) && $uri['page'] > 0) {
            $page = $uri['page'];
        } else {
            $page = 0;
        }

        // Get the total rows without limit
        $total_rows = $this->Wine_model->get_wine_list($filter, null, null, true);
        //echo $total_rows;die;
        /*$config = $this->init_pagination($this->url.'/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/',17, $total_rows,$filter['view']);

        //$limit_end = ($page * $config['per_page']) - $config['per_page'];
        $limit_end = ($page * $filter['view']) - $filter['view'];
        if ($limit_end < 0){
            $limit_end = 0;
        }

        //$filter['limit'] = $config['per_page'];
        $filter['limit'] = $filter['view'];
        $filter['offset'] = $limit_end;*/

        // Get the size List
        
        

        // Get the Wine List
        $data['list'] = $this->Wine_model->get_wine_list($filter, 'created_on', 'desc');
        //echo count($data['list']);die;
        //print "<pre>"; print_r($data['list']); die;
        //print "<pre>"; print_r($data); die;
        $data['filter'] = $filter;
        $data['page'] = 'Wine';
        $data['page_title'] = SITE_NAME.' :: Wine Management';

        $data['main_content'] = 'wine/list';
        $this->load->view(TEMPLATE_PATH, $data);
    }


    /**
     *
     */


    public function add() {

        // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);

        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            
            //form validation
            $this->form_validation->set_rules('upc_code', 'UPC Code', 'trim|required|callback_check_duplicate_upc[upc_code]');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            //$this->form_validation->set_rules('brand', 'Brand', 'trim|required');
            //$this->form_validation->set_rules('year', 'Year', 'trim|required|numeric');
            $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
            //$this->form_validation->set_rules('type', 'Type', 'trim|required');
            //$this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('size', 'Size', 'trim|required|numeric');
            $this->form_validation->set_rules('flavour[]', 'Company', 'trim|required');
            // $this->form_validation->set_rules('c_type[]', 'Company type', 'trim|required');
            $this->form_validation->set_message('check_duplicate_upc', 'UPC Code already exists.Please try with different UPC Code');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            //if the form has passed through the validation

            if ($this->form_validation->run())
            {
                
                $flavour=$this->input->post('flavour');
                $company_type_array=$this->input->post('c_type[]');
                $c_type='';
                foreach($company_type_array as $val)
                {
                    $c_type.=$val.',';
                }
                $c_type=rtrim($c_type,',');
                
                $data = array(
                    'upc_code' => htmlspecialchars(addslashes($this->input->post('upc_code')), ENT_QUOTES, 'utf-8'),
                    'name' => htmlspecialchars($this->input->post('name'), ENT_QUOTES, 'utf-8'),
                    'brand' => $this->input->post('brand'),
                    'year' => htmlspecialchars($this->input->post('year'), ENT_QUOTES, 'utf-8'),
                    'category_id' => htmlspecialchars($this->input->post('category_id'), ENT_QUOTES, 'utf-8'),
                    'type' => htmlspecialchars($this->input->post('type'), ENT_QUOTES, 'utf-8'),
                    'UOM' => $this->input->post('uom'),
                    'description' => $this->input->post('description'),
                    'size' => htmlspecialchars($this->input->post('size'), ENT_QUOTES, 'utf-8'),
                    'flavour' => htmlspecialchars($flavour, ENT_QUOTES, 'utf-8'),
                    // 'company_type' => htmlspecialchars($c_type, ENT_QUOTES, 'utf-8'),
                    'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
                    'created_by' => $this->session->userdata('id'),
                    'created_on' => date('Y-m-d H:i:s')
                );
               
                        // Upload product Image
                        $pics = $_FILES['pics'];

                        if (!empty($pics['name'][0])) {

                            // Update Product Image
                            $config['upload_path'] = DIR_WINE_PICTURE;
                            $config['max_size'] = '8000';
                            $config['allowed_types'] = 'jpg|png|jpeg';
                            $config['overwrite'] = FALSE;
                            $config['remove_spaces'] = TRUE;

                            $this->load->library('upload', $config);

                            $images = array();

                            foreach ($pics['name'] as $key => $image) {

                                $_FILES['images[]']['name']= $pics['name'][$key];
                                $_FILES['images[]']['type']= $pics['type'][$key];
                                $_FILES['images[]']['tmp_name'] = $pics['tmp_name'][$key];
                                $_FILES['images[]']['error']= $pics['error'][$key];
                                $_FILES['images[]']['size']= $pics['size'][$key];

                                $config['file_name'] = 'wine-'.rand().date('YmdHis');
                                $images[] = $config['file_name'];


                                $this->upload->initialize($config);

                                if ($this->upload->do_upload('images[]')) {

                                    if ($wine_id = $this->Wine_model->insert($this->tablename, $data)) {

                                        $config_thumb['image_library'] = 'gd2';
                                        $config_thumb['source_image'] = DIR_WINE_PICTURE.$this->upload->file_name;
                                        $config_thumb['create_thumb'] = FALSE;
                                        $config_thumb['maintain_ratio'] = TRUE;
                                        $config_thumb['master_dim'] = 'auto';
                                        $config_thumb['width'] = WINE_PICTURE_THUMB_SIZE; // image re-size  properties
                                        $config_thumb['height'] = WINE_PICTURE_THUMB_SIZE; // image re-size  properties
                                        $config_thumb['new_image'] = DIR_WINE_PICTURE_THUMB.$this->upload->file_name; // image re-size  properties
    
                                        $this->load->library('image_lib', $config_thumb); //codeigniter default function
    
                                        $this->image_lib->initialize($config_thumb);
                                        if (!$this->image_lib->resize()) {
                                             echo $this->image_lib->display_errors();
                                        }
                                        $this->image_lib->clear();
    
                                        $upload_data =  $this->upload->data();
                                        $uploaded_pics = array();
                                        $uploaded_pics[] = $upload_data['file_name'];
    
                                        // Update database here
                                        $this->Wine_model->insert_product_pic($wine_id, $uploaded_pics);

                                        $this->session->set_flashdata('message_type', 'success');
                                        $this->session->set_flashdata('message', '<strong>Well done!</strong> Product has been added successfully.');
                                    }else{
                                        $this->session->set_flashdata('message_type', 'danger');
                                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Please try again.');
                                    }
                                    
                                } else {
                                   
                                    $this->session->set_flashdata('message_type', 'danger');
                                    $this->session->set_flashdata('message', $this->upload->display_errors());
                                    redirect('/App/wine/add');
                                   
                                }
                            }
                        }
                
                    redirect($this->url);
            } 
        }

        $filter = array('deleted' => 0, 'status' => 'active');
        $search['parent_id'] = '0';
        $search['deleted'] = 0;
        $search['tag'] = "&ndash;";
        $this->load->model('Category_model');
        $data['list'] = $this->Category_model->get_category_list($search);
        
        $data['page'] = 'Wine';
        $data['page_title'] = SITE_NAME.' :: Wine Management &raquo; Add Wine';

        $data['main_content'] = 'wine/add';
        $this->load->view(TEMPLATE_PATH, $data);
        
    }


     /*
    public function add() {

        // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);

        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            
            //form validation
            $this->form_validation->set_rules('upc_code', 'UPC Code', 'trim|required|callback_check_duplicate_upc[upc_code]');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            //$this->form_validation->set_rules('brand', 'Brand', 'trim|required');
            //$this->form_validation->set_rules('year', 'Year', 'trim|required|numeric');
            $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
            //$this->form_validation->set_rules('type', 'Type', 'trim|required');
            //$this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('size', 'Size', 'trim|required|numeric');
            $this->form_validation->set_rules('flavour[]', 'Company', 'trim|required');
            $this->form_validation->set_rules('c_type[]', 'Company type', 'trim|required');
            $this->form_validation->set_message('check_duplicate_upc', 'UPC Code already exists.Please try with different UPC Code');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            //if the form has passed through the validation

            if ($this->form_validation->run())
            {
               
                $flavour=$this->input->post('flavour');
                $company_type_array=$this->input->post('c_type[]');
                $c_type='';
                foreach($company_type_array as $val)
                {
                    $c_type.=$val.',';
                }
                $c_type=rtrim($c_type,',');
                
                $data = array(
                    'upc_code' => htmlspecialchars(addslashes($this->input->post('upc_code')), ENT_QUOTES, 'utf-8'),
                    'name' => htmlspecialchars($this->input->post('name'), ENT_QUOTES, 'utf-8'),
                    'brand' => $this->input->post('brand'),
                    'year' => htmlspecialchars($this->input->post('year'), ENT_QUOTES, 'utf-8'),
                    'category_id' => htmlspecialchars($this->input->post('category_id'), ENT_QUOTES, 'utf-8'),
                    'type' => htmlspecialchars($this->input->post('type'), ENT_QUOTES, 'utf-8'),
                    'UOM' => $this->input->post('uom'),
                    'description' => $this->input->post('description'),
                    'size' => htmlspecialchars($this->input->post('size'), ENT_QUOTES, 'utf-8'),
                    'flavour' => htmlspecialchars($flavour, ENT_QUOTES, 'utf-8'),
                    'company_type' => htmlspecialchars($c_type, ENT_QUOTES, 'utf-8'),
                    'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
                    'created_by' => $this->session->userdata('id'),
                    'created_on' => date('Y-m-d H:i:s')
                );
                //Check unique upc code for wine
                
                    //if the insert has returned true then we show the flash message
                    if ($wine_id = $this->Wine_model->insert($this->tablename, $data)) {



                        // Upload product Image
                        $pics = $_FILES['pics'];

                        if (!empty($pics['name'][0])) {

                            // Update Product Image
                            $config['upload_path'] = DIR_WINE_PICTURE;
                            $config['max_size'] = '8000';
                            $config['allowed_types'] = 'jpg|png|jpeg';
                            $config['overwrite'] = FALSE;
                            $config['remove_spaces'] = TRUE;

                            $this->load->library('upload', $config);

                            $images = array();

                            foreach ($pics['name'] as $key => $image) {

                                $_FILES['images[]']['name']= $pics['name'][$key];
                                $_FILES['images[]']['type']= $pics['type'][$key];
                                $_FILES['images[]']['tmp_name'] = $pics['tmp_name'][$key];
                                $_FILES['images[]']['error']= $pics['error'][$key];
                                $_FILES['images[]']['size']= $pics['size'][$key];

                                $config['file_name'] = 'wine-'.rand().date('YmdHis');
                                $images[] = $config['file_name'];


                                $this->upload->initialize($config);

                                if ($this->upload->do_upload('images[]')) {

                                    $config_thumb['image_library'] = 'gd2';
                                    $config_thumb['source_image'] = DIR_WINE_PICTURE.$this->upload->file_name;
                                    $config_thumb['create_thumb'] = FALSE;
                                    $config_thumb['maintain_ratio'] = TRUE;
                                    $config_thumb['master_dim'] = 'auto';
                                    $config_thumb['width'] = WINE_PICTURE_THUMB_SIZE; // image re-size  properties
                                    $config_thumb['height'] = WINE_PICTURE_THUMB_SIZE; // image re-size  properties
                                    $config_thumb['new_image'] = DIR_WINE_PICTURE_THUMB.$this->upload->file_name; // image re-size  properties

                                    $this->load->library('image_lib', $config_thumb); //codeigniter default function

                                    $this->image_lib->initialize($config_thumb);
                                    if (!$this->image_lib->resize()) {
                                         echo $this->image_lib->display_errors();
                                    }
                                    $this->image_lib->clear();

                                    $upload_data =  $this->upload->data();
                                    $uploaded_pics = array();
                                    $uploaded_pics[] = $upload_data['file_name'];

                                    // Update database here
                                    $this->Wine_model->insert_product_pic($wine_id, $uploaded_pics);

                                } else {
                                    $this->upload->display_errors(); die;
                                }
                            }
                        }

                        $this->session->set_flashdata('message_type', 'success');
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> Product has been added successfully.');
                    } else {
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Please try again.');
                    }
                
                    redirect($this->url);
                //}
            } //validation run
        }

        $filter = array('deleted' => 0, 'status' => 'active');
        $search['parent_id'] = '0';
        $search['deleted'] = 0;
        $search['tag'] = "&ndash;";
        $this->load->model('Category_model');
        $data['list'] = $this->Category_model->get_category_list($search);
        //print "<pre>"; print_r($data); die;

        $data['page'] = 'Wine';
        $data['page_title'] = SITE_NAME.' :: Wine Management &raquo; Add Wine';

        $data['main_content'] = 'wine/add';
        $this->load->view(TEMPLATE_PATH, $data);
    }*/



    // My callback function
    public function check_duplicate_upc($upc_code) {

        return $this->Wine_model->checkDuplicateUpccode($upc_code);

    }

    /**
     *
     * @param unknown_type $id
     */
    public function edit($id = 0) {

        // Permission Checking

        parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        $is_deleted = check_is_deleted('wine',$id);
        if($is_deleted==false)
        {
          redirect($this->url);
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //echo "<pre>";
            //print_r($this->input->post());die;
            //form validation
            //form validation
            $this->form_validation->set_rules('upc_code', 'UPC Code', 'trim|required');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            //$this->form_validation->set_rules('brand', 'Brand', 'trim|required');
            //$this->form_validation->set_rules('year', 'Year', 'trim|required|numeric');
            $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
            //$this->form_validation->set_rules('type', 'Type', 'trim|required');
            //$this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('size', 'Size', 'trim|required|numeric');
            $this->form_validation->set_rules('flavour[]', 'Company', 'trim|required');
            // $this->form_validation->set_rules('c_type[]', 'Company type', 'trim|required');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            //if the form has passed through the validation

            if ($this->form_validation->run())
            {
                /*$flavour_array=$this->input->post('flavour[]');
                $flavour='';
                foreach($flavour_array as $val)
                {
                    $flavour.=$val.',';
                }
                $flavour=rtrim($flavour,',');*/
                $flavour=$this->input->post('flavour');

                /*
                $company_type_array=$this->input->post('c_type[]');
                $c_type='';
                foreach($company_type_array as $val)
                {
                    $c_type.=$val.',';
                }
                $c_type=rtrim($c_type,',');*/

                $data = array(
                    'upc_code' => htmlspecialchars($this->input->post('upc_code'), ENT_QUOTES, 'utf-8'),
                    'name' => htmlspecialchars($this->input->post('name'), ENT_QUOTES, 'utf-8'),
                    'brand' => $this->input->post('brand'),
                    'year' => htmlspecialchars($this->input->post('year'), ENT_QUOTES, 'utf-8'),
                    'category_id' => htmlspecialchars($this->input->post('category_id'), ENT_QUOTES, 'utf-8'),
                    'type' => htmlspecialchars($this->input->post('type'), ENT_QUOTES, 'utf-8'),
                    'UOM' => $this->input->post('uom'),
                    'description' => $this->input->post('description'),
                    'size' => htmlspecialchars($this->input->post('size'), ENT_QUOTES, 'utf-8'),
                    'flavour' => htmlspecialchars($flavour, ENT_QUOTES, 'utf-8'),
                    // 'company_type' => htmlspecialchars($c_type, ENT_QUOTES, 'utf-8'),
                    'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
                    'updated_by' => $this->session->userdata('id'),
                    'updated_on' => date('Y-m-d H:i:s')
                );
                $upc_code=$this->input->post('upc_code');
                $check=$this->Wine_model->check_duplicate_upccode($upc_code,$id);
                if($check > 0)
                {
                    $this->session->set_flashdata('message_type', 'danger');
                    $this->session->set_flashdata('message', '<strong>Oh snap!</strong> UPC Code already exists.Please try with different UPC Code');
                    redirect('/App/wine/edit/'.$id);
                }
                else
                {
                    //if the insert has returned true then we show the flash message
                    if ($this->Wine_model->update($this->tablename, 'id', $id, $data)) {



                        // Upload product Image
                        $pics = $_FILES['pics'];

                        if (!empty($pics['name'][0])) {

                            // Update Product Image
                            $config['upload_path'] = DIR_WINE_PICTURE;
                            $config['max_size'] = '8000';
                            $config['allowed_types'] = 'jpg|png|jpeg';
                            $config['overwrite'] = FALSE;
                            $config['remove_spaces'] = TRUE;

                            $this->load->library('upload', $config);

                            $images = array();

                            foreach ($pics['name'] as $key => $image) {

                                $_FILES['images[]']['name']= $pics['name'][$key];
                                $_FILES['images[]']['type']= $pics['type'][$key];
                                $_FILES['images[]']['tmp_name'] = $pics['tmp_name'][$key];
                                $_FILES['images[]']['error']= $pics['error'][$key];
                                $_FILES['images[]']['size']= $pics['size'][$key];

                                $config['file_name'] = 'wine-'.rand().date('YmdHis');
                                $images[] = $config['file_name'];

                                //print "<pre>"; print_r($_FILES); print "</pre>"; die;
                                $this->upload->initialize($config);

                                if ($this->upload->do_upload('images[]')) {

                                    $config_thumb['image_library'] = 'gd2';
                                    $config_thumb['source_image'] = DIR_WINE_PICTURE.$this->upload->file_name;
                                    $config_thumb['create_thumb'] = FALSE;
                                    $config_thumb['maintain_ratio'] = TRUE;
                                    $config_thumb['master_dim'] = 'auto';
                                    $config_thumb['width'] = WINE_PICTURE_THUMB_SIZE; // image re-size  properties
                                    $config_thumb['height'] = WINE_PICTURE_THUMB_SIZE; // image re-size  properties
                                    $config_thumb['new_image'] = DIR_WINE_PICTURE_THUMB.$this->upload->file_name; // image re-size  properties

                                    $this->load->library('image_lib', $config_thumb); //codeigniter default function

                                    $this->image_lib->initialize($config_thumb);
                                    if (!$this->image_lib->resize()) {
                                         echo $this->image_lib->display_errors();
                                    }
                                    $this->image_lib->clear();

                                    $upload_data =  $this->upload->data();
                                    $uploaded_pics = array();
                                    $uploaded_pics[] = $upload_data['file_name'];

                                    // Update database here
                                    $this->Wine_model->insert_product_pic($id, $uploaded_pics);

                                } else {
                                    $this->session->set_flashdata('message_type', 'danger');
                                    $this->session->set_flashdata('message', $this->upload->display_errors());
                                    redirect('/App/wine/edit/'.$id);
                                }
                            }
                        }

                        $this->session->set_flashdata('message_type', 'success');
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> Wine successfully updated.');
                    } else{
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
                    }

                    redirect($this->url);
                    //redirect('/App/wine/edit/'.$id);
                }
            } //validation run
        }

        $data['wine']  = $this->Wine_model->get_wine_details($id);
        //print "<pre>";print_r($data);die;
        if (!is_numeric($id) || $id == 0 || empty($data['wine'])) {
            redirect($this->url);
        }

        $filter = array('deleted' => 0, 'status' => 'active');
        $data['sizes'] = $this->Wine_model->get_wine_list($filter);
        //print "<pre>"; print_r($data); die;

        $search['parent_id'] = '0';
        $search['deleted'] = 0;
        $search['tag'] = "&ndash;";
        $this->load->model('Category_model');
        $data['list'] = $this->Category_model->get_category_list($search);

        $data['page'] = 'Wine';
        $data['page_title'] = SITE_NAME.' :: Wine Management &raquo; Edit Wine';

        $data['main_content'] = 'wine/edit';
        $this->load->view(TEMPLATE_PATH, $data);
    }


    /**
     *
     */
    public function update_status() {

        // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);

        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('operation', 'Operation', 'required');
            $this->form_validation->set_rules('item_id[]', 'Product', 'trim|required');

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
                    $filename = 'report_export_wine_'.date('m-d-Y-His').'.csv';
        
                    header("Content-Description: File Transfer");

                    header("Content-Disposition: attachment; filename=$filename");

                    header("Content-Type: application/csv; ");

                    
                    $data['students'] =  $this->Wine_model->get_wine_list_for_csv($items_to_export);
					//mycode add//
					for ($i=0;$i<count($data['students']);$i++){
						if($data['students'][$i]['company'] == 'mix')
						{
							$data['students'][$i]['company']='myx';
						}
						
					}
					//print_r($data['students']);die();
                    
                     $file = fopen('php://output', 'w');
                    

                    $delimiter = ',';
					
                    if(!empty($data['students'][0]))
                    {
						
                        $header = array_keys($data['students'][0]);
                        //$header[0]='SL No';
                        
                        
                        fputcsv($file, $header,$delimiter);

                        foreach ($data['students'] as $key=>$line){
                            if($key=='name')
                            {
                                $line=str_replace( '&amp;', '&', $line );
                            }
                            fputcsv($file,$line);
                        }
                    }
                    fclose($file);

                    exit;
                }
                else{
                    $data_to_store = array(
                        'status' => ($operation == "active")?'active':'inactive'
                    );

                    foreach ($items as $id=>$value) {

                        if ($operation == 'delete') {

                            //Check this wine is assotiated with any jobs or not
                            $wine_number=$this->Wine_model->check_job($id);
                            if($wine_number > 0)
                            {

                                $this->session->set_flashdata('message_type', 'danger');
                                $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Wine can not be deleted,because some of this wine has been associated with jobs.');
                                redirect($this->agent->referrer());
                            }
                            $data_to_store = array(
                                'is_deleted' => 1
                            );
                        } else {
                            $data_to_store = array(
                                'status' => ($operation == "active")?'active':'inactive'
                            );
                        }

                        if ($this->Wine_model->update($this->tablename, 'id', $id, $data_to_store)) {
                            $count++;
                        }
                    }

                    $msg = ($operation=='delete')?'deleted.':'updated.';

                    $this->session->set_flashdata('message_type', 'success');
                    $this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' wine(s) successfully '.$msg);
                }

            } else {
                $this->session->set_flashdata('message_type', 'danger');
                $this->session->set_flashdata('message', validation_errors());
            }
            redirect($this->url);
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
                $insertCount  = $notAddCount = 0;
                $updateCount =1;
                $rowCount=1;
				$duplicateUpc = array();
				$upcArray = array();
				$duplicate = FALSE;
				$invalidCategory = FALSE;
				$InvCatId = array();
                // If file uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
          
                    $this->load->library('CSVReader');
                    
                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);

                    // Insert/update CSV data into database
                    if(!empty($csvData)){
						//Check duplicate upsc codes
						foreach($csvData as $row){
                            array_push($upcArray,$row['upc_code']);
                            if(isset($row[' id']))
                            {
                                $upc = $this->Wine_model->checkDuplicateUpccodeWithId($row[' id'],$row['upc_code']);
                                if(!$upc){
                                    if(!in_array($row['upc_code'], $duplicateUpc)){
                                        array_push($duplicateUpc,$row['upc_code']);
                                    }
                                    $duplicate = TRUE;
                                }
                            }
                            $cat = $this->Wine_model->checkValidCategory($row['category_id']);
							if(!$cat){
								if(!in_array($row['category_id'], $InvCatId)){
									array_push($InvCatId,$row['category_id']);
								}
								$invalidCategory = TRUE;
							}
						}
						foreach(array_count_values($upcArray) as $val => $c){
							if($c > 1) {
								if(!in_array($val,$duplicateUpc)){
									array_push($duplicateUpc,$val);
								}
								
								$duplicate = TRUE;
							}
						}
							
                        if(!$duplicate && !$invalidCategory){
							foreach($csvData as $row){ 
								$rowCount++;
							   
                                $company_type = '';
                                $flavour='';
                                if(strtolower($row['company'])=="royal")
								{
									$flavour='royal';
								}else if(strtolower($row['company'])=="kayco")
								{
									$flavour='kayco';
								}else if(strtolower($row['company'])=="other")
								{
									$flavour='other';
								}else if(strtolower($row['company'])=="myx")
								{
									$flavour='mix';
								}


                                if ($flavour != ''){
                                    $memData = array(
                                        'id' => $row[' id'],
                                        'upc_code' => $row['upc_code'],
                                        'name' => $row['name'],
                                        'brand' => $row['brand'],
                                        'year' => $row['year'],
                                        'type' => $row['type'],
                                        'description' => $row['description'],
                                        'size' => $row['size'],
                                        'category_id' =>$row['category_id'],
                                        'flavour'=>$flavour,
                                        'company_type'=>$company_type,
                                        'UOM'=>$row['UOM']
                                        
                                    );
                                    
                                    // Check whether email already exists in the database
                                    $con = array(
                                        'where' => array(
                                            'id' => $row[' id']
                                        ),
                                        'returnType' => 'count'
                                    );
                                    $prevCount = $this->Wine_model->getRows($con);
                                                          
                                    if($row['upc_code']!='')
                                    {
                                        if($prevCount > 0){
                                            // Update member data
                                            $condition = array('id' => $row[' id']);
                                            $update = $this->Wine_model->update_data($memData, $condition);
    
                                            if($update){
                                                $updateCount++;
                                            }
                                        }else{
                                            // Insert member data
                                            $insert = $this->Wine_model->insert_data($memData);
    
                                            if($insert){
                                                $insertCount++;
                                            }
                                        }
                                    }
                                        // Status message with imported data count
                                $notAddCount = ($rowCount - ($insertCount + $updateCount));
                                $successMsg = 'Wine imported successfully. Total Rows ('.$rowCount.') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.')';
                                //$this->session->set_userdata('success_msg', $successMsg);
                                $this->session->set_flashdata('message_type', 'success');
                                $this->session->set_flashdata('message', $successMsg);
                                }else{

                                    $this->session->set_flashdata('message_type', 'danger');
                                    $this->session->set_flashdata('message', nl2br('<strong>Oh snap!</strong> The below wine/wines are not inserted/updated because of the invalid company type: ('.$row['upc_code'].')'));
                                }
                            }   

						}else{
							$this->session->set_flashdata('message_type', 'danger');
							if(!empty($duplicateUpc) && empty($InvCatId)){
								if(count($duplicateUpc)>1){
									$this->session->set_flashdata('message', nl2br("<strong>Oh snap!</strong> Import CSV operation unsuccessful. Following UPC codes are duplicated. Check CSV file and/or wine list. \n".implode(',', $duplicateUpc)));
								}else{
									$this->session->set_flashdata('message', nl2br("<strong>Oh snap!</strong> Import CSV operation unsuccessful. Following UPC code is duplicated. Check CSV file and/or wine list. \n".implode(',', $duplicateUpc)));
								}
							}
							if(!empty($InvCatId) && empty($duplicateUpc)){
								if(count($InvCatId)>1){
									$this->session->set_flashdata('message', nl2br("<strong>Oh snap!</strong> Import CSV operation unsuccessful. Following category ids are invalid and/or category id is empty. Check CSV file. \n".str_replace(',,',',',trim(implode(',', $InvCatId),","))));
								}else{
									$this->session->set_flashdata('message', nl2br("<strong>Oh snap!</strong> Import CSV operation unsuccessful. Following category id is invalid and/or category id is empty. Check CSV file. \n".str_replace(',,',',',trim(implode(',', $InvCatId),","))));
								}
							}
							if(!empty($InvCatId) && !empty($duplicateUpc)){
								if(count($duplicateUpc)>1 && count($InvCatId)>1){
									$this->session->set_flashdata('message', nl2br("<strong>Oh snap!</strong> Import CSV operation unsuccessful. Following UPC codes are duplicated. Check CSV file and/or wine list. ".implode(',', $duplicateUpc)."\n And following category ids are invalid and/or category id is empty. Check CSV file. \n".str_replace(',,',',',trim(implode(',', $InvCatId),","))));
								}else{
									$this->session->set_flashdata('message', nl2br("<strong>Oh snap!</strong> Import CSV operation unsuccessful. Following UPC code is duplicated. Check CSV file and/or wine list. \n".implode(',', $duplicateUpc)." \n And following category id is invalid and/or category id is empty. Check CSV file.\n".str_replace(',,',',',trim(implode(',', $InvCatId),","))));
								}
								
							}
						}
                    }
                }else{
                    //echo $this->upload->display_errors();die;
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
            redirect('App/wine');
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
    public function delete($id = null) {
        //echo $this->uri->segment(3);die;
        $data['product']  = $this->Wine_model->get_wine_details($id);
        
        if (!is_numeric($id) || $id == 0 || empty($data['product'])) {
            redirect($this->url);
        }
        //Check this wine is assotiated with any jobs or not
        $wine_number=$this->Wine_model->check_job($id);
        if($wine_number > 0)
        {

            

            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Wine can not be deleted,because this wine has been associated with jobs.');
            redirect('/App/wine');
        }

        $data_to_store = array(
            'is_deleted' => 1
        );

        if ($this->Wine_model->update($this->tablename, 'id', $id, $data_to_store)) {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', '<strong>Well done!</strong> Wine successfully deleted.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
        redirect($this->url);
    }



    /*public function delete_pic($id = null) {

        $id_part = explode("-", $id);

        $image_id = $id_part[0];
        $product_id = $id_part[1];

        if ($this->Wine_model->delete_product_pic($image_id)) {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', '<strong>Well done!</strong> Product image successfully deleted.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
        redirect('/admin/Wine/edit/'.$product_id);
    }*/

    // Check Product Code validation
    /*public function validate_code($code) {

        if($this->Wine_model->validate_code($code)) {
            $this->form_validation->set_message('validate_code', 'Product Code already exists.');
            return FALSE;
        } else {
            return TRUE;
        }
    }*/




    public function images($wine_id = null) {

        // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);

        if ($wine_id == null) {
            redirect($this->url);
        }

        // Get the Users List
        $data['list'] = $this->Wine_model->get_wine_images_list($wine_id);
        //print "<pre>";print_r($data);die;

        $data['wine']  = $this->Wine_model->get_wine_details($wine_id);

        $data['wine_id'] = $wine_id;
        $data['page'] = 'Wine';
        $data['page_title'] = SITE_NAME.' :: Wine Management &raquo; Image Management';

        $data['main_content'] = 'wine/images';
        $this->load->view(TEMPLATE_PATH, $data);
    }

    /**
     *
     */
    public function images_details($id = 0) {

        $image  = $this->Wine_model->get_images_details($id);
        //print "<pre>";print_r($data);die;
        if (!is_numeric($id) || $id == 0 || empty($image)) {
            return false;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($image));
    }


    /**
     *
     */
    public function images_order() {

        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $order = $this->input->post('sortable[]');
            //print_r($order);
            if (!empty($order)) {

                $count = 0;
                foreach ($order as $key => $id) {
                    if ($this->Wine_model->update('product_images', 'id', $id, array('order' => $key))){
                        $count++;
                    }
                }

                if ($count > 0) {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode("Image order successfully updated."));
                } else {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode("Please try again."));
                }
                
            }
        }
    }
    
    

    /**
     *
     */
    public function images_upload() {

        // Permission Checking
        //arent::checkMethodPermission($this->permissionValues[$this->router->method]);

        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            //$this->form_validation->set_rules('file', 'File', 'trim|required');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            //if the form has passed through the validation

            if ($this->form_validation->run())
            {

                $file = $_FILES['image'];
                //$image=$file['name'];
                $action = htmlspecialchars($this->input->post('action'), ENT_QUOTES, 'utf-8');
                $order = htmlspecialchars($this->input->post('order'), ENT_QUOTES, 'utf-8');
                $id = htmlspecialchars($this->input->post('image_id'), ENT_QUOTES, 'utf-8');
                $wine_id = htmlspecialchars($this->input->post('wine_id'), ENT_QUOTES, 'utf-8');

                $data = array(
                    'wine_id' => $wine_id,
                    'title' => htmlspecialchars($this->input->post('title'), ENT_QUOTES, 'utf-8'),
                    'order' => $order
                );

                // Upload File
                if (!empty($file['name'])) {

                    $config['upload_path'] = DIR_WINE_PICTURE;
                    $config['max_size'] = '8000';
                    $config['allowed_types'] = 'jpg|png|jpeg|tiff|gif';
                    $config['overwrite'] = FALSE;
                    $config['remove_spaces'] = TRUE;

                    $this->load->library('upload', $config);

                    $config['file_name'] = 'wine-'.rand().date('YmdHis');
                    
                    $this->upload->initialize($config);

                     if($this->upload->do_upload('image')){
                        
                        $config_thumb['image_library'] = 'gd2';
                        $config_thumb['source_image'] = DIR_WINE_PICTURE.$this->upload->file_name;
                        $config_thumb['create_thumb'] = FALSE;
                        $config_thumb['maintain_ratio'] = TRUE;
                        $config_thumb['master_dim'] = 'auto';
                        $config_thumb['width'] = WINE_PICTURE_THUMB_SIZE; // image re-size  properties
                        $config_thumb['height'] = WINE_PICTURE_THUMB_SIZE; // image re-size  properties
                        $config_thumb['new_image'] = DIR_WINE_PICTURE_THUMB.$this->upload->file_name; // image re-size  properties

                        $this->load->library('image_lib', $config_thumb); //codeigniter default function

                        $this->image_lib->initialize($config_thumb);
                        if (!$this->image_lib->resize()) {
                             $this->session->set_flashdata('message_type', 'danger');
                             $this->session->set_flashdata('message', $this->upload->display_errors());
                             
                             redirect('App/wine/images/'.$wine_id);
                        }
                        $this->image_lib->clear();

                        $upload_data =  $this->upload->data();
                        $data['image'] = $upload_data['file_name'];
                     } else {
                       
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', $this->upload->display_errors());

                        redirect('App/wine/images/'.$wine_id);
                     }
                    
                }

                //if the insert has returned true then we show the flash message
                if ($action == "Edit") {
                    if ($this->Wine_model->update('wine_images', 'id', $id, $data)) {
                        $this->session->set_flashdata('message_type', 'success');
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> Image has been updated successfully.');
                    } else {
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
                    }
                } else {
                    if ($this->Wine_model->insert('wine_images', $data)) {
                        $this->session->set_flashdata('message_type', 'success');
                        $this->session->set_flashdata('message', '<strong>Well done!</strong> Image has been uploaded successfully.');
                    } else {
                        $this->session->set_flashdata('message_type', 'danger');
                        $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
                    }
                }
                
                redirect('App/wine/images/'.$wine_id);
            } //validation run
        }
    }

    /**
     *
     * @param int $id
     */
    public function images_delete($product_id = null) {

        // Permission Checking
        //parent::checkMethodPermission($this->permissionValues[$this->router->method]);

        if ($product_id == null) {
            redirect($this->url);
        }

        $id = $this->uri->segment(6);
        $data['info'] = $this->Wine_model->get_images_details($id);

        if (!is_numeric($id) || $id == 0 || empty($data['info'])) {
            redirect('/App/wine/images/'.$product_id);
        }

        $data_to_store = array(
            'is_deleted' => 1
        );

        if ($this->Wine_model->update('wine_images', 'id', $id, $data_to_store)) {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', '<strong>Well done!</strong> Image successfully deleted.');
        } else {
            $this->session->set_flashdata('message_type', 'danger');
            $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
        }
        redirect('/App/wine/images/'.$product_id);
    }


    // Product Search form submit
    public function search_submit() {


        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $name = $this->clean_value($this->input->post('name'));
            $sampling_date = $this->clean_value($this->input->post('sampling_date'));
            $sampling_status = $this->clean_value($this->input->post('sampling_status'));
            $bottles = $this->clean_value($this->input->post('bottles'));
            $upc_code = $this->clean_value($this->input->post('upc_code'));
            $url = "App/wine/index/";

            if ($name != '') {
                $url .= "name/". urlencode($name)."/";
            }
            if ($upc_code != '') {
                $url .= "upc_code/". urlencode($upc_code)."/";
            }
            if ($sampling_date != '') {
                $url .= "sampling_date/". urlencode($sampling_date)."/";
            }
            if ($sampling_status != '') {
                $url .= "sampling_status/". urlencode($sampling_status)."/";
            }
            if ($bottles != '') {
                $url .= "bottles/". urlencode($bottles)."/";
            }
            if ($size_id != '') {
                $url .= "size_id/". urlencode($size_id)."/";
            }
            redirect($url);
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
    private function init_pagination($uri, $segment=4, $total_rows,$view=NULL) {

        $this->config->load('pagination');
        $this->load->library('pagination');

        $config = $this->config->item('pagination');

        $ci                          =& get_instance();
        $config['uri_segment']       = $segment;
        $config['base_url']          = base_url().$uri;
        $config['total_rows']        = $total_rows;
        $config['per_page']        		= $view;
        $ci->pagination->initialize($config);
        return $config;
    }


    private function format_date($date) {
       if ($date == null)
        return null;
       if ($date == "")
        return "";

       $newdate = date_create($date);
       return date_format($newdate,"Y-m-d");
    }

    public function view_details()
    {
        $wine_id=$this->input->post('wine_id');
        $this->db->select('bottles_sampled,bottles_sold,open_bottles_sampled');
        $this->db->from('completed_job_wine_details');
        $this->db->where('wine_id',$wine_id);
        $value=$this->db->get();
        $result=$value->result_array();
        $bottles_sampled=0;
        $bottles_sold=0;
        $open_bottles_sampled=0;
        foreach($result as $val)
        {
            $bottles_sampled+=$val['bottles_sampled'];
            $bottles_sold+=$val['bottles_sold'];
            $open_bottles_sampled+=$val['open_bottles_sampled'];
        }
		$this->db->select('*');
		$this->db->from('wine');
		$this->db->where('id',$wine_id);
		$wine_details = $this->db->get()->row();
		//echo $wine_details->name;
		//print_r($wine_details);die;
		if($wine_details){
		$this->db->select('*');
		$this->db->from('category');
		$this->db->where('id',$wine_details->category_id);
		$category=$this->db->get()->row();
		$data['category_name']=$category->name;
		}
		$data['wine']=$wine_details;
        $data['bottles_sampled']=$bottles_sampled;
        $data['bottles_sold']=$bottles_sold;
        $data['open_bottles_sampled']=$open_bottles_sampled;
        $this->load->view('wine/wine_details',$data);

    }
}

/* End of file Wine.php */
/* Location: ./application/controllers/admin/Wine.php */
