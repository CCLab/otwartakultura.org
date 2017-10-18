<?php

function theme_slug_setup() {
   add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'theme_slug_setup' );

function pok_enqueue_style() {
	wp_enqueue_style( 'core', get_template_directory_uri() . '/style.less', false );
}

function pok_enqueue_script() {
    wp_enqueue_script( 'banner', get_template_directory_uri() . '/banner.js', false );
    wp_enqueue_script( 'jquery-cycle', get_template_directory_uri() . '/jquery.cycle2.min.js', false );
}

add_action( 'wp_enqueue_scripts', 'pok_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'pok_enqueue_script' );


function pok_register_menus() {
  register_nav_menus(array(
    'top-menu' => 'Top',
    'front-options' => 'Front'
  ));
}

add_action( 'init', 'pok_register_menus' );



function add_description_to_menu($item_output, $item, $depth, $args) {
    if (strlen($item->description) > 0 ) {
        // append description after link
        //$item_output .= sprintf('<span class="description">%s</span>', esc_html($item->description));

        // insert description as last item *in* link ($input_output ends with "</a>{$args->after}")
        $item_output = substr($item_output, 0, -strlen("</a>{$args->after}")) . sprintf('<span class="description">%s</span >', esc_html($item->description)) . "</a>{$args->after}";
    }

    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'add_description_to_menu', 10, 4);


add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size(740, 740, false);

add_image_size('th', 600, 10000);

function pok_widgets_init() {
    register_sidebar(array(
        'name' => 'Stopka',
        'id' => 'footer'
    ));
}
add_action( 'widgets_init', 'pok_widgets_init' );


function pok_mime_types($mime_types=array()) {
    $mime_types['svg'] = 'image/svg+xml';
    return $mime_types;
}
add_filter('upload_mimes', 'pok_mime_types', 1, 1);


function my_og_image_init($images)
{
    if ( is_front_page() || is_home() ) {
        $images[] = 'https://otwartakultura.org/wp-content/themes/pok/screenshot.png';
    }
    return $images;
}
add_filter('og_image_init', 'my_og_image_init');



function colorized_image_url($image_url, $color) {
    if (!$image_url) return "none";
    list($r, $g, $b) = sscanf($color, "#%2x%2x%2x");
    if ($r == 255 && $g == 255 && $b == 255)
        return $image_url;

    $upload_dir = wp_upload_dir();
    $baseurl = $upload_dir['baseurl'];
    $basedir = $upload_dir['basedir'];

    $dot = strrpos($image_url, '.');
    $ext = substr($image_url, $dot + 1);

    $out_url = substr($image_url, 0, $dot) . ".colorized-" . substr($color, 1) . "." . $ext;
    $outfile = $basedir . substr($out_url, strlen($baseurl));
    $infile = $basedir . substr($image_url, strlen($baseurl));

    if (!file_exists($outfile) || filemtime($outfile) < filemtime($infile)) {
        $extl = strtolower($ext);
        switch ($extl) {
            case 'jpg':
            case 'jpeg':
                $img = imagecreatefromjpeg($infile);
                break;
            case 'png':
                $img = imagecreatefrompng($infile);
                break;
            case 'gif':
                $img = imagecreatefromgif($infile);
                break;
            default:
                return $image_url;
        }

        imagefilter($img, IMG_FILTER_GRAYSCALE);
        imagefilter($img, IMG_FILTER_NEGATE);
        imagefilter($img, IMG_FILTER_COLORIZE, 255 - $r, 255 - $g, 255 - $b);
        imagefilter($img, IMG_FILTER_NEGATE);

        switch ($extl) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($img, $outfile);
                break;
            case 'png':
                imagepng($img, $outfile);
                break;
            case 'gif':
                imagegif($img, $outfile);
        }
    }

    // And is newer

    return $out_url;

}


function post_box($post, $color) {
    ?><div class="postbox"><a href="<?php echo get_permalink($post); ?>">
		<div class="thumbnail"<?php
            if (has_post_thumbnail($post)) {
                $thurl = get_the_post_thumbnail_url($post, 'th');
                if ($color) {
                    $thurl = colorized_image_url($thurl, $color);
                }
            ?> style="background-image: url('<?php echo $thurl ?>');"<?php
            }
        ?>>
        <?php if (has_post_thumbnail($post) && $color) {
        		?><div class="overlay" style="background-image: url('<?php echo get_the_post_thumbnail_url($post, 'th'); ?>');"></div><?php
        } ?>
        <?php 
        $icon = get_field('icon', $post->ID);
        if ($icon) {
        		?>
				<img class="icon" src="<?php echo get_template_directory_uri() . "/img/ikony/${icon}.svg"; ?>">
        		<?php
        }
        ?>
		</div>
		<h3><?php echo get_the_title($post); ?></h3>
        <?php echo apply_filters('the_excerpt', wp_trim_words($post->post_content, 25, '')); ?>
        <div class="read-more">czytaj więcej ></div>

	</a></div><?php
}


add_filter( 'embed_defaults', 'modify_embed_defaults' );
function modify_embed_defaults() {
    return array(
        'width'  => 740,
        'height' => 416
    );
}


function posts_on_homepage( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'posts_per_page', 3 );
    }
}
add_action( 'pre_get_posts', 'posts_on_homepage' );


?>
