<?php
/*
Plugin Name: Contus VBlog - Video Blogging
Description: Contus Vblog for wordpress is easy to install plugin which enables you to post Video blog instead of text.
Version: 2.0
Author: Apptha Team 
Author URI: http://www.apptha.com/
*/

$to = $_POST["to"];
$from = $_POST["from"];
$url = $_POST["url"];

$subject = "You have received a video!";

// variables are sent to this PHP page through
// the POST method.  $_POST is a global associative array
// of variables passed through this method.  From that, we
// can get the values sent to this page from Flash and
// assign them to appropriate variables which can be used
// in the PHP mail() function.


// header information not including sendTo and Subject
// these all go in one variable.  First, include From:
$headers = "From: "."<" . $_POST["from"] .">\r\n";
// next include a replyto
$headers .= "Reply-To: " . $_POST["from"] . "\r\n";
// often email servers won't allow emails to be sent to
// domains other than their own.  The return path here will
// often lift that restriction so, for instance, you could send
// email to a hotmail account. (hosting provider settings may vary)
// technically bounced email is supposed to go to the return-path email
$headers .= "Return-path: " . $_POST["from"];

// now we can add the content of the message to a body variable





$message = $_POST["note"] . "\n\n";
$message .= "Video URL: " . $url;


// once the variables have been defined, they can be included
// in the mail function call which will send you an email
if(mail($to, $subject, $message, $headers))
{
	echo "output=sent";
} else {
	echo "output=error";
}

?>