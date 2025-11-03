<?php
/**
 * Nutrifrais Theme functions and definitions
 *
 * @package Nutrifrais
 */

if ( ! defined( 'NUTRIFRAIS_THEME_VERSION' ) ) {
    define( 'NUTRIFRAIS_THEME_VERSION', '1.0.0' );
}

// Theme setup
add_action( 'after_setup_theme', function () {
    load_theme_textdomain( 'nutrifrais', get_template_directory() . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption', 'style', 'script', 'comment-list', 'comment-form' ] );
    add_theme_support( 'custom-logo', [ 'height' => 48, 'width' => 160, 'flex-width' => true, 'flex-height' => true ] );

    // WooCommerce support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'nutrifrais' ),
        'footer'  => __( 'Footer Menu', 'nutrifrais' ),
        'account' => __( 'Account Menu', 'nutrifrais' ),
    ] );
} );

// Widgets
add_action( 'widgets_init', function () {
    register_sidebar( [
        'name'          => __( 'Sidebar', 'nutrifrais' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Main blog/sidebar area.', 'nutrifrais' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s card">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );

    for ( $i = 1; $i <= 3; $i++ ) {
        register_sidebar( [
            'name'          => sprintf( __( 'Footer %d', 'nutrifrais' ), $i ),
            'id'            => 'footer-' . $i,
            'description'   => __( 'Footer widget area', 'nutrifrais' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ] );
    }
} );

// Assets
add_action( 'wp_enqueue_scripts', function () {
    // Tailwind via CDN for rapid theming
    wp_enqueue_script( 'nutrifrais-tailwind', 'https://cdn.tailwindcss.com', [], null, false );
    $tw_config = 'tailwind.config = { theme: { extend: { colors: { nf: { green: "#2dbf7a", dark: "#189b5f", leaf: "#9be4c3", slate: "#24323f" } } } } };';
    wp_add_inline_script( 'nutrifrais-tailwind', $tw_config, 'before' );

    // Base stylesheet and WooCommerce overrides
    wp_enqueue_style( 'nutrifrais-style', get_stylesheet_uri(), [], NUTRIFRAIS_THEME_VERSION );
    wp_enqueue_style( 'nutrifrais-woo', get_template_directory_uri() . '/assets/css/woo.css', [ 'nutrifrais-style' ], NUTRIFRAIS_THEME_VERSION );

    // Theme JS
    wp_enqueue_script( 'nutrifrais-main', get_template_directory_uri() . '/assets/js/main.js', [ 'jquery' ], NUTRIFRAIS_THEME_VERSION, true );
    wp_localize_script( 'nutrifrais-main', 'Nutrifrais', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'i18n'    => [ 'added_to_cart' => __( 'Added to cart', 'nutrifrais' ) ],
    ] );
} );

// Body classes helper
add_filter( 'body_class', function ( $classes ) {
    $classes[] = 'nf-body';
    if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
        $classes[] = 'nf-woo';
    }
    return $classes;
} );

// Woo: mini cart count fragment
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
    ob_start();
    ?>
    <span class="count"><?php echo WC()->cart ? esc_html( WC()->cart->get_cart_contents_count() ) : 0; ?></span>
    <?php
    $fragments['span.count'] = ob_get_clean();
    return $fragments;
} );

// Woo: show diet/type/calories on single product
add_action( 'woocommerce_single_product_summary', function () {
    $diet = get_the_terms( get_the_ID(), 'nf_diet' );
    $type = get_the_terms( get_the_ID(), 'nf_meal_type' );
    $kcal = get_post_meta( get_the_ID(), '_nf_calories', true );
    echo '<div class="nf-product-badges" style="display:flex;gap:8px;flex-wrap:wrap;margin:8px 0 12px 0;">';
    if ( $diet && ! is_wp_error( $diet ) ) {
        echo '<span class="badge">' . esc_html( $diet[0]->name ) . '</span>';
    }
    if ( $type && ! is_wp_error( $type ) ) {
        echo '<span class="badge">' . esc_html( $type[0]->name ) . '</span>';
    }
    if ( $kcal ) {
        echo '<span class="badge">' . esc_html( $kcal ) . ' kcal</span>';
    }
    echo '</div>';
}, 6 );

// Custom taxonomies for product filters
add_action( 'init', function () {
    if ( post_type_exists( 'product' ) ) {
        register_taxonomy( 'nf_diet', 'product', [
            'label'        => __( 'Diet', 'nutrifrais' ),
            'hierarchical' => true,
            'public'       => true,
            'show_ui'      => true,
            'show_in_rest' => true,
        ] );
        register_taxonomy( 'nf_meal_type', 'product', [
            'label'        => __( 'Meal Type', 'nutrifrais' ),
            'hierarchical' => true,
            'public'       => true,
            'show_ui'      => true,
            'show_in_rest' => true,
        ] );
        // Calories kept as product meta: _nf_calories (integer)
    }
} );

// Front-end product filters [nutrifrais_filters]
add_shortcode( 'nutrifrais_filters', function () {
    if ( ! function_exists( 'is_woocommerce' ) ) return '';
    ob_start();
    ?>
    <form class="nf-filters card pad" method="get">
        <div class="grid grid-3">
            <label>
                <span><?php esc_html_e( 'Diet', 'nutrifrais' ); ?></span>
                <?php wp_dropdown_categories( [
                    'taxonomy'        => 'nf_diet',
                    'name'            => 'nf_diet',
                    'show_option_all' => __( 'All', 'nutrifrais' ),
                    'hide_empty'      => false,
                    'orderby'         => 'name',
                    'selected'        => isset( $_GET['nf_diet'] ) ? (int) $_GET['nf_diet'] : 0,
                ] ); ?>
            </label>
            <label>
                <span><?php esc_html_e( 'Meal Type', 'nutrifrais' ); ?></span>
                <?php wp_dropdown_categories( [
                    'taxonomy'        => 'nf_meal_type',
                    'name'            => 'nf_meal_type',
                    'show_option_all' => __( 'All', 'nutrifrais' ),
                    'hide_empty'      => false,
                    'orderby'         => 'name',
                    'selected'        => isset( $_GET['nf_meal_type'] ) ? (int) $_GET['nf_meal_type'] : 0,
                ] ); ?>
            </label>
            <label>
                <span><?php esc_html_e( 'Max Calories', 'nutrifrais' ); ?></span>
                <input type="number" name="nf_calories_max" value="<?php echo isset( $_GET['nf_calories_max'] ) ? esc_attr( $_GET['nf_calories_max'] ) : ''; ?>" placeholder="500" />
            </label>
        </div>
        <div style="margin-top:12px">
            <button class="btn btn-primary" type="submit"><?php esc_html_e( 'Apply Filters', 'nutrifrais' ); ?></button>
        </div>
    </form>
    <?php
    return ob_get_clean();
} );

// Apply filters to Woo product queries
add_action( 'pre_get_posts', function ( $q ) {
    if ( is_admin() || ! $q->is_main_query() ) return;
    if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
        $tax_query  = [];
        $meta_query = [];

        if ( ! empty( $_GET['nf_diet'] ) ) {
            $tax_query[] = [ 'taxonomy' => 'nf_diet', 'field' => 'term_id', 'terms' => (int) $_GET['nf_diet'] ];
        }
        if ( ! empty( $_GET['nf_meal_type'] ) ) {
            $tax_query[] = [ 'taxonomy' => 'nf_meal_type', 'field' => 'term_id', 'terms' => (int) $_GET['nf_meal_type'] ];
        }
        if ( ! empty( $_GET['nf_calories_max'] ) ) {
            $meta_query[] = [
                'key'     => '_nf_calories',
                'value'   => (int) $_GET['nf_calories_max'],
                'type'    => 'NUMERIC',
                'compare' => '<=',
            ];
        }

        if ( $tax_query ) {
            $q->set( 'tax_query', array_merge( [ 'relation' => 'AND' ], $tax_query ) );
        }
        if ( $meta_query ) {
            $q->set( 'meta_query', array_merge( [ 'relation' => 'AND' ], $meta_query ) );
        }
    }
} );

// Demo data on theme activation
add_action( 'after_switch_theme', function () {
    // Create pages if not exist
    $pages = [
        'Home'                => [ 'slug' => 'home', 'template' => 'front-page.php' ],
        'Plans'               => [ 'slug' => 'plans', 'template' => 'page-templates/plans.php' ],
        'AI Nutrition Assistant' => [ 'slug' => 'ai-nutrition-assistant', 'template' => 'page-templates/ai-assistant.php' ],
        'Partner Restaurants' => [ 'slug' => 'partners', 'template' => 'page-templates/partners.php' ],
        'Blog'                => [ 'slug' => 'blog', 'template' => '' ],
        'Shop'                => [ 'slug' => 'shop', 'template' => '' ],
        'My Account'          => [ 'slug' => 'my-account', 'template' => '' ],
        'Cart'                => [ 'slug' => 'cart', 'template' => '' ],
        'Checkout'            => [ 'slug' => 'checkout', 'template' => '' ],
    ];

    $created = [];
    foreach ( $pages as $title => $info ) {
        $page = get_page_by_path( $info['slug'] );
        if ( ! $page ) {
            $page_id = wp_insert_post( [ 'post_title' => $title, 'post_name' => $info['slug'], 'post_type' => 'page', 'post_status' => 'publish' ] );
            if ( $page_id && ! is_wp_error( $page_id ) && ! empty( $info['template'] ) ) {
                update_post_meta( $page_id, '_wp_page_template', $info['template'] );
            }
            $created[ $info['slug'] ] = $page_id;
        } else {
            $created[ $info['slug'] ] = $page->ID;
        }
    }

    // Set front page and posts page
    if ( ! empty( $created['home'] ) ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', (int) $created['home'] );
    }
    if ( ! empty( $created['blog'] ) ) {
        update_option( 'page_for_posts', (int) $created['blog'] );
    }

    // Create menu and assign items
    $menu_id = wp_create_nav_menu( 'Primary' );
    if ( ! is_wp_error( $menu_id ) ) {
        $links = [ 'home', 'shop', 'plans', 'partners', 'blog', 'ai-nutrition-assistant', 'my-account' ];
        foreach ( $links as $slug ) {
            if ( ! empty( $created[ $slug ] ) ) {
                wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-type'      => 'post_type',
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $created[ $slug ],
                    'menu-item-status'    => 'publish',
                ] );
            }
        }
        set_theme_mod( 'nav_menu_locations', [ 'primary' => $menu_id ] );
    }

    // Seed Woo demo products if WooCommerce active
    if ( class_exists( 'WC_Product' ) ) {
        $diet_terms = [ 'Weight Loss', 'Muscle Gain', 'Diabetic', 'Health' ];
        foreach ( $diet_terms as $t ) { wp_insert_term( $t, 'nf_diet' ); }
        $type_terms = [ 'Breakfast', 'Lunch', 'Dinner', 'Snack' ];
        foreach ( $type_terms as $t ) { wp_insert_term( $t, 'nf_meal_type' ); }

        for ( $i = 1; $i <= 10; $i++ ) {
            $title = sprintf( __( 'Bio Meal %d', 'nutrifrais' ), $i );
            $existing = get_page_by_title( $title, OBJECT, 'product' );
            if ( $existing ) continue;
            $product_id = wp_insert_post( [
                'post_title'   => $title,
                'post_content' => __( 'Delicious organic meal with balanced macros and local ingredients.', 'nutrifrais' ),
                'post_status'  => 'publish',
                'post_type'    => 'product',
            ] );
            if ( $product_id && ! is_wp_error( $product_id ) ) {
                wp_set_object_terms( $product_id, 'simple', 'product_type' );
                update_post_meta( $product_id, '_regular_price',  sprintf( '%.2f', 8 + $i * 0.5 ) );
                update_post_meta( $product_id, '_price',          sprintf( '%.2f', 8 + $i * 0.5 ) );
                update_post_meta( $product_id, '_stock_status',   'instock' );
                update_post_meta( $product_id, '_manage_stock',   'no' );
                update_post_meta( $product_id, '_nf_calories',     350 + ( $i * 25 ) );
                // assign random terms
                $diet  = get_terms( [ 'taxonomy' => 'nf_diet', 'hide_empty' => false ] );
                $types = get_terms( [ 'taxonomy' => 'nf_meal_type', 'hide_empty' => false ] );
                if ( ! is_wp_error( $diet ) && $diet ) {
                    wp_set_object_terms( $product_id, $diet[ array_rand( $diet ) ]->term_id, 'nf_diet' );
                }
                if ( ! is_wp_error( $types ) && $types ) {
                    wp_set_object_terms( $product_id, $types[ array_rand( $types ) ]->term_id, 'nf_meal_type' );
                }
            }
        }
    }
} );

// Helper: get cart count safely
function nutrifrais_get_cart_count() {
    return function_exists( 'WC' ) && WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0;
}

// Register image sizes
add_action( 'after_setup_theme', function () {
    add_image_size( 'nf-card', 600, 400, true );
} );
