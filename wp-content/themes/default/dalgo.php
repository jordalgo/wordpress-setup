<?php

  // Adding Translation Option
  load_theme_textdomain( 'jordalgo', TEMPLATEPATH.'/languages' );
  $locale = get_locale();
  $locale_file = TEMPLATEPATH."/languages/$locale.php";
  if ( is_readable($locale_file) ) require_once($locale_file);

  // Cleaning up the Wordpress Head
  function dalgo_head_cleanup() {
  	remove_action( 'wp_head', 'rsd_link' );                               
  	remove_action( 'wp_head', 'wlwmanifest_link' );                       
  	remove_action( 'wp_head', 'index_rel_link' );                         
  	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );            
  	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );             
  	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
  	remove_action( 'wp_head', 'wp_generator' );
  }

	add_action('init', 'dalgo_head_cleanup');
	
  // remove WP version from RSS
	function dalgo_rss_version() { return ''; }
	add_filter('the_generator', 'dalgo_rss_version');
	
  // This removes the annoying […] to a Read More link
  function dalgo_excerpt_more($more) {
  	global $post;
  	// edit here if you like
  	return '...  <a href="'. get_permalink($post->ID) . '" title="Read '.get_the_title($post->ID).'">Read more &raquo;</a>';
  }
  add_filter('excerpt_more', 'dalgo_excerpt_more');
	
// Adding WP 3+ Functions & Theme Support
function dalgo_theme_support() {
	add_theme_support('post-thumbnails');      // wp thumbnails (sizes handled in functions.php)
	//set_post_thumbnail_size(125, 125, true);   // default thumb size
	add_custom_background();                   // wp custom background
	add_theme_support('automatic-feed-links'); // rss thingy
	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/
	// adding post format support
	add_theme_support( 'post-formats',      // post formats
		array( 
			'aside',   // title less blurb
			'gallery', // gallery of images
			'link',    // quick link to other site
			'image',   // an image
			'quote',   // a quick quote
			'status',  // a Facebook like status update
			'video',   // video 
			'audio',   // audio
			'chat'     // chat transcript 
		)
	);	
	add_theme_support( 'menus' );            // wp menus
	register_nav_menus(                      // wp3+ menus
		array( 
			'main_nav' => 'The Main Menu',   // main nav in header
			'footer_links' => 'Footer Links', // secondary nav in footer
      'social_media_links' => 'Social Media Links' //icon nav in footer
		)
	);	
}

	// launching this stuff after theme setup
	add_action('after_setup_theme','dalgo_theme_support');	
	// adding sidebars to Wordpress (these are created in functions.php)
	add_action( 'widgets_init', 'dalgo_register_sidebars' );
	// adding the dalgo search form (created in functions.php)
	add_filter( 'get_search_form', 'dalgo_wpsearch' );
	

class dalgo_walker extends Walker_Nav_Menu {

	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$output .= $indent . '<li'. $class_names .'">';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		//$item_output .= '<br /><span class="sub">' . $item->description . '</span>';
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}	

function dalgo_main_nav() {
	// display the wp3 menu if available
	$walker = new dalgo_walker;

	wp_nav_menu(array( 
		'menu' => 'main_nav', /* menu name */
		'theme_location' => 'main_nav', /* where in the theme it's assigned */
		'container' => false,
		'fallback_cb' => 'dalgo_main_nav_fallback', /* menu fallback */
	  'items_wrap' => '<ul class="main_nav">%3$s</ul>',
	  'walker' => $walker /* customizes the output of the menu */
	));
}

function dalgo_footer_links() { 
	// display the wp3 menu if available
  wp_nav_menu(
  	array(
  		'menu' => 'footer_links',
  		'theme_location' => 'footer_links',
      'container_id'    => 'foot-links',
      'container_class' => '',
  		'fallback_cb' => 'dalgo_footer_links_fallback'
  	)
	);
}

function dalgo_social_media_links() { 
  // display the wp3 menu if available
  wp_nav_menu(
    array(
      'menu' => 'social_media_links',
      'theme_location' => 'social_media_links',
      'container_id'    => 'social-media-links',
      'container_class' => '',
      'fallback_cb' => 'dalgo_social_media_links_fallback',
      'walker' => $walker
    )
  );
}
 
// this is the fallback for header menu
function dalgo_main_nav_fallback() { 
	wp_page_menu( 'show_home=Home&menu_class=menu' ); 
}

// this is the fallback for footer menu
function dalgo_footer_links_fallback() { 
	/* you can put a default here if you like */ 
}

// this is the fallback for social media menu
function dalgo_social_media_links_fallback() { 
  /* you can put a default here if you like */ 
}


/****************** PLUGINS & EXTRA FEATURES **************************/

/* 
 * Remove NextGen Gallery Scripts from all but the pages where they exist
 * You can remove these three functions if not using a next gen gallery
 * or something that adds scripts to each page where they are not needed
 */
function wpse_82982_removeScripts() {
    wp_dequeue_script('ngg-slideshow');
    wp_dequeue_script('shutter');
}

function wpse_82982_removeStyles() {
    wp_dequeue_style('NextGEN');
    wp_dequeue_style('shutter');
}

function removeNextGenScripts() {
  add_action('wp_print_scripts', 'wpse_82982_removeScripts');
  add_action('wp_print_styles', 'wpse_82982_removeStyles');
};
	
// Related Posts Function (call using dalgo_related_posts(); )
function dalgo_related_posts() {
	echo '<ul id="dalgo-related-posts">';
	global $post;
	$tags = wp_get_post_tags($post->ID);
	if($tags) {
		foreach($tags as $tag) { $tag_arr .= $tag->slug . ','; }
        $args = array(
        	'tag' => $tag_arr,
        	'numberposts' => 5, /* you can change this to show more */
        	'post__not_in' => array($post->ID)
     	);
        $related_posts = get_posts($args);
        if($related_posts) {
        	foreach ($related_posts as $post) : setup_postdata($post); ?>
	           	<li class="related_post"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
	        <?php endforeach; } 
	    else { ?>
            <li class="no_related_post">No Related Posts Yet!</li>
		<?php }
	}
	wp_reset_query();
	echo '</ul>';
}

// Numeric Page Navi (built into the theme by default)
function page_navi($before = '', $after = '') {
	global $wpdb, $wp_query;
	$request = $wp_query->request;
	$posts_per_page = intval(get_query_var('posts_per_page'));
	$paged = intval(get_query_var('paged'));
	$numposts = $wp_query->found_posts;
	$max_page = $wp_query->max_num_pages;
	if ( $numposts <= $posts_per_page ) { return; }
	if(empty($paged) || $paged == 0) {
		$paged = 1;
	}
	$pages_to_show = 7;
	$pages_to_show_minus_1 = $pages_to_show-1;
	$half_page_start = floor($pages_to_show_minus_1/2);
	$half_page_end = ceil($pages_to_show_minus_1/2);
	$start_page = $paged - $half_page_start;
	if($start_page <= 0) {
		$start_page = 1;
	}
	$end_page = $paged + $half_page_end;
	if(($end_page - $start_page) != $pages_to_show_minus_1) {
		$end_page = $start_page + $pages_to_show_minus_1;
	}
	if($end_page > $max_page) {
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = $max_page;
	}
	if($start_page <= 0) {
		$start_page = 1;
	}
	echo $before.'<nav class="page-navigation"><ol class="dalgo_page_navi clearfix">'."";
	if ($start_page >= 2 && $pages_to_show < $max_page) {
		$first_page_text = "First";
		echo '<li class="bpn-first-page-link"><a href="'.get_pagenum_link().'" title="'.$first_page_text.'">'.$first_page_text.'</a></li>';
	}
	echo '<li class="bpn-prev-link">';
	previous_posts_link('<<');
	echo '</li>';
	for($i = $start_page; $i  <= $end_page; $i++) {
		if($i == $paged) {
			echo '<li class="bpn-current">'.$i.'</li>';
		} else {
			echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
		}
	}
	echo '<li class="bpn-next-link">';
	next_posts_link('>>');
	echo '</li>';
	if ($end_page < $max_page) {
		$last_page_text = "Last";
		echo '<li class="bpn-last-page-link"><a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'">'.$last_page_text.'</a></li>';
	}
	echo '</ol></nav>'.$after."";
}
	
// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

add_filter('the_content', 'filter_ptags_on_images');

?>
