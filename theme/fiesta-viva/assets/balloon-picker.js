let balloonCartPending = false;
const balloonToast = document.querySelector('.balloon-cart-toast');
if (balloonToast) document.body.appendChild(balloonToast);
const showBalloonNotice = (message, error = false) => {
  balloonToast.querySelector('.balloon-cart-message').textContent = message;
  balloonToast.classList.toggle('is-error', error);
  balloonToast.hidden = false;
};
balloonToast?.querySelector('.balloon-toast-close').addEventListener('click', () => { balloonToast.hidden = true; });
document.querySelectorAll('.balloon-variant-card').forEach(form => {
  const photos = JSON.parse(form.dataset.goldPhotos || '{}');
  const image = form.querySelector('.balloon-card-photo');
  const atlas = form.querySelector('.balloon-card-atlas');
  const digit = form.querySelector('.balloon-card-digit');
  const output = form.querySelector('.balloon-card-selection');
  const variations = JSON.parse(form.dataset.variations || '{}');
  const variationId = form.querySelector('[name="variation_id"]');
  const quantity = form.querySelector('[name="quantity"]');
  const decrease = form.querySelector('[data-quantity-step="-1"]');
  const updateQuantity = () => { decrease.disabled = Number(quantity.value) <= 1; };
  form.querySelectorAll('[data-quantity-step]').forEach(button => {
    button.addEventListener('click', () => {
      quantity.value = Math.max(1, (Number(quantity.value) || 1) + Number(button.dataset.quantityStep));
      updateQuantity();
    });
  });
  quantity.addEventListener('input', updateQuantity);
  quantity.addEventListener('change', () => {
    if (!quantity.value || Number(quantity.value) < 1) quantity.value = 1;
    updateQuantity();
  });
  const selected = () => form.querySelector('[name="attribute_numero"]:checked').value;
  form.addEventListener('submit', async event => {
    if (typeof fiestaBalloonCart === 'undefined') return;
    event.preventDefault();
    if (balloonCartPending || !form.reportValidity()) return;
    balloonCartPending = true;
    const submit = form.querySelector('[type="submit"]');
    const submits = document.querySelectorAll('.balloon-variant-card [type="submit"]');
    const body = new FormData(form);
    body.set('product_id', body.get('add-to-cart'));
    body.delete('add-to-cart');
    body.set('nonce', fiestaBalloonCart.nonce);
    const amount = quantity.value;
    const number = selected();
    submits.forEach(button => { button.disabled = true; });
    submit.textContent = 'Agregando…';
    form.setAttribute('aria-busy', 'true');
    try {
      const response = await fetch(fiestaBalloonCart.url, { method: 'POST', body, credentials: 'same-origin' });
      const result = await response.json();
      if (!result.success) {
        showBalloonNotice(result.data?.message || 'No se pudo agregar. Vuelve a intentarlo.', true);
        return;
      }
      document.querySelectorAll('a.cart-link').forEach(link => { link.textContent = `Carrito (${result.data.count})`; });
      if (window.jQuery) window.jQuery(document.body).trigger('wc_fragment_refresh');
      showBalloonNotice(`${amount} ${Number(amount) === 1 ? 'globo agregado' : 'globos agregados'} con éxito · Número ${number}, ${form.dataset.colorName}, ${form.dataset.size} pulgadas.`);
    } catch {
      showBalloonNotice('No pudimos confirmar la operación. Revisa el carrito antes de volver a intentarlo.', true);
    } finally {
      balloonCartPending = false;
      submits.forEach(button => { button.disabled = false; });
      submit.textContent = 'Agregar al carrito';
      form.removeAttribute('aria-busy');
    }
  });
  const updateAtlas = () => {
    const number = Number(selected());
    atlas.style.backgroundPosition = `${(number % 5) * 25}% ${Math.floor(number / 5) * 100}%`;
    atlas.setAttribute('aria-label', `Globo número ${number} ${form.dataset.colorName} de ${form.dataset.size || 32} pulgadas, imagen de referencia`);
  };
  if (atlas) {
    updateAtlas();
    const preload = new Image();
    preload.addEventListener('error', () => { atlas.hidden = true; digit.hidden = false; });
    preload.src = atlas.dataset.atlas;
  }
  if (image) {
    image.addEventListener('load', () => {
      const current = image.dataset.number === selected();
      image.hidden = !current;
      digit.hidden = current;
    });
    image.addEventListener('error', () => { image.hidden = true; digit.hidden = false; });
    image.dataset.number = selected();
  }
  form.addEventListener('change', () => {
    const number = selected();
    digit.textContent = number;
    output.textContent = `Número ${number} · ${form.dataset.colorName}`;
    variationId.value = variations[number] || 0;
    if (atlas) updateAtlas();
    if (!image) return;
    const url = photos[number];
    image.hidden = true;
    digit.hidden = false;
    if (url) {
      image.dataset.number = number;
      image.alt = `Globo número ${number} dorado de ${form.dataset.size || 32} pulgadas, imagen de referencia`;
      if (image.getAttribute('src') !== url) image.src = url;
      else if (image.complete && image.naturalWidth) { image.hidden = false; digit.hidden = true; }
    }
  });
});
