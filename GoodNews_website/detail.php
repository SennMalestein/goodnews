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
  <link rel="stylesheet" href="CSS/style.css">
  <style>
    /* Resetten van de standaardmarges en paddings */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        max-width: 900px; /* Breder zodat het niet te smal is */
        background-color: white; /* Witte achtergrond */
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Zachte schaduw voor een mooi effect */
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: #789DFF;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
    }

    .header-subtitel {
        margin: 0;
        color: #fff;
        font-style: italic;
    }

    /* Bovenste navigatiebalk (back to home) */
    header {
        width: 100%;
        background: #345094;
        color: white;
        text-align: center;
        padding: 20px 0;
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

    .back-to-home {
    position: absolute;
    top: 20px;
    left: 20px;
    }

    .back-to-home a {
        text-decoration: none;
        color: white;
        background-color: #007BFF;
        padding: 10px 15px;
        border-radius: 5px;
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
    <section class="header-main">
        <div class="header">
            <div class="header-logo">
                <h1 class="header-titel">DE EEUWIGE OPTIMIST</h1>
                <p class="header-subtitel">Positive News That Inspires</p>
                <div class="back-to-home">
                    <a href="index.php">Back to Home</a>
                </div>
            </div>
        </div>
    </section>
  </header>
    <div class="container">
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
    </div>
</body>
</html>
