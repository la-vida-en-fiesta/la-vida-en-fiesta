document.querySelectorAll('.fiesta-options').forEach((form) => {
    const fields = [...form.querySelectorAll('select')];
    const output = form.querySelector('.fiesta-selection');
    const reset = form.querySelector('.fiesta-reset');
    function update() {
        const number = form.querySelector('[name="numero"]').value;
        const color = form.querySelector('[name="color"]').value;
        output.textContent = number !== '' && color !== ''
            ? `Tu globo: número ${number}, ${color.toLocaleLowerCase('es-UY')} · $130.`
            : 'Elegí las dos opciones para ver tu selección.';
        reset.disabled = fields.every((field) => field.value === '');
    }
    fields.forEach((field) => field.addEventListener('change', update));
    form.addEventListener('reset', () => requestAnimationFrame(update));
    form.addEventListener('submit', (event) => event.preventDefault());
    update();
});
