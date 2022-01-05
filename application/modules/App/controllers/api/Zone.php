<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Zone extends REST_Controller {

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

        
        
        $this->load->model('Zone_model');
    }





	
    public function get_zone_get() {



        $zone_id = $this->get('zone_id');
        

        if($zone_id!='')
        {
            $details=$this->Zone_model->get_zone($zone_id);
            
            
        }
        else
        {
            $details = $this->Zone_model->get_zone();
            

            
        }
       // echo "<pre>";
       // print_r($details);die;



        // If a user exists in the data store e.g. database

        if (!empty($details))

        {

            $this->set_response([

                'success' => TRUE,
                
                'data' => $details,

            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        }

        else

        {

            $this->set_response([

                'success' => FALSE,

                'error' => 'Zone not found'

            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }



        return false;

    }

}



	




	








	




	



	




	

	



