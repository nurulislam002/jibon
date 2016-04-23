<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url(); ?>public/backend/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/backend/css/bootstrap-responsive.min.css" />
<style media="screen">
/*For Dashbord*/
.validation_error_message {
	display: block;
	color: #ff6600 ;
	clear: both;
}
</style>
</head>

<body>

	<div class="container">
		<dev class="row">
		<div id="content-header" style="margin-top:10%">
			<h3 class="text-center">Dashboard User Configeration</h3>
		</div>
		<div class="col-md-6 col-lg-6 col-xs-12">
			<?php
			$attributes = array('class' => 'form-horizontal');
			echo form_open_multipart('home/create_user', $attributes);
			?>
			<?php
			if( $this->session->flashdata('success_message') ){
				echo '<div class="alert alert-success fade in">'. $this->session->flashdata('success_message') . '</div>';
			}
			elseif( $this->session->flashdata('error_message') ){
				echo '<div class="alert alert-danger fade in">'. $this->session->flashdata('error_message') . '</div>';
			}
			?>
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box">
						<div class="widget-content nopadding">

							<div class="control-group ">
								<label class="control-label">User Name :</label>
								<div class="controls">
									<?php
									$user_name	=	array(
										'class'				=>	'span10',
										'name'				=>	'user_name',
										'placeholder'	=>	'User Name',
										'value'				=>	set_value('user_name'),
									);
									echo form_input($user_name);
									echo form_error('user_name');
									?>
								</div>
							</div>
							<div class="control-group ">
								<div class="pro_left_input span6">
									<label class="control-label">User Email :</label>
									<div class="controls">
										<?php
										if(isset($user_history->email)){
											$user_email	=	array(
												'readonly'			=>	'readonly',
												'class'				=>	'span8',
												'name'				=>	'user_email',
												'placeholder'		=>	'User Email',
												'value'				=>	set_value('user_email'),
											);
										}else{
											$user_email	=	array(
												'class'				=>	'span8',
												'name'				=>	'user_email',
												'placeholder'		=>	'User Email',
												'value'				=>	set_value('user_email'),
											);
										}
										echo form_input($user_email);
										echo form_error('user_email');
										?>
									</div>
								</div>
								<div class="pro_right_input span6">
									<label class="control-label ">User's Access Level:</label>
									<div class="controls">
										<?php
										$options	=	array(
											'1'         	=> 'Customer',
											'6'           	=> 'Maneger',
											'9'         	=> 'Adminastrator',
										);
										if(isset($user_history) ){
											echo form_dropdown('access_level', $options, $user_history->auth_level, 'class="span7"');
										}else{
											echo form_dropdown('access_level', $options, set_value('access_level'), 'class="span7"');
										}

										echo form_error('access_level');
										?>
									</div>
								</div>
							</div>
								<div class="control-group">
									<div class="pro_left_input span6">
										<label class="control-label ">User Password :</label>
										<div class="controls  ">
											<?php
											$user_pass	=	array(
												'class'				=>	'span8',
												'name'				=>	'user_pass',
												'placeholder'	    =>	'User Password',
											);
											echo form_password($user_pass);
											echo form_error('user_pass');
											?>
										</div>
									</div>
									<div class="pro_right_input span6">
										<label class="control-label ">User Confirm Password :</label>
										<div class="controls">
											<?php

											$con_pass	=	array(
												'class'			=>	"span7",
												'name'			=>	'con_pass',
												'placeholder'	=>	'Confirm Password',
											);
											echo form_password($con_pass);
											echo form_error('con_pass');
											?>
										</div>
									</div>
								</div>
							<div class="form-actions ">
								<input name="add_user_btn" class="btn btn-success" type="submit" value="Add User" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>public/backend/js/bootstrap.min.js"></script>
</body>
</html>
