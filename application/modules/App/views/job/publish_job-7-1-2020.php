<style>
.sw { border:2px solid #ccc; width:366px; height: 206px; overflow-y: scroll;
</style>
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-list"></span> Publish job &raquo; <small></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/job');?>"><span class="glyphicon glyphicon-list"></span> Job</a></li>
                 <li><a href="<?php echo base_url('App/Job/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Job</a></li>
    		</ul>
        </div>
    </div>
</div>
<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/job');?>">Job Management</a></li>
		<li class="active">Publish job</li>
	</ol>
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
	<?php
		//form validation
		echo validation_errors();
		$attributes = array('class' => 'form-horizontal', 'id' => 'pj', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open_multipart(base_url('App/job/publish_job/'.$job->id), $attributes);
    ?>
	<div class="col-sm-6">
      	<fieldset>
    		<legend>Basic Info</legend>
    		<div class="form-group">
               
		  		<label for="inputFirstName" class="col-sm-3 control-label">Sales Representative</label>
		  		<div class="col-sm-7">
                    <input type="text" readonly value="<?php echo $sales_rep;?>" class="form-control">
		  			<!-- <strong><?php echo $sales_rep;?></strong> -->
		  		</div>
            
		  	</div>

		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Job date</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="tasting_date" class="form-control datepicker" id="tasting_date" placeholder="Enter job date" value="<?php echo date("m/d/Y", strtotime($job->tasting_date));?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
			<div class="form-group">
			<!--<div class="col-md-12">
		  		<label for="inputLastName" class="col-sm-3 control-label">Start time</label>
		  		<div class="col-sm-7">
		  			<input type="time" name="start_time" class="form-control t" id="inputEmail" placeholder="Enter start time" value="<?php if($job->start_time!=''){echo date("h:i", strtotime($job->start_time));}?>" required >
		  			<div class="help-block with-errors"></div>
		  		</div>
		  		<div class="col-sm-2">
		  			<select name="time_one" required class="form-control">

		  				<option value="am" <?php if(date('A', strtotime($job->start_time))=='AM'){echo "selected";}?>>am</option>
		  				<option value="pm" <?php if(date('A', strtotime($job->start_time))=='PM'){echo "selected";}?>>pm</option>
		  			</select>
		  		</div>
		  	</div>-->
            <?php
                $start_hour=date('h',strtotime($job->start_time));
                $end_hour=date('h',strtotime($job->end_time));
                $start_minute=date('i',strtotime($job->start_time));
                $end_minute=date('i',strtotime($job->end_time));
            ?>
            <div class="col-md-12">
		  		<label for="inputLastName" class="col-sm-3 control-label">Start time</label>
		  		
                <div class="col-sm-3">
                    <select name="start_time_hour" required class="form-control">
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
                    <select name="start_time_minute" required class="form-control">
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
                        <option value="15" <?php if($start_minute=="14"){echo "selected";}?>>15</option>
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
		  		<div class="col-sm-2">
		  			<select name="time_one" required class="form-control">

		  				<option value="am" <?php if(date('A', strtotime($job->start_time))=='AM'){echo "selected";}?>>am</option>
		  				<option value="pm" <?php if(date('A', strtotime($job->start_time))=='PM'){echo "selected";}?>>pm</option>
		  			</select>
		  		</div>
		  	</div>
		  	</div>
		  	<div class="form-group">
		  		<!--<div class="col-md-12">
		  		<label for="inputLastName" class="col-sm-3 control-label t">End time</label>
		  		<div class="col-sm-7">
		  			<input type="time" name="end_time" class="form-control" id="inputEmail" placeholder="Enter end time" value="<?php if($job->end_time!=''){echo date("h:i", strtotime($job->end_time));}?>" required >
		  			<div class="help-block with-errors"></div>
		  		</div>
		  		<div class="col-sm-2">
		  			<select name="time_two" required class="form-control">
		  				<option value="am" <?php if(date('A', strtotime($job->end_time))=='AM'){echo "selected";}?>>am</option>
		  				<option value="pm" <?php if(date('A', strtotime($job->end_time))=='PM'){echo "selected";}?>>pm</option>
		  			</select>
		  		</div>
		  	</div>-->
                <div class="col-md-12">
		  		<label for="inputLastName" class="col-sm-3 control-label t">End time</label>
		  		
                    <div class="col-sm-3">
                    <select name="end_time_hour" required class="form-control">
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
                    <select name="end_time_minute" required class="form-control">
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
		  		<div class="col-sm-2">
		  			<select name="time_two" required class="form-control">
		  				<option value="am" <?php if(date('A', strtotime($job->end_time))=='AM'){echo "selected";}?>>am</option>
		  				<option value="pm" <?php if(date('A', strtotime($job->end_time))=='PM'){echo "selected";}?>>pm</option>
		  			</select>
		  		</div>
		  	</div>
		  	</div>
            <input type="hidden" id="hidden_store_id" value="">
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Store</label>
		  		<div class="col-sm-7">
			  		<select name="store_id"  required class="form-control" onchange="get_tester(this.value,<?php echo $job->taster_id;?>,<?php echo $job->wine_id;?>)">
			  			<option value="">Select store</option>
			  			<?php
                            
			  				foreach($store as $value){
			  			?>
			  			<option value="<?php echo $value['id'];?>" <?php if($value['id']==$job->store_id){echo "selected";}?>><?php echo $value['name'];?></option>
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Admin note</label>
		  		<div class="col-sm-7">
		  			<textarea name="admin_note" class="form-control" id="admin_note"  placeholder="Enter admin note"><?php echo $job->admin_note;?></textarea>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Taster note</label>
		  		<div class="col-sm-7">
		  			<textarea name="taster_note" class="form-control" id="taster_note"  placeholder="Enter taster note"><?php echo $job->taster_note;?></textarea>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary oc"><span class="glyphicon glyphicon-ok-sign"></span>Publish</button> or <a href="<?php echo base_url('App/job');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>
	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>
    		<?php
		  		$taster_id=$job->taster_id;
		  		$taster_id_array=explode(",",$taster_id);
		  	?>
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Assign taster/agency</label>
		  		<div class="col-sm-7">
			  		<select name="taster_id[]"  required class="form-control"  id="testers">
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
			  					echo $value['first_name']." ".$value['last_name'];
			  				}
			  				
			  			?>
			  				
			  			</option>
			  			<?php } ?>
			  		</select>
			  		<br/>
			  		<!--<input type="button" id="select_all" name="select_all" value="Select All">-->
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
		  	<?php
		  		/*$question_id=$job->question_id;
		  		$question_id_array=explode(",",$question_id);*/
		  	?>
		  	<!--<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Question answers</label>
		  		<div class="col-sm-7">
			  		<select name="question_id[]"  class="form-control" multiple="multiple" >
			  			<option value="">Select question</option>
			  			<?php
			  				foreach($question_answers as $value){
			  			?>
			  			<option value="<?php echo $value['id'];?>" <?php if(in_array($value['id'],$question_id_array)){echo "selected";}?>><?php echo $value['question'];?></option>
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>-->
		  	<?php
		  		$wine_id=$job->wine_id;
                
                //echo $wine_id;die;
		  		$wine_id_array=explode(",",$wine_id);
		  	?>
		  	<!--<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Select wine</label>
		  		<div class="col-sm-7" id="wines">
			  		
			  			<?php
			  				foreach($wine as $value){
			  			?>
			  		<?php echo $value->name;?>&nbsp;<input type="checkbox" name="wine_id[]"  value="<?php echo $value->id;?>" <?php if(in_array($value->id,$wine_id_array)){echo "checked";}?> class="wine_id">&nbsp;
			  			<?php 
			  				} 
			  			?>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>-->
            <div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label"></label>
		  		<div class="col-sm-7">
		  			<textarea id="fulloptions"  value="" readonly class="form-control"></textarea>
                    
		  		</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label"></label>
		  		<div class="col-sm-7">
		  			<input type="text" id="sw" class="form-control" placeholder="Enter wine name">
                    
		  		</div>
		  	</div>
            		  	<div class="form-group">
                
		  		<label for="inputPhone" class="col-sm-3 control-label">Select wine</label>
                
                
		  		<div class="col-sm-7 sw" id="wines" >
			  		<!--select name="wine_id[]"  required class="form-control" multiple="multiple">
			  			<option value="">Select wine</option>
			  			<?php
			  				//foreach($wine as $value){
			  			?>
			  			<option value="<?php //echo $value->id;?>" <?php //if(in_array($value->id,$wine_id_array)){//echo "selected";}?>><?php //echo $value->name;?></option>
			  			<?php //} ?>
			  		</select-->
			  			<?php
			  				foreach($wine as $value){
			  			?>
			  			
                          <div class="col-md-12" style="margin:6px 0 0 0;">  <input type="checkbox" name="wine_id[]"  value="<?php echo $value->id;?>" style="margin:3px 2px 0 0; float:left;"  class="wine_id" <?php if(in_array($value->id,$wine_id_array)){echo "checked";}?>><label><?php echo $value->name;?></label></div>
			  			<?php
			  				}
			  				
			  			?>
			  		        
			  		
			  			
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
<div class="loader" style="display: none"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">
<style>
    
    .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('<?php echo base_url(); ?>assets/images/full_page_loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}
</style>
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
	$(".chosen-select").chosen({max_selected_options: 2});
	/*var limit = 2;
	$('input.single-checkbox').on('change', function(evt) {
	   if($(this).siblings(':checked').length >= limit) {
	       this.checked = false;
	   }
	});*/
</script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$('.datepicker').datepicker({

    format: 'm/d/yyyy',
    todayHighlight: true,
    autoclose: true,
    startDate: truncateDate(new Date()) 
});
function truncateDate(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}

$(document).ready(function(){
    
    $('.oc').click(function(e) {
        var c=confirm('Are you ready to confirm the job?');
        
        if(c==true)
        {
            $(".loader").fadeIn();
            $("#pj").submit();
            
        }
    });
    
    var wor = new Array();
    var checkValues = $("#wines  input[type='checkbox']:checked").map(function()
    {
        return $(this).val();
    }).get();
    
    var array = checkValues.toString().split(',');
    //console.log(array);
    for(i=0;i<array.length;i++)
    {
        wor.push(array[i]);
        
    }
    $.ajax({
               type:'POST',
               url:"<?php echo base_url(); ?>App/job/set_wine_id/",
               data: {wine_id_array:wor},
               success:function(data){
                    $("#fulloptions").val(data);
               }

        });
   /* $("form").submit(function(e){
        alert(1);
		if ($('input:checkbox').filter(':checked').length < 1){
        		alert("Select at least one Wine!");
		return false;
		}
		else
		{
			//e.preventDefault();
            var wine_id_array=[];
	        $.each($("input[name='wine_id[]']:checked"), function(){            
                wine_id_array.push($(this).val());
            });
			
	        $.ajax({
			   type:'POST',
			   url:"<?php //echo base_url(); ?>App/job/get_wine_flavour/",
			   data: {wine_id:wine_id_array},
			   success:function(data){
			   		console.log(data);
			    	if(data==4)
			    	{
			    		//alert(1);
			    		//e.preventDefault();
			    		alert('You can not select different types of wine together');
			    		return false;
			    	}
			    	else
			    	{
			    		$("#pj").submit();
			    	}
			    	
			   }
			});
		}
    	
	});   */
    
    $('#select_all').click(function() {
       $('#testers option').prop('selected', true);
});

function get_tester(id,taster_id,wine_id)
{
    $("#hidden_store_id").val(id);
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

var checkboxes = $("#wines  input[type='checkbox']");
//var wine_id_array = new Array();

checkboxes.on('change', function() {
    //Set the wine id to session using ajax
    var selected_wine=$(this).val();
    wor.push(selected_wine);
   // console.log(wor);
    $.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/set_wine_id/",
		   data: {wine_id_array:wor},
           success:function(data){
		    	$("#fulloptions").val(data);
		   }
		   
	});
    //End
    
    
});

$("#sw").keyup(function () {
    
    var search_key = $(this).val();
    var hidden_store_id=$("#hidden_store_id").val();
    
    
    $.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/get_wine_using_search_key/",
		   data: {store_id:hidden_store_id,search_key:search_key},
		   success:function(data){
		    	$("#wines").html(data);
		   }
	});
        
});
	
});

</script>
