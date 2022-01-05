<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Wine extends REST_Controller {

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

        
        $this->load->model('Category_model');
        $this->load->model('Wine_model');
    }





	
    public function get_wine_get() {



        $category_id = $this->get('category_id');
        $wine_sell_type=$this->get('wine_sell_type');

        if($category_id!='')
        {
            $details=$this->Wine_model->get_wine($category_id,$wine_sell_type);
            
            $category_details=$this->Category_model->get_category($category_id);
        }
        else
        {
            $details = $this->Wine_model->get_wine('',$wine_sell_type);
            $category_details='';

            
        }
       // echo "<pre>";
       // print_r($details);die;



        // If a user exists in the data store e.g. database

        if (!empty($details))

        {

            $this->set_response([

                'success' => TRUE,
                'assets_folder'=>BASE_URL.DIR_WINE_PICTURE,
                'assets_thumb_folder'=>BASE_URL.DIR_WINE_PICTURE_THUMB,
                'category'=>$category_details,
                'data' => $details,

            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        }

        else

        {

            $this->set_response([

                'success' => FALSE,

                'error' => 'Wine could not be found'

            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }



        return false;

    }
    public function search_wine_get() {


        $wine_sell_type=$this->get('wine_sell_type');
        $search_text=$this->get('search_text');
        $time=$this->get('server_time');
    
        $server_time = str_replace('_', ' ', $time);
        
        $category_id_container=array();
        if($search_text!='')
        {
            $details=$this->Wine_model->search_wine($search_text,$wine_sell_type);
            
            foreach($details as $arr)
            {
            	array_push($category_id_container,$arr['category_id']);
            }
            $category_details=$this->Category_model->search_category($category_id_container);
        }else if ($server_time !=''){
            $details=$this->Wine_model->search_wine($search_text,$wine_sell_type,$server_time);

            foreach($details as $arr)
            {
            	array_push($category_id_container,$arr['category_id']);
            }

           if (count($category_id_container)>0 ){
            $category_details=$this->Category_model->search_category($category_id_container);
           }   
        }else{
            $details = $this->Wine_model->search_wine('',$wine_sell_type);
            // print_r($date_time);die;
            foreach($details as $arr)
            {
            	array_push($category_id_container,$arr['category_id']);
            }
            $category_details=$this->Category_model->search_category($category_id_container);

            
        }
        
      	unset($category_id_container);

          // get server time..
        $timestamp = time();
        $date_time = date("Y-m-d_H:i:s", $timestamp);

        // If a user exists in the data store e.g. database

        if (!empty($details))

        {

            $this->set_response([

                'success' => TRUE,
                'assets_folder'=>BASE_URL.DIR_WINE_PICTURE,
                'assets_thumb_folder'=>BASE_URL.DIR_WINE_PICTURE_THUMB,
                'server_time'=>$date_time,
                'category'=>$category_details,
                'data' => $details,

            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        }
        else

        {
            $this->set_response([

                'success' => TRUE,

                'error' => 'Wine could not be found'

            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }

        return false;

    }
    
}



	




	








	




	



	




	

	



