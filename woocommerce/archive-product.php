<?php
/**
 * WooCommerce Product Archive
 * @package Nutrifrais
 * Template override
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<header class="hero card" style="margin:20px 0;">
  <div class="pad">
    <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
    <p><?php esc_html_e( 'Filter by diet, meal type, and calories.', 'nutrifrais' ); ?></p>
  </div>
</header>

<?php echo do_shortcode('[nutrifrais_filters]'); ?>

<?php
if ( woocommerce_product_loop() ) {
  do_action( 'woocommerce_before_shop_loop' );
  woocommerce_product_loop_start();
  if ( wc_get_loop_prop( 'total' ) ) {
    while ( have_posts() ) {
      the_post();
      do_action( 'woocommerce_shop_loop' );
      wc_get_template_part( 'content', 'product' );
    }
  }
  woocommerce_product_loop_end();
  do_action( 'woocommerce_after_shop_loop' );
} else {
  do_action( 'woocommerce_no_products_found' );
}

<?php get_footer( 'shop' );

