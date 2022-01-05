<div class="container">
    <div class="row profile">
		<div class="col-md-3">
			<?php $this->load->view('sidebar'); ?>
		</div>
		<div class="col-md-9">
			<div class="profile-content">           
                <div class="page-header">
                    <h1>
                        Order Details 
                        <p><small>Order ID: <strong><?php echo $order->code;?></strong>, Status: <strong><?php echo order_status_display($order->status);?></strong>, <span class="text-muted">Placed on: <?php echo mdate("%d %M %Y", strtotime($order->created_on));?></span></small></p>
                    </h1>
                </div>

                <p>&nbsp;</p>

                <div class="row">
		            <div class="col-md-4">
                        <div class="well well-sm">
                            <div class="heading">Customer Information</div>
                            <strong><?php echo $order->customer_name;?></strong><br>
                            <?php echo $order->customer_email;?>
                        </div>
                       
                        <?php if ($order->updated_on) {?>
                        <div class="well well-sm">
                            Last Updated on <?php echo datetime_display($order->updated_on);?><br>
                            <?php echo $order->comment?>
                        </div>
                        <?php }?>
                    </div>
                    <div class="col-md-4">
                        <div class="well well-sm">
                            <div class="heading">Shipping Information</div>
                            <strong><?php echo $order->name?></strong><br>
                            <?php echo $order->address?><br>
                            <?php echo $order->city?>, <?php echo $order->state_name?> <?php echo $order->zipcode?><br>
                            <?php echo $order->country_name?><br><br>
                            <abbr title="Phone">P:</abbr> <?php echo $order->phone?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="well well-sm">
                            <div class="row">
		                        <div class="col-md-8">Total</div>
                                <div class="col-md-4 text-right"><?php echo price_display($order->total);?></div>
                            </div>
                            <div class="row">
		                        <div class="col-md-8">Delivery Charges</div>
                                <div class="col-md-4 text-right">NILL</div>
                            </div>
                            <div class="row heading-reverse">
		                        <div class="col-md-8"><strong>Payable Amount</strong></div>
                                <div class="col-md-4 text-right"><strong><?php echo price_display($order->total);?></strong></div>
                            </div>
                        </div>

                        <div class="well well-sm">
                            <div class="heading">Payment Details</div>
                            <?php 
                                if ($order->payment_type=="cod")
                                    echo "Cash on Delivery";
                                if ($order->payment_type=="paypal") {
                                    echo "Paypal ";
                                    if ($order->payment_status=="complete") {
                                        echo payment_status_display($order->payment_status)."<br /> Txn ID: ".$order->txn_id." on ".date_display($order->payment_date);
                                    } else {
                                        echo payment_status_display($order->payment_status)."<br /> ";
                                    }
                                }
                                    
                            ?>
                        </div>
                        
                    </div>
                </div>

                <?php echo order_progressbar($order->status);?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            
                            <div class="panel-body">
                                <?php foreach($order->items as $item) { ?>
                                <div class="row order-list-item">
                                    <div class="col-md-2">
                                        <?php
                                            if ($item->image == "") {
                                                echo '<img title="'.$item->name.'" class="img-responsive img-thumbnail" style="max-width:120px; max-height:100px" class="" src="'.base_url('assets/images/no_image_large.png').'">';
                                            } else {
                                                echo '<img title="'.$item->name.'" class="img-responsive img-thumbnail" style="max-width:120px; max-height:100px" src="'.base_url(DIR_PRODUCT_PICTURE_THUMB.$item->image).'">';
                                            }
                                        ?>
                                    </div>
                                    <div class="col-md-8 lead">
                                        <?php echo $item->qnty;?> X <?php echo $item->name;?><br />
                                        <span>Size: <?php echo $item->size;?></span><br />
                                    </div>
                                    <div class="col-md-2 lead text-right">
                                        <?php echo price_display($item->price*$item->qnty);?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                
               
                <p>&nbsp;</p>
                <p>&nbsp;</p>
            </div>
        </div>
    </div>
</div>