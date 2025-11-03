<?php
/**
 * Single post template
 * @package Nutrifrais
 */
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
    <div class="pad">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <div class="entry-meta">
            <span><?php echo esc_html( get_the_date() ); ?></span>
            <span> &middot; </span>
            <span><?php the_author(); ?></span>
        </div>
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
        <?php comments_template(); ?>
    </div>
</article>
<?php endwhile; endif; ?>

<?php get_footer();

