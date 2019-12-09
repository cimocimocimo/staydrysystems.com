<?php

    use function Tonik\Theme\App\asset_path;

?><div class="before-header">
    <div class="col-full">
        <div class="payment-acceptance-logos">
            <img src="<?= asset_path('images/visa-logo.svg') ?>" alt="Order online securely with Visa">
            <img src="<?= asset_path('images/mastercard-logo.svg') ?>" alt="Order online securely with Mastercard">
            <img src="<?= asset_path('images/paypal-p-logo.svg') ?>" alt="Order online securely with PayPal">
        </div>
        <div class="topbar-phone-number">
            <a href="tel:1-866-944-0449">
                <span class="top-line">Order by Phone or Online</span>
                <span class="bottom-line">1-866-944-0449</span>
            </a>
        </div>
        <div class="topbar-worldwide-shipping">
            <a href="/shipping-policies/">
                Worldwide Shipping
            </a>
        </div>
    </div>
</div>
