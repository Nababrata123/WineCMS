<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Application_Controller extends MX_Controller {

    protected $data = array();

    public function __construct() {

        parent::__construct();

        // HMVC form_validation - fix
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;

        $this->lang->load('application');

        $this->data['page_title'] = $this->lang->line('app_site_name');
        $this->load->vars($this->data);
    }

    protected function checkLoggedin() {

    	if (!$this->session->userdata('is_logged_in')) {

    		if (!$this->input->is_ajax_request()) {
	            redirect('');
    		} else {
    			show_error();
    		}
        }
        return false;
    }

    protected function checkMethodPermission($method) {

    	if (!$this->checkActionPermission($method)) {
    		$this->session->set_flashdata('message_type', 'danger');
    		$this->session->set_flashdata('message', '<span class="glyphicon glyphicon-warning-sign"></span> You don\'t have required permission to view the page.');

    		redirect('dashboard');
    	}
    }

    protected function checkActionPermission($method) {

    	$permissions = $this->session->userdata('permissions');
        
        
    	if (!in_array($method, $permissions)) {
    		return false;
    	} else {
            return true;
        }
    }

}
