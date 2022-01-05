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

	
	<div class="row">

    	

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
                <a href="<?php echo base_url('Agency/tester');?>">
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
                <a href="<?php echo base_url('Agency/job');?>">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        
       

        

        

        

        
        
		
	</div>

	<script src="https://use.fontawesome.com/a730297c43.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
	    // google.load("visualization", "1", {packages: ["corechart"]});
	    // google.setOnLoadCallback(drawChart);
	   /* function drawChart() {
	        var data = google.visualization.arrayToDataTable([
	            ['Month', 'No. of customers', { role: 'style' }],
				<?php 
					// foreach ($dashboard['customers'] as $data) {
					// 	$monthYear = date("M Y", mktime(0, 0, 0, $data->month, 1, $data->year));
					// 	echo "['".$monthYear."', ".$data->total_customers.", '#337ab7'],";	
					// }
				?>
	        ]);

	        var options = {
	            title: '',
	            legend: { position: 'none'},
	        };

	        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
	        //chart.draw(data, options);
	    }*/
	</script>
 	
</div>
