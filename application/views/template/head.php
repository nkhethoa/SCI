<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<head>
	<title>Thuto SCI</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
<?php 
    $cssFiles =['bootstrap.min.css',
                'font-awesome.min.css',
    			'mystyle.css',
                'inner-tabcss.css',
                'tabcss.css',
    			'admin.css',
    			'teacherList.css',
                'intercom.css',
                'daterangepicker.css',
                //'jquery.dataTables.min.css',
                //'dataTables.jqueryui.css',
                //'dataTables.bootstrap.css',
                'academic.css',       
                'home_calendar.css',       

    		];
    foreach ($cssFiles as $cssFile) {?>
        <link href='<?php echo base_url("assets/css/$cssFile")?>' rel="stylesheet">
    <?php }?>
    <!--<script src="//cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>-->
    <link href='<?php echo base_url("assets/fullcalendar/fullcalendar.css")?>' rel="stylesheet"> 
    <link href='<?php echo base_url("assets/fullcalendar/fullcalendar.print.css");?>' rel="stylesheet" media="print">
    </head>
