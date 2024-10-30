<?php
/*
Plugin Name: Contus VBlog - Video Blogging
Description: Contus Vblog for wordpress is easy to install plugin which enables you to post Video blog instead of text.
Version: 2.0
Author: Apptha Team 
Author URI: http://www.apptha.com/
*/
function hdvideo_header() {
global $wpdb;
$postreq="";
if($_GET['p'])
{
$postID = (int) $_GET['p'];
$postreq=$postID;
}
elseif(!isset($postID)) {
			global $post;
			$postID = (int) $post->ID;

		}

		//$count = (int) $wpdb->get_var("SELECT term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id = $postID");
		$count = (int) $wpdb->get_var("SELECT a.term_taxonomy_id FROM $wpdb->term_relationships as a inner join $wpdb->term_taxonomy as t on t.term_taxonomy_id = a.term_taxonomy_id WHERE t.taxonomy ='category' AND object_id = $postID");

		$categoryname = $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE term_id = $count");

		$currentid = $wpdb->get_var("SELECT p.post_content FROM  $wpdb->posts as p   WHERE  p.post_status ='publish' and p.ID =$postID");
		$totals = $wpdb->get_results( "SELECT p.ID,p.post_content,p.post_title,p.guid  FROM $wpdb->term_relationships as a inner join $wpdb->posts as p on p.ID = a.object_id  WHERE a.term_taxonomy_id = $count  AND p.ID <> $postID AND p.post_status ='publish' order by p.ID desc");

		 preg_match('/<a href="http:\/\/(.*?)#content">Play<\/a>/',$currentid, $results);

		$currentid =$results[1];

	$returnid = array();
	$returncontent = array();
	$returntitle = array();
	$returnurl = array();
	$i=0;
	foreach ( $totals as $total )
	{
		$returnid[$i] = $total->ID;
		 preg_match('/<a href="http:\/\/(.*?)#content">Play<\/a>/',$total->post_content, $result);

		$returncontent[$i] =$result[1];
		$returntitle[$i]=$total->post_title;
		$returnurl[$i]=$total->guid;
		$i++;
		}

    $site_url = get_option('siteurl');



    $optionvalue = get_option('webflvOptions');

    $serverpath = $optionvalue[0];

 $cvc = dirname( plugin_basename(__FILE__));

    ?><script type="text/javascript" >
var ids = new Array();
var videoids = new Array();
var videotitles = new Array();
var posturl = new Array();
var imagepath ="<?php echo $site_url.'/wp-content/plugins/'.$cvc.'/images/';?>";
var imagereq;
<?php $j=0;
  for($i=0;$i<count($returnid);$i++)
  {  if($returncontent[$i]!="")
  {?>
  ids[<?php echo $j; ?>]= '<?php echo $returnid[$i];?>';
   videoids[<?php echo $j; ?>]= '<?php echo $returncontent[$i];?>';
    videotitles[<?php echo $j; ?>]= '<?php echo $returntitle[$i];?>';
	posturl[<?php echo $j; ?>]= '<?php echo $returnurl[$i];?>';
  <?php $j++;} } ?>
    var imagereq ="<?php echo $postreq ?>";

    var serverpath ="<?php echo $serverpath ?>";
	 var currentid ="<?php echo $currentid ?>";

    var site_url ="<?php echo $site_url ?>";

	  var categoryname ="<?php echo $categoryname ?>";


    var pwidth ="<?php echo $optionvalue[1] ?>";

    var pheight ="<?php echo $optionvalue[2] ?>";

    var rwidth ="<?php echo $optionvalue[3] ?>";

    var rheight ="<?php echo $optionvalue[4] ?>";

    var maxduration ="<?php echo $optionvalue[5] ?>";

    var license ="<?php echo $optionvalue[6] ?>";

	 var pcwidth ="<?php echo $optionvalue[7] ?>";

    var pcheight ="<?php echo $optionvalue[8] ?>";

    var rcwidth ="<?php echo $optionvalue[9] ?>";

    var rcheight ="<?php echo $optionvalue[10] ?>";

    var cmaxduration ="<?php echo $optionvalue[11] ?>";

       var cvc ="<?php echo $cvc ?>";

</script><?

    echo '

<script type="text/javascript" src="'.$site_url.'/wp-content/plugins/'.$cvc.'/videoblog.js"></script>
<script type="text/javascript" src="'.$site_url.'/wp-content/plugins/'.$cvc.'/related_thumbs.js"></script>

<script type="text/javascript" src="'.$site_url.'/wp-content/plugins/'.$cvc.'/jquery-works.js"></script>
<script type="text/javascript" src="'.$site_url.'/wp-content/plugins/'.$cvc.'/jquery-1-works.js"></script>


<link rel="stylesheet" href="'.$site_url.'/wp-content/plugins/'.$cvc.'/css/works.css" type="text/css" />
<link rel="stylesheet" href="'.$site_url.'/wp-content/plugins/'.$cvc.'/css/hdvideo.css" type="text/css" media="all" />';

}



/*

* Convert legacy tags into modern hdvideo links

*/



function hdvideo_comment_text($comment = '') {



    if ($comment != '') {

        $pattern = '/\[hdvideo_video\](.*)\[\/hdvideo_video\]/';

        preg_match_all($pattern, $comment, $matches);



        foreach ($matches[1] as $hdvideo_id) {

            $pattern = '/\[hdvideo_video\]' . $hdvideo_id . '\[\/hdvideo_video\]/';

            $replacement = sprintf('<a href="http://hdvideo.com/v/%s#video">Play Video Comment</a>', $hdvideo_id);

            $comment = preg_replace($pattern, $replacement, $comment);

        }



        $pattern = '/\[hdvideo_audio\](.*)\[\/hdvideo_audio\]/';

        preg_match_all($pattern, $comment, $matches);



        foreach ($matches[1] as $hdvideo_id) {

            $pattern = '/\[hdvideo_audio\]' . $hdvideo_id . '\[\/hdvideo_audio\]/';

            $replacement = sprintf('<a href="http://hdvideo.com/v/%s#audio">Play Audio Comment</a>', $hdvideo_id);

            $comment = preg_replace($pattern, $replacement, $comment);

        }

    }



    return $comment;

}







function hdvideo_footer() {



}



function web_deinstall() {

    delete_option('webflvOptions');

}

function webflvAddPage() {

    add_options_page('Contus VBlog Settings', 'Contus VBlog', '8', 'wp-webflv.php', 'webflvOptions');

}

function webflvOptions() {

    $option = get_option('webflvOptions');

    $options[0] = $option[0];

    $options[1] = $option[1];

    $options[2] = $option[2];

    $options[3] = $option[3];

    $options[4] = $option[4];

    $options[5] = $option[5];

    $options[6] = $option[6];

	$options[7] = $option[7];

    $options[8] = $option[8];

    $options[9] = $option[9];

    $options[10] = $option[10];

    $options[11] = $option[11];

    if ($_POST) {

        $options[0]= $_POST['path'];

        $options[1]= $_POST['pwidth'];

        $options[2]= $_POST['pheight'];

        $options[3]= $_POST['rwidth'];

        $options[4]= $_POST['rheight'];

        $options[5]= $_POST['maxduration'];

        $options[6]= $_POST['license'];

		 $options[7]= $_POST['pcwidth'];

        $options[8]= $_POST['pcheight'];

        $options[9]= $_POST['rcwidth'];

        $options[10]= $_POST['rcheight'];

        $options[11]= $_POST['cmaxduration'];

        update_option('webflvOptions', $options);



    }

    if($options =='') $options[0]="Enter the Path";

     echo '<div class="wrap">';

    echo '<h2>Contus VBlog Settings</h2>';

    echo '<form method="post" action="options-general.php?page=wp-webflv.php"><table cellspacing="15">';

     echo '<tr><td>Server path to store the Video comments:</td><td ><input type="text" name="path" value="'.$options[0].'" size=45  /></td></tr>';

    echo '<tr><td>Player Scale:</td><td><table cellspacing="5"><tr><td><input type="text" name="pwidth" value="'.$options[1].'" size=5  /></td><td>X</td><td><input type="text" name="pheight" value="'.$options[2].'" size=5  /></td></tr></table></td></tr>';

    echo '<tr><td>Recorder Scale:</td><td><table cellspacing="5"><tr><td><input type="text" name="rwidth" value="'.$options[3].'" size=5  /></td><td>X</td><td><input type="text" name="rheight" value="'.$options[4].'" size=5  /></td></tr></table></td></tr>';

    echo '<tr><td>Max duration:</td><td ><input type="text" name="maxduration" value="'.$options[5].'" size=10  /></td></tr>';

    echo '<tr><td>License Key:</td><td ><input type="text" name="license" value="'.$options[6].'" size=45  /></td></tr></table>';



  echo '<h2>Contus VBlog Video Comments Settings</h2>';

   echo '<table cellspacing="15">';

    echo '<tr><td>Player Scale:</td><td><table cellspacing="5"><tr><td><input type="text" name="pcwidth" value="'.$options[7].'" size=5  /></td><td>X</td><td><input type="text" name="pcheight" value="'.$options[8].'" size=5  /></td></tr></table></td></tr>';

    echo '<tr><td>Recorder Scale:</td><td><table cellspacing="5"><tr><td><input type="text" name="rcwidth" value="'.$options[9].'" size=5  /></td><td>X</td><td><input type="text" name="rcheight" value="'.$options[10].'" size=5  /></td></tr></table></td></tr>';

    echo '<tr><td>Max duration:</td><td ><input type="text" name="cmaxduration" value="'.$options[11].'" size=10  /></td></tr></table>';


	echo '<p class="submit"><input class="button-primary" type="submit" method="post" value="Update Options"></p>';
    echo '</form>';



}



function webflv_load() {

    $options[0]="Enter the path here";

    $options[1]="500";

    $options[2]="420";

    $options[3]="320";

    $options[4]="290";

    $options[5]="200";

    $options[6]="Enter the License Key here";

	$options[7]="320";

    $options[8]="260";

    $options[9]="320";

    $options[10]="290";

    $options[11]="200";


    return $options;



}



function webflv_activate() {

    update_option('webflvOptions', webflv_load());

}



register_activation_hook(__FILE__,'webflv_activate');

register_uninstall_hook(__FILE__, 'webflv_deinstall');





add_filter('admin_head', 'hdvideo_header');

add_filter('wp_head', 'hdvideo_header');



add_filter('wp_footer', 'hdvideo_footer');



add_filter('comment_text', 'hdvideo_comment_text'); // Convert legacy tags into modern hdvideo links

add_action('admin_menu', 'webflvAddPage');

?>