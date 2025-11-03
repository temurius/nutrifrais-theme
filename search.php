<?php
/**
 * Search results
 * @package Nutrifrais
 */
get_header(); ?>

<header class="hero card" style="margin-bottom:20px;">
  <div class="pad">
    <h1><?php printf( esc_html__( 'Search: %s', 'nutrifrais' ), get_search_query() ); ?></h1>
  </div>
</header>

<div class="grid grid-2">
  <div>
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
        <div class="pad">
          <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <?php the_excerpt(); ?>
        </div>
      </article>
    <?php endwhile; else : ?>
      <p><?php esc_html_e( 'No results found.', 'nutrifrais' ); ?></p>
    <?php endif; ?>
    <div class="pagination"><?php the_posts_pagination(); ?></div>
  </div>
  <aside><?php get_sidebar(); ?></aside>
</div>

<?php get_footer();

