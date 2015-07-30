<?php

// Adding Translation Option
load_theme_textdomain( 'default', TEMPLATEPATH.'/languages' );
$locale = get_locale();
$locale_file = TEMPLATEPATH."/languages/$locale.php";
if ( is_readable($locale_file) ) require_once($locale_file);

// Cleaning up the Wordpress Head
function custom_head_cleanup() {
  remove_action( 'wp_head', 'rsd_link' );
  remove_action( 'wp_head', 'wlwmanifest_link' );
  remove_action( 'wp_head', 'index_rel_link' );
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
  remove_action( 'wp_head', 'wp_generator' );
}

add_action('init', 'custom_head_cleanup');

// remove WP version from RSS
function custom_rss_version() { return ''; }
add_filter('the_generator', 'custom_rss_version');

// This removes the annoying […] to a Read More link
function custom_excerpt_more($more) {
  global $post;
  return '...  <a href="'. get_permalink($post->ID) . '" title="Read '.get_the_title($post->ID).'">Read more &raquo;</a>';
}
add_filter('excerpt_more', 'custom_excerpt_more');

// Adding WP 3+ Functions & Theme Support
function custom_theme_support() {
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
      'footer_links' => 'Footer Links' // secondary nav in footer
    )
  ); 
}

// launching this stuff after theme setup
add_action('after_setup_theme','custom_theme_support');  
// adding sidebars to Wordpress (these are created in functions.php)
add_action( 'widgets_init', 'custom_register_sidebars' );

class custom_walker extends Walker_Nav_Menu {

  function start_el(&$output, $item, $depth, $args) {
    global $wp_query;
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
    $class_names = $value = '';
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
    $class_names = ' class="' . esc_attr( $class_names ) . '"';
    $output .= $indent . '<li data-post="' . $post->ID .'" id="'. $item->attr_title .'-tab"'. $class_names .'>';

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

// this is the fallback for header menu
function custom_main_nav_fallback() { 
  wp_page_menu( 'show_home=Home&menu_class=menu' ); 
}

function custom_main_nav() {
  // display the wp3 menu if available
  $walker = new custom_walker;

  wp_nav_menu(array( 
    'menu' => 'main_nav', /* menu name */
    'theme_location' => 'main_nav', /* where in the theme it's assigned */
    'container' => false,
    'fallback_cb' => 'custom_main_nav_fallback', /* menu fallback */
    'items_wrap' => '<ul class="main_nav">%3$s</ul>',
    'walker' => $walker /* customizes the output of the menu */
  ));
}

// this is the fallback for footer menu
function custom_footer_links_fallback() { 
  /* you can put a default here if you like */ 
}

function custom_footer_links() { 
  // display the wp3 menu if available
  $walkerF = new custom_walker;

  wp_nav_menu(array(
    'menu' => 'footer_links',
    'theme_location' => 'footer_links',
    'container_id'    => 'foot-links',
    'container_class' => '',
    'walker' => $walkerF,
    'fallback_cb' => 'custom_footer_links_fallback'
  ));
}


/****************** Scripts & Styles **************************/

function load_style() {
  wp_register_style( 'base', get_template_directory_uri() . '/library/build/style-base.css', array(), '2011-11-04T15:38', 'all' );
  wp_register_style( 'non-mobile', get_template_directory_uri() . '/library/build/style.css', array(), '2011-11-04T15:38', '(min-width:481px)' );

  wp_enqueue_style( 'base' );
  wp_enqueue_style( 'non-mobile' );
}

function load_scripts() {
  wp_register_script( 'main-js', get_template_directory_uri() . '/library/build/main.js', array(), '2012-02-15-1537', true );
  wp_register_script( 'google-analytics', get_template_directory_uri() . '/library/js/vendor/google-analytics.js', array(), '2012-02-15-1537', true );

  wp_enqueue_script( 'main-js' );
  wp_enqueue_script( 'google-analytics' );
}


add_action( 'wp_enqueue_scripts', 'load_style', 1);
add_action( 'wp_enqueue_scripts', 'load_scripts', 2);

/****************** PLUGINS & EXTRA FEATURES **************************/

// Related Posts Function (call using custom_related_posts(); )
function custom_related_posts() {
  echo '<ul id="custom-related-posts">';
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
  echo $before.'<nav class="page-navigation"><ol class="custom_page_navi clearfix">'."";
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
