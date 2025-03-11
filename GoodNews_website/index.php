<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Positive News That Inspires</title>
    <link rel="stylesheet" href="style.css">
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
            <div class="filter">
                <label class="filter-label">
                    <div class="filter-name">Category:</div> 
                    <select id="filter-category">
                        <option value="">All Categories</option>
                    </select>
                </label>
                <label class="filter-label">
                    <div class="filter-name">Source: </div>
                    <select id="filter-source">
                        <option value="">All Sources</option>
                    </select>
                </label>
                <button id="filter-button">Confirm</button>
            </div>
            <table id="news-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody id="news-body">
                    <!-- News items will be populated here via JavaScript -->
                </tbody>
            </table>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>