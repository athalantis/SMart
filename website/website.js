// js bagian cart awal
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

    // memastikan elemen ditemukan
    console.log("Add to Cart Icons:", addToCartIcons);
    console.log("Cart Icon:", cartIcon);
    console.log("Sidebar Close Button:", closeButton);

    // Event listener untuk setiap menekan icon "add to cart"
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
//* js bagian cart akhir */

//js bagian blockquote awal
document.addEventListener("DOMContentLoaded", () => {
  const feedbackForm = document.getElementById("feedbackForm");
  const reviewContainer = document.getElementById("reviewContainer");

  feedbackForm.addEventListener("submit", (event) => {
    event.preventDefault(); // Mencegah reload halaman

    // Ambil nilai dari inputan
    const nama = document.getElementById("nama").value.trim();
    const pesanSingkat = document.getElementById("pesan-singkat").value.trim();
    const pesan = document.getElementById("pesan").value.trim();

    // Validasi input
    if (!nama || !pesanSingkat || !pesan) {
      alert("Nama, pesan singkat, dan kritik/pesan tidak boleh kosong!");
      return;
    }

    // Buat elemen baru untuk review
    const reviewCard = document.createElement("div");
    reviewCard.classList.add("col-md-4", "mb-3");

    // menambahkan konten atau pesan blockquote 
    reviewCard.innerHTML = `
      <div class="card card-blockquote hovered-card-service p-3">
        <figure>
          <blockquote class="blockquote">
            <p>${pesanSingkat}</p>
          </blockquote>
          <figcaption class="blockquote-footer">
            ${nama}
          </figcaption>
          <p>${pesan}</p>
        </figure>
      </div>
    `;

    // Tambahkan elemen baru ke container review
    reviewContainer.appendChild(reviewCard);

    // Reset form
    feedbackForm.reset();
  });
});
//js bagian blockquote akhir
