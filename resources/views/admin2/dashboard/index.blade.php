<x-app-layout>
    <section class="categories-section">
        <div class="categories-header">
            <div class="header-content">
                <p class="header-title">Categories that might interest you</p>
                <a href="#" class="see-all-categories-btn" role="button">See all categories</a>
            </div>
        </div>

        <div class="categories-list">
            <!-- Category: Laptops & Computers -->
            <a href="#" class="category-item">
                <svg class="category-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 16H5a1 1 0 0 1-1-1V5c0-.6.4-1 1-1h14c.6 0 1 .4 1 1v1M9 12H4m8 8V9h8v11h-8Zm0 0H9m8-4a1 1 0 1 0-2 0 1 1 0 0 0 2 0Z"></path>
                </svg>
                <p class="category-title">Laptops &amp; Computers</p>
            </a>

            <!-- Category: TV -->
            <a href="#" class="category-item">
                <svg class="category-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v5m-3 0h6M4 11h16M5 15h14c.6 0 1-.4 1-1V5c0-.6-.4-1-1-1H5a1 1 0 0 0-1 1v9c0 .6.4 1 1 1Z"></path>
                </svg>
                <p class="category-title">TV</p>
            </a>

            <!-- Category: Tablets -->
            <a href="#" class="category-item">
                <svg class="category-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 18h2M5.9 3H18c.5 0 .9.4.9 1v16c0 .6-.4 1-.9 1H6c-.5 0-.9-.4-.9-1V4c0-.6.4-1 .9-1Z"></path>
                </svg>
                <p class="category-title">Tablets</p>
            </a>
        </div>
    </section>
</x-app-layout>

<style>
    .categories-section {
        padding: 2rem;
    }

    .categories-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .header-title {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .see-all-categories-btn {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
        transition: color 0.3s;
    }

    .see-all-categories-btn:hover {
        color: #0056b3;
    }

    .categories-list {
        display: flex;
        gap: 1.5rem;
    }

    .category-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #000;
        transition: transform 0.3s;
    }

    .category-item:hover {
        transform: scale(1.05);
    }

    .category-icon {
        width: 48px;
        height: 48px;
        margin-bottom: 0.5rem;
        color: #6c757d;
    }

    .category-title {
        font-size: 1rem;
        text-align: center;
    }
</style>
