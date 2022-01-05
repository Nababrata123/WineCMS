<!-- Modal content-->
<div class="modal-content" id='modal'>
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <legend>  <?php 
            echo $store_name;
            ?></legend>
      </div>
     <?php
      echo validation_errors();
      $attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
        echo form_open_multipart(base_url('App/job/create_billing_three/'.$job_id), $attributes);
     ?>
      <div class="modal-body">
      <form>
        <input type="hidden" name="job_id" value="<?php echo $job_id;?>">
        <input type="hidden" name="taster_id" value="<?php echo $taster_id;?>">
        <div class="form-group">
        <fieldset>
 <center><legend>Time management</legend></center>
 <?php
 $start_hour=date('h',strtotime($job_details->start_time));
 $end_hour=date('h',strtotime($job_details->end_time));
 $start_minute=date('i',strtotime($job_details->start_time));
 $end_minute=date('i',strtotime($job_details->end_time));

 $check_start_Hour=date('h A',strtotime($job_details->start_time));
 $check_end_hour=date('h A',strtotime($job_details->end_time));
 $check_start_minute=date('i',strtotime($job_details->start_time));
 $check_end_minute=date('i',strtotime($job_details->end_time));

 if( $check_start_Hour=='12 AM' && $check_start_minute=='00' ){
 $start_hour=$check_start_Hour;
 $start_minute='';
 
 }

 if( $check_end_hour=='12 AM' && $check_end_minute=='00' ){
 $end_hour=$check_end_hour;
 $end_minute='';

 }

 $am_pm=date('A',strtotime($job_details->start_time));
 
 // Actual Start and End time

 $actual_start_hour=date('h',strtotime($job_details->job_start_time));
 $actual_end_hour=date('h',strtotime($job_details->finish_time));
 $actual_start_minute=date('i',strtotime($job_details->job_start_time));
 $actual_end_minute=date('i',strtotime($job_details->finish_time));

 $check_actual_start_Hour=date('h A',strtotime($job_details->job_start_time));
 $check_actual_end_hour=date('h A',strtotime($job_details->finish_time));
 $check_actual_start_minute=date('i',strtotime($job_details->job_start_time));
 $check_actual_end_minute=date('i',strtotime($job_details->finish_time));

 if( $check_actual_start_Hour=='12 AM' && $check_actual_start_minute=='00' ){
 $actual_start_hour=$check_actual_start_Hour;
 $actual_start_minute='';
 
 }

 if( $check_actual_end_hour=='12 AM' && $check_actual_end_minute=='00' ){
 $actual_end_hour=$check_actual_end_hour;
 $actual_end_minute='';

 }

 $actual_am_pm=date('A',strtotime($job_details->job_start_time));


 // echo $start_hour.' '.$start_minute; die;
 // echo $end_hour.' '.$end_minute;die;
 
 ?>
 <?php 
 //echo $job_details->store_id;die;
 ?>
 <div class="col-md-12">
 <label class="col-sm-3 control-label" style="margin-bottom:15px; padding-top:0px;">Scheduled start time:</label>
 
 <div class="col-md-3">
 <label for="fname"><?php echo $start_hour; ?> : <?php echo $start_minute; ?> <?php echo $am_pm; ?></label>
 </div>

 <label class="col-sm-3 control-label" style="margin-bottom:15px; padding-top:0px;">Scheduled end time:</label>
 <div class="col-md-3">
 <label for="fname"><?php echo $end_hour; ?> : <?php echo $end_minute; ?> <?php echo $am_pm; ?></label>
 </div><br>
 </div>
 


 <div class="col-md-12">
 <label for="inputPhone" class="col-sm-3 control-label">Actual start time:</label>
 <!--<div class="col-sm-6">
 <input type="time" name="job_start_time" required class="form-control" value="<?php if($job_details->job_start_time!=''){echo date("h:i", strtotime($job_details->job_start_time));}?>">
 <div class="help-block with-errors"></div>
 </div>-->
 <div class="col-sm-3">
 <select name="start_time_hour" required class="form-control" id="start_time_hour">
 <option value="">Hour</option>
 <option value="01" <?php if($actual_start_hour=="01"){echo "selected";}?> >01</option>
 <option value="02" <?php if($actual_start_hour=="02"){echo "selected";}?>>02</option>
 <option value="03" <?php if($actual_start_hour=="03"){echo "selected";}?>>03</option>
 <option value="04" <?php if($actual_start_hour=="04"){echo "selected";}?>>04</option>
 <option value="05" <?php if($actual_start_hour=="05"){echo "selected";}?>>05</option>
 <option value="06" <?php if($actual_start_hour=="06"){echo "selected";}?>>06</option>
 <option value="07" <?php if($actual_start_hour=="07"){echo "selected";}?>>07</option>
 <option value="08" <?php if($actual_start_hour=="08"){echo "selected";}?>>08</option>
 <option value="09" <?php if($actual_start_hour=="09"){echo "selected";}?>>09</option>
 <option value="10" <?php if($actual_start_hour=="10"){echo "selected";}?>>10</option>
 <option value="11" <?php if($actual_start_hour=="11"){echo "selected";}?>>11</option>
 <option value="12" <?php if($actual_start_hour=="12"){echo "selected";}?>>12</option>

 </select>
 </div>
 <div class="col-sm-3">
 <select name="start_time_minute" required class="form-control" id="start_time_minute">
 <option value="">Minute</option>
 <option value="00" <?php if($actual_start_minute=="00"){echo "selected";}?>>00</option>
 <option value="01" <?php if($actual_start_minute=="01"){echo "selected";}?>>01</option>
 <option value="02" <?php if($actual_start_minute=="02"){echo "selected";}?>>02</option>
 <option value="03" <?php if($actual_start_minute=="03"){echo "selected";}?>>03</option>
 <option value="04" <?php if($actual_start_minute=="04"){echo "selected";}?>>04</option>
 <option value="05"<?php if($actual_start_minute=="05"){echo "selected";}?>>05</option>
 <option value="06" <?php if($actual_start_minute=="06"){echo "selected";}?>>06</option>
 <option value="07" <?php if($actual_start_minute=="07"){echo "selected";}?>>07</option>
 <option value="08" <?php if($actual_start_minute=="08"){echo "selected";}?>>08</option>
 <option value="09" <?php if($actual_start_minute=="09"){echo "selected";}?>>09</option>
 <option value="10" <?php if($actual_start_minute=="10"){echo "selected";}?>>10</option>
 <option value="11" <?php if($actual_start_minute=="11"){echo "selected";}?>>11</option>
 <option value="12" <?php if($actual_start_minute=="12"){echo "selected";}?>>12</option>
 <option value="13" <?php if($actual_start_minute=="13"){echo "selected";}?>>13</option>
 <option value="14" <?php if($actual_start_minute=="14"){echo "selected";}?>>14</option>
 <option value="15" <?php if($actual_start_minute=="15"){echo "selected";}?>>15</option>
 <option value="16" <?php if($actual_start_minute=="16"){echo "selected";}?>>16</option>
 <option value="17" <?php if($actual_start_minute=="17"){echo "selected";}?>>17</option>
 <option value="18" <?php if($actual_start_minute=="18"){echo "selected";}?>>18</option>
 <option value="19" <?php if($actual_start_minute=="19"){echo "selected";}?>>19</option>
 <option value="20" <?php if($actual_start_minute=="20"){echo "selected";}?>>20</option>
 <option value="21" <?php if($actual_start_minute=="21"){echo "selected";}?>>21</option>
 <option value="22" <?php if($actual_start_minute=="22"){echo "selected";}?>>22</option>
 <option value="23" <?php if($actual_start_minute=="23"){echo "selected";}?>>23</option>
 <option value="24" <?php if($actual_start_minute=="24"){echo "selected";}?>>24</option>
 <option value="25" <?php if($actual_start_minute=="25"){echo "selected";}?>>25</option>
 <option value="26" <?php if($actual_start_minute=="26"){echo "selected";}?>>26</option>
 <option value="27" <?php if($actual_start_minute=="27"){echo "selected";}?>>27</option>
 <option value="28" <?php if($actual_start_minute=="28"){echo "selected";}?>>28</option>
 <option value="29" <?php if($actual_start_minute=="29"){echo "selected";}?>>29</option>
 <option value="30" <?php if($actual_start_minute=="30"){echo "selected";}?>>30</option>
 <option value="31" <?php if($actual_start_minute=="31"){echo "selected";}?>>31</option>
 <option value="32" <?php if($actual_start_minute=="32"){echo "selected";}?>>32</option>
 <option value="33" <?php if($actual_start_minute=="33"){echo "selected";}?>>33</option>
 <option value="34" <?php if($actual_start_minute=="34"){echo "selected";}?>>34</option>
 <option value="35" <?php if($actual_start_minute=="35"){echo "selected";}?>>35</option>
 <option value="36" <?php if($actual_start_minute=="36"){echo "selected";}?>>36</option>
 <option value="37" <?php if($actual_start_minute=="37"){echo "selected";}?>>37</option>
 <option value="38" <?php if($actual_start_minute=="38"){echo "selected";}?>>38</option>
 <option value="39" <?php if($actual_start_minute=="39"){echo "selected";}?>>39</option>
 <option value="40" <?php if($actual_start_minute=="40"){echo "selected";}?>>40</option>
 <option value="41" <?php if($actual_start_minute=="41"){echo "selected";}?>>41</option>
 <option value="42" <?php if($actual_start_minute=="42"){echo "selected";}?>>42</option>
 <option value="43" <?php if($actual_start_minute=="43"){echo "selected";}?>>43</option>
 
 <option value="44" <?php if($actual_start_minute=="44"){echo "selected";}?>>44</option>
 <option value="45" <?php if($actual_start_minute=="45"){echo "selected";}?>>45</option>
 <option value="46" <?php if($actual_start_minute=="46"){echo "selected";}?>>46</option>
 <option value="47" <?php if($actual_start_minute=="47"){echo "selected";}?>>47</option>
 <option value="48" <?php if($actual_start_minute=="48"){echo "selected";}?>>48</option>
 <option value="49" <?php if($actual_start_minute=="49"){echo "selected";}?>>49</option>
 <option value="50" <?php if($actual_start_minute=="50"){echo "selected";}?>>50</option>
 <option value="51" <?php if($actual_start_minute=="51"){echo "selected";}?>>51</option>
 <option value="52" <?php if($actual_start_minute=="52"){echo "selected";}?>>52</option>
 <option value="53" <?php if($actual_start_minute=="53"){echo "selected";}?>>53</option>
 <option value="54" <?php if($actual_start_minute=="54"){echo "selected";}?>>54</option>
 <option value="55" <?php if($actual_start_minute=="55"){echo "selected";}?>>55</option>
 <option value="56" <?php if($actual_start_minute=="56"){echo "selected";}?>>56</option>
 <option value="57" <?php if($actual_start_minute=="57"){echo "selected";}?>>57</option>
 <option value="58" <?php if($actual_start_minute=="58"){echo "selected";}?>>58</option>
 <option value="59" <?php if($actual_start_minute=="59"){echo "selected";}?>>59</option>
 

 </select>
 </div>
 <div class="col-sm-3">
 <select id="first_am_pm_dropdown" name="time_one" required class="form-control">

 <option value="am" <?php if(date('A', strtotime($job_details->job_start_time))=='AM'){echo "selected";
 
 }?>>am</option>
 <option value="pm" <?php if(date('A', strtotime($job_details->job_start_time))=='PM'){echo "selected";}?>>pm</option>
 </select>
 </div>
 </div>
 <div class="col-md-12">
 <label for="inputPhone" class="col-sm-3 control-label">Actual end time:</label>
 <!--<div class="col-sm-6">
 <input type="time" name="finish_time" required class="form-control" value="<?php if($job_details->finish_time!=''){echo date("h:i", strtotime($job_details->finish_time));}?>"> 
 <div class="help-block with-errors"></div>
 </div>-->
 <div class="col-sm-3">
 <select name="end_time_hour" required class="form-control" id="end_time_hour">
 <option value="">Hour</option>
 <option value="01" <?php if($actual_end_hour=="01"){echo "selected";}?> >01</option>
 <option value="02" <?php if($actual_end_hour=="02"){echo "selected";}?>>02</option>
 <option value="03" <?php if($actual_end_hour=="03"){echo "selected";}?>>03</option>
 <option value="04" <?php if($actual_end_hour=="04"){echo "selected";}?>>04</option>
 <option value="05" <?php if($actual_end_hour=="05"){echo "selected";}?>>05</option>
 <option value="06" <?php if($actual_end_hour=="06"){echo "selected";}?>>06</option>
 <option value="07" <?php if($actual_end_hour=="07"){echo "selected";}?>>07</option>
 <option value="08" <?php if($actual_end_hour=="08"){echo "selected";}?>>08</option>
 <option value="09" <?php if($actual_end_hour=="09"){echo "selected";}?>>09</option>
 <option value="10" <?php if($actual_end_hour=="10"){echo "selected";}?>>10</option>
 <option value="11" <?php if($actual_end_hour=="11"){echo "selected";}?>>11</option>
 <option value="12" <?php if($actual_end_hour=="12"){echo "selected";}?>>12</option>

 </select>
 </div>
 <div class="col-sm-3">
 <select name="end_time_minute" required class="form-control" id="end_time_minute">
 <option value="">Minute</option>
 <option value="00" <?php if($actual_end_minute=="00"){echo "selected";}?>>00</option>
 <option value="01" <?php if($actual_end_minute=="01"){echo "selected";}?>>01</option>
 <option value="02" <?php if($actual_end_minute=="02"){echo "selected";}?>>02</option>
 <option value="03" <?php if($actual_end_minute=="03"){echo "selected";}?>>03</option>
 <option value="04" <?php if($actual_end_minute=="04"){echo "selected";}?>>04</option>
 <option value="05"<?php if($actual_end_minute=="05"){echo "selected";}?>>05</option>
 <option value="06" <?php if($actual_end_minute=="06"){echo "selected";}?>>06</option>
 <option value="07" <?php if($actual_end_minute=="07"){echo "selected";}?>>07</option>
 <option value="08" <?php if($actual_end_minute=="08"){echo "selected";}?>>08</option>
 <option value="09" <?php if($actual_end_minute=="09"){echo "selected";}?>>09</option>
 <option value="10" <?php if($actual_end_minute=="10"){echo "selected";}?>>10</option>
 <option value="11" <?php if($actual_end_minute=="11"){echo "selected";}?>>11</option>
 <option value="12" <?php if($actual_end_minute=="12"){echo "selected";}?>>12</option>
 <option value="13" <?php if($actual_end_minute=="13"){echo "selected";}?>>13</option>
 <option value="14" <?php if($actual_end_minute=="14"){echo "selected";}?>>14</option>
 <option value="15" <?php if($actual_end_minute=="14"){echo "selected";}?>>15</option>
 <option value="16" <?php if($actual_end_minute=="16"){echo "selected";}?>>16</option>
 <option value="17" <?php if($actual_end_minute=="17"){echo "selected";}?>>17</option>
 <option value="18" <?php if($actual_end_minute=="18"){echo "selected";}?>>18</option>
 <option value="19" <?php if($actual_end_minute=="19"){echo "selected";}?>>19</option>
 <option value="20" <?php if($actual_end_minute=="20"){echo "selected";}?>>20</option>
 <option value="21" <?php if($actual_end_minute=="21"){echo "selected";}?>>21</option>
 <option value="22" <?php if($actual_end_minute=="22"){echo "selected";}?>>22</option>
 <option value="23" <?php if($actual_end_minute=="23"){echo "selected";}?>>23</option>
 <option value="24" <?php if($actual_end_minute=="24"){echo "selected";}?>>24</option>
 <option value="25" <?php if($actual_end_minute=="25"){echo "selected";}?>>25</option>
 <option value="26" <?php if($actual_end_minute=="26"){echo "selected";}?>>26</option>
 <option value="27" <?php if($actual_end_minute=="27"){echo "selected";}?>>27</option>
 <option value="28" <?php if($actual_end_minute=="28"){echo "selected";}?>>28</option>
 <option value="29" <?php if($actual_end_minute=="29"){echo "selected";}?>>29</option>
 <option value="30" <?php if($actual_end_minute=="30"){echo "selected";}?>>30</option>
 <option value="31" <?php if($actual_end_minute=="31"){echo "selected";}?>>31</option>
 <option value="32" <?php if($actual_end_minute=="32"){echo "selected";}?>>32</option>
 <option value="33" <?php if($actual_end_minute=="33"){echo "selected";}?>>33</option>
 <option value="34" <?php if($actual_end_minute=="34"){echo "selected";}?>>34</option>
 <option value="35" <?php if($actual_end_minute=="35"){echo "selected";}?>>35</option>
 <option value="36" <?php if($actual_end_minute=="36"){echo "selected";}?>>36</option>
 <option value="37" <?php if($actual_end_minute=="37"){echo "selected";}?>>37</option>
 <option value="38" <?php if($actual_end_minute=="38"){echo "selected";}?>>38</option>
 <option value="39" <?php if($actual_end_minute=="39"){echo "selected";}?>>39</option>
 <option value="40" <?php if($actual_end_minute=="40"){echo "selected";}?>>40</option>
 <option value="41" <?php if($actual_end_minute=="41"){echo "selected";}?>>41</option>
 <option value="42" <?php if($actual_end_minute=="42"){echo "selected";}?>>42</option>
 <option value="43" <?php if($actual_end_minute=="43"){echo "selected";}?>>43</option>
 
 <option value="44" <?php if($actual_end_minute=="44"){echo "selected";}?>>44</option>
 <option value="45" <?php if($actual_end_minute=="45"){echo "selected";}?>>45</option>
 <option value="46" <?php if($actual_end_minute=="46"){echo "selected";}?>>46</option>
 <option value="47" <?php if($actual_end_minute=="47"){echo "selected";}?>>47</option>
 <option value="48" <?php if($actual_end_minute=="48"){echo "selected";}?>>48</option>
 <option value="49" <?php if($actual_end_minute=="49"){echo "selected";}?>>49</option>
 <option value="50" <?php if($actual_end_minute=="50"){echo "selected";}?>>50</option>
 <option value="51" <?php if($actual_end_minute=="51"){echo "selected";}?>>51</option>
 <option value="52" <?php if($actual_end_minute=="52"){echo "selected";}?>>52</option>
 <option value="53" <?php if($actual_end_minute=="53"){echo "selected";}?>>53</option>
 <option value="54" <?php if($actual_end_minute=="54"){echo "selected";}?>>54</option>
 <option value="55" <?php if($actual_end_minute=="55"){echo "selected";}?>>55</option>
 <option value="56" <?php if($actual_end_minute=="56"){echo "selected";}?>>56</option>
 <option value="57" <?php if($actual_end_minute=="57"){echo "selected";}?>>57</option>
 <option value="58" <?php if($actual_end_minute=="58"){echo "selected";}?>>58</option>
 <option value="59" <?php if($actual_end_minute=="59"){echo "selected";}?>>59</option>

 </select>
 </div>
 <div class="col-sm-3">
 <select id="second_am_pm_dropdown" name="time_two" required class="form-control">
 <?php if($actual_am_pm =='PM'){?> <option value="pm" <?php if(date('A', strtotime($job_details->finish_time))=='PM'){echo "selected";}?>>pm</option> <?php } 
 else {?>
 <option value="am" <?php if(date('A', strtotime($job_details->finish_time))=='AM'){echo "selected";}?>>am</option>
 <option value="pm" <?php if(date('A', strtotime($job_details->finish_time))=='PM'){echo "selected";}?>>pm</option>
 <?php }?> 
 </select>
 </div>
 </div>
 </fieldset>
          <fieldset>
          <center><legend>Expense details</legend></center>
          <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">Expense amount:</label>
            
            <div class="col-sm-8">
              <input type="text" id="exp_amount" name="exp_amount" min="0"  class="form-control number" value="<?php if(isset($expense_details[0]['exp_amount'])){echo ltrim($expense_details[0]['exp_amount'],'$');}?>">
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">Expense reason:</label>
            <div class="col-sm-8">
              <textarea id="exp_reason" name="exp_reason" rows="4" cols="30"  class="form-control"><?php if(isset($expense_details[0]['exp_reason'])){echo ltrim($expense_details[0]['exp_reason'],"$");}?></textarea>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">Receipt:</label>
            <div class="col-sm-8">
              <input type="file" id="receipt_file" name="expense_images[]"  class="form-control" multiple="multiple">
              <?php

                $value_img=get_receipt_images($job_id);
                if(!empty($value_img))
                {
                  foreach($value_img as $img){
                  ?>
                  <input type="hidden" name="old_exp_image[]" value="<?php echo $img['images'];?>">
                  <?php
                    
                  }
                }
                else
                {
              ?>
              <input type="hidden" name="old_exp_image[]" value="">
              <?php    
                }

              ?>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-12"> 
          <?php if(isset($expense_details[0]['support_imgs']))
          {
          ?>
          <?php 
          if(count($expense_details[0]['support_imgs'])>0)
          foreach( $expense_details[0]['support_imgs']  as $imgindx){?>
          
            <?php echo '<div class="col-sm-2"><img data-enlargeable style="cursor: zoom-in; height: 100px; width: 100px;" src="'.BASE_URL.DIR_EXPENSE_IMAGE.$imgindx.'"> </div>'; ?>
          
          <?php } } ?>

          </div>

        </fieldset>
        </div>
       
          <!-- <div class="form-group">
          <fieldset>
          <center><legend>Manager verification details</legend></center>
          <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">First name:</label>
            <div class="col-sm-8">
              <input type="text" name="first_name" required class="form-control" value="<?php if(isset($manager_verification_details[0]['first_name'])){echo $manager_verification_details[0]['first_name'];}?>">
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">Lastname:</label>
            <div class="col-sm-8">
              <input type="text" name="last_name" required class="form-control" value="<?php if(isset($manager_verification_details[0]['last_name'])){echo $manager_verification_details[0]['last_name'];}?>">
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">Cell no:</label>
            <div class="col-sm-8">
              <input type="text" name="cell_number" required class="form-control" value="<?php if(isset($manager_verification_details[0]['cell_number'])){echo $manager_verification_details[0]['cell_number'];}?>" minlength="10" maxlength="10">
              <div class="help-block with-errors"></div>
            </div>
          </div>
            <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">Name:</label>
            <div class="col-sm-8">
              <input type="text" name="name" required class="form-control" value="<?php if(isset($manager_verification_details[0]['first_name'])){echo $manager_verification_details[0]['first_name']." ".$manager_verification_details[0]['last_name'];}?>">
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">Comment:</label>
            <div class="col-sm-8">
              <textarea name="comment"  rows="4" cols="30"  class="form-control"><?php if(isset($manager_verification_details[0]['comment'])){echo $manager_verification_details[0]['comment'];}?></textarea>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <?php
            
              $img=get_signature_image($job_id);
          ?>
          <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">Signature image:</label>
            <div class="col-sm-4">
              <input type="file" id="uploadfile" name="signature_img"  class="form-control">
              <input type="hidden" name="old_image" value="<?php echo $img;?>">
              <div class="help-block with-errors"></div>
            </div>


            <?php if(isset($manager_verification_details[0]['signature_img']))
          {
          ?>
          
          <div class="col-sm-4">
            <img src="<?php echo $manager_verification_details[0]['signature_img'];?>">
          </div>
          <?php } ?>
          </div>
          
        </fieldset>
        </div> -->
        
         
        <!--General note-->
        <div class="form-group">
          <fieldset>
          <center><legend>Admin note</legend></center>
          <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">Note:</label>
            <div class="col-sm-8">
              <textarea name="admin_note" rows="4" cols="30"  class="form-control"></textarea>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          </fieldset>
        </div>
        <div class="form-group">
          <fieldset>
          <center><legend>Feedback</legend></center>
          <div class="col-md-12">
            <label for="inputPhone" class="col-sm-4 control-label">Feedback:</label>
            <div class="col-sm-8">
              <textarea name="general_note" rows="4" cols="30" required class="form-control"><?php if(isset($general_note)){echo $general_note;}?></textarea>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          </fieldset>
        </div>
    <fieldset id="wineForm">
        <legend>Wine Info</legend>
        <div class="col-sm-4">
        <legend><h4> Wine</h4></legend>
        </div>
        <div class="col-sm-2">
        <legend><h4>Sampled</h4></legend>
        </div>
        <div class="col-sm-2">
        <legend><h4>Opened bottles sampled</h4></legend>
        </div>
        <div class="col-sm-2">
        <legend><h4>Sold</h4></legend>
        </div>
        <div class="col-sm-2">
        <button class="add_field_button" href="#" ><span class="glyphicon glyphicon-plus-sign"></span>Add more </button>
        </div>
      </fieldset>
      <fieldset >

      <div class="input_fields_wrap">
        <?php 
         foreach($get_wine_info as $value){?> 
            
              <div class="row">
                <div class="col-sm-4">
                <select class="form-control" id="wine" name="wine[]">
                 <?php foreach($get_wine_list as $get_val){?>
                 <option value="<?php echo $get_val['id']; ?>" <?php if($get_val['id']==$value['id']){?> <?php echo "selected" ?> <?php }?>  > <?php echo $get_val['name'] ; ?> </option>
                  
                 <?php } ?>
                 </select>
                 <br/>
                </div>
                <div class="col-sm-2">
                  <input name="bottles_sampled[]" value="<?php echo $value['bottles_sampled']; ?>" onkeypress="return onlyNumberKey(event)" class="form-control"> 
                </div>
                <div class="col-sm-2">
                  <input name="open_bottles_sampled[]" value="<?php echo $value['open_bottles_sampled']; ?>" onkeypress="return onlyNumberKey(event)" class="form-control"> 
                </div>
                <div class="col-sm-2">
                  <input name="bottles_sold[]" value="<?php echo $value['bottles_sold']; ?>" onkeypress="return onlyNumberKey(event)" class="form-control"> 
                </div>
                <a href="#" class="remove_field"><strong><span class="glyphicon glyphicon-minus-sign" style="padding-top: 10px;"></strong></a>
              </div>
               
        <?php }?>
         </div>
        <!-- <div class="row">
                
                <div class="col-sm-4" >
                  <button class="add_field_button" href="#" ><span class="glyphicon glyphicon-plus-sign"></span>Add more </button>
                </div>
              </div>
         -->
        <!-- <input type="button" value="Add wine" class="add" id="add" /> -->
        </fieldset>
        <!--End-->
      </div>
      <div class="modal-footer" style="text-align: center;>
         !-- <input type="submit" class="btn btn-primary" value="" value="click" id="">
        <button type="submit" class="btn btn-primary"  onclick="return validate();"><span class="glyphicon glyphicon-ok-sign"></span>Save & Create billing</button> or <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>      </div>
      <?php echo form_close();?>
    </div>
          </form>
    <script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
    $('img[data-enlargeable]').addClass('img-enlargeable').click(function(){
    var src = $(this).attr('src');
    var modal;
    function removeModal(){ modal.remove(); $('body').off('keyup.modal-close'); }
    modal = $('<div>').css({
        background: 'RGBA(0,0,0,.9) url('+src+') no-repeat center',
        backgroundSize: 'contain',
        width:'100%', height:'100%',
        position:'fixed',
        zIndex:'10000',
        top:'0', left:'0',
        cursor: 'zoom-out'
    }).click(function(){
        removeModal();
    }).appendTo('body');
    //handling ESC
    $('body').on('keyup.modal-close', function(e){
      if(e.key==='Escape'){ removeModal(); } 
    });
    });
    $("#uploadfile").change(function(){
      var ext = $('#uploadfile').val().split('.').pop().toLowerCase();
      if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {

          alert('You have to upload gif , png , jpg , jpeg file format!');
          $("#uploadfile").val(''); 
          return false;
      }
      else
      {
        return true;
      }
    });
    $("#receipt_file").change(function(){
      var ext = $('#receipt_file').val().split('.').pop().toLowerCase();
      var len = this.files.length;
      if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {

          alert('You have to upload gif , png , jpg , jpeg file format!');
          $("#receipt_file").val(''); 
          return false;
      }
      else if (len > 3) {
          alert("You can upload maximum 3 files");
          $("#receipt_file").val(''); 
          return false;
      }
      else
      {
        return true;
      }

      
    });

    $('input[name="cell_number"]').keyup(function(e)
                                {
      if (/\D/g.test(this.value))
      {
        // Filter non-digits from input value.
        this.value = this.value.replace(/\D/g, '');
      }

    });

  </script>
  <script type="text/javascript">
    $('.number').keypress(function(event) {
    if (event.which != 46 && (event.which < 47 || event.which > 59))
    {
        event.preventDefault();
        if ((event.which == 46) && ($(this).indexOf('.') != -1)) {
            event.preventDefault();
        }
    }
});

/*
$('#first_am_pm_dropdown').change(function() {
  $('#second_am_pm_dropdown').html('');
  if($(this).val()=='am')
      $('#second_am_pm_dropdown').append('<option value="am">am</option>');
  $('#second_am_pm_dropdown').append('<option value="pm">pm</option>');

  //alert('The option with value ' + $(this).val() + ' and text ' + $(this).text() + ' was selected.');
});*/


function validate(){
	var a= confirm('Do you wish to create billing?');
	if(a==true){

    var  winevals= $("select[name=\'wine[]\']").map(function() {
        return $(this).val();
      }).toArray();

      winevals=winevals.filter(Number);
    var job_date = '<?php echo $job_details->tasting_date;?>';
		var start_hour = $('#start_time_hour').val();
		var start_min = $('#start_time_minute').val();
		var time_one = $('#first_am_pm_dropdown').val();

		if(time_one == 'pm' && start_hour !=12){
			start_hour = 12+parseInt(start_hour);
		}
		if(time_one == 'am' && start_hour ==12){
			start_hour = 12-parseInt(start_hour);
		}
		var end_hour = $('#end_time_hour').val();
		var end_min = $('#end_time_minute').val();
		var time_two = $('#second_am_pm_dropdown').val();
    
		if(time_two == 'pm' && end_hour != 12){
			end_hour = 12+parseInt(end_hour);
		}
		if(time_two == 'am' && end_hour == 12){
			end_hour = 12-parseInt(end_hour);
		}
		var time1 =  new Date(job_date+' '+start_hour+':'+start_min+':00');
		var time2 =  new Date(job_date+' '+end_hour+':'+end_min+':00');
		var curtime = new Date();
		
	
		//alert(curtime);
    var now=new Date();
    now.setDate(now.getDate() - 1);
    now.setHours(0,0,0,0);

    var mydate=new Date(job_date);
    mydate.setHours(0,0,0,0);

		var start_time = start_hour+':'+start_min+':00';
		var end_time = end_hour+':'+end_min+':00';
		var seconds =  (time2- time1)/1000;
		var min = seconds/60;

	 if($('#start_time_hour').val()==''){
			$('#start_time_hour').focus();
			swal("Oops!", "Select a start time.", "warning");
			return false;
		}
    else if($('#end_time_hour').val()==''){
			$('#end_time_hour').focus();
			swal("Oops!", "Select an end time.", "warning");
			return false;
		}
    // else if(min < 30 && min >=0){
		// 	$('#end_time_hour').focus();
		// 	swal("Oops!", "The start time and the end time should not be same. There should be a gap of minimum 30 min between start and end time.", "warning");
		// 	return false;
		// }
    // else if(min < 0){
		// 	$('#end_time_hour').focus();
		// 	swal("Oops!", "End time should be greater than start time.", "warning");
		// 	return false;
		// }
    else if(time_one == 'pm' && time_two == 'am' && end_hour >=3){
				
        $('#end_time_hour').focus();
        swal("Oops!", "End time should be greater than start time.", "warning");
        return false;

    }else if(winevals.length==0){
      $('#wine').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Please select atleast one wine', });
      return false;
    }else if(winevals.length!=0){
      var arr=winevals;
      arr =arr.filter(Number);
      //let hasDuplicate = arr.some((val, i) => arr.indexOf(val) !== i); 
      let hasDuplicate = new Set(arr).size !== arr.length;
      //alert(hasDuplicate);
      if(hasDuplicate){
      $.alert({ title: 'Oops!', type:'red', content: 'Duplicate wines selected.', });
      return false;
      }
    }else{
			return true;
		}
	}else{
		return false;
	}
}


/*
$(document).ready(function(){

$("save_and_move").submit(function(e){
            e.preventDefault();
            
            if(!$("#submit_button").hasClass("disabled"))
            {
                //var c=confirm('Are you ready to confirm the Billing?');
                // if(c==true)
                // {
                    var winevals= $("select[name=\'wine[]\']").map(function() { return $(this).val(); }).toArray();
                    winevals=winevals.filter(Number);  
                    var start_hour = $('#start_time_hour').val();
                    var start_min = $('#start_time_minute').val();
                    var time_one = $('#first_am_pm_dropdown').val();

                    var end_hour = $('#end_time_hour').val();
                    var end_min = $('#end_time_minute').val();
                    var time_two = $('#second_am_pm_dropdown').val();


                    var exp_amount = $('#exp_amount').val();
                    var exp_reason = $('#exp_reason').val().trim();

                    if(time_one == 'pm' && start_hour !=12){
                      start_hour = 12+parseInt(start_hour);
                    }
                    if(time_one == 'am' && start_hour ==12){
                      start_hour = 12-parseInt(start_hour);
                    }
                    
                  
                    
                    if(time_two == 'pm' && end_hour != 12){
                      end_hour = 12+parseInt(end_hour);
                    }
                    if(time_two == 'am' && end_hour == 12){
                      end_hour = 12-parseInt(end_hour);
                    }
                    
                    
                    
                    var start_time = '01/01/2020'+' '+start_hour+':'+start_min;
                    var end_time = '01/01/2020'+' '+end_hour+':'+end_min;
                    var fromTime = new Date(end_time).getTime();
                    var toTime = new Date(start_time).getTime();
                    var seconds =  (fromTime - toTime)/1000;
                    var min = seconds/60;

                    if(min < 0){
                      $.alert({ title: 'Oops!', type:'red', content: 'Job end time can not be less than job start time.', });
                      return false;
                    }else if( exp_amount!='' && exp_reason==''){
                        $('#exp_reason').focus();
                        $('#exp_reason').val('');
                        $.alert({ title: 'Oops!', type:'red', content: 'Please enter expense reason.', });
                        return false;
                      }else if( exp_amount=='' && exp_reason!=''){
                        $('#exp_amount').focus();
                        $.alert({ title: 'Oops!', type:'red', content: 'Please enter expense amount.', });
                        return false;
                      }else if(winevals.length==0){
                        $('#wine').focus();
                        $.alert({ title: 'Oops!', type:'red', content: 'Please select atleast one wine', });
                        return false;
                      }else{
                         if(winevals.length!=0){
                            var arr=winevals;
                            arr =arr.filter(Number);
                            let hasDuplicate = arr.some((val, i) => arr.indexOf(val) !== i); 
                            if(hasDuplicate){
                              $.alert({ title: 'Oops!', type:'red', content: 'Duplicate wines selected.', });
                            return false;
                            }else{
                              alert("Welcome...");
                              $.ajax({
                                type:'POST',
                                url:"<?php echo base_url(); ?>App/job/create_billing_one/",
                                data: $('form').serialize(),
                                success:function(data){
                                  $("#modal .close").click();
                                  location.reload();
                                  }
                                });
                              return false;
                            }
                          }
                      }

            }
            else
            {
                return false;
            } 
  }); 

});*/


  </script>
  
  
  <script type="text/javascript">
// var wines=('#wineData').val();
// console.log("wineData", wines);
$(document).ready(function() {
  var max_fields      = 10; //maximum input boxes allowed
  var wrapper       = $(".input_fields_wrap"); //Fields wrapper
  var add_button      = $(".add_field_button"); //Add button ID
  
  var x = 1; //initlal text box count
  $(add_button).click(function(e){ //on add input button click
    <?php
      $wine='<option value="">Select Wine</option>';
      $wineList = $get_wine_list;
      foreach($wineList as $w){
        $wine.='<option value="'.$w['id'].'">'.addslashes($w['name']).'</option>';
      }
    ?>
    
    var wine='<?php echo $wine;?>';
    
    //  alert(wine);
    var html ='<div class="row" style="margin-top:20px;"><div class="col-sm-4"><select class="form-control" id="wine" name="wine[]">'+wine+'</select></div><div class="col-sm-2"><input value="0" name="bottles_sampled[]" onkeypress="return onlyNumberKey(event)" class="form-control"></div><div class="col-sm-2"><input value="0" onkeypress="return onlyNumberKey(event)" name="open_bottles_sampled[]" class="form-control"></div><div class="col-sm-2"><input value="0" onkeypress="return onlyNumberKey(event)" name="bottles_sold[]" class="form-control"> </div><a href="#" class="remove_field"><strong><span class="glyphicon glyphicon-minus-sign" style="padding-top: 10px;"></strong></a></div>';
    
    e.preventDefault();      
      $(wrapper).append(html); //add input box
    
  });
  
  $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
      //e.preventDefault(); $(this).parent('div').remove(); x--;
      var  winevals= $("select[name=\'wine[]\']").map(function() {
        return $(this).val();
      }).toArray();
      var arr=winevals;
      //arr =arr.filter(Number);
      //let hasDuplicate = arr.some((val, i) => arr.indexOf(val) !== i);

      if(arr.length>1){
        e.preventDefault(); $(this).parent('div').remove(); x--;
      }else{
        $.alert({ title: 'Oops!', type:'red', content: 'All wines can not be deleted. Atleast one wine needed in the list.', });
        return false;
      }
  });
});
  

function onlyNumberKey(evt) { 
          
          // Only ASCII charactar in that range allowed 
          var ASCIICode = (evt.which) ? evt.which : evt.keyCode 
          if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) 
              return false; 
          return true; 
      } 
</script>