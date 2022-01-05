<div class="container">
  <div class="row">
    <?php
      $attributes = array('class' => '', 'id' => 'frm-subscribe', 'role' => 'form', 'data-toggle' => 'validator');
      echo form_open(base_url('home/buy'), $attributes);
    ?>
    <div class="col-md-8 col-md-offset-2">
      <div class="logo"><a href="<?php echo base_url();?>"><img src="<?php echo base_url('assets/images/logo.png');?>" border="0" alt="PHIT APP" /></a></div>
      <div class="sub-heading"> 
        Sub-Total: $<?php echo number_format($price,2);?><br />
        <?php /*?>TAX (if applicable): $<?php echo number_format($tax,2);?><br /><?php */?>
        Shipping:<br />
        <?php foreach ($shipping as $type => $ship) {?>
          <label class="radio-inline">
            <input type="radio" name="shipping_type" data-price="<?php echo $ship['price'];?>" value="<?php echo $type;?>" onclick="calculateTotal()" <?php if($ship['default']) echo "checked";?>> <?php echo $ship['name'];?>
          </label>
        <?php }?>
        <br />
        Total: $<span id="htmlTotal"><?php echo number_format($total,2);?></span> 
      </div>
      <input type="hidden" id="inputItem" value="<?php echo number_format($price,2);?>">
      <input type="hidden" id="inputTax" value="<?php echo number_format($tax,2);?>">
      <input type="hidden" id="inputTotal" value="<?php echo number_format($total,2);?>">
      
      <?php
        //form validation
        echo validation_errors();
      ?>
      <fieldset>
        <h3 class="panel-title">Recipient Information</h3>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <input class="form-control" placeholder="First Name" id="inputFName" name="first_name" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <input class="form-control" placeholder="Last Name" id="inputLName" name="last_name" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <input class="form-control" placeholder="Street Line 1" id="inputAddr1" name="address1" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <input class="form-control" placeholder="Street Line 2" id="inputAddr2" name="address2" type="text" >
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <input class="form-control" placeholder="City" id="inputCity" name="city" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input class="form-control" placeholder="State(TX)" id="inputState" name="state" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input class="form-control" placeholder="Zip Code" id="inputZip" name="zip" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>
        <div class="checkbox">
          <label>
            <input name="remember" type="checkbox" value="Same as above" onclick="copy()">
            Same as above </label>
        </div>
        <h3 class="panel-title">Shipping Information</h3>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <input class="form-control" placeholder="First Name" id="inputRecFName" name="shipping_first_name" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <input class="form-control" placeholder="Last Name" id="inputRecLName" name="shipping_last_name" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <input class="form-control" placeholder="Email Address" id="inputEmail" name="email" type="email" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <input class="form-control" placeholder="Phone Number" id="inputPhone" name="phone" type="text" >
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <input class="form-control" placeholder="Street Line 1" id="inputRecAddr1" name="shipping_address1" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <input class="form-control" placeholder="Street Line 2" id="inputRecAddr2" name="shipping_address2" type="text" >
              <div class="help-block with-errors"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <input class="form-control" placeholder="City" id="inputRecCity" name="shipping_city" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input class="form-control" placeholder="State(TX)" id="inputRecState" name="shipping_state" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input class="form-control" placeholder="Zip Code" id="inputRecZip" name="shipping_zip" type="text" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group phit-text"> Please be advised that all payments will be temporarily processed through a partnered company, UNE LLC, as we work to resume operations for PHIT APP LLC and it’s direct bank account </div>
          </div>
          <div class="col-md-12">
            <input type="submit" class="btn btn-lg btn-success btn-block" value="PURCHASE">
          </div>
        </div>
      </fieldset>
    </div>
    <?php echo form_close();?> </div>
  </div>
</div>
<script>
    function copy() {
        jQuery("#inputRecFName").val(jQuery("#inputFName").val());
        jQuery("#inputRecLName").val(jQuery("#inputLName").val());
        jQuery("#inputRecAddr1").val(jQuery("#inputAddr1").val());
        jQuery("#inputRecAddr2").val(jQuery("#inputAddr2").val());
        jQuery("#inputRecCity").val(jQuery("#inputCity").val());
        jQuery("#inputRecState").val(jQuery("#inputState").val());
        jQuery("#inputRecZip").val(jQuery("#inputZip").val());
    }

    function calculateTotal() {
      var shipping_type = jQuery("input[name=shipping_type]:checked").val();
      var shipping_amt = jQuery("input[name=shipping_type]:checked").data('price');
      
      var item = jQuery("#inputItem").val();
      var tax = jQuery("#inputTax").val();
      var total = parseFloat(item) + parseFloat(tax) + parseFloat(shipping_amt);

      /*console.log(shipping_amt);
      console.log(item);
      console.log(tax);
      console.log(total);*/
      jQuery("#inputTotal").val(total);
      jQuery("#htmlTotal").html(total);
      
    }
</script> 
