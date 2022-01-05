<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-select.css">
<style>
  .bootstrap-select .dropdown-menu {
    width:100%;
  }
  .bootstrap-select {
      width: 100% \0;
      vertical-align: middle;
      width: 100% !important;
}
.show-tick.bootstrap-select .dropdown-menu .selected span.check-mark{
    left: 2px;
}
  </style>
<div class="subnav">
  <div class="container-fluid">
    <h1><span class="glyphicon glyphicon-print"></span> Reports</h1>
    <div id="sub-menu" class="pull-right">
      <ul class="nav nav-pills">
        <!-- <li class="active"><a href="<?php echo base_url('App/Billing');?>"><span class="glyphicon glyphicon-print"></span> Billing</a></li> -->
      </ul>
    </div>
  </div>
</div>

<div class="container-fluid main">
  <?php if ($this->session->userdata('role')!='brand_wise_users'){ ?>
  <ol class="breadcrumb">
    <li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
    <li class="active">Reports</li>
  </ol>
<?php } ?>

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
      <!-- <div class="col-sm-12"> -->
         <div class="col-sm-2">
            <div class="form-group" style="width:100%;">
            <label for="inputType">Date From</label><br/>
            <input type="text" class="form-control datepicker" id="from_date" name="from_date" placeholder="Search here" autocomplete="off" style="width:100%;" value="<?php if (isset($filter['from_date'])) {echo $filter['from_date'];}?>" required>
           </div>
          </div>

          <div class="col-sm-2">
            <div class="form-group" style="width:100%;">
            <label for="inputType">Date To</label><br/>
            <input type="text" class="form-control datepicker" id="to_date" name="to_date" placeholder="Search here" autocomplete="off" style="width:100%;" value="<?php if (isset($filter['to_date'])) {echo $filter['to_date'];}?>" required>
          </div>
          </div>

         <div class="col-sm-3">
          <div class="form-group" style="width:100%;">
            <label for="inputType">Brand </label><br/>
            <select name="brand[]" id="brand" class="selectpicker" multiple data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true">
              <!-- <option selected>Select brand</option> -->
              <?php
               
                foreach($brand as $value){
              ?>
                <option value="<?php echo $value['brand'];?>" <?php if(count($filter['brand'])>0){ foreach($filter['brand'] as $brand){ if($brand==$value['brand']){echo "selected"; break;}}}?>><?php echo $value['brand'];?></option>
              <?php } ?>
            </select>
          </div>
          </div>

          <div class="col-sm-3">
                <div class="form-group" style="width:100%;">
                <label for="inputName">Wine Type </label><br />
                <select name="wine_type[]" id="wine_type" class="selectpicker" multiple data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true">

                <?php
			  				foreach($wine_type as $value){
                    ?>
                     <option value="<?php echo $value->id;?>" <?php if(count($filter['wine_type'])>0){ foreach($filter['wine_type'] as $type){ if($type==$value->id){echo "selected"; break;}}}?>><?php echo $value->name;?></option>
                  <?php 
                      } 
                    ?>
                    </select>
                </div>
            </div>


            <div class="col-sm-2">
                <div class="form-group" style="width:100%;">
                <label for="inputName">Size </label><br />
                <select name="size[]" class="selectpicker" multiple data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true"">
                            <?php
                    foreach($wine_size as $value){
                    ?>
                      <option value="<?php echo $value;?>" <?php if(count($filter['size'])>0){ foreach($filter['size'] as $size){ if($size==$value){echo "selected"; break;}}}?>><?php echo $value;?></option>
                    <?php } ?>
                            </select>
                    </div>
            </div>

            <!-- </div> -->
           </div>

           <div class="row">
            <!-- <div class="col-sm-12">  -->

            <div class="col-sm-2">
            <div class="form-group" style="width:100%;">
            <label for="inputName">Store</label><br/>
            <select name="search_by_store[]" id="search_by_store" class="selectpicker" multiple data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true" style="width:100%;">
                <!-- <option value="">Choose store</option> -->
                <?php 
                    foreach($store as $val)
                    {
                ?>
                <option value="<?php echo $val->id;?>" <?php if(count($filter['search_by_store'])>0){ foreach($filter['search_by_store'] as $store_id){ if($store_id==$val->id){echo "selected"; break;}}}?>><?php echo $val->name;?></option>
            <?php }?>
            </select>
            </div>
        </div>


        <div class="col-sm-2">
                    <div class="form-group" style="width:100%;">
                        <label for="inputName">Taster </label><br />
                        <select name="taster[]" id="taster" class="selectpicker" multiple data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true" style="width:100%;">
                            
                            <?php 
                            
                                $taster_id=$filter['taster'];
                                $taster_id_array=explode("@",$taster_id);
                                foreach($taster as $user)
                                {
                                  $name=$user['last_name']." ".$user['first_name'];
                            ?>
                             <option value="<?php echo $user['id'];?>" <?php if(count($filter['taster'])>0){ foreach($filter['taster'] as $taster_id){ if($taster_id==$user['id']){echo "selected"; break;}}}?>><?php echo $name;?></option>
                        <?php }?>
                        </select>
                    </div>
                </div> 
            
            <div class="col-sm-3">
                    <div class="form-group" style="width:100%;">
                        <label for="inputName">Agency </label><br />
                        <select name="agency[]" id="agency" class="selectpicker" multiple data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true" style="width:100%;">
                            
                            <?php 
                            
                                $agency_id=$filter['agency'];
                                
                                $taster_id_array=explode("@",$agency_id);
                                foreach($agency as $user)
                                {
                                  $name=get_agency_name('user_meta',$user['id']);
                            ?>
                             <option value="<?php echo $user['id'];?>" <?php if(count($filter['agency'])>0){ foreach($filter['agency'] as $agency_id){ if($agency_id==$user['id']){echo "selected"; break;}}}?>><?php echo $name;?></option>
                        <?php }?>
                        </select>
                    </div>
                </div> 

                  <!-- For sales rep-->
            <div class="col-sm-3">
            <div class="form-group" style="width:100%;">
                <label for="inputName">Sales rep </label><br />
                <select name="sales_rep[]" id="sales_rep" class="selectpicker" multiple data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true">
                    <?php 
                        foreach($sales_rep as $val)
                        {
                    ?>
                      <option value="<?php echo $val['id'];?>" <?php if(count($filter['sales_rep'])>0){ foreach($filter['sales_rep'] as $salesrep_id){ if($salesrep_id==$val['id']){echo "selected"; break;}}}?>><?php echo $val['last_name']." ".$val['first_name'];?></option>

                <?php }?>
                </select>
            </div>
            </div> 

          <div class="col-sm-2 search-btn">
          <div class="form-group" style="width:200px; margin-left: -6px;">
            <label style="margin:30px 0 0 0;">&nbsp;</label>
           <button type="submit" style="width:90px;" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Preview</button>&nbsp;
           <button type="button" style="width:89px;" class="btn btn-default" onclick="window.location='<?php echo base_url('App/billing/get_expenses_brandwise');?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
          </div>
          </div>
        
        
      <!-- </div> -->
    </div>

    <div class="row">
      <div class="col-sm-12">&nbsp;</div>
    </div>
  </fieldset>
  <?php echo form_close();?>
  <?php
		echo validation_errors();
    $attributes = array('class' => 'form-inline status-form', 'id' => 'user-status-form');

		echo form_open(base_url('App/billing/generate_report_csv'), $attributes);
	?>
  
  <div class="table-responsive"> 
    <!-- Table -->
    <table id="example" class="table table-striped table-responsive" width="100%">
      <thead>
        <tr>
          <th>Tasting date</th>
          <th>Month</th>
          <th>Brand</th>
          <th>Wine Type</th>
          <th>Size</th>
          <th>Store</th>
          <th>Taster</th>
          <th>Agency</th>
          <th>Sales rep</th>
          <th>Bottles Sampled</th>
          <th>Opened Bottles Sampled</th>
          <th>Bottles Sold</th>
          <th>Total Cost</th>
          
        </tr>
      </thead>
      <tbody>
        
        <?php
            if(isset($expense_details) && !empty($expense_details)){
        ?>
        <?php
            $isDataFound=0;
            foreach($expense_details as $ex_details){
                 //echo "<pre>";print_r($ex_details);die;
                foreach($ex_details as $details){
                if($details['has_wine']=='yes')
                {
                  $isDataFound=1;
                  $list_tasterName='N/A';
                  $list_agencyName='N/A';
                  $expense_amount='--';

                  if($details['taster_name'] !=''){ 
                    $list_tasterName=$details['taster_name'];
                  }
                  if($details['agency_name'] !=''){ 
                    $list_agencyName = $details['agency_name'];
                  }
                  if($details['expense_amount']!='$'){
                    $expense_amount = $details['expense_amount'];
                  }

                  $single_row = date("m/d/y", strtotime($details['tasting_date'])).','. date('F', strtotime($details['tasting_date'])).','.$details['brand'].','. $details['wine_type'].','. (double) $details['wine_size'].''.$details['wine_uom'].','. $details['store_name'].','. $list_tasterName.','. $list_agencyName.','. $details['sales_rep_name'].','. $details['bottles_sampled'].','. $details['bottles_sold'].','. $expense_amount;

                  $single_row = str_replace(' ', '%20', $single_row);
                
        ?>
        <tr>
          <td><?php echo date("m/d/y", strtotime($details['tasting_date']));?></td>
          <td><?php echo date('F', strtotime($details['tasting_date']));?></td>
          <td><?php echo $details['brand'];?></td>
          <td><?php echo $details['wine_type'];?></td>
          <td><?php echo (double) $details['wine_size'].''.$details['wine_uom'];?></td>
          <td><?php echo $details['store_name'];?></td>
          <td><?php if($details['taster_name'] !=''){ echo $details['taster_name'];}else{echo 'N/A';}?></td>
          <td><?php if($details['agency_name'] !=''){ echo $details['agency_name'];}else{echo 'N/A';}?></td>
          <td><?php echo $details['sales_rep_name'];?></td>
          <td><?php echo $details['bottles_sampled'];?></td>
          <td><?php echo $details['open_bottles_sampled'];?></td>
          <td><?php echo $details['bottles_sold'];?></td>
          <td><?php if($details['expense_amount']!='$'){echo $details['expense_amount'];}else{echo '--';}?></td>
         
        </tr>
        <?php }} }
        if($isDataFound==0){ ?>
          <tr>
          <td colspan="5">No data found</td>
          
        </tr>
        <?php }
        ?>
       <?php }else{ ?>
          <tr>
          <td colspan="5">No data found</td>
          
        </tr>
       <?php
            }
          ?>
      </tbody>
      <tfoot>
                <tr>
                    <td colspan="12">
                        <!-- <input type="submit" name="Export to csv" value="Export to csv" class="btn btn-success"> -->
                    </td>
                </tr>
            </tfoot>
    </table>
  </div>
   </div>


<script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

<script src="<?php echo base_url();?>assets/js/bootstrap-select.js"></script>

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
$(document).ready(function() {
    $('#example').DataTable( {
    searching: false, 
    paging: false,
    ordering: false,
    info: false,
    dom: 'Bfrtip',
    buttons: [
       {
        extend: 'csv',
        text: 'Export to CSV',
        filename: 'Reports',
        className: 'btn btn-success',
       }
    ]
    } );
} );
</script>
