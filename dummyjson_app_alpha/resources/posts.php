<?php
require_once '../backend/auth.php';
require_once '../backend/database.php';
requireLogin();
$active = 'posts';

$data = fetchAPI('https://dummyjson.com/posts?limit=30');
$posts = $data['posts'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts — DummyJSON App</title>

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
                linear-gradient(rgba(0, 0, 0, 0.78), rgba(0, 0, 0, 0.85)),
                url("../assets/bg valo.png") no-repeat center center/cover;
        }

        body>div>div:first-child {
            text-align: center;
            margin-bottom: 40px;
        }

        body>div>div:first-child h1 {
            font-family: 'Valorant Font.ttf';
            font-size: 80px;
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
            text-decoration: none;
            background: #000539;
            color: white;
            margin-right: 160px;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s ease;
            white-space: nowrap;
        }

        .back-btn:hover {
            background: #ff4655;
        }

        #postSearch {
            width: 500px;
            max-width: 100%;
            margin-left: 165px;
            padding: 16px 20px;
            border-radius: 15px;
            outline: solid 2px #030028e6;
            background: rgba(255, 255, 255, 0.1);
            color: black;
            font-size: 16px;
        }

        #postSearch::placeholder {
            color: #ccc;
        }

        .post-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }

        .post-card {
            width: 700px;
            min-height: 260px;
            background: #030028e6;
            border-radius: 25px;
            padding: 25px;

            display: flex;
            flex-direction: column;
        }

        .post-card h4 {
            font-size: 26px;
            margin-bottom: 15px;
            color: white;

            min-height: 40px;
        }

        .post-card p {
            color: #ffe5e8;
            line-height: 1.6;
            margin-bottom: 20px;
            min-height: 70px;
        }

        .post-card>div:nth-child(3) {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .post-card>div:nth-child(3) span {
            background: rgba(0, 0, 0, 0.2);
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 14px;
        }

        .post-card>div:nth-child(4) {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .post-card>div:nth-child(4) span {
            background: rgba(255, 255, 255, 0.12);
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 14px;
        }

        #noPostResults {
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
            <h1>POST</h1>
            <p>Showing <?= count($posts) ?> posts from DummyJSON</p>
        </div>

        <div class="top-bar">
            <input type="text" id="postSearch" placeholder="Search posts by title or tag…" oninput="filterPosts()">

            <a href="dashboard.php" class="back-btn">Go back</a>
        </div>

        <?php if (empty($posts)): ?>
            <div class="alert alert-error">Could not fetch posts. Check your internet connection.</div>
        <?php else: ?>
            <div class="post-grid" id="postGrid">
                <?php foreach ($posts as $post): ?>
                    <?php
                    $tags = $post['tags'] ?? [];
                    $tagsStr = strtolower(implode(' ', $tags));
                    ?>
                    <div class="card post-card"
                        data-search="<?= strtolower(htmlspecialchars($post['title'])) . ' ' . $tagsStr ?>">
                        <h4><?= htmlspecialchars($post['title']) ?></h4>
                        <p><?= htmlspecialchars($post['body']) ?></p>

                        <?php if (!empty($tags)): ?>
                            <div>
                                <?php foreach ($tags as $tag): ?>
                                    <span>#<?= htmlspecialchars($tag) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div>
                            <?php if (isset($post['reactions'])): ?>
                                <?php if (is_array($post['reactions'])): ?>
                                    <span>👍 <?= (int) ($post['reactions']['likes'] ?? 0) ?></span>
                                    <span>👎 <?= (int) ($post['reactions']['dislikes'] ?? 0) ?></span>
                                <?php else: ?>
                                    <span>❤️ <?= (int) $post['reactions']['love'] ?? 0 ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span>👁 <?= (int) ($post['views'] ?? 0) ?> views</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <p id="noPostResults" style="display:none;">No posts match your search.</p>
        <?php endif; ?>
    </div>

    <script>
        function filterPosts() {
            const q = document.getElementById('postSearch').value.toLowerCase();
            const cards = document.querySelectorAll('#postGrid .post-card');
            let visible = 0;

            cards.forEach(card => {
                const match = card.dataset.search.includes(q);
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            const noResults = document.getElementById('noPostResults');
            if (noResults) {
                noResults.style.display = visible === 0 ? '' : 'none';
            }
        }
    </script>
</body>

</html>