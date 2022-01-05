<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Invoice</title>
<style>
 body{ font-family:Verdana, Geneva, sans-serif; font-size:14px;}
 h2{ font-size:22px; line-height:26px; padding-bottom:20px;}
 h3{ font-size:16px; line-height:20px; color:#000; padding-bottom:10px;}
 th{ padding-bottom:20px;}
</style>
</head>

<body>
<table width="100%" cellpadding="0" cellspacing="0">
 <tr>
   <td><h2>ROYAL WINE CORP.</h2></td>
 </tr>
 <tr>
   <td><strong>TASTING INVOICE &amp; REPORT</strong></td>
 </tr>
 <tr>
   <td>&nbsp;</td>
 </tr>
 <tr>
   <td></td>
 </tr>
 <tr>
   <td>&nbsp;</td>
 </tr>
 <tr>
   <td>
     <table width="100%">
       <tr>
           <?php
            $date=date('m/d/Y', strtotime($more_job_info->sampling_date));
           ?>
         <td><table width="100%"><tr><td><strong><u><?php echo $date;?></u></strong></td></tr>
           <tr>
             <td>Date of Tasting:</td>
             </tr>
            <?php
             //Calculate hour and minute
            //  $actual_start_time=date("h:i", strtotime($more_job_info->job_start_time)).date('A', strtotime($job->job_start_time));
            //  $actual_end_time=date("h:i", strtotime($more_job_info->finish_time)).date('A', strtotime($job->finish_time));
             
            $actual_start_time=date("h:i A", strtotime($more_job_info->job_start_time));
            $actual_end_time=date("h:i A", strtotime($more_job_info->finish_time));
            
             $datetime1 = new DateTime($actual_start_time);
            $datetime2 = new DateTime($actual_end_time);
            $interval = $datetime1->diff($datetime2);
             
             $actual_taken_time=$interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
             ?>
           <tr>
             <td><strong><u><?php  echo $actual_start_time;?> - <?php  echo $actual_end_time;?> (<?php echo $actual_taken_time?>)</u></strong></td>
             </tr>
           <tr>
             <td>Time</td>
             </tr>
          </table></td>
         <td width="41%" valign="top"><table width="100%">
           <tr>
            <?php
            //generate invoice id using tasting date
                $array=explode("-",$more_job_info->sampling_date);
                $invoice_id=$array[0].$array[1].$array[2].'va';
            ?>
             <td><strong><u><?php echo $invoice_id;?></u></strong></td>
           </tr>
           <tr>
             <td>Invoice #:</td>
           </tr>
          </table></td>
        </tr>
       <tr>
         <td>&nbsp;</td>
         <td valign="top"></td>
       </tr>
       <tr>
         <td><strong><u><?php echo $more_job_info->store_name?>, <?php echo $more_job_info->store_adress?></u></strong><br />
          Name &amp; address of liquor store<br /><br /></td>
         <td valign="top"></td>
       </tr>
       <tr>
         <td width="59%"><strong><u><?php echo $more_job_info->sales_rep_name?></u></strong><br />
          Sales Rep (RWC)<br /></td>
         <td valign="top"></td>
       </tr>
       <tr>
         <td><strong><u><?php echo $more_job_info->taster_name?></u></strong><br />
          <strong><u>Agency:</u></strong></td>
         <td valign="top"></td>
       </tr>
      </table>
    </td>
 </tr>
 <tr>
   <td>&nbsp;</td>
 </tr>
 </table>

 
 
 
    <?php
        
        if(!empty($more_job_info->wine_sampled_details))
        {   
    ?>
    <table width="100%" cellpadding="5" cellspacing="0" border="1">
    <tr>
      <th width="25%" align="left">Brands Tasted</th>
      <th width="25%" align="center"># of Bottles Used</th>
      <th width="25%" align="center"># Bottles Sold</th>
      <th width="25%" align="center">Cost Per Tasting</th>
     </tr>
        <?php
            $total_sampled=0;
            $total_sold=0;
            $no_of_wine_sampled=count($more_job_info->wine_sampled_details);
            $amount=ltrim($more_job_info->expense_amount,"$");
            $amount_on_rate=ltrim($more_job_info->total_amount,"$");
            $actual_amount=$amount+$amount_on_rate;
            $individual_amount=number_format((float)$actual_amount/$no_of_wine_sampled, 2, '.', '');
            foreach($more_job_info->wine_sampled_details as $val)
            {
                $total_sampled+=$val['bottles_sampled'];
                $total_sold+=$val['bottles_sold'];
        ?>
             <tr>
                  <td ><?php echo $val['brand']?>&nbsp; <?php echo $val['size']?> ML</td>
                  <td align="center" ><?php echo $val['bottles_sampled']?></td>
                  <td align="center" ><?php echo $val['bottles_sold']?></td>
                  <td align="center" >$<?php echo $individual_amount;?></td>
             </tr>
        <?php
            }
        ?>
     <tr>
       <td><strong>TOTAL</strong></td>
       <td align="center" ><strong><?php echo $total_sampled;?></strong></td>
       <td align="center" ><strong><?php echo $total_sold;?></strong></td>
       <td align="center" >
           <strong>
               <?php 
                    
                    $actual_total_amount=number_format((float)$actual_amount, 2, '.', '');
                    echo "$".$actual_total_amount;
               ?>
           </strong>
         </td>
     </tr>
    </table>
    <?php
        }
        else
        {
            $amount=ltrim($more_job_info->expense_amount,"$");
            $amount_on_rate=ltrim($more_job_info->total_amount,"$");
            $actual_amount=$amount+$amount_on_rate;
           // $actual_total_amount=0;
            $actual_total_amount=number_format((float)$actual_amount, 2, '.', '');

            echo "No wine sampled";
        }
    ?>


 
 <table width="100%">
 <tr>
   <td>&nbsp;</td>
 </tr>
 <tr>
   <td>
    <table width="100%">
     <tr>
      <td width="50%"><strong><u><?php if($more_job_info->agency_taster_id!=0){
        echo $more_job_info->agency_taster_id;
      }else echo $more_job_info->taster_id;?></u></strong> 
      <br />Taster #</td>
      <td width="50%">
       <strong><u><?php echo $more_job_info->actual_taster_name;?></u></strong><br />
 	   Name of person conducting tasting
      </td>
     </tr>
     <tr>
       <td><strong><u><?php echo "$".$actual_total_amount;?></u></strong><br />
         Cost of Tasting - ENTER ABOVE THIS LINE<br /></td>
       <td>&nbsp;</td>
     </tr>
     <tr><td></td><td></td><td></td></tr>
     
     <tr>
       <td>Comments:</td>
       <td><?php if(!empty($signature_and_comment)){echo $signature_and_comment->comment;}else{echo "No comments";}?></td>
     </tr>
    </table>
   </td>
 </tr>
 <tr>
   <td>&nbsp;</td>
 </tr>
<tr>
	<td colspan="4">
		<table width="100%" cellpadding="5" cellspacing="0" border="1">
			<tr>
				<th width="50%" align="left">Other Expenses</th>
				<th width="50%" align="left">Cost</th>
			</tr>
			<?php 
			if($more_job_info->expense_amount!="$" && $more_job_info->expense_reason!=""){
			?>
			<tr>
				<td align="left"><?php echo $more_job_info->expense_reason;?></td>
				<td align="left"><?php echo $more_job_info->expense_amount;?></td>
			</tr>
			<?php
			}else{				
			?>
			<tr>
				<td align="center" colspan="2">Not Available</td>
			</tr>
			<?php
			}
			?>
		</table>
	</td>
 </tr>
 <tr>
   <td>&nbsp;</td>
 </tr>
 <tr>
   <td><h3>Additional info:
   </h3></td>
 </tr>
 <tr>
   <td>
    <table>
      
      <?php
        if(!empty($question_answers))
        {  
            $count=0;
            foreach($question_answers as $value)
            {
                $count+=1;
      ?>
      <?php
        if($count<=5){      
      ?>
      <tr>
          <td><strong><?php echo $value['question'].": ";?></strong> </td>
        <td><?php if($value['ans_text']!=''){echo $value['ans_text'];}else{echo 'No answer found';}?></td>
      </tr>
      <?php
        }
      ?>
      <?php
            }
        }
        else
        {
      ?>
        <tr>
        <td colspan="2"><strong>No question answer found</strong> </td>
        
      </tr>
      <?php
        }
        ?>
    </table>
   </td>
 </tr>
</table>
</body>
</html>
