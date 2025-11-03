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

<header class="site-header bg-white shadow-md sticky top-0 z-40">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-center justify-between py-3">
            <div class="flex items-center gap-3">
                <a class="logo inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gradient-to-br from-[var(--nf-green,#2dbf7a)] to-[var(--nf-leaf,#9be4c3)] text-white font-extrabold" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">N</a>
                <a class="text-slate-800 font-bold text-lg" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
            </div>

            <button id="nf-nav-toggle" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-md border border-slate-200" aria-controls="primary-menu" aria-expanded="false">
                <span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'nutrifrais' ); ?></span>
                <span class="block w-5 h-0.5 bg-slate-700 mb-1"></span>
                <span class="block w-5 h-0.5 bg-slate-700 mb-1"></span>
                <span class="block w-5 h-0.5 bg-slate-700"></span>
            </button>

            <nav class="primary-nav hidden md:flex items-center gap-4" aria-label="<?php esc_attr_e( 'Primary', 'nutrifrais' ); ?>">
                <?php
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'menu_id'        => 'primary-menu',
                    'depth'          => 2,
                    'menu_class'     => 'menu md:flex items-center gap-6 text-[15px] font-medium',
                ] );
                ?>
                <?php $nf_cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart'); ?>
                <a class="cart-badge inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-50 text-slate-700 hover:bg-slate-100" href="<?php echo esc_url( $nf_cart_url ); ?>">
                    <span><?php esc_html_e( 'Cart', 'nutrifrais' ); ?></span>
                    <span class="count inline-flex items-center justify-center text-white bg-[var(--nf-green,#2dbf7a)] text-xs px-2 py-0.5 rounded-full"><?php echo (int) nutrifrais_get_cart_count(); ?></span>
                </a>
                <?php $nf_account = get_option( 'woocommerce_myaccount_page_id' ); ?>
                <a class="cta inline-flex items-center bg-[var(--nf-green,#2dbf7a)] hover:bg-[var(--nf-green-dark,#189b5f)] text-white px-4 py-2 rounded-xl shadow" href="<?php echo esc_url( $nf_account ? get_permalink( $nf_account ) : wp_login_url() ); ?>"><?php esc_html_e( 'Account', 'nutrifrais' ); ?></a>
            </nav>
        </div>

        <div id="nf-mobile-menu" class="md:hidden hidden pb-3">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => false,
                'menu_id'        => 'primary-menu-mobile',
                'depth'          => 2,
                'menu_class'     => 'menu flex flex-col gap-2',
            ] );
            ?>
            <div class="mt-2 flex items-center gap-2">
                <?php $nf_cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart'); ?>
                <a class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-50 text-slate-700" href="<?php echo esc_url( $nf_cart_url ); ?>">
                    <span><?php esc_html_e( 'Cart', 'nutrifrais' ); ?></span>
                    <span class="count inline-flex items-center justify-center text-white bg-[var(--nf-green,#2dbf7a)] text-xs px-2 py-0.5 rounded-full"><?php echo (int) nutrifrais_get_cart_count(); ?></span>
                </a>
                <?php $nf_account = get_option( 'woocommerce_myaccount_page_id' ); ?>
                <a class="inline-flex items-center bg-[var(--nf-green,#2dbf7a)] text-white px-4 py-2 rounded-xl" href="<?php echo esc_url( $nf_account ? get_permalink( $nf_account ) : wp_login_url() ); ?>"><?php esc_html_e( 'Account', 'nutrifrais' ); ?></a>
            </div>
        </div>
    </div>
</header>

<main id="content" class="site-content">
    <div class="container">
