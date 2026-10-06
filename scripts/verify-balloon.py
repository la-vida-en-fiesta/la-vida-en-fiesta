import sqlite3
from collections import Counter

connection = sqlite3.connect('file:.local/wordpress/wp-content/database/.ht.sqlite?mode=ro', uri=True)
product = connection.execute("SELECT post_id FROM wp_postmeta WHERE meta_key='_fiesta_reference' AND meta_value='82644'").fetchone()[0]
rows = connection.execute("SELECT ID FROM wp_posts WHERE post_parent=? AND post_type='product_variation'", (product,)).fetchall()
combinations = []
prices = []
for (variation_id,) in rows:
    meta = dict(connection.execute('SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id=?', (variation_id,)))
    combinations.append((meta['attribute_numero'], meta['attribute_color']))
    prices.append(meta['_regular_price'])
assert len(rows) == 90, len(rows)
assert len(set(combinations)) == 90
assert set(n for n, c in combinations) == set('0123456789')
assert set(c for n, c in combinations) == {'Dorado', 'Plateado', 'Azul', 'Verde', 'Rojo', 'Negro', 'Fucsia', 'Multicolor', 'Rosa oro'}
assert set(prices) == {'130'}
print('PASS: 90 unique variants, numbers 0–9, nine colors, all UYU 130.')
