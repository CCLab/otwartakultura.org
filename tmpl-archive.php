<?php
/*
Template Name: Archiwum aktualności
*/
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

    // set up our archive arguments
    $archive_args = array(
      post_type => 'post',    // get only posts
      'posts_per_page'=> -1   // this will display all posts on one page
    );

    // new instance of WP_Quert
    $archive_query = new WP_Query( $archive_args );


while ( $archive_query->have_posts() ) {
	$archive_query->the_post();
	?>
	<article<?php
		if (has_post_thumbnail()) {
			?> style="background-image: url('<?php echo the_post_thumbnail_url('large'); ?>');"<?php
		} ?>>
	<header>
	<a href="<?php echo get_permalink() ?>">
        <h1><?php echo get_the_title(); ?></h1>
        <span class="timestamp"><?php the_date() ?></span>
		<span class="read-more">czytaj więcej ></span>
        </a></header>
	</article>
    <?php
    $first = false;
}

wp_reset_postdata();

?>
</div>




<?php get_footer(); ?>
