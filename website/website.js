document.addEventListener("DOMContentLoaded", () => {
    const addToCartIcons = document.querySelectorAll(".add-cart");
    const cartItemCount = document.querySelector(".cart-icon span");
    const cartItemList = document.querySelector(".cart-items");
    const cartTotal = document.querySelector(".cart-total");
    const cartIcon = document.querySelector(".cart-icon");
    const cartSidebar = document.querySelector(".sidebar");
    const closeButton = document.querySelector(".sidebar-close");
    const checkoutButton = document.querySelector(".checkout-btn");

    let cartItems = [];
    let totalAmount = 0;

    // Debugging: memastikan elemen ditemukan
    console.log("Add to Cart Icons:", addToCartIcons);
    console.log("Cart Icon:", cartIcon);
    console.log("Sidebar Close Button:", closeButton);

    // Event listener untuk ikon Add to Cart
    if (addToCartIcons.length > 0) {
        addToCartIcons.forEach((icon) => {
            icon.addEventListener("click", () => {
                const card = icon.closest(".card");
                if (!card) {
                    console.error("Card element not found for icon:", icon);
                    return;
                }

                const item = {
                    name: card.querySelector(".card-title").textContent.trim(),
                    price: parseFloat(card.querySelector(".card-price").textContent.replace("Rp", "").replace(".", "").trim()),
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
    } else {
        console.error("No .add-cart elements found");
    }

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
            cartItem.classList.add("cart-item","individual-cart-item");
            cartItem.innerHTML = `
              <div class="d-flex justify-content-between align-items-center">
                <span >(${item.quantity}x) ${item.name}</span>
                <span class="cart-item-price ms-auto">Rp.${(item.price * item.quantity).toFixed(2)}
                </span>
                <button class="remove-btn" data-index="${index}"><i class="bi bi-x-lg"></i></button>
                </div> 
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
        cartTotal.textContent = `Rp.${totalAmount.toFixed(2)}`;
    }

    if (cartIcon) {
        cartIcon.addEventListener("click", () => {
            cartSidebar.classList.toggle("open");
        });
    } else {
        console.error("Cart Icon not found");
    }

    if (closeButton) {
        closeButton.addEventListener("click", () => {
            cartSidebar.classList.remove("open");
        });
    } else {
        console.error("Sidebar Close Button not found");
    }
 
    if (checkoutButton) {
        checkoutButton.addEventListener("click", () => {
            if (cartItems.length === 0) {
                alert("Keranjang belanja kosong. Tambahkan produk terlebih dahulu.");
            } else {
                alert(`Checkout berhasil! Total: Rp.${totalAmount.toFixed(2)}`);
                // Kosongkan keranjang setelah checkout
                cartItems = [];
                totalAmount = 0;
                updateCartUI();
            }
        });
    } else {
        console.error("Checkout Button not found");
    }
});
