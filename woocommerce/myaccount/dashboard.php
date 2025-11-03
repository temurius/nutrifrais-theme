<?php
/**
 * My Account dashboard wrapper
 * @package Nutrifrais
 */
defined( 'ABSPATH' ) || exit;

// Load default then add a card with quick links
if ( function_exists( 'WC' ) ) {
    wc_get_template( 'myaccount/dashboard.php', [], '', WC()->plugin_path() . '/templates/' );
}

echo '<div class="card" style="margin-top:20px;"><div class="pad">';
echo '<h2>' . esc_html__( 'Your Meal Plans', 'nutrifrais' ) . '</h2>';
echo '<p>' . esc_html__( 'Track your active plans and recommendations here.', 'nutrifrais' ) . '</p>';
echo '<ul><li>' . esc_html__( 'No active plans. Start one from Plans page.', 'nutrifrais' ) . '</li></ul>';
echo '</div></div>';

