function removeFromCart(product) {
    const index = cart.findIndex(item => item.product === product);
    if (index > -1) {
        cart.splice(index, 1); // ลบรายการออกจากตะกร้า
    }
    displayCart();
}
// script.js
let cart = [];

function addToCart(product, price) {
    // เช็คว่ามีสินค้านี้อยู่ในตะกร้าหรือไม่
    const existingProduct = cart.find(item => item.product === product);
    if (existingProduct) {
        existingProduct.quantity += 1; // เพิ่มจำนวนถ้ามีอยู่แล้ว
    } else {
        cart.push({ product, price, quantity: 1 }); // เพิ่มสินค้าใหม่
    }
    displayCart();
}

function displayCart() {
    const cartItems = document.getElementById('cart-items');
    cartItems.innerHTML = '';
    let total = 0;
    
    cart.forEach(item => {
        const li = document.createElement('li');
        li.textContent = `${item.product} - ${item.quantity} ชิ้น (ราคา: ${item.price} บาท)`;
        cartItems.appendChild(li);
        total += item.price * item.quantity; // คำนวณราคาสุทธิ
    });

    document.getElementById('total-price').textContent = `รวม: ${total} บาท`;
}

function checkout() {
    alert('กำลังไปยังหน้าชำระเงิน...');
    // แทนที่ด้วยการดำเนินการชำระเงิน QR Code
}
const nav = document.querySelector('#nav');
const menu = document.querySelector('#menu');
const menuToggle = document.querySelector('.nav__toggle');
let isMenuOpen = false;


// TOGGLE MENU ACTIVE STATE
menuToggle.addEventListener('click', e => {
  e.preventDefault();
  isMenuOpen = !isMenuOpen;
  
  // toggle a11y attributes and active class
  menuToggle.setAttribute('aria-expanded', String(isMenuOpen));
  menu.hidden = !isMenuOpen;
  nav.classList.toggle('nav--open');
});


// TRAP TAB INSIDE NAV WHEN OPEN
nav.addEventListener('keydown', e => {
  // abort if menu isn't open or modifier keys are pressed
  if (!isMenuOpen || e.ctrlKey || e.metaKey || e.altKey) {
    return;
  }
  
  // listen for tab press and move focus
  // if we're on either end of the navigation
  const menuLinks = menu.querySelectorAll('.nav__link');
  if (e.keyCode === 9) {
    if (e.shiftKey) {
      if (document.activeElement === menuLinks[0]) {
        menuToggle.focus();
        e.preventDefault();
      }
    } else if (document.activeElement === menuToggle) {
      menuLinks[0].focus();
      e.preventDefault();
    }
  }
});

