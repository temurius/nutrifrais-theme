<?php
/**
 * Front Page Template
 * @package Nutrifrais
 */
get_header(); ?>

<section class="hero card" style="margin:20px 0;">
  <div class="pad">
    <span class="badge"><?php esc_html_e( 'Bio & Local', 'nutrifrais' ); ?></span>
    <h1><?php esc_html_e( 'Healthy meals and AI nutrition — personalized for you', 'nutrifrais' ); ?></h1>
    <p><?php esc_html_e( 'Discover organic menus crafted by partner restaurants and tailored by our AI assistant. Build your 3, 7, or 14 day plan in minutes.', 'nutrifrais' ); ?></p>
    <div class="actions">
      <a class="btn btn-primary" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse Meals', 'nutrifrais' ); ?></a>
      <a class="btn btn-secondary" href="<?php echo esc_url( site_url('/plans') ); ?>"><?php esc_html_e( 'See Plans', 'nutrifrais' ); ?></a>
    </div>
  </div>
</section>

<section style="margin:20px 0;">
  <h2><?php esc_html_e( 'Popular Meals', 'nutrifrais' ); ?></h2>
  <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); ?>
</section>

<section style="margin:20px 0;">
  <h2><?php esc_html_e( 'Build Your Plan', 'nutrifrais' ); ?></h2>
  <div class="grid grid-3">
    <div class="card"><div class="pad"><h3>3 <?php esc_html_e( 'Days', 'nutrifrais' ); ?></h3><p><?php esc_html_e( 'Quick reset with balanced meals.', 'nutrifrais' ); ?></p><a class="btn btn-primary" href="<?php echo esc_url( site_url('/plans') ); ?>"><?php esc_html_e( 'Choose', 'nutrifrais' ); ?></a></div></div>
    <div class="card"><div class="pad"><h3>7 <?php esc_html_e( 'Days', 'nutrifrais' ); ?></h3><p><?php esc_html_e( 'One week of mindful eating.', 'nutrifrais' ); ?></p><a class="btn btn-primary" href="<?php echo esc_url( site_url('/plans') ); ?>"><?php esc_html_e( 'Choose', 'nutrifrais' ); ?></a></div></div>
    <div class="card"><div class="pad"><h3>14 <?php esc_html_e( 'Days', 'nutrifrais' ); ?></h3><p><?php esc_html_e( 'Deeper transformation & habits.', 'nutrifrais' ); ?></p><a class="btn btn-primary" href="<?php echo esc_url( site_url('/plans') ); ?>"><?php esc_html_e( 'Choose', 'nutrifrais' ); ?></a></div></div>
  </div>
</section>

<section style="margin:20px 0;">
  <div class="card"><div class="pad">
    <h2><?php esc_html_e( 'AI Nutrition Assistant', 'nutrifrais' ); ?></h2>
    <p><?php esc_html_e( 'Ask anything about nutrition — we’ll connect your preferences and goals to create smart menus. (Placeholder UI)', 'nutrifrais' ); ?></p>
    <a class="btn btn-secondary" href="<?php echo esc_url( site_url('/ai-nutrition-assistant') ); ?>"><?php esc_html_e( 'Open Assistant', 'nutrifrais' ); ?></a>
  </div></div>
</section>

<?php get_footer();

