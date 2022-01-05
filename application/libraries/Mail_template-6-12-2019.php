<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Mail.php";

class Mail_template {

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
        //$this->load->library('form_validation');
        $this->CI = &get_instance();
        $this->CI->load->library('email');

        $this->CI->lang->load('application');
    }


    public function new_user_email($name = NULL, $email = NULL, $password = NULL,$activation_link=NULL) {

        if ($email == NULL) {
            return false;
        }

        $message = "<p><strong>Dear ".$name.",</strong><br/></p>
                <p>You have been added as an user at ".$this->CI->lang->line('app_site_name')." with the following details.<br /><br />
                Email: ".$email."<br/>
                Password: ".$password."<br/><br /></p>";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";
                $message .= "<p>Follow the link below to login and change your password. </p>";
                $message .= "<p><a href='".$activation_link."' >".$activation_link."</a></p><br /><br />";

        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - New User', $message);
    }
    public function send_approved_job_email($name,$email,$content,$fullpath)
    {

        $message = "<p><strong>Dear ".$name.",</strong><br/></p>
                <p>This is the report of  todays approved jobs with the following details.</p>";

        //echo $message;die;
        $this->email_to_admin_with_csv($email, $name, $this->CI->lang->line('app_site_name').' - Approved jobs', $message,$content,$fullpath);
    }
    public function email_to_admin_with_csv($to_email = NULL, $name = NULL, $subject = NULL, $message = NULL,$content,$fullpath) {

        
        $from = $this->CI->lang->line('app_site_name')." <".NO_REPLY_EMAIL.">";

        //SMTP & mail configuration
        $host = "ssl://smtp.gmail.com";
        $port = "465";
        $username = "smtpmail362@gmail.com";
        $password = "kushalnopany1";

       
        // Arrays are much more readable
        
        $uid = md5(uniqid(time()));
        
        
        $multipartSep = md5(uniqid(time()));
        $headers = array (
            'MIME-Version' => '1.0',
            'Content-Type' => "multipart/mixed; boundary=$multipartSep",
            'Content-Disposition'=>"attachment;filename=$fullpath",
            'Pragma'=>"no-cache",
            'From' => $from,
            'To' => $to_email,
            'Subject' => $subject,
        );

        $smtp = Mail::factory('smtp',
            array (
                'host' => $host,
                'port' => $port,
                'auth' => true,
                'username' => $username,
                'password' => $password
            )
        );

        $message .= "--$multipartSep\r\n"
        . "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n"
        . "Content-Transfer-Encoding: 7bit\r\n"
        . "\r\n"
        . "$message\r\n"
        . "--$multipartSep\r\n"
        . "Content-Type: text/csv\r\n"
        . "Content-Transfer-Encoding: base64\r\n"
        . "Content-Disposition: attachment; filename=\"Approved_job.csv\"\r\n"
        . "\r\n"
        . "$content\r\n"
        . "--$multipartSep--";

        $message .= "<p>Thank you,<br /><i><strong>".$this->CI->lang->line('app_site_name')."</strong></i></p>";

        $header = '<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-Type" content="text/csv; charset=UTF-8">
<title></title>
<style>
/* -------------------------------------
    GLOBAL
------------------------------------- */
* {
  font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
  font-size: 100%;
  line-height: 1.6em;
  margin: 0;
  padding: 0;
}
img {
  width: auto;
}
body {
  -webkit-font-smoothing: antialiased;
  height: 100%;
  -webkit-text-size-adjust: none;
  width: 100% !important;
}
/* -------------------------------------
    ELEMENTS
------------------------------------- */
a {
  color: #348eda;
}
.padding {
  padding: 10px 0;
}
/* -------------------------------------
    BODY
------------------------------------- */
table.body-wrap {
  padding: 0;
  width: 100%;
  border: 5px solid #C48F29;
}
table.body-wrap .container {
  border: 1px solid #ccc;
}
/* -------------------------------------
    FOOTER
------------------------------------- */
table.footer-wrap {
  clear: both !important;
  width: 100%;
  margin-top:20px;
}
.footer-wrap .container p {
  color: #666666;
  font-size: 12px;

}
table.footer-wrap a {
  color: #999999;
}
/* -------------------------------------
    TYPOGRAPHY
------------------------------------- */
h1,
h2,
h3 {
  color: #111111;
  font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
  font-weight: 200;
  line-height: 1.2em;
  margin: 40px 0 10px;
}
h1 {
  font-size: 36px;
}
h2 {
  font-size: 28px;
}
h3 {
  font-size: 22px;
}
p,
ul,
ol {
  font-size: 14px;
  font-weight: normal;
  margin-bottom: 10px;
}
ul li,
ol li {
  margin-left: 5px;
  list-style-position: inside;
}
/* ---------------------------------------------------
    RESPONSIVENESS
------------------------------------------------------ */
/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
.container {
  clear: both !important;
  display: block !important;
  margin: 0 auto !important;
}
/* Set the padding on the td rather than the div for Outlook compatibility */
.body-wrap .container {
  padding: 20px;
}
/* This should also be a block element, so that it will fill 100% of the .container */
.content {
  display: block;
  /*margin: 0 auto;*/
}
/* Lets make sure tables in the content area are 100% wide */
.content table {
  width: 100%;
}

/**/
.logo-wrap {
  margin: 10px 0 0;
  width:100%;
  text-align:left;
}
.logo-wrap, .content, table.body-wrap, img{
  max-width: 600px;
}
</style>
</head>
<body>

<!-- body -->
<table class="body-wrap" cellpadding="0" cellspacing="0">
  <tr>
    <td class="container">
      <!-- content -->
      <div class="content">
      <table>
        <tr>
          <td>';

        $footer = '</td>
        </tr>
      </table>
      </div>
      <!-- /content -->
    </td>
    <td></td>
  </tr>
</table>
<!-- /body -->
<!-- footer -->
<table class="footer-wrap">
  <tr>
    <td></td>
    <td class="container">
      <!-- content -->
      <div class="content">
        <table>
          <tr>
            <td align="center">
              <p>
              </p>
            </td>
          </tr>
        </table>
      </div>
      <!-- /content -->
    </td>
    <td></td>
  </tr>
</table>
<!-- /footer -->
</body>
</html>';
        /*$this->CI->email->message($header.$message.$footer);
        $this->CI->email->send();*/
        $htmlContent = $header.$message.$footer;
        $mail = $smtp->send($to_email, $headers, $htmlContent);
        /*try{
          $mail = $smtp->send($to_email, $headers, $htmlContent);
          echo "<pre>";
          print_r($mail);die;
        }
        catch(Exception $e)
        { 
          echo $e->getMessage(); die;
        }*/

    }

    public function new_password_email($name = NULL, $email = NULL, $password = NULL) {

        if ($email == NULL) {
            return false;
        }

        $message = "<p><strong>Dear ".$name.",</strong><br/></p>
                <p>Your password have been reset at ".$this->CI->lang->line('app_site_name')." with the following details.<br /><br />
                Email: ".$email."<br/>
                Password: ".$password."<br/><br/></p>";
        $message .= "<p>Please try using the new password and you can also reset it.</p><br/>";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";

        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - Reset Password', $message);
    }

    public function password_reset_email($activation_link = NULL, $to_email = NULL) {

        $message = "<p><strong>Dear User, </strong></p>";
        $message .= "<p>A password reset request has been submitted on your behalf.</p>";
        $message .= "<p>If you feel that this has been done in error, delete and disregard this email.
        				Your account is still secure and no one has been given access to it.
        				It is not locked and your password has not been reset.
        				Someone could have mistakenly entered your email address. </p>";
        $message .= "<p>Follow the link below to login and change your password. </p>";

        $message .= "<p><a href='".$activation_link."' >".$activation_link."</a></p><br /><br />";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";
        //echo $message;die;

        $this->email_to_user($to_email, NULL, $this->CI->lang->line('app_site_name').' - Password Reset', $message);
    }
    
    public function email_activation_email($activation_link = NULL, $to_email = NULL) {

        $message = "<p><strong>Dear Customer, </strong></p>";
        $message .= "<p>Thank you for signing up with PHIT.</p>";
        $message .= "<p></p>";
        $message .= "<p>Follow the link below to activate your email address. </p>";

        $message .= "<p><a href='".$activation_link."' >".$activation_link."</a></p><br /><br />";

        $this->email_to_user($to_email, NULL, $this->CI->lang->line('app_site_name').' - Activate Email', $message);
    }

    public function bar_request_email($name = NULL, $bar_name = NULL, $status = NULL, $email = NULL) {

        if ($email == NULL) {
            return false;
        }

        $message = "<p><strong>Dear ".$name.",</strong><br/></p>
                <p>Your administrator request for ".$bar_name." has been ".$status." at ".$this->CI->lang->line('app_site_name').".<br /></p>";
        $message = "<p>Please login to view details<br /><br /></p>";

        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - New User', $message);
    }


    public function notification_email($name = NULL, $email = NULL, $message = NULL) {

      if ($email == NULL) {
          return false;
      }

      $message = "<p><strong>Dear ".$name.",</strong><br/></p>".$message;

      //echo $message;die;
      $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - Notification', $message);
    }

    //Assign job to tester or agency
    public function assigned_job_email($name = NULL, $email = NULL) {

        if ($email == NULL) {
            return false;
        }

        $message = "<p><strong>Dear ".$name.",</strong><br/></p>
                <p>You have assigned with a Job".$this->CI->lang->line('app_site_name')."<br/><br /></p>";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";

        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - Assign Job', $message);
    }
    //Cancell taster from job
    public function cancelled_job_email($name = NULL, $email = NULL) {

        if ($email == NULL) {
            return false;
        }

        $message = "<p><strong>Dear ".$name.",</strong><br/></p>
                <p>A job has been canceled by the admin".$this->CI->lang->line('app_site_name')."<br/><br /></p>";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";

        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - Canceled Job', $message);
    }
    //Job changes email
    public function job_change_email($name = NULL, $email = NULL,$tasting_date)
    {
      if ($email == NULL) {
            return false;
        }
        $message = "<p><strong>Dear ".$name.",</strong><br/></p>
                <p>Your assigned Job date has been changed"."<p>Job tasting date is:".$tasting_date."</p>".$this->CI->lang->line('app_site_name')."<br/><br /></p>";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";

        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - Change Job info', $message);
    }
    //Approve job and send notification email to new tester
    public function approve_job_email($name = NULL, $email = NULL) {

        if ($email == NULL) {
            return false;
        }

        $message = "<p><strong>Configuration ".$name.",</strong><br/></p>
                <p>You have assigned with a Job by admin <br/><br /></p>";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";

        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - Approve Job', $message);
    }
    //Send early job email to taster
    public function early_job_notification_email($name = NULL, $email = NULL) {

        if ($email == NULL) {
            return false;
        }

        $message = "<p><strong>Dear ".$name.",</strong><br/></p>
                <p>You have a job today<br/><br /></p>";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";

        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - Assign Job', $message);
    }
    //Send notification email for two hour
    public function job_notification_email_between_two_hour($name = NULL, $email = NULL,$start_time=NULL)
    {
        if ($email == NULL) {
            return false;
        }
        $start_time_display=date('h:i A', strtotime($start_time));
        $message = "<p><strong>Dear ".$name.",</strong><br/></p>
                <p>Today you have a Job at ".$start_time_display."<br/><br /></p>";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";

        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - Assign Job', $message);
    }
    //Send bulk schedule email
    public function bulk_schedule_notification_mail($name = NULL, $email = NULL,$tasting_date)
    {
        if ($email == NULL) {
            return false;
        }
        $date=date("j-F-Y",strtotime($tasting_date));
        $message = "<p><strong>Dear ".$name.",</strong><br/></p>
                <p> You have a Job at ".$date."<br/><br /></p>";
        $message .= "<p>Please assign tasters and wine for the job</p>";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";

        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - Bulk schedule notification', $message);
    }
    public function email_to_user($to_email = NULL, $name = NULL, $subject = NULL, $message = NULL) {

        /*$config['protocol'] = 'sendmail';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->CI->email->initialize($config);

        $this->CI->email->from(NO_REPLY_EMAIL, $this->CI->lang->line('app_site_name'), CONTACT_EMAIL);
        $this->CI->email->reply_to(CONTACT_EMAIL, $this->CI->lang->line('app_site_name'));
        $this->CI->email->to($to_email);

        $this->CI->email->subject($subject);*/
        $from = $this->CI->lang->line('app_site_name')." <".NO_REPLY_EMAIL.">";

        //SMTP & mail configuration
        $host = "ssl://smtp.gmail.com";
        $port = "465";
        $username = "smtpmail362@gmail.com";
        $password = "kushalnopany1";

        $headers = array (
            'MIME-Version' => '1.0rn',
            'Content-Type' => "text/html; charset=ISO-8859-1rn",
            'From' => $from,
            'To' => $to_email,
            'Subject' => $subject,
        );

        $smtp = Mail::factory('smtp',
            array (
                'host' => $host,
                'port' => $port,
                'auth' => true,
                'username' => $username,
                'password' => $password
            )
        );

        $message .= "<p>Thank you,<br /><i><strong>".$this->CI->lang->line('app_site_name')."</strong></i></p>";

        $header = '<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<style>
/* -------------------------------------
    GLOBAL
------------------------------------- */
* {
  font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
  font-size: 100%;
  line-height: 1.6em;
  margin: 0;
  padding: 0;
}
img {
  width: auto;
}
body {
  -webkit-font-smoothing: antialiased;
  height: 100%;
  -webkit-text-size-adjust: none;
  width: 100% !important;
}
/* -------------------------------------
    ELEMENTS
------------------------------------- */
a {
  color: #348eda;
}
.padding {
  padding: 10px 0;
}
/* -------------------------------------
    BODY
------------------------------------- */
table.body-wrap {
  padding: 0;
  width: 100%;
  border: 5px solid #C48F29;
}
table.body-wrap .container {
  border: 1px solid #ccc;
}
/* -------------------------------------
    FOOTER
------------------------------------- */
table.footer-wrap {
  clear: both !important;
  width: 100%;
  margin-top:20px;
}
.footer-wrap .container p {
  color: #666666;
  font-size: 12px;

}
table.footer-wrap a {
  color: #999999;
}
/* -------------------------------------
    TYPOGRAPHY
------------------------------------- */
h1,
h2,
h3 {
  color: #111111;
  font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
  font-weight: 200;
  line-height: 1.2em;
  margin: 40px 0 10px;
}
h1 {
  font-size: 36px;
}
h2 {
  font-size: 28px;
}
h3 {
  font-size: 22px;
}
p,
ul,
ol {
  font-size: 14px;
  font-weight: normal;
  margin-bottom: 10px;
}
ul li,
ol li {
  margin-left: 5px;
  list-style-position: inside;
}
/* ---------------------------------------------------
    RESPONSIVENESS
------------------------------------------------------ */
/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
.container {
  clear: both !important;
  display: block !important;
  margin: 0 auto !important;
}
/* Set the padding on the td rather than the div for Outlook compatibility */
.body-wrap .container {
  padding: 20px;
}
/* This should also be a block element, so that it will fill 100% of the .container */
.content {
  display: block;
  /*margin: 0 auto;*/
}
/* Lets make sure tables in the content area are 100% wide */
.content table {
  width: 100%;
}

/**/
.logo-wrap {
  margin: 10px 0 0;
  width:100%;
  text-align:left;
}
.logo-wrap, .content, table.body-wrap, img{
  max-width: 600px;
}
</style>
</head>
<body>

<!-- body -->
<table class="body-wrap" cellpadding="0" cellspacing="0">
  <tr>
    <td class="container">
      <!-- content -->
      <div class="content">
      <table>
        <tr>
          <td>';

        $footer = '</td>
        </tr>
      </table>
      </div>
      <!-- /content -->
    </td>
    <td></td>
  </tr>
</table>
<!-- /body -->
<!-- footer -->
<table class="footer-wrap">
  <tr>
    <td></td>
    <td class="container">
      <!-- content -->
      <div class="content">
        <table>
          <tr>
            <td align="center">
              <p>
              </p>
            </td>
          </tr>
        </table>
      </div>
      <!-- /content -->
    </td>
    <td></td>
  </tr>
</table>
<!-- /footer -->
</body>
</html>';
        /*$this->CI->email->message($header.$message.$footer);
        $this->CI->email->send();*/
        $htmlContent = $header.$message.$footer;
        $mail = $smtp->send($to_email, $headers, $htmlContent);
        /*try{
          $mail = $smtp->send($to_email, $headers, $htmlContent);
          echo "<pre>";
          print_r($mail);die;
        }
        catch(Exception $e)
        { 
          echo $e->getMessage(); die;
        }*/

    }


    public function que_ans_email($name = NULL, $question = NULL, $location = NULL) {

        $message = "<p><strong>Dear Admin,</strong><br/></p>
                <p>".$name." has been sent a question at ".$this->CI->lang->line('app_site_name')." with the following details.<br /><br />
                User Location: ".$location."<br/>
                Question: ".$question."<br/><br /></p>";
        //$message .= "<p>Thanks,<br /><i>".$this->CI->lang->line('app_site_name')." Team</i></p>";
        $email = Q_A_EMAIL;
        //echo $message;die;
        $this->email_to_user($email, $name, $this->CI->lang->line('app_site_name').' - New Question By User', $message);
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
