<?php
if (!defined('ABSPATH')) { exit; }
function fiesta_how_to_buy_content() {
    return '<p>Preparar tu fiesta es fácil. Elige los productos que te gustan y arma tu pedido desde el carrito.</p>
<h2>Compra paso a paso</h2>
<ol><li><strong>Explora el catálogo.</strong> Busca por celebración o por tipo de producto. Para los globos números, elige el tamaño, el color y el número.</li>
<li><strong>Agrega al carrito.</strong> Ajusta la cantidad con los botones de subir o bajar y pulsa “Agregar al carrito”. Verás un aviso de confirmación y podrás seguir agregando productos.</li>
<li><strong>Revisa tu selección.</strong> Entra en “Ver carrito” o en el carrito de la cabecera. Comprueba los productos, las cantidades y el total. Puedes eliminar un producto con la papelera roja o volver al catálogo con “Seguir comprando”.</li>
<li><strong>Continúa con tu pedido.</strong> Cuando tengas todo listo, pulsa “Finalizar compra” y completa los datos solicitados.</li></ol>
<h2>Medios de pago</h2>
<ul><li>Transferencia bancaria.</li><li>Efectivo.</li><li>Depósito en Abitab o Redpagos.</li><li>Tarjeta de crédito o débito.</li><li>Mercado Pago.</li></ul>
<p>Para consultar los datos de transferencia o depósito y coordinar tu pago, escríbenos por WhatsApp. También podemos ayudarte a elegir los productos para tu fiesta.</p>' . fiesta_shipping_information();
}
function fiesta_shipping_information() {
    return '<h2 id="envios-montevideo">Envíos a todo Montevideo</h2>
<p>El costo del envío depende de la zona. Antes de confirmar tu pedido, escríbenos por WhatsApp e indícanos tu dirección o zona para cotizar el envío hasta donde te encuentras.</p>
<p>Puedes pagar antes del envío o al recibir tu pedido.</p>
<p><strong>Envío gratis dentro de Montevideo en compras a partir de $2.500.</strong></p>' . fiesta_pickup_information();
}
function fiesta_pickup_information() {
    return '<h2 id="retiro-local">Retiro gratis en el local</h2><p>Puedes retirar tu pedido sin costo en <strong>Juncal 1412, esquina Rincón, Ciudad Vieja, Montevideo</strong>, a una cuadra de Plaza Independencia.</p><p>Te esperamos de lunes a viernes de 9:00 a 19:00. Para retirar en sábados o domingos en fechas de fiestas o eventos, consulta primero el horario por WhatsApp.</p>';
}
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_how_to_buy_initialized')) { return; }
    $page = get_page_by_path('como-comprar');
    $id = $page ? $page->ID : wp_insert_post(array('post_type'=>'page', 'post_status'=>'publish', 'post_name'=>'como-comprar', 'post_title'=>'Cómo comprar', 'post_content'=>fiesta_how_to_buy_content()), true);
    if ($id && !is_wp_error($id)) { update_option('fiesta_how_to_buy_initialized', 1); }
});
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_shipping_information_v1')) { return; }
    $page = get_page_by_path('como-comprar');
    if (!$page) { return; }
    if (strpos($page->post_content, 'id="envios-montevideo"') === false) {
        $result = wp_update_post(array('ID'=>$page->ID, 'post_content'=>$page->post_content . fiesta_shipping_information()), true);
        if (!$result || is_wp_error($result)) { return; }
    }
    update_option('fiesta_shipping_information_v1', 1);
}, 20);
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_shipping_threshold_v2')) { return; }
    $page = get_page_by_path('como-comprar');
    if (!$page) { return; }
    $result = wp_update_post(array('ID'=>$page->ID, 'post_content'=>str_replace('compras superiores a $2.500', 'compras a partir de $2.500', $page->post_content)), true);
    if ($result && !is_wp_error($result)) { update_option('fiesta_shipping_threshold_v2', 1); }
}, 21);
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || get_option('fiesta_pickup_information_v1')) { return; }
    $page = get_page_by_path('como-comprar');
    if (!$page) { return; }
    if (strpos($page->post_content, 'id="retiro-local"') === false) {
        $result = wp_update_post(array('ID'=>$page->ID, 'post_content'=>$page->post_content . fiesta_pickup_information()), true);
        if (!$result || is_wp_error($result)) { return; }
    }
    update_option('fiesta_pickup_information_v1', 1);
}, 22);
function fiesta_how_to_buy_url() {
    $page = get_page_by_path('como-comprar');
    return $page ? get_permalink($page) : home_url('/como-comprar/');
}
