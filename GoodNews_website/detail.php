<?php
session_start();
$newsItems = $_SESSION['newsItems'] ?? [];

// Debug: Log session data
error_log('Session newsItems: ' . print_r($newsItems, true));

// Get the article ID from the URL
$id = isset($_GET['id']) ? (string)$_GET['id'] : '0';

// Find the article by ID (compare as strings)
$article = array_filter($newsItems, fn($item) => (string)$item['id'] === $id);
$article = array_values($article)[0] ?? null;

// Debug: Log the ID and found article
error_log('Requested ID: ' . $id);
error_log('Found article: ' . print_r($article, true));
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($article['title'] ?? 'Artikel Detail'); ?></title>
  <link rel="stylesheet" href="stylesheet.css">
  <style>
    /* Resetten van de standaardmarges en paddings */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f5f5f5;
    }

    /* Bovenste navigatiebalk (back to home) */
    header {
        padding: 20px;
        border-bottom: 1px solid #ddd;
    }

    header a {
        text-decoration: none;
        color: #007BFF;
        font-weight: bold;
        font-size: 1rem;
    }

    /* Container voor de hoofdcontent */
    main {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Afbeelding bovenaan */
    .image-container {
        width: 60%;
        margin-bottom: 20px;
    }

    .image-container img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 4px;
    }

    /* Datum en categorie */
    .meta-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        font-size: 0.95rem;
        color: #666;
    }

    .meta-info .category {
        background-color: #e3f2fd;
        padding: 5px 10px;
        border-radius: 20px;
        display: inline-block;
        text-align: center;
        font-weight: bold;
    }

    .meta-info .date {
        font-weight: 500;
        font-weight: bold;
    }

    /* Titel van het artikel */
    h1 {
        font-size: 1.8rem;
        margin-bottom: 20px;
    }

    /* Paragraaf styling */
    p {
        line-height: 1.6;
        margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <header>
    <a href="index.php">back to home</a>
  </header>

  <main>
    <?php if ($article): ?>
      <div class="image-container">
        <img src="<?php echo $article['image'] ? 'image-proxy.php?url=' . urlencode($article['image']) : 'images/placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($article['title'] ?? 'Afbeelding'); ?>">
      </div>

      <div class="meta-info">
        <span class="category"><?php echo htmlspecialchars($article['categories'][0]['name'] ?? 'Nieuws'); ?></span>
        <span class="date"><?php echo date('d-m-y', strtotime($article['published_at'] ?? '')); ?></span>
      </div>

      <h1><?php echo htmlspecialchars($article['title'] ?? 'Geen titel'); ?></h1>

      <p>
        <?php echo nl2br(htmlspecialchars($article['description'] ?? 'Geen beschrijving beschikbaar')); ?>
      </p>
      <p>
        <?php echo nl2br(htmlspecialchars($article['body'] ?? 'Geen volledig artikel beschikbaar')); ?>
      </p>
      <p>
        <a href="<?php echo htmlspecialchars($article['href'] ?? '#'); ?>" target="_blank">Lees meer op de originele site</a>
      </p>
    <?php else: ?>
      <p>Artikel niet gevonden.</p>
    <?php endif; ?>
  </main>
</body>
</html>
