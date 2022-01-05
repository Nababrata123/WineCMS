<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <style>
    div.bdr {
        width: 91%;
        border: 5px solid #C48F29;

        }
        img {
        width: auto;
        max-width:90px;
        }
        .stars-container {
        position: relative;
        display: inline-block;
        color: transparent;
        font-size:45px;
        }

        .stars-container:before {
        position: absolute;
        top: 0;
        left: 0;
        content: '★★★★★';
        color: lightgray;
        }

        .stars-container:after {
        position: absolute;
        top: 0;
        left: 0;
        content: '★★★★★';
        color: #DC9202;
        overflow: hidden;
        display: inline-flex;
        }

        .stars-0:after { width: 0%; }
        .stars-10:after { width: 10%; }
        .stars-20:after { width: 20%; }
        .stars-30:after { width: 30%; }
        .stars-40:after { width: 40%; }
        .stars-50:after { width: 50%; }
        .stars-60:after { width: 60%; }
        .stars-70:after { width: 70%; }
        .stars-80:after { width: 80%; }
        .stars-90:after { width: 90%; }
        .stars-100:after { width: 100; }

.borderless td, .borderless th {
    border: none;
}

  </style>
</head>
<body>

<div class="container">
       <form action="<?php echo base_url('Public/home/submit_rating'); ?>" method="POST">
        <div class="bdr"> 
        <input type="hidden" name="rating" value="<?php echo $rating;?>"/> 
		<input type="hidden" name="job_id" value="<?php echo $job_id;?>"/> 
		<input type="hidden" name="store_id" value="<?php echo $store_id;?>"/> 
		<input type="hidden" name="taster_id" value="<?php echo $taster_id;?>"/> 
        <input type="hidden" name="created_at" value=""/> 
        <div style="position:center; padding-left:45%; ">
           <img src="https://karosslive.east-coast-developer.pro/assets/wine/thumb/Wine_Logo.png">
           </div>
           <!-- <h1 class="text-center" style="text-align:center; margin:0px;">Tasting information:</h1> -->
        <!-- <table border="0" class="no-spacing" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
            <tbody>
                <tr>
                    <td style="padding-right:18px;padding-left:18px">
                    <div style="height: 50px; overflow:hidden;">
                        <h4 style="padding: 5px 0px;font-size:16px;">Taster - <label class="font-weight-normal lbl"><?php echo $tasterName?></label></h4></div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="padding-right:18px; width:100%">
                    <table border="0" class="no-spacing" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
                    <tbody>
                        <tr>
                        <td valign="top" style="padding-right:18px;padding-left:18px ; width:27%">
                        <h4 style="font-size:16px;">Job start time - <label class="font-weight-normal lbl"><?php echo $job_start_time?></label></h4>
                    </td>
                    <td valign="top">
                     <h4 style="font-size:16px;">Job end time - <label class="font-weight-normal lbl"><?php echo $finish_time?></label></h4>    
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
        </table>  -->

       <!-- <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
                <tbody>
                <?php foreach($wineNames as $wines) { ?>
                <tr>
                <td valign="top" style="padding-top:9px;padding-right:18px;padding-bottom:9px;padding-left:18px; width:10px;">
                <img style="height:70px;" src="<?php echo $wines['image']?>" />
                </td>
                <td> 
                <p style="margin-top:7px; font-size:14px ; font-weight:500; "><?php echo $wines['name']?> - <?php echo $wines['soldwine']?> bottles sold </p>
                </td>
                </tr>
                <?php }?>
            </tbody>
            </table> -->
            <hr style="margin-left:16px; margin-right:16px;">
          <h1 class="text-center" style="text-align:center;">Job Rating</h1>
          <p style="font-size:18px; text-align:center;margin:15px 5px"><i>Honesty is important, it will help us to improve the quality of the tastings.</i></p>
          <p style="font-size:18px; text-align:center;margin:15px 5px"><i>(this information will not be shared with the taster)</i></p><br>
          <div style="text-align:center;"> 
          <table border="0" class="no-spacing" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
          <tbody>
              <tr>
              <span class="stars-container stars-<?php echo $rating*20;?>">★★★★★</span>
            </tr>
           </tbody>
           </table>
        </div>
        </br>
        <div class="container" style="" >
      <h3 class="text-center">Please give us your feedback</h3>
    <textarea class="form-control" id="exampleFormControlTextarea1" name="feedback" rows="3" required></textarea><br>
    <div class="row">
    <div class="col text-center">
      <button class="btn btn-success" style="position:center; width:300px; height:40px" type="submit">Submit</button>
    </div>
	<?php
                 
                  
                  
                  if($this->session->flashdata('message_type')) {
                    if($this->session->flashdata('message')) {

                      echo '<div class="alert alert-'.$this->session->flashdata('message_type').' alert-dismissable">';
                      echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                      echo $this->session->flashdata('message');
                      echo '</div>';
                    }
                  }
                  
                  
              ?>
  </div>
</br>
  </div>
</form>
</div>
</div>

</body>
</html>
