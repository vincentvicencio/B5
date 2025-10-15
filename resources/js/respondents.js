// resources/js/respondents.js

const respondentsTable = document.getElementById('respondents-table');
const respondentsTableHead = document.getElementById('respondents-table-header');
const respondentsTableBody = document.getElementById('respondents-table-body');
const recordInfoSpan = document.getElementById('record-info');
const paginationControls = document.getElementById('pagination-controls');

let currentPage = 1;
let currentSearchTerm = '';
let currentInterpretationFilter = 'all interpretation';
let dynamicTraitNames = [];

/**
 * Render table header
 */
function renderDynamicHeader(traits) {
    if (respondentsTableHead.dataset.headersRendered === 'true') return;
    console.log('Rendering table header with traits:', traits);

    respondentsTableHead.innerHTML = '';

    // Base headers (before traits)
    const baseHeaders = [
        ['#', ''],
        ['Respondent', '']
    ];

    // Create dynamic trait headers with subtitles
    const traitHeaders = traits.map(trait => {
        return [trait, 'text-center', trait.description || ''];
    });

    // Add trailing fixed headers
    const trailingHeaders = [
        ['Overall Score', 'text-center'],
        ['Interpretation', 'text-center'],
        ['All Response', 'text-center'],
        ['Action', 'text-center']
    ];

    // Combine everything
    const allHeaders = [
        ...baseHeaders,
        ...traitHeaders,
        ...trailingHeaders
    ];
    console.log('All headers to render:', allHeaders);
    // Render each <th>
    allHeaders.forEach(([text, className, subtitle]) => {
        const th = document.createElement('th');
        th.scope = 'col';
        th.className = className;

        // If there's a subtitle (from DB or OCEAN mapping)
        if (subtitle) {
            th.innerHTML = `
                <div class="text-center">
                    <strong>${text}</strong><br>
                    <small class="text-muted">${subtitle}</small>
                </div>`;
        } else {
            th.textContent = text;
        }

        respondentsTableHead.appendChild(th);
    });

    respondentsTableHead.dataset.headersRendered = 'true';
}


/**
 * Render table rows
 */
function renderTable(data, traitNames) {
    if (!respondentsTableBody) return;
    respondentsTableBody.innerHTML = '';

    const numColumns = 10 + traitNames.length;

    if (data.length === 0) {
        respondentsTableBody.innerHTML = `
            <tr>
                <td colspan="${numColumns}" class="text-center py-5 text-muted">
                    No respondents found matching your criteria.
                </td>
            </tr>`;
        return;
    }

    data.respondents.forEach((respondent, index) => {
     
        const row = document.createElement('tr');
        row.dataset.id = respondent.id;
        row.dataset.interpretation = respondent.interpretation?.toLowerCase() || '';

        const rowNumber = (currentPage - 1) * data.length + index + 1;

        const oceanCells = dynamicTraitNames.map(traitName => {
            const score = respondent.scores?.[traitName] || 'N/A';
            return `<td class="text-center">${score}</td>`;
        }).join('');


        let traitCells = '';
        traitNames.forEach(traitName => {
            const score = respondent.scores?.[traitName] || 'N/A';
            traitCells += `<td class="text-center">${score}</td>`;
        });

        console.log('Rendering respondent:', respondent);
        row.innerHTML = `
            <th scope="row">${row.dataset.id}</th>
            <td>
                <p class="fw-bold mb-1">${respondent.name || 'Unknown'}</p>
                <p class="text-muted mb-0">${respondent.email || ''}</p>
            </td>
            ${oceanCells}
            <td class="text-center">${respondent.overall_score || 'N/A'}</td>
            <td class="text-center">
                <span class="badge rounded-pill text-bg-${getInterpretationClass(respondent.interpretation)}">
                    ${respondent.interpretation || 'N/A'}
                </span>
            </td>
            <td class="text-center">${respondent.all_response || 'N/A'}</td>
            <td class="text-center">
                <div class="dropdown">
                    <button class="btn btn-sm btn-light p-1 border-0" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item view-btn" href="#" data-id="${respondent.id}"><i class="bi bi-eye me-2"></i>View</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item delete-btn text-danger" href="#" data-id="${respondent.id}"><i class="bi bi-trash me-2"></i>Delete</a></li>
                    </ul>
                </div>
            </td>
        `;

        respondentsTableBody.appendChild(row);
    });

    attachActionListeners();
}

function getInterpretationClass(interpretation) {
    if (!interpretation) return 'secondary';
    switch (interpretation.toLowerCase()) {
        case 'high': return 'success';
        case 'moderate': return 'warning';
        case 'low': return 'danger';
        default: return 'secondary';
    }
}

function renderFooter(response) {
    if (recordInfoSpan) {
        recordInfoSpan.textContent = `Showing ${response.from || 0} to ${response.to || 0} of ${response.total || 0} data`;
    }

    if (!paginationControls) return;
    paginationControls.innerHTML = '';
    const ul = document.createElement('ul');
    ul.className = 'pagination pagination-sm mb-0';

    const createPageItem = (page, text, disabled = false, active = false) => {
        const li = document.createElement('li');
        li.className = `page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}`;
        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.textContent = text;
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

    ul.appendChild(createPageItem(currentPage - 1, 'Previous', currentPage === 1));
    for (let i = 1; i <= response.last_page; i++) {
        ul.appendChild(createPageItem(i, i, false, i === currentPage));
    }
    ul.appendChild(createPageItem(currentPage + 1, 'Next', currentPage === response.last_page));
    paginationControls.appendChild(ul);
}

/**
 * Fetch respondents via AJAX
 */
async function fetchRespondents() {
    const numCols = 10 + dynamicTraitNames.length;
    respondentsTableBody.innerHTML = `
        <tr>
            <td colspan="${numCols}" class="text-center py-5 text-muted">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div> Loading data...
            </td>
        </tr>
    `;

    const params = new URLSearchParams({
        page: currentPage,
        search: currentSearchTerm,
        interpretation: currentInterpretationFilter
    }); 

    const response = await fetch(`/api/respondents?${params.toString()}`);
        const data = await response.json();
        console.log('Fetched respondents data:', data.data);

    try { 
       

        if (data) { 
            if (data.traits && dynamicTraitNames.length === 0) {
                dynamicTraitNames = data.traits;
            }

            renderDynamicHeader(dynamicTraitNames);
            renderTable(data, dynamicTraitNames);
            renderFooter(data);
        } else {
            console.log('Invalid response structure:', data);
            respondentsTableBody.innerHTML = `
                <tr>
                    <td colspan="${numCols}" class="text-center py-5 text-danger">
                        No data found or invalid response.
                    </td>
                </tr>`;
        }
    } catch (error) {
        console.error('Error fetching respondents:', error);
        respondentsTableBody.innerHTML = `
            <tr>
                <td colspan="${numCols}" class="text-center py-5 text-danger">
                    An error occurred while fetching data hehe.
                </td>
            </tr>`;
    }
}

function attachActionListeners() {
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const id = e.currentTarget.dataset.id;
            window.location.href = `/respondents/${id}`;
        });
    });
}

function setupFilters() {
    const searchInput = document.getElementById('name-email-search');
    const interpretationPlaceholder = document.getElementById('interpretation-placeholder');
    const interpretationMenu = document.getElementById('interpretation-menu');

    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const newSearchTerm = searchInput.value.trim();
                if (newSearchTerm !== currentSearchTerm) {
                    currentSearchTerm = newSearchTerm;
                    currentPage = 1;
                    fetchRespondents();
                }
            }, 400);
        });
    }

    if (interpretationMenu && interpretationPlaceholder) {
        interpretationMenu.querySelectorAll('a.dropdown-item').forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                interpretationPlaceholder.textContent = this.textContent;
                const newFilter = this.textContent.toLowerCase().trim();

                if (newFilter !== currentInterpretationFilter) {
                    currentInterpretationFilter = newFilter;
                    currentPage = 1;
                    fetchRespondents();
                }
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (respondentsTable) { 
        // renderDynamicHeader([]);
        setupFilters();
        fetchRespondents();
    }
});
