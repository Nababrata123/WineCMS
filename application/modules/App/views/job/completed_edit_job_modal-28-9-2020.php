<!-- Modal content-->

    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit job</h4>
      </div>
     <?php
      echo validation_errors();
        $attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
        echo form_open_multipart(base_url('App/job/completed_edit_job/'.$job->id), $attributes);
     ?>
     <div class="modal-body">
     <div class="col-sm-12">
      <fieldset>
        <legend>Basic Info</legend>
        <!-- <div class="form-group">
          <label for="inputFirstName" class="col-sm-4 control-label">Sales Representative</label>
          <div class="col-sm-6">
            <?php echo $sales_rep;?>
          </div>
        </div> -->
        <div class="form-group">
          <label for="inputFirstName" class="col-sm-3 control-label">Job date</label>
          <div class="col-sm-7">
            <input readonly type="text" name="tasting_date" class="form-control " id="tasting_date2" placeholder="Enter job date" value="<?php echo date("m/d/Y", strtotime($job->tasting_date));?>" required>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="form-group">
            <?php
           // print_r($job);die;
            //echo $job->status;die;
                $start_hour=date('h',strtotime($job->job_start_time));
                $end_hour=date('h',strtotime($job->finish_time));
                $start_minute=date('i',strtotime($job->job_start_time));
                $end_minute=date('i',strtotime($job->finish_time));
                //echo $start_hour.' '.$end_hour.' '.$start_minute.' '.$end_minute;die;
            ?>
        <div class="col-md-12">
            <label for="inputLastName" class="col-sm-3 control-label">Job start time</label>
            <div class="col-sm-3">
            <select name="start_time_hour" required class="form-control" id="start_time_hour">
                        <option value="">Hour</option>
		  				          <option value="01" <?php if($start_hour=="01"){echo "selected";}?> >01</option>
                        <option value="02" <?php if($start_hour=="02"){echo "selected";}?>>02</option>
                        <option value="03" <?php if($start_hour=="03"){echo "selected";}?>>03</option>
                        <option value="04" <?php if($start_hour=="04"){echo "selected";}?>>04</option>
                        <option value="05" <?php if($start_hour=="05"){echo "selected";}?>>05</option>
                        <option value="06" <?php if($start_hour=="06"){echo "selected";}?>>06</option>
                        <option value="07" <?php if($start_hour=="07"){echo "selected";}?>>07</option>
                        <option value="08" <?php if($start_hour=="08"){echo "selected";}?>>08</option>
                        <option value="09" <?php if($start_hour=="09"){echo "selected";}?>>09</option>
                        <option value="10" <?php if($start_hour=="10"){echo "selected";}?>>10</option>
                        <option value="11" <?php if($start_hour=="11"){echo "selected";}?>>11</option>
                        <option value="12" <?php if($start_hour=="12"){echo "selected";}?>>12</option>

            </select> 
                </div>
            <div class="col-sm-3">
             
              <select name="start_time_minute" required class="form-control"id="start_time_minute">
                        <option value="">Minute</option>
                        <option value="00" <?php if($start_minute=="00"){echo "selected";}?>>00</option>
                        <option value="01" <?php if($start_minute=="01"){echo "selected";}?>>01</option>
                        <option value="02" <?php if($start_minute=="02"){echo "selected";}?>>02</option>
                        <option value="03" <?php if($start_minute=="03"){echo "selected";}?>>03</option>
                        <option value="04" <?php if($start_minute=="04"){echo "selected";}?>>04</option>
                        <option value="05"<?php if($start_minute=="05"){echo "selected";}?>>05</option>
                        <option value="06" <?php if($start_minute=="06"){echo "selected";}?>>06</option>
                        <option value="07" <?php if($start_minute=="07"){echo "selected";}?>>07</option>
                        <option value="08" <?php if($start_minute=="08"){echo "selected";}?>>08</option>
                        <option value="09" <?php if($start_minute=="09"){echo "selected";}?>>09</option>
                        <option value="10" <?php if($start_minute=="10"){echo "selected";}?>>10</option>
                        <option value="11" <?php if($start_minute=="11"){echo "selected";}?>>11</option>
                        <option value="12" <?php if($start_minute=="12"){echo "selected";}?>>12</option>
                        <option value="13" <?php if($start_minute=="13"){echo "selected";}?>>13</option>
                        <option value="14" <?php if($start_minute=="14"){echo "selected";}?>>14</option>
                        <option value="15" <?php if($start_minute=="15"){echo "selected";}?>>15</option>
                        <option value="16" <?php if($start_minute=="16"){echo "selected";}?>>16</option>
                        <option value="17" <?php if($start_minute=="17"){echo "selected";}?>>17</option>
                        <option value="18" <?php if($start_minute=="18"){echo "selected";}?>>18</option>
                        <option value="19" <?php if($start_minute=="19"){echo "selected";}?>>19</option>
                        <option value="20" <?php if($start_minute=="20"){echo "selected";}?>>20</option>
                        <option value="21" <?php if($start_minute=="21"){echo "selected";}?>>21</option>
                        <option value="22" <?php if($start_minute=="22"){echo "selected";}?>>22</option>
                        <option value="23" <?php if($start_minute=="23"){echo "selected";}?>>23</option>
                        <option value="24" <?php if($start_minute=="24"){echo "selected";}?>>24</option>
                        <option value="25" <?php if($start_minute=="25"){echo "selected";}?>>25</option>
                        <option value="26" <?php if($start_minute=="26"){echo "selected";}?>>26</option>
                        <option value="27" <?php if($start_minute=="27"){echo "selected";}?>>27</option>
                        <option value="28" <?php if($start_minute=="28"){echo "selected";}?>>28</option>
                        <option value="29" <?php if($start_minute=="29"){echo "selected";}?>>29</option>
                        <option value="30" <?php if($start_minute=="30"){echo "selected";}?>>30</option>
                        <option value="31" <?php if($start_minute=="31"){echo "selected";}?>>31</option>
                        <option value="32" <?php if($start_minute=="32"){echo "selected";}?>>32</option>
                        <option value="33" <?php if($start_minute=="33"){echo "selected";}?>>33</option>
                        <option value="34" <?php if($start_minute=="34"){echo "selected";}?>>34</option>
                        <option value="35" <?php if($start_minute=="35"){echo "selected";}?>>35</option>
                        <option value="36" <?php if($start_minute=="36"){echo "selected";}?>>36</option>
                        <option value="37" <?php if($start_minute=="37"){echo "selected";}?>>37</option>
                        <option value="38" <?php if($start_minute=="38"){echo "selected";}?>>38</option>
                        <option value="39" <?php if($start_minute=="39"){echo "selected";}?>>39</option>
                        <option value="40" <?php if($start_minute=="40"){echo "selected";}?>>40</option>
                        <option value="41" <?php if($start_minute=="41"){echo "selected";}?>>41</option>
                        <option value="42" <?php if($start_minute=="42"){echo "selected";}?>>42</option>
                        <option value="43" <?php if($start_minute=="43"){echo "selected";}?>>43</option>
                        
                        <option value="44" <?php if($start_minute=="44"){echo "selected";}?>>44</option>
                        <option value="45" <?php if($start_minute=="45"){echo "selected";}?>>45</option>
                        <option value="46" <?php if($start_minute=="46"){echo "selected";}?>>46</option>
                        <option value="47" <?php if($start_minute=="47"){echo "selected";}?>>47</option>
                        <option value="48" <?php if($start_minute=="48"){echo "selected";}?>>48</option>
                        <option value="49" <?php if($start_minute=="49"){echo "selected";}?>>49</option>
                        <option value="50" <?php if($start_minute=="50"){echo "selected";}?>>50</option>
                        <option value="51" <?php if($start_minute=="51"){echo "selected";}?>>51</option>
                        <option value="52" <?php if($start_minute=="52"){echo "selected";}?>>52</option>
                        <option value="53" <?php if($start_minute=="53"){echo "selected";}?>>53</option>
                        <option value="54" <?php if($start_minute=="54"){echo "selected";}?>>54</option>
                        <option value="55" <?php if($start_minute=="55"){echo "selected";}?>>55</option>
                        <option value="56" <?php if($start_minute=="56"){echo "selected";}?>>56</option>
                        <option value="57" <?php if($start_minute=="57"){echo "selected";}?>>57</option>
                        <option value="58" <?php if($start_minute=="58"){echo "selected";}?>>58</option>
                        <option value="59" <?php if($start_minute=="59"){echo "selected";}?>>59</option>
		  				

            </select>
				
                </div>
            <div class="col-sm-3">
            
              <select name="time_one" required class="form-control" id="time_one">

              <option value="am" <?php if(date('A', strtotime($job->start_time))=='AM'){echo "selected";}?>>am</option>
              <option value="pm" <?php if(date('A', strtotime($job->start_time))=='PM'){echo "selected";}?>>pm</option>
            </select>
					
            </div>
        </div>

        </div>
        <div class="form-group">
          <div class="col-md-12">
          <label for="inputLastName" class="col-sm-3 control-label t">Job end time</label>
          <div class="col-sm-3">
          <input id='currentJobStatus' type="hidden" value="<?php echo $job->status ?>" class="form-control">
                    <select name="end_time_hour" required class="form-control" id="end_time_hour">
                        <option value="">Hour</option>
		  				<option value="01" <?php if($end_hour=="01"){echo "selected";}?> >01</option>
                        <option value="02" <?php if($end_hour=="02"){echo "selected";}?>>02</option>
                        <option value="03" <?php if($end_hour=="03"){echo "selected";}?>>03</option>
                        <option value="04" <?php if($end_hour=="04"){echo "selected";}?>>04</option>
                        <option value="05" <?php if($end_hour=="05"){echo "selected";}?>>05</option>
                        <option value="06" <?php if($end_hour=="06"){echo "selected";}?>>06</option>
                        <option value="07" <?php if($end_hour=="07"){echo "selected";}?>>07</option>
                        <option value="08" <?php if($end_hour=="08"){echo "selected";}?>>08</option>
                        <option value="09" <?php if($end_hour=="09"){echo "selected";}?>>09</option>
                        <option value="10" <?php if($end_hour=="10"){echo "selected";}?>>10</option>
                        <option value="11" <?php if($end_hour=="11"){echo "selected";}?>>11</option>
                        <option value="12" <?php if($end_hour=="12"){echo "selected";}?>>12</option>

            </select>
            
                </div>
          <div class="col-sm-3">
          
                    <select name="end_time_minute" required class="form-control" id="end_time_minute">
                        <option value="">Minute</option>
		  				<option value="00" <?php if($end_minute=="00"){echo "selected";}?>>00</option>
                        <option value="01" <?php if($end_minute=="01"){echo "selected";}?>>01</option>
                        <option value="02" <?php if($end_minute=="02"){echo "selected";}?>>02</option>
                        <option value="03" <?php if($end_minute=="03"){echo "selected";}?>>03</option>
                        <option value="04" <?php if($end_minute=="04"){echo "selected";}?>>04</option>
                        <option value="05" <?php if($end_minute=="05"){echo "selected";}?>>05</option>
                        <option value="06" <?php if($end_minute=="06"){echo "selected";}?>>06</option>
                        <option value="07" <?php if($end_minute=="07"){echo "selected";}?>>07</option>
                        <option value="08" <?php if($end_minute=="08"){echo "selected";}?>>08</option>
                        <option value="09" <?php if($end_minute=="09"){echo "selected";}?>>09</option>
                        <option value="10" <?php if($end_minute=="10"){echo "selected";}?>>10</option>
                        <option value="11" <?php if($end_minute=="11"){echo "selected";}?>>11</option>
                        <option value="12" <?php if($end_minute=="12"){echo "selected";}?>>12</option>
                        <option value="13" <?php if($end_minute=="13"){echo "selected";}?>>13</option>
                        <option value="14" <?php if($end_minute=="14"){echo "selected";}?>>14</option>
                        <option value="15" <?php if($end_minute=="15"){echo "selected";}?>>15</option>
                        <option value="16" <?php if($end_minute=="16"){echo "selected";}?>>16</option>
                        <option value="17" <?php if($end_minute=="17"){echo "selected";}?>>17</option>
                        <option value="18" <?php if($end_minute=="18"){echo "selected";}?>>18</option>
                        <option value="19" <?php if($end_minute=="19"){echo "selected";}?>>19</option>
                        <option value="20" <?php if($end_minute=="20"){echo "selected";}?>>20</option>
                        <option value="21" <?php if($end_minute=="21"){echo "selected";}?>>21</option>
                        <option value="22" <?php if($end_minute=="22"){echo "selected";}?>>22</option>
                        <option value="23" <?php if($end_minute=="23"){echo "selected";}?>>23</option>
                        <option value="24" <?php if($end_minute=="24"){echo "selected";}?>>24</option>
                        <option value="25" <?php if($end_minute=="25"){echo "selected";}?>>25</option>
                        <option value="26" <?php if($end_minute=="26"){echo "selected";}?>>26</option>
                        <option value="27" <?php if($end_minute=="27"){echo "selected";}?>>27</option>
                        <option value="28" <?php if($end_minute=="28"){echo "selected";}?>>28</option>
                        <option value="29" <?php if($end_minute=="29"){echo "selected";}?>>29</option>
                        <option value="30" <?php if($end_minute=="30"){echo "selected";}?>>30</option>
                        <option value="31" <?php if($end_minute=="31"){echo "selected";}?>>31</option>
                        <option value="32" <?php if($end_minute=="32"){echo "selected";}?>>32</option>
                        <option value="33" <?php if($end_minute=="33"){echo "selected";}?>>33</option>
                        <option value="34" <?php if($end_minute=="34"){echo "selected";}?>>34</option>
                        <option value="35" <?php if($end_minute=="35"){echo "selected";}?>>35</option>
                        <option value="36" <?php if($end_minute=="36"){echo "selected";}?>>36</option>
                        <option value="37" <?php if($end_minute=="37"){echo "selected";}?>>37</option>
                        <option value="38" <?php if($end_minute=="38"){echo "selected";}?>>38</option>
                        <option value="39" <?php if($end_minute=="39"){echo "selected";}?>>39</option>
                        <option value="40" <?php if($end_minute=="40"){echo "selected";}?>>40</option>
                        <option value="41" <?php if($end_minute=="41"){echo "selected";}?>>41</option>
                        <option value="42" <?php if($end_minute=="42"){echo "selected";}?>>42</option>
                        <option value="43" <?php if($end_minute=="43"){echo "selected";}?>>43</option>
                        
                        <option value="44" <?php if($end_minute=="44"){echo "selected";}?>>44</option>
                        <option value="45" <?php if($end_minute=="45"){echo "selected";}?>>45</option>
                        <option value="46" <?php if($end_minute=="46"){echo "selected";}?>>46</option>
                        <option value="47" <?php if($end_minute=="47"){echo "selected";}?>>47</option>
                        <option value="48" <?php if($end_minute=="48"){echo "selected";}?>>48</option>
                        <option value="49" <?php if($end_minute=="49"){echo "selected";}?>>49</option>
                        <option value="50" <?php if($end_minute=="50"){echo "selected";}?>>50</option>
                        <option value="51" <?php if($end_minute=="51"){echo "selected";}?>>51</option>
                        <option value="52" <?php if($end_minute=="52"){echo "selected";}?>>52</option>
                        <option value="53" <?php if($end_minute=="53"){echo "selected";}?>>53</option>
                        <option value="54" <?php if($end_minute=="54"){echo "selected";}?>>54</option>
                        <option value="55" <?php if($end_minute=="55"){echo "selected";}?>>55</option>
                        <option value="56" <?php if($end_minute=="56"){echo "selected";}?>>56</option>
                        <option value="57" <?php if($end_minute=="57"){echo "selected";}?>>57</option>
                        <option value="58" <?php if($end_minute=="58"){echo "selected";}?>>58</option>
                        <option value="59" <?php if($end_minute=="59"){echo "selected";}?>>59</option>

            </select>
            
                </div>
          <div class="col-sm-3">
          
            <select name="time_two" required class="form-control" id="time_two">
              <option value="am" <?php if(date('A', strtotime($job->end_time))=='AM'){echo "selected";}?>>am</option>
              <option value="pm" <?php if(date('A', strtotime($job->end_time))=='PM'){echo "selected";}?>>pm</option>
            </select>
            
          </div>
        </div>
        </div>
        
        <div class="form-group">
          <label for="inputPhone" class="col-sm-3 control-label">Store</label>
          <div class="col-sm-7">
            
          <?php foreach($store as $value){ ?>
                <?php if($value['id']==$job->store_id){ ?>
                  <input type="hidden" id="store" name="store_id" readonly value="<?php echo $job->store_id;?>" class="form-control">
							<input readonly value="<?php echo $value['name'];?>" class="form-control">
								<?php 
								break; 
								 } ?>
					<?php } ?>
          </div>
        </div>

        <div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Sales Rep</label>
		  		<div class="col-sm-7">
              <?php
              
                foreach($sales_rep as $value){?>  
                      <?php if($value['id']==$job->user_id){ ?>
                        <input type="hidden" id="salesRep" name="salesRep" readonly value="<?php echo $job->user_id;?>" class="form-control">
                        <input readonly value="<?php echo $value['last_name']." ".$value['first_name'];?>" class="form-control">
                      <?php 
                      break; 
                       } ?>      
			  			<?php } ?>
			  		<div class="help-block with-errors"></div>
			  	</div>
        </div>
        
      
        <div class="form-group">
          <label for="inputConfirmPassword" class="col-sm-3 control-label">Comments </label>
          <div class="col-sm-7"> 
                      <textarea name="Comments" class="form-control" id="Comments"  placeholder="Comments"  ><?php if ($manager_verification_details[0]['comment']!= null ) { echo $manager_verification_details[0]['comment'];?> <?php } ?></textarea>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        
        <div class="form-group">
          <label for="inputExpAmount" class="col-sm-3 control-label">Expense Amount</label>
          <div class="col-sm-7"> 
          <input id="exp_amount" onkeypress="return isNumberKey(event,this)" name="exp_amount" value="<?php if(isset($expense_details[0]['exp_amount'])){echo ltrim($expense_details[0]['exp_amount'],'$');}?>" class="form-control">
            <div class="help-block with-errors"></div>
          </div>
        </div>

        <div class="form-group">
          <label for="inputExpAmount" class="col-sm-3 control-label">Expense reason</label>
          <div class="col-sm-7"> 
          <textarea placeholder="Expense Reason" id="exp_reason" name="exp_reason" class="form-control"><?php if(isset($expense_details[0]['exp_reason'])){echo ltrim($expense_details[0]['exp_reason'],"$");}?></textarea>
            <div class="help-block with-errors"></div>
          </div>
        </div>
    
        <?php
          $taster_id=$job->taster_id;
          $taster_id_array=explode(",",$taster_id);
        ?>
        <div class="form-group">
          <label for="inputPhone" class="col-sm-3 control-label"> Taster/agency</label>
          <div class="col-sm-7">
          <select class="form-control" disabled="disabled" >
                <option value="">Select Taster</option>
              <?php
                
                foreach($tester as $value){
                  //Get user role
                  
                  $role_id=get_user_role('users',$value['id']);
                  if($role_id=='5')
                  {
                    $agency_name=get_agency_name('user_meta',$value['id']);
                  }
              ?>
              <option value="<?php echo $value['id'];?>" <?php if(in_array($value['id'],$taster_id_array)){echo "selected";}?>><?php 
                if($role_id=='5')
                {
                  echo $agency_name;
                }
                else
                {
                  echo $value['last_name']." ".$value['first_name'];
                }
                
              ?>
                
              </option>
              <?php } ?>
            </select>
              
              <?php
                
                foreach($tester as $value){
                  //Get user role
              ?>
              <?php if(in_array($value['id'],$taster_id_array)){ ?> 
                <input type="hidden" name="taster_id" class="form-control"  id="testers" readonly value="<?php echo $value['id'];?>" >
							
                <?php }?>
              
                
              <?php } ?>
            <br/>
            <!-- <input type="button" id="select_all" name="select_all" value="Select All"> -->
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </fieldset>

      <fieldset id="wineForm">
        <legend>Wine Info</legend>
        <div class="col-sm-6">
        <legend><h4> Wine</h4></legend>
        </div>
        <div class="col-sm-2">
        <legend><h4>Sampled</h4></legend>
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
                <div class="col-sm-6">
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
        <div class="form-group" style="margin-top: 10px;">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary" onclick="return validate();"><span class="glyphicon glyphicon-ok-sign"></span>Save</button> or <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>

     
  </div>
      <?php echo form_close();?>
  </div>
  </div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">-->
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script type="text/javascript">
$('.datepicker').datepicker({

    format: 'm/d/yyyy',
    todayHighlight: true,
    autoclose: true,
    minDate:new Date()
    //startDate: truncateDate(new Date()) 
});
function truncateDate(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}
$('#select_all').click(function() {
       $('#testers option').prop('selected', true);
});
function get_tester(id,taster_id,wine_id)
{
  $.ajax({
       type:'POST',
       url:"<?php echo base_url(); ?>App/job/get_tester/",
       data: {store_id:id,taster_id:taster_id},
       success:function(data){
          $("#testers").html(data);
       }
  });
  $.ajax({
       type:'POST',
       url:"<?php echo base_url(); ?>App/job/get_wine/",
       data: {store_id:id,wine_id:wine_id},
       success:function(data){
          $("#wines").html(data);
       }
  });
  
}
function validate(){ 
  var a = confirm('Do you wish to edit the job?');
  if(a==true){
   // alert($('#wine').val());
    
  
      var  winevals= $("select[name=\'wine[]\']").map(function() {
        return $(this).val();
      }).toArray();
      winevals=winevals.filter(Number);
      var exp_amount = $('#exp_amount').val();
      var exp_reason = $('#exp_reason').val().trim();

    var job_date = $('#tasting_date2').val();
    var start_hour = $('#start_time_hour').val();
    var start_min = $('#start_time_minute').val();
    var time_one = $('#time_one').val();
    if(time_one == 'pm' && start_hour !=12){
      start_hour = 12+parseInt(start_hour);
    }
    if(time_one == 'am' && start_hour ==12){
      start_hour = 12-parseInt(start_hour);
    }
    var end_hour = $('#end_time_hour').val();
    var end_min = $('#end_time_minute').val();
    var time_two = $('#time_two').val();
    if(time_two == 'pm' && end_hour != 12){
      end_hour = 12+parseInt(end_hour);
    }
    if(time_two == 'am' && end_hour == 12){
      end_hour = 12-parseInt(end_hour);
    }
    var time1 =  new Date(job_date+' '+start_hour+':'+start_min+':00');
    var time2 =  new Date(job_date+' '+end_hour+':'+end_min+':00');
    var curtime = new Date();
    
    //alert(time1);
    //alert(curtime);
    var start_time = start_hour+':'+start_min+':00';
    var end_time = end_hour+':'+end_min+':00';
    var seconds =  (time2- time1)/1000;
    var min = seconds/60;
    var taster_id = $('#testers').val();
    if(job_date == ''){
      $.alert({ title: 'Oops!', type:'red', content: 'Select a job date.', });
      return false;
    }
    else if($('#start_time_hour').val()==''){
      $('#start_time_hour').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Select a start time.', });
      return false;
    }
    else if($('#end_time_hour').val()==''){
      $('#end_time_hour').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Select an end time.', });
      return false;
    }
    // else if(min < 30 && min >=0){
    //  $('#end_time_hour').focus();
    //  swal("Oops!", "The start time and the end time should not be same. There should be a gap of minimum 30 min between start and end time.", "warning");
    //  return false;
    // }
    else if(min < 0){
      $('#end_time_hour').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'End time should be greater than start time.', });
      return false;
    }
    else if($('#store').val()==''){
      $('#store').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Select a store.', });
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
    }
    else if($('#testers').val()==''){
      $('#testers').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Select a taster/agency.', });
      return false;
    }else if(winevals.length==0){
      $('#wine').focus();
      $.alert({ title: 'Oops!', type:'red', content: 'Please select atleast one wine', });
      return false;
    }else if(winevals.length!=0){
      var arr=winevals;
      arr =arr.filter(Number);
      let hasDuplicate = arr.some((val, i) => arr.indexOf(val) !== i); 
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

</script>

<script type="text/javascript">
// var wines=('#wineData').val();
// console.log("wineData", wines);
$(document).ready(function() {
	var max_fields      = 10; //maximum input boxes allowed
	var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
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
    var html ='<div class="row" style="margin-top:20px;"><div class="col-sm-6"><select class="form-control" id="wine" name="wine[]">'+wine+'</select></div><div class="col-sm-2"><input value="0" name="bottles_sampled[]" onkeypress="return onlyNumberKey(event)" class="form-control"></div><div class="col-sm-2"><input value="0" onkeypress="return onlyNumberKey(event)" name="bottles_sold[]" class="form-control"> </div><a href="#" class="remove_field"><strong><span class="glyphicon glyphicon-minus-sign" style="padding-top: 10px;"></strong></a></div>';
		e.preventDefault();      
			$(wrapper).append(html); //add input box
		
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
    var  winevals= $("select[name=\'wine[]\']").map(function() {
        return $(this).val();
      }).toArray();
      var arr=winevals;
      //arr =arr.filter(Number);
      //let hasDuplicate = arr.some((val, i) => arr.indexOf(val) !== i);

      if(arr.length>1){
        e.preventDefault(); $(this).parent('div').remove(); x--;
      }else{
      $.alert({ title: 'Oops!', type:'red', content: 'Please select atleast one wine.', });
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
      function isNumberKey(evt, obj) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      var value = obj.value;
      var dotcontains = value.indexOf(".") != -1;
      if (dotcontains)
          if (charCode == 46) return false;
      if (charCode == 46) return true;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) 
          return false;
      return true;
      }
</script>
