<div class="container">
    <div class="row profile">
		<div class="col-md-3">
            <?php $this->load->view('sidebar'); ?>
		</div>
		<div class="col-md-9">
            <div class="profile-content">

                <div class="page-header">
                    <h1>
                        My Orders
                        <p><small>Manage my orders.</small></p>
                    </h1>
                </div>

                <p>&nbsp;</p>
                
                <?php foreach($orders as $order) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-6">
                                        Order ID: <a href="javascript:;" onclick="toggleView('<?php echo $order->id;?>');"><strong><?php echo $order->code;?></strong></a> (<?php echo $order->total_items;?> <?php echo ($order->total_items>1?"items":"item")?>) <br />
                                        Placed on:  <?php echo mdate("%d %M %Y", strtotime($order->created_on));?>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="<?php echo base_url('/account/order_details/'.$order->id);?>" class="btn btn-default">Details</a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body" style="display:none" id="order-body-<?php echo $order->id;?>">
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
                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-md-8">
                                        Status: <?php echo order_status_display($order->status);?>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        Order Total: <strong><?php echo price_display($order->total);?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
                <?php if (count($orders) == 0) { ?>
                <div class="row">
                    <div class="col-md-12">Sorry!! No Orders found.</div>
                </div>
                <?php } ?>
                <p>&nbsp;</p>
                <p>&nbsp;</p>

            </div>
		</div>
    </div>
</div>
<script>
    function toggleView(id) {
        jQuery("#order-body-"+id).slideToggle();
    }
</script>