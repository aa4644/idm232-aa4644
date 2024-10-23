document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const navButtons = document.getElementById('nav-buttons');
    const closeIcon = document.getElementById('close-icon');
    const navLinks = document.querySelectorAll('.nav-link'); 
    const clearAllText = document.querySelector('.clear-all-text');
    const filterToggleButton = document.querySelector('.filter-toggle-button');
    const filterArea = document.getElementById('filter-area');
    const searchButton = document.querySelector('.search-button');
    const searchInput = document.querySelector('.search-bar');

    // Ensure elements exist
    if (hamburger && navButtons && closeIcon && navLinks.length) {
        // Toggle the menu when the hamburger is clicked
        hamburger.addEventListener('click', () => {
            navButtons.classList.toggle('active');
        });

        // Close the menu when the close icon is clicked
        closeIcon.addEventListener('click', () => {
            navButtons.classList.remove('active'); 
        });

        // Close the menu when a link is clicked
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navButtons.classList.remove('active'); 
            });
        });
    }

    // Clear all dropdowns when clearAllText is clicked
    if (clearAllText) {
        clearAllText.addEventListener('click', () => {
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                dropdown.selectedIndex = 0; 
            });
        });
    }

    // Functionality for search button
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            window.location.href = 'no-results.html';
        });
    }
});
