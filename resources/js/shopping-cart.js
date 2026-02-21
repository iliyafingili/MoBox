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
            console.log(response);

            const items = response.cart_items;
            const container = document.getElementById('cart-container');
            const countEl = document.getElementById('cart-head-count');
            const TotalOfPrice = document.getElementById('cart-total');
            const totalEl = document.getElementById('cart-total-with-discount');
            const discountEl = document.getElementById('cart-discount');

            container.innerHTML = '';
            let totalPrice = 0;
            let totalDiscount = 0;
            let totalBeforeDiscount = 0;
            let i = 0;

            items.forEach(item => {
                i++;
                const price = parseFloat(item.price);
                const quantity = item.pivot.quantity;
                const discountPercent = item.off;

                const discountAmount = price * discountPercent / 100;
                const finalPrice = price - discountAmount;
                const itemTotal = finalPrice * quantity;
                const itemTotalBeforeDiscount = price * quantity;
                totalBeforeDiscount += itemTotalBeforeDiscount;

                totalPrice += itemTotal;
                totalDiscount += discountAmount * quantity;

                container.innerHTML += `
                    <a href="/produce-show/${item.id}" class="cart-item">
                        <div class="item-image">
                            <img src="/images/hero.jpg" alt="${item.name}">
                        </div>

                        <div class="item-details">
                            <h3 class="item-title">${item.name}</h3>
                            <p class="item-desc">${item.description}</p>

                            <div class="item-footer">
                                <div class="price-flex">
                                    <span class="price-final">
                                        ${finalPrice.toLocaleString()} تومان
                                    </span>

                                    ${
                                        discountPercent > 0
                                            ? `
                                        <span class="price-original">
                                            ${price.toLocaleString()} تومان
                                        </span>

                                        <span class="price-discount">
                                            ${discountPercent}٪
                                        </span>
                                        `
                                            : ''
                                    }
                                </div>


                                <div class="item-actions">
                                    <span class="item-qty">× ${quantity}</span>
                                    <button class="btn-outline delete-product-event" data-id="${item.id}"><i class="fa-solid fa-trash"></i></button>
                                    <button class="btn-primary">مشاهده</button>
                                </div>
                            </div>
                        </div>
                    </a>
                `;
            });

            countEl.innerText = i + " نوع کالا";
            totalEl.innerText = totalPrice.toLocaleString() + " تومان";
            discountEl.innerText = totalDiscount.toLocaleString() + " تومان";
            TotalOfPrice.innerText = totalBeforeDiscount.toLocaleString() + " تومان";

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



    // ================ remove produce from shopping cart ================
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.delete-product-event');
        if (!btn) return;

        e.preventDefault();     // جلوگیری از href
        e.stopPropagation();    // جلوگیری از رسیدن به <a>

        const id = btn.dataset.id;
        removeFromCart(id, btn);
    });

    async function removeFromCart(productId, btn) {
        try {
            const res = await axios.post(`/profile/shopping-cart/remove/${productId}`);
            const response = res.data;

            if (!response.success) {
                throw new Error("خطا در دریافت اطلاعات");
            }

            if (response.success) {
                update_cart_counter();
                load_shopping_cart();
                // console.log();


                Swal.fire({
                    toast: true,
                    icon: "success",
                    title: response.message,
                    text: `${response.cart_count} محصول در سبد خرید`,
                    background: "#3b82f6",
                    color: "#fff",
                    showConfirmButton: false,
                    iconColor: "chartreuse",
                    position: "center",
                    timer: 2000
                });
            }
        } catch (err) {
            const msg = err?.response?.data?.message || err.message || "خطا";

            Swal.fire({
                toast: true,
                icon: "error",
                title: msg,
                background: "#3b82f6",
                color: "#fff",
                showConfirmButton: false,
                position: "center",
                timer: 2000
            });
        }
    }

    // ================== see product with button in shopping cart============


});