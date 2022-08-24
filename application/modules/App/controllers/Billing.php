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
        $this->load->model('Wine_model');
        $this->load->library('m_pdf');
    }
    public function index() {
    // Permission Checking
        parent::checkMethodPermission($this->permissionValues[$this->router->method]);
        add_js('assets/modules/'.$this->router->fetch_module().'/js/'.$this->router->fetch_module().'.js');
        add_js('assets/js/plugins/colResizable-1.6.min.js');
        
        $page_segment = 0;
        $default_uri = array('page','status','field','action','view','search_text');
        $uri = $this->uri->uri_to_assoc(4, $default_uri);
        $pegination_uri = array();
        $filter = array();
        $status = $uri['status'];

        if (isset($uri['search_text']) && trim(urldecode($uri['search_text'])) <> "") {
            //pegination_uri['status'] = $uri['status'];
            $filter['search_text'] = $uri['search_text'];
            $pegination_uri['search_text'] = $uri['search_text'];
        }
        else
        {
            $filter['search_text'] = "";
            $pegination_uri['search_text'] = "~";
        }

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
        // if($pegination_uri['field'] == "~")
        // {
        //     $config = $this->init_pagination('App/billing/index/page/',5,$total_rows,$filter['view']);
        // }
        // else
        // {
        //     $config = $this->init_pagination('App/billing/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/', 7,$total_rows,$filter['view']);
        // }
        
        // //$limit_end = ($page * $config['per_page']) - $config['per_page'];
        // $limit_end 			= ($page * $filter['view']) - $filter['view'];
        // if ($limit_end < 0){
        //     $limit_end = 0;
        // }
        // //$filter['limit'] = $config['per_page'];
        // $filter['limit'] = $filter['view'];
        // $filter['offset'] = $limit_end;
        // Overwite it for print
        /*if ($uri['action'] == "print" || $uri['action'] == "csv") {
            $uri['limit'] = -1;
        }*/
        // if($pegination_uri['field'] == "~")
        // {
        //     $config = $this->init_pagination('App/billing/index/page/',5,$total_rows,$filter['view']);
        // }
        // else
        // {
        //     $config = $this->init_pagination('App/billing/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/', 9,$total_rows,$filter['view']);
        //     //$config = $this->init_pagination('App/billing/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/', 7,$total_rows,$filter['view']);
        // }

        // $config = $this->init_pagination('App/billing/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/', 9,$total_rows,$filter['view']);


        if($pegination_uri['field'] == "~")
        {
            if( $pegination_uri['search_text']=='~'){
                $pegination_uri = \array_diff($pegination_uri, ["~"]);
               $config = $this->init_pagination('App/billing/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/',7,$total_rows,$filter['view']);
            }else{
                $pegination_uri = \array_diff($pegination_uri, ["~"]);
                $config = $this->init_pagination('App/billing/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/', 9,$total_rows,$filter['view']);
            }
        }
        else
        {
            $config = $this->init_pagination('App/billing/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/', 11,$total_rows,$filter['view']);
        }
        
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        $limit_end          = ($page * $filter['view']) - $filter['view'];
        if ($limit_end < 0){
            $limit_end = 0;
        }
        $filter['limit'] = $config['per_page'];
        //$filter['limit'] = $filter['view'];
        $filter['offset'] = $limit_end;
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
        $currenttab=$this->input->post('currenttab');
        $operation=$this->input->post('operation');
       // echo 'current tab:: '.$currenttab." Operation:: ".$operation.' ';
        if($operation=='delete'){
            //echo 'current tab:: '.$currenttab." Operation:: ".$operation.' '; die;
            $count = 0;
            $tableName=$this->tablename;
           // echo $tableName;die;
                foreach ($checked_id as $id=>$value) {
                        $this->Job_model->delete_jobs($tableName, $id);
                        ++$count;     
                }

                $msg = 'deleted.';
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' Job(s) successfully '.$msg);
                redirect('/App/'.$currenttab.'');
        }else{ 
        $filename = 'report_export_'.date('Ymd').'.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
        
        //header('Content-Transfer-Encoding: binary'); 
        
        //$this->load->model('Job_model');
        // get data
        $data['jobs'] = $this->Job_model->get_csv($checked_id);
       // echo "<pre>";
        //print_r($data['jobs']);die;
        // file creation
        //ob_start();
         $file = fopen('php://output', 'w');
        // //$file=fopen(DIR_PROFILE_PICTURE.$filename, 'w+');
       
        $delimiter = ',';
        if(!empty($data['jobs'][0]))
        {
            $header = array_keys($data['jobs'][0]);
            /*            $header[1]="user";
            $header[5]="store";
            $header[8]="wine";*/
            $header[0]="Vendor Number";
            $header[1]="Invoice Number";
            $header[2]="Invoice Date";
            $header[3]="Invoice Amount";
            $header[4]="Store Name";
            fputcsv($file, $header,$delimiter);
            foreach ($data['jobs'] as $key=>$line){
                fputcsv($file,$line);
            }
        }
        fclose($file);
        
        exit;
        }
    }

//     public function generate_csv()
//     {
//         $checked_id=$this->input->post('item_id[]');
//        //print_r($checked_id);die;
       
//         $filename = 'report_export_'.date('Ymd').'.csv';
//         header("Content-Description: File Transfer");
//         header("Content-Disposition: attachment; filename=$filename");
//         header("Content-Type: application/csv; ");
        
//         //header('Content-Transfer-Encoding: binary'); 
        
//         //$this->load->model('Job_model');
//         // get data
//         $data['jobs'] = $this->Job_model->get_csv($checked_id);
//        // echo "<pre>";
//         //print_r($data['jobs']);die;
//         // file creation
//         //ob_start();
//          $file = fopen('php://output', 'w');
//         // //$file=fopen(DIR_PROFILE_PICTURE.$filename, 'w+');
       
//         $delimiter = ',';
//         if(!empty($data['jobs'][0]))
//         {
//             $header = array_keys($data['jobs'][0]);
// /*            $header[1]="user";
//             $header[5]="store";
//             $header[8]="wine";*/
//             $header[0]="Vendor Number";
//             $header[1]="Invoice Number";
//             $header[2]="Invoice Date";
//             $header[3]="Invoice Amount";
//             $header[4]="Store Name";
//             fputcsv($file, $header,$delimiter);
//             foreach ($data['jobs'] as $key=>$line){
//                 fputcsv($file,$line);
//             }
//         }
//         fclose($file);
        
//         exit;
//     }
    public function moved_to_archive()
    {
        $checked_id=$this->input->post('checked_value');
        $archive=$this->Job_model->moved_to_archive($checked_id);
       
            $jobId_Array=explode(",",$checked_id);

            if($archive) {
                foreach($jobId_Array as $job_id){
               
                    $manager_name=$this->Job_model->getManagerName($job_id);
                    $manager_name=$manager_name[0]['manager_name'];
         
                    $completedJobData= $this->Job_model->get_completed_job_info($job_id);
                    $samplingDate = $completedJobData->tasting_date;
                    $samplingDate = date("F d, Y", strtotime($samplingDate));
         
                    $jobStartTime = $completedJobData->job_start_time;
                    if($completedJobData->agency_taster_id){
                        $tasterName=$this->Job_model->getTasterName($completedJobData->agency_taster_id);
                    }else{
                        $tasterName=$this->Job_model->getTasterName($completedJobData->taster_id);
                    }
                   $tasterName=$tasterName->taster_name;
                   $startTime=$completedJobData->job_start_time;
                   $finish_time=$completedJobData->finish_time;
                   $taster_feedBack = $this->Job_model->get_general_note($job_id);
                   $wineNames=$this->Job_model->get_mail_wine_names($job_id);
                 //   $storeMangerMailAddress = $this->Job_model->get_store_mail($job_id);
                   $store = $this->Job_model->get_store_name_mail($job_id);
                   $store_name = $store[0]['store_name'];
                   $store_address = $store[0]['store_address'];

                    $this->load->library('mail_template');

                    $salesrep_name = $this->Job_model->get_salesrep_name($completedJobData->user_id);

                    $salesIdArray = explode(',', $completedJobData->user_id);
                    $this->db->select("email, first_name, last_name");
                    $this->db->from('users');
                    $this->db->where_in('id',$salesIdArray);
                    $m_result=$this->db->get()->result_array();
                    
                    // $salesrep_name='';
                    foreach ($m_result as $res){
                        // $salesrep_name.=$res['first_name']." ".$res['last_name'];
                        $data=$this->archiveJobMailTemplate($job_id, $manager_name, $samplingDate, $tasterName, $startTime, $finish_time, $wineNames,$salesrep_name,$store_name,$store_address);
                        $this->mail_template->email_to_store($res['email'], 'Wine Move to archive - '.$samplingDate, $data);
                    }
                 }
                 echo true;
            }else{
                echo false;
            }
    
            // if($archive)
            //     echo true;
            // else
            //     echo false;

    }

    public function moved_to_billing()
    {
        $checked_id=$this->input->post('checked_value');
        $archive=$this->Job_model->moved_to_billing($checked_id);
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
       
        $taster = $this->input->post('search_by_taster');
       
        $sales_rep = $this->input->post('sales_rep');
     
        $agency = $this->input->post('ag_name');
       
       
        $tasterarray = array();
        if ($taster)
        {
            foreach ($taster as $value)
            {
                array_push($tasterarray,$value);
            }
        }
        $st='';
       
        foreach($tasterarray as $i)
        {
            $st.=$i."@";
        }
       
        $st=rtrim($st,"@");
       
        $store = $this->input->post('search_by_store');
      
        // $search_text = base64_encode($this->input->post('search_text'));
        // $search_text = $this->clean_value($search_text);
        $search_text = $this->input->post('search_text');
       
              if (strpos($search_text, '/') !== false) {
                $search_text = substr($search_text, 0, strpos($search_text, '/'));
              }
              if (strpos($search_text, '\'') !== false) {
                $search_text = substr($search_text, 0, strpos($search_text, '\''));
              }
              if (strpos($search_text, '%') !== false) {
                $search_text = substr($search_text, 0, strpos($search_text, '%'));
              }
       
        $url = "App/billing/index/";
       
       
        if ($s_field != '') {
        $url .= "field/". urlencode($s_field)."/";
          }
       
        if ($taster != '') {
            $url .= "taster/".$st."/";
         }
        if ($sales_rep != '') {
        $url .= "sales_rep/".$sales_rep."/";
        }
        if ($store != '') {
            $url .= "store/".$store."/";
        }
        if ($agency != '') {
            $url .= "agency/".$agency."/";
        }
        if ($search_text != '') {
           
            $url .= "search_text/". urlencode($search_text)."/";
            // $url .= "search_text/".$search_text."/";
        }
       // print_r($url);die;
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
        // $job_id=$this->input->post('checked_value');

        $this->load->model('Job_model');
        $job_id=$billing_id;
        $result_array = $this->Job_model->get_feedback_question_answer($job_id); 
        $data['number_of_tasters'] = '';
        $data['weather'] = '';
        $data['traffic'] = '';
        $data['store_environment'] = '';
        foreach ($result_array as $res){
            if ($res['question_id'] == 1){
                $data['number_of_tasters'] = $res['answer'];
            }else if($res['question_id'] == 2){
                $data['weather'] = $res['answer'];
            }else if($res['question_id'] == 3){
                $data['traffic'] = $res['answer'];
            }else if($res['question_id'] == 4){
                $data['store_environment'] = $res['answer'];
            }
        }

        $data['more_job_info']=$this->Job_model->get_more_job_info($job_id);
        $data['signature_and_comment']=$this->Job_model->get_signature_and_comment($job_id);

        $data['question_answers']=$this->Job_model->get_question_answers_for_job($job_id);
        $data['job_id']=$job_id;
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
        //Get brand
        $this->load->model('Job_model');
        $this->load->model('Users_model');

        $job_id=$this->input->post('job_id');
        $user_Id = $this->session->userdata('id');

        // print_r($user_Id);
        // print_r($job_id);die;

        if($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $brand=$this->input->post('brand');
            $from_date=$this->input->post('from_date');
            $to_date=$this->input->post('to_date');
            $taster=$this->input->post('taster');
            $agency=$this->input->post('agency');
            $store=$this->input->post('search_by_store');
            $sales_rep=$this->input->post('sales_rep');
            $wine_type=$this->input->post('wine_type');
            $size=$this->input->post('size');
            $month=$this->input->post('month');

            $brand_array = array();
            
            if ($this->session->userdata('role')=='brand_wise_users'){
                $userMeta = $this->Users_model->get_user_meta($user_Id);
                $brand_array = explode(",",$userMeta[0]->meta_value);
            }

            $data['expense_details']=$this->Job_model->get_brandwise_expense($brand, $from_date, $to_date, $taster, $agency, $store, $sales_rep, $wine_type, $size, $month, $brand_array);

        }

        // Create Month array..
        $month_array = array(["name" => "January", "id" => 1,],["name" => "February", "id" => 2,],["name" => "March", "id" => 3,],["name" => "April", "id" => 4,],["name" => "May", "id" => 5,],["name" => "June", "id" => 6,],["name" => "July", "id" => 7,],["name" => "August", "id" => 8,],["name" => "September", "id" => 9,],["name" => "October", "id" => 10,],["name" => "November", "id" => 11,],["name" => "December", "id" => 12,]);

        // Create Size array..
        $size_array = array("ML", "L", "GAL", "OZ");


        // Get users Brand Name and Wine Type for Report section..
        if ($this->session->userdata('role')=='brand_wise_users'){
            $userMeta = $this->Users_model->get_user_meta($user_Id);
        
            $brand_name_array = explode(",",$userMeta[0]->meta_value);
            $sales_rep_id = explode(",",$userMeta[1]->meta_value);
            $sales_rep_name = $this->Job_model->getTasterName($user_Id);
            $selected_brand =$this->Users_model->get_selected_brand($brand_name_array);
            $data['wine_type'] = $this->Users_model->get_selected_brand_wine($selected_brand);
            $data['brand']=$selected_brand;
   
            if(!empty($sales_rep_id[0])){
                $data['sales_rep_selected'] = 1;
                $data['sales_rep']=$this->Users_model->get_sales_rep($sales_rep_id);
            }else{
                $data['sales_rep']=$this->Job_model->get_sales_rep();
                $data['sales_rep_selected'] = 0;
            }

        }else{
            $data['brand']=$this->Job_model->get_brand();
            $data['wine_type'] = $this->Job_model->get_all_wine();
            $data['sales_rep']=$this->Job_model->get_sales_rep();
        }
       
        $data['store']=$this->Job_model->get_store();
        $data['month_array'] = $month_array;
        $data['wine_size'] = $this->Wine_model->getAll_WineUOM();
        $data['taster']=$this->Job_model->get_taster_for_report();
        $data['agency']=$this->Job_model->get_agency_for_report();

        $data['filter']=$this->input->post();
        $data['main_content'] ='billing/filter_expenses_brandwise';
     
        $this->load->view(TEMPLATE_PATH, $data);
    }

    public function generate_report_csv()
    {
        $checked_id=$this->input->post('item_id[]');
        $currenttab=$this->input->post('currenttab');
        $operation=$this->input->post('operation');

    //    echo 'current tab:: '.$currenttab." Operation:: ".$operation.' ';
        if($operation=='delete'){
            //echo 'current tab:: '.$currenttab." Operation:: ".$operation.' '; die;
            $count = 0;
            $tableName=$this->tablename;
           // echo $tableName;die;
                foreach ($checked_id as $id=>$value) {
                        $this->Job_model->delete_jobs($tableName, $id);
                        ++$count;     
                }

                $msg = 'deleted.';
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', '<strong>Well done!</strong> '.$count.' Job(s) successfully '.$msg);
                redirect('/App/'.$currenttab.'');
        }else{ 
        $filename = 'report_export_'.date('Ymd').'.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
    
        // get data
        // $data['jobs'] = $this->Job_model->get_csv_brandwise($checked_id);

  
        //ob_start();
         $file = fopen('php://output', 'w');
        // //$file=fopen(DIR_PROFILE_PICTURE.$filename, 'w+');
       
        $delimiter = ',';

        
        $header[0]='Tasting Date';
        $header[1]='Month';
        $header[2]='Brand';
        $header[3]='Wine Type';
        $header[4]='Size';
        $header[5]='Store Name';
        $header[6]='Taster';
        $header[7]='Agency';
        $header[8]='Sales Rep';
        $header[9]='Bottles Sampled';
        $header[10]='Bottles Sold';
        $header[11]='Total Cost';
       
        // $header = str_replace('"', '', $header);
        // $header = preg_replace('/(^|;)"([^"]+)";/','$1$2;',$header);
        fputcsv($file, $header,$delimiter, " ");
        if (count($checked_id) > 0)
        {
            foreach ($checked_id as $item_id){
                // print_r($item_id);die;
               $single_row = explode(",",$item_id);
                fputcsv($file,$single_row);
            }
        }
            
        fclose($file);
        
        exit;
        }
    }

    public function open_edit_job_modal()
    {
        $job_id=$this->input->post('job_id');
        //Get job details
        
        $data['job']  = $this->Job_model->job_details($job_id);
        $sales_rep_id=$data['job']->user_id;
        //get tester or agency
        $data['tester']=$this->Job_model->get_tester_or_agency($job_id);
       
        if($data['job']->job_state==2){
            $data['store']=json_decode(json_encode($this->Job_model->get_store()), true);
            // $data['store']= $this->Job_model->get_store_for_wine_tpye($sales_rep_id, $data['job']->wine_id);
            $data['expense_details']=$this->Job_model->get_expense_details($job_id);
             $data['general_note']=$this->Job_model->get_general_note($job_id);
             $data['sales_rep']=$this->Job_model->get_sales_rep();
             $data['salesrep_name'] = $this->Job_model->get_salesrep_name($sales_rep_id);
             $data['get_wine_info']=$this->Job_model->get_wines_sampled_sold_details($job_id);
             $data['get_wine_list']=json_decode(json_encode($this->Job_model->get_wine($data['job']->store_id)), true);
             $data['expence_amount']=$this->Job_model->get_expense_amount($job_id);
             $data['manager_verification_details']=$this->Job_model->get_manager_verification_details($job_id);

             $wineList = $data['get_wine_list'];
             $wineList_array = array();
             foreach($wineList as $wine){
                 array_push($wineList_array,$wine['id']);
             }
 
             $tastingWine = $data['get_wine_info'];
 
             $storeTypeChange = 0;
             foreach($tastingWine as $wine){ 
               
                 if (in_array($wine['id'], $wineList_array)){
                     ++$storeTypeChange;
                 }
             }
 
             if(count($tastingWine) != $storeTypeChange) {
                 $data['get_wine_info'] = array();
             }
    
            $this->load->view('billing/completed_edit_job_modal',$data);
        }
    }
    public function completed_edit_job($job_id=0)
    {
        
                $exp_reason=$this->input->post('exp_reason');
                $taster_id=$this->input->post('taster_id');
                $tasting_date=$this->input->post('tasting_date');

                $start_time_hour=$this->input->post('start_time_hour');
                $start_time_minute=$this->input->post('start_time_minute');
                $time_one=$this->input->post('time_one');

                $end_time_hour=$this->input->post('end_time_hour');
                $end_time_minute=$this->input->post('end_time_minute');
                $time_two=$this->input->post('time_two');

                $Comments=$this->input->post('Comments');
                $general_notes=$this->input->post('general_note');
                $exp_amount="$".$this->input->post('exp_amount');

                $wine=$this->input->post('wine');
                $bottles_sampled=$this->input->post('bottles_sampled');
                $open_bottles_sampled=$this->input->post('open_bottles_sampled');
                $bottles_sold=$this->input->post('bottles_sold');

                 // Merge Completed Wine Info and New Wine Info...
                 $completedWineInfo=$this->Job_model->get_wines_sampled_sold_details($job_id);

                 /*
                 foreach($completedWineInfo as $wineinfo){
 
                     if (in_array($wineinfo['id'], $wine))
                         {
                             // print_r("Found");die;
                         }
                         else
                         {
                             array_push($wine,$wineinfo['id']);
                             array_push($bottles_sampled,$wineinfo['bottles_sampled']);
                             array_push($open_bottles_sampled,$wineinfo['open_bottles_sampled']);
                             array_push($bottles_sold,$wineinfo['bottles_sold']);
                         }
                 }*/

                // print_r($open_bottles_sampled);die;

                    $actual_start_hour_min=$start_time_hour.":".$start_time_minute;
                    $actual_start_time = date("H:i", strtotime($actual_start_hour_min.$time_one));

                    $actual_end_hour_min=$end_time_hour.":".$end_time_minute;
                    $actual_end_time = date("H:i", strtotime($actual_end_hour_min.$time_two));
                    $dateDiff = intval((strtotime($actual_end_time)-strtotime($actual_start_time))/60);

                    $hours = intval($dateDiff/60);
                    $minutes = $dateDiff%60;
                    $working_hour = date("H:i", strtotime($hours.':'.$minutes));
                   
                    $mtasting_date = date("Y-m-d",strtotime($this->input->post('tasting_date')));
                     $job = array(
                    'tasting_date' => $mtasting_date,
                    'job_start_time' => htmlspecialchars($actual_start_time, ENT_QUOTES, 'utf-8'),
                    'finish_time' => htmlspecialchars($actual_end_time, ENT_QUOTES, 'utf-8'),
                    'working_hour' => $working_hour,
                    );
               // print_r( $job);die;
                $this->Job_model->update_job($this->tablename, 'id', $job_id, $job);

                $general_note_array=array(
                    'general_note'=>$general_notes
                );
                $this->db->where('job_id',$job_id);
                $this->db->update('general_notes',$general_note_array);

                // $manager_verification_array=array(
                //     'comment'=>$Comments
                // );
                // $this->db->where('job_id',$job_id);
                // $this->db->update('manager_verification_details',$manager_verification_array);

                // $expense_array=array(
                //     'exp_amount'=>$exp_amount,
                //     'exp_reason'=>$exp_reason
                // );
                // $this->db->where('job_id',$job_id);
                // $this->db->update('expense_details',$expense_array);
                $date=date("Y-m-d");
                $expense_array=array(
                    'taster_id'=>$taster_id,
                    'job_id'=>$job_id,
                    'exp_amount'=>$exp_amount,
                    'exp_reason'=>$exp_reason,
                    'date'=>$date
                );

                $data['expense_details']=$this->Job_model->get_expense_details($job_id);

                if(isset($data['expense_details'][0]['job_id']) && isset($data['expense_details'][0]['exp_amount']) && isset($data['expense_details'][0]['exp_reason'])){
                    $this->Job_model->update_data('expense_details','id', $data['expense_details'][0]['exp_id'], $expense_array);
                }else{
                    $this->db->insert('expense_details', $expense_array);
                }

                $this->db->select('wine_id, job_id');
                $this->db->from('completed_job_wine_details');
                $this->db->where('job_id',$job_id);
                $result=$this->db->get();
                $final_result=$result->result_array();
                // print_r($final_result);die;
                foreach($final_result as $fr){
                    $this -> db -> where('job_id', $fr['job_id']);
                    $this -> db -> where('wine_id', $fr['wine_id']);
                    $this -> db -> delete('completed_job_wine_details');
                }

                $i=0;
                foreach($wine as $w){
                    $bottleSampled = $bottles_sampled[$i];
                    $bottleSold = $bottles_sold[$i];
                    $openBottleSampled = $open_bottles_sampled[$i];

                    if ($bottleSampled == ''){
                        $bottleSampled = 0;
                    }  
                    if ($bottleSold == ''){
                        $bottleSold = 0;
                    }
                    if ($openBottleSampled == ''){
                        $openBottleSampled = 0;
                    }

                    $data=array('wine_id'=>$w, 'bottles_sampled'=> $bottleSampled, 'open_bottles_sampled'=>$openBottleSampled, 'bottles_sold'=>$bottleSold, 'job_id'=> $job_id, 'taster_id'=> $taster_id );
                    
                    // echo "<pre>";
                    // print_r($data);die;
                    $this->db->insert('completed_job_wine_details',$data);
                        ++$i;
                }

              
             redirect('App/billing');
        
    }

         // New Task Archive job Info email.
         public function archiveJobMailTemplate($job_id, $manager_name, $samplingDate, $tasterName, $startTime, $finish_time, $wineNames, $salesrep_name, $store_name, $store_address, $taster_feedBack)
         {
     
            if (empty($taster_feedBack)){
                 $taster_feedBack = 'N/A';
            }
     
            $data='';
            $data.='<!DOCTYPE html>
            <html lang="en">
                <head>
                    <title></title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    
                    <style>
                        .container{border: 5px solid #c48f29;width:70%;}
                        .logo{width:100px;}
                        .time{width:35%;}
                        .staricon{width:45%; display:block; margin-left:25%;}
                        .small{
                            font-size: 59%;
                            font-weight: 400;
                        }
                        .img-thumbnail {
                            padding: .25rem;
                            background-color: #fff;
                            border: 1px solid #dee2e6;
                            border-radius: .25rem;
                            max-width: 100%;
                            height: auto;
                        }
                        .size{
                            width:50px;
                        }
                        .size:hover {
                          color: white;
                        }
                        .wine{margin-left: 15px;
                            font-size: 16px;}
                        
                        
                        @media screen and (max-width: 600px) {
                            
                            .container{border: 5px solid #c48f29;width:100%;}
                            .logo{width:30%;}
                            .wine{margin-left: 15px;
                            font-size: 12px;}
                            .tim{font-size:14px;}
                        }
                    </style>
                </head>
                <body>
                    <center>
                        <div>
                            <table class="container">
                                <tr>
                                    <td colspan="3" style="text-align:center;">
                                         <center>
                                             <img src="'.BASE_URL.'assets/wine/thumb/Wine_Logo.png" width="100">
                                         </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:center;">
                                        <h1 style="margin-top:auto;">Tasting Information</h1>
                                    </td>
                                </tr>
                              <tr>
                                 <td colspan="3">
                                     <h3 style="margin-top:auto;">Tasting Date - '.$samplingDate.'</h3>
                                 </td>
                            </tr>
                                <tr>
                                     <td colspan="3">
                                         <h3 style="margin-top:auto;">Store name & address - '.$store_name.', '.$store_address.'</h3>
                                     </td>
                              </tr>
                              <tr>
                                     <td colspan="3">
                                         <h3 style="margin-top:auto;">Sales Rep - '.$salesrep_name.'</h3>
                                     </td>
                              </tr>
                                <tr>
                                    <td colspan="3">
                                        <h3 style="margin-top:auto;">Taster - '.$tasterName.'</h3>
                                    </td>
                                </tr>
                                <tr>
                                     <td colspan="3">
                                         <h3 style="margin-top:auto;">Taster Feedback: - '.$taster_feedBack.'</h3>
                                     </td>
                                 </tr>
                                <tr>
                                    <td width="100">
                                        <h3 style="margin-top:auto;" class="tim">Job Start Time - '.date("g:i a", strtotime($startTime)).'</h3>
                                    </td>
                                    <td width="100">
                                        <h3 style="margin-top:auto;" class="tim">Job End Time - '.date("g:i a", strtotime($finish_time)).'</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <h3 style="margin-top:auto;">Wine Info : </h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table>';
                                     if (!empty($wineNames)) {
                                         foreach ($wineNames as $wine) {
     
                                             if($wine["soldwine"] > 1){
                                                 $bottle_sold ='Bottles sold';
                                             }else{
                                                 $bottle_sold = 'Bottle sold';
                                             }
                                             if($wine["usedwine"] > 1){
                                                 $bottle_used ='Bottles used';
                                             }else{
                                                 $bottle_used = 'Bottle used';
                                             }
                                             if($wine["open_bottles_sampled"] > 1){
                                                 $bottle_sampled ='Opened bottles sampled';
                                             }else{
                                                 $bottle_sampled = 'Opened bottle sampled';
                                             }
                                         $data.='<tr>
                                                <td style="width:10%;">
                                                    <img style="max-width:100px;" src="'.$wine["image"].'" width="75">
                                                </td>
                                                <td>
                                                <p  class="wine">'. $wine["name"] .' - ' . $wine["soldwine"] . ' '.$bottle_sold.',  ' . $wine["usedwine"] . ' '.$bottle_used.',  ' . $wine["open_bottles_sampled"] . ' '.$bottle_sampled.'</p>
                                                </td>
                                            </tr>';
                                         }
                                     }
                                     $data.='</table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <hr style="border-top: 2px solid rgba(0,0,0,.1);">
                                    </td>
                                </tr>
                               
                            </table>
                        </div>
                    </center>
                </body>
            </html>';
            return $data;
         }

}