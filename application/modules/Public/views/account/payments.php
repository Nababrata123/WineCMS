 <div class="container text-left">
    <div class="row profile">
		<div class="col-md-12">
            <div class="profile-content pa-5">
                <h3 class="page-heading">
                     Payment Details
                </h3>
                     
                <p>&nbsp;</p>
                
                <div class="col-md-12 table-responsive">
                    <!-- Table -->
                    <table class="table table-striped table-responsive" width="100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php //print "<pre>"; print_r($list); print "</pre>";	?>
                            <?php if (count($payments) == 0) { ?>
                            <tr>
                                <td colspan="100%">Sorry!! No Records found.</td>
                            </tr>
                            <?php } ?>
                            <?php foreach($payments as $item) { ?>
                            <tr>
                                <td><?php echo datetime_display($item->subscription_date);?></td>
                                <td><?php echo ucfirst($item->subscription_type);?></td>
                                <td><?php echo price_display($item->subscription_amount);?></td>
                                <td>
                                    <?php 
                                        if ($item->subscription_status==1)
                                            echo "Active";
                                        else 
                                            echo "Inactive";                                                
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        
                    </table>
                </div>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                
            </div>
        </div>
    </div>
</div>