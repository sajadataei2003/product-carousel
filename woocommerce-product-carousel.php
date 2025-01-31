<?php
/**
 * Plugin Name: WooCommerce Product Carousel
 * Description: A simple product carousel for WooCommerce.
 * Version: 1.0
 * Author: Sajjad Ataei
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Enqueue scripts and styles
function wpc_enqueue_scripts() {
    wp_enqueue_style( 'slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css' );
    wp_enqueue_style( 'slick-theme-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css' );
    wp_enqueue_script( 'slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery'), null, true );
    wp_enqueue_script( 'wpc-script', plugin_dir_url( __FILE__ ) . 'js/script.js', array('jquery', 'slick-js'), null, true );
}
add_action( 'wp_enqueue_scripts', 'wpc_enqueue_scripts' );

// Shortcode to display the product carousel
function wpc_product_carousel( $atts ) {
    $atts = shortcode_atts( array(
        'posts_per_page' => 5,
    ), $atts, 'product_carousel' );

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['posts_per_page'],
    );

    $loop = new WP_Query( $args );

    if ( $loop->have_posts() ) {
        ob_start();
        ?>
        <div class="product-carousel">
            <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                <div class="product-item">
                    <a href="<?php the_permalink(); ?>">
                        <?php if ( has_post_thumbnail() ) {
                            the_post_thumbnail('medium');
                        } ?>
                        <h2><?php the_title(); ?></h2>
                        <span><?php echo $product->get_price_html(); ?></span>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }

    return '<p>محصولی یافت نشد!</p>';
}
add_shortcode( 'product_carousel', 'wpc_product_carousel' );

