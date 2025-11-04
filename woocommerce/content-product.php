<?php
/**
 * Product content within loops - customized card
 * @package Nutrifrais
 */
defined( 'ABSPATH' ) || exit;

global $product;
if ( empty( $product ) || ! $product->is_visible() ) { return; }

?>
<li <?php wc_product_class( 'product group transition-all hover:shadow-lg', $product ); ?> style="list-style:none;">
  <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link block">
    <?php echo woocommerce_get_product_thumbnail( 'nf-card' ); ?>
    <h2 class="woocommerce-loop-product__title px-3 pt-2 text-slate-800 group-hover:text-[var(--nf-green,#2dbf7a)] transition-colors"><?php the_title(); ?></h2>
  </a>
  <div class="pad">
    <div class="price font-bold text-[var(--nf-green,#2dbf7a)]"><?php echo $product->get_price_html(); ?></div>
    <?php woocommerce_template_loop_rating(); ?>
    <div class="nf-meta" style="font-size:12px;color:#6b7a88;display:flex;gap:10px;flex-wrap:wrap;">
      <?php
        $diet = get_the_terms( get_the_ID(), 'nf_diet' );
        $type = get_the_terms( get_the_ID(), 'nf_meal_type' );
        $kcal = get_post_meta( get_the_ID(), '_nf_calories', true );
        if ( $diet && ! is_wp_error( $diet ) ) {
          echo '<span class="badge">' . esc_html( $diet[0]->name ) . '</span>';
        }
        if ( $type && ! is_wp_error( $type ) ) {
          echo '<span class="badge">' . esc_html( $type[0]->name ) . '</span>';
        }
        if ( $kcal ) {
          echo '<span class="badge">' . esc_html( $kcal ) . ' kcal</span>';
        }
      ?>
    </div>
    <div class="flex gap-2 mt-2">
      <button type="button" class="btn btn-secondary nf-quick-view" data-product-id="<?php echo esc_attr( get_the_ID() ); ?>"><?php esc_html_e('Quick View','nutrifrais'); ?></button>
    </div>
    <?php woocommerce_template_loop_add_to_cart(); ?>
  </div>
</li>
