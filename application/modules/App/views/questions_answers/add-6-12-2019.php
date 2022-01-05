<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span>Create Question Answers</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/questions_answers');?>"><span class="glyphicon glyphicon-user"></span> Question Answers</a></li>
    			<li class="active"><a href="<?php echo base_url('App/questions_answers/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Create Question Answers</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/questions_answers');?>">Question Answers Management</a></li>
		<li class="active">Create Question Answers</li>
	</ol>

	<?php
		//form validation
		echo validation_errors();

		$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open(base_url('App/questions_answers/add'),$attributes);
      ?>
	<div class="col-sm-6">

      	<fieldset>
    		<legend>Questions</legend>
	      	
    		
		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Type Question</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="question" class="form-control" id="question" placeholder="Type your question" value="<?php echo set_value('question'); ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Answer Type</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="answer_type" id="checkboxActive" value="text" <?php if(set_value('answer_type') != "text") echo "checked";?>>
					    Text
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="answer_type" id="checkboxinactive" value="multiple" <?php if(set_value('answer_type') == "multiple") echo "checked";?>>
					    Multiple
					  </label>
					</div>
				</div>
		  	</div>

		  	
		  	

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save</button> or <a href="<?php echo base_url('App/questions_answers');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>

	<div class="col-sm-6 dd">
		
    	<fieldset>
    		<legend>Answers</legend>
    		<div class="form-group">
		  		<label for="inputAnswer1" class="col-sm-3 control-label">Answer 1</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="answer_one" class="form-control" id="answer1" placeholder="Type your answer" value="<?php echo set_value('answer_one'); ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputAnswer2" class="col-sm-3 control-label">Answer 2</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="answer_two" class="form-control" id="answer2" placeholder="Type your answer" value="<?php echo set_value('answer_two'); ?>">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputAnswer3" class="col-sm-3 control-label">Answer 3</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="answer_three" class="form-control" id="answer3" placeholder="Type your answer" value="<?php echo set_value('answer_three'); ?>">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputAnswer4" class="col-sm-3 control-label">Answer 4</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="answer_four" class="form-control" id="answer4" placeholder="Type your answer" value="<?php echo set_value('answer_four'); ?>">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		    

			
    	</fieldset>
	</div>

	
	<?php echo form_close();?>
</div>


<script type="text/javascript">
 $(document).ready(function(){
 	var radio = $("input[name='answer_type']:checked").val();
 	//alert(radio);	
 	if(radio=='text')
 	{
 		$(".dd").hide();
 	}
 	else
 	{
 		$(".dd").show();
 	}
 });

 $('input[type=radio][name=answer_type]').change(function() 
 {
        

	var value = $(this).val();

	if (value == 'multiple'){

		$(".dd").show();

	}
	else{
		$(".dd").hide();
	}
});
 
</script>

