<x-layout>
    <main class="cart-page">

        <!-- 🛒 لیست محصولات -->
        <section class="cart-items">
            <header class="cart-header">
                <h2>سبد خرید شما</h2>
                <span class="cart-count" id="cart-head-count">0 کالا</span>
            </header>

            <!-- 👇 اینجا محصولات داینامیک لود میشن -->
            <div id="cart-container"></div>

        </section>

        <!-- 💳 سایدبار پرداخت -->
        <aside class="checkout-panel">
            <div class="promo-box">محل نمایش تبلیغات</div>

            <div class="summary-card">
                <h3>خلاصه سفارش</h3>

                <div class="summary-row">
                    <span>جمع کل</span>
                    <strong id="cart-total">0 تومان</strong>
                </div>

                <div class="summary-row discount">
                    <span>سود شما</span>
                    <strong id="cart-discount">0 تومان</strong>
                </div>

                <button class="checkout-btn">ادامه و پرداخت</button>
            </div>

            <div class="cart-alert">
                ⚠️ این سفارش هنوز پرداخت نشده است و در صورت اتمام موجودی،
                کالاها از سبد حذف خواهند شد.
            </div>
        </aside>

    </main>
</x-layout>