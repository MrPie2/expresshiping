<!doctype html>
<html lang="en">
<?php include('Header.php'); ?>
<body class="tracking-page">
<?php include('Navbar.php'); ?>

<style>
:root {
    --tracking-ink: #142b3d;
    --tracking-muted: #667789;
    --tracking-brand: #0a7868;
    --tracking-brand-dark: #075c52;
    --tracking-line: #e6edf1;
}
.tracking-page { background:#f4f7f8; color:var(--tracking-ink); }
.tracking-hero {
    position:relative; isolation:isolate; overflow:hidden;
    padding:76px 0 96px;
    background:linear-gradient(112deg,rgba(8,29,46,.96),rgba(8,66,70,.88)),
        url('https://images.unsplash.com/photo-1494412519320-aa613dfb7738?auto=format&fit=crop&w=1900&q=85') center/cover;
    color:#fff;
}
.tracking-hero:after {
    content:""; position:absolute; z-index:-1; width:440px; height:440px;
    right:-130px; top:-230px; border:1px solid rgba(255,255,255,.15); border-radius:50%;
    box-shadow:0 0 0 45px rgba(255,255,255,.035),0 0 0 90px rgba(255,255,255,.025);
}
.tracking-eyebrow {
    display:inline-flex; align-items:center; gap:9px; margin-bottom:18px;
    color:#a9ded5; font-size:12px; font-weight:700; letter-spacing:2px; text-transform:uppercase;
}
.tracking-eyebrow:before { content:""; width:26px; height:2px; background:#43c2a9; }
.tracking-hero h1 { max-width:720px; margin:0 0 14px; color:#fff; font-size:clamp(34px,5vw,54px); font-weight:700; line-height:1.12; letter-spacing:-1.5px; }
.tracking-hero p { max-width:610px; margin:0; color:rgba(255,255,255,.76); font-size:16px; line-height:1.8; }
.tracking-search-wrap { margin-top:-43px; position:relative; z-index:2; }
.tracking-search-card {
    padding:30px; border:1px solid rgba(18,48,65,.06); border-radius:18px;
    background:#fff; box-shadow:0 18px 55px rgba(17,43,58,.12);
}
.tracking-search-card label { display:block; margin-bottom:10px; color:var(--tracking-ink); font-size:13px; font-weight:700; }
.tracking-input-group { display:flex; gap:12px; }
.tracking-input {
    min-width:0; flex:1; height:58px; padding:0 18px; border:1px solid #dce5e9;
    border-radius:9px; outline:none; background:#fbfcfd; color:var(--tracking-ink); font-size:15px;
    transition:border-color .2s,box-shadow .2s;
}
.tracking-input:focus { border-color:var(--tracking-brand); box-shadow:0 0 0 4px rgba(10,120,104,.10); background:#fff; }
.tracking-submit {
    min-width:170px; height:58px; padding:0 24px; border:0; border-radius:9px;
    background:var(--tracking-brand); color:#fff; font-weight:700; transition:.2s;
}
.tracking-submit:hover { background:var(--tracking-brand-dark); transform:translateY(-1px); }
.tracking-submit:disabled { opacity:.7; cursor:wait; transform:none; }
.tracking-hint { margin:12px 0 0; color:var(--tracking-muted); font-size:12px; }
.tracking-hint i { margin-right:6px; color:var(--tracking-brand); }
.tracking-message { display:none; margin-top:14px; padding:12px 14px; border-radius:8px; font-size:13px; }
.tracking-message.is-error { display:block; color:#9c2d35; background:#fff0f0; border:1px solid #f5d2d4; }
.tracking-message.is-info { display:block; color:#245a68; background:#eef8fa; border:1px solid #d7edf1; }
.tracking-result-section { padding:44px 0 76px; min-height:160px; }
.tracking-result:empty { display:none; }
.tracking-result { overflow:hidden; }
.tracking-loading { display:none; padding:34px 18px; text-align:center; color:var(--tracking-muted); }
.tracking-loading.active { display:block; }
.tracking-spinner { width:36px; height:36px; margin:0 auto 13px; border:3px solid #dce9e7; border-top-color:var(--tracking-brand); border-radius:50%; animation:tracking-spin .8s linear infinite; }
@keyframes tracking-spin { to { transform:rotate(360deg); } }
.tracking-help { padding:0 0 76px; }
.tracking-help-card { height:100%; padding:24px; border:1px solid var(--tracking-line); border-radius:13px; background:#fff; }
.tracking-help-icon { display:grid; place-items:center; width:44px; height:44px; margin-bottom:15px; border-radius:11px; background:#e9f5f2; color:var(--tracking-brand); font-size:18px; }
.tracking-help-card h3 { margin:0 0 8px; font-size:16px; font-weight:700; }
.tracking-help-card p { margin:0; color:var(--tracking-muted); font-size:13px; line-height:1.7; }
@media(max-width:767px) {
    .tracking-hero { padding:58px 0 78px; }
    .tracking-hero p { font-size:14px; }
    .tracking-search-card { padding:21px 17px; border-radius:14px; }
    .tracking-input-group { flex-direction:column; }
    .tracking-input,.tracking-submit { width:100%; height:54px; }
    .tracking-submit { min-width:0; }
    .tracking-search-wrap { margin-top:-35px; }
    .tracking-result-section { padding:30px 0 55px; }
}
</style>

<main>
    <section class="tracking-hero">
        <div class="container">
            <div class="tracking-eyebrow">Shipment visibility</div>
            <h1>Every delivery has a journey. Follow yours.</h1>
            <p>Enter your shipment tracking ID to view its latest status, delivery information and available shipment updates.</p>
        </div>
    </section>

    <section class="tracking-search-wrap">
        <div class="container">
            <div class="tracking-search-card">
                <form id="trackingForm" novalidate>
                    <label for="trackingId">Tracking ID</label>
                    <div class="tracking-input-group">
                        <input id="trackingId" name="TrackingText" class="tracking-input" type="text"
                            placeholder="e.g. Enter your tracking number" autocomplete="off" required>
                        <button class="tracking-submit" id="trackingSubmit" type="submit">
                            <i class="fa-solid fa-magnifying-glass me-2"></i><span>Track shipment</span>
                        </button>
                    </div>
                    <p class="tracking-hint"><i class="fa-solid fa-shield-halved"></i>Use the tracking ID provided when your shipment was registered.</p>
                    <div id="trackingMessage" class="tracking-message" role="status" aria-live="polite"></div>
                </form>
            </div>
        </div>
    </section>

    <section class="tracking-result-section">
        <div class="container">
            <div class="tracking-loading" id="trackingLoading" aria-live="polite">
                <div class="tracking-spinner"></div>
                <strong>Looking up your shipment</strong>
                <div class="small mt-1">Please wait while we retrieve the latest information.</div>
            </div>
            <div id="trackingResult" class="tracking-result" aria-live="polite"></div>
        </div>
    </section>

    <section class="tracking-help">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="tracking-help-card">
                        <div class="tracking-help-icon"><i class="fa-solid fa-barcode"></i></div>
                        <h3>Find your tracking ID</h3>
                        <p>Your tracking ID is the unique reference supplied when your package was booked.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tracking-help-card">
                        <div class="tracking-help-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <h3>View shipment progress</h3>
                        <p>Check the available shipment status, current location and delivery information.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tracking-help-card">
                        <div class="tracking-help-icon"><i class="fa-solid fa-headset"></i></div>
                        <h3>Need assistance?</h3>
                        <p>If your tracking details are unavailable or appear incorrect, contact our support team.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Required by the existing shipment result: route map and SafeBox QR code. -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
(function ($) {
    const form = $('#trackingForm');
    const input = $('#trackingId');
    const button = $('#trackingSubmit');
    const message = $('#trackingMessage');
    const loading = $('#trackingLoading');
    const result = $('#trackingResult');

    function showMessage(text, type) {
        message.removeClass('is-error is-info').addClass(type === 'error' ? 'is-error' : 'is-info')
            .text(text).show();
    }

    function clearMessage() {
        message.removeClass('is-error is-info').text('').hide();
    }

    form.on('submit', function (event) {
        event.preventDefault();
        const trackingId = $.trim(input.val());

        clearMessage();
        if (!trackingId) {
            showMessage('Please enter your tracking ID to continue.', 'error');
            input.trigger('focus');
            return;
        }

        button.prop('disabled', true).find('span').text('Searching...');
        loading.addClass('active');
        result.empty();

        $.ajax({
            url: 'getAddress.php',
            method: 'POST',
            data: { TrackingText: trackingId },
            dataType: 'html',
            timeout: 30000
        }).done(function (html) {
            if (!html || !$.trim(html)) {
                showMessage('We could not retrieve shipment details. Please try again.', 'error');
                return;
            }
            result.html(html);
            if (result.find('.alert-danger').length) {
                result[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                result[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }).fail(function (xhr, status) {
            const errorText = status === 'timeout'
                ? 'The tracking request timed out. Please try again.'
                : 'We could not connect to the tracking service. Please try again shortly.';
            showMessage(errorText, 'error');
        }).always(function () {
            loading.removeClass('active');
            button.prop('disabled', false).find('span').text('Track shipment');
        });
    });
})(jQuery);
</script>
</body>
</html>
