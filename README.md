
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="style.css">
    <div class="viewport">
  <header class="header" role="banner">
    
    <nav id="nav" class="nav" role="navigation">
      
      <!-- ACTUAL NAVIGATION MENU -->
      <ul class="nav__menu" id="menu" tabindex="-1" aria-label="main navigation" hidden>
        <li class="nav__item"><a href="#" class="nav__link">Home</a></li>
        <li class="nav__item"><a href="#" class="nav__link">Shop</a></li>
        <li class="nav__item"><a href="#" class="nav__link">Blog</a></li>
        <li class="nav__item"><a href="#" class="nav__link">About</a></li>
        <li class="nav__item"><a href="#" class="nav__link">Contact</a></li>
      </ul>
      
      <!-- MENU TOGGLE BUTTON -->
      <a href="#nav" class="nav__toggle" role="button" aria-expanded="false" aria-controls="menu">
        <svg class="menuicon" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50">
          <title>Toggle Menu</title>
          <g>
            <line class="menuicon__bar" x1="13" y1="16.5" x2="37" y2="16.5"/>
            <line class="menuicon__bar" x1="13" y1="24.5" x2="37" y2="24.5"/>
            <line class="menuicon__bar" x1="13" y1="24.5" x2="37" y2="24.5"/>
            <line class="menuicon__bar" x1="13" y1="32.5" x2="37" y2="32.5"/>
            <circle class="menuicon__circle" r="23" cx="25" cy="25" />
          </g>
        </svg>
      </a>
      
      <!-- ANIMATED BACKGROUND ELEMENT -->
      <div class="splash"></div>
      
    </nav>
    
  </header>
  
  <!-- DEMO CONTENT -->
  <main class="main" role="main">
    <div class="gallery" aria-label="gallery">
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
      <a href="#" class="gallery__item"></a>
    </div>
  </main>
</div>
</head>
<body>
    <header>
        <h1>ยินดีต้อนรับสู่</h1> ชาววัง (เย็น) Coffee
    </header>
    <main>
        <div id="product-list">
            <!-- หมวดเครื่องดื่ม -->
            <div class="category">
                <h2>เครื่องดื่ม</h2>
                <div class="product-container">
                    <!-- สินค้า 1 -->
                    <div class="product">
                        <img src="https://via.placeholder.com/150" alt="กาแฟสด">
                        <h3>กาแฟสด</h3>
                        <p>ราคา: 50 บาท</p>
                        <button class="order-button" onclick="addToCart('กาแฟสด', 50)">เพิ่มไปยังตะกร้า</button>
                    </div>

                    <!-- สินค้า 2 -->
                    <div class="product">
                        <img src="https://via.placeholder.com/150" alt="ชาเขียว">
                        <h3>ชาเขียว</h3>
                        <p>ราคา: 45 บาท</p>
                        <button class="order-button" onclick="addToCart('ชาเขียว', 45)">เพิ่มไปยังตะกร้า</button>
                    </div>

                    <!-- สินค้า 3 -->
                    <div class="product">
                        <img src="https://via.placeholder.com/150" alt="น้ำผลไม้สกัดเย็น">
                        <h3>น้ำผลไม้สกัดเย็น</h3>
                        <p>ราคา: 60 บาท</p>
                        <button class="order-button" onclick="addToCart('น้ำผลไม้สกัดเย็น', 60)">เพิ่มไปยังตะกร้า</button>
                    </div>
                </div>
                
            </div>

            <!-- หมวดขนม -->
            <div class="category">
                <h2>ขนม</h2>
                <div class="product-container">
                    <!-- สินค้า 4 -->
                    <div class="product">
                        <img src="https://via.placeholder.com/150" alt="เค้กช็อกโกแลต">
                        <h3>เค้กช็อกโกแลต</h3>
                        <p>ราคา: 80 บาท</p>
                        <button class="order-button" onclick="addToCart('เค้กช็อกโกแลต', 80)">เพิ่มไปยังตะกร้า</button>
                    </div>

                    <!-- สินค้า 5 -->
                    <div class="product">
                        <img src="https://via.placeholder.com/150" alt="เค้กสตรอว์เบอร์รี่">
                        <h3>เค้กสตรอว์เบอร์รี่</h3>
                        <p>ราคา: 85 บาท</p>
                        <button class="order-button" onclick="addToCart('เค้กสตรอว์เบอร์รี่', 85)">เพิ่มไปยังตะกร้า</button>
                    </div>

                    <!-- สินค้า 6 -->
                    <div class="product">
                        <img src="https://via.placeholder.com/150" alt="มัฟฟินบลูเบอร์รี่">
                        <h3>มัฟฟินบลูเบอร์รี่</h3>
                        <p>ราคา: 55 บาท</p>
                        <button class="order-button" onclick="addToCart('มัฟฟินบลูเบอร์รี่', 55)">เพิ่มไปยังตะกร้า</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="cart">
            <h2>ตะกร้าสินค้า</h2>
            <ul id="cart-items"></ul>
            <p id="total-price">รวม: 0 บาท</p>
            <button onclick="checkout()">ชำระเงิน</button>
        </div>
    </main>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        function addToCart(product, price) {
            const existingProduct = cart.find(item => item.product === product);
            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                cart.push({ product, price, quantity: 1 });
            }
            updateCart();
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        function updateCart() {
            const cartItems = document.getElementById('cart-items');
            cartItems.innerHTML = '';
            let total = 0;

            cart.forEach((item, index) => {
                const li = document.createElement('li');
                li.innerHTML = `${item.product} - ${item.quantity} ชิ้น (ราคา: ${item.price} บาท)`;

                // สร้างปุ่มลบ
                const removeButton = document.createElement('button');
                removeButton.textContent = 'ลบสินค้า';
                removeButton.onclick = () => removeFromCart(index);

                li.appendChild(removeButton);
                cartItems.appendChild(li);
                total += item.price * item.quantity;
            });

            document.getElementById('total-price').textContent = `รวม: ${total} บาท`;
        }

        function removeFromCart(index) {
            const product = cart[index];
            if (product.quantity > 1) {
                product.quantity -= 1;
            } else {
                cart.splice(index, 1);
            }
            updateCart();
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        function checkout() {
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            window.location.href = `qr.html?amount=${total}`;
        }

        updateCart();
    </script>
</body>
</html>
