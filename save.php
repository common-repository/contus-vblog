<?php
/*
Plugin Name: Contus VBlog - Video Blogging
Description: Contus Vblog for wordpress is easy to install plugin which enables you to post Video blog instead of text.
Version: 2.0
Author: Apptha Team 
Author URI: http://www.apptha.com/
*/
if(isset($GLOBALS["HTTP_RAW_POST_DATA"])){
	// get bytearray
	$jpg = $GLOBALS["HTTP_RAW_POST_DATA"];

	// add headers for download dialog-box
	$filename = "images/". $_GET["id"]. ".jpg";
	file_put_contents($filename, $jpg);
        $size = getimagesize($filename);
        if($size["mime"] != "image/jpeg" || $size["mime"] != ""){
            unlink($filename);
            echo "Invalid image type";
        }
} else{
	echo "Encoded JPEG information not received.";
}
?>