<?php
/**
 * Cart template wrapper (loads Woo default inside theme container)
 * @package Nutrifrais
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<section class="card" style="margin:20px 0;">
  <div class="pad">
    <h1><?php esc_html_e( 'Your Cart', 'nutrifrais' ); ?></h1>
  </div>
</section>

<div class="card"><div class="pad">
<?php
// Load the plugin default cart template to ensure full functionality
if ( function_exists( 'WC' ) ) {
    wc_get_template( 'cart/cart.php', [], '', WC()->plugin_path() . '/templates/' );
}
?>
</div></div>

<?php get_footer( 'shop' );

