<?php get_header(); ?>

<!--Start of main -->
<div class="main-container">

  <?php if (is_category()) { ?>
    <h1 class="archive_title">
      <span><?php _e("", "jordalgo"); ?></span> <span class="detail"><?php single_cat_title(); ?></span>
    </h1>
  <?php } elseif (is_tag()) { ?> 
    <h1 class="archive_title">
      <span><?php _e("Posts Tagged :", "jordalgo"); ?></span> <span class="detail"><?php single_tag_title(); ?></span>
    </h1>
  <?php } elseif (is_author()) { ?>
    <h1 class="archive_title">
      <span><?php _e("Posts By :", "jordalgo"); ?></span> <span class="detail"><?php get_the_author_meta('display_name'); ?></span>
    </h1>
  <?php } elseif (is_day()) { ?>
    <h1 class="archive_title">
      <span><?php _e("Daily Archives :", "jordalgo"); ?></span> <span class="detail"><?php the_time('l, F j, Y'); ?></span>
    </h1>
  <?php } elseif (is_month()) { ?>
      <h1 class="archive_title">
        <span><?php _e("Monthly Archives :", "jordalgo"); ?>:</span> <span class="detail"><?php the_time('F Y'); ?></span>
      </h1>
  <?php } elseif (is_year()) { ?>
      <h1 class="archive_title">
        <span><?php _e("Yearly Archives :", "jordalgo"); ?>:</span> <span class="detail"><?php the_time('Y'); ?></span>
      </h1>
  <?php } ?>

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
    <article
      id="post-<?php the_ID(); ?>" <?php post_class('search-result'); ?>
      role="article"
    >
    
      <h3 class="result-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

    <div class="date"><?php echo the_time('F jS, Y'); ?></div>
      <?php the_excerpt(); ?>

    </article>
  
  <?php endwhile; ?>  
  
  <?php if (function_exists('page_navi')) { // if expirimental feature is active ?>
    
    <?php page_navi(); // use the page navi function ?>

  <?php } else { // if it is disabled, display regular wp prev & next links ?>
    <nav class="wp-prev-next">
      <ul class="clearfix">
        <li class="prev-link"><?php next_posts_link(_e('&laquo; Older Entries', "jordalgo")) ?></li>
        <li class="next-link"><?php previous_posts_link(_e('Newer Entries &raquo;', "jordalgo")) ?></li>
      </ul>
    </nav>
  <?php } ?>
        
  
  <?php else : ?>
  
    <article id="post-not-found">
        <header>
          <h1><?php _e("No Posts Yet", "jordalgo"); ?></h1>
        </header>
        <section class="post-content">
          <p><?php _e("Sorry, What you were looking for is not here.", "jordalgo"); ?></p>
        </section>
        <footer>
        </footer>
    </article>
  
  <?php endif; ?>

</div>
<!-- end of .main-container -->

<?php get_footer(); ?>