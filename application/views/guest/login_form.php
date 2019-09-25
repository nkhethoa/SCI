<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- header section -->
    <article id="login">
      <?php echo validation_errors(); ?>
      <h2 class="major">Login</h2>
      <div class="alert login-alert text-center alert-color"></div>
      <form action="" id="loginForm" method="POST" accept-charset="utf-8">
        <div class="field half first">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" placeholder="username" value="" autofocus>
        </div>
        <div class="field half">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" placeholder="password" value="" />
        </div>
        <div class="field">
      </div>
        <ul class="icons">
          <div class="form-group text-center">
            <input type="checkbox" tabindex="3" class="" name="remember" value="True" id="remember">
            <label for="remember"> Remember Me</label>
          </div>
        </ul>
        <ul class="actions">
          <li><input type="submit" id="userLogin" value="Login" class="special" /></li>
          <li> <button type="button" class="btn btn-default modalClose" data-dismiss="modal">Cancel</button></li>
        </ul><br>
        <div class="text-center">
          <a href="#forgotPassword" id="forgotPasswor" tabindex="5" class="forgot-password">Forgot Password?</a>
        </div>
              
    </article>

