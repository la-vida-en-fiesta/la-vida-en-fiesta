document.querySelectorAll('[data-page-back]').forEach(link => {
  link.addEventListener('click', event => {
    if (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
    if (window.history.length > 1) {
      event.preventDefault();
      window.history.back();
    }
  });
});
