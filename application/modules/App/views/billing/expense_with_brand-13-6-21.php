
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Expense details with Wine</h4>
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
      <th style="text-align:center;">Wine Name</th>
      <th style="text-align:center; width:130px;"># of Bottles Used</th>
      <th style="text-align:center; width:190px;"># of Opened Bottles Used</th>
      <th style="text-align:center; width:130px;"># of Bottles Sold</th>
      <th style="text-align:center; width:130px;">Cost Per Tasting</th>
     </tr>
        <?php
            $total_sampled=0;
            $total_opened=0;
            $total_sold=0;
            $no_of_wine_sampled=count($expense_with_brand);
            $dta=json_decode(json_encode($expense_amount), true);
            $amount=ltrim($dta->exp_amount,"$");
            $amount_on_rate=ltrim($more_job_info->total_amount,"$");
            $actual_amount=$amount+$amount_on_rate;
            $individual_amount=number_format((float)$actual_amount/$no_of_wine_sampled, 2, '.', '');
            foreach($expense_with_brand as $val)
            {
                $total_sampled+=$val['bottles_sampled'];
                $total_opened+=$val['open_bottles_sampled'];
                $total_sold+=$val['bottles_sold'];
        ?>
     <tr>
          <td style="text-align:center;"><?php echo ucfirst($val['name']);?>&nbsp; 
          <?php 
           echo (double) $val['size'];
              
          ?> 
            <?php echo $val['UOM']?> </td>
         <td style="text-align:center; width:130px;"><?php echo $val['bottles_sampled']?></td>
         <td style="text-align:center; width:190px;"><?php echo $val['open_bottles_sampled']?></td>
         <td style="text-align:center; width:130px;"><?php echo $val['bottles_sold']?></td>
         <td style="text-align:center; width:130px;">$<?php echo $individual_amount;?></td>
      </tr>
        <?php
            }
        ?>
     <tr>
         <td style="text-align:center;"><strong>TOTAL</strong></td>
       <td style="text-align:center;"><strong><?php echo $total_sampled;?></strong></td>
       <td style="text-align:center;"><strong><?php echo $total_opened;?></strong></td>
       <td style="text-align:center;"><strong><?php echo $total_sold;?></strong></td>
       <td style="text-align:center;"><strong>$<?php echo number_format((float)$actual_amount, 2, '.', '');?></strong></td>
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
  