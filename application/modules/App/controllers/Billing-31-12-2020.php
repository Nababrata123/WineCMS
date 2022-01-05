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

        $config = $this->init_pagination('App/billing/index/'.$this->uri->assoc_to_uri($pegination_uri).'/page/', 9,$total_rows,$filter['view']);
        
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
    public function open_edit_job_modal()
    {
        $job_id=$this->input->post('job_id');
        //Get job details
        // echo $job_id;die;
        
        $data['job']  = $this->Job_model->job_details($job_id);
        $sales_rep_id=$data['job']->user_id;
        //get tester or agency
        $data['tester']=$this->Job_model->get_tester_or_agency($job_id);
       
        if($data['job']->job_state==2){
            $data['store']=json_decode(json_encode($this->Job_model->get_store()), true);
            $data['expense_details']=$this->Job_model->get_expense_details($job_id);
             $data['general_note']=$this->Job_model->get_general_note($job_id);
             $data['sales_rep']=$this->Job_model->get_sales_rep();
             $data['get_wine_info']=$this->Job_model->get_wines_sampled_sold_details($job_id);
             $data['get_wine_list']=json_decode(json_encode($this->Job_model->get_all_wine()), true);
             $data['expence_amount']=$this->Job_model->get_expense_amount($job_id);
             $data['manager_verification_details']=$this->Job_model->get_manager_verification_details($job_id);
            // echo "<pre>";
            //  print_r($data['get_wine_info']);die;
            $this->load->view('billing/completed_edit_job_modal',$data);
        }
    }
    public function completed_edit_job($job_id=0)
    {
        //echo "<pre>";
        //echo $job_id;die;
        //print_r($_POST);die;

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
                $bottles_sold=$this->input->post('bottles_sold');

                    $actual_start_hour_min=$start_time_hour.":".$start_time_minute;
                    $actual_start_time = date("H:i", strtotime($actual_start_hour_min.$time_one));

                    $actual_end_hour_min=$end_time_hour.":".$end_time_minute;
                    $actual_end_time = date("H:i", strtotime($actual_end_hour_min.$time_two));
                    $dateDiff = intval((strtotime($actual_end_time)-strtotime($actual_start_time))/60);

                    $hours = intval($dateDiff/60);
                    $minutes = $dateDiff%60;
                    $working_hour = date("H:i", strtotime($hours.':'.$minutes));
                    //$start = strtotime($actual_start_time);
                    //$end = strtotime($actual_end_time);
                    //$working_hour= date('h:i', $end - $start);
                    //echo $actual_start_time.'-:strt:- '.$hours.':'.$minutes.' end:'.$actual_end_time ;die;
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
                    $data=array('wine_id'=>$w, 'bottles_sampled'=> $bottles_sampled[$i], 'bottles_sold'=>$bottles_sold[$i], 'job_id'=> $job_id, 'taster_id'=> $taster_id );
                    $this->db->insert('completed_job_wine_details',$data);
                        ++$i;
                }

              
             redirect('App/billing');
        
    }

}