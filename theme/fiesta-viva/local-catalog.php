<?php
// Reproducible preview data. Never import automatically on a production site.
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || !class_exists('WC_Product_Simple')) { return; }
    $file = __DIR__ . '/assets/catalogo/productos.json';
    if (!is_file($file)) { return; }
    $raw = ltrim(file_get_contents($file), "\xEF\xBB\xBF");
    $hash = hash('sha256', 'variants-v2:' . $raw);
    if (get_option('fiesta_catalog_hash') === $hash) { return; }
    $items = json_decode($raw, true);
    if (!is_array($items)) { return; }
    update_option('woocommerce_price_num_decimals', 0);
    update_option('woocommerce_price_thousand_sep', '.');
    update_option('woocommerce_price_decimal_sep', ',');
    foreach ($items as $item) {
        $matches = get_posts(array('post_type'=>'product', 'post_status'=>'any', 'numberposts'=>1, 'meta_key'=>'_fiesta_reference', 'meta_value'=>$item['codigo_archivo']));
        $variable = !empty($item['opciones']['Número']) && !empty($item['opciones']['Color']);
        $product = $variable ? new WC_Product_Variable($matches ? $matches[0]->ID : 0) : ($matches ? wc_get_product($matches[0]->ID) : new WC_Product_Simple());
        $product->set_name($item['nombre']);
        $product->set_status('publish');
        $product->set_regular_price((string)$item['precio']);
        $product->set_manage_stock(false);
        $product->set_short_description($item['descripcion'] ?? '');
        $categories = array();
        $catalog_categories = $item['categorias'];
        if ($item['codigo_archivo'] === '82644') {
            $catalog_categories[] = 'cumpleanos-globos-numeros-32-pulgadas';
            $catalog_categories[] = 'globos-numeros-32-pulgadas';
        }
        $birthday_category = fiesta_birthday_product_category($item['codigo_archivo']);
        if ($birthday_category) { $catalog_categories[] = $birthday_category; }
        $decoration_category = fiesta_decoration_product_category($item['codigo_archivo']);
        if ($decoration_category) {
            $catalog_categories[] = 'decoracion';
            $catalog_categories[] = $decoration_category;
        }
        if ($birthday_category && strpos($birthday_category, 'cumpleanos-globos-') === 0) {
            $catalog_categories[] = 'globos';
            $catalog_categories[] = substr($birthday_category, strlen('cumpleanos-'));
        }
        foreach ($catalog_categories as $slug) {
            $term = get_term_by('slug', $slug, 'product_cat');
            if ($term) { $categories[] = $term->term_id; }
        }
        $product->set_category_ids($categories);
        $product->update_meta_data('_fiesta_reference', $item['codigo_archivo']);
        $product->update_meta_data('_fiesta_preview', 'yes');
        $product->update_meta_data('_fiesta_provisional_price', !empty($item['precio_provisional']) ? 'yes' : 'no');
        if (!empty($item['opciones'])) {
            $attributes = array();
            foreach ($item['opciones'] as $name=>$values) {
                $attribute = new WC_Product_Attribute();
                $attribute->set_name($name);
                $attribute->set_options($values);
                $attribute->set_visible(true);
                $attribute->set_variation($variable);
                $attributes[] = $attribute;
            }
            $product->set_attributes($attributes);
        }
        $id = $product->save();
        if ($variable) {
            $existing = array();
            foreach ($product->get_children() as $child_id) {
                $child = wc_get_product($child_id);
                if ($child) { $child_values = $child->get_attributes(); ksort($child_values); $existing[wp_json_encode($child_values)] = $child; }
            }
            foreach ($item['opciones']['Número'] as $number) {
                foreach ($item['opciones']['Color'] as $color) {
                    // WooCommerce sanitizes custom attribute names, including accents.
                    $values = array(sanitize_title('Número')=>(string)$number, sanitize_title('Color')=>$color);
                    ksort($values);
                    $key = wp_json_encode($values);
                    $variation = $existing[$key] ?? new WC_Product_Variation();
                    $variation->set_parent_id($id);
                    $variation->set_attributes($values);
                    $variation->set_regular_price((string)$item['precio']);
                    $variation->set_status('publish');
                    $variation->set_manage_stock(false);
                    $variation->update_meta_data('_fiesta_preview', 'yes');
                    $variation->save();
                }
            }
            WC_Product_Variable::sync($id);
        }
        if (!$product->get_image_id()) {
            $source = __DIR__ . '/assets/catalogo/' . basename($item['imagen']);
            if (!is_file($source)) { continue; }
            $upload = wp_upload_bits(basename($source), null, file_get_contents($source));
            if (!empty($upload['error'])) { continue; }
            $attachment = wp_insert_attachment(array('post_mime_type'=>'image/png', 'post_title'=>$item['nombre'], 'post_status'=>'inherit'), $upload['file'], $id, true);
            if (is_wp_error($attachment)) { continue; }
            update_post_meta($attachment, '_wp_attachment_image_alt', $item['nombre']);
            $product->set_image_id($attachment);
            $product->save();
        }
    }
    update_option('fiesta_catalog_hash', $hash);
}, 30);
add_filter('woocommerce_is_purchasable', function ($purchasable, $product) {
    return wp_get_environment_type() === 'local' && $product->get_meta('_fiesta_preview') === 'yes' && !fiesta_local_checkout_enabled() ? false : $purchasable;
}, 10, 2);
add_action('woocommerce_before_shop_loop', function () {
    if (wp_get_environment_type() === 'local') { echo '<p class="catalog-preview-note">Catálogo en preparación · Precios en pesos uruguayos. Stock por confirmar.</p>'; }
});
function fiesta_provisional_price_note() {
    global $product;
    if ($product && $product->get_meta('_fiesta_provisional_price') === 'yes') { echo '<p class="price-pending">Precio por confirmar</p>'; }
}
add_action('woocommerce_after_shop_loop_item_title', 'fiesta_provisional_price_note', 11);
add_action('woocommerce_single_product_summary', 'fiesta_provisional_price_note', 11);
require_once __DIR__ . '/product-options.php';
