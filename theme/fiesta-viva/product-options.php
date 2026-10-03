<?php
function fiesta_is_balloon_preview() {
    $preview_product = is_product() ? wc_get_product(get_queried_object_id()) : false;
    return wp_get_environment_type() === 'local' && $preview_product instanceof WC_Product && $preview_product->get_meta('_fiesta_reference') === '82644';
}
add_action('wp', function () {
    if (!is_product() || !fiesta_is_balloon_preview() || fiesta_local_checkout_enabled()) { return; }
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    add_action('woocommerce_single_product_summary', 'fiesta_balloon_options', 30);
});
function fiesta_balloon_options() {
    global $product;
    echo '<form class="fiesta-options" aria-label="Elegir globo"><h2>Armá tu celebración</h2><p>Elegí un número y un color. Cada globo cuesta $130.</p><div class="fiesta-options-fields">';
    $attributes = array_values($product->get_attributes());
    usort($attributes, function ($a, $b) { return ($a->get_name() === 'Número' ? 0 : 1) <=> ($b->get_name() === 'Número' ? 0 : 1); });
    foreach ($attributes as $attribute) {
        $name = $attribute->get_name();
        $key = sanitize_title($name);
        echo '<div><label for="fiesta-' . esc_attr($key) . '">' . esc_html($name) . '</label><select id="fiesta-' . esc_attr($key) . '" name="' . esc_attr($key) . '"><option value="">Elegí ' . ($name === 'Color' ? 'un color' : 'un número') . '</option>';
        foreach ($attribute->get_options() as $value) { echo '<option value="' . esc_attr($value) . '">' . esc_html($value) . '</option>'; }
        echo '</select></div>';
    }
    echo '</div><p class="fiesta-selection" aria-live="polite" aria-atomic="true">Elegí las dos opciones para ver tu selección.</p><button class="fiesta-reset" type="reset">Limpiar selección</button><p class="fiesta-stock-note">Disponibilidad por confirmar. La compra todavía no está habilitada.</p></form><p class="fiesta-photo-note">La imagen muestra el número 3 dorado y se mantiene como referencia al elegir otras opciones.</p>';
}
add_action('wp_enqueue_scripts', function () {
    if (is_product() && fiesta_is_balloon_preview() && !fiesta_local_checkout_enabled()) {
        wp_enqueue_script('fiesta-options', get_stylesheet_directory_uri() . '/assets/product-options.js', array(), filemtime(__DIR__ . '/assets/product-options.js'), true);
    }
});
add_filter('woocommerce_short_description', function ($description) {
    return fiesta_is_balloon_preview() ? 'Globo número de 32 pulgadas para cumpleaños y celebraciones.' : $description;
});
add_filter('wc_product_sku_enabled', function ($enabled) {
    return fiesta_is_balloon_preview() ? false : $enabled;
});
add_action('woocommerce_single_product_summary', function () {
    if (fiesta_local_checkout_enabled() && fiesta_is_balloon_preview()) { echo '<p class="fiesta-photo-note">La imagen muestra el 3 dorado como referencia, también al elegir otras opciones.</p>'; }
}, 31);
