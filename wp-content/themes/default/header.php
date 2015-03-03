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
        <?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); ?>
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

    <!-- CSS -->
    <link
      rel="stylesheet"
      media="all"
      href="<?php echo get_template_directory_uri(); ?>/library/build/style-base.css"
    />

    <link
      rel="stylesheet"
      media="(min-width:481px)"
      href="<?php echo get_template_directory_uri(); ?>/library/build/style.css"
    />
    
    <!-- wordpress head functions -->
    <?php wp_head(); ?>
    <!-- end of wordpress head -->
    
  </head>
    
    <body <?php body_class(); ?>>

    <div class="wrapper">
    
      <h1 class="site-title"><?php bloginfo('name'); ?></h1>
         
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
            <!--Start of wordpres main navigation -->
            <?php dalgo_main_nav(); ?>
            <!--End of wordpress main navigation -->
        </nav>
      </header>
