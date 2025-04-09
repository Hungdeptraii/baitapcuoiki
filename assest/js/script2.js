'use strict';



/**
 * add event on element
 */

const addEventOnElem = function (elem, type, callback) {
  if (elem.length > 1) {
    for (let i = 0; i < elem.length; i++) {
      elem[i].addEventListener(type, callback);
    }
  } else {
    elem.addEventListener(type, callback);
  }
}



/**
 * navbar toggle
 */

const navbar = document.querySelector("[data-navbar]");
const navbarLinks = document.querySelectorAll("[data-nav-link]");
const navTogglers = document.querySelectorAll("[data-nav-toggler]");
const overlay = document.querySelector("[data-overlay]");

const toggleNavbar = function () {
  navbar.classList.toggle("active");
  overlay.classList.toggle("active");
  document.body.classList.toggle("active");
}

addEventOnElem(navTogglers, "click", toggleNavbar);

const closeNavbar = function () {
  navbar.classList.remove("active");
  overlay.classList.remove("active");
  document.body.classList.remove("active");
}

addEventOnElem(navbarLinks, "click", closeNavbar);



/**
 * header & back top btn active when window scroll down to 100px
 */

const header = document.querySelector("[data-header]");
const backTopBtn = document.querySelector("[data-back-top-btn]");

const showElemOnScroll = function () {
  if (window.scrollY > 100) {
    header.classList.add("active");
    backTopBtn.classList.add("active");
  } else {
    header.classList.remove("active");
    backTopBtn.classList.remove("active");
  }
}

addEventOnElem(window, "scroll", showElemOnScroll);



/**
 * product filter
 */

const filterBtns = document.querySelectorAll("[data-filter-btn]");
const filterBox = document.querySelector("[data-filter]");

let lastClickedFilterBtn = filterBtns[0];

const filter = function () {
  lastClickedFilterBtn.classList.remove("active");
  this.classList.add("active");
  lastClickedFilterBtn = this;

  filterBox.setAttribute("data-filter", this.dataset.filterBtn)
}

addEventOnElem(filterBtns, "click", filter);




document.addEventListener('DOMContentLoaded', () => {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const cartCountElement = document.getElementById('cart-count');
    const cartMenu = document.getElementById('cart-menu'); // Ensure cart menu element is selected
    const cartItemsElement = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const cartIcon = document.getElementById('cart-icon');
    const checkoutButton = document.getElementById('checkout-button');

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    let cartTotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

    addToCartButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const productForm = event.target.closest('form');
            const productId = productForm.querySelector('input[name="MaHang"]').value;
            const productName = productForm.querySelector('input[name="TenSanPham"]').value;
            const productPrice = parseFloat(productForm.querySelector('input[name="GiaNhap"]').value);
            const productImage = productForm.querySelector('input[name="HinhAnh"]').value;
            const discountedPrice = parseFloat(productForm.querySelector('input[name="GiamGia"]').value);

            const product = {
                id: productId,
                name: productName,
                price: discountedPrice > 0 ? discountedPrice : productPrice, // Use discounted price if available, otherwise use original price
                quantity: 1,
                image: productImage
            };

            if (product.price <= 0) {
                alert('Sản phẩm đã hết hàng hoặc không có sẵn để mua.');
                return;
            }

            addToCart(product);
        });
    });

    cartIcon.addEventListener('click', () => {
        cartMenu.classList.toggle('show'); // Toggle visibility of cart menu
    });

    checkoutButton.addEventListener('click', () => {
        if (cartCount === 0) {
            alert('Giỏ hàng của bạn đang trống. Không thể thanh toán.');
            return;
        }
        window.location.href = 'thanhtoan.php';
    });

    function addToCart(product) {
        const existingProduct = cart.find(item => item.id === product.id);
        if (existingProduct) {
            existingProduct.quantity++;
        } else {
            cart.push(product);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        cartCount++;
        cartTotal += product.price;
        updateCartDisplay();
    }

    function updateCartDisplay() {
        cartCountElement.textContent = cartCount;
        cartItemsElement.innerHTML = '';
        cart.forEach(item => {
            const cartItem = document.createElement('li');
            cartItem.classList.add('cart-item');
            cartItem.innerHTML = `
                <div class="item-details">
                    ${item.name} - $${item.price.toFixed(2)} x ${item.quantity}
                </div>
                <div class="quantity-controls">
                    <button class="decrease-quantity" data-id="${item.id}">-</button>
                    <span class="quantity">${item.quantity}</span>
                    <button class="increase-quantity" data-id="${item.id}">+</button>
                </div>
                <button class="remove-from-cart" data-id="${item.id}">
                    <ion-icon name="trash-outline"></ion-icon>
                </button>
            `;
            cartItemsElement.appendChild(cartItem);
            cartItem.querySelector('.increase-quantity').addEventListener('click', () => changeQuantity(item.id, 1));
            cartItem.querySelector('.decrease-quantity').addEventListener('click', () => changeQuantity(item.id, -1));
            cartItem.querySelector('.remove-from-cart').addEventListener('click', () => removeFromCart(item.id));
        });
        cartTotalElement.textContent = `$${cartTotal.toFixed(2)}`;
    }

    function changeQuantity(productId, change) {
        const product = cart.find(item => item.id === productId);
        if (product) {
            product.quantity += change;
            if (product.quantity <= 0) {
                removeFromCart(productId);
            } else {
                cartTotal += product.price * change;
                cartCount += change;
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartDisplay();
            }
        }
    }

    function removeFromCart(productId) {
        const productIndex = cart.findIndex(item => item.id === productId);
        if (productIndex !== -1) {
            const product = cart[productIndex];
            cartTotal -= product.price * product.quantity;
            cartCount -= product.quantity;
            cart.splice(productIndex, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartDisplay();
        }
    }

    // Initial update of cart display
    updateCartDisplay();
});






  // Đoạn mã JavaScript để xử lý hiển thị thông tin người dùng và đăng xuất
  document.addEventListener('DOMContentLoaded', (event) => {
    const userBtn = document.getElementById('userBtn');
    const userMenu = document.getElementById('userMenu');
    let logoutBtn = document.getElementById('logoutBtn'); // Corrected variable declaration

    userBtn.addEventListener('click', () => {
        if (userMenu.style.display === 'none' || userMenu.style.display === '') {
            userMenu.style.display = 'block';
        } else {
            userMenu.style.display = 'none';
        }
    });

    // Close the menu if clicking outside of it
    document.addEventListener('click', (event) => {
        if (!userMenu.contains(event.target) && event.target !== userBtn) {
            userMenu.style.display = 'none';
        }
    });

    // Ensure that logoutBtn is not null
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            document.getElementById('logoutForm').submit();
        });
    } else {
        console.error("Logout button not found in the DOM");
    }
});

document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', () => {
        const category = button.getAttribute('data-filter-btn');
        document.querySelectorAll('.product-card').forEach(card => {
            if (category === 'all' || card.getAttribute('data-category') === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
    });
});







document.addEventListener('DOMContentLoaded', function () {
    const itemsPerPage = 8;
    let currentPage = 1;
    const productCards = document.querySelectorAll('.product-card');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const paginationContainer = document.getElementById('pagination');
    const productList = document.querySelector('.product-list');
    const sectionProduct = document.getElementById('product');

    let currentCategory = 'all';
    let filteredProducts = Array.from(productCards);

    function scrollToTop() {
        sectionProduct.scrollIntoView({ behavior: 'smooth' });
    }

    function updatePagination() {
        const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
        currentPage = Math.min(currentPage, totalPages);
        
        // Clear previous pagination buttons
        paginationContainer.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('pagination-btn');
            if (i === currentPage) {
                pageButton.classList.add('active');
            }
            pageButton.addEventListener('click', function () {
                currentPage = i;
                updatePagination();
                scrollToTop();
            });
            paginationContainer.appendChild(pageButton);
        }

        filteredProducts.forEach((product, index) => {
            if (index >= (currentPage - 1) * itemsPerPage && index < currentPage * itemsPerPage) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            currentCategory = this.getAttribute('data-filter-btn');

            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            // Filter products based on category
            filteredProducts = Array.from(productCards).filter(product => {
                return currentCategory === 'all' || product.getAttribute('data-category') === currentCategory;
            });

            // Reset to first page
            currentPage = 1;
            updatePagination();
            scrollToTop();
        });
    });

    updatePagination();
});
