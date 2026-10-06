<?php
// Preview facts are explicit data, never inferred from the generated photograph.
function fiesta_costume_sizes($sizes = null) {
    $sizes = is_array($sizes) ? array_values(array_filter(array_map('trim', $sizes), 'strlen')) : array();
    return $sizes ?: array('Talle único');
}
function fiesta_costume_details() {
    if (wp_get_environment_type() !== 'local' || !is_product()) { return null; }
    $product = wc_get_product(get_queried_object_id());
    if (!$product) { return null; }
    $path = __DIR__ . '/assets/catalogo/disfraces.json';
    if (!is_file($path)) { return null; }
    $items = json_decode(ltrim(file_get_contents($path), "\xEF\xBB\xBF"), true);
    foreach (is_array($items) ? $items : array() as $item) {
        if ($item['codigo_archivo'] === $product->get_meta('_fiesta_reference')) {
            $item['talles_disponibles'] = fiesta_costume_sizes($item['talles_disponibles'] ?? null);
            return $item;
        }
    }
    return null;
}
add_action('woocommerce_single_product_summary', function () {
    $details = fiesta_costume_details();
    if (!$details) { return; }
    $sizes = $details['talles_disponibles'] ?? array();
    echo '<section class="fiesta-costume-details" aria-label="Talle y contenido del disfraz"><h2>Talle y medidas</h2>';
    if (count($sizes) > 1) {
        echo '<form class="fiesta-size-options"><label for="fiesta-talle">Elegí tu talle</label><select name="talle" id="fiesta-talle"><option value="">Elegí un talle</option>';
        foreach ($sizes as $size) { echo '<option value="' . esc_attr($size) . '">' . esc_html($size) . '</option>'; }
        echo '</select><p class="fiesta-size-selection" aria-live="polite" aria-atomic="true">Elegí un talle para ver tu selección.</p></form>';
    } elseif (count($sizes) === 1) {
        echo $sizes[0] === 'Talle único' ? '<p><strong>Talle único</strong></p>' : '<p>Talle del traje: <strong>' . esc_html($sizes[0]) . '</strong></p>';
    } else {
        echo '<p>Talle y disponibilidad por confirmar.</p>';
    }
    if (!empty($details['medidas_por_talle'])) {
        echo '<dl class="fiesta-size-measurements">';
        foreach ($details['medidas_por_talle'] as $size=>$measurements) { echo '<dt>' . esc_html($size) . '</dt><dd>' . esc_html($measurements) . '</dd>'; }
        echo '</dl>';
    } else { echo '<p class="fiesta-detail-note">Medidas del traje pendientes de confirmar.</p>'; }
    echo '<h2>Qué incluye</h2>';
    if (is_array($details['piezas_incluidas'])) {
        if ($details['piezas_incluidas']) {
            echo '<ul>';
            foreach ($details['piezas_incluidas'] as $piece) { echo '<li>' . esc_html($piece) . '</li>'; }
            echo '</ul>';
        } else { echo '<p>Sin accesorios adicionales.</p>'; }
    } else { echo '<p>Contenido del paquete por confirmar.</p>'; }
    if (!empty($details['accesorios_no_incluidos'])) {
        echo '<p>No incluye: ' . esc_html(implode(', ', $details['accesorios_no_incluidos'])) . '.</p>';
    }
    echo '<p class="fiesta-detail-note">La imagen es una recreación de referencia.';
    if (!is_array($details['piezas_incluidas'])) { echo ' Los accesorios incluidos se detallarán al confirmar el contenido del paquete.'; }
    echo '</p></section>';
}, 25);
add_filter('woocommerce_short_description', function ($description) {
    $details = fiesta_costume_details();
    return $details['descripcion_corta'] ?? $description;
}, 15);
add_action('wp_enqueue_scripts', function () {
    $details = fiesta_costume_details();
    if ($details && count($details['talles_disponibles'] ?? array()) > 1) {
        wp_enqueue_script('fiesta-sizes', get_stylesheet_directory_uri() . '/assets/costume-sizes.js', array(), filemtime(__DIR__ . '/assets/costume-sizes.js'), true);
    }
});
