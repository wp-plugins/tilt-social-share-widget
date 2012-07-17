<?php
/*
Plugin Name: Tilt Social Share Widget
Plugin URI: http://xonoxlabs.com/
Description: Display icons that allow users to share your posts and pages in common social websites.
Author: Rui Oliveira
Version: 0.97
Author URI: http://xonoxlabs.com/

Special thanks to JS Morisset from http://trtms.com/ for the popup and archive-type code.

*/

// Define paths
if(!defined('WP_PLUGIN_URL')) define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');

class tiltSocialWidget extends WP_Widget {

	private $services = array(
		'delicious' => array(
			'slug' => 'delicious',
			'name' => 'Del.icio.us',
			'label' => 'Del.icio.us',
			'url' => 'http://del.icio.us/post?url=[URL]&title=[TITLE]]&notes=[DESCRIPTION]',
			'width' => '700',
			'height' => '500',
		),
		'designbump' => array(
			'slug' => 'designbump',
			'name' => 'Designbump',
			'label' => 'Designbump',
			'url' => 'http://www.designbump.com/submit?url=[URL]&title=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'digg' => array(
			'slug' => 'digg',
			'name' => 'Digg',
			'label' => 'Digg',
			'url' => 'http://www.digg.com/submit?phase=2&url=[URL]&title=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'diigo' => array(
			'slug' => 'diigo',
			'name' => 'Diigo',
			'label' => 'Diigo',
			'url' => 'http://www.diigo.com/post?url=[URL]&title=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'evernote' => array(
			'slug' => 'evernote',
			'name' => 'Evernote',
			'label' => 'Evernote',
			'url' => 'http://www.evernote.com/clip.action?url=[URL]&title=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'facebook' => array(
			'slug' => 'facebook',
			'name' => 'Facebook',
			'label' => 'Facebook',
			'url' => 'http://www.facebook.com/share.php?u=[URL]',
			'width' => '640',
			'height' => '400',
		),
		'friendfeed' => array(
			'slug' => 'friendfeed',
			'name' => 'FriendFeed',
			'label' => 'FriendFeed',
			'url' => 'http://www.friendfeed.com/share?url=[URL]&title=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'gbookmarks' => array(
			'slug' => 'gbookmarks',
			'name' => 'Google Bookmarks',
			'label' => 'Google Bookmarks',
			'url' => 'http://www.google.com/bookmarks/mark?op=edit&bkmk=[URL]&title=[TITLE]&annotation=[DESCRIPTION]',
			'width' => '640',
			'height' => '400',
		),
		'gbuzz' => array(
			'slug' => 'gbuzz',
			'name' => 'Google Buzz',
			'label' => 'Google Buzz',
			'url' => 'http://www.google.com/reader/link?title=[TITLE]&url=[URL]',
			'width' => '640',
			'height' => '400',
		),
		'gplus' => array(
			'slug' => 'gplus',
			'name' => 'Google+',
			'label' => 'Google+',
			'url' => 'https://plus.google.com/share?url=[URL]',
			'width' => '640',
			'height' => '400',
		),
		'linkedin' => array(
			'slug' => 'linkedin',
			'name' => 'LinkedIn',
			'label' => 'LinkedIn',
			'url' => 'http://www.linkedin.com/shareArticle?mini=true&url=[URL]&title=[TITLE]&source=[DOMAIN]',
			'width' => '640',
			'height' => '400',
		),
		'newsvine' => array(
			'slug' => 'newsvine',
			'name' => 'Newsvine',
			'label' => 'Newsvine',
			'url' => 'http://www.newsvine.com/_tools/seed&save?u=[URL]&h=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'pingfm' => array(
			'slug' => 'pingfm',
			'name' => 'Ping.fm',
			'label' => 'Ping.fm',
			'url' => 'http://ping.fm/ref/?link=[URL]&title=[TITLE]&body=[DESCRIPTION]',
			'width' => '640',
			'height' => '400',
		),
		'pinterest' => array(
			'slug' => 'pinterest',
			'name' => 'Pinterest',
			'label' => 'Pinterest',
			'url' => 'http://pinterest.com/pin/create/button/?url=[URL]&media=[URL]&description=[DESCRIPTION]',
			'width' => '640',
			'height' => '400',
		),
		'posterous' => array(
			'slug' => 'posterous',
			'name' => 'Posterous',
			'label' => 'Posterous',
			'url' => 'http://posterous.com/share?linkto=[URL]',
			'width' => '640',
			'height' => '400',
		),
		'reddit' => array(
			'slug' => 'reddit',
			'name' => 'Reddit',
			'label' => 'Reddit',
			'url' => 'http://www.reddit.com/submit?url=[URL]&title=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'slashdot' => array(
			'slug' => 'slashdot',
			'name' => 'Slashdot',
			'label' => 'Slashdot',
			'url' => 'http://slashdot.org/bookmark.pl?url=[URL]&title=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'stumbleupon' => array(
			'slug' => 'stumbleupon',
			'name' => 'StumbleUpon',
			'label' => 'StumbleUpon',
			'url' => 'http://www.stumbleupon.com/submit?url=[URL]&title=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'technorati' => array(
			'slug' => 'technorati',
			'name' => 'Technorati',
			'label' => 'Technorati',
			'url' => 'http://technorati.com/faves?add=[URL]&title=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'tumblr' => array(
			'slug' => 'tumblr',
			'name' => 'Tumblr',
			'label' => 'Tumblr',
			'url' => 'http://www.tumblr.com/share?v=3&u=[URL]&t=[TITLE]',
			'width' => '640',
			'height' => '400',
		),
		'twitter' => array(
			'slug' => 'twitter',
			'name' => 'Twitter',
			'label' => 'Twitter',
			'url' => 'http://twitter.com/home?status=[TITLE]+%7C+[URL]',
			'width' => '450',
			'height' => '250',
		),
		'yahoo' => array(
			'slug' => 'yahoo',
			'name' => 'Yahoo Bookmarks',
			'label' => 'Yahoo Bookmarks',
			'url' => 'http://bookmarks.yahoo.com/toolbar/savebm?u=[URL]&t=[TITLE]',
			'width' => '640',
			'height' => '400',
		)
	);

	// Widget actual processes
	function __construct() {
		parent::WP_Widget('tilt_social_widget', 'Tilt Social Share', array('description' => 'Display icons that allow users to share your posts and pages in common social websites.'));
	}
	
	// Outputs the options form on admin
	function form($instance) {
	
		// Set up default widget settings
		$defaults = array(
			'title' => 'Share...',
			'tiltcss' => true,
			'pages' => false,
			'posts' => true,
			'multi' => false,
			'multi_share' => 'site_info',
			'order' => 'gplus, facebook, linkedin, twitter, stumbleupon, digg, delicious',
			'on_delicious' => false,
			'on_designbump' => false,
			'on_digg' => true,
			'on_diigo' => false,
			'on_evernote' => false,
			'on_facebook' => true,
			'on_friendfeed' => false,
			'on_gbookmarks' => false,
			'on_gbuzz' => false,
			'on_gplus' => true,
			'on_linkedin' => true,
			'on_newsvine' => false,
			'on_pingfm' => false,
			'on_pinterest' => false,
			'on_posterous' => false,
			'on_reddit' => true,
			'on_slashdot' => false,
			'on_stumbleupon' => true,
			'on_technorati' => false,
			'on_tumblr' => true,
			'on_twitter' => true,
			'on_yahoo' => false
		);
		
		$instance = wp_parse_args((array)$instance, $defaults); 
		
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo($instance['title']); ?>" style="width:100%;" />
			</p>

			<p>Where should Tilt Social Share be used:</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked($instance['pages']); ?> 
					id="<?php echo $this->get_field_id('pages'); ?>" name="<?php echo $this->get_field_name('pages'); ?>" />
				<label for="<?php echo $this->get_field_id('pages'); ?>"> Pages</label><br />
				<input class="checkbox" type="checkbox" <?php checked($instance['posts']); ?> 
					id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" />
				<label for="<?php echo $this->get_field_id('posts'); ?>"> Posts</label><br />
				<input class="checkbox" type="checkbox" <?php checked($instance['multi']); ?> 
					id="<?php echo $this->get_field_id('multi'); ?>" name="<?php echo $this->get_field_name('multi'); ?>" />
				<label for="<?php echo $this->get_field_id('multi'); ?>"> Multi-entry webpages (home page, archives, categories, etc.)</label><br />
			</p>
			<p>What information (url, title, and description) should be shared on multi-entry pages:</p>
			<p>
				<?php $options = get_option( 'my_option' ); ?>
				<input class="radio" type="radio" value="site_info" <?php checked($instance['multi_share'] == 'site_info'); ?> 
					id="<?php echo $this->get_field_id('multi_share'); ?>" name="<?php echo $this->get_field_name('multi_share'); ?>" />
				<label for="<?php echo $this->get_field_id('multi_share'); ?>"> The home page information</label><br />

				<input class="radio" type="radio" value="multi_info" <?php checked($instance['multi_share'] == 'multi_info'); ?> 
					id="<?php echo $this->get_field_id('multi_share'); ?>" name="<?php echo $this->get_field_name('multi_share'); ?>" />
				<label for="<?php echo $this->get_field_id('multi_share'); ?>"> The multi-entry page information</label><br />

				<input class="radio" type="radio" value="post_info" <?php checked($instance['multi_share'] == 'post_info'); ?> 
					id="<?php echo $this->get_field_id('multi_share'); ?>" name="<?php echo $this->get_field_name('multi_share'); ?>" />
				<label for="<?php echo $this->get_field_id('multi_share'); ?>"> Information from the latest entry listed</label>
			</p>
			<p>Select which services are enabled:</p>
			<p>
				<?php
					foreach($this->services as $service) {
						if($instance['on_' . $service['slug']]) $checked = 'checked="checked"'; else $checked = '';
						echo('<input class="checkbox" type="checkbox" ' . $checked . ' id="' . $this->get_field_id('on_' . $service['slug']) . '" name="' . $this->get_field_name('on_' . $service['slug']) . '" />');
						echo('<label for="' . $this->get_field_id('on_' . $service['slug']) . '"> ' . $service['name'] . ' (' . $service['slug']  . ')</label><br />');
					}
				?>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('order'); ?>">Order to display icons:</label>
				<input id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" value="<?php echo($instance['order']); ?>" style="width:100%;" />
			</p>
		<?php
	}

	// Processes widget options to be saved
	function update($new_instance, $old_instance) {
	
		$instance = $old_instance;

		// Strip tags (if needed) and update the widget settings
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['tiltcss'] = (bool)$new_instance['tiltcss'];
		$instance['pages'] = (bool)$new_instance['pages'];
		$instance['posts'] = (bool)$new_instance['posts'];
		$instance['multi'] = (bool)$new_instance['multi'];
		$instance['multi_share'] = $new_instance['multi_share'];
		$instance['order'] = strip_tags($new_instance['order']);
		$instance['on_delicious'] = (bool)$new_instance['on_delicious'];
		$instance['on_designbump'] = (bool)$new_instance['on_designbump'];
		$instance['on_digg'] = (bool)$new_instance['on_digg'];
		$instance['on_diigo'] = (bool)$new_instance['on_diigo'];
		$instance['on_evernote'] = (bool)$new_instance['on_evernote'];
		$instance['on_facebook'] = (bool)$new_instance['on_facebook'];
		$instance['on_friendfeed'] = (bool)$new_instance['on_friendfeed'];
		$instance['on_gbookmarks'] = (bool)$new_instance['on_gbookmarks'];
		$instance['on_gbuzz'] = (bool)$new_instance['on_gbuzz'];
		$instance['on_gplus'] = (bool)$new_instance['on_gplus'];
		$instance['on_linkedin'] = (bool)$new_instance['on_linkedin'];
		$instance['on_newsvine'] = (bool)$new_instance['on_newsvine'];
		$instance['on_pingfm'] = (bool)$new_instance['on_pingfm'];
		$instance['on_pinterest'] = (bool)$new_instance['on_pinterest'];
		$instance['on_posterous'] = (bool)$new_instance['on_posterous'];
		$instance['on_reddit'] = (bool)$new_instance['on_reddit'];
		$instance['on_slashdot'] = (bool)$new_instance['on_slashdot'];
		$instance['on_stumbleupon'] = (bool)$new_instance['on_stumbleupon'];
		$instance['on_technorati'] = (bool)$new_instance['on_technorati'];
		$instance['on_tumblr'] = (bool)$new_instance['on_tumblr'];
		$instance['on_twitter'] = (bool)$new_instance['on_twitter'];
		$instance['on_yahoo'] = (bool)$new_instance['on_yahoo'];

		return($instance);
	
	}
	
	// Outputs the content of the widget
	function widget($args, $instance) {
	
		// Check if it should display widget
		if(is_page() && !$instance['pages']) return;
		if(is_single() && !$instance['posts']) return;
		if(!is_singular() && !$instance['multi']) return;	// skip multi-entry pages
		if( is_404() ) return;
	
		extract($args);

		// User-selected settings
		$title = apply_filters('widget_title', $instance['title'] );

		// Before widget
		echo $before_widget;

		// Title of widget
		if($title) echo($before_title . $title . $after_title);

		?>
		<ul class="tssw-list">
		<script type="text/javascript">
			function popupWin (popUrl, popWidth, popHeight) {
				if (! popWidth) popWidth = 640;
				if (! popHeight) popHeight = 400;
				var newWin = window.open(popUrl,'Social Share','toolbar=no,status=no,location=no,menubar=no,width='+popWidth+',height='+popHeight);
				newWin.focus();
				return false; 
			}
		</script>
			<?php

				if ( is_home() || ( ! is_singular() && $instance['multi_share'] == 'site_info') ) {

					$pageURL = home_url();
					$pageTitle = get_bloginfo( 'name', 'display' );
					$pageDesc = get_bloginfo( 'description', 'display' );

				} elseif ( ! is_singular() && $instance['multi_share'] == 'multi_info') {

					$pageURL = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

					global $page, $paged;
					$pageTitle = wp_title( '|', false, 'right' );
					$pageTitle .= get_bloginfo( 'name', 'display' );
					if ( $paged >= 2 || $page >= 2 ) $pageTitle .= ' | ' . sprintf( 'Page %s', max( $paged, $page ) );

					if ( is_author() ) {  the_post(); $pageDesc = sprintf( 'Authored by %s', get_the_author() ); }
					elseif ( is_tag() ) $pageDesc = sprintf( 'Tagged with &quot;%s&quot;', single_tag_title('', false) );
					elseif ( is_category() ) $pageDesc = sprintf( '&quot;%s&quot; Category', single_cat_title('', false) );
					elseif ( is_day() ) $pageDesc = sprintf( 'Daily Archives for %s', get_the_date() );
					elseif ( is_month() ) $pageDesc = sprintf( 'Monthly Archives for %s', get_the_date('F Y') );
					elseif ( is_year() ) $pageDesc = sprintf( 'Yearly Archives for %s', get_the_date('Y') );
					else $pageDesc = get_bloginfo('description', 'display');	// just in case we missed one. ;-)

				} else {

					the_post();
					$pageURL = get_permalink();
					$pageTitle = get_the_title().' | '.get_bloginfo( 'name', 'display' );
					$pageDesc = esc_attr(substr(strip_tags(get_the_excerpt()), 0, 300));

				}
		
				// Start by the ordered elements
				$order = str_replace(' ', '', $instance['order']);
				if($order != '') {
					$elements = explode(',', $order);
					foreach($elements as $element) {
						if($instance['on_'. $element]) {
							echo '<li class="tssw-item"><a onclick="return popupWin 
								(this.href, '.$this->services[$element]['width'].', '.$this->services[$element]['height'].')" 
									href="'.$this->processUrl( $this->services[$element]['slug'], 
										$this->services[$element]['url'], $pageTitle, $pageDesc, $pageURL ).'">
								<span class="tssw-icon tssw-'.$this->services[$element]['slug'].'"></span>
								<span class="tssw-tooltip">'.$this->services[$element]['label'].'</span></a></li>';
						}
					}
				}

				// Do the others
				foreach($this->services as $service) {
					if($instance['on_'.$service['slug']] && !in_array($service['slug'], $elements)) {
						echo '<li class="tssw-item"><a onclick="return popupWin 
							(this.href, '.$service['width'].', '.$service['height'].')" 
								href="'.$this->processUrl( $service['slug'], 
									$service['url'], $pageTitle, $pageDesc, $pageURL ).'">
							<span class="tssw-icon tssw-'.$service['slug'].'"></span>
							<span class="tssw-tooltip">'.$service['label'].'</span></a></li>';
					}
				}

			?>
		</ul>
		<?php
		
		// After widget
		echo $after_widget;
		
	}

	function processUrl($slug, $url, $pageTitle, $pageDesc, $pageURL) {

		// there's some stubborn encoding in some of these strings...
		$pageTitle = preg_replace('/&#\d{2,5};/ue', "tiltSocialWidget_utf8_entity_decode('$0')", $pageTitle);
		$pageDesc = preg_replace('/&#\d{2,5};/ue', "tiltSocialWidget_utf8_entity_decode('$0')", $pageDesc);

		$url = str_replace('[DOMAIN]', urlencode( $pageDom ), $url);
		$url = str_replace('[URL]', urlencode( $pageURL ), $url);
		$url = str_replace('[TITLE]', urlencode( $pageTitle ), $url);
		$url = str_replace('[DESCRIPTION]', urlencode( $pageDesc ), $url);

		return $url;
	}
	
}

// callback function for the regex
function tiltSocialWidget_utf8_entity_decode( $entity ) {
	$convmap = array( 0x0, 0x10000, 0, 0xfffff );
	return mb_decode_numericentity( $entity, $convmap, 'UTF-8' );
}

// Register widget
add_action('widgets_init', 'tiltSocialWidget_load_widgets');

function tiltSocialWidget_load_widgets() {
    register_widget('tiltSocialWidget');
	// Load widget CSS
	if(is_active_widget(false, false, 'tilt_social_widget')) add_action('wp_head', 'tiltSocialWidget_load_style');
}

function tiltSocialWidget_load_style() {
	wp_register_style('tiltSocialWidget_css', WP_PLUGIN_URL . '/tilt-social-share-widget/css/style.css');
	wp_enqueue_style('tiltSocialWidget_css');
}

?>
