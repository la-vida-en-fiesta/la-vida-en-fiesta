document.querySelectorAll('.fiesta-size-options').forEach((form) => {
    const select = form.querySelector('select');
    const summary = form.querySelector('.fiesta-size-selection');
    select.addEventListener('change', () => {
        summary.textContent = select.value
            ? `Talle elegido: ${select.value}. Disponibilidad por confirmar.`
            : 'Elegí un talle para ver tu selección.';
    });
    form.addEventListener('submit', (event) => event.preventDefault());
});
