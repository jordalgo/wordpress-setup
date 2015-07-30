<?php get_header(); ?>

  <h1 class="archive_title">
    <span><?php _e("Posts By:", "jordalgo"); ?></span> 
    <!-- google+ rel=me function -->
    <?php $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
    $google_profile = get_the_author_meta( 'google_profile', $curauth->ID );
    if ( $google_profile ) {
      echo '<a href="' . esc_url( $google_profile ) . '" rel="me">' . $curauth->display_name . '</a>'; ?></a>
    <?php } else { ?>
    <?php echo get_author_name(get_query_var('author')); ?>
    <?php } ?>
  </h1>

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
    <article id="post-<?php the_ID(); ?>" role="article">
      
      <header>
        
        <h3>
          <a
            href="<?php the_permalink() ?>"
            rel="bookmark"
            title="<?php the_title_attribute(); ?>"
          ><?php the_title(); ?></a>
        </h3>
        
        <p class="meta">
          <?php _e("Posted", "jordalgo"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_time('F jS, Y'); ?></time> <?php _e("by", "jordalgo"); ?> <?php the_author_posts_link(); ?> <span class="amp">&</span> <?php _e("filed under", "jordalgo"); ?> <?php the_category(', '); ?>.
        </p>
      
      </header>
    
      <section class="post-content">
      
        <?php the_post_thumbnail( 'custom-thumb-300' ); ?>
      
        <?php the_excerpt(); ?>
    
      </section>

    </article> <!-- end article -->
            
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
  
<?php get_footer(); ?>