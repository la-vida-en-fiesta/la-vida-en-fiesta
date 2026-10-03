import sqlite3
db = sqlite3.connect('file:.local/wordpress/wp-content/database/.ht.sqlite?mode=ro', uri=True)
order = db.execute("SELECT id,status,total_amount,payment_method,transaction_id FROM wp_wc_orders WHERE payment_method='fiesta_test' ORDER BY id DESC LIMIT 1").fetchone()
assert order and order[1] == 'wc-on-hold' and order[2] == 260 and not order[4], order
metadata = dict(db.execute('SELECT meta_key,meta_value FROM wp_wc_orders_meta WHERE order_id=?', (order[0],)))
assert metadata['_fiesta_local_test'] == 'yes', metadata
items = db.execute("SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_id=? AND order_item_type='line_item'", (order[0],)).fetchall()
assert len(items) == 1, items
item_meta = dict(db.execute('SELECT meta_key,meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id=?', (items[0][0],)))
assert item_meta['_qty'] == '2' and float(item_meta['_line_total']) == 260, item_meta
assert item_meta['color'] == 'Verde' and item_meta['numero'] == '0', item_meta
print(f'PASS: local test order {order[0]}, 2 balloons 0/Verde, UYU260, on-hold, no real transaction.')
