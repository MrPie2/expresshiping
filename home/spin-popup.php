<?php if (!isset($lin) || $lin === 'index'): ?>
<link rel="stylesheet" href="assets/css/spin-win.css?v=3">
<span style="position:fixed;right:10px;bottom:10px;z-index:100000;background:#173d5d;color:#fff;padding:7px 11px;border-radius:20px;font:12px Arial,sans-serif">Spin system loaded</span>
<div class="eh-spin-overlay" id="ehSpinOverlay" aria-modal="true" role="dialog" aria-labelledby="ehSpinTitle">
  <section class="eh-spin-modal">
    <button type="button" class="eh-spin-close" id="ehSpinClose" aria-label="Close">&times;</button>
    <span class="eh-spin-kicker">Expresshipping rewards</span>
    <h2 id="ehSpinTitle">Spin &amp; Win!</h2>
    <p class="eh-spin-intro">Try your luck and win a discount on your next shipment. No sign-up required.</p>
    <div class="eh-wheel-wrap">
      <span class="eh-wheel-pointer" aria-hidden="true"></span>
      <canvas class="eh-wheel" id="ehSpinWheel" width="640" height="640" aria-label="Prize wheel"></canvas>
      <span class="eh-spin-center">SPIN</span>
    </div>
    <button type="button" class="eh-spin-action" id="ehSpinButton">Spin the wheel</button>
    <form class="eh-spin-claim" id="ehSpinClaim">
      <label for="ehSpinPhone">Enter your phone number to claim your prize</label>
      <input type="tel" id="ehSpinPhone" name="phone" autocomplete="tel" placeholder="+234 800 000 0000" maxlength="24" required>
      <button type="submit" class="eh-spin-action">Claim my coupon</button>
    </form>
    <div class="eh-spin-result" id="ehSpinResult">
      <span>Your coupon code</span>
      <strong id="ehSpinPrize"></strong>
      <span class="eh-spin-code" id="ehSpinCoupon"></span>
    </div>
    <p class="eh-spin-message" id="ehSpinMessage" aria-live="polite">One spin per browser session. Good luck!</p>
  </section>
</div>
<script src="assets/js/spin-win.js?v=5" defer></script>
<?php endif; ?>
