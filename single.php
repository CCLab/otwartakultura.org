<?php error_reporting(E_ALL); ini_set('display_errors', 1); ?>
<?php get_header() ?>
<?php the_post(); ?>
<?php
//$ancestor_url = get_month_link(get_the_time('Y'), get_the_time('m'));
$ancestor_url = "/archiwum/";
?>

<div class="jumbotron">
    <div class="jumbo">
        <h1><?php the_title(); ?></h1>
        <div class="breadcrumbs"><?php bcn_display() ?></div>
        <span class="timestamp"><?php the_date() ?></span>
        <div class="organization">
                <a href="https://centrumcyfrowe.pl" target="_blank" id="cclogo"><img src="<?php echo get_template_directory_uri() ?>/img/CentrumCyfrowe.svg" alt="Centrum Cyfrowe" title="Centrum Cyfrowe"></a>
                <div class="subtitle">Pracownię Otwierania Kultury<br>prowadzi Centrum Cyfrowe</div>
        </div>
    </div>


<?php if (strlen($post->post_content) > 0 || has_post_thumbnail($post)) { ?>
<div class="content-wide">
<div class="content">
    <a class="close" href="<?php echo $ancestor_url; ?>"><img src="<?php echo get_template_directory_uri() ?>/img/X.svg"></a>
    <?php if (has_post_thumbnail($post)) { ?>
        <!--<?php echo get_the_post_thumbnail($post, 'post-thumbnail', $attr=array("class" => "featured")); ?>-->        
        <img class="featured" src="<?php echo get_the_post_thumbnail_url($post, 'post-thumbnail'); ?>">
    <?php } ?>
    <?php the_content() ?>
</div>
</div>
<?php } ?>


<?php
$postboxen = false;
foreach (get_pages(array('parent' => $post->ID)) as $subpage) {
    $ispage = strlen($subpage->post_content) > 0 || has_post_thumbnail($subpage);
    if (!$ispage) {
        $subsubs = get_pages(array('parent' => $subpage->ID));
/*
        if (!count($subsubs)) {
            $ispage = true;
        }
*/
    }

    if ($ispage) {
        if (!$postboxen) {
            $postboxen = true;
            ?><div class="postboxen"><?php
        }
        post_box($subpage);
    } else {
        if ($postboxen) {
            $postboxen = false;
            ?></div><?php
        }
        ?>
        <h2><?php echo $subpage->post_title; ?></h2>
        <div class="postboxen">
            <?php
            foreach ($subsubs as $subsub) {
                post_box($subsub);
            }
            ?>
        </div>
        <?php
    }
}
if ($postboxen) {
    ?></div><?php
}
?>

</div>



<?php get_footer() ?>
