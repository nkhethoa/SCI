<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<body class="col-md-12 col-sm-12 col-xs-12 body">
	<div class="body-margin">
		<div>
			<?php 
			$this->load->view('template/header.php');
			$this->load->view($pageToLoad);
			$this->load->view('template/footer.php');
			?>
		</div>
	</div>
</body>
