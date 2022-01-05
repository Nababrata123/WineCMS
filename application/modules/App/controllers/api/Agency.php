<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Agency extends REST_Controller {

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

        $this->load->model('Tester_model');
        $this->load->model('Zone_model');
        $this->load->model('Agency_model');
    }

    public function get_agency_and_tester_get() {



        $zone_id = $this->get('zone_id');
        $store_id=$this->get('store_id');

        //Get agency id and details
        $agency_ids=$this->Agency_model->get_agency_id($zone_id,$store_id);
        //get agencies tester id
        $agencies_tester_ids=$this->Agency_model->get_tester_id_for_agency($zone_id,$store_id);
        $agencies_tester_details=$this->Agency_model->fetch_tester_details($agencies_tester_ids);

        $agency_details=$this->Agency_model->fetch_agency_details($agency_ids);


        //Get tester id and details
        $tester_ids=$this->Agency_model->get_tester_id($zone_id,$store_id);
        $tester_details=$this->Agency_model->fetch_tester_details($tester_ids);

        if (!empty($agency_details) || !empty($tester_details))

        {

            $this->set_response([

                'success' => TRUE,
                
                'agency'=>$agency_details,
                'agencies_taster'=>$agencies_tester_details,
                'tester' => $tester_details,

            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        }

        else

        {

            $this->set_response([

                'success' => FALSE,

                'error' => 'Agency or tester could not be found'

            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }



        return false;

    }



	

}



	




	








	




	



	




	

	



