<?php
/**
 * Template Name: Calorie & Macros Calculator
 * @package Nutrifrais
 */
get_header(); ?>

<section class="card" style="margin:20px 0;">
  <div class="pad">
    <h1><?php esc_html_e( 'Calorie & Macros Calculator', 'nutrifrais' ); ?></h1>
    <p><?php esc_html_e( 'Enter your details to get a daily target and recommended meals tailored to you.', 'nutrifrais' ); ?></p>
  </div>
  <div class="pad">
    <?php echo do_shortcode('[nutrifrais_calculator]'); ?>
  </div>
  <div class="pad">
    <a class="btn btn-secondary" href="<?php echo esc_url( site_url('/plans') ); ?>"><?php esc_html_e( 'Go to Plans', 'nutrifrais' ); ?></a>
  </div>
</section>

<?php get_footer();

