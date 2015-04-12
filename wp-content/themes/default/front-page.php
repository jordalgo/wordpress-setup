<?php
/*
Template Name: Homepage
*/
?>

<?php get_header(); ?>

  <!--Start of main -->
    <div class="main-container front-page">

    <div class="front-page-content">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
        <?php the_content(); ?>
        <div class="cta-box">
          <a href="*" class="cta-button-box left">
            *Powered by Vaults*
            <span class="border left-top"></span>
            <span class="border left-bottom"></span>
            <span class="border right-top"></span>
            <span class="border right-bottom"></span>
          </a>
          <a href="*" class="cta-button-box right">
            Our Timeline &gt;&gt;
            <span class="border left-top"></span>
            <span class="border left-bottom"></span>
            <span class="border right-top"></span>
            <span class="border right-bottom"></span>
          </a>
        </div>
            
    <?php endwhile; ?>          
                        
    <?php else : ?>
                        
        <article id="post-not-found">
        <header>
            <h1>Not Found</h1>
        </header>
        <section class="post-content">
            <p>Sorry, but the requested resource was not found on this site.</p>
        </section>
        </article>
                    
    <?php endif; ?>

    </div>
    <!-- end of .front-page-content -->

    </div>
  <!-- end of .main-container -->
                            
<?php get_footer(); ?>