<?php
if (!defined('ABSPATH')) { exit; }
require_once __DIR__ . '/seasons.php';
require_once __DIR__ . '/about.php';
require_once __DIR__ . '/how-to-buy.php';
require_once __DIR__ . '/catalog-navigation.php';
require_once __DIR__ . '/balloon-picker.php';
function fiesta_arrow_svg() {
    return '<svg aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18 18 6M6 6h12v12"/></svg>';
}
function fiesta_category_icon($slug) {
    $icons = array(
        'baby-shower'=>'<path d="M20 25v13m10-19v19M11 23l4 15" fill="none" stroke="#292334" stroke-width="1.3"/><ellipse cx="20" cy="15" rx="8" ry="11" fill="#FFD75E"/><ellipse cx="31" cy="12" rx="7" ry="10" fill="#B51F59"/><ellipse cx="10" cy="15" rx="7" ry="10" fill="#5BD4C7"/><path d="m6 11 2-3m9 0 2-2m9 0 2-2" fill="none" stroke="white" stroke-width="2" stroke-linecap="round"/>',
        'despedidas-de-soltera'=>'<circle cx="15" cy="26" r="10" fill="#FFD75E"/><circle cx="25" cy="26" r="10" fill="none" stroke="#B51F59" stroke-width="4"/><path d="m19 6 6-3 6 3-6 9z" fill="#5BD4C7"/><circle cx="15" cy="26" r="6" fill="#FFF9F1"/>',
        'halloween'=>'<path d="m20 13 2-8 5 1" fill="none" stroke="#5BD4C7" stroke-width="4"/><path d="M20 13C1 4-3 36 16 36h8c19 0 15-32-4-23z" fill="#FF775E"/><path d="m9 23 5-5 4 5m5 0 4-5 5 5M11 28l6 4 5-3 6 3 4-4" fill="#292334"/>',
        'navidad'=>'<path d="M18 35h5v5h-5z" fill="#FF775E"/><path d="m20 4 12 16h-5l9 14H4l9-14H8z" fill="#5BD4C7"/><circle cx="17" cy="21" r="2.4" fill="#B51F59"/><circle cx="26" cy="29" r="2.4" fill="#B51F59"/><path d="m20 1 2 4 5 1-4 3 1 4-4-2-4 2 1-4-4-3 5-1z" fill="#FFD75E"/>',
        'noche-de-la-nostalgia'=>'<circle cx="20" cy="20" r="17" fill="#292334"/><circle cx="20" cy="20" r="8" fill="#B51F59"/><circle cx="20" cy="20" r="2" fill="#FFF9F1"/><path d="M8 20a12 12 0 0 1 12-12M32 20a12 12 0 0 1-12 12" fill="none" stroke="#5BD4C7" stroke-width="1.5"/>',
        'perfumeria'=>'<path d="M15 3h10v6H15z" fill="#FFD75E"/><path d="M12 9h16v5H12z" fill="#292334"/><rect x="7" y="14" width="26" height="24" rx="6" fill="#5BD4C7"/><rect x="12" y="22" width="16" height="9" rx="2" fill="#FFF9F1"/><path d="M11 19v12" stroke="white" stroke-width="2" stroke-linecap="round"/>',
        'regaleria'=>'<rect x="5" y="16" width="30" height="22" rx="2" fill="#5BD4C7"/><rect x="3" y="13" width="34" height="7" rx="2" fill="#B51F59"/><path d="M17 14h6v24h-6z" fill="#FFD75E"/><path d="M20 14C-1 11 12-6 20 14c8-20 21-3 0 0z" fill="none" stroke="#FFD75E" stroke-width="4"/>',
        'jugueteria'=>'<path d="m20 3 5 11 12 2-9 9 2 12-10-6-10 6 2-12-9-9 12-2z" fill="#FFD75E"/><ellipse cx="15" cy="20" rx="1.7" ry="2.5" fill="#292334"/><ellipse cx="25" cy="20" rx="1.7" ry="2.5" fill="#292334"/><path d="M16 26q4 4 8 0" fill="none" stroke="#292334" stroke-width="1.7" stroke-linecap="round"/><circle cx="10" cy="25" r="2" fill="#FF775E"/><circle cx="30" cy="25" r="2" fill="#FF775E"/>',
        'joyeria'=>'<path d="m8 8 24 0 6 10-18 20L2 18z" fill="#5BD4C7"/><path d="M2 18h36M8 8l7 10 5 20 5-20 7-10M15 18l5-10 5 10" fill="none" stroke="#FFF9F1" stroke-width="1.5"/>'
    );
    return isset($icons[$slug]) ? '<svg class="category-icon" aria-hidden="true" focusable="false" width="44" height="44" viewBox="-3 -3 46 46">'.$icons[$slug].'</svg>' : '';
}
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
});
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('fiesta-viva', get_stylesheet_uri(), array(), filemtime(get_stylesheet_directory() . '/style.css'));
    wp_enqueue_script('fiesta-navigation', get_template_directory_uri() . '/assets/navigation.js', array(), filemtime(__DIR__ . '/assets/navigation.js'), true);
});
require_once __DIR__ . '/local-checkout.php';
add_filter('woocommerce_available_payment_gateways', function ($gateways) {
    return wp_get_environment_type() === 'local' ? array_intersect_key($gateways, array('fiesta_test'=>true)) : $gateways;
});
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
require_once __DIR__ . '/local-catalog.php';
require_once __DIR__ . '/costume-details.php';
