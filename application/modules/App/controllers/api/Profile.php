<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Profile extends REST_Controller {

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

        
        
        $this->load->model('Profile_model');
    }





	
    public function update_password_post() {



        $user_id = $this->post('user_id');
        $old_password = $this->post('old_password');
        $new_password = $this->post('new_password');
        $user['password']=md5($new_password);
        $data['info'] = $this->Profile_model->get_user_details($user_id);
        
        
           
        


            // If a user exists in the data store e.g. database

            if (!empty($data['info']))
            {
                $password=$data['info'][0]['password'];
                if($password!=md5($old_password))
                {
                    $this->set_response([

                        'success' => FALSE,

                        'error' => 'Old password does not exist'

                    ], REST_Controller::HTTP_OK);
                }
                else
                {
                    $update=$this->Profile_model->update('users','id',$user_id,$user);
                        $this->set_response([

                        'success' => TRUE,
                        
                        

                    ], REST_Controller::HTTP_OK);
                }

                 // OK (200) being the HTTP response code

            }

            else

            {

                $this->set_response([

                    'success' => FALSE,

                    'error' => 'User not found'

                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

            }

        

        return false;

    }

    //Update user details

    public function update_user_details_post() {



        $user_id = $this->post('user_id');
        $first_name = $this->post('first_name');
        $last_name = $this->post('last_name');
        $email = $this->post('email');
        $ph_no = $this->post('ph_no');
        $city = $this->post('city');
        $state = $this->post('state');
        $zipcode = $this->post('zipcode');
        $Address = $this->post('Address');
        $suite_no = $this->post('suite_number');
        $appartment_no = $this->post('appartment_number');

        $rate_per_hr = $this->post('rate_per_hr');
        if (strpos($rate_per_hr, '$') !== false) {
           $rate_per_hr= preg_replace("([$])"," ",$rate_per_hr);
           
        }
        $zone_id = $this->post('zone_id');
        
        $data['info'] = $this->Profile_model->get_user_details($user_id);
        $user_created_by=$data['info'][0]['created_by'];
        $user['first_name']=$first_name;
        $user['last_name']=$last_name;
        $user['email']=$email;

        
        $user_meta['address']=$Address;
        $user_meta['phone']=$ph_no;
        $user_meta['rate_per_hour']=$rate_per_hr;
        $user_meta['zone']=$zone_id;
        $user_meta['city']=$city;   
        $user_meta['state']=$state;
        $user_meta['zipcode']=$zipcode;
        $user_meta['suite_number']=$suite_no;
        $user_meta['appartment_number']=$appartment_no;

            // If a user exists in the data store e.g. database

            if (!empty($data['info']))
            {
                if($user_created_by==7)
                {
                    if($city=='' || $state=='' || $zipcode=='' || $Address=='' || $suite_no=='' || $appartment_no=='')
                    {
                        $this->set_response([

                            'success' => FALSE,

                            'error' => 'City,state,zipcode,address,suite number and appartment number are mandatory'

                        ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                    }
                    else
                    {
                        $update=$this->Profile_model->update('users','id',$user_id,$user);
                        $this->Profile_model->update_user_meta('user_meta','user_id',$user_id,$user_meta);

                        $this->set_response([

                            'success' => TRUE,



                        ], REST_Controller::HTTP_OK);

                        // OK (200) being the HTTP response code
                    }
                }
                else
                {
                    $update=$this->Profile_model->update('users','id',$user_id,$user);
                    $this->Profile_model->update_user_meta('user_meta','user_id',$user_id,$user_meta);
                
                    $this->set_response([

                        'success' => TRUE,
                            
                            

                    ], REST_Controller::HTTP_OK);
                
                    // OK (200) being the HTTP response code
                }
                
                
                    

            }

            else

            {

                $this->set_response([

                    'success' => FALSE,

                    'error' => 'User not found'

                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

            }

        

        return false;

    }

}



	




	








	




	



	




	

	



