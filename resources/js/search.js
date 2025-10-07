document.addEventListener("DOMContentLoaded", function () {
    // Only run this code if the elements exist (on the respondents page)
    const searchInput = document.getElementById("name-email-search");
    if (!searchInput) {
        console.log(
            "Search input not found - skipping respondents page script"
        );
        return; // Exit early if elements don't exist
    }
    const interpretationPlaceholder = document.getElementById(
        "interpretation-placeholder"
    );
    const interpretationMenu = document.getElementById("interpretation-menu");
    const recommendationPlaceholder = document.getElementById(
        "recommendation-placeholder"
    );
    const recommendationMenu = document.getElementById("recommendation-menu");
    const registeredIdTable = document.getElementById("registeredidTable"); // Only proceed if all required elements exist

    if (
        !interpretationPlaceholder ||
        !interpretationMenu ||
        !recommendationPlaceholder ||
        !recommendationMenu ||
        !registeredIdTable
    ) {
        console.log("Some required elements not found - skipping");
        return;
    } // This function applies all filters to the table

    function applyFilters() {
        const searchFilter = searchInput.value.toLowerCase().trim();
        const interpretationValue = interpretationPlaceholder.textContent
            .toLowerCase()
            .trim();
        const recommendationValue = recommendationPlaceholder.textContent
            .toLowerCase()
            .trim();

        const tableRows = registeredIdTable.querySelectorAll("tbody tr");

        tableRows.forEach((row) => {
            const rowText = row.textContent.toLowerCase();
            const interpretation = row.dataset.interpretation.toLowerCase();
            const recommendation = row.dataset.recommendation.toLowerCase();

            const searchMatch = rowText.includes(searchFilter);
            const interpretationMatch =
                interpretationValue === "all interpretation" ||
                interpretation === interpretationValue;
            const recommendationMatch =
                recommendationValue === "all recommendation" ||
                recommendation === recommendationValue;

            if (searchMatch && interpretationMatch && recommendationMatch) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    searchInput.addEventListener("keyup", applyFilters);

    interpretationMenu.querySelectorAll("a.dropdown-item").forEach((item) => {
        item.addEventListener("click", function (event) {
            event.preventDefault();
            interpretationPlaceholder.textContent = this.textContent;
            applyFilters();
        });
    });

    recommendationMenu.querySelectorAll("a.dropdown-item").forEach((item) => {
        item.addEventListener("click", function (event) {
            event.preventDefault();
            recommendationPlaceholder.textContent = this.textContent;
            applyFilters();
        });
    });
});
