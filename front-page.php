<?php
/**
 * Front Page Template
 * @package Nutrifrais
 */
get_header(); ?>

<?php
  $slider_sc = get_theme_mod('nf_slider_shortcode');
  if ( $slider_sc ) {
    echo '<section class="mb-6">' . do_shortcode( $slider_sc ) . '</section>';
  }
?>

<section class="hero card mt-5 mb-5">
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

<section class="max-w-screen-xl mx-auto px-4 mb-8">
  <h2 class="text-2xl font-bold mb-3"><?php esc_html_e( 'Find Your Calories & Macros', 'nutrifrais' ); ?></h2>
  <?php echo do_shortcode('[nutrifrais_calculator]'); ?>
  <p class="text-sm text-slate-500 mt-2"><?php esc_html_e( 'Use this to discover recommended daily calories/macros and see meals matched to your target.', 'nutrifrais' ); ?></p>
  <hr class="my-4" />
</section>

<section class="max-w-screen-xl mx-auto px-4 mb-8">
  <h2 class="text-2xl font-bold mb-3"><?php esc_html_e( 'Popular Meals', 'nutrifrais' ); ?></h2>
  <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); ?>
</section>

<section class="max-w-screen-xl mx-auto px-4 mb-8">
  <h2 class="text-2xl font-bold mb-3"><?php esc_html_e( 'Build Your Plan', 'nutrifrais' ); ?></h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="card"><div class="pad"><h3 class="text-xl font-bold">3 <?php esc_html_e( 'Days', 'nutrifrais' ); ?></h3><p><?php esc_html_e( 'Quick reset with balanced meals.', 'nutrifrais' ); ?></p><a class="btn btn-primary" href="<?php echo esc_url( site_url('/plans') ); ?>"><?php esc_html_e( 'Choose', 'nutrifrais' ); ?></a></div></div>
    <div class="card"><div class="pad"><h3 class="text-xl font-bold">7 <?php esc_html_e( 'Days', 'nutrifrais' ); ?></h3><p><?php esc_html_e( 'One week of mindful eating.', 'nutrifrais' ); ?></p><a class="btn btn-primary" href="<?php echo esc_url( site_url('/plans') ); ?>"><?php esc_html_e( 'Choose', 'nutrifrais' ); ?></a></div></div>
    <div class="card"><div class="pad"><h3 class="text-xl font-bold">14 <?php esc_html_e( 'Days', 'nutrifrais' ); ?></h3><p><?php esc_html_e( 'Deeper transformation & habits.', 'nutrifrais' ); ?></p><a class="btn btn-primary" href="<?php echo esc_url( site_url('/plans') ); ?>"><?php esc_html_e( 'Choose', 'nutrifrais' ); ?></a></div></div>
  </div>
</section>

<section class="max-w-screen-xl mx-auto px-4 mb-10">
  <div class="card"><div class="pad">
    <h2><?php esc_html_e( 'AI Nutrition Assistant', 'nutrifrais' ); ?></h2>
    <p><?php esc_html_e( 'Ask anything about nutrition — we’ll connect your preferences and goals to create smart menus. (Placeholder UI)', 'nutrifrais' ); ?></p>
    <a class="btn btn-secondary" href="<?php echo esc_url( site_url('/ai-nutrition-assistant') ); ?>"><?php esc_html_e( 'Open Assistant', 'nutrifrais' ); ?></a>
  </div></div>
</section>

<?php get_footer();
