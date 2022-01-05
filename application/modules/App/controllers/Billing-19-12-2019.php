<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Billing extends Application_Controller {
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
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
    private $tablename = 'job';
    private $url = '/App/billing';
    private $reference_url = '';
    private $permissionValues = array(
        'index' => 'App.Billing.View',
        'get_expenses_brandwise'=>'App.Billing.Get_expenses_brandwise'
    );
    //private $allowed_roles = array('bar_admin');
    public function __construct() {
        parent::__construct();
        // Validate Login
        parent::checkLoggedin();
        $this->module_dir = APPPATH.'modules/'.$this->router->fetch_module();
        $this->load->config('config');
        $this->session->set_userdata('page_data', array('url' => $this->url, 'permissions' => $this->permissionValues));
        $this->load->model('Job_model');
        $this->load->library('m_pdf');
    }
    public function index() {
    // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
        add_js('assets/js/plugins/colResizable-1.6.min.js');
        
        $page_segment = 0;
        $default_uri = array('page','status','field','action','view');
        $uri = $this->uri->uri_to_assoc(4, $default_uri);
        $pegination_uri = array();
        $filter = array();
        $status = $uri['status'];
        if ($uri['page'] > 0) {
            $page = $uri['page'];
            //$page_segment = array_search('page', array_keys($uri));
        } else {
            $page = 0;
        }
        // Create the filters
       
        if ($uri['view'] <> "") {
            $filter['view'] 		= $uri['view'];
			$pegination_uri['view'] = $uri['view'];
        } else {
			$filter['view'] 		= 10;
			$pegination_uri['view'] = 10;
		}
        if ($uri['field'] <> "") {
            $filter['field'] = $uri['field'];
            $pegination_uri['field'] = $uri['field'];
        } else {
            $filter['field'] = "";
            $pegination_uri['field'] = "~";
        }

        if ($status <> '') {
            $filter['status'] = $status;
        } else {
            $status = 0;
        }
        // Get the total rows without limit
        $total_rows = $this->Job_model->get_billing_list($filter, null, null, true);
        //echo $total_rows;die;
        if($pegination_uri['field'] == "~")
        {
            $config = $this->init_pagination('App/billing/index/page/',5,$total_rows,$filter['view']);
        }
        else
        {
            $config = $this->init_pagination('App/billing/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/', 7,$total_rows,$filter['view']);
        }
        
        //$limit_end = ($page * $config['per_page']) - $config['per_page'];
        $limit_end 			= ($page * $filter['view']) - $filter['view'];
        if ($limit_end < 0){
            $limit_end = 0;
        }
        //$filter['limit'] = $config['per_page'];
        $filter['limit'] = $filter['view'];
        $filter['offset'] = $limit_end;
        // Overwite it for print
        /*if ($uri['action'] == "print" || $uri['action'] == "csv") {
            $uri['limit'] = -1;
        }*/
        // Get the jobs List
        $data['jobs'] = $this->Job_model->get_billing_list($filter, 'id', 'asc');
        

        $data['page_no'] = $page;
        //$data['filters'] = $uri;
        $data['filter'] = $filter;
        $data['page'] = 'Billing';
        $data['page_title'] = SITE_NAME.' :: Billing Management';
        if ($uri['action'] == "csv") {

            
            $this->load->helper('download');
            $delimiter = ',';
            if (count($data['jobs']) > 0) {

                $name = 'report_export_'.time().'.csv';

                // prepare the file
                $fp = fopen(DIR_PROFILE_PICTURE.$name, 'w+');

                // Save header
                $header = array_keys((array)$data['jobs'][0]);
                fputcsv($fp, $header, $delimiter);

                // Save data
                foreach ($data['jobs'] as $element) {
                    fputcsv($fp, (array)$element, $delimiter);
                }
                fclose($fp);

                $data = file_get_contents(DIR_PROFILE_PICTURE.$name); // Read the file's contents

                force_download($name, $data);
                exit;
            }
        }
        $data['main_content'] ='billing/list';
        $this->load->view(TEMPLATE_PATH, $data);
    }
    public function generate_csv()
    {
        $checked_id=$this->input->post('item_id[]');
       //print_r($checked_id);die;
       
        $filename = 'report_export_'.date('Ymd').'.csv';
        
        header("Content-Description: File Transfer");
        
        header("Content-Disposition: attachment; filename=$filename");
        
        header("Content-Type: application/csv; ");
        
        //header('Content-Transfer-Encoding: binary'); 
        
        //$this->load->model('Job_model');
        // get data
        $data['jobs'] = $this->Job_model->get_csv($checked_id);
        //echo "<pre>";
        //print_r($data['jobs']);die;
        // file creation
        //ob_start();
         $file = fopen('php://output', 'w');
        // //$file=fopen(DIR_PROFILE_PICTURE.$filename, 'w+');
       
        $delimiter = ',';
        if(!empty($data['jobs'][0]))
        {
            $header = array_keys($data['jobs'][0]);
            $header[1]="user";
            $header[5]="store";
            $header[8]="wine";
            fputcsv($file, $header,$delimiter);

            foreach ($data['jobs'] as $key=>$line){
            fputcsv($file,$line);
            }
        }
        fclose($file);
        
        exit;
    }
    public function moved_to_archive()
    {
        $checked_id=$this->input->post('checked_value');
        $archive=$this->Job_model->moved_to_archive($checked_id);
        if($archive)
            echo true;
        else
            echo false;
    }
    private function init_pagination($uri,$segment=7,$total_rows,$view=NULL) {

        $this->config->load('pagination');
        $this->load->library('pagination');
        $config = $this->config->item('pagination');
        $ci                          =& get_instance();
        $config['uri_segment']       = $segment;
        $config['base_url']          = base_url().$uri;
        $config['total_rows']        = $total_rows;
        $config['per_page']        		= $view;
        $ci->pagination->initialize($config);
        return $config;
   }
   public function view_details()
   {
        $this->load->model('Job_model');
        $job_id=$this->input->post('job_id');
        //Get additional information of billing and job
        $data['signature_and_comment']=$this->Job_model->get_signature_and_comment($job_id);
        $data['question_answer']=$this->Job_model->get_question_answer($job_id);
        $this->load->view('billing/details',$data);
   }
   public function more_info()
   {
        $this->load->model('Job_model');
        $job_id=$this->input->post('job_id');
        $data['more_job_info']=$this->Job_model->get_more_job_info($job_id);
        $this->load->view('billing/more_info',$data);
   }
   public function question_answers()
   {
        $this->load->model('Job_model');
        $job_id=$this->input->post('job_id');
        $data['question_answers']=$this->Job_model->get_question_answers_for_job($job_id);
        /*echo "<pre>";
        print_r($data['question_answers']);die;*/
        $this->load->view('billing/qa',$data);
   }
   public function search_submit() {

        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $s_field = $this->clean_value($this->input->post('field'));
            
            

            $url = "App/billing/index/";

            if ($s_field != '') {
                $url .= "field/". urlencode($s_field)."/";
            }


            redirect($url);
        }
    }
    private function clean_value($str) {

        $str = str_replace('/', '~', $str);
        return preg_replace('/[^A-Za-z0-9_@.\-~]/', '', $str);
    }
    public function download_invoice($billing_id=NULL)
    {
        //echo $billing_id;
        $this->load->model('Job_model');
        $job_id=$billing_id;
        $data['more_job_info']=$this->Job_model->get_more_job_info($job_id);
        $data['signature_and_comment']=$this->Job_model->get_signature_and_comment($job_id);
        //echo "<pre>";
        //print_r($data['more_job_info']);die;
        $data['question_answers']=$this->Job_model->get_question_answers_for_job($job_id);
        $html = $this->load->view('billing/Sample_invoice',$data, true);
        //this the the PDF filename that user will get to download
        $pdfFilePath = "invoice-".$data['more_job_info']->sampling_date.".pdf";

       //generate the PDF from the given html
        $this->m_pdf->pdf->WriteHTML($html);

        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "I");
        //$content = $this->m_pdf->pdf->Output('', 'S'); // Saving pdf to attach to email 
        //$content = chunk_split(base64_encode($content));
    }
    public function get_expenses_with_brand()
    {
        $this->load->model('Job_model');
        $job_id=$this->input->post('job_id');
        $data['expense_with_brand']=$this->Job_model->get_expense_with_brand($job_id);
        $data['expense_amount']=$this->Job_model->get_expense_amount($job_id);
        $data['more_job_info']=$this->Job_model->get_more_job_info($job_id);
        $this->load->view('billing/expense_with_brand',$data);
    }
    public function get_expenses_brandwise()
    {
        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $brand=$this->input->post('brand');
            $from_date=$this->input->post('from_date');
            $to_date=$this->input->post('to_date');
            $data['expense_details']=$this->Job_model->get_brandwise_expense($brand,$from_date,$to_date);
        }
        //Get brand
        $this->load->model('Job_model');
        $data['brand']=$this->Job_model->get_brand();
        
        $data['filter']=$this->input->post();
        $data['main_content'] ='billing/filter_expenses_brandwise';
        $this->load->view(TEMPLATE_PATH, $data);
    }
}