<?php
/**
 * Index fallback
 * @package Nutrifrais
 */
get_header(); ?>

<div class="grid grid-2">
    <div>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
                <div class="pad">
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-meta">
                        <span><?php echo esc_html( get_the_date() ); ?></span>
                        <span> &middot; </span>
                        <span><?php the_author(); ?></span>
                    </div>
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                </div>
            </article>
        <?php endwhile; endif; ?>

        <div class="pagination">
            <?php the_posts_pagination(); ?>
        </div>
    </div>

    <aside>
        <?php get_sidebar(); ?>
    </aside>
</div>

<?php get_footer();

