// resources/js/respondents.js

// Assuming Common is globally available or imported from the environment
// We rely on the common.js provided in the previous context to have Common class
const Common = window.Common; // Or import Common from './common'; if using modules correctly

const respondentsTable = document.getElementById('respondents-table');
const respondentsTableHead = document.getElementById('respondents-table-header');
const respondentsTableBody = document.getElementById('respondents-table-body');
const recordInfoSpan = document.getElementById('record-info');
const paginationControls = document.getElementById('pagination-controls');

// State to manage current filters and pagination
let currentPage = 1;
let currentSearchTerm = '';
let currentInterpretationFilter = 'all interpretation';

// State to store the dynamic list of trait names for header generation
let dynamicTraitNames = [];

/**
 * Renders the dynamic table headers based on the fetched trait names.
 * This should only run once upon the first successful fetch.
 * @param {Array<string>} traitNames - Array of trait names (e.g., ['Initiative', 'Teamwork']).
 */
function renderDynamicHeader(traitNames) {
    if (traitNames.length === 0 || respondentsTableHead.dataset.headersRendered === 'true') {
        return;
    }

    // Get the fixed elements in the header
    const fixedHeaders = [
        respondentsTableHead.querySelector('th:nth-child(1)'), // #
        respondentsTableHead.querySelector('th:nth-child(2)'), // Respondent
        respondentsTableHead.querySelector('th:nth-last-child(3)'), // Overall Score
        respondentsTableHead.querySelector('th:nth-last-child(2)'), // Interpretation
        respondentsTableHead.querySelector('th:last-child') // Action
    ];

    // Clear existing content completely
    respondentsTableHead.innerHTML = '';
    
    // 1. Insert fixed headers (Initial # and Respondent)
    respondentsTableHead.appendChild(fixedHeaders[0]);
    respondentsTableHead.appendChild(fixedHeaders[1]);

    // 2. Insert dynamic trait headers
    const fragment = document.createDocumentFragment();
    traitNames.forEach(trait => {
        const th = document.createElement('th');
        th.scope = 'col';
        th.textContent = trait; // Trait name (e.g., Initiative)
        fragment.appendChild(th);
    });
    respondentsTableHead.appendChild(fragment);

    // 3. Insert fixed headers (Overall Score, Interpretation, Action)
    respondentsTableHead.appendChild(fixedHeaders[2]);
    respondentsTableHead.appendChild(fixedHeaders[3]);
    respondentsTableHead.appendChild(fixedHeaders[4]);

    // Mark as rendered
    respondentsTableHead.dataset.headersRendered = 'true';
}


/**
 * Renders the table rows based on the API response data.
 * @param {Array} data - Array of respondent objects.
 * @param {Array<string>} traitNames - The array of trait names used to define the score columns order.
 */
function renderTable(data, traitNames) {
    if (!respondentsTableBody) return;

    respondentsTableBody.innerHTML = ''; // Clear existing rows
    const numColumns = 5 + traitNames.length; // 5 fixed + N dynamic

    if (data.length === 0) {
        respondentsTableBody.innerHTML = `
            <tr>
                <td colspan="${numColumns}" class="text-center py-5 text-muted">No respondents found matching your criteria.</td>
            </tr>
        `;
        return;
    }

    data.forEach((respondent, index) => {
        const row = document.createElement('tr');
        row.dataset.id = respondent.id;
        row.dataset.interpretation = respondent.interpretation.toLowerCase();
        
        // Prepare trait cells dynamically using the stored traitNames order
        let traitCells = '';
        traitNames.forEach(traitName => {
            // respondent.scores contains keys like 'Initiative', 'Teamwork' etc.
            const score = respondent.scores[traitName] || 'N/A'; // Use N/A if a score is missing
            traitCells += `<td>${score}</td>`;
        });

        // The index calculation ensures we show correct row numbers on subsequent pages
        const rowNumber = (index + 1) + ((currentPage - 1) * (data.length)); 

        row.innerHTML = `
            <th scope="row">${rowNumber}</th>
            <td>
                <p class="fw-bold mb-1">${respondent.name}</p>
                <p class="text-muted mb-0">${respondent.email}</p>
            </td>
            ${traitCells}
            <td>${respondent.overall_score}</td>
            <td><span class="badge rounded-pill text-bg-${getInterpretationClass(respondent.interpretation)}">${respondent.interpretation}</span></td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light p-1 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item view-btn" href="#" data-id="${respondent.id}"><i class="bi bi-eye"></i> View</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item delete-btn text-danger" href="#" data-id="${respondent.id}"><i class="bi bi-trash"></i> Delete</a></li>
                    </ul>
                </div>
            </td>
        `;

        respondentsTableBody.appendChild(row);
    });

    // Re-attach listeners after rendering
    attachActionListeners();
}

/**
 * Gets a Bootstrap class for the interpretation badge.
 */
function getInterpretationClass(interpretation) {
    switch(interpretation.toLowerCase()) {
        case 'high':
            return 'success';
        case 'moderate':
            return 'warning';
        case 'low':
            return 'danger';
        default:
            return 'secondary';
    }
}

/**
 * Renders pagination controls and record info.
 * @param {Object} response - The API response object containing pagination metadata.
 */
function renderFooter(response) {
    if (recordInfoSpan) {
        recordInfoSpan.textContent = `Showing ${response.from} to ${response.to} of ${response.total} data`;
    }

    if (paginationControls) {
        paginationControls.innerHTML = ''; // Clear existing pagination

        const ul = document.createElement('ul');
        ul.className = 'pagination pagination-sm mb-0';

        // Helper to create a page link item
        const createPageItem = (page, text, disabled = false, active = false) => {
            const li = document.createElement('li');
            li.className = `page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}`;
            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = text;
            a.dataset.page = page;

            if (!disabled && !active) {
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    currentPage = page;
                    fetchRespondents();
                });
            }
            li.appendChild(a);
            return li;
        };

        // Previous button
        ul.appendChild(createPageItem(currentPage - 1, 'Previous', currentPage === 1));

        // Page buttons (simplified for up to 3 visible pages)
        let startPage = Math.max(1, currentPage - 1);
        let endPage = Math.min(response.last_page, startPage + 2);
        startPage = Math.max(1, endPage - 2); // Adjust start page if near the end

        for (let i = startPage; i <= endPage; i++) {
            ul.appendChild(createPageItem(i, i, false, i === currentPage));
        }
        
        // Next button
        ul.appendChild(createPageItem(currentPage + 1, 'Next', currentPage === response.last_page));

        paginationControls.appendChild(ul);
    }
}

/**
 * Fetches respondent data from the server via AJAX.
 */
async function fetchRespondents() {
    const numCols = dynamicTraitNames.length > 0 ? 5 + dynamicTraitNames.length : 10;
    
    // Show loading state
    respondentsTableBody.innerHTML = `
        <tr>
            <td colspan="${numCols}" class="text-center py-5 text-muted">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div> Loading data...
            </td>
        </tr>
    `;
    
    // Prepare the query parameters
    const params = { 
        page: currentPage,
        search: currentSearchTerm,
        interpretation: currentInterpretationFilter
    };

    try {
        // Use Common.processData for the AJAX request
        const response = await Common.processData('/api/respondents/list', 'GET', params);

        if (response && response.data) {
            // 1. Handle dynamic headers (only on the first load)
            if (response.trait_names && dynamicTraitNames.length === 0) {
                dynamicTraitNames = response.trait_names;
                renderDynamicHeader(dynamicTraitNames);
            }

            // 2. Render table body
            renderTable(response.data, dynamicTraitNames);
            
            // 3. Render footer/pagination
            renderFooter(response);
        } else {
            respondentsTableBody.innerHTML = `
                <tr>
                    <td colspan="${numCols}" class="text-center py-5 text-danger">Failed to load data. Please check the server response.</td>
                </tr>
            `;
        }
    } catch (error) {
        console.error('Error fetching respondents:', error);
        respondentsTableBody.innerHTML = `
            <tr>
                <td colspan="${numCols}" class="text-center py-5 text-danger">An error occurred while fetching data.</td>
            </tr>
        `;
    }
}

/**
 * Attach listeners for View and Delete buttons in the table.
 */
function attachActionListeners() {
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const id = e.currentTarget.dataset.id;
            const row = e.currentTarget.closest('tr');
            // Get the respondent's name from the second <td>'s first <p>
            const name = row.querySelector('td:nth-child(2) p:first-child').textContent.trim();
            
            Common.showDeleteConfirmation(
                id,
                'Confirm Deletion',
                `Are you sure you want to delete the assessment results for: ${name}?`
            );
        });
    });

    // View buttons (dummy implementation for now)
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const id = e.currentTarget.dataset.id;
            Common.showToast(`Viewing details for Respondent ID ${id}.`, 0);
            // In a real application, you would navigate here: window.location.href = `/respondents/show/${id}`;
        });
    });
}

/**
 * Setup search and filter inputs to trigger data fetch.
 */
function setupFilters() {
    const searchInput = document.getElementById('name-email-search');
    const interpretationPlaceholder = document.getElementById('interpretation-placeholder');
    const interpretationMenu = document.getElementById('interpretation-menu');

    // 1. Search Input Handler (Debounced)
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const newSearchTerm = searchInput.value.trim();
                if (newSearchTerm !== currentSearchTerm) {
                    currentSearchTerm = newSearchTerm;
                    currentPage = 1; // Reset page on new search
                    fetchRespondents();
                }
            }, 500); // Debounce search
        });
    }

    // 2. Interpretation Filter Handler
    if (interpretationMenu && interpretationPlaceholder) {
        interpretationMenu.querySelectorAll('a.dropdown-item').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                interpretationPlaceholder.textContent = this.textContent;
                const newFilter = this.textContent.toLowerCase().trim();

                if (newFilter !== currentInterpretationFilter) {
                    currentInterpretationFilter = newFilter;
                    currentPage = 1; // Reset page on filter change
                    fetchRespondents();
                }
            });
        });
    }

    // 3. Set up the global delete confirmation for the respondents API
    Common.setupDeleteConfirmation('/api/respondents', (deletedId) => {
        // After successful deletion, refresh the table data.
        fetchRespondents(); 
    });
}


document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if the table container exists
    if (respondentsTable) {
        setupFilters();
        fetchRespondents(); // Initial load
    }
});
