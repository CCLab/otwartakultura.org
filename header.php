<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <?php wp_head(); ?>
    </head>
<body>
<div id="body">

<header id="main">




<svg id="svg"></svg><script type="text/javascript">
   header_generator()
</script>
<a href="/"><img id="identification" src="<?php echo get_template_directory_uri(); ?>/img/identification.svg" alt="Pracownia Otwierania Kultury"></a>

<a id="menu-toggle"><img src="<?php echo get_template_directory_uri(); ?>/img/menu.svg" alt="Menu"></a>
<?php wp_nav_menu( array( 'theme_location' => 'top-menu' ) ); ?>

</header>

