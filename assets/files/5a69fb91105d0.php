<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>  </title>
	<link rel="stylesheet" href="">
</head>
<body>
	<?php
		echo form_open('App/validateUser');
	 ?>
	 <input type="hidden" name="userID" value="<?php //echo (isset($users)?  ) ; ?>" >
	 <div class="form-group">
	   <label for="username">Username</label>
	   <input class="form-control" id="username" name="username" placeholder="Username " type="text" value="<?php echo set_value('username'); ?>"/>
	   <span><?php echo form_error('username'); ?></span>
	 </div>
	 <div class="form-group">
	   <label for="email">email</label>
	   <input class="form-control" id="email" name="email" placeholder="email" type="email" value="<?php echo set_value('email'); ?>" />
	   <span><?php echo form_error('email'); ?></span>
	 </div>
	 <div class="form-group">
	   <label for="password">password</label>
	   <input class="form-control" id="password" name="password" placeholder="password" type="password" value="" />
	    <span><?php echo form_error('password'); ?></span>
	 </div>
	 <div class="form-group">
	   <label for="confirm_password">confirm password</label>
	   <input class="form-control" id="confirm_password" name="confirm_password" placeholder="confirm password" type="password" value="" />
	    <span><?php echo form_error('confirm_password'); ?></span>
	 </div>
	 <div class="button-group">
	   <input class="form-control" id="register" name="register" placeholder="register" type="submit" value="Register" />
	 </div>
	 <?php echo form_close(); ?>
</body>
</html>