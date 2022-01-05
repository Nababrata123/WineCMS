<div class="container">
    <div class="page-header">
		<h1>
            Checkout
			<p><small>Review &amp; complete your order.</small></p>
		</h1>
	</div>

    <div class="row">
        <div class="col-md-8">
            <?php 
                $attributes = array('class' => '', 'id' => 'frm-checkout', 'role' => 'form', 'data-toggle' => 'validator');
                echo form_open(base_url('stores/checkout'), $attributes); 
            ?>

            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                1. LOGIN
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <p>Logged in as: <?php echo $this->session->userdata('name');?> (<?php echo $this->session->userdata('email');?>) </p>
                            <p><small>Not <?php echo $this->session->userdata('name');?>? <a href="<?php echo base_url('auth/logout');?>">Logout</a></small></p>
                            <br />
                            <button type="button" class="btn btn-success" data-toggle="collapse" data-parent="#accordion" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">Continue Checkout <span class='glyphicon glyphicon-chevron-right'></span></button>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                2. DELIVERY ADDRESS
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="inputName">Name</label>
                                <input type="text" class="form-control" name="name" id="inputName" placeholder="Enter delivery person's name" value="<?php echo $this->session->userdata('name');?>" required>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <label for="inputPhone">Phone</label>
                                <input type="text" class="form-control" name="phone" id="inputPhone" placeholder="Enter phone number" required>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <label for="inputAddress">Address</label>
                                <textarea class="form-control" id="inputAddress" name="address" placeholder="Enter full address of delivery" required></textarea>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <label for="inputCity">City</label>
                                <input type="text" class="form-control" name="city" id="inputCity" placeholder="Enter city" required>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <label for="inputZipcode">Zipcode</label>
                                <input type="text" class="form-control" name="zipcode" id="inputZipcode" placeholder="Enter zipcode" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="inputCountry">Country</label>
                                <select class="form-control countries" name="country" id="inputCountry" required >
                                    <option value="" selected>Select Country</option>
                                </select>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <label for="inputState">State</label>
                                <select class="form-control states" name="state" id="inputState" required >
                                    <option value="" selected>Select State</option>
                                </select>
                                <div class="help-block with-errors"></div>
                            </div>
                            <button type="submit" id="btn-deliver" class="btn btn-success">Deliver Here <span class='glyphicon glyphicon-chevron-right'></span></button>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingThree">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                3. REVIEW ORDER
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <!-- Table -->
                                <table class="table table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Item Description</th>
                                            <th>QTY</th>
                                            <th class="text-right">Item Price</th>
                                            <th class="text-right">Sub-Total</th>
                                        </tr>
                                    </thead>
                                    <?php $i = 1; ?>

                                    <tbody>
                                    <?php foreach ($this->cart->contents() as $items): ?>
                                        <tr>
                                            <td width="80">
                                                <?php if (isset($items['image']) && $items['image'] <> ""):?>
                                                    <img class="img-thumbnail img-responsive" style="width:80px;" alt="<?php echo $items['name'];?>" title="<?php echo $items['name'];?>" src="<?php echo base_url(DIR_PRODUCT_PICTURE_THUMB.$items['image']);?>">
                                                <?php else: ?>
                                                    <img class="img-thumbnail img-responsive" style="width:80px;" alt="<?php echo $items['name'];?>" title="<?php echo $items['name'];?>" data-src="holder.js/200x100%" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDI0MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MjAwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTYwODMzYTQxNDEgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMnB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNjA4MzNhNDE0MSI+PHJlY3Qgd2lkdGg9IjI0MiIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI4OS41IiB5PSIxMDUuMSI+MjQyeDIwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg==" data-holder-rendered="true">
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('stores/details/'.$items['id']);?>"><?php echo str_replace("_"," ",$items['name']); ?></a>
                                                <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
                                                    <p>
                                                        <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
                                                            <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />
                                                        <?php endforeach; ?>
                                                    </p>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $items['qty']; ?></td>
                                            <td class="text-right"><?php echo $this->cart->format_number($items['price']); ?></td>
                                            <td class="text-right">$<?php echo $this->cart->format_number($items['subtotal']); ?></td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3"><button type="button" class="btn btn-success" data-toggle="collapse" data-parent="#accordion" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">Proceed to Payment <span class='glyphicon glyphicon-chevron-right'></span></button> </td>
                                            <td class="text-right"><strong>Total</strong></td>
                                            <td class="text-right"><strong>$<?php echo $this->cart->format_number($this->cart->total()); ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <p>Order confirmation email will be sent to <mark><?php echo $this->session->userdata('email');?></mark></p>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingFour">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseTwo">
                                4. MAKE PAYMENT
                            </a>
                        </h4>
                    </div>
                    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                        <div class="panel-body">

                            <div class="col-xs-3">
                                <!-- required for floating -->
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs tabs-left">
                                    <li class="active"><a href="#cod" data-toggle="tab">Cash On Delivery</a></li>
                                    <li><a href="#paypal" data-toggle="tab">Paypal</a></li>
                                </ul>
                            </div>
                            <div class="col-xs-9">
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="cod">
                                        <h4 class="payment-heading">Pay using Cash On Delivery</h4>
                                        <br /><br />
                                        <button type="submit" name="payment_type" value="cod" class="btn btn-warning btn-lg">Place COD Order</button>
                                    </div>
                                    <div class="tab-pane" id="paypal">
                                        <h4 class="payment-heading">Pay using Paypal</h4>
                                        <br /><br />
                                        <button type="submit" name="payment_type" value="paypal" class="btn btn-info btn-lg">Pay Now</button>
                                    </div>
                                    <p><br /><small>100% Payment Protection, Easy Returns Policy</small></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php echo form_close();?>
        </div>

        <div class="col-md-4">
            <div class="well">
                <strong>SUMMARY (<?php echo $this->cart->total_items();?> <?php echo ($this->cart->total_items()>1?"items":"item")?>)</strong><br />

                <?php foreach ($this->cart->contents() as $items): ?>
                    <div class="row" style="padding:10px 0px">
                        <div class="col-md-12"><?php echo str_replace("_"," ",$items['name']); ?></div>
                    </div>
                    <div class="row" style="padding:0px 5px 10px;">
                        <div class="col-md-6"><samp>Quantity: <?php echo $items['qty']; ?></samp></div>
                        <div class="col-md-6 text-right"><samp>$<?php echo $this->cart->format_number($items['subtotal']); ?></samp></div>
                    </div>
                <?php endforeach; ?>
                <div class="row" style="margin-top:10px; padding:15px 5px; border-top:solid 1px #ddd;">
                    <div class="col-md-6"><samp>Total:</samp></div>
                    <div class="col-md-6 text-right"><samp>$<?php echo $this->cart->format_number($this->cart->total()); ?></samp></div>
                </div>
                <div class="row" style="padding:0px 5px 15px;">
                    <div class="col-md-6"><samp>Delivery:</samp></div>
                    <div class="col-md-6 text-right text-success"><samp>Free</samp></div>
                </div>
                <div class="row" style="margin-top:10px; padding:15px 5px; border-top:solid 1px #ddd;">
                    <div class="col-md-6"><h4>You Pay:</h4></div>
                    <div class="col-md-6 text-right"><h4>$<?php echo $this->cart->format_number($this->cart->total()); ?></h4></div>
                </div>
            </div>
            <p>&nbsp;</p>
            <p class="text-center"><span class="glyphicon glyphicon-ok-sign"></span> Safe and Secure Payments. Easy returns. 100% Authentic products.</p>
        </div>
    </div>
    <p>&nbsp;</p>
</div>
<script>
jQuery(document).ready(function() {
    var eventhandler;
	var loc = new locationInfo();
    loc.getCountries();
    jQuery(".countries").on("change", function(ev) {
        var countryId = jQuery(this).val()
        if (countryId != '') {
            loc.getStates(countryId);
        } else {
            jQuery(".states option:gt(0)").remove();
        }
    });

    jQuery('#collapseTwo').on('shown.bs.collapse', function () {
        jQuery('#frm-checkout').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
                // handle the invalid form...
            } else {
                // everything looks good!
                eventhandler = e.preventDefault();
                jQuery('#collapseTwo').collapse('hide');
                jQuery('#collapseThree').collapse('show');
            }
        });
    });

    jQuery('#collapseTwo').on('hidden.bs.collapse', function () {
        //console.log('collapseThree');
        $("#frm-checkout").unbind('submit', eventhandler);
    });

});
</script>