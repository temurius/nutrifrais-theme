<?php
/**
 * Template Name: Plans (3/7/14 Days)
 * @package Nutrifrais
 */
get_header(); ?>

<section class="card" style="margin:20px 0;">
  <div class="pad">
    <h1><?php esc_html_e( 'Healthy Meal Plans', 'nutrifrais' ); ?></h1>
    <p><?php esc_html_e( 'Choose from 3, 7, or 14-day plans. Each plan includes curated organic meals. Subscriptions optional.', 'nutrifrais' ); ?></p>
  </div>
</section>

<div class="grid grid-3">
  <div class="card">
    <div class="pad">
      <h2>3 <?php esc_html_e( 'Days', 'nutrifrais' ); ?></h2>
      <p><?php esc_html_e( 'Quick reset with bio meals.', 'nutrifrais' ); ?></p>
      <ul><li><?php esc_html_e( '6 meals', 'nutrifrais' ); ?></li><li><?php esc_html_e( 'AI kickoff consult', 'nutrifrais' ); ?></li></ul>
      <a class="btn btn-primary" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Start 3-Day Plan', 'nutrifrais' ); ?></a>
    </div>
  </div>
  <div class="card">
    <div class="pad">
      <h2>7 <?php esc_html_e( 'Days', 'nutrifrais' ); ?></h2>
      <p><?php esc_html_e( 'One-week mindful eating program.', 'nutrifrais' ); ?></p>
      <ul><li><?php esc_html_e( '14 meals', 'nutrifrais' ); ?></li><li><?php esc_html_e( 'AI weekly adjust', 'nutrifrais' ); ?></li></ul>
      <a class="btn btn-primary" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Start 7-Day Plan', 'nutrifrais' ); ?></a>
    </div>
  </div>
  <div class="card">
    <div class="pad">
      <h2>14 <?php esc_html_e( 'Days', 'nutrifrais' ); ?></h2>
      <p><?php esc_html_e( 'Deeper change with full guidance.', 'nutrifrais' ); ?></p>
      <ul><li><?php esc_html_e( '28 meals', 'nutrifrais' ); ?></li><li><?php esc_html_e( 'AI bi-weekly refine', 'nutrifrais' ); ?></li></ul>
      <a class="btn btn-primary" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Start 14-Day Plan', 'nutrifrais' ); ?></a>
    </div>
  </div>
</div>

<section style="margin:20px 0;">
  <h2><?php esc_html_e( 'Popular Meals', 'nutrifrais' ); ?></h2>
  <?php echo do_shortcode('[products limit="6" columns="3" category="meals"]'); ?>
  <p><a class="btn btn-secondary" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse All Meals', 'nutrifrais' ); ?></a></p>
  </section>

<?php get_footer();

