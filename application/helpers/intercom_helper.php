<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if(!function_exists('getMessageNotification')){
	
function getMessageNotification()
{
	$ci =& get_instance();
	$messages = $ci->message_model->getMessage();
    return $messages;
 }
}

if(!function_exists('getAnnouncesNotification')){
function getAnnouncesNotification()
{
	$ci =& get_instance();
    $announces = $ci->announce_model->getAnnounce();
    return $announces;
}

}
?>