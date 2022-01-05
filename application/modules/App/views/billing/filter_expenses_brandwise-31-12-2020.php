<div class="subnav">
  <div class="container-fluid">
    <h1><span class="glyphicon glyphicon-print"></span>Filter expenses</h1>
    <div id="sub-menu" class="pull-right">
      <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo base_url('App/Billing');?>"><span class="glyphicon glyphicon-print"></span> Billing</a></li>
      </ul>
    </div>
  </div>
</div>
<div class="container-fluid main">
  <div class="form-group"> 
    
  </div>
  <ol class="breadcrumb">
    <li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
    <li class="active">Filter expenses by brand</li>
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
		echo validation_errors();

		$attributes = array('class' => 'form-inline search-form', 'id' => 'billing-search-form', 'role' => 'form');
		echo form_open(base_url('App/billing/get_expenses_brandwise'), $attributes);
	?>
       

  <fieldset>

    <legend><span class="glyphicon glyphicon-filter"></span> Filters</legend>

    <div class="row">
      <div class="col-md-12">
        
         <div class="col-sm-2">
          <div class="form-group">
              
              
            <label for="inputType">Search By </label>

            <select name="brand" id="inputField" class="form-control" required>
              <option value="" selected>Select brand</option>
              <?php
                
                foreach($brand as $value){
              ?>
                <option value="<?php echo $value['brand']?>" <?php echo ($filter['brand'] == $value['brand']) ? 'selected':'' ?> ><?php echo $value['brand']?></option>
              <?php
                }
              ?>
            </select>
          </div>
          </div>
          <div class="col-sm-2">
            <div class="form-group">
              
              
            <label for="inputType">From</label>

            <input type="text" class="form-control datepicker" id="from_date" name="from_date" placeholder="Search here" value="<?php if (isset($filter['from_date'])) {echo $filter['from_date'];}?>" required>
              
            
           </div>
          </div>
          <div class="col-sm-2">
            <div class="form-group">
              
              
            <label for="inputType">To</label>
            <input type="text" class="form-control datepicker" id="to_date" name="to_date" placeholder="Search here" value="<?php if (isset($filter['to_date'])) {echo $filter['to_date'];}?>" required>
            
          </div>
          </div>
          <div class="col-sm-3 search-btn">
          <div class="form-group">
            <label style="margin:30px 0 0 0;">&nbsp;</label>
            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
            &nbsp;
            <button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/billing/get_expenses_brandwise');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
          </div>
          </div>
        
        
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">&nbsp;</div>
    </div>
  </fieldset>
  <?php echo form_close();?>
  
  <div class="table-responsive"> 
    <!-- Table -->
    <table class="table table-striped table-responsive" width="100%">
      <thead>
        <tr>
          
          <th>Tasting date</th>
          <th>Sales rep</th>
          
          <th>Store</th>
          <th>Expenses</th>
          
          
        </tr>
      </thead>
      <tbody>
        
        <?php
            if(isset($expense_details) && !empty($expense_details)){
        ?>
        <?php
            foreach($expense_details as $details){
                
                if($details['has_wine']=='yes')
                {
        ?>
        <tr>
          <td><?php echo date("m/d/Y", strtotime($details['tasting_date']));?></td>
          <td><?php echo $details['sales_rep_name'];?></td>
          
          <td><?php echo $details['store_name'];?></td>
          <td><?php if($details['expense_amount']!='$'){echo $details['expense_amount'];}else{echo '--';}?></td>
        </tr>
        <?php } }?>
       <?php 
            }
          else
          {
        ?>
          <tr>
          <td colspan="5">No data found</td>
          
        </tr>
       <?php
            }
          ?>
      </tbody>
    </table>
  </div>
  
   </div>
<script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$('.datepicker').datepicker({

    format: 'yyyy-mm-dd',
    todayHighlight: true,
    autoclose: true,
    //startDate: truncateDate(new Date()) 
});
function truncateDate(date) {
  return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}
</script>
