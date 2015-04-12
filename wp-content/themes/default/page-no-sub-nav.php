<?php
/*
Template Name: Page No Sub Nav
*/
?>

<?php get_header(); ?>

  <!--Start of main -->
	<div class="main-container">
  
  	<div class="page-title">
   	  <h2><?php the_title(); ?></h2>
   	  <span class="border left-top"></span>
   	  <span class="border left-bottom"></span>
   	  <span class="border right-top"></span>
   	  <span class="border right-bottom"></span>
   	</div>

	<div class="sub-page-content">

      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    	<div class="content-col-full">
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
		<!-- end of .content-col -->

	  </div>
	  <!-- end of .sub-page-content -->

	</div>
  <!-- end of .main-container -->
							
<?php get_footer(); ?>
