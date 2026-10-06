<?php
if (!defined('ABSPATH')) { exit; }
function fiesta_catalog_groups() {
    return array(
        'Por celebración'=>array('cumpleanos'=>'Cumpleaños', 'halloween'=>'Halloween', 'navidad'=>'Navidad', 'baby-shower'=>'Baby shower', 'despedidas-de-soltera'=>'Despedidas de soltera', 'noche-de-la-nostalgia'=>'Noche de la Nostalgia'),
        'Por tipo de producto'=>array('globos'=>'Globos', 'decoracion'=>'Decoración', 'cotillon'=>'Cotillón', 'disfraces'=>'Disfraces y accesorios', 'regaleria'=>'Regalos', 'jugueteria'=>'Juguetes', 'perfumeria'=>'Perfumería', 'joyeria'=>'Joyería'),
    );
}
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || !taxonomy_exists('product_cat') || get_option('fiesta_catalog_access_v1')) { return; }
    foreach (array('globos'=>'Globos', 'decoracion'=>'Decoración') as $slug=>$name) {
        if (!term_exists($slug, 'product_cat')) { wp_insert_term($name, 'product_cat', array('slug'=>$slug)); }
    }
    $products = get_posts(array('post_type'=>'product', 'numberposts'=>-1, 'meta_key'=>'_fiesta_reference'));
    foreach ($products as $product) {
        $reference = get_post_meta($product->ID, '_fiesta_reference', true);
        $category = in_array($reference, array('80588','80589','80590','80587','82644'), true) ? 'globos' : '';
        if (!$category && preg_match('/^(Esqueleto colgante|Mantel|Sorbitos)/u', $product->post_title)) { $category = 'decoracion'; }
        if ($category) { wp_set_object_terms($product->ID, $category, 'product_cat', true); }
    }
    update_option('fiesta_catalog_access_v1', 1);
}, 40);
function fiesta_catalog_navigation() {
    if (!is_shop() && !is_product_category()) { return; }
    if (is_product_category()) {
        echo '<nav class="catalog-category-back" aria-label="Volver al catálogo"><a class="catalog-all" href="' . esc_url(wc_get_page_permalink('shop')) . '">Ver todas las categorías</a></nav>';
        fiesta_catalog_subcategories();
        return;
    }
    echo '<nav class="catalog-access" aria-label="Explorar el catálogo">';
    foreach (fiesta_catalog_groups() as $heading=>$categories) {
        echo '<section><h2>' . esc_html($heading) . '</h2><div class="catalog-top-level">';
        foreach ($categories as $slug=>$label) {
            $term = get_term_by('slug', $slug, 'product_cat');
            if (!$term) { continue; }
            $url = get_term_link($term);
            if (is_wp_error($url)) { continue; }
            $children = fiesta_category_children($term->term_id);
            if ($children) {
                echo '<details class="catalog-expand"><summary>' . esc_html($label) . '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg></summary><div class="catalog-expand-content">';
                fiesta_category_choices($children);
                echo '<a class="catalog-see-all" href="' . esc_url($url) . '">Ver todo en ' . esc_html($label) . '</a></div></details>';
            } else {
                echo '<a class="catalog-direct" href="' . esc_url($url) . '">' . esc_html($label) . fiesta_arrow_svg() . '</a>';
            }
        }
        echo '</div></section>';
    }
    if (!is_shop()) { echo '<a class="catalog-all" href="' . esc_url(wc_get_page_permalink('shop')) . '">Ver todos los productos</a>'; }
    echo '</nav>';
    fiesta_catalog_subcategories();
}
function fiesta_category_children($id) {
    $children = get_terms(array('taxonomy'=>'product_cat', 'parent'=>$id, 'hide_empty'=>false, 'orderby'=>'name'));
    if (is_wp_error($children)) { return array(); }
    usort($children, function ($a, $b) {
        return ((int)get_term_meta($a->term_id, 'fiesta_order', true) <=> (int)get_term_meta($b->term_id, 'fiesta_order', true)) ?: strcmp($a->name, $b->name);
    });
    return $children;
}
function fiesta_category_choice_link($term) {
    $url = get_term_link($term);
    if (is_wp_error($url)) { return; }
    $label = preg_match('/de (16|32|40) pulgadas$/u', $term->name, $match) ? $match[1] . ' pulgadas' : $term->name;
    echo '<a href="' . esc_url($url) . '"' . (is_product_category($term->term_id) ? ' aria-current="page"' : '') . '>' . esc_html($label) . '</a>';
}
function fiesta_category_choices($children) {
    $leaves = array();
    foreach ($children as $child) {
        $nested = fiesta_category_children($child->term_id);
        if (in_array($child->slug, array('globos-numeros', 'cumpleanos-globos-numeros'), true)) {
            $leaves[] = $child;
            continue;
        }
        if ($nested) {
            echo '<div class="catalog-inline-group"><h3><a href="' . esc_url(get_term_link($child)) . '">' . esc_html($child->name) . '</a></h3>';
            echo '<div class="catalog-access-links">';
            foreach ($nested as $leaf) { fiesta_category_choice_link($leaf); }
            echo '</div>';
            echo '</div>';
        } else { $leaves[] = $child; }
    }
    if ($leaves) {
        echo '<div class="catalog-access-links">';
        foreach ($leaves as $leaf) { fiesta_category_choice_link($leaf); }
        echo '</div>';
    }
}
function fiesta_number_size_cards($children) {
    $descriptions = array(16=>array('40,6', 'Formato pequeño'), 32=>array('81,3', 'Formato mediano'), 40=>array('101,6', 'Formato grande'));
    echo '<div class="balloon-size-cards">';
    foreach ($children as $child) {
        if (!preg_match('/de (16|32|40) pulgadas$/u', $child->name, $match)) { continue; }
        $url = get_term_link($child);
        if (is_wp_error($url)) { continue; }
        $size = (int)$match[1];
        $description = $descriptions[$size];
        echo '<a class="balloon-size-card" href="' . esc_url($url) . '"><strong>' . esc_html($size) . ' pulgadas</strong><span class="balloon-size-cm">Aprox. ' . esc_html($description[0]) . ' cm</span><span>' . esc_html($description[1]) . '</span><span class="balloon-size-action">Ver globos ' . fiesta_arrow_svg() . '</span></a>';
    }
    echo '</div>';
}
function fiesta_number_size_entry() {
    $current = get_queried_object();
    echo '<section class="balloon-size-entry" aria-labelledby="balloon-size-title"><h1 id="balloon-size-title">Globos números</h1><p>Elegí el tamaño para ver los colores y números.</p>';
    fiesta_number_size_cards(fiesta_category_children($current->term_id));
    echo '<p class="fiesta-photo-note">Equivalencias aproximadas. La medida del globo inflado puede variar.</p></section>';
}
function fiesta_catalog_subcategories() {
    if (!is_product_category()) { return; }
    $current = get_queried_object();
    if (!($current instanceof WP_Term)) { return; }
    $parent = $current->parent ? get_term($current->parent, 'product_cat') : null;
    $group = $current;
    $children = fiesta_category_children($current->term_id);
    if (!$children && $parent && !is_wp_error($parent)) {
        $group = $parent;
        $children = fiesta_category_children($parent->term_id);
    }
    if (!$children && !$parent) { return; }
    echo '<nav class="catalog-subcategories" aria-label="Subcategorías de ' . esc_attr($current->name) . '">';
    if ($parent && !is_wp_error($parent)) {
        $parent_url = get_term_link($parent);
        if (!is_wp_error($parent_url)) { echo '<a class="catalog-parent" href="' . esc_url($parent_url) . '">Volver a ' . esc_html($parent->name) . '</a>'; }
    }
    if ($children && !fiesta_number_size_selector()) {
        $sizes = in_array($group->slug, array('globos-numeros', 'cumpleanos-globos-numeros'), true);
        if ($sizes) {
            echo '<div class="catalog-size-switch"><span>Tamaño</span><div class="catalog-access-links catalog-size-links">';
            foreach ($children as $child) { fiesta_category_choice_link($child); }
            echo '</div></div>';
        } else {
            echo '<h2>Explorar ' . esc_html($group->name) . '</h2>';
            fiesta_category_choices($children);
        }
    }
    echo '</nav>';
}
function fiesta_number_size_selector() {
    return is_product_category(array('cumpleanos-globos-numeros', 'globos-numeros'));
}
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_number_sizes_v1')) { return; }
    foreach (array('cumpleanos-globos-numeros', 'globos-numeros') as $parent_slug) {
        $parent = get_term_by('slug', $parent_slug, 'product_cat');
        if (!$parent) { return; }
        foreach (array(16, 32, 40) as $size) {
            $slug = $parent_slug . '-' . $size . '-pulgadas';
            $term = get_term_by('slug', $slug, 'product_cat');
            $result = $term ? wp_update_term($term->term_id, 'product_cat', array('parent'=>$parent->term_id)) : wp_insert_term('Globos números de ' . $size . ' pulgadas', 'product_cat', array('slug'=>$slug, 'parent'=>$parent->term_id));
            if (is_wp_error($result)) { return; }
            update_term_meta($result['term_id'], 'fiesta_order', $size);
        }
    }
    foreach (get_posts(array('post_type'=>'product', 'numberposts'=>-1, 'meta_key'=>'_fiesta_reference', 'meta_value'=>'82644')) as $product) {
        $result = wp_set_object_terms($product->ID, array('cumpleanos-globos-numeros-32-pulgadas', 'globos-numeros-32-pulgadas'), 'product_cat', true);
        if (is_wp_error($result)) { return; }
    }
    update_option('fiesta_number_sizes_v1', 1);
}, 28);
function fiesta_birthday_subcategories() {
    return array('cumpleanos-globos-numeros'=>'Globos números', 'cumpleanos-descartables'=>'Descartables', 'cumpleanos-globos-lisos'=>'Globos lisos', 'cumpleanos-globos-disenos'=>'Globos con diseños', 'cumpleanos-globos-formas'=>'Globos con formas', 'cumpleanos-decoracion'=>'Decoración');
}
function fiesta_birthday_product_category($reference) {
    $categories = array('82644'=>'cumpleanos-globos-numeros', '82645'=>'cumpleanos-descartables', '82642'=>'cumpleanos-decoracion');
    return $categories[$reference] ?? null;
}
function fiesta_balloon_subcategories() {
    return array('globos-numeros'=>'Globos números', 'globos-lisos'=>'Globos lisos', 'globos-formas'=>'Globos con formas', 'globos-disenos'=>'Globos con diseños');
}
function fiesta_decoration_product_category($reference) {
    $categories = array('82642'=>'decoracion-articulos', '80674'=>'decoracion-articulos', '82645'=>'decoracion-descartables');
    return $categories[$reference] ?? null;
}
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_decoration_subcategories_v1')) { return; }
    $parent = get_term_by('slug', 'decoracion', 'product_cat');
    if (!$parent) {
        $old = get_term_by('slug', 'decoracion-y-cotillon', 'product_cat');
        $result = $old ? wp_update_term($old->term_id, 'product_cat', array('name'=>'Decoración', 'slug'=>'decoracion')) : wp_insert_term('Decoración', 'product_cat', array('slug'=>'decoracion'));
        if (is_wp_error($result)) { return; }
        $parent = get_term($result['term_id'], 'product_cat');
    }
    if (!term_exists('cotillon', 'product_cat')) {
        $result = wp_insert_term('Cotillón', 'product_cat', array('slug'=>'cotillon'));
        if (is_wp_error($result)) { return; }
    }
    $order = 0;
    foreach (array('decoracion-articulos'=>'Decoración', 'decoracion-descartables'=>'Descartables') as $slug=>$name) {
        $term = get_term_by('slug', $slug, 'product_cat');
        $result = $term ? wp_update_term($term->term_id, 'product_cat', array('parent'=>$parent->term_id)) : wp_insert_term($name, 'product_cat', array('slug'=>$slug, 'parent'=>$parent->term_id));
        if (is_wp_error($result)) { return; }
        update_term_meta($result['term_id'], 'fiesta_order', ++$order);
    }
    foreach (get_posts(array('post_type'=>'product', 'numberposts'=>-1, 'meta_key'=>'_fiesta_reference')) as $product) {
        $category = fiesta_decoration_product_category(get_post_meta($product->ID, '_fiesta_reference', true));
        if ($category) { wp_set_object_terms($product->ID, array('decoracion', $category), 'product_cat', true); }
    }
    update_option('fiesta_decoration_subcategories_v1', 1);
}, 27);
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_balloon_subcategories_v1')) { return; }
    $parent = get_term_by('slug', 'globos', 'product_cat');
    if (!$parent) {
        $created = wp_insert_term('Globos', 'product_cat', array('slug'=>'globos'));
        if (is_wp_error($created)) { return; }
        $parent = get_term($created['term_id'], 'product_cat');
    }
    $order = 0;
    foreach (fiesta_balloon_subcategories() as $slug=>$name) {
        $term = get_term_by('slug', $slug, 'product_cat');
        $result = $term ? wp_update_term($term->term_id, 'product_cat', array('parent'=>$parent->term_id)) : wp_insert_term($name, 'product_cat', array('slug'=>$slug, 'parent'=>$parent->term_id));
        if (is_wp_error($result)) { return; }
        update_term_meta($result['term_id'], 'fiesta_order', ++$order);
        $birthday_term = get_term_by('slug', 'cumpleanos-' . $slug, 'product_cat');
        if (!$birthday_term) { continue; }
        $products = get_objects_in_term($birthday_term->term_id, 'product_cat');
        if (is_wp_error($products)) { continue; }
        foreach ($products as $product_id) { wp_set_object_terms($product_id, array((int)$parent->term_id, (int)$result['term_id']), 'product_cat', true); }
    }
    update_option('fiesta_balloon_subcategories_v1', 1);
}, 26);
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_birthday_subcategories_v1')) { return; }
    $parent = get_term_by('slug', 'cumpleanos', 'product_cat');
    if (!$parent) { return; }
    $order = 0;
    foreach (fiesta_birthday_subcategories() as $slug=>$name) {
        $term = get_term_by('slug', $slug, 'product_cat');
        $result = $term ? wp_update_term($term->term_id, 'product_cat', array('parent'=>$parent->term_id)) : wp_insert_term($name, 'product_cat', array('slug'=>$slug, 'parent'=>$parent->term_id));
        if (is_wp_error($result)) { return; }
        update_term_meta($result['term_id'], 'fiesta_order', ++$order);
    }
    foreach (get_posts(array('post_type'=>'product', 'numberposts'=>-1, 'meta_key'=>'_fiesta_reference')) as $product) {
        $category = fiesta_birthday_product_category(get_post_meta($product->ID, '_fiesta_reference', true));
        if ($category) { wp_set_object_terms($product->ID, $category, 'product_cat', true); }
    }
    update_option('fiesta_birthday_subcategories_v1', 1);
}, 25);
