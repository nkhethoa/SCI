<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('setMenuActiveItem')) {
	
	function setMenuActiveItem($flag=false)
	{
		If($flag)
		{
			return 'class= "active"';
		}
		else {
			return '';
		}
	}

} 


if (!function_exists('alertMsgs')) {

	function alertMsgs($flag=false, $successMsg ="", $errMsg="")

	{
		If($flag)
		{
			return '<div class="alert alert-success" alert-dismissable">
  						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  						<strong>Success!</strong> '.$successMsg.'
					</div>';
		}
		else {
			return '<div class="alert alert-danger" alert-dismissable">
  						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  						<strong>Error!</strong> '.$errMsg.'
					</div>';
		}
	}

} 
?>