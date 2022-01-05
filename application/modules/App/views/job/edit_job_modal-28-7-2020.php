<!-- Modal content-->

    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit job</h4>
      </div>
     <?php
      echo validation_errors();
        $attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
        echo form_open_multipart(base_url('App/job/edit_job/'.$job->id), $attributes);
     ?>
     <div class="modal-body">
     <div class="col-sm-12">
      <fieldset>
        <legend>Basic Info</legend>
        <!-- <div class="form-group">
          <label for="inputFirstName" class="col-sm-4 control-label">Sales Representative</label>
          <div class="col-sm-6">
            <strong><?php echo $sales_rep;?></strong>
          </div>
        </div> -->
        <div class="form-group">
          <label for="inputFirstName" class="col-sm-3 control-label">Job date</label>
          <div class="col-sm-7">
          <?php if($job->status!='approved'){ ?>
            <input type="text" name="tasting_date" class="form-control datepicker" id="tasting_date2" placeholder="Enter job date" value="<?php echo date("m/d/Y", strtotime($job->tasting_date));?>" required>
            <?php }else{?> 
              <input type="text" readonly name="tasting_date" class="form-control" id="tasting_date2" placeholder="Enter job date" value="<?php echo date("m/d/Y", strtotime($job->tasting_date));?>" required>
					<?php }?>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="form-group">
            <?php
           // print_r($job);die;
            //echo $job->status;die;
                $start_hour=date('h',strtotime($job->start_time));
                $end_hour=date('h',strtotime($job->end_time));
                $start_minute=date('i',strtotime($job->start_time));
                $end_minute=date('i',strtotime($job->end_time));
            ?>
        <div class="col-md-12">
            <label for="inputLastName" class="col-sm-3 control-label">Start time</label>
            <div class="col-sm-3">
            <?php if($job->status!='approved'){ ?>
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
            <?php }else{?> 
						<input id="start_time_hour" name="start_time_hour" readonly value="<?php echo $start_hour;?>" class="form-control">
					<?php }?>
                </div>
            <div class="col-sm-3">
            <?php if($job->status!='approved'){ ?>
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
            <?php }else{?> 
						<input id="start_time_minute" name="start_time_minute" readonly value="<?php echo $start_minute;?>" class="form-control">
					<?php }?>
                </div>
            <div class="col-sm-3">
            <?php if($job->status!='approved'){ ?>
              <select name="time_one" required class="form-control" id="time_one">

              <option value="am" <?php if(date('A', strtotime($job->start_time))=='AM'){echo "selected";}?>>am</option>
              <option value="pm" <?php if(date('A', strtotime($job->start_time))=='PM'){echo "selected";}?>>pm</option>
            </select>
            <?php }else{?> 
						<?php	$ampm=''; ?>
							<?php if(date('A', strtotime($job->start_time))=='PM'){ ?> 
								<?php $ampm='pm'; ?>
								<?php }else{?> 
									<?php $ampm='am'; ?>
								<?php }?>
								<input type="hidden" id="time_one" name="time_one" readonly value="<?php echo $ampm; ?>" class="form-control">
							<input  readonly value="<?php echo strtolower(date('A', strtotime($job->start_time)));?>" class="form-control">
					<?php }?>
            </div>
        </div>

        </div>
        <div class="form-group">
          <div class="col-md-12">
          <label for="inputLastName" class="col-sm-3 control-label t">End time</label>
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
                        <option value="05"<?php if($end_minute=="05"){echo "selected";}?>>05</option>
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
            <?php if($job->status!='approved'){ ?>
            <select name="store_id"  id="store" required class="form-control" onchange="get_tester(this.value,<?php echo $job->taster_id;?>,<?php echo $job->wine_id;?>)">
              <option value="">Select store</option>
              <?php
                foreach($store as $value){
              ?>
              <option value="<?php echo $value['id'];?>" <?php if($value['id']==$job->store_id){echo "selected";}?>><?php echo $value['name'];?></option>
              <?php } ?>
            </select>
            <?php }else{?> 
							<!-- <input type="hidden" id="store" name="store_id" readonly value="<?php echo $job->store_id;?>" class="form-control"> -->
							<?php foreach($store as $value){ ?>
                <?php if($value['id']==$job->store_id){ ?>
                  <input type="hidden" id="store" name="store_id" readonly value="<?php echo $job->store_id;?>" class="form-control">
							<input readonly value="<?php echo $value['name'];?>" class="form-control">
								<?php 
								break; 
								 } ?>
					<?php
					
					}
					}?>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="form-group">
          <label for="inputConfirmPassword" class="col-sm-3 control-label">Admin note</label>
          <div class="col-sm-7">
          <?php if($job->status!='approved'){ ?>
            <textarea name="admin_note" class="form-control" id="admin_note"  placeholder="Enter admin note"><?php echo $job->admin_note;?></textarea>
            <?php }else{?> 
						<textarea readonly name="admin_note" class="form-control" id="admin_note"  placeholder="Enter admin note"><?php echo $job->admin_note;?></textarea>
						<?php } ?>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="form-group">
          <label for="inputConfirmPassword" class="col-sm-3 control-label">Taster note</label>
          <div class="col-sm-7">
          <?php if($job->status!='approved'){ ?>
            <textarea name="taster_note" class="form-control" id="taster_note"  placeholder="Enter taster note"  ><?php echo $job->taster_note;?></textarea>
            <?php }else{?> 
					<textarea readonly name="taster_note" class="form-control" id="taster_note"  placeholder="Enter taster note"><?php echo $job->taster_note;?></textarea>

						<?php } ?>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        
      
    
        <?php
          $taster_id=$job->taster_id;
          $taster_id_array=explode(",",$taster_id);
        ?>
        <div class="form-group">
          <label for="inputPhone" class="col-sm-3 control-label">Assign taster/agency</label>
          <div class="col-sm-7">
          <?php if($job->status!='approved'){ ?>
            <select name="taster_id"  required class="form-control"  id="testers">
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
            <?php }else{?> 

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

						<?php } ?>
            <br/>
            <!-- <input type="button" id="select_all" name="select_all" value="Select All"> -->
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary" onclick="return validate();"><span class="glyphicon glyphicon-ok-sign"></span>Save</button> or <a href="<?php echo base_url('App/Job/index/status/accepted');?>">Cancel</a>
          </div>
        </div>
      </fieldset>

     
  </div>
      <?php echo form_close();?>
  </div>
  </div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">-->
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script> 
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
	var a= confirm('Do you wish to edit the job?');
	if(a==true){
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
			swal("Oops!", "Select a job date.", "warning");
			return false;
		}
    else if($('#start_time_hour').val()==''){
			$('#start_time_hour').focus();
			swal("Oops!", "Select a start time.", "warning");
			return false;
		}
    else if($('#end_time_hour').val()==''){
			$('#end_time_hour').focus();
			swal("Oops!", "Select an end time.", "warning");
			return false;
		}
    else if($('#currentJobStatus').val()!='approved')
    if(time1.getTime()<curtime.getTime()){
			$('#start_time_hour').focus();
			swal("Oops!", "Start time should be greater than current time.", "warning");
			return false;
		}
    else if(min < 30 && min >=0){
			$('#end_time_hour').focus();
			swal("Oops!", "The start time and the end time should not be same. There should be a gap of minimum 30 min between start and end time.", "warning");
			return false;
		}
    else if(min < 0){
			$('#end_time_hour').focus();
			swal("Oops!", "End time should be greater than start time.", "warning");
			return false;
		}
    else if($('#store').val()==''){
			$('#store').focus();
			swal("Oops!", "Select a store.", "warning");
			return false;
		}
    else if($('#testers').val()==''){
			$('#testers').focus();
			swal("Oops!", "Select a taster/agency.", "warning");
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}
</script>