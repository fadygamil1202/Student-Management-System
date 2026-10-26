<div class="print-page">
  <div class="no-print print-help card">
    <h1><?= e(t('card.title')) ?></h1>
    <p><?= e(t('card.help_nfc')) ?></p>
    <p class="mono" dir="ltr"><?= e($link) ?></p>
    <button class="btn btn-primary" type="button" id="print-btn"><?= e(t('card.print_now')) ?></button>
  </div>

  <div class="id-card">
    <div class="id-card-top">
      <span class="id-cross" aria-hidden="true">+</span>
      <span><?= e(t('card.heading')) ?></span>
    </div>
    <div class="id-card-body">
      <div>
        <div class="id-name"><?= e($student['first_name']) ?></div>
        <div class="id-text"><?= e(t('card.scan_text')) ?></div>
      </div>
      <div id="qr" class="qr" data-link="<?= e($link) ?>" role="img" aria-label="<?= e(t('card.qr_label')) ?>"></div>
    </div>
  </div>
</div>
<script src="/assets/qrcode.js"></script>
<script src="/assets/app.js"></script>
