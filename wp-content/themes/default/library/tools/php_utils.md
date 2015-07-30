Sample Wordpress PHP Utilities

#Check if we're on the front page
  <?php if(is_front_page()) { ?><?php } ?>

#Check if a sidebar widget is registered and display it
  <?php if ( is_active_sidebar( 'hp-slideshow' )) : ?>
    <?php dynamic_sidebar( 'hp-slideshow' ); ?>
  <?php endif; ?>

#Check if page is part of the current page directory
  <?php if ( is_tree( '109' ) ) { ?><?php } ?>

#Check on page meta data
  <?php $intro = get_post_meta($post->ID, 'intro', true); if($intro) { ?>
    <?php echo $intro; ?> 
  <?php } ?>

#Check for thumbnail image
  <?php if ( has_post_thumbnail() ) { ?><?php the_post_thumbnail();?><?php } ?>

#Use title as class name
  <?php echo strtolower(str_replace(' ', '', wp_title('', false))); ?>

#Get the search form
  <?php get_search_form(); ?>

#Get the page title
  <?php the_title(); ?>

#Remove scripts and add them back for only pages that need them
  <?php
    // if we aren't on a page that has a gallery, remove the scripts
    $nextGenGallery = get_post_meta($post->ID, 'image-gallery', true);
    if ( !$nextGenGallery ) { removeNextGenScripts(); } 
  ?>

#Show child pages for the current page (add to page.php)
<?php 
  // determine parent of current page
  if ($post->post_parent) {
    $ancestors = get_post_ancestors($post->ID);
    $parent = $ancestors[count($ancestors) - 1];
    $section_title = get_the_title( $post->post_parent );
    $section_parent = false;
  } else {
    $parent = $post->ID;
    $section_title = get_the_title( $post->ID );
    $section_parent = true;
  }

  $children = wp_list_pages("title_li=&child_of=" . $parent . "&echo=0");
?>

#Get posts per category
  <?php 
    $posts = get_posts('category=5&numberposts=10'); 
    foreach($posts as $post) {

    $intro = get_post_meta($post->ID, 'link', true);

    if($intro) {
        $link = $intro;
    } else {
        $link = get_permalink();
    }

?>
  
  <a href="<?php echo $link; ?>" target="_parent">
    <?php the_time('n') ?>.<?php the_time('j') ?> | <?php the_title(); ?></a>

<?php } ?>