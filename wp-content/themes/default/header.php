<!doctype html>  
<!--[if IEMobile 7 ]>
  <html <?php language_attributes(); ?> class="no-js iem7">
<![endif]-->
<!--[if (gte IE 8)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!-->
  <html class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <title>
        <?php bloginfo('name'); ?> | <?php is_front_page() ? bloginfo('description') : wp_title(''); ?>
    </title>
    
    <!-- mobile optimized -->
    <meta name="viewport" content="width=device-width">
    
    <!-- allow pinned sites -->
    <meta name="application-name" content="<?php bloginfo('name'); ?>" />
    
    <!-- icons & favicons -->
    <link
      rel="shortcut icon"
      href="<?php echo get_template_directory_uri(); ?>/library/images/theme/favicon.png"
    >

    <link 
      rel="pingback"
      href="<?php bloginfo('pingback_url'); ?>"
    >

    <!-- Scripts and Style added in extras.php -->

    <!-- wordpress head functions -->
    <?php wp_head(); ?>
    <!-- end of wordpress head -->
    
  </head>
    
  <body <?php body_class(); ?>>

    <header>
      <nav class="access" role="navigation">
          <h3 class="assistive-text">Main Menu</h3>
          <div class="skip-link">
            <a
              class="assistive-text"
              href="#content"
              title="skip content"
            >skip to content</a>
          </div>
          <!--Start of main navigation -->
          <?php custom_main_nav(); ?>
          <!--End of main navigation -->
          <div class="search-container">
            <!--Start of search form -->
            <?php get_search_form(); ?>
            <!--End of search form -->
          </div>
      </nav>
    </header>
