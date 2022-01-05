<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-dashboard"></span> <?php echo $this->lang->line('auth_dashboard_page_heading')?> </h1>
    </div>
</div>

<div class="container-fluid main">
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

	<div class="row">
		<div class="col-md-12">
	        <div class="alert alert-info alert-dismissable">
	            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	        	<span class="glyphicon glyphicon-log-in"></span> <?php echo $this->lang->line('auth_dashboard_page_lastlogin_text')?> <?php echo $this->session->userdata('last_login');?>
	        </div>
		</div>
	</div>

	<?php if ($this->session->userdata('role') == "administrator") { ?>
	<div class="row">

    	<div class="col-lg-3 col-md-6">
        	<div class="panel panel-success">
            	<div class="panel-heading">
                	<div class="row">
                    	<div class="col-xs-3">
                        	<i class="fa fa-user-circle-o fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                        	<div class="huge"><?php echo $dashboard['total_sales_representative'];?></div>
                            <div>Total Sales Representative</div>
                        </div>
                   	</div>
              	</div>
                <a href="<?php echo base_url('App/sales_representative');?>">
               		<div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                    	<div class="clearfix"></div>
                   	</div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
        	<div class="panel panel-success">
            	<div class="panel-heading">
                	<div class="row">
                    	<div class="col-xs-3">
                        	
                        	<i class="fa fa-map-marker fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                        	<div class="huge"><?php echo $dashboard['total_zone'];?></div>
                            <div>Total Zones</div>
                        </div>
                   	</div>
              	</div>
                <a href="<?php echo base_url('App/zone');?>">
               		<div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                    	<div class="clearfix"></div>
                   	</div>
                </a>
            </div>
        </div>

        <!-- For Testers-->
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-users fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard['total_tester'];?></div>
                            <div>Tasters</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('App/tester');?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Agency -->
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-industry fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard['total_agency'];?></div>
                            <div>Agency</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('App/agency');?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Wines-->
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-glass fa-5x" aria-hidden="true"></i>
                            
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard['total_wine'];?></div>
                            <div>Products</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('App/wine');?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Jobs-->
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-tasks fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard['total_job'];?></div>
                            <div>All jobs</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('App/job');?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Unassigned jobs or requests-->
        <!--div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-tasks fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard['pre_assigned_job'];?></div>
                            <div>Unassigned jobs/Requests</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('App/Job/index/status/pre_assigned');?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div-->

        <!-- Sotres -->

        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-building fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard['total_store'];?></div>
                            <div>Stores</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('App/store');?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Reports -->

        <!--div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-file-excel-o fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">0</div>
                            <div>Reports</div>
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0)">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div-->

        <!-- Saved Reports -->

        <!--div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-file-excel-o fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">0</div>
                            <div>Saved Reports</div>
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0)">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div-->
        <!-- Billing-->

        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-print fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard['total_billing'];?></div>
                            <div>Billing</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('App/billing');?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        
        <!--Archive-->

        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-archive fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard['total_archive'];?></div>
                            <div>Archive</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('App/archive');?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Jobs-->
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            
                            <i class="fa fa-tasks fa-5x" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $dashboard['total_bulk_schedule'];?></div>
                            <div>Bulk Schedules</div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo base_url('App/bulk_schedule_job');?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

		<!--div class="col-lg-6 col-md-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Customer Registration by Month - Chart</h3>
				</div>
                <div class="panel-body">
                	<div id="chart_div" style="width: 100%; height: 100%;"></div>
                </div>
          	</div>
		</div-->
	</div>

	<script src="https://use.fontawesome.com/a730297c43.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
	    google.load("visualization", "1", {packages: ["corechart"]});
	    google.setOnLoadCallback(drawChart);
	    function drawChart() {
	        var data = google.visualization.arrayToDataTable([
	            ['Month', 'No. of customers', { role: 'style' }],
				<?php 
					foreach ($dashboard['customers'] as $data) {
						$monthYear = date("M Y", mktime(0, 0, 0, $data->month, 1, $data->year));
						echo "['".$monthYear."', ".$data->total_customers.", '#337ab7'],";	
					}
				?>
	        ]);

	        var options = {
	            title: '',
	            legend: { position: 'none'},
	        };

	        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
	        //chart.draw(data, options);
	    }
        
	</script>

 	<?php } ?>
</div>
