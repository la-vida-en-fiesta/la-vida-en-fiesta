<?php
if (!defined('ABSPATH')) { exit; }
get_header();
function fiesta_category_url($slug) {
    $term = get_term_by('slug', $slug, 'product_cat');
    $url = $term ? get_term_link($term) : false;
    return esc_url($url && !is_wp_error($url) ? $url : wc_get_page_permalink('shop'));
}
?>
<main id="contenido" class="home-main">
    <section class="welcome" aria-labelledby="welcome-title">
        <h1 id="welcome-title">Que empiece <span>la fiesta.</span></h1>
        <p class="welcome-copy">De un pequeño detalle a una gran celebración. <br>Cotillón, disfraces y regalos para tu próxima fiesta.</p>
    </section>
    <section class="season-showcase" aria-label="Conocé la tienda y prepará tu próxima fiesta">
        <a class="store-feature" href="#el-local">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/local-vidriera.jpg'); ?>" alt="Vidriera de La Vida en Fiesta, con guirnaldas, globos y regalos" width="2048" height="1450" fetchpriority="high">
            <div class="entry-caption"><div><h2>El local</h2><p>Una vidriera llena de ideas para tu fiesta.</p></div><?php echo fiesta_arrow_svg(); ?></div>
        </a>
        <?php $season = fiesta_current_season(); ?>
        <a class="event-feature <?php echo $season ? 'event-season' : 'event-birthday'; ?>" href="<?php echo fiesta_category_url($season ? $season['slug'] : 'cumpleanos'); ?>">
            <div><h2><?php echo $season ? esc_html($season['name']) : 'Cumpleaños'; ?></h2><p><?php echo $season && $season['slug'] === 'halloween' ? 'Disfraces, globos y detalles para una noche de terror.' : 'Globos, colores y detalles para tu próxima fiesta.'; ?></p></div>
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/' . ($season ? $season['art'] : 'birthday.svg')); ?>" alt="" width="400" height="400">
            <span class="event-action">Explorar <?php echo $season ? esc_html($season['name']) : 'cumpleaños'; ?> <?php echo fiesta_arrow_svg(); ?></span>
        </a>
    </section>
    <section class="occasions" aria-labelledby="occasions-title">
        <div class="section-heading"><h2 id="occasions-title">Tenemos una categoría para eso.</h2><a class="text-link" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Ver catálogo <?php echo fiesta_arrow_svg(); ?></a></div>
        <div class="occasion-list">
        <?php foreach (array('cumpleanos'=>'Cumpleaños', 'disfraces'=>'Disfraces', 'baby-shower'=>'Baby shower', 'despedidas-de-soltera'=>'Despedidas de soltera', 'halloween'=>'Halloween', 'navidad'=>'Navidad', 'noche-de-la-nostalgia'=>'Noche de la Nostalgia') as $slug=>$name): ?>
            <a href="<?php echo fiesta_category_url($slug); ?>"><span class="category-name"><?php echo fiesta_category_icon($slug); ?><span><?php echo esc_html($name); ?></span></span><?php echo fiesta_arrow_svg(); ?></a>
        <?php endforeach; ?>
        </div>
    </section>
    <section class="extras" aria-labelledby="extras-title">
        <div><h2 id="extras-title">También hay detalles<br>para regalar.</h2><p>Para sorprender a alguien o darte un gusto.</p></div>
        <div class="extras-links">
        <?php foreach (array('perfumeria'=>'Perfumería', 'regaleria'=>'Regalería', 'jugueteria'=>'Juguetería', 'joyeria'=>'Joyería') as $slug=>$name): ?>
            <a href="<?php echo fiesta_category_url($slug); ?>"><span class="category-name"><?php echo fiesta_category_icon($slug); ?><span><?php echo esc_html($name); ?></span></span><?php echo fiesta_arrow_svg(); ?></a>
        <?php endforeach; ?>
        </div>
    </section>
    <section id="el-local" class="local-section" aria-labelledby="local-title"><div><h2 id="local-title">La fiesta también<br>se vive en el local.</h2><p>Globos, disfraces, cotillón y regalos: vení a descubrir los detalles que van a alegrar tu próxima celebración.</p><a class="text-link" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Recorrer el catálogo <?php echo fiesta_arrow_svg(); ?></a></div><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/local-detalle.jpg'); ?>" alt="Detalle de la vidriera de la tienda, con globos metálicos, peluches y decoración de fiestas" width="3840" height="2160" loading="lazy"></section>
    <section class="instagram-note" aria-labelledby="instagram-title"><h2 id="instagram-title">Más ideas en Instagram.</h2><a class="button" href="https://www.instagram.com/lavidaenfiesta.uy/" target="_blank" rel="noopener noreferrer">@lavidaenfiesta.uy <?php echo fiesta_arrow_svg(); ?><span class="sr-only"> (abre una pestaña nueva)</span></a></section>
</main>
<?php get_footer(); ?>
