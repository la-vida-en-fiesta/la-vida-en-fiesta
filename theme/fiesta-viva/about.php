<?php
if (!defined('ABSPATH')) { exit; }
function fiesta_about_description() {
    return '<p>Somos La Vida en Fiesta, una sociedad de emprendedores cubanos en Uruguay que comparte una pasión: acompañarte a celebrar los momentos especiales de la vida.</p><p>Llevamos más de un año en el mercado y te recibimos en nuestro local de Juncal 1412, esquina Rincón, en Ciudad Vieja, a solo una cuadra de Plaza Independencia.</p><p>Nos caracteriza el buen trato y la atención cercana. Te brindamos ayuda personalizada para preparar tus fiestas y eventos, elegir los detalles y darle forma a tus ideas.</p><p>Ya sea para un cumpleaños, una fecha especial o una reunión para celebrar, queremos que encuentres en nuestro local ideas, cotillón, disfraces y regalos, junto con alguien dispuesto a ayudarte.</p>';
}
// Create the editable information page in the local development store.
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_about_initialized')) { return; }
    $page = get_page_by_path('quienes-somos');
    $id = $page ? $page->ID : wp_insert_post(array(
        'post_type'=>'page', 'post_status'=>'publish', 'post_name'=>'quienes-somos',
        'post_title'=>'Quiénes somos',
        'post_content'=>fiesta_about_description(),
    ), true);
    if (!is_wp_error($id) && $id) { update_option('fiesta_about_initialized', 1); }
});
// Apply the owner's supplied story once, keeping subsequent page edits intact.
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_about_story_v1')) { return; }
    $page = get_page_by_path('quienes-somos');
    if (!$page) { return; }
    $result = wp_update_post(array('ID'=>$page->ID, 'post_content'=>fiesta_about_description()), true);
    if (is_wp_error($result) || !$result) { return; }
    set_theme_mod('fiesta_local_address', 'Juncal 1412, esquina Rincón. Ciudad Vieja, Montevideo. A una cuadra de Plaza Independencia.');
    update_option('fiesta_about_story_v1', 1);
}, 20);
function fiesta_about_url() {
    $page = get_page_by_path('quienes-somos');
    return $page ? get_permalink($page) : home_url('/quienes-somos/');
}
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_about_hours_v1')) { return; }
    set_theme_mod('fiesta_local_hours', "Lunes a viernes: de 9:00 a 19:00.\nSábados y domingos: apertura en fechas de fiestas o eventos. Consultá el horario para cada ocasión.");
    update_option('fiesta_about_hours_v1', 1);
}, 20);
add_action('customize_register', function ($customizer) {
    $customizer->add_section('fiesta_local', array('title'=>'Datos del local', 'priority'=>35));
    foreach (array('address'=>'Dirección', 'hours'=>'Horarios', 'phone'=>'Teléfono', 'whatsapp'=>'WhatsApp (código de país y número)') as $key=>$label) {
        $name = 'fiesta_local_' . $key;
        $customizer->add_setting($name, array('default'=>'', 'sanitize_callback'=>'sanitize_textarea_field'));
        $customizer->add_control($name, array('section'=>'fiesta_local', 'label'=>$label, 'type'=>in_array($key, array('address','hours'), true) ? 'textarea' : 'text'));
    }
});
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_about_whatsapp_v1')) { return; }
    set_theme_mod('fiesta_local_whatsapp', '59897192690');
    set_theme_mod('fiesta_local_phone', '097 192 690');
    update_option('fiesta_about_whatsapp_v1', 1);
}, 20);
