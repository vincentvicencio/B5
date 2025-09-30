document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('name-email-search');
    const interpretationPlaceholder = document.getElementById('interpretation-placeholder');
    const interpretationMenu = document.getElementById('interpretation-menu');
    const recommendationPlaceholder = document.getElementById('recommendation-placeholder');
    const recommendationMenu = document.getElementById('recommendation-menu');
    const registeredIdTable = document.getElementById('registeredidTable');

    // This function applies all filters to the table
    function applyFilters() {
        const searchFilter = searchInput.value.toLowerCase().trim();
        const interpretationValue = interpretationPlaceholder.textContent.toLowerCase().trim();
        const recommendationValue = recommendationPlaceholder.textContent.toLowerCase().trim();

        const tableRows = registeredIdTable.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const interpretation = row.dataset.interpretation.toLowerCase();
            const recommendation = row.dataset.recommendation.toLowerCase();

            const searchMatch = rowText.includes(searchFilter);
            const interpretationMatch = (interpretationValue === 'all interpretation' || interpretation === interpretationValue);
            const recommendationMatch = (recommendationValue === 'all recommendation' || recommendation === recommendationValue);

            if (searchMatch && interpretationMatch && recommendationMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Listen for keyup events on the main search input
    searchInput.addEventListener('keyup', applyFilters);

    // Listen for clicks on the interpretation dropdown menu items
    interpretationMenu.querySelectorAll('a.dropdown-item').forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault();
            interpretationPlaceholder.textContent = this.textContent;
            applyFilters();
        });
    });

    // Listen for clicks on the recommendation dropdown menu items
    recommendationMenu.querySelectorAll('a.dropdown-item').forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault();
            recommendationPlaceholder.textContent = this.textContent;
            applyFilters();
        });
    });
});