<?php
/**
 * 404 template
 * @package Nutrifrais
 */
get_header(); ?>

<section class="card" style="margin:40px 0;">
  <div class="pad">
    <h1><?php esc_html_e( 'Page not found', 'nutrifrais' ); ?></h1>
    <p><?php esc_html_e( 'We couldn’t find what you’re looking for. Try searching or go back to the homepage.', 'nutrifrais' ); ?></p>
    <?php get_search_form(); ?>
    <p><a class="btn btn-primary" href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e( 'Go Home', 'nutrifrais' ); ?></a></p>
  </div>
  </section>

<?php get_footer();

