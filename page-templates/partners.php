<?php
/**
 * Template Name: Partner Restaurants
 * @package Nutrifrais
 */
get_header(); ?>

<section class="card" style="margin:20px 0;">
  <div class="pad">
    <h1><?php esc_html_e( 'Partner Restaurants', 'nutrifrais' ); ?></h1>
    <p><?php esc_html_e( 'Discover our trusted partners crafting bio, local meals.', 'nutrifrais' ); ?></p>
  </div>
</section>

<div class="grid grid-3">
  <?php for ( $i=1; $i<=6; $i++ ) : ?>
    <div class="card"><div class="pad">
      <h3><?php printf( esc_html__( 'Restaurant %d', 'nutrifrais' ), $i ); ?></h3>
      <p><?php esc_html_e( 'Organic-focused kitchen with seasonal menus.', 'nutrifrais' ); ?></p>
      <p class="badge"><?php esc_html_e( 'Bio & Local', 'nutrifrais' ); ?></p>
    </div></div>
  <?php endfor; ?>
</div>

<?php get_footer();

