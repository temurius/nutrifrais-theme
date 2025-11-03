<?php
/**
 * Checkout template wrapper
 * @package Nutrifrais
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<section class="card" style="margin:20px 0;">
  <div class="pad">
    <h1><?php esc_html_e( 'Checkout', 'nutrifrais' ); ?></h1>
  </div>
</section>

<div class="card"><div class="pad">
<?php if ( function_exists( 'WC' ) ) { wc_get_template( 'checkout/form-checkout.php', [], '', WC()->plugin_path() . '/templates/' ); } ?>
</div></div>

<?php get_footer( 'shop' );

