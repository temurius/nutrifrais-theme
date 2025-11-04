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

// Customizer: homepage slider shortcode
add_action( 'customize_register', function( $wp_customize ) {
    $wp_customize->add_section( 'nf_home', [
        'title'       => __( 'NutriFrais Home', 'nutrifrais' ),
        'priority'    => 30,
        'description' => __( 'Optional slider shortcode for the homepage. Paste from Smart Slider 3 or Slider Revolution.', 'nutrifrais' ),
    ] );
    $wp_customize->add_setting( 'nf_slider_shortcode', [
        'type'              => 'theme_mod',
        'sanitize_callback' => 'wp_kses_post',
    ] );
    $wp_customize->add_control( 'nf_slider_shortcode', [
        'section' => 'nf_home',
        'label'   => __( 'Slider Shortcode', 'nutrifrais' ),
        'type'    => 'text',
    ] );
} );

// Product nutrition details metabox
add_action( 'add_meta_boxes', function() {
    add_meta_box( 'nf_meal_details', __( 'Meal Nutrition Details', 'nutrifrais' ), function( $post ) {
        wp_nonce_field( 'nf_meal_details', 'nf_meal_nonce' );
        $fields = [
            'ingredients'  => get_post_meta( $post->ID, '_nf_ingredients', true ),
            'protein'      => get_post_meta( $post->ID, '_nf_protein', true ),
            'carbs'        => get_post_meta( $post->ID, '_nf_carbs', true ),
            'fats'         => get_post_meta( $post->ID, '_nf_fats', true ),
            'serving_size' => get_post_meta( $post->ID, '_nf_serving_size', true ),
            'calories'     => get_post_meta( $post->ID, '_nf_calories', true ),
            'plan_duration'=> get_post_meta( $post->ID, '_nf_plan_duration', true ),
        ];
        echo '<div class="grid" style="grid-template-columns:1fr 1fr; gap:12px;">';
        echo '<label><strong>' . esc_html__( 'Ingredients (comma-separated)', 'nutrifrais' ) . '</strong><textarea style="width:100%;min-height:80px" name="_nf_ingredients">' . esc_textarea( $fields['ingredients'] ) . '</textarea></label>';
        echo '<label><strong>' . esc_html__( 'Serving Size', 'nutrifrais' ) . '</strong><input type="text" name="_nf_serving_size" value="' . esc_attr( $fields['serving_size'] ) . '" /></label>';
        echo '<label><strong>' . esc_html__( 'Calories (kcal)', 'nutrifrais' ) . '</strong><input type="number" name="_nf_calories" value="' . esc_attr( $fields['calories'] ) . '" /></label>';
        echo '<label><strong>' . esc_html__( 'Protein (g)', 'nutrifrais' ) . '</strong><input type="number" step="0.1" name="_nf_protein" value="' . esc_attr( $fields['protein'] ) . '" /></label>';
        echo '<label><strong>' . esc_html__( 'Carbs (g)', 'nutrifrais' ) . '</strong><input type="number" step="0.1" name="_nf_carbs" value="' . esc_attr( $fields['carbs'] ) . '" /></label>';
        echo '<label><strong>' . esc_html__( 'Fats (g)', 'nutrifrais' ) . '</strong><input type="number" step="0.1" name="_nf_fats" value="' . esc_attr( $fields['fats'] ) . '" /></label>';
        echo '<label><strong>' . esc_html__( 'Plan Duration', 'nutrifrais' ) . '</strong><select name="_nf_plan_duration">';
        $durations = [ '' => __( 'None', 'nutrifrais' ), '3' => '3', '7' => '7', '14' => '14' ];
        foreach ( $durations as $k => $label ) {
            echo '<option value="' . esc_attr( $k ) . '"' . selected( $fields['plan_duration'], $k, false ) . '>' . esc_html( $label ) . '</option>';
        }
        echo '</select></label>';
        echo '</div>';
    }, 'product', 'normal', 'default' );
} );

add_action( 'save_post_product', function( $post_id ) {
    if ( ! isset( $_POST['nf_meal_nonce'] ) || ! wp_verify_nonce( $_POST['nf_meal_nonce'], 'nf_meal_details' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    $map = [ '_nf_ingredients' => 'sanitize_textarea_field', '_nf_serving_size' => 'sanitize_text_field', '_nf_calories' => 'intval', '_nf_protein' => 'floatval', '_nf_carbs' => 'floatval', '_nf_fats' => 'floatval', '_nf_plan_duration' => 'sanitize_text_field' ];
    foreach ( $map as $key => $sanitize ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, call_user_func( $sanitize, wp_unslash( $_POST[ $key ] ) ) );
        }
    }
} );

// Quick View AJAX and modal output
add_action( 'wp_footer', function(){
    echo '<div id="nf-qv-overlay" class="nf-modal hidden"><div class="nf-modal-backdrop"></div><div class="nf-modal-panel"><button class="nf-modal-close" type="button" aria-label="Close">×</button><div id="nf-qv-content"></div></div></div>';
} );

add_action( 'wp_ajax_nf_quick_view', 'nutrifrais_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_nf_quick_view', 'nutrifrais_ajax_quick_view' );
function nutrifrais_ajax_quick_view() {
    $pid = isset( $_GET['product_id'] ) ? absint( $_GET['product_id'] ) : 0;
    if ( ! $pid ) { wp_send_json_error( [ 'message' => 'Missing product_id' ], 400 ); }
    $p = wc_get_product( $pid );
    if ( ! $p ) { wp_send_json_error( [ 'message' => 'Not found' ], 404 ); }
    ob_start();
    echo '<div class="qv">';
    echo get_the_post_thumbnail( $pid, 'large', [ 'class' => 'qv-img' ] );
    echo '<div class="qv-body">';
    echo '<h3 class="qv-title">' . esc_html( get_the_title( $pid ) ) . '</h3>';
    echo '<div class="qv-price">' . wp_kses_post( $p->get_price_html() ) . '</div>';
    $kcal = get_post_meta( $pid, '_nf_calories', true );
    $protein = get_post_meta( $pid, '_nf_protein', true );
    $carbs = get_post_meta( $pid, '_nf_carbs', true );
    $fats = get_post_meta( $pid, '_nf_fats', true );
    echo '<ul class="qv-nutri">';
    if ( $kcal ) echo '<li><strong>' . esc_html__( 'Calories', 'nutrifrais' ) . ':</strong> ' . esc_html( $kcal ) . ' kcal</li>';
    if ( $protein ) echo '<li><strong>' . esc_html__( 'Protein', 'nutrifrais' ) . ':</strong> ' . esc_html( $protein ) . ' g</li>';
    if ( $carbs ) echo '<li><strong>' . esc_html__( 'Carbs', 'nutrifrais' ) . ':</strong> ' . esc_html( $carbs ) . ' g</li>';
    if ( $fats ) echo '<li><strong>' . esc_html__( 'Fats', 'nutrifrais' ) . ':</strong> ' . esc_html( $fats ) . ' g</li>';
    echo '</ul>';
    echo '<div class="qv-actions">';
    woocommerce_template_single_add_to_cart();
    echo '</div>';
    echo '</div></div>';
    wp_send_json_success( [ 'html' => ob_get_clean() ] );
}

// Calorie calculator shortcode and AJAX
add_shortcode( 'nutrifrais_calculator', function(){
    ob_start();
    ?>
    <form id="nf-calc-form" class="grid" style="grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;">
      <label><span><?php esc_html_e('Age','nutrifrais'); ?></span><input type="number" name="age" min="14" max="100" required></label>
      <label><span><?php esc_html_e('Gender','nutrifrais'); ?></span>
        <select name="gender"><option value="m"><?php esc_html_e('Male','nutrifrais'); ?></option><option value="f"><?php esc_html_e('Female','nutrifrais'); ?></option></select>
      </label>
      <label><span><?php esc_html_e('Weight (kg)','nutrifrais'); ?></span><input type="number" step="0.1" name="weight" required></label>
      <label><span><?php esc_html_e('Height (cm)','nutrifrais'); ?></span><input type="number" step="0.1" name="height" required></label>
      <label><span><?php esc_html_e('Activity','nutrifrais'); ?></span>
        <select name="activity">
          <option value="1.2"><?php esc_html_e('Sedentary','nutrifrais'); ?></option>
          <option value="1.375"><?php esc_html_e('Lightly active','nutrifrais'); ?></option>
          <option value="1.55"><?php esc_html_e('Moderately active','nutrifrais'); ?></option>
          <option value="1.725"><?php esc_html_e('Very active','nutrifrais'); ?></option>
        </select>
      </label>
      <label><span><?php esc_html_e('Goal','nutrifrais'); ?></span>
        <select name="goal">
          <option value="loss"><?php esc_html_e('Weight Loss','nutrifrais'); ?></option>
          <option value="maint"><?php esc_html_e('Maintenance','nutrifrais'); ?></option>
          <option value="gain"><?php esc_html_e('Muscle Gain','nutrifrais'); ?></option>
        </select>
      </label>
      <label><span><?php esc_html_e('Meals / day','nutrifrais'); ?></span>
        <select name="meals"><option>3</option><option>4</option><option>5</option></select>
      </label>
      <label><span><?php esc_html_e('Plan Duration (days)','nutrifrais'); ?></span>
        <select name="duration"><option>3</option><option selected>7</option><option>14</option></select>
      </label>
      <div style="grid-column:1/-1"><button type="button" id="nf-calc-run" class="btn btn-primary"><?php esc_html_e('Calculate','nutrifrais'); ?></button></div>
    </form>
    <div id="nf-calc-result" class="card mt-3"><div class="pad"></div></div>
    <?php
    return ob_get_clean();
} );

add_action( 'wp_ajax_nf_calculate_plan', 'nutrifrais_ajax_calculate_plan' );
add_action( 'wp_ajax_nopriv_nf_calculate_plan', 'nutrifrais_ajax_calculate_plan' );
function nutrifrais_ajax_calculate_plan(){
    $age   = isset($_POST['age']) ? (int) $_POST['age'] : 0;
    $g     = isset($_POST['gender']) ? sanitize_text_field($_POST['gender']) : 'm';
    $w     = isset($_POST['weight']) ? (float) $_POST['weight'] : 0;
    $h     = isset($_POST['height']) ? (float) $_POST['height'] : 0;
    $act   = isset($_POST['activity']) ? (float) $_POST['activity'] : 1.2;
    $goal  = isset($_POST['goal']) ? sanitize_text_field($_POST['goal']) : 'maint';
    $meals = isset($_POST['meals']) ? max(1,(int) $_POST['meals']) : 3;
    $dur   = isset($_POST['duration']) ? (int) $_POST['duration'] : 7;

    if ( $age < 14 || $w <= 0 || $h <= 0 ) {
        wp_send_json_error([ 'message' => 'Invalid inputs' ], 400);
    }
    // Mifflin-St Jeor
    $bmr = ( $g === 'm' ) ? (10*$w + 6.25*$h - 5*$age + 5) : (10*$w + 6.25*$h - 5*$age - 161);
    $tdee = $bmr * $act;
    if ( $goal === 'loss' ) $tdee *= 0.8; // ~20% deficit
    if ( $goal === 'gain' ) $tdee *= 1.15; // ~15% surplus

    // Macro splits
    $splits = [ 'loss' => [30,35,35], 'maint' => [30,40,30], 'gain' => [30,45,25] ];
    $sp = isset($splits[$goal]) ? $splits[$goal] : $splits['maint'];
    list($pPct,$cPct,$fPct) = $sp;
    $protein_g = round( ($tdee * $pPct/100) / 4 );
    $carbs_g   = round( ($tdee * $cPct/100) / 4 );
    $fats_g    = round( ($tdee * $fPct/100) / 9 );

    // Recommend meals near target per-meal calories
    $per_meal = max(200, round($tdee / $meals));
    $q = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => 9,
        'meta_key'       => '_nf_calories',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
        'meta_query'     => [ [ 'key' => '_nf_calories', 'compare' => 'EXISTS' ] ],
    ]);
    ob_start();
    echo '<div class="pad">';
    echo '<h3>' . esc_html__( 'Daily Target', 'nutrifrais' ) . ': ' . esc_html( round($tdee) ) . ' kcal</h3>';
    echo '<p>' . esc_html__( 'Macros', 'nutrifrais' ) . ': ' . esc_html( $protein_g ) . 'g P • ' . esc_html( $carbs_g ) . 'g C • ' . esc_html( $fats_g ) . 'g F</p>';
    echo '<h4 class="mt-3">' . esc_html__( 'Suggested Meals', 'nutrifrais' ) . '</h4>';
    echo '<ul class="products" style="list-style:none;padding:0;">';
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) { $q->the_post();
            $pid = get_the_ID();
            $cal = (int) get_post_meta( $pid, '_nf_calories', true );
            echo '<li class="product card"><a href="' . esc_url( get_permalink($pid) ) . '" class="block">' . get_the_post_thumbnail( $pid, 'nf-card' ) . '<div class="pad"><h3>' . esc_html( get_the_title($pid) ) . '</h3><p class="badge">' . esc_html( $cal ) . ' kcal</p></div></a><div class="pad">';
            woocommerce_template_loop_add_to_cart();
            echo '</div></li>';
        }
        wp_reset_postdata();
    } else {
        echo '<li>' . esc_html__( 'No meals found. Try adjusting filters.', 'nutrifrais' ) . '</li>';
    }
    echo '</ul>';
    echo '</div>';
    $html = ob_get_clean();

    wp_send_json_success([
        'tdee' => round($tdee), 'protein' => $protein_g, 'carbs' => $carbs_g, 'fats' => $fats_g,
        'html' => $html,
    ]);
}

// Woo My Account: Nutrition Profile endpoint
add_action( 'init', function(){ add_rewrite_endpoint( 'nutrition-profile', EP_ROOT | EP_PAGES ); } );
add_filter( 'woocommerce_account_menu_items', function( $items ){
    $items = array_slice( $items, 0, 1, true ) + [ 'nutrition-profile' => __( 'Nutrition Profile', 'nutrifrais' ) ] + array_slice( $items, 1, null, true );
    return $items;
} );
add_action( 'woocommerce_account_nutrition-profile_endpoint', function(){
    if ( ! is_user_logged_in() ) { echo '<p>' . esc_html__( 'Please log in to manage your profile.', 'nutrifrais' ) . '</p>'; return; }
    $uid = get_current_user_id();
    $fields = [ 'age','gender','weight','height','activity','goal','meals' ];
    if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['nf_profile_nonce']) && wp_verify_nonce($_POST['nf_profile_nonce'],'nf_profile') ) {
        foreach ( $fields as $f ) { if ( isset($_POST[$f]) ) update_user_meta( $uid, 'nf_'.$f, sanitize_text_field( wp_unslash($_POST[$f]) ) ); }
        echo '<div class="woocommerce-message">' . esc_html__( 'Profile saved.', 'nutrifrais' ) . '</div>';
    }
    $vals = [];
    foreach ( $fields as $f ) { $vals[$f] = get_user_meta( $uid, 'nf_'.$f, true ); }
    echo '<div class="card"><div class="pad"><h3>' . esc_html__( 'Nutrition Profile', 'nutrifrais' ) . '</h3>';
    echo '<form method="post" class="grid" style="grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;">';
    wp_nonce_field('nf_profile','nf_profile_nonce');
    echo '<label><span>'.esc_html__('Age','nutrifrais').'</span><input name="age" type="number" value="'.esc_attr($vals['age']).'"></label>';
    echo '<label><span>'.esc_html__('Gender','nutrifrais').'</span><select name="gender"><option value="m" '.selected($vals['gender'],'m',false).'>'.esc_html__('Male','nutrifrais').'</option><option value="f" '.selected($vals['gender'],'f',false).'>'.esc_html__('Female','nutrifrais').'</option></select></label>';
    echo '<label><span>'.esc_html__('Weight (kg)','nutrifrais').'</span><input name="weight" type="number" step="0.1" value="'.esc_attr($vals['weight']).'"></label>';
    echo '<label><span>'.esc_html__('Height (cm)','nutrifrais').'</span><input name="height" type="number" step="0.1" value="'.esc_attr($vals['height']).'"></label>';
    echo '<label><span>'.esc_html__('Activity','nutrifrais').'</span><select name="activity">';
    foreach ( [ '1.2'=>__('Sedentary','nutrifrais'), '1.375'=>__('Lightly active','nutrifrais'), '1.55'=>__('Moderately active','nutrifrais'), '1.725'=>__('Very active','nutrifrais') ] as $k=>$lbl ) { echo '<option value="'.esc_attr($k).'" '.selected($vals['activity'],$k,false).'>'.esc_html($lbl).'</option>'; }
    echo '</select></label>';
    echo '<label><span>'.esc_html__('Goal','nutrifrais').'</span><select name="goal">';
    foreach ( [ 'loss'=>__('Weight Loss','nutrifrais'), 'maint'=>__('Maintenance','nutrifrais'), 'gain'=>__('Muscle Gain','nutrifrais') ] as $k=>$lbl ) { echo '<option value="'.esc_attr($k).'" '.selected($vals['goal'],$k,false).'>'.esc_html($lbl).'</option>'; }
    echo '</select></label>';
    echo '<label><span>'.esc_html__('Meals/day','nutrifrais').'</span><select name="meals">';
    foreach ( [3,4,5] as $n ) { echo '<option value="'.esc_attr($n).'" '.selected($vals['meals'],$n,false).'>'.esc_html($n).'</option>'; }
    echo '</select></label>';
    echo '<div style="grid-column:1/-1"><button class="btn btn-primary" type="submit">'.esc_html__('Save','nutrifrais').'</button></div>';
    echo '</form></div></div>';
} );
