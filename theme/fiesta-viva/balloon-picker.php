<?php
if (!defined('ABSPATH')) { exit; }
function fiesta_balloon_category_size() {
    foreach (array(16, 32, 40) as $size) {
        if (is_product_category(array('globos-numeros-' . $size . '-pulgadas', 'cumpleanos-globos-numeros-' . $size . '-pulgadas'))) { return $size; }
    }
    return 0;
}
function fiesta_is_32_balloon_category() {
    return is_product_category(array('globos-numeros-32-pulgadas', 'cumpleanos-globos-numeros-32-pulgadas'));
}
add_filter('woocommerce_cart_item_thumbnail', function ($thumbnail, $item) {
    $product = wc_get_product($item['product_id']);
    if (!$product || !in_array($product->get_meta('_fiesta_reference'), array('82644', 'fiesta-numero-16', 'fiesta-numero-40'), true)) { return $thumbnail; }
    $attributes = $item['variation'] ?? array();
    $number = $attributes['attribute_numero'] ?? '';
    $color = $attributes['attribute_color'] ?? '';
    if (!preg_match('/^[0-9]$/', (string)$number)) { return $thumbnail; }
    $label = 'Globo número ' . $number . ' ' . $color;
    if ($color === 'Dorado') {
        $file = '/assets/catalogo/globos-dorados/' . $number . '.png';
        if (!is_file(__DIR__ . $file)) { return $thumbnail; }
        $url = get_template_directory_uri() . $file . '?v=' . filemtime(__DIR__ . $file);
        return '<img class="balloon-cart-photo" src="' . esc_url($url) . '" alt="' . esc_attr($label) . '" width="80" height="80">';
    }
    $colors = array('Plateado'=>'silver', 'Verde'=>'green', 'Azul'=>'blue', 'Negro'=>'black', 'Rosa oro'=>'rose', 'Multicolor'=>'multi', 'Rojo'=>'red', 'Fucsia'=>'fuchsia');
    if (!isset($colors[$color])) { return $thumbnail; }
    $file = '/assets/catalogo/globos-colores/' . $colors[$color] . '-proveedor.png';
    if (!is_file(__DIR__ . $file)) { return $thumbnail; }
    $url = get_template_directory_uri() . $file . '?v=' . filemtime(__DIR__ . $file);
    $position = (((int)$number % 5) * 25) . '% ' . (intdiv((int)$number, 5) * 100) . '%';
    return '<span class="balloon-cart-atlas" role="img" aria-label="' . esc_attr($label) . '" style="background-image:url(' . esc_url($url) . ');background-position:' . esc_attr($position) . '"></span>';
}, 10, 2);
add_action('wp_enqueue_scripts', function () {
    if (fiesta_balloon_category_size()) {
        wp_enqueue_script('fiesta-balloon-picker', get_template_directory_uri() . '/assets/balloon-picker.js', array(), filemtime(__DIR__ . '/assets/balloon-picker.js'), true);
        wp_localize_script('fiesta-balloon-picker', 'fiestaBalloonCart', array(
            'url'=>WC_AJAX::get_endpoint('fiesta_add_balloon'),
            'nonce'=>wp_create_nonce('fiesta_add_balloon'),
        ));
    }
});
add_action('wc_ajax_fiesta_add_balloon', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fiesta_add_balloon')) {
        wp_send_json_error(array('message'=>'La sesión venció. Recarga la página para seguir agregando.'), 403);
    }
    $product_id = absint($_POST['product_id'] ?? 0);
    $variation_id = absint($_POST['variation_id'] ?? 0);
    $raw_quantity = wc_clean(wp_unslash($_POST['quantity'] ?? '1'));
    $quantity = filter_var($raw_quantity, FILTER_VALIDATE_INT, array('options'=>array('min_range'=>1)));
    $product = wc_get_product($product_id);
    $variation = wc_get_product($variation_id);
    $attributes = array(
        'attribute_color'=>sanitize_text_field(wp_unslash($_POST['attribute_color'] ?? '')),
        'attribute_numero'=>sanitize_text_field(wp_unslash($_POST['attribute_numero'] ?? '')),
    );
    if (!$quantity || !$product || !in_array($product->get_meta('_fiesta_reference'), array('82644', 'fiesta-numero-16', 'fiesta-numero-40'), true)
        || !$variation || !$variation->is_type('variation') || $variation->get_parent_id() !== $product_id
        || $variation->get_variation_attributes() != $attributes) {
        wp_send_json_error(array('message'=>'Revisa el número, el color y la cantidad seleccionados.'), 400);
    }
    try {
        $valid = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $attributes);
        $added = $valid && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $attributes);
        if (!$added) {
            $errors = wc_get_notices('error');
            $message = $errors ? wp_strip_all_tags($errors[0]['notice']) : 'No se pudo agregar esta selección. Revisa su disponibilidad.';
            wc_clear_notices();
            wp_send_json_error(array('message'=>$message), 400);
        }
        WC()->cart->calculate_totals();
        wp_send_json_success(array('count'=>WC()->cart->get_cart_contents_count()));
    } catch (Exception $error) {
        wp_send_json_error(array('message'=>wp_strip_all_tags($error->getMessage())), 400);
    }
});
function fiesta_32_balloon_picker() {
    $size = fiesta_balloon_category_size();
    $reference = $size === 32 ? '82644' : 'fiesta-numero-' . $size;
    $matches = get_posts(array('post_type'=>'product', 'post_status'=>'publish', 'numberposts'=>1, 'meta_key'=>'_fiesta_reference', 'meta_value'=>$reference));
    $product = $matches ? wc_get_product($matches[0]->ID) : false;
    if (!$product || !$product->is_type('variable')) { woocommerce_content(); return; }
    $variations = array();
    foreach ($product->get_children() as $id) {
        $variation = wc_get_product($id);
        if (!$variation) { continue; }
        $attributes = $variation->get_attributes();
        $variations[$attributes['color']][$attributes['numero']] = $id;
    }
    wc_print_notices();
    $colors = array('Dorado'=>'gold', 'Plateado'=>'silver', 'Verde'=>'green', 'Azul'=>'blue', 'Negro'=>'black', 'Rosa oro'=>'rose', 'Multicolor'=>'multi', 'Rojo'=>'red', 'Fucsia'=>'fuchsia');
    if ($size !== 32) { $colors = array('Dorado'=>'gold', 'Plateado'=>'silver'); }
    $gold_photos = array();
    for ($number = 0; $number <= 9; $number++) {
        $file = '/assets/catalogo/globos-dorados/' . $number . '.png';
        if (is_file(__DIR__ . $file)) { $gold_photos[$number] = get_template_directory_uri() . $file . '?v=' . filemtime(__DIR__ . $file); }
    }
    ?>
    <section class="balloon-catalog" aria-labelledby="balloon-catalog-title">
        <h1 id="balloon-catalog-title"><?php echo esc_html('Globos números de ' . $size . ' pulgadas'); ?></h1>
        <p>Elegí el color y seleccioná el número en su tarjeta.</p>
        <p class="fiesta-photo-note">Disponibilidad por confirmar. Imágenes de referencia recreadas para mostrar cada número y color.</p>
        <div class="balloon-catalog-grid<?php if ($size !== 32) { echo ' balloon-catalog-grid-two'; } ?>">
        <?php foreach ($colors as $name=>$swatch) : ?>
            <?php
            $atlas_file = '/assets/catalogo/globos-colores/' . $swatch . '.png';
            $supplier_atlas_file = '/assets/catalogo/globos-colores/' . $swatch . '-proveedor.png';
            if (is_file(__DIR__ . $supplier_atlas_file)) { $atlas_file = $supplier_atlas_file; }
            $atlas = is_file(__DIR__ . $atlas_file) ? get_template_directory_uri() . $atlas_file . '?v=' . filemtime(__DIR__ . $atlas_file) : '';
            ?>
            <form class="balloon-variant-card" data-color="<?php echo esc_attr($swatch); ?>" data-color-name="<?php echo esc_attr($name); ?>" data-size="<?php echo esc_attr($size); ?>" data-variations="<?php echo esc_attr(wp_json_encode($variations[$name] ?? array())); ?>" data-gold-photos="<?php echo esc_attr(wp_json_encode($gold_photos)); ?>" method="post">
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>">
                <input type="hidden" name="variation_id" value="<?php echo esc_attr($variations[$name][0] ?? 0); ?>">
                <input type="hidden" name="attribute_color" value="<?php echo esc_attr($name); ?>">
                <div class="balloon-card-visual">
                    <?php if ($atlas) : ?>
                        <div class="balloon-card-atlas" role="img" aria-label="<?php echo esc_attr('Globo número 0 ' . $name . ' de ' . $size . ' pulgadas, imagen de referencia'); ?>" data-atlas="<?php echo esc_url($atlas); ?>" style="background-image:url('<?php echo esc_url($atlas); ?>')"></div>
                    <?php endif; ?>
                    <?php if ($name === 'Dorado' && isset($gold_photos[0])) : ?>
                        <img class="balloon-card-photo" src="<?php echo esc_url($gold_photos[0]); ?>" alt="<?php echo esc_attr('Globo número 0 dorado de ' . $size . ' pulgadas, imagen de referencia'); ?>" width="1254" height="1254" decoding="async">
                    <?php endif; ?>
                    <b class="balloon-card-digit" aria-hidden="true" <?php if ($atlas || ($name === 'Dorado' && isset($gold_photos[0]))) { echo 'hidden'; } ?>>0</b>
                    <?php if (!$atlas && $name !== 'Dorado') : ?><span class="balloon-card-placeholder">Vista previa · Foto pendiente</span><?php endif; ?>
                </div>
                <h2>Globo número <?php echo esc_html($size); ?> pulgadas · <?php echo esc_html($name); ?></h2>
                <p class="balloon-card-price"><?php echo wp_kses_post($product->get_price_html()); ?></p>
                <fieldset><legend>Número</legend><div class="balloon-card-variants">
                    <?php for ($number = 0; $number <= 9; $number++) : ?>
                        <label><input type="radio" name="attribute_numero" value="<?php echo esc_attr($number); ?>" <?php checked($number, 0); ?> required><span><?php echo esc_html($number); ?></span></label>
                    <?php endfor; ?>
                </div></fieldset>
                <p class="balloon-card-selection" aria-live="polite" aria-atomic="true">Número 0 · <?php echo esc_html($name); ?></p>
                <label class="balloon-quantity-label" for="quantity-<?php echo esc_attr($swatch); ?>">Cantidad</label>
                <div class="balloon-quantity">
                    <button type="button" data-quantity-step="-1" aria-label="Reducir cantidad" disabled><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/></svg></button>
                    <input id="quantity-<?php echo esc_attr($swatch); ?>" name="quantity" type="number" min="1" step="1" value="1" required inputmode="numeric">
                    <button type="button" data-quantity-step="1" aria-label="Aumentar cantidad"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5v14"/></svg></button>
                </div>
                <button type="submit">Agregar al carrito</button>
            </form>
        <?php endforeach; ?>
        </div>
        <div class="balloon-cart-toast" role="status" aria-live="polite" aria-atomic="true" hidden>
            <p class="balloon-cart-message"></p>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>">Ver carrito</a>
            <button type="button" class="balloon-toast-close" aria-label="Cerrar aviso"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
        </div>
    </section>
    <?php
}
