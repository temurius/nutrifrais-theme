<?php
/**
 * Sidebar
 * @package Nutrifrais
 */
?>
<aside id="secondary" class="widget-area">
  <?php if ( is_active_sidebar( 'sidebar-1' ) ) { dynamic_sidebar( 'sidebar-1' ); } else : ?>
    <section class="widget card"><div class="pad">
      <h3 class="widget-title"><?php esc_html_e( 'About', 'nutrifrais' ); ?></h3>
      <p><?php esc_html_e( 'Add widgets to the sidebar from Appearance → Widgets.', 'nutrifrais' ); ?></p>
    </div></section>
  <?php endif; ?>
</aside>

