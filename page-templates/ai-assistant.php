<?php
/**
 * Template Name: AI Nutrition Assistant (Placeholder)
 * @package Nutrifrais
 */
get_header(); ?>

<section class="card" style="margin:20px 0;">
  <div class="pad">
    <h1><?php esc_html_e( 'AI Nutrition Assistant', 'nutrifrais' ); ?></h1>
    <p><?php esc_html_e( 'Ask questions and get personalized menu suggestions. (Placeholder UI – API connection pending)', 'nutrifrais' ); ?></p>
    <form class="grid" onsubmit="return false;">
      <textarea id="nf-ai-input" rows="4" placeholder="<?php esc_attr_e( 'Ask about calories, diets, or goals…', 'nutrifrais' ); ?>" style="width:100%;"></textarea>
      <button id="nf-ai-send" class="btn btn-primary" type="button"><?php esc_html_e( 'Ask', 'nutrifrais' ); ?></button>
    </form>
    <div id="nf-ai-output" class="card" style="margin-top:12px;"><div class="pad">
      <strong><?php esc_html_e( 'Example reply:', 'nutrifrais' ); ?></strong>
      <p><?php esc_html_e( 'For a 7-day weight loss plan, target ~1600 kcal/day, prioritize lean protein and fiber. Suggested meals: quinoa salad, grilled salmon, veggie bowls.', 'nutrifrais' ); ?></p>
    </div></div>
  </div>
</section>

<?php get_footer();

