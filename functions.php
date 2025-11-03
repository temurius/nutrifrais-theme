<?php
// Theme setup
function nutrifrais_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'nutrifrais_theme_setup');
