<?php get_header(); ?>

  <!--Start of .main-container -->
  <div class="main-container">

    <h1 class="search-result-title">Search Results for: <span class="detail"><?php echo esc_attr(get_search_query()); ?></span></h1>

    <div class="search-container">
      <?php get_search_form(); ?>
    </div>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
      <article id="post-<?php the_ID(); ?>" role="article" class="search-result">
        
        <h3 class="result-title">
          <a href="<?php the_permalink() ?>"
            rel="bookmark"
            title="<?php the_title_attribute(); ?>"
          ><?php the_title(); ?></a>
        </h3>

        <?php the_excerpt(); ?>
        <a href="<?php the_permalink() ?>"><?php the_permalink() ?></a>

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
            
      <!-- this area shows up if there are no results -->
      
      <article id="post-not-found">
          <header>
            <h1>No Results Found</h1>
          </header>
          <section class="post-content">
            <p>Sorry, but the requested resource was not found on this site.</p>
          </section>
          <footer>
          </footer>
      </article>
    
    <?php endif; ?>

  </div>
  <!-- end of .main-container -->
              
<?php get_footer(); ?>
