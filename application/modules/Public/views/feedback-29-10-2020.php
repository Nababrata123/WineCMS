
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" />
    <title>FEEDBACK</title>
    <style>
        .center-block {
            top:50%;
            left: 50%;
            width: 500px;
            height: 300px;
            transform: translate3d(-50%,-50%, 0);
            position: absolute;
            background-color: #FFFFFF;
            box-shadow: 2px 2px #DAF7A6;
            }
    </style>
</head>
<body style="background-color: #dff0d8;">




<?php 
    ob_start();
    include('index.php');
    ob_end_clean();
    $CI =& get_instance();
    $CI->load->database();
    $data=$_POST;
    //print_r($data);die;
    if(isset($data['job_id'])){
        $CI->db->select("*");
        $CI->db->from('job_rating');
        $CI->db->where('job_rating.job_id',$data['job_id']);
        $job_details =json_decode(json_encode($CI->db->get()->row()), true);
        if(isset($job_details['job_id'])){
            // we keep the existing value so we dont need to insert the new value 
        }else{
            $CI->db->insert('job_rating',$data);

            $CI->db->select("tasting_date,job_start_time,finish_time, taster_id, agency_taster_id, wine_id");
            $CI->db->from('job');
            $CI->db->where('id',$data['job_id']);
            $completedJobData=$CI->db->get()->row();
           // return $result;

            $samplingDate = $completedJobData->tasting_date;

            $jobStartTime = $completedJobData->job_start_time;
            if($completedJobData->agency_taster_id){
                $CI->db->select("CONCAT(last_name, ' ',first_name) as taster_name");
                $CI->db->from('users');
                $CI->db->where('users.id',$completedJobData->agency_taster_id);
                $tasterName=$CI->db->get()->row();
                //return $result;
               // $tasterName=$this->Job_model->getTasterName($completedJobData->agency_taster_id);
            }else{
                $CI->db->select("CONCAT(last_name, ' ',first_name) as taster_name");
                $CI->db->from('users');
                $CI->db->where('users.id',$completedJobData->taster_id);
                $tasterName=$CI->db->get()->row();
                //$tasterName=$this->Job_model->getTasterName($completedJobData->taster_id);
            }
            $tasterName=$tasterName->taster_name;
            $startTime=$completedJobData->job_start_time;
            $finish_time=$completedJobData->finish_time;

            //$wineNames=$CI->Job_model->get_wine_names($data['job_id']);

            $CI->db->select("user_id");
            $CI->db->from('job');
            $CI->db->where('id',$data['job_id']);
            $result=$CI->db->get()->row_array();
            $result= $result['user_id'];

            $CI->db->select("email, first_name");
            $CI->db->from('users');
            $CI->db->where('id',$result);
            $m_result=$CI->db->get()->row_array();
            $salesRepMailAddress = $m_result['email'];

            
            $CI->db->select("wine_id");
            $CI->db->from('completed_job_wine_details');
            $CI->db->where_in('job_id',$data['job_id']);
            $CI->db->order_by('wine_id','ASC');
            $result=$CI->db->get();
            $result= $result->result_array();
            $index=-1;
            for($indx=0;$indx< count($result);$indx++){
                $wine_id_array[++$index]=$result[$indx]['wine_id'];
            }

            $CI->db->select("wine.name,wine.id");
            $CI->db->from('wine');
            $CI->db->where_in('wine.id',$wine_id_array);
            $CI->db->order_by('wine.id','ASC');
            $result=$CI->db->get();
            $m_data=$result->result_array();
            

            $CI->db->select('image,wine_id');
            $CI->db->from('wine_images');
            $CI->db->where('is_deleted',0);
            $CI->db->where_in('wine_id',$wine_id_array);
            $CI->db->order_by('wine_id','ASC');
            $query_image=$CI->db->get();
            $query_image = $query_image->result_array();

            $CI->db->select('bottles_sold');
            $CI->db->from('completed_job_wine_details');
            $CI->db->where('job_id', $data['job_id']);
            $CI->db->order_by('wine_id','ASC');
            $sold_wine=$CI->db->get();
            $sold_wine=($sold_wine->result_array());

            $wine_name_array=array();
            $index=-1;
            for($indx=0;$indx< count($m_data);$indx++){
                ++$index;
                $wine_name_array[$index]['name']=$m_data[$indx]['name'];
                $wine_name_array[$index]['soldwine']=$sold_wine[$indx]['bottles_sold'];

                $wine_name_id=$m_data[$indx]['id'];

                $wine_img_url=BASE_URL('assets/images/dummy-wine.jpg');
                for($idx=0;$idx< count($query_image);$idx++){
                    if($wine_name_id==$query_image[$idx]['wine_id'])
                    {
                        $wine_img_url=BASE_URL.DIR_WINE_PICTURE.$query_image[$idx]['image'];
                       break;
                    }
        
                }
                $wine_name_array[$index]['image']=$wine_img_url;

                // if(count($query_image)>$indx){
                //     $wine_name_array[$index]['image']=BASE_URL.DIR_WINE_PICTURE.$query_image[$indx]['image'];
                //     //$wine_name_array[$index]['image']=BASE_URL.DIR_WINE_PICTURE.'expense-5122902520190124023236.jpg';
                // }else{
                //     $wine_name_array[$index]['image']=BASE_URL('assets/images/dummy-wine.jpg');
                // }
            }
            $wineNames = $wine_name_array;

            $CI->db->select('rating, feedback');
            $CI->db->from('job_rating');
            $CI->db->where('job_id', $data['job_id']);
            $job_rating_feedback=$CI->db->get();
            $job_rating_feedback=($job_rating_feedback->row_array());
            $rating=$job_rating_feedback['rating'];
            $feedback=$job_rating_feedback['feedback'];

            if(isset($rating)){
                $i=0;
                $stars='';
                for(;$i<$rating;++$i){
                    $stars.='✶ ';
                }
            }else{
                $stars='N/A';
            }

            if(!isset($feedback)){
                $feedback='N/A';
            }else{
                if (empty($feedback)){
                    $feedback='N/A';
                }
            }


           // print_r($job_rating_feedback);die;

            //$storeMangerMailAddress = $CI->Job_model->get_store_mail($data['job_id']);

            // prepare data for sales representative and mail send function 
            $samplingDate=date("F d, Y", strtotime($samplingDate));
            $dataforsalesrep = jobRatingMailTemplate($data['job_id'], $m_result['first_name'], $samplingDate, $tasterName, $startTime, $finish_time, $wineNames,  $stars, $feedback);
            $dataforadmin = jobRatingMailTemplate($data['job_id'], 'Admin', $samplingDate, $tasterName, $startTime, $finish_time, $wineNames,  $stars, $feedback);
            
            email_to_user($salesRepMailAddress, 'Wine Sampling - '.$samplingDate, $dataforsalesrep); 
            email_to_user('fraidy@thekgroupny.com', 'Wine Sampling - '.$samplingDate, $dataforadmin); //admin mail 
            //email_to_user('rr.avalgate@gmail.com', 'Wine Sampling - '.$samplingDate, $dataforadmin); //admin mail 
        }
    }




    function jobRatingMailTemplate($job_id, $manager_name, $samplingDate, $tasterName, $startTime, $finish_time, $wineNames, $stars, $feedback)
    {

        // $data = 'Hi ';
        // $data .= ucwords($manager_name) . '<br/><br/>';
        $data='';
        $data .= '<!DOCTYPE html>
        <html lang="en">
        <head>
          <title>Bootstrap Example</title>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
          <style>
          div.bdr {
            width: 91%;
            border: 5px solid #C48F29;
            /* border: 1px solid black; */
            /* margin: 40px; */
          }
          h4{font-size: 16px; margin-top:-7px; }
          .lbl{font-size: 16px;}
             h4.winesoldstyle{
                 margin-top:-8px;
             }
          table.no-spacing {
             border-spacing:0; /* Removes the cell spacing via CSS */
             border-collapse: collapse; 
           }

           /* body{
            margin: 0;
            padding: 0;
            background: #262626;
        } */
        .txt-center {
            text-align: center;
          }
          .hide {
            display: none;
          }
          
          .clear {
            float: none;
            clear: both;
          }
          
          .star-rating {
            direction: rtl;
            display: inline-block;
            padding: 20px
        }
        .star-rating input[type=radio] {
            display: none
        }
        
        .star-rating label {
            color: #bbb;
            font-size: 18px;
            padding: 0;
            cursor: pointer;
            -webkit-transition: all .3s ease-in-out;
            transition: all .3s ease-in-out
        }
        
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input[type=radio]:checked+label {
            color: #f2b600;
        }

        img {
            width: auto;
            max-width:90px;
          }

          #over img {
            margin-left: auto;
            margin-right: auto;
            display: block;
          }

          </style>
        </head>
        <body>
        
        <div class="container">
        <form>
         
         <div class="bdr"> 
         <input type="hidden" name="job_id" value="' . $job_id . '"/> 
         <input type="hidden" name="created_at" value="' . date('Y-m-d H:i:s') . '"/> 
         <div id="over" style="position:absolute; width:100%; height:100%">
            <img src="https://karosslive.east-coast-developer.pro/assets/wine/thumb/Wine_Logo.png">
            </div>
            <h1 class="text-center" style="text-align:center; margin:0px;">Tasting information:</h1>

         <table border="0" class="no-spacing" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
             <tbody>
                 <tr>
                     <td style="padding-right:18px;padding-left:18px">
                     <div style="height: 50px; overflow:hidden;">
                         <h4 style="padding: 5px 0px;font-size:16px;">Taster - <label class="font-weight-normal lbl">';
        $data .= $tasterName . '</label></h4></div>
                     </td>
                 </tr>
                 <tr>
                     <td valign="top" style="padding-right:18px; width:100%">
                     <table border="0" class="no-spacing" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
                     <tbody>
                         <tr>
                         <td valign="top" style="padding-right:18px;padding-left:18px ; width:23%">
                         <h4 style="font-size:16px;">Job start time - <label class="font-weight-normal lbl">';
        $data .= date("g:i a", strtotime($startTime)) . '</label></h4>
                     </td>
                     <td valign="top">
                      <h4 style="font-size:16px;">Job end time - <label class="font-weight-normal lbl">';
        $data .= date("g:i a", strtotime($finish_time)) . '</label></h4>    
                     </td>
                         </tr>
                         <tr>
                         <td style="padding-right:18px; padding-left: 16px;">
                         
                             <h4 style="margin-top:0px;">Wines sold:</h4> 
                         </td>
                     </tr>
                         </tbody>
                         </table>
                     </td>
                     
                 </tr>
                 
             </tbody>
         </table>';

        $tbl = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
                 <tbody>';
        if (!empty($wineNames)) {
            foreach ($wineNames as $wine) {
                $tbl .= '<tr><td valign="top" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px; width:10px;"><img style="height:70px;" src="' . $wine["image"] . '" /></td><td> <p style="margin-top:7px; font-size:14px ; font-weight:500; ">' . $wine["name"] . '&nbsp;&nbsp; - ' . $wine["soldwine"] . ' bottles sold </p></td></tr>';
            }
        }
        $data .= $tbl;
        $data .= '</tbody></table><hr style="margin-left:16px; margin-right:16px;">
           <div style="padding-left: 2%; padding-bottom: 2%;">
           <h1 class="text-center" style="text-align:center; margin:0px; ">Job rating</h1>
           <p class="text-center" style="text-align:center; color: #FFA500; margin:0px; font-size:30px;">'.$stars.'</p>
           <h1 class="text-center" style="text-align:center; margin:0px;">Store feedback</h1>
           <p class="text-center" style="text-align:center;  font-size:20px; margin:2px;">'.$feedback.'</p>
     
         </div>
         </br> 
            </br>     
          </br>
        </form>
          </div>
        </div>
        
        </body>
        </html>';
        // <table border="0" class="no-spacing" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
        // <tbody>
        //        <tr>
        //             <label style="font-size: 16px; font-weight:bold;">Job rating: 
        //         </tr>
        //         <tr>
        //             <label style="font-size: 18px; color: #FFA500;">'.$stars.'</label>
        //         </tr>
        //         <tr>
        //         <label style="font-size: 16px; font-weight:bold;">Store feedback: 
        //         </tr>
        //         <tr>
        //         <label style="font-size: 16px; font-weight: 500">'.$feedback.'</label>
        //         </tr>
        //     </tbody>
        //     </table> 
        return $data;
    }

    function email_to_user($to_email = NULL, $subject = NULL, $message = NULL)
    {
        
        $CI = &get_instance();
        $CI->load->library('mail_template');
        // $CI->lang->load('application');

        $from = $CI->lang->line('app_site_name') . " <" . NO_REPLY_EMAIL . ">";
        //SMTP & mail configuration
        $host = "ssl://smtp.gmail.com";
        $port = "465";
        $username = "noreply@karossonline.com";
        $password = "lAmQMI8enGzUDqd";

        $headers = array(
            'MIME-Version' => '1.0rn',
            'Content-Type' => "text/html; charset=ISO-8859-1rn",
            'From' => $from,
            'To' => $to_email,
            'Subject' => $subject,
        );

        $smtp = Mail::factory(
            'smtp',
            array(
                'host' => $host,
                'port' => $port,
                'auth' => true,
                'username' => $username,
                'password' => $password
            )
        );

        //$message .= "<p>Thank you,<br /><i><strong>" . $CI->lang->line('app_site_name') . "</strong></i></p>";
        $htmlContent =  $message;
        $smtp->send($to_email, $headers, $htmlContent);
    }

?>


    <div class="center-block" style="border: 5px solid #C48F29;">
            <h1 class="display-3" style="text-align: center; font-size:50px;font-family:sans-serif; color: #3c763d; margin: 0px;">&#10004;</h1>
            <h1 class="animate__animated animate__bounce display-3" style="text-align: center; font-size:50px;font-family:sans-serif; color: #337ab7; margin: 0px;">Thank </br>you!</h1>
            <h3 style="text-align: center; font-size:16px; font-family:sans-serif; color:#3c763d;">WE APPRECIATE YOUR FEEDBACK</h3>
            <h3 style="text-align: center; font-size:16px;font-family:sans-serif; color:#3c763d;">HAVE A WONDERFUL DAY</h3>
            <!-- <h1 class="animate__animated animate__bounce display-3" style="text-align: center; font-family:Georgia, 'Times New Roman', Times, serif;">Sorry! Feedback already submitted </h1> -->
    </div>
</body>

<script>



</script>
</html>
   
