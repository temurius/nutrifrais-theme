<?php
/**
 * Footer template
 * @package Nutrifrais
 */
?>
    </div><!-- .container -->
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-widgets">
            <?php for ( $i=1; $i<=3; $i++ ) : ?>
                <div class="footer-col">
                    <?php if ( is_active_sidebar( 'footer-' . $i ) ) { dynamic_sidebar( 'footer-' . $i ); } ?>
                </div>
            <?php endfor; ?>
            <div class="footer-col">
                <h4><?php esc_html_e( 'Explore', 'nutrifrais' ); ?></h4>
                <?php wp_nav_menu( [ 'theme_location' => 'footer', 'container' => false, 'fallback_cb' => false ] ); ?>
            </div>
        </div>
        <div class="subfooter">
            <div>&copy; <?php echo esc_html( date('Y') ); ?> <?php bloginfo('name'); ?></div>
            <div><?php bloginfo('description'); ?></div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

