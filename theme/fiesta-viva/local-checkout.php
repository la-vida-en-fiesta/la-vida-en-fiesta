<?php
function fiesta_local_checkout_enabled() {
    return wp_get_environment_type() === 'local' && get_option('fiesta_local_checkout_enabled') === 'yes';
}
add_action('wp_enqueue_scripts', function () { if (fiesta_local_checkout_enabled()) { wp_enqueue_script('wc-cart-fragments'); } });
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    if (fiesta_local_checkout_enabled()) {
        $fragments['a.cart-link'] = '<a class="cart-link" href="' . esc_url(wc_get_cart_url()) . '">Carrito (' . esc_html(WC()->cart->get_cart_contents_count()) . ')</a>';
    }
    return $fragments;
});
add_filter('woocommerce_get_privacy_policy_text', function ($text, $type) {
    return fiesta_local_checkout_enabled() ? 'Usá datos ficticios para esta prueba local. No se envían correos ni se realizan cobros.' : $text;
}, 10, 2);
add_action('init', function () {
    if (wp_get_environment_type() !== 'local' || !class_exists('WooCommerce') || get_option('fiesta_local_checkout_initialized')) { return; }
    foreach (array('woocommerce_cart_page_id'=>'[woocommerce_cart]', 'woocommerce_checkout_page_id'=>'[woocommerce_checkout]') as $option=>$content) {
        $id = (int)get_option($option);
        if ($id) { wp_update_post(array('ID'=>$id, 'post_content'=>$content)); }
    }
    update_option('woocommerce_enable_guest_checkout', 'yes');
    update_option('fiesta_local_checkout_enabled', 'yes');
    update_option('fiesta_local_checkout_initialized', 1);
}, 35);
add_action('wp_body_open', function () {
    if (fiesta_local_checkout_enabled()) { echo '<p class="local-test-banner">Modo de prueba local · Sin cobros ni correos. Los pedidos y el envío son simulados.</p>'; }
});
add_filter('pre_wp_mail', function ($result) { return fiesta_local_checkout_enabled() ? true : $result; });
add_filter('woocommerce_package_rates', function ($rates, $package) {
    return fiesta_local_checkout_enabled() ? array('fiesta_test_shipping'=>new WC_Shipping_Rate('fiesta_test_shipping', 'Envío simulado (solo prueba)', 0, array(), 'fiesta_test_shipping')) : $rates;
}, 100, 2);
add_filter('woocommerce_payment_gateways', function ($gateways) {
    if (wp_get_environment_type() !== 'local') { return $gateways; }
    if (!class_exists('Fiesta_Local_Test_Gateway')) {
        class Fiesta_Local_Test_Gateway extends WC_Payment_Gateway {
            public function __construct() {
                $this->id = 'fiesta_test';
                $this->method_title = 'Pago simulado local';
                $this->title = 'Pago de prueba · sin cobro';
                $this->description = 'Genera un pedido de prueba. No solicita tarjetas ni realiza pagos.';
                $this->enabled = 'yes';
                $this->has_fields = false;
                $this->supports = array('products');
            }
            public function is_available() { return fiesta_local_checkout_enabled(); }
            public function process_payment($order_id) {
                if (!fiesta_local_checkout_enabled()) { return array('result'=>'failure'); }
                $order = wc_get_order($order_id);
                if (!$order) { return array('result'=>'failure'); }
                $order->update_meta_data('_fiesta_local_test', 'yes');
                $order->save();
                $order->update_status('on-hold', 'Pedido de prueba local. Sin cobro, correos ni sincronización de stock.');
                WC()->cart->empty_cart();
                return array('result'=>'success', 'redirect'=>$this->get_return_url($order));
            }
        }
    }
    $gateways[] = 'Fiesta_Local_Test_Gateway';
    return $gateways;
});
add_filter('woocommerce_can_reduce_order_stock', function ($allowed, $order) { return fiesta_local_checkout_enabled() ? false : $allowed; }, 100, 2);
add_filter('woocommerce_order_button_text', function ($label) { return fiesta_local_checkout_enabled() ? 'Crear pedido de prueba' : $label; });
add_filter('woocommerce_thankyou_order_received_text', function ($text, $order) {
    return $order && $order->get_meta('_fiesta_local_test') === 'yes' ? 'Pedido de prueba recibido. No se realizó ningún cobro.' : $text;
}, 10, 2);
