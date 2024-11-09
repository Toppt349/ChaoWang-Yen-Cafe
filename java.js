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
