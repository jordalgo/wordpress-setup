<?php get_header(); ?>

<!--Start of .main-container -->
<div class="main-container">

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
            
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
<!-- end of .main-container -->
							
<?php get_footer(); ?>
