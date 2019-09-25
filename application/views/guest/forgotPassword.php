<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- header section -->
<article id="forgotPassword">
  <h2 class="major">forgot Password</h2>
  <!--<form action="" id="forgotForm" method="POST" accept-charset="utf-8">-->
    <br>
    <div class="field half first">
      <label for="forgotUser">Username</label>
      <input type="email" name="forgotUser" class="form-control" id="forgotUser" placeholder="Enter your username" value="<?php set_value('forgotUser') ?>" autofocus>
      <div class="alert-msg text-center alert-color"></div><br>
    <ul class="actions">
      <li><input type="submit" value="Submit" class="special forgotPasswo" /></li>
      <li> <button type="button" class="btn btn-danger modalClose" data-dismiss="modal">Cancel</button></li>
    </ul>         
 </div>
</article>

