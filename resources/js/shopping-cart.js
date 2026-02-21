document.addEventListener('DOMContentLoaded', () => {

    // فقط اگر صفحه سبد خرید باشه ادامه بده
    if (!document.querySelector('.cart-page')) return;

    async function load_shopping_cart() {
        try {

            const res = await axios.post('/profile/shopping-cart/BelongToUser');
            const response = res.data;

            if (!response || !response.success) {
                throw new Error("خطا در دریافت اطلاعات");
            }

            const items = response.cart_items;
            const container = document.getElementById('cart-container');
            const countEl = document.getElementById('cart-head-count');
            const totalEl = document.getElementById('cart-total');
            const discountEl = document.getElementById('cart-discount');

            container.innerHTML = '';
            let totalPrice = 0;
            let totalDiscount = 0;
            let i = 0;

            items.forEach(item => {
                i++;
                const price = parseFloat(item.price);
                const quantity = item.pivot.quantity;
                const discountPercent = item.off;

                const discountAmount = price * discountPercent / 100;
                const finalPrice = price - discountAmount;
                const itemTotal = finalPrice * quantity;

                totalPrice += itemTotal;
                totalDiscount += discountAmount * quantity;

                container.innerHTML += `
                    <article class="cart-item">
                        <div class="item-image">
                            <img src="/images/hero.jpg" alt="${item.name}">
                        </div>

                        <div class="item-details">
                            <h3 class="item-title">${item.name}</h3>
                            <p class="item-desc">${item.description}</p>

                            <div class="item-footer">
                                <span class="item-price">
                                    ${finalPrice.toLocaleString()} تومان
                                </span>

                                <div class="item-actions">
                                    <span class="item-qty">× ${quantity}</span>
                                    <button class="btn-outline"><i class="fa-solid fa-trash"></i></button>
                                    <button class="btn-primary">مشاهده</button>
                                </div>
                            </div>
                        </div>
                    </article>
                `;
            });

            countEl.innerText = i + " نوع کالا";
            totalEl.innerText = totalPrice.toLocaleString() + " تومان";
            discountEl.innerText = totalDiscount.toLocaleString() + " تومان";

        } catch (error) {
            Swal.fire({
                toast: true,
                icon: "error",
                title: "خطا",
                text: "مشکلی در دریافت سبد خرید رخ داد",
                showConfirmButton: false,
            });
        }
    }


            load_shopping_cart();


});