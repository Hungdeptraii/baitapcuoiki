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
    const cartMenu = document.getElementById('cart-menu');
    const cartItemsElement = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const cartIcon = document.getElementById('cart-icon');
 
    const checkoutButton = document.getElementById('checkout-button');
    const userBtn = document.getElementById('userBtn');
    const userMenu = document.getElementById('userMenu');
    let logoutBtn = document.getElementById('logoutBtn');

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    let cartTotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

    addToCartButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const productForm = event.target.closest('form');
            const formData = new FormData(productForm);
    
            const product = {
                id: formData.get('MaHang'),
                name: formData.get('TenSanPham'),
                price: parseFloat(formData.get('GiamGia')) > 0 ? parseFloat(formData.get('GiamGia')) : parseFloat(formData.get('GiaNhap')),
                quantity: 1,
                image: formData.get('HinhAnh')
            };
    
            if (product.price <= 0) {
                alert('Sản phẩm đã hết hàng hoặc không có sẵn để mua.');
                return;
            }
    
            // Gửi yêu cầu AJAX đến add_to_cart.php
            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addToCart(product);
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
            });
        });
    });
    
    cartIcon.addEventListener('click', (event) => {
        event.stopPropagation(); // Ngăn chặn sự kiện nhấp chuột nổi lên
        cartMenu.classList.toggle('show');
    });

    checkoutButton.addEventListener('click', () => {
        if (cartCount === 0) {
            alert('Giỏ hàng của bạn đang trống. Không thể thanh toán.');
            return;
        }
        window.location.href = 'checkout.php';
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
                <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                <div class="item-details">
                ${item.name} - ${item.price.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })} x ${item.quantity}
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
        cartTotalElement.textContent = `${cartTotal.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}`;
    }

    function changeQuantity(productId, change) {
        const product = cart.find(item => item.id === productId);
        if (product) {
            const newQuantity = product.quantity + change;
            if (newQuantity > 0) {
                // Gửi yêu cầu AJAX đến update_cart_quantity.php
                fetch('update_cart_quantity.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        MaHang: productId,
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        product.quantity = newQuantity;
                        cartTotal += product.price * change;
                        cartCount += change;
                        localStorage.setItem('cart', JSON.stringify(cart));
                        updateCartDisplay();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi cập nhật số lượng sản phẩm.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật số lượng sản phẩm.');
                });
            } else {
                removeFromCart(productId);
            }
        }
    }

    function removeFromCart(productId) {
        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                MaHang: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const productIndex = cart.findIndex(item => item.id === productId);
                if (productIndex !== -1) {
                    const product = cart[productIndex];
                    cartTotal -= product.price * product.quantity;
                    cartCount -= product.quantity;
                    cart.splice(productIndex, 1);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartDisplay();
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.');
        });
    }

    // Đoạn mã JavaScript để xử lý hiển thị thông tin người dùng và đăng xuất
    userBtn.addEventListener('click', (event) => {
        event.stopPropagation(); // Ngăn chặn sự kiện nhấp chuột nổi lên
        if (userMenu.style.display === 'none' || userMenu.style.display === '') {
            userMenu.style.display = 'block';
        } else {
            userMenu.style.display = 'none';
        }
    });

    // Close the menus if clicking outside of them
    document.addEventListener('click', (event) => {
        if (!cartMenu.contains(event.target) && event.target !== cartIcon) {
            cartMenu.classList.remove('show');
        }
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

    updateCartDisplay();
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


window.addEventListener("load",() => {
    const preloader = document.querySelector(".preloader");
    preloader.classList.add("unactive");
});



// scripts.js

document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.slider-track');
    const slides = Array.from(track.children);
    const nextButton = document.querySelector('.carousel-control.next');
    const prevButton = document.querySelector('.carousel-control.prev');
    const slideWidth = slides[0].getBoundingClientRect().width;
    const intervalTime = 3000; // Thay đổi giá trị này để điều chỉnh khoảng thời gian giữa các slide

    let currentIndex = 0;

    const moveToSlide = (index) => {
        track.style.transform = `translateX(-${slideWidth * index}px)`;
        currentIndex = index;
    };

    const autoSlide = () => {
        const nextIndex = (currentIndex + 1) % slides.length;
        moveToSlide(nextIndex);
    };

    let autoSlideInterval = setInterval(autoSlide, intervalTime);

    nextButton.addEventListener('click', () => {
        clearInterval(autoSlideInterval); // Dừng tự động chuyển đổi khi người dùng nhấn nút
        const nextIndex = (currentIndex + 1) % slides.length;
        moveToSlide(nextIndex);
        autoSlideInterval = setInterval(autoSlide, intervalTime); // Bắt đầu lại tự động chuyển đổi sau khi nhấn nút
    });

    prevButton.addEventListener('click', () => {
        clearInterval(autoSlideInterval); // Dừng tự động chuyển đổi khi người dùng nhấn nút
        const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
        moveToSlide(prevIndex);
        autoSlideInterval = setInterval(autoSlide, intervalTime); // Bắt đầu lại tự động chuyển đổi sau khi nhấn nút
    });
});


$(document).ready(function() {
    $('#search-input').on('input', function() {
        var query = $(this).val().trim(); // Loại bỏ khoảng trắng thừa
        if (query.length > 2) { // Chỉ gửi yêu cầu khi có ít nhất 3 ký tự
            $.ajax({
                url: 'search_suggestions.php',
                type: 'GET',
                data: { search: query },
                success: function(data) {
                    $('#suggestions').html(data).show(); // Hiển thị gợi ý
                },
                error: function() {
                    $('#suggestions').html('<p>Error retrieving suggestions.</p>').show(); // Thông báo lỗi
                }
            });
        } else {
            $('#suggestions').empty().hide(); // Xóa và ẩn gợi ý khi không có ký tự
        }
    });

    $(document).on('click', '.suggestion-item', function() {
        var productId = $(this).data('mahang'); // Lấy MaHang từ thuộc tính data
        if (productId) {
            window.location.href = 'products_details.php?MaHang=' + encodeURIComponent(productId); // Redirect đến trang chi tiết sản phẩm
        }
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#search-input, #suggestions').length) {
            $('#suggestions').empty().hide(); // Ẩn gợi ý khi nhấp ra ngoài
        }
    });
});
