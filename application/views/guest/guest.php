<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE HTML>
<html>
<?php
$this->load->view('guest/head.php');
?> 
  <body style=""  id="searchBG">

    <!-- Wrapper -->
      <div id="wrapper">
         <?php
               $this->load->view('guest/header.php');
          ?> 
       
        <!-- Main -->
          <div id="main">

            <!-- Intro -->
               <?php
                    $this->load->view('guest/login_form.php');
                    //forgot password form
                    $this->load->view('guest/forgotPassword.php');
                ?> 

            <!-- Work -->
             
                <?php
                    $this->load->view('guest/ourTeam.php');
                ?> 
            <!-- About -->
              <?php
                    $this->load->view('guest/about.php');
                ?> 
            <!-- Contact -->
              
                   <?php
                    $this->load->view('guest/contact_us.php');
                ?> 
            <!-- modal for success messages -->
            <div id="guest-success" class="modal in" data-backdrop="static" data-keyboard="false" style="display: none;">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title"></h4>
                  </div>
                  <div class="modal-body">
                    <p class="modal-msg"></p>
                    <div class="row">
                      <div class="col-12-xs text-center">
                        <button class="btn btn-info btn-md guest-successOK modalClose" data-dismiss="modal" value="OK">OK</button>
                      </div>
                    </div>
                  </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
          </div>

        <!-- Footer -->
          <footer id="footer">
            <p class="copyright">&copy;Thuto-Sci 2017</p>
          </footer>

      </div>

    <!-- BG -->
      <div id="bg"></div>

    <!-- Scripts -->
      <?php $this->load->view('guest/scripts.php');?> 


  </body>
</html>
