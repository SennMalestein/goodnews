document.addEventListener('DOMContentLoaded', async function () {
    const tbody = document.getElementById('news-body');
    const table = document.getElementById('news-table');
    const filterCategory = document.getElementById('filter-category');
    const filterSource = document.getElementById('filter-source');
    const filterButton = document.getElementById('filter-button');

    let newsItems = []; // Store the fetched news items
    let sessionReady = false; // Flag to track if session is set

    async function loadNews() {
        try {
            const response = await fetch('api-proxy.php', {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            if (data.error) throw new Error(data.error);
            
            newsItems = data.results || []; // Store full news items list
            console.log('Fetched newsItems:', newsItems); // Debug: Log fetched items

            // Attempt to save newsItems in PHP session via AJAX
            try {
                const formData = new FormData();
                formData.append('newsItems', JSON.stringify(newsItems));
                const sessionResponse = await fetch('save_session.php', {
                    method: 'POST',
                    body: formData
                });
                if (!sessionResponse.ok) {
                    console.warn('Session save failed with status:', sessionResponse.status);
                } else {
                    const sessionResult = await sessionResponse.json();
                    console.log('Session save response:', sessionResult); // Debug: Log session save result
                    if (sessionResult.status === 'success') {
                        sessionReady = true; // Set flag when session is successfully saved
                    } else {
                        console.warn('Session save returned unexpected result:', sessionResult);
                    }
                }
            } catch (sessionError) {
                console.error('Error saving session:', sessionError);
            }

            // Proceed with populating filters and table even if session save fails
            populateFilters(newsItems);
            displayNews(newsItems);
        } catch (error) {
            console.error('Error fetching news:', error);
            tbody.innerHTML = '<tr><td colspan="5">Failed to load news. Please try again later.</td></tr>';
        }
    }

    function populateFilters(items) {
        const categories = new Set();
        const sources = new Set();

        items.forEach(item => {
            if (item.categories && item.categories.length > 0) categories.add(item.categories[0].name);
            if (item.source && item.source.domain) sources.add(item.source.domain); // Use source.domain
        });

        filterCategory.innerHTML = '<option value="">All Categories</option>' +
            Array.from(categories).map(cat => `<option value="${cat}">${cat}</option>`).join('');
        filterSource.innerHTML = '<option value="">All Sources</option>' +
            Array.from(sources).map(src => `<option value="${src}">${src}</option>`).join('');
    }

    function displayNews(items) {
        tbody.innerHTML = '';
        items.slice(0, 20).forEach(item => {
            const date = new Date(item.published_at).toLocaleDateString('en-US', { day: '2-digit', month: '2-digit', year: '2-digit' });
            const category = item.categories?.[0]?.name || 'News';
            const imageUrl = item.image ? `image-proxy.php?url=${encodeURIComponent(item.image)}` : 'images/placeholder.jpg';
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${date}</td>
                <td><img src="${imageUrl}" alt="News image" onerror="this.src='images/placeholder.jpg';"></td>
                <td>${item.title || 'No title'}</td>
                <td><span class="category">${category}</span></td>
                <td>${item.description || 'No description available'}</td>
            `;
            // Make the row clickable, but only if session is ready
            row.addEventListener('click', () => {
                if (!sessionReady) {
                    alert('Session is not yet ready. Please wait a moment and try again.');
                    return;
                }
                window.location.href = `detail.php?id=${item.id}`;
            });
            tbody.appendChild(row);
        });
    }

    function filterNews() {
        const selectedCategory = filterCategory.value;
        const selectedSource = filterSource.value;
        
        const filteredItems = newsItems.filter(item => {
            const categoryMatch = !selectedCategory || (item.categories?.[0]?.name === selectedCategory);
            const sourceMatch = !selectedSource || (item.source?.domain === selectedSource); // Use source.domain
            return categoryMatch && sourceMatch;
        });
        displayNews(filteredItems);
    }

    filterButton.addEventListener('click', filterNews);

    loadNews();
});