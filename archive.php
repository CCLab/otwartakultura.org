<?php
get_header(); ?>


<div class="jumbotron archive">

<div class="jumbo">
<h1>Archiwum aktualności</h1>
<div class="breadcrumbs"><?php bcn_display() ?></div>
        <div class="organization">
                <a href="https://centrumcyfrowe.pl" target="_blank" id="cclogo"><img src="<?php echo get_template_directory_uri() ?>/img/CentrumCyfrowe.svg" alt="Centrum Cyfrowe" title="Centrum Cyfrowe"></a>
                <div class="subtitle">Pracownię Otwierania Kultury<br>prowadzi Centrum Cyfrowe</div>
        </div>
</div>

</div>



<div id="news">
<?php
while ( have_posts() ) {
	the_post();
	?>
	<article<?php
		if (has_post_thumbnail()) {
			?> style="background-image: url('<?php echo the_post_thumbnail_url('large'); ?>');"<?php
		} ?>>
	<a href="<?php echo get_permalink() ?>">
	<header>
        <div class="header-inner">
	<div class="title-box">
        <h1><?php echo get_the_title(); ?></h1>
        <span class="timestamp"><?php the_date() ?></span>
		<span class="read-more">czytaj więcej ></span>
        </div></div></header>
	</a>
	</article>
	<?php
        $first = false;
    } ?>
</div>




<?php get_footer(); ?>
