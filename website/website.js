document.addEventListener("DOMContentLoaded", () => {
    const addToCart = document.querySelectorAll(".add-cart");
    const cartItemCount = document.querySelector(".cart-icon span");
    const cartItemList = document.querySelector(".cart-items");
    const cartTotal = document.querySelector(".cart-total");
    const cartIcon = document.querySelector(".cart-icon");
    const cartSidebar = document.querySelector(".sidebar");

    let cartItems = [];
    let totalAmount = 0;

    addToCart.forEach((button, index) => {
        button.addEventListener("click", () => {
            const card = button.closest(".card"); 
            const item = {
                name: card.querySelector(".card-title").textContent.trim(),
                price: parseFloat(card.querySelector(".card-price").textContent.replace("Rp", "").replace(",", "").trim()),
                quantity: 1,
            };

            const existingItem = cartItems.find(cartItem => cartItem.name === item.name);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cartItems.push(item);
            }

            totalAmount += item.price;
            updateCartUI();
        });
    });

    function updateCartUI() {
        updateCartItemCount(cartItems.length);
        updateCartItemList();
        updateCartTotal();
    }

    function updateCartItemCount(count) {
        cartItemCount.textContent = count;
    }

    function updateCartItemList() {
        cartItemList.innerHTML = "";
        cartItems.forEach((item, index) => {
            const cartItem = document.createElement("li");
            cartItem.classList.add("cart-item", "individual-cart-item");
            cartItem.innerHTML = `
                <span>(${item.quantity}x) ${item.name}</span>
                <span class="cart-item-price">Rp ${(item.price * item.quantity).toFixed(2)}</span>
                <button class="remove-btn" data-index="${index}">Hapus</button>
            `;

            cartItemList.append(cartItem);
        });

        const removeButtons = document.querySelectorAll(".remove-btn");
        removeButtons.forEach((button) => {
            button.addEventListener("click", (event) => {
                const index = event.target.dataset.index;
                removeItemFromCart(index);
            });
        });
    }

    function removeItemFromCart(index) {
        const removedItem = cartItems.splice(index, 1)[0];
        totalAmount -= removedItem.price * removedItem.quantity;
        updateCartUI();
    }

    function updateCartTotal() {
        cartTotal.textContent = `Total: Rp ${totalAmount.toFixed(2)}`;
    }

    cartIcon.addEventListener("click", () => {
        cartSidebar.classList.toggle("open");
    });

    const closeButton = document.querySelector(".sidebar-close");
    closeButton.addEventListener("click", () => {
        cartSidebar.classList.remove("open");
    });
});