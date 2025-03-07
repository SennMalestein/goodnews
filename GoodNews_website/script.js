document.addEventListener('DOMContentLoaded', async function() {
    const tbody = document.getElementById('newsBody');
    const table = document.getElementById('newsTable');
    const headers = table.querySelectorAll('th');

    // Function to populate the table with news items
    async function loadNews() {
        try {
            const response = await fetch('api-proxy.php', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Full API Response:', data); // Log the entire API response for debugging
            
            if (data.error) {
                throw new Error(data.error);
            }

            const newsItems = data.results || []; // Get all news items from the API response
            console.log('News Items:', newsItems); // Log the news items array

            const limitedNewsItems = newsItems.slice(0, 20); // Limit to first 20 items

            // Clear the table body before populating
            tbody.innerHTML = '';

            limitedNewsItems.forEach((item, index) => {
                // Debug: Log image and categories for each item
                console.log(`Item ${index}:`, {
                    image: item.image,
                    categories: item.categories,
                    title: item.title
                });

                const date = new Date(item.published_at);
                const formattedDate = date.toLocaleDateString('en-US', { 
                    day: '2-digit', 
                    month: '2-digit', 
                    year: '2-digit' 
                });

                // Debug image URL construction
                const rawImageUrl = item.image || 'images/placeholder.jpg';
                const imageUrl = item.image 
                    ? `image-proxy.php?url=${encodeURIComponent(item.image)}` 
                    : 'images/placeholder.jpg';
                console.log(`Item ${index} Image URL:`, imageUrl); // Log the constructed image URL

                // Use the first category name if available, otherwise fallback to "News" with debug
                let categoryName = 'News'; // Default value
                if (item.categories && Array.isArray(item.categories) && item.categories.length > 0) {
                    categoryName = item.categories[0].name;
                    console.log(`Item ${index} Category Name:`, categoryName); // Debug category assignment
                } else {
                    console.log(`Item ${index} Categories check failed:`, {
                        categories: item.categories,
                        isArray: Array.isArray(item.categories),
                        length: item.categories ? item.categories.length : 'undefined'
                    });
                }

                // Create the row and append it to the table
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${formattedDate || 'N/A'}</td>
                    <td><img src="${imageUrl}" alt="News image" onerror="console.log('Image load failed for:', '${imageUrl}'); this.src='images/placeholder.jpg';"></td>
                    <td>${item.title || 'No title'}</td>
                    <td><span class="category">${categoryName}</span></td>
                    <td>${item.description || 'No description available'}</td>
                `;
                tbody.appendChild(row);
                console.log(`Row ${index} added to table with Category: ${categoryName}, Image: ${imageUrl}`); // Debug row addition
            });

            // Debug table contents after population
            console.log('Table contents after population:', tbody.innerHTML);
        } catch (error) {
            console.error('Error fetching news:', error);
            tbody.innerHTML = '<tr><td colspan="5">Failed to load news. Please try again later. Error: ' + error.message + '</td></tr>';
        }
    }

    // Call loadNews only once when the page loads
    loadNews();

    // Add sorting functionality
    headers.forEach((header, index) => {
        header.addEventListener('click', () => {
            sortTable(index);
        });
    });

    function sortTable(columnIndex) {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent;
            const bValue = b.cells[columnIndex].textContent;
            return aValue.localeCompare(bValue);
        });

        // Clear and re-append sorted rows without triggering API calls
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        rows.forEach(row => tbody.appendChild(row));
        console.log('Table contents after sorting:', tbody.innerHTML); // Debug table after sorting
    }
});