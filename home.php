<?php error_reporting(E_ALL); ini_set('display_errors', 1); ?>
<?php get_header() ?>



<div class="jumbotron front">

<div class="jumbo">
<h1><?php bloginfo('description'); ?></h1>
        <div class="organization">
                <a href="https://centrumcyfrowe.pl" target="_blank" id="cclogo"><img src="<?php echo get_template_directory_uri() ?>/img/CentrumCyfrowe.svg" alt="Centrum Cyfrowe" title="Centrum Cyfrowe"></a>
                <div class="subtitle">Pracownię Otwierania Kultury<br>prowadzi Centrum Cyfrowe</div>
        </div>
</div>


<?php wp_nav_menu( array( 'theme_location' => 'front-options', 'menu_class' => 'front-menu', 'link_before'=>'<span class="title">', 'link_after'=>'</span>' ) ); ?>

</div>




<?php
$first = true;

if ( have_posts() ) {
    ?><div id="promobox"><div id="news" class="cycle-slideshow"
        data-cycle-slides="> article"
        data-cycle-pager-template="<span></span>"
    >
    <div class="cycle-pager"></div>
    <?php
    while ( have_posts() ) {
        the_post();
/*        if ($first) {
        	$archive_link = get_month_link(get_the_time('Y'), get_the_time('m'));
        }*/
	?>
	<article<?php
		if (has_post_thumbnail()) {
			?> style="background-image: url('<?php echo the_post_thumbnail_url('full'); ?>');"<?php
		} ?>>
	<header>
	<a href="<?php echo get_permalink() ?>">
        	<h1><?php echo get_the_title(); ?></h1>
		<span class="read-more">czytaj więcej ></span>
        </a></header>
	</article>
	<?php
        $first = false;
    } ?>
    </div>
    <a id="archive" href="/archiwum/">archiwum aktualności</a>
    </div>
<?php
}
?>




<?php get_footer() ?>
