<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Store extends REST_Controller {

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


	public function __construct() {

        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['do_login_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['send_otp_get']['limit'] = 500; // 500 requests per hour per user/key

        $this->load->model('Sales_representative_model');
        $this->load->model('Store_model');
        $this->load->model('Zone_model');
    }





	
    public function get_store_get() {



        $user_id = $this->get('user_id');
        if($user_id!='')
        {
            $details=$this->Store_model->get_store($user_id);
        }
        else
        {
            $details = $this->Store_model->get_store();
        }

        foreach($details as $element)
        {
            if($element->logo=='')
            {
                $element->logo=BASE_URL.DIR_STORE_LOGO."no-image.jpg";
            }
        }
       

        

        // If a user exists in the data store e.g. database

        if (!empty($details))

        {

            $this->set_response([

                'success' => TRUE,
                'assets_folder'=>BASE_URL.DIR_STORE_LOGO,
                
                'data' => $details,

            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        }

        else

        {

            $this->set_response([

                'success' => FALSE,

                'error' => 'Store could not be found'

            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }



        return false;

    }

}



	




	








	




	



	




	

	



