<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="skip-link" href="#contenido">Saltar al contenido</a>
<?php $season = fiesta_current_season(); ?>
<header class="site-header"><a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="La Vida en Fiesta — Inicio"><img class="brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/' . ($season ? $season['logo'] : 'logo-la-vida-en-fiesta.png')); ?>" alt="Logo de La Vida en Fiesta" width="84" height="84"><span aria-hidden="true">La Vida<br>en Fiesta</span></a>
<form class="search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"><label class="sr-only" for="busqueda">Buscar productos</label><input id="busqueda" name="s" type="search" placeholder="¿Qué estás buscando?" value="<?php echo esc_attr(get_search_query()); ?>"><input type="hidden" name="post_type" value="product"><button type="submit">Buscar</button></form>
<a class="catalog-link" href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/')); ?>">Catálogo</a>
<?php if (function_exists('fiesta_local_checkout_enabled') && fiesta_local_checkout_enabled()) : ?><a class="cart-link" href="<?php echo esc_url(wc_get_cart_url()); ?>">Carrito (<?php echo esc_html(WC()->cart ? WC()->cart->get_cart_contents_count() : 0); ?>)</a><?php endif; ?></header>
<nav class="entry-nav" aria-label="La tienda y la temporada"><a href="<?php echo esc_url(home_url('/#el-local')); ?>">El local</a><?php if ($season) : ?><a class="season-link" href="<?php echo esc_url(fiesta_event_url($season['slug'])); ?>"><?php echo esc_html($season['name']); ?> <span><?php echo esc_html($season['label']); ?></span></a><?php endif; ?><?php if ($season && $season['slug'] === 'halloween') : ?><button class="effects-toggle" type="button" aria-pressed="false" hidden>Pausar efectos</button><?php endif; ?></nav>
