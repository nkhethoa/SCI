<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <article id="contact">
    <h2 class="major">Contact</h2>
    <div class="contact-alert text-center alert-color"></div>
    <form action="" id="loginForm" method="POST" accept-charset="utf-8">
      <div class="field half first">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" />
         <span class="name-alert text-center" style="background-color: red"></span>
      </div>
      <div class="field half">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" />
        <span class="email-alert text-center" style="background-color: red"></span>
      </div>
      <div class="field">
        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" />
        <span class="subject-alert text-center" style="background-color: red"></span>
      </div>
      <div class="field">
        <label for="message">Message</label>
        <textarea name="message" id="message" rows="4"></textarea>
        <span class="message-alert text-center" style="background-color: red"></span>
      </div><br>
      <ul class="actions">
        <li><input type="submit" value="Send" class="special contactUs" /></li>
        <li><input type="reset" value="Reset" class="modalClose" /></li>
      </ul>
    </form>
    <ul class="icons">
      <li><a href="mailto:thutosci@gmail.com?subject=Talk to Us" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
      <li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
      <li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
      <li><a href="#" class="icon fa-github"><span class="label">GitHub</span></a></li>
    </ul>
</article>

  