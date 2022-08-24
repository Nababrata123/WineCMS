<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Geocode {

    private $CI;

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {

        //parent::__construct();
        $this->CI = &get_instance();

        //$this->CI->lang->load('application');
    }

    public function get_location($lat, $long) {

        try {
            $data = "latlng=".$lat.",".$long."&key=".GEOCODE_API;
            $ch = curl_init('https://maps.googleapis.com/maps/api/geocode/json?'.$data);
            //curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch); // This is the result from the API
            curl_close($ch);

            return json_decode($result);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n"; die;
        }
    }

	/**
	 * This function returns Longitude & Latitude from location.
	 *
	 * @param string $location
	 */
	function get_lat_long($location = "") {

        $result_loc = array();

		if ($location == "")
			return $result_loc;

		try {
            $location = str_replace (" ", "+", urlencode($location));

			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($location)."&key=".GEOCODE_API."&sensor=false";

            print_r($url);die;

			$ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result_string = curl_exec($ch); // This is the result from the API
            curl_close($ch);

			$result = json_decode($result_string, true);
            //print "<pre>";print_r($result_string); die;
			$result_loc = $result['results'][0]['geometry']['location'];

		} catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n"; die;
        }
		return $result;
	}
}
//https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=YOUR_API_KEY

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
