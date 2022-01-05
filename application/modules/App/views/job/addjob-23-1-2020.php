<style>
.sw { border:2px solid #ccc; width:366px; height: 206px; overflow-y: scroll;
</style>
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-list"></span> Add job &raquo; <small></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li class="active"><a href="<?php echo base_url('App/Job');?>"><span class="glyphicon glyphicon-list"></span> Job</a></li>
                <li><a href="<?php echo base_url('App/Job/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Job</a></li>
    		</ul>
        </div>
    </div>
</div>
<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/job');?>">Job Management</a></li>
		<li class="active">Add job</li>
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
		echo form_open_multipart(base_url('App/job/add/'), $attributes);

		if($this->session->userdata('inputdata'))
		{
			$allinput=$this->session->userdata('inputdata');
			$this->session->unset_userdata('inputdata');
		}
        
    ?>
	<div class="col-sm-6">
      	<fieldset>
    		<legend>Basic Info</legend>
            <?php
                if($this->session->userdata('wine_ids'))
                {
                    /*$wine_ids=$this->session->userdata('wine_ids');
                    $names=get_wine_names($wine_ids);
                    //echo $names;die();
                    $this->session->unset_userdata('wine_ids');*/
                    $names='';
                    
                }
                else
                {
                    $names='';
                }
                
            ?>
    		<input type="hidden" id="hidden_wine_session_id" value="<?php echo $names;?>">
            <div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Sales Rep*</label>
                <input type="hidden" id="hidden_sales_rep_id" value="<?php if(isset($allinput['user_id']))
{ echo $allinput['user_id'];}?>">
		  		<div class="col-sm-7">
			  		<select name="user_id"  required class="form-control" onchange="get_store(this.value)">
			  			<option value="">Select sales rep</option>
			  			<?php
			  				foreach($sales_rep as $value){
			  			?>
			  			<?php
			  				if(isset($allinput['user_id']))
			  				{
			  			?>
                        
			  			<option value="<?php echo $value['id'];?>" <?php if($value['id']==$allinput['user_id']){echo "selected";}?>><?php echo $value['last_name']." ".$value['first_name'];?></option>
			  			<?php
			  				}
			  				else
			  				{
			  			?>
			  			<option value="<?php echo $value['id'];?>"><?php echo $value['last_name']." ".$value['first_name'];?></option>
			  			<?php
			  				}
			  			?>
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Job date*</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="tasting_date" class="form-control datepicker" id="tasting_date" placeholder="Enter job date" value="<?php if(isset($allinput['tasting_date']) && $allinput['tasting_date']!=''){echo date("m/d/Y", strtotime($allinput['tasting_date']));} ?>"  readonly>
		  			<div class="help-block with-errors df"></div>
		  		</div>
		  	</div>
			<div class="form-group">
			<div class="col-md-12">
		  		<label for="inputLastName" class="col-sm-3 control-label">Start time*</label>
		  		<!--<div class="col-sm-7">
		  			<input type="time" name="start_time" class="form-control t" id="inputEmail" placeholder="Enter start time" value="<?php //echo set_value('start_time'); ?>" required >
		  			<div class="help-block with-errors"></div>
		  		</div>-->
                <div class="col-sm-3">
                    <select name="start_time_hour" required class="form-control">
                        <option value="">Hour</option>
		  				<option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>

		  			</select>
                </div>
                <div class="col-sm-3">
                    <select name="start_time_minute" required class="form-control">
                        <option value="">Minute</option>
		  				
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                        <option value="32">32</option>
                        <option value="33">33</option>
                        <option value="34">34</option>
                        <option value="35">35</option>
                        <option value="36">36</option>
                        <option value="37">37</option>
                        <option value="38">38</option>
                        <option value="39">39</option>
                        <option value="40">40</option>
                        <option value="41">41</option>
                        <option value="42">42</option>
                        <option value="43">43</option>
                        <option value="44">44</option>
                        <option value="45">45</option>
                        <option value="46">46</option>
                        <option value="47">47</option>
                        <option value="48">48</option>
                        <option value="49">49</option>
                        <option value="50">50</option>
                        <option value="51">51</option>
                        <option value="52">52</option>
                        <option value="53">53</option>
                        <option value="54">54</option>
                        <option value="55">55</option>
                        <option value="56">56</option>
                        <option value="57">57</option>
                        <option value="58">58</option>
                        <option value="59">59</option>
                        

		  			</select>
                </div>
		  		<div class="col-sm-2">
		  			<select name="time_one" required class="form-control">

		  				<option value="am">am</option>
		  				<option value="pm">pm</option>
		  			</select>
		  		</div>
		  	</div>
		  	</div>
		  	<div class="form-group">
		  		<div class="col-md-12">
		  		<label for="inputLastName" class="col-sm-3 control-label t">End time*</label>
		  		<!--<div class="col-sm-7">
		  			<input type="time" name="end_time" class="form-control" id="inputEmail" placeholder="Enter end time" value="<?php //echo set_value('end_time'); ?>" required >
		  			<div class="help-block with-errors"></div>
		  		</div>-->
                    <div class="col-sm-3">
                    <select name="end_time_hour" required class="form-control">
                        <option value="">Hour</option>
		  				<option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>

		  			</select>
                </div>
                <div class="col-sm-3">
                    <select name="end_time_minute" required class="form-control">
                        <option value="">Minute</option>
		  				<option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                        <option value="32">32</option>
                        <option value="33">33</option>
                        <option value="34">34</option>
                        <option value="35">35</option>
                        <option value="36">36</option>
                        <option value="37">37</option>
                        <option value="38">38</option>
                        <option value="39">39</option>
                        <option value="40">40</option>
                        <option value="41">41</option>
                        <option value="42">42</option>
                        <option value="43">43</option>
                        <option value="44">44</option>
                        <option value="45">45</option>
                        <option value="46">46</option>
                        <option value="47">47</option>
                        <option value="48">48</option>
                        <option value="49">49</option>
                        <option value="50">50</option>
                        <option value="51">51</option>
                        <option value="52">52</option>
                        <option value="53">53</option>
                        <option value="54">54</option>
                        <option value="55">55</option>
                        <option value="56">56</option>
                        <option value="57">57</option>
                        <option value="58">58</option>
                        <option value="59">59</option>
                        

		  			</select>
                </div>
		  		<div class="col-sm-2">
		  			<select name="time_two" required class="form-control">
		  				<option value="am" >am</option>
		  				<option value="pm" >pm</option>
		  			</select>
		  		</div>
		  	</div>
		  	</div>
            <!--<input type="hidden" id="hidden_store_id" value="">-->
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Store*</label>
                <input type="hidden" id="hidden_store_id" value="<?php if(isset($allinput['store_id'])){echo $allinput['store_id'];}?>">
		  		<div class="col-sm-7">
			  		<select name="store_id"  required class="form-control" id="store" onchange="get_tester(this.value)">
			  			<option value="">Select store</option>
			  			<?php
			  				foreach($store as $value){

			  			?>
			  			<?php
			  				if(isset($allinput['store_id']))
			  				{
			  			?>
			  			<option value="<?php echo $value->id;?>" <?php if($value->id==$allinput['store_id']){echo "selected";}?>><?php echo $value->name;?></option>
			  			<?php
			  				}
			  				else
			  				{
			  			?>
			  			<option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
			  			<?php
			  				}
			  			?>
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Admin note</label>
		  		<div class="col-sm-7">
		  			<textarea name="admin_note" class="form-control" id="admin_note"  placeholder="Enter admin note"><?php if(isset($allinput['admin_note'])){echo $allinput['admin_note'];} ?></textarea>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Taster note</label>
		  		<div class="col-sm-7">
		  			<textarea name="taster_note" class="form-control" id="taster_note"  placeholder="Enter taster note"><?php if(isset($allinput['taster_note'])){echo $allinput['taster_note'];} ?></textarea>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" id="submit_button"><span class="glyphicon glyphicon-ok-sign"></span>Publish</button> or <a href="<?php echo base_url('App/job');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>
	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>
    		
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Assign taster/agency*</label>
		  		<div class="col-sm-7">
			  		<select name="taster_id[]"  class="form-control"  id="testers" >
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
			  			<option value="<?php echo $value['id'];?>" <?php if(isset($allinput['taster_id']) && ($allinput['taster_id']==$value['id'])){echo "selected";}?>><?php 
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
                    <br/>
			  		<!--<input type="button" id="select_all" name="select_all" value="Select All">-->
			  		<div class="help-block with-errors"></div>
			  		
			  	</div>
		  	</div>
		  	
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
                
                
		  		<div class="col-sm-7 sw" id="wines">
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
			  			<?php if(isset($allinput['wine_id']))
			  				{
                                //echo 1;die;
                                //echo "<pre>";
                                //print_r($wine_ids);die;
			  			?>
                          <div class="col-md-12" style="margin:6px 0 0 0;">  <input type="checkbox" name="wine_id[]"  value="<?php echo $value->id;?>" style="margin:3px 2px 0 0; float:left;"  class="wine_id"><label><?php echo $value->name;?></label></div>
			  			<?php
			  				}
			  				else
			  				{
                               // echo 2;die;
			  			?>
			  		        <div class="col-md-12" style="margin:6px 0 0 0;"> <input type="checkbox" name="wine_id[]"  style="margin:3px 2px 0 0; float:left;" value="<?php echo $value->id;?>"  class="wine_id"  ><label><?php echo $value->name;?></label></div>
			  		<?php
			  				}
			  		?>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">
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
    startDate: truncateDate(new Date()),
    
});
function truncateDate(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}

$(document).ready(function(){
    
    var hidden_sales_rep_id=$("#hidden_sales_rep_id").val();
    
    var hidden_store_id=$("#hidden_store_id").val();
   
    var hidden_wine_session_id=$("#hidden_wine_session_id").val();
    $("#fulloptions").val('');
    /*if(hidden_wine_session_id!='')
    {
       // alert(hidden_wine_session_id);
        $("#fulloptions").val(hidden_wine_session_id);
    }
    else
    {
       // alert(1);
        $("#fulloptions").val('');
    }*/
   // alert(hidden_wine_session_id);
    
    if(hidden_sales_rep_id!='')
    {
        
            //var sales_rep_id=id;
            $.ajax({
                   type:'POST',
                   url:"<?php echo base_url(); ?>App/job/get_store_for_sales_rep/",
                   data: {sales_rep_id:hidden_sales_rep_id,hidden_store_id:hidden_store_id},
                   success:function(data){
                        $("#store").html(data);
                   }
            });
            if(hidden_store_id!='')
            {
                $.ajax({
                       type:'POST',
                       url:"<?php echo base_url(); ?>App/job/get_tester/",
                       data: {store_id:hidden_store_id},
                       success:function(data){
                            $("#testers").html(data);
                       }
                });
                
                $.ajax({
                       type:'POST',
                       url:"<?php echo base_url(); ?>App/job/get_wine/",
                       data: {store_id:hidden_store_id},
                       success:function(data){
                            $("#wines").html(data);
                           // $("#fulloptions").val(hidden_wine_session_id);
                       }
                });
            }
            
            
        
    }
    
    $("form").submit(function(e){
            e.preventDefault();
            
            if(!$("#submit_button").hasClass("disabled"))
            {
                var c=confirm('Are you ready to confirm the job?');
                if(c==true)
                {
                    //alert(1);
                    if ($('input:checkbox').filter(':checked').length < 1){
                        alert("Select at least one Wine!");
                        return false;
                    }
                    else
                    {
                        $('form').unbind('submit').submit();
                    }
                }
                
            }
            else
            {
                return false;
            }
            
		/*else
		{
			//e.preventDefault();
            var wine_id_array=[];
	        $.each($("input[name='wine_id[]']:checked"), function(){            
                wine_id_array.push($(this).val());
            });
			
	        $.ajax({
			   type:'POST',
			   url:"<?php echo base_url(); ?>App/job/get_wine_flavour/",
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
    	*/
	});         
	
});
$('#select_all').click(function() {
       $('#testers option').prop('selected', true);
});
function get_tester(id)
{
    $("#hidden_store_id").val(id);
	if(id!='')
	{
		$.ajax({
			   type:'POST',
			   url:"<?php echo base_url(); ?>App/job/get_tester/",
			   data: {store_id:id},
			   success:function(data){
			    	$("#testers").html(data);
			   }
		});
		$.ajax({
			   type:'POST',
			   url:"<?php echo base_url(); ?>App/job/get_wine/",
			   data: {store_id:id},
			   success:function(data){
                    console.log(data);
			    	$("#wines").html(data);
                    $("#fulloptions").val('');
			   }
		});
	}
}
function get_store(id)
{
    //var sales_rep_id=id;
    $.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/get_store_for_sales_rep/",
		   data: {sales_rep_id:id},
		   success:function(data){
		    	$("#store").html(data);
		   }
	});
    
}
var checkboxes = $("#wines  input[type='checkbox']");
var wine_id_array = new Array();

checkboxes.on('change', function() {
    
    //Set the wine id to session using ajax
    var selected_wine=$(this).val();
    wine_id_array.push(selected_wine);
    $.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/set_wine_id/",
		   data: {wine_id_array:wine_id_array},
           success:function(data){
		    	$("#fulloptions").val(data);
		   }
		   
	});
    //End
    
    /*$('#fulloptions').val(
        
        checkboxes.filter(':checked').map(function(item) {
            //alert(checkboxes);
            var t=$(this).next('label').text();
            var w_id=$(this).val();
            $('input[name=wine_id]').val(w_id);
            return t;
            //return this.value;
        }).get().join(', ')
        
     );*/
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
$('#select_all').click(function() {
       $('#testers option').prop('selected', true);
});
</script>
