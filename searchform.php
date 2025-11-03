<?php
/**
 * Search form
 * @package Nutrifrais
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label>
    <span class="screen-reader-text"><?php _e( 'Search for:', 'nutrifrais' ); ?></span>
    <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search…', 'placeholder', 'nutrifrais' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
  </label>
  <button type="submit" class="btn btn-secondary"><?php echo esc_html_x( 'Search', 'submit button', 'nutrifrais' ); ?></button>
</form>

