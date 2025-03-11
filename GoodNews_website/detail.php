<?php
session_start();
$newsItems = $_SESSION['newsItems'] ?? [];
// Debug: Log session data
error_log('Session newsItems: ' . print_r($newsItems, true));
// Get the article ID from the URL
$id = isset($_GET['id']) ? (string)$_GET['id'] : '0'; // Ensure string type for comparison
// Find the article by ID (compare as strings)
$article = array_filter($newsItems, fn($item) => (string)$item['id'] === $id);
$article = array_values($article)[0] ?? null;
// Debug: Log the ID and found article
error_log('Requested ID: ' . $id);
error_log('Found article: ' . print_r($article, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Detail - Positive News That Inspires</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .detail-image img {
            max-width: 300px;
            height: auto;
            border-radius: 5px;
        }
        .detail-content {
            margin-top: 20px;
        }
        .back-button {
            padding: 8px 15px;
            font-size: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <section class="header-main">
        <div class="header">
            <div class="header-logo">
                <h1 class="header-titel">DE EEUWIGE OPTIMIST</h1>
                <p class="header-subtitel">Positive News That Inspires</p>
            </div>
        </div>
    </section>
    <section class="content-main">
        <div class="content">
            <div class="detail-container">
                <?php if ($article): ?>
                    <h2><?php echo htmlspecialchars($article['title'] ?? 'No title'); ?></h2>
                    <div class="detail-image">
                        <img src="<?php echo $article['image'] ? 'image-proxy.php?url=' . urlencode($article['image']) : 'images/placeholder.jpg'; ?>" alt="Article Image">
                    </div>
                    <div class="detail-content">
                        <p><strong>Date:</strong> <?php echo date('d-m-y', strtotime($article['published_at'] ?? '')); ?></p>
                        <p><strong>Category:</strong> <span class="category"><?php echo htmlspecialchars($article['categories'][0]['name'] ?? 'News'); ?></span></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($article['description'] ?? 'No description available'); ?></p>
                        <p><strong>Full Article:</strong> <?php echo nl2br(htmlspecialchars($article['body'] ?? 'No full article available')); ?></p>
                        <a href="<?php echo htmlspecialchars($article['href'] ?? '#'); ?>" target="_blank">Read more on original site</a>
                    </div>
                <?php else: ?>
                    <p>Article not found.</p>
                <?php endif; ?>
                <button class="back-button" onclick="window.location.href='index.php'">Back to News</button>
            </div>
        </div>
    </section>
</body>
</html>