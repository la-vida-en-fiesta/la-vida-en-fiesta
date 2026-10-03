from pathlib import Path
from PIL import Image
assets = Path('theme/fiesta-viva/assets')
for name in ['logo-halloween.png', 'halloween-decoration.png', 'halloween-intro-pumpkin.png']:
    with Image.open(assets / name) as im:
        assert im.mode == 'RGBA', (name, im.mode)
        alpha = im.getchannel('A')
        assert alpha.getextrema() == (0, 255), (name, alpha.getextrema())
        print(name, im.size, 'RGBA con transparencia real')
