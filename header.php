<?php
/**
 * Header template
 * @package Nutrifrais
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <style>.skip-link{position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;} .skip-link:focus{position:static;width:auto;height:auto;}</style>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'nutrifrais' ); ?></a>

<header class="site-header">
    <div class="container bar">
        <div class="site-brand">
            <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
                <span>N</span>
            </a>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
        </div>
        <nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'nutrifrais' ); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => false,
                'menu_class'     => 'menu',
            ] );
            ?>
            <?php $nf_cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart'); ?>
            <a class="cart-badge" href="<?php echo esc_url( $nf_cart_url ); ?>">
                <span><?php esc_html_e( 'Cart', 'nutrifrais' ); ?></span>
                <span class="count"><?php echo (int) nutrifrais_get_cart_count(); ?></span>
            </a>
            <?php $nf_account = get_option( 'woocommerce_myaccount_page_id' ); ?>
            <a class="cta" href="<?php echo esc_url( $nf_account ? get_permalink( $nf_account ) : wp_login_url() ); ?>"><?php esc_html_e( 'Account', 'nutrifrais' ); ?></a>
        </nav>
    </div>
</header>

<main id="content" class="site-content">
    <div class="container">
