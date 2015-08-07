<?php

// extra functions
require_once('extras.php');

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'custom-thumb-600', 600, 150, true );
add_image_size( 'custom-thumb-300', 300, 100, true );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas (register new widgets here)
function custom_register_sidebars() {
  register_sidebar(array(
    'id' => 'example_id',
    'name' => 'Example Sidebar',
    'description' => '',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<div class="widget-title">',
    'after_title' => '</div>',
  ));
}

/************* COMMENT LAYOUT *********************/

// Comment Layout
function custom_comments($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment; ?>
  <li <?php comment_class(); ?>>
    <article id="comment-<?php comment_ID(); ?>" class="clearfix">
      <header class="comment-author vcard">
        <?php echo get_avatar($comment,$size='32',$default='<path_to_url>' ); ?>
        <?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
        <time><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s'), get_comment_date(),  get_comment_time()) ?></a></time>
        <?php edit_comment_link(__('(Edit)'),'  ','') ?>
      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="help">
          <p><?php _e('Your comment is awaiting moderation.') ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content clearfix">
          <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <!-- </li> is added by wordpress automatically -->
<?php
}

/************* Body Class Appending *****************/

// check the page and page parent
// param {int} page id
function is_tree($pid) {
  global $post;
  if(is_page()&&($post->post_parent==$pid||is_page($pid)))
    return true;
  else
    return false;
};

// add class names to particular pages or directory of pages
function my_class_names($classes) {
  global $post;
  if ( is_tree( '0' ) ) {
    $classes[] = '';
  }

  // add the page title as a class to the body
  $classes[] =  strtolower(str_replace(' ', '', get_the_title($post->ID)));

  return $classes;
}

add_filter('body_class','my_class_names');

/******Site Map Creator*********************/

function custom_create_sitemap() {

  // don't create the sitemap locally
  if (strpos(DB_USER,'root') !== false) {
    return;
  }

$postsForSitemap = get_posts(array('numberposts' => -1,     'orderby' => 'modified',     'post_type'  => array('post','page'),     'order'    => 'DESC'   ));     $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';   $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';     foreach($postsForSitemap as $post) {     setup_postdata($post);         $postdate = explode(" ", $post->post_modified);         $sitemap .= '<url>'.       '<loc>'. get_permalink($post->ID) .'</loc>'.       '<lastmod>'. $postdate[0] .'</lastmod>'.       '<changefreq>monthly</changefreq>'.     '</url>';   }     $sitemap .= '</urlset>';     $fp = fopen(ABSPATH . "sitemap.xml", 'w');   fwrite($fp, $sitemap);   fclose($fp);

}

add_action("publish_post", "custom_create_sitemap"); 
add_action("publish_page", "custom_create_sitemap"); 

?>
