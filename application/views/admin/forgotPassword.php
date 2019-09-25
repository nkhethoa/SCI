<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html class="body-margin">
<head>
    <title></title>
    <?php 
    $cssFiles =['bootstrap.min.css',
    'font-awesome.min.css',
    'daterangepicker.css',
    'mystyle.css',
    'inner-tabcss.css',
    'admin.css',
    'teacherList.css',
                //'jquery-ui.min.css'

];
foreach ($cssFiles as $cssFile) {?>
<link href='<?php echo base_url("assets/css/$cssFile")?>' rel="stylesheet">
<?php }?> 
</head>
<body >
    <?php
    $this->load->view('template/jumbotron.php');
    ?>
    <div class="container-fluid backgroundColour">
        <h1 class="text-center">Create New Password</h1>
        <div class="card card-container">
            <p id="profile-name" class="profile-name-card"></p>
            <?php
            echo form_open('Access/validateUserResetKey','class="form-signin"');
            
            ?>
            <input type="hidden" name="reset" value="<?php echo $reset;?>" >

        <div class="form-group">
            <span><?php echo form_error('reset'); ?></span>
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
             <input class="form-control btn-success btn-block btn-signin" id="register" name="register" placeholder="register" type="submit" value="Submit" />
         </div>
         <?php echo form_close(); ?>
     </div><!-- /card-container -->

 </div>
 <footer class="container-fluid text-center footer_fixed">
  <p class="foot">Thuto-Sci &copy 2017</p>  
</footer>
<?php 
    $jsFiles=['jquery-3.2.1.min.js',
              'bootstrap.min.js'
             ];


    foreach ($jsFiles as $jsFile) {?>
        <script src='<?php echo base_url("assets/js/$jsFile")?>'></script>
    <?php }?> 

</body>
</html>