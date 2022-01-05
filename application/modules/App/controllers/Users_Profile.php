
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users_Profile extends Application_Controller {

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

    // private $tablename = 'users';
    
    // private $reference_url = '';
    // private $permissionValues = array(

        
    //     'edit' => 'App.Users.Edit',
        
    // );
	// protected $data = array();

	// public function __construct() {

    //     parent::__construct();
    //     $this->load->model('Users_model');
        
    // }

	// public function index() {
       
	// 	redirect(BASE_URL);

	// }
    // public function edit($id = 0) {

        
    //     // print_r($this->permissionValues[$this->router->method]);die;
    //     // Permission Checking
    //     parent::checkMethodPermission($this->permissionValues[$this->router->method]);
    //     // print_r("Hello");die;
    //     $this->load->model('Users_model');
    //     if ($id === 0) {
    //         $id = $this->session->userdata('id');
    //     }

    //     // Include the Module JS file.
    //     add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    //     $config = $this->config->item('module_config');

    //     //get usermeta
    //     $this->db->select('*');
    //     $this->db->from('user_meta');
    //     $this->db->where('user_meta.user_id',$id);
        
    //     $user_meta = $this->db->get();

        
    //     $data['user_meta']=$user_meta->result_array();
    //     // echo "<pre>";
    //     // print_r($data['user_meta']);

    //     //if save button was clicked, get the data sent via post
    //     if ($this->input->server('REQUEST_METHOD') === 'POST')
    //     {

    //         //form validation
    //         $this->form_validation->set_rules('role_id', 'Role', 'trim|required');
    //         $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
    //         $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
    //         $this->form_validation->set_rules('email', 'Email address', 'trim|required');

    //         if ($this->input->post('password')) {
    //             // echo "Hi Change password";die;
    //             $this->form_validation->set_rules('password', 'Password', 'matches[c_password]');
    //             $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required');
    //         }

    //         // print_r($this->input->post('password'));die;
    //         // Custom field validation
    //         if(is_array($data['user_meta'])){

    //             foreach ($data['user_meta'] as $key=>$value) {
    //                 $field_name = $key;
    //                 $field_label = $key;
    //                 if (isset($value['required']) && $value['required'] == true) {
    //                     $this->form_validation->set_rules($field_name, $field_label, 'required');
    //                 }
    //             }
    //         }

    //         $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
    //         //if the form has passed through the validation

    //         if ($this->form_validation->run())
    //         {
    //             // echo "Hi Everything is done";die;
    //             $user = array(
    //                 'first_name' => htmlspecialchars($this->input->post('first_name'), ENT_QUOTES, 'utf-8'),
    //                 'last_name' => htmlspecialchars($this->input->post('last_name'), ENT_QUOTES, 'utf-8'),
    //                 'role_id' => htmlspecialchars($this->input->post('role_id'), ENT_QUOTES, 'utf-8'),
    //                 'email' => htmlspecialchars($this->input->post('email'), ENT_QUOTES, 'utf-8'),
    //                 'status' => htmlspecialchars($this->input->post('status'), ENT_QUOTES, 'utf-8'),
    //                 'updated_by' => $this->session->userdata('id'),
    //                 'updated_on' => date('Y-m-d H:i:s')
    //             );

    //             $meta = $this->input->post('meta');
    //             // $data['user_meta'][0]['meta_value'] = $meta['phone'];
    //             // $data['user_meta'][1]['meta_value'] = $meta['adress'];
 
    //             // print_r($data['user_meta']);die;
               
    //             	 //Set input data to Meta Table
	// 		 $metadata = array(
    //             'phone'  => $meta['phone'],
    //             'adress'  => $meta['adress'],
    //             'brand_id'  => $data['user_meta'][2]['meta_value'],				
    //         );
            



    //             if ($this->input->post('password')) {
    //                 $user['password'] = md5(htmlspecialchars($this->input->post('password'), ENT_QUOTES, 'utf-8'));
    //             }

    //             //if the insert has returned true then we show the flash message
    //             if ($this->Users_model->update_profile('id', $id, $user)) {

    //                 // Insert the Mata Data
    //                 $this->Users_model->replace_user_meta($id, $metadata);

    //                 $this->session->set_flashdata('message_type', 'success');
    //                 if ($this->input->post('ref') == 'profile') {
    //                     $this->session->set_flashdata('message', '<strong>Well done!</strong> Profile successfully updated.');
    //                 } else {
    //                     $this->session->set_flashdata('message', '<strong>Well done!</strong> User successfully updated.');
    //                 }
    //             } else{
    //                 $this->session->set_flashdata('message_type', 'danger');
    //                 $this->session->set_flashdata('message', '<strong>Oh snap!</strong> Change something and try again.');
    //             }

    //             // If from profile page - redirect there
    //             if ($this->input->post('ref') == 'profile') {
    //                 redirect('App/Users_Profile/profile');
    //             }

    //             redirect('App/Users_Profile/profile');
    //         } //validation run
    //     }

    //     $data['user'] = $this->Users_model->get_user_details($id);

    //     if (!is_numeric($id) || $id == 0 || empty($data['user'])) {
    //         redirect('App/Users_Profile/profile');
    //     }

    //     // Roles List (for dropdown)
    //     $data['roles'] = $this->Users_model->get_roles_list();
    //     $data['zone']=$this->Users_model->get_zone_list();
    //     $data['page'] = 'users';
    //     $data['page_title'] = SITE_NAME.' :: User Management &raquo; Edit User';

    //     $data['main_content'] = 'users/users_profile';
    //     $this->load->view(TEMPLATE_PATH, $data);
    // }
	// /**
    //  * Login into account
    //  * Sets sesstion data
    //  */

    // public function profile() {
    //     $id = $this->session->userdata('id');
    //     // print_r($id);die;
    //     $this->load->model('Users_model');
    //     // Include the Module JS file.
    //     add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
    //     $config = $this->config->item('module_config');

    //     //get usermeta
    //     $this->db->select('*');
    //     $this->db->from('user_meta');
    //     $this->db->where('user_meta.user_id',$id);
        
    //     $user_meta = $this->db->get();
    //     $data['zone']=$this->Users_model->get_zone_list();
        
    //     $data['user_meta']=$user_meta->result_array();

    //     $data['user']  = $this->Users_model->get_user_details($id);

    //     // if (!is_numeric($id) || $id == 0 || empty($data['user'])) {
    //     //     redirect('Agency/dashboard');
    //     // }

    //     // Roles List (for dropdown)
    //     $data['roles'] = $this->Users_model->get_roles_list();

    //     $data['page'] = 'profile';
    //     $data['page_title'] = SITE_NAME.' :: Update Profile';

    //     $data['main_content'] = 'users/users_profile';


    //     $this->load->view(TEMPLATE_PATH, $data);
    // }

  

}
