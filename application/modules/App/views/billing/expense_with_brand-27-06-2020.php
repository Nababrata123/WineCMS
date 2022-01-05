
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Expense details with brand</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <?php
        
        if(!empty($expense_with_brand))
        {   
    ?>
    <table width="100%" cellpadding="5" cellspacing="0" border="1">
    <tr>
      <th style="padding: 5px;" width="25%" align="left">Wine Name</th>
      <th style="padding: 5px;" width="25%" align="left"># Bottles Used</th>
      <th style="padding: 5px;" width="25%" align="left"># Bottles Sold</th>
      <th style="padding: 5px;" width="25%" align="left">Cost Per Tasting</th>
     </tr>
        <?php
            $total_sampled=0;
            $total_sold=0;
            $no_of_wine_sampled=count($expense_with_brand);
//echo $expense_amount;die;
            $amount=ltrim($expense_amount,"$");
            $amount_on_rate=ltrim($more_job_info->total_amount,"$");
            $actual_amount=$amount+$amount_on_rate;
            $individual_amount=number_format((float)$actual_amount/$no_of_wine_sampled, 2, '.', '');
            foreach($expense_with_brand as $val)
            {
                $total_sampled+=$val['bottles_sampled'];
                $total_sold+=$val['bottles_sold'];
        ?>
     <tr>
     <td style="padding: 5px;"><?php echo ucfirst($val['name']);?>&nbsp; <?php echo $val['size']?> <?php echo $val['UOM']?> </td>
         <td><center><?php echo $val['bottles_sampled']?></center></td>
         <td><center><?php echo $val['bottles_sold']?></center></td>
         <td><center>$<?php echo $individual_amount;?></center></td>
     </tr>
        <?php
            }
        ?>
     <tr>
         <td><center><strong>TOTAL</strong></center></td>
       <td><center><strong><?php echo $total_sampled;?></strong></center></td>
       <td><center><strong><?php echo $total_sold;?></strong></center></td>
       <td><center><strong>$<?php echo number_format((float)$actual_amount, 2, '.', '');?></strong></center></td>
     </tr>
    </table>
    <?php
        }
        else
        {
            echo "No wine sampled";
        }
    ?>
                </div>
            </div>
         
        </div>
        
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  