<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stores extends Front_Controller {

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
	protected $data = array();

	public function __construct() {
		parent::__construct();
    }

	public function index() {

		$this->load->model('../../App/models/Product_model');

		// Set the filters
		$filter = array('deleted' => 0, 'status', 'active');
		
		// List the prodcuts
		$this->data['products'] = $this->Product_model->get_product_list($filter, 'created_on', 'desc');

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'stores';

		$this->data['main_content'] = 'stores/list';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}

	
	public function details($id = null) {

		if ($id == null) {
			redirect('/stores');
		}

		$this->load->model('../../App/models/Product_model');

		// Set the filters
		$filter = array('deleted' => 0, 'status' => 'active');
        $this->data['sizes'] = $this->Product_model->get_size_list($filter);
		
		// Get product details
		$this->data['product'] = $this->Product_model->get_product_details($id);;

		// Get the product images
		$this->data['images'] = $this->Product_model->get_product_images_list($id);
		
		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'stores';

		$this->data['main_content'] = 'stores/details';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}


	public function add_to_cart() {

		if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
     		//form validation
     		$this->form_validation->set_rules('size', 'Size', 'trim|required');
	    	$this->form_validation->set_rules('product_id', 'product', 'trim|required');

     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
     		//if the form has passed through the validation

     		if ($this->form_validation->run())
     		{
				$id = $this->input->post('product_id');

				// Get product details
				$this->load->model('../../App/models/Product_model');
				$product = $this->Product_model->get_product_details($id);;

				// Get the product images
				$images = $this->Product_model->get_product_images_list($id);

				if (empty($product)) {
					redirect('/stores');
				}

				//print "<pre>"; print_r($_POST); die;
				$name = str_replace(" ","_",$product->name);
				$name = htmlspecialchars_decode($name, ENT_QUOTES);
				$name = str_replace(array("'", "\""), "", $name);

				$pdata = array(
					'id'      => $id,
					'qty'     => 1,
					'price'   => $product->price,
					'name'    => $name,
					'options' => array(
						'Size' => $this->input->post('size'),
					),
					'image'	  => $images[0]->image,
				);
				
				$this->cart->insert($pdata);
     			redirect('/stores/cart', 'refresh');
     		} //validation run
		 }
	}


	public function update_cart() {

		if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
			$data = $this->input->post();
			$this->cart->update($data);

			redirect('/stores/cart', 'refresh');
		 }
	}

	public function remove_cart($rowid) {

		$this->cart->remove($rowid);
		redirect('/stores/cart', 'refresh');
	}

	public function cart() {
		
		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'cart';

		$this->data['main_content'] = 'stores/cart';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}


	public function checkout() {
		
		// If customer is not logged-in, go to login page
		if (!$this->session->userdata('is_customer_logged_in')) {
			redirect('login?redirect=stores/checkout');
		}

		// If no products found in cart, go to store page
		if ($this->cart->total_items() < 1) {
			redirect('stores');
		}

		if ($this->input->server('REQUEST_METHOD') === 'POST')
     	{
			//print "<pre>"; print_r($_POST); print_r($this->cart->contents());die;
			//form validation
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
	    	$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('city', 'City', 'trim|required');
			$this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|required');
			$this->form_validation->set_rules('country', 'Country', 'trim|required');
			$this->form_validation->set_rules('state', 'State', 'trim|required');

     		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
     		//if the form has passed through the validation

     		if ($this->form_validation->run())
     		{				
				$this->load->model('Public_model');
				$order_code = "OD-".date('Ymd')."-".rand(10000,99999);

				$payment_type = htmlspecialchars($this->input->post('payment_type'), ENT_QUOTES, 'utf-8');
				$a_data = array(
					'name' => htmlspecialchars($this->input->post('name'), ENT_QUOTES, 'utf-8'),
					'phone' => htmlspecialchars($this->input->post('phone'), ENT_QUOTES, 'utf-8'),
					'address' => htmlspecialchars($this->input->post('address'), ENT_QUOTES, 'utf-8'),
					'city' => htmlspecialchars($this->input->post('city'), ENT_QUOTES, 'utf-8'),
					'zipcode' => htmlspecialchars($this->input->post('zipcode'), ENT_QUOTES, 'utf-8'),
					'country' => htmlspecialchars($this->input->post('country'), ENT_QUOTES, 'utf-8'),
					'state' => htmlspecialchars($this->input->post('state'), ENT_QUOTES, 'utf-8'),
				);
				//print_r($a_data); die;
				// Save address
				$address_id = $this->Public_model->insert('order_address', $a_data);
				
				$o_data = array(
					'customer_id' => $this->session->userdata('id'),
					'address_id' => $address_id,
					'code' => $order_code,
					'total' => $this->cart->total(),
					'status' => 'pending',
					'payment_type' => $payment_type,
					'created_on' => date('Y-m-d H:i:s')
				);

				// Save order
				$order_id = $this->Public_model->insert('orders', $o_data);

				foreach ($this->cart->contents() as $rowid => $item) {
					$p_data = array(
						'order_id' => $order_id,
						'product_id' => $item['id'],
						'price' => $item['price'],
						'qnty' => $item['qty'],
						'size' => $item['options']['Size'],
					);

					// Save products
					$this->Public_model->insert('order_products', $p_data);
				}

				if ($payment_type == 'paypal') {

					// Load the paypal library
					$this->load->library('paypal');

					//Set variables for paypal form
					$returnURL = base_url('stores/success'); //payment success url
					$cancelURL = base_url('stores/cancel'); //payment cancel url
					$notifyURL = base_url('stores/ipn'); //ipn url
					$logo = base_url('assets/images/logo.png');

					$this->paypal->add_field('return', $returnURL);
					$this->paypal->add_field('cancel_return', $cancelURL);
					$this->paypal->add_field('notify_url', $notifyURL);
					$this->paypal->add_field('custom', $order_code);

					// Cart specific values
					$this->paypal->add_field('cmd', '_cart');
					$this->paypal->add_field('upload', '1');
					$i = 1;
					foreach ($this->cart->contents() as $rowid => $item) {
						$this->paypal->add_field('item_name_'.$i, str_replace("_", " ",$item['name']));
						$this->paypal->add_field('amount_'.$i, $item['price']);
						$this->paypal->add_field('quantity_'.$i, $item['qty']);
						$i++;
					}
					//$this->paypal->add_field('shipping', 0);
					//$this->paypal->add_field('quantity', 1);
					//$this->paypal->add_field('tax', 0);
					//$this->paypal->add_field('amount', $this->cart->total());

					$this->paypal->image($logo);
					//print "<pre>"; print_r($this->paypal); die;
					$this->paypal->paypal_auto_form();
				}

				// Remove the items from cart
				$this->cart->destroy();

				$this->session->set_flashdata('message', "Thank you for submitting your order #".$order_code.". We will get in-touch with you soon.");
     			redirect('/stores/confirm', 'refresh');
     		} //validation run
		}


		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'checkout';

		$this->data['main_content'] = 'stores/checkout';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}



	public function confirm() {

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'confirm';

		$this->data['main_content'] = 'stores/confirm';
		$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
	}


	function success() {
    	//get the transaction data
        $paypalInfo = $this->input->get();

		$this->data['payment_amt'] = $paypalInfo["amt"];
		$this->data['currency_code'] = $paypalInfo["cc"];
		$this->data['order_code'] = $paypalInfo["cm"];
		$this->data['status'] = $paypalInfo["st"];
        $this->data['txn_id'] = $paypalInfo["tx"];

		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'success';

        //pass the transaction data to view
		$this->data['main_content'] = 'stores/success';
    	$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
    }

	function cancel() {
    	//get the transaction data
		$this->data['page'] = new stdClass();
		$this->data['page']->page_type = 'cancel';

        //pass the transaction data to view
		$this->data['main_content'] = 'paypal/cancel';
    	$this->load->view(PUBLIC_TEMPLATE_PATH, $this->data);
    }

	function ipn() {

		$this->load->library('paypal');

	   	//paypal return transaction details array
	   	$paypalInfo = $this->input->post();

		$results = print_r($paypalInfo, true);
		file_put_contents(BASEPATH.'logs/filename.txt', $results);

	   	$this->data['order_code'] = $paypalInfo['custom'];
	   	//$this->data['product_id'] = $paypalInfo["item_number"];
	   	$this->data['txn_id'] = $paypalInfo["txn_id"];
	   	/*$this->data['payment_gross'] = $paypalInfo["payment_gross"];
	   	$this->data['currency_code'] = $paypalInfo["mc_currency"];
	   	$this->data['payer_email'] = $paypalInfo["payer_email"];
	   	$this->data['payment_status'] = $paypalInfo["payment_status"];*/
	   	$paypalURL = $this->paypal->paypal_url;
	   	$result = $this->paypal->curlPost($paypalURL,$paypalInfo);

	   	//check whether the payment is verified
	   	if(preg_match("/VERIFIED/i", $result)){

		   	//insert the transaction data into the database
			$data_update = array(
		        'txn_id' => $this->data['txn_id'],
		        'payment_status' => 'complete',
		        'payment_date' => date('Y-m-d H:i:s')
			);
			$this->db->where('code', $this->data['order_code']);
			$this->db->update('orders', $data_update);
		}
	}
}
