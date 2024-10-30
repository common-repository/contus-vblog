<?php
/*
Plugin Name: Contus VBlog - Video Blogging
Description: Contus Vblog for wordpress is easy to install plugin which enables you to post Video blog instead of text.
Version: 2.0
Author: Apptha Team 
Author URI: http://www.apptha.com/
*/

function widget_ContusRecentPosts_init() {


	if ( !function_exists('register_sidebar_widget') )
		return;

		function widget_ContusRecentPosts($args) {

			// "$args is an array of strings that help widgets to conform to
			// the active theme: before_widget, before_title, after_widget,
			// and after_title are the array keys." - These are set up by the theme
			extract($args);
            global $wpdb, $wp_version, $popular_posts_current_ID;
			// These are our own options
			$options = get_option('widget_ContusRecentPosts');
			$title = $options['title'];  // Title in sidebar for widget
			$show = $options['show'];  // # of Posts we are showing
			$excerpt = $options['excerpt'];  // Showing the excerpt or not
			$exclude = $options['exclude'];  // Categories to exclude
            if ($show<1) $show = 1;
			if ($exclude=="") $exclude = "0";


			if($wp_version >= 2.3)
			{
$r = new WP_Query(array('showposts' => $show, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1));
if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post(); $queried_post = get_post(get_the_ID()); $currentid=$queried_post->post_content;
		preg_match('/<a href="http:\/\/(.*?)#content">Play<\/a>/',$currentid, $results);

		$currentid =$results[1];

		?>
		<li><div style="padding:0;margin:0;"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><img src="wp-content/plugins/Contus-VBlog/images/<?php echo $currentid.".jpg"; ?> " alt="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>" width="185" height="135"></a></div><div style="padding:0;margin:0;"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?> </a></div></li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
			wp_reset_query();  // Restore global post data stomped by the_post().
		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('widget_recent_posts', $cache, 'widget');
}
else
{
$sql = 'select DISTINCT * from '.$wpdb->posts.'
			INNER JOIN (select * from '.$wpdb->post2cat.'
			INNER JOIN '.$wpdb->categories .' ON '.$wpdb->post2cat.'.category_id = '.$wpdb->categories .'.cat_ID)
			as A ON '.$wpdb->posts.'.ID = A.post_ID
			WHERE (A.cat_ID NOT IN ('.$exclude.'))
			AND '.$wpdb->posts.'.post_status="publish"
			AND '.$wpdb->posts.'.post_type="post"
			GROUP BY ID
			ORDER BY '.$wpdb->posts.'.post_date
			DESC LIMIT 0,'.$show.';';

			$posts = $wpdb->get_results($sql);
			echo '<ul>';
				// were there any posts found?
				if (!empty($posts)) {
					// posts were found, loop through them
					 foreach ($posts as $post) {

							// format a date for the posts
							$post->post_date = date("F j, Y",strtotime($post->post_date));

							// if we want to display an excerpt, get it/generate it if no excerpt found
							if ($excerpt) {
								 if (empty($post->post_excerpt)) {
									 $post->post_excerpt = explode(" ",strrev(substr(strip_tags($post->post_content), 0, 100)),2);
									 $post->post_excerpt = strrev($post->post_excerpt[1]);
									 $post->post_excerpt.= " [...]";
								 }
							}
							$currentid=$post->post_content;
		preg_match('/<a href="http:\/\/(.*?)#content">Play<\/a>/',$currentid, $results);

		$currentid =$results[1];
							//output to screen
							echo '<li><div style="padding:0;margin:0;"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><img src="wp-content/plugins/Contus-VBlog/images/'. $currentid.'.jpg" alt="'.$post->post_title.'" width="185" height="135"></a></div><div  style="padding:0;margin:0;"><a class="post" rel="bookmark" href="'.get_permalink($post->ID).'"><span class="inner">
							<strong class="title lifestyle">'.$post->post_date.' - '.$post->post_title.'</strong>';

							if ($excerpt) echo '<br />'.strip_tags($post->post_excerpt);

							echo '</span></a></div></li>';
					 }
				} else echo "<li>No recent Posts</li>";
		// end list
		echo '</ul>';

		// echo widget closing tag
		echo $after_widget;
}

	}


	// Settings form
	function widget_ContusRecentPosts_control() {

		// Get options
		$options = get_option('widget_ContusRecentPosts');
		// options exist? if not set defaults
		if ( !is_array($options) )
			$options = array('title'=>'Recent Posts', 'show'=>'5', 'excerpt'=>'1','exclude'=>'');

		// form posted?
		if ( $_POST['ContusRecentPosts-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['ContusRecentPosts-title']));
			$options['show'] = strip_tags(stripslashes($_POST['ContusRecentPosts-show']));
			$options['excerpt'] = strip_tags(stripslashes($_POST['ContusRecentPosts-excerpt']));
			$options['exclude'] = strip_tags(stripslashes($_POST['ContusRecentPosts-exclude']));
			update_option('widget_ContusRecentPosts', $options);
		}

		// Get options for form fields to show
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$show = htmlspecialchars($options['show'], ENT_QUOTES);
		$excerpt = htmlspecialchars($options['excerpt'], ENT_QUOTES);
		$exclude = htmlspecialchars($options['exclude'], ENT_QUOTES);

		// The form fields
		echo '<p style="text-align:right;">
				<label for="ContusRecentPosts-title">' . __('Title:') . '
				<input style="width: 200px;" id="ContusRecentPosts-title" name="ContusRecentPosts-title" type="text" value="'.$title.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="ContusRecentPosts-show">' . __('Show:') . '
				<input style="width: 200px;" id="ContusRecentPosts-show" name="ContusRecentPosts-show" type="text" value="'.$show.'" />
				</label></p>';

		echo '<input type="hidden" id="ContusRecentPosts-submit" name="ContusRecentPosts-submit" value="1" />';
	}

	// Register widget for use
	register_sidebar_widget(array('Contus Recent Posts', 'widgets'), 'widget_ContusRecentPosts');

	// Register settings for use, 300x100 pixel form
	register_widget_control(array('Contus Recent Posts', 'widgets'), 'widget_ContusRecentPosts_control', 300, 200);
}

// Run code and init
add_action('widgets_init', 'widget_ContusRecentPosts_init');

?>