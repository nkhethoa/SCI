<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?> 

	<footer class="container-fluid text-center footer_fixed">
  <p class="copyright">Thuto-Sci &copy&nbsp;<?php echo date("Y");?></p>  
</footer>
<?php 
	$jsFiles=['jquery.min.js',
			  'bootstrap.min.js',
			  'moment.min.js',
			  'Chart.min.js',
			  'tabScript.js',
			  'academic.js',
			  'jquery.tabletoCSV',
			  'intercom.js',
			  'admin.js',
			  'moment.min.js',
			  //'jquecry.dataTables.min.js',
			  //'dataTables.jqueryui.min.js',
			  'custom_calendar.js',
			  'daterangepicker.js',
			  'users.js',
			  //'bootstrap-datepicker.js',
			  //'mychart.js'
			 ];
	foreach ($jsFiles as $jsFile) {?>
        <script src='<?php echo base_url("assets/js/$jsFile")?>'></script>
    <?php }?> 

<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src='<?php echo base_url("assets/fullcalendar/fullcalendar.js")?>'></script>
<script src='<?php echo base_url("assets/js/custom_calendar.js")?>'></script>







