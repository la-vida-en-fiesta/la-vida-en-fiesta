<?php
if (!defined('ABSPATH')) { exit; }
get_header(); ?>
<main id="contenido" class="site-main how-to-buy">
    <?php while (have_posts()) : the_post(); ?>
    <article>
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
        <div class="how-to-buy-actions">
            <a class="button" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Explorar el catálogo</a>
            <a href="https://wa.me/59897192690" target="_blank" rel="noopener noreferrer">Escríbenos por WhatsApp<span class="sr-only"> (abre una pestaña nueva)</span></a>
        </div>
    </article>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
