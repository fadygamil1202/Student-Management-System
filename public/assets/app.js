// Confirm dialogs for destructive forms
document.querySelectorAll('form[data-confirm]').forEach(function (form) {
  form.addEventListener('submit', function (e) {
    if (!window.confirm(form.getAttribute('data-confirm'))) e.preventDefault();
  });
});

// QR code on the printable card
(function () {
  var el = document.getElementById('qr');
  if (el && window.qrcode) {
    var qr = window.qrcode(0, 'M');
    qr.addData(el.getAttribute('data-link'));
    qr.make();
    el.innerHTML = qr.createSvgTag({ cellSize: 4, margin: 0, scalable: true });
  }
  var btn = document.getElementById('print-btn');
  if (btn) btn.addEventListener('click', function () { window.print(); });
})();
