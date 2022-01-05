<section class="body-container">
	<div class="container">
		<div class="card card-container"> <img id="profile-img" class="profile-img-card" src="<?php echo base_url();?>assets/images/profile-icon.png" />
			<h1 class="signup-heading"><span>Make your payment</span></h1>
      
			<?php
				//form validation
				echo validation_errors();

				if($this->session->flashdata('message_type')) {
					if($this->session->flashdata('message')) {

						echo '<div class="alert alert-'.$this->session->flashdata('message_type').' alert-dismissable">';
						echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
						echo $this->session->flashdata('message');
						echo '</div>';
					}
				}
			?>
					
			<form id="paymentform" class="" role="form" data-toggle="validator" method="post" action="">
					
				<label><input type="radio" id="" name="payment_type" value="ref" onclick="updateFields()" checked > Referal code</label>
				<input type="text" class="form-control" name="code" id="inputCode" maxlength="10" placeholder="Enter Referal code" data-minlength="10" >
				<div class="help-block">Please enter the supplied referral code or choose monthly subscription below.</div>
				<h2 class="or">or</h2>
				<label><input type="radio" id="" name="payment_type" value="sub" onclick="updateFields()"> Monthly subscription</label>
				<div class="checkbox">
					<label>
						<input type="hidden" name="subscription" value="9.99"> $9.99 / month
					</label>
				</div>				
				<div class="checkbox" style='text-align: left;'>
					<label>
						<input type="checkbox" id="tou" name="tou" value="Y" required>
								I want to enter the Mr. Olympia sweepstakes, and have read and understand the rules
					</label>
				</div>
				<button type="submit" class="btn btn-success" id="codePayment">Pay now</button>
				<button type="button" class="btn btn-success" id="stripePayment">Pay now</button>
				
				<input type="hidden" name="stripToken" id="stripToken" />
				<input type="hidden" name="stripCard" id="stripCard" />
			</form>
			<!-- /form --> 
      
		</div>
    <!-- /card-container --> 
    
	</div>
</section>
<!-- /container -->
<div id="overlay">
	<div id="text"><img src="<?php echo base_url('assets/images/loading.gif');?>"> Please wait, while we loading the page.</div>
</div>

<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
	var handler = StripeCheckout.configure({
  		key: '<?php echo STRIPE_PUBLIC_KEY;?>',
  		image: '<?php echo base_url('assets/images/profile-icon.png');?>',
  		locale: 'auto',
		email: '<?php echo $customer->email;?>',
  		token: function(token) {
			
			console.log(token);
    		// You can access the token ID with `token.id`.
    		// Get the token ID to your server-side code for use.
			var $form = jQuery("#paymentform");
			jQuery("#stripToken").val(token.id);
			jQuery("#stripCard").val(token.card.id);
			
			$form.unbind('submit').submit();
			jQuery("#overlay").css('display', 'block');
  		}
	});

	jQuery('#stripePayment').on('click', function(e) {

		// Open Checkout with further options:
	  	handler.open({
	    	name: '<?php echo SITE_NAME;?>',
	    	description: 'Monthly Subscription',
	    	amount: parseInt(9.99*100)
	  	});
	  	//e.preventDefault();
	});

	// Close Checkout on page navigation:
	window.addEventListener('popstate', function() {
	  	handler.close();
	});
	
	jQuery(document).ready(function() {
		updateFields();
	});

	function updateFields() {		
		var payment_type = jQuery('input[name=payment_type]:checked').val();
		if (payment_type == "sub") {
			jQuery("#inputCode").val("");
			
			jQuery("#stripePayment").css("display", "");
			jQuery("#codePayment").css("display", "none");
			
			jQuery("#inputCode").prop("disabled", true);
			jQuery("#inputCode").prop("required", false);
			jQuery("#inputCode").prop("data-minlength", "0");
		} else {
			
			jQuery("#stripePayment").css("display", "none");
			jQuery("#codePayment").css("display", "");
			
			jQuery("#inputCode").prop("disabled", false);
			jQuery("#inputCode").prop("required", true);
			jQuery("#inputCode").prop("data-minlength", "10");
		}
		jQuery("#paymentform").validator('update');
	}
</script>