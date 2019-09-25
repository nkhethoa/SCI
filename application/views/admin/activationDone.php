<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html class="body-margin">
<head>
    <title>Password Created</title>
    <?php 
    $cssFiles =['bootstrap.min.css',
    'font-awesome.min.css',
    'mystyle.css',
    'inner-tabcss.css',
    'admin.css',
];
foreach ($cssFiles as $cssFile) {?>
<link href='<?php echo base_url("assets/css/$cssFile")?>' rel="stylesheet">
<?php }?> 
</head>
<body >
    <?php
    $this->load->view('template/jumbotron.php');

    ?>
    <div class="container-fluid">
<div class="starter-template">
    <div class="panel panel-success">

                <div class="panel-body">
                    <br>
                    <h2 class="text-center">Your have successfully created your new Thuto-Sci password.</h2>
                    <br>
                    <h4 class="text-center">Please click OK to proceed to Login page.</h4>
                    <?php
                    $action = 'Access/activationDone';
                    $options = array("class"=>"form-horizontal","method"=>"POST");
                    echo form_open($action,$options);
                    ?>
                    <!--<input type="hidden" name="id_user" value="<?php echo isset($id_user)? $id_user:0 ;?>" placeholder="">
                     <div class="text-center">-->
                        <div class="text-center">
                            <br>
                            <br>
                            <a role="button" href="<?php echo base_url("Guests#login") ?>" class="btn btn-info btn-lg" title="OK">OK &nbsp;<i class="fa fa-check" aria-hidden="true"></i>
                            </a>       
                        </div>
                    
                    </div>
                    <?php echo form_close(); ?>

                    <br>
                    <br>

            </div>
</div>

</div><!--div for container-->
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