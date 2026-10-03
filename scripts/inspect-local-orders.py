import sqlite3
db = sqlite3.connect('file:.local/wordpress/wp-content/database/.ht.sqlite?mode=ro', uri=True)
tables = [row[0] for row in db.execute("SELECT name FROM sqlite_master WHERE type='table'")]
if 'wp_wc_orders' in tables:
    print(db.execute('SELECT id,status,total_amount,payment_method FROM wp_wc_orders ORDER BY id DESC LIMIT 5').fetchall())
print(db.execute("SELECT ID,post_status FROM wp_posts WHERE post_type='shop_order' ORDER BY ID DESC LIMIT 5").fetchall())
