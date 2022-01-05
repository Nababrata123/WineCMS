<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Job Rating</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<style>
			.logo{width:10%;}

			.stars-container {
			position: relative;
			display: inline-block;
			color: transparent;
			font-size:45px;
			}

			.stars-container:before {
			position: absolute;
			top: 0;
			left: 0;
			content: '★★★★★';
			color: lightgray;
			}

			.stars-container:after {
			position: absolute;
			top: 0;
			left: 0;
			content: '★★★★★';
			color: #DC9202;
			overflow: hidden;
			display: inline-flex;
			}

			.stars-0:after { width: 0%; }
			.stars-10:after { width: 10%; }
			.stars-20:after { width: 20%; }
			.stars-30:after { width: 30%; }
			.stars-40:after { width: 40%; }
			.stars-50:after { width: 50%; }
			.stars-60:after { width: 60%; }
			.stars-70:after { width: 70%; }
			.stars-80:after { width: 80%; }
			.stars-90:after { width: 90%; }
			.stars-100:after { width: 100; }

			@media screen and (max-width: 600px) {
				.logo{width:30%;}
			}
		</style>
	</head>
	<body>
    <div class="container" style="border: 5px solid #c48f29;">
     <form action="<?php echo base_url('Public/home/submit_rating'); ?>" method="POST">
      <input type="hidden" name="rating" value="<?php echo $rating;?>"/> 
      <input type="hidden" name="job_id" value="<?php echo $job_id;?>"/> 
      <input type="hidden" name="store_id" value="<?php echo $store_id;?>"/> 
      <input type="hidden" name="taster_id" value="<?php echo $taster_id;?>"/> 
			<div class="row text-center">
				<div class="col-sm-12">
					<img class="logo" src="https://karosslive.east-coast-developer.pro/assets/wine/thumb/Wine_Logo.png">
					<h3>Tasting Information</h3>
				</div>
      </div>
      
			<!-- <div class="row">
				<div class="col-sm-12">
					<h6>Taster - Jones Olivia</h6>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h6>Job start time - 10:57 am</h6>
				</div>
				<div class="col-sm-4">
					<h6>Job end time - 10:57 am</h6>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<h6 style="margin: 6px 0px 16px 0px;">Wine Sold : </h6>
				</div>
			</div>
			<div class="row" style="margin-bottom: 10px;">
				<div class="col-sm-1">
					<img class="img-thumbnail" src="https://karosslive.east-coast-developer.pro/assets/images/dummy-wine.jpg">
				</div>
				<div class="col-sm-11" style="display: flex;align-items: center;">ASTI BARTENURA 750 ML - 0 bottles sold
				</div>
			</div>
			<div class="row" style="margin-bottom: 10px;">
				<div class="col-sm-1">
					<img class="img-thumbnail" src="https://karosslive.east-coast-developer.pro/assets/images/dummy-wine.jpg">
				</div>
				<div class="col-sm-11" style="display: flex;align-items: center;">ASTI BARTENURA 750 ML - 0 bottles sold
				</div>
      </div> -->
      
			<hr style="border-top: 2px solid rgba(0,0,0,.1);">
			<div class="row text-center">
				<div class="col-sm-12">
					<h3>Job Rating</h3>
					<p>Honesty is important, it will help us to improve the quality of the tastings.<br>
					(this information will not be shared with the taster)</p>
				</div>
			</div>
			<div class="row text-center">
				<div class="col-sm-12">
					<span class="stars-container stars-<?php echo $rating*20;?>">★★★★★</span>
				</div>
			</div>
			<div class="row text-center">
				<div class="col-sm-12">
					<h4>Please give us your feedback</h4>
					<textarea class="form-control" name="feedback" rows="3"></textarea>
				</div>
			</div>
			<br/>
			<div class="row text-center">
				<div class="col-sm-12">
					<button type="submit" class="btn btn-success" style="padding: 6px 10% 6px 10%;">Submit</button>
				</div>
      </div>
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
    </form>
			<br/>
    </div>
	</body>
</html>