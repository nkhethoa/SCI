<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="container-fluid backgroundColour">
        <h1 class="text-center">Change Password</h1>
        <div class="card card-container">
            <p id="profile-name" class="profile-name-card"></p>
            <?php
            echo form_open('Access/change_my_password','class="form-signin"');
            ?>

        <div class="form-group">
             <label for="password">New Password</label>
             <input class="form-control" id="password" name="password" placeholder="password" type="password" value="" />
             <span><?php echo form_error('password'); ?></span>
         </div>
         <div class="form-group">
             <label for="confirm_password">Confirm Password</label>
             <input class="form-control" id="confirm_password" name="confirm_password" placeholder="confirm password" type="password" value="" />
             <span><?php echo form_error('confirm_password'); ?></span>
         </div>
         <div class="button-group">
             <input class="form-control btn-success btn-block btn-signin" id="register" name="save" placeholder="register" type="submit" value="Save" />
         </div>
         <?php echo form_close(); ?>
     </div><!-- /card-container -->

 </div>