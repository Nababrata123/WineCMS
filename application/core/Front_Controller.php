<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Front_Controller extends MX_Controller {

    protected $data = array();

    public function __construct() {

        parent::__construct();

        // HMVC form_validation - fix
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;

        $this->lang->load('application');

        $this->data['page'] = new stdClass();
		$this->data['page']->page_type = '';
        $this->data['page_title'] = $this->lang->line('app_site_name');
        $this->data['meta_description'] = "";
        $this->data['meta_keyword'] = "";

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




}
