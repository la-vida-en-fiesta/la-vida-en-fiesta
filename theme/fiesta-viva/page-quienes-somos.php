<?php
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<main id="contenido" class="site-main about-main">
    <?php while (have_posts()) : the_post(); ?>
    <section class="about-intro" aria-labelledby="about-title">
        <div><h1 id="about-title"><?php the_title(); ?></h1><div class="about-copy"><?php the_content(); ?></div></div>
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/local-vidriera.jpg'); ?>" alt="Vidriera de La Vida en Fiesta con globos, guirnaldas y regalos" width="2048" height="1450">
    </section>
    <section class="about-contact" aria-labelledby="about-contact-title">
        <h2 id="about-contact-title">Nuestro local</h2>
        <dl>
            <div><dt>Nombre</dt><dd>La Vida en Fiesta</dd></div>
            <?php foreach (array('address'=>'Dirección', 'hours'=>'Horarios', 'phone'=>'Teléfono') as $key=>$label) : $value = get_theme_mod('fiesta_local_' . $key, ''); if (!$value) { continue; } ?>
            <div><dt><?php echo esc_html($label); ?></dt><dd><?php echo nl2br(esc_html($value)); ?></dd></div>
            <?php endforeach; ?>
            <div><dt>Instagram</dt><dd><a href="https://www.instagram.com/lavidaenfiesta.uy/" target="_blank" rel="noopener noreferrer">@lavidaenfiesta.uy<span class="sr-only"> (abre una pestaña nueva)</span></a></dd></div>
        </dl>
        <?php $whatsapp = preg_replace('/\D/', '', get_theme_mod('fiesta_local_whatsapp', '')); if ($whatsapp) : ?>
        <a class="button" href="<?php echo esc_url('https://wa.me/' . $whatsapp); ?>" target="_blank" rel="noopener noreferrer">Escríbenos por WhatsApp<span class="sr-only"> (abre una pestaña nueva)</span></a>
        <?php endif; ?>
    </section>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
