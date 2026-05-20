<?php
require_once '../backend/auth.php';
require_once '../backend/database.php';
requireLogin();
$active = 'products';

// Fetch products from DummyJSON
$data = fetchAPI('https://dummyjson.com/products?limit=30');
$products = $data['products'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products — DummyJSON App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            padding: 40px 80px;
            color: white;
            background:
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)),
                url("../assets/bg valo.png") no-repeat center center/cover;
            background-color: white;
        }

        body>div>div:first-child {
            text-align: center;
            margin-bottom: 40px;
        }

        body>div>div:first-child h1 {
            font-family: 'Valorant Font.ttf';
            font-size: 60px;
            color: #ff4655;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        body>div>div:first-child p {
            color: #ffffff;
            font-size: 18px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            gap: 20px;
        }

        .back-btn {
            margin-right: 30px;
            text-decoration: none;
            background: #ff4655;
            color: white;
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: bold;
            transition: 0.3s ease;
            white-space: nowrap;
        }

        .back-btn:hover {
            color: black;
        }

        #searchInput {
            margin-left: 30px;
            width: 500px;
            max-width: 100%;
            padding: 16px 20px;
            border: none;
            outline: solid 2px #ff4655;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
            backdrop-filter: blur(5px);
        }

        #searchInput::placeholder {
            color: #ccc;
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }

        .product-card {
            background: rgba(31, 31, 31, 0.9);
            border-radius: 25px;
            padding: 20px;
            transition: 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            outline: solid 2px #ff4655;
            border: 1px solid transparent;

            display: flex;
            flex-direction: column;
            height: 100%;
            width: 300px;
        }

        .product-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 15px;
        }

        .product-card h4 {
            font-size: 24px;
            margin-bottom: 15px;
            color: white;
            transition: 0.3s;

            min-height: 90px;
            display: flex;
            align-items: flex-start;
        }

        .product-card>div:nth-child(3) {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #ff4655;
        }

        .product-card>div:nth-child(4) {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .product-card>div:nth-child(4) span {
            background: #340004;
            outline: solid #ff4655 2px;
            padding: 8px 14px;
            border-radius: 5px;
            font-size: 14px;
        }

        #noResults {
            text-align: center;
            margin-top: 30px;
            font-size: 20px;
            color: #ff4655;
        }

        @font-face {
            font-family: 'Valorant Font.ttf';
            src: url('../assets/Valorant Font.ttf') format('truetype');
        }

        @media(max-width:768px) {

            body {
                padding: 30px 20px;
            }

            body>div>div:first-child h1 {
                font-size: 42px;
            }

            #postSearch {
                width: 100%;
            }

            .post-card {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div>
        <div>
            <h1>PRODUCTS</h1>
            <p>Showing <?= count($products) ?> products from DummyJSON</p>
        </div>

        <div class="top-bar">
            <input type="text" id="searchInput" placeholder="Search products by name or category…"
                oninput="filterProducts()">

            <a href="dashboard.php" class="back-btn">Go back</a>
        </div>

        <?php if (empty($products)): ?>
            <div>Could not fetch products. Check your internet connection.</div>
        <?php else: ?>
            <div class="product-grid" id="productGrid">
                <?php foreach ($products as $p): ?>
                    <div class="card product-card" data-name="<?= strtolower(htmlspecialchars($p['title'])) ?>"
                        data-cat="<?= strtolower(htmlspecialchars($p['category'])) ?>">
                        <img src="<?= htmlspecialchars($p['thumbnail'] ?? '') ?>" alt="<?= htmlspecialchars($p['title']) ?>"
                            loading="lazy">
                        <h4><?= htmlspecialchars($p['title']) ?></h4>
                        <div>$<?= number_format($p['price'], 2) ?></div>
                        <div>
                            <span><?= htmlspecialchars($p['category']) ?></span>
                            <span>Stock: <?= (int) ($p['stock']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <p id="noResults" style="display:none;">No products match your search.</p>
        <?php endif; ?>
    </div>

    <script>
        function filterProducts() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('#productGrid .product-card');
            let visible = 0;
            cards.forEach(card => {
                const match = card.dataset.name.includes(q) || card.dataset.cat.includes(q);
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            document.getElementById('noResults').style.display = visible === 0 ? '' : 'none';
        }
    </script>
</body>

</html>