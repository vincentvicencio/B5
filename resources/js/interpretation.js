// resources/js/interpretation.js
import Common from './common';

const API_BASE_URL = '/api/interpretations';
const API_TYPES_URL = '/api/interpretation-types';

let interpretationTypes = {};
let currentModalType = null;
let interpretationModal;

// Initialize when document is ready
$(document).ready(function () {
    interpretationModal = new bootstrap.Modal(document.getElementById('interpretationModal'));
    
    loadInterpretationTypes();
    
    // Setup delete confirmation
    Common.setupDeleteConfirmation(API_BASE_URL, function (deletedId) {
        // Show success toast
        Common.showToast('Interpretation deleted successfully.');
        // Reload both sections after deletion
        loadInterpretations('sub-trait');
        loadInterpretations('trait');
    });

    // Event delegation for delete buttons
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        
        const itemId = $(this).data('item-id');
        
        if (itemId) {
            Common.showDeleteConfirmation(
                itemId,
                'Confirm Deletion',
                'Are you sure you want to delete this interpretation? This action cannot be undone.'
            );
        }
    });
});

/**
 * Load interpretation types and store them
 */
function loadInterpretationTypes() {
    $.ajax({
        url: API_TYPES_URL,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                // Store types in an object for easy lookup
                response.data.forEach(type => {
                    interpretationTypes[type.name.toLowerCase().replace(/\s+/g, '-')] = type.id;
                });
                
                // Load both sections
                loadInterpretations('sub-trait');
                loadInterpretations('trait');
            }
        },
        error: function () {
            Common.showToast('Failed to load interpretation types.', 1);
        }
    });
}

/**
 * Load interpretations for a specific type
 */
window.loadInterpretations = function (type) {
    const container = type === 'sub-trait' 
        ? $('#subTraitInterpretationContainer') 
        : $('#traitInterpretationContainer');
    
    container.html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-3">Loading interpretations...</p>
        </div>
    `);

    // Get the type ID based on the type parameter
    const typeKey = type === 'sub-trait' ? 'sub-trait-standard' : 'trait-standard';
    const typeId = interpretationTypes[typeKey];

    if (!typeId) {
        const typeLabel = type === 'sub-trait' ? 'sub-trait' : 'trait';
        container.html(`
            <div class="empty-state text-center py-5">
                <div class="empty-state-icon mb-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h5 class="text-muted mb-2">Configuration Error</h5>
                <p class="text-muted mb-0">
                    ${typeLabel} interpretation type not found. Please contact support.
                </p>
            </div>
        `);
        return;
    }

    $.ajax({
        url: `${API_BASE_URL}/list`,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { type_id: typeId },
        success: function (response) {
            if (response.success) {
                renderInterpretations(response.data, type);
            }
        },
        error: function (xhr) {
            const typeLabel = type === 'sub-trait' ? 'sub-trait' : 'trait';
            container.html(`
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon mb-3">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <h5 class="text-danger mb-2">Error Loading Data</h5>
                    <p class="text-muted mb-0">
                        Failed to load ${typeLabel} interpretations. Please try refreshing the page.
                    </p>
                </div>
            `);
            Common.showToast('Failed to load interpretations.', 1);
        }
    });
};

/**
 * Render interpretations in the container
 */
function renderInterpretations(data, type) {
    const container = type === 'sub-trait' 
        ? $('#subTraitInterpretationContainer') 
        : $('#traitInterpretationContainer');

    if (data.length === 0) {
        const typeLabel = type === 'sub-trait' ? 'sub-trait' : 'trait';
        container.html(`
            <div class="text-center py-4">
                <p class="text-muted mb-0">No ${typeLabel} interpretations defined yet.</p>
            </div>
        `);
        return;
    }

    let html = '';
    data.forEach((item, index) => {
        html += createInterpretationCard(item, index + 1, type);
    });
    
    container.html(html);
}

/**
 * Create HTML for a single interpretation card
 */
function createInterpretationCard(item, index, type) {
    return `
        <div class="interpretation-card" data-interpretation-id="${item.id}">
            <div class="card-body p-3">
                <div class="d-flex align-items-start">
                    <div class="interpretation-number me-3">
                        ${index}
                    </div>

                    <div class="interpretation-content flex-grow-1">
                        <span class="trait-level-label">Trait Level</span>
                        <div class="trait-level-value">${escapeHtml(item.trait_level)}</div>
                        
                        <span class="interpretation-label">Interpretation</span>
                        <div class="interpretation-text">
                            ${escapeHtml(item.interpretation)}
                        </div>
                    </div>

                    <div class="card-actions d-flex ms-3">
                        <button class="btn btn-link btn-edit p-1 me-1" 
                                onclick="showEditInterpretationModal(${item.id}, '${type}')" 
                                title="Edit">
                            <i class="bi bi-pencil fs-5"></i>
                        </button>
                        <button class="btn btn-link btn-delete p-1" 
                                data-item-id="${item.id}" 
                                data-type="${type}"
                                title="Delete">
                            <i class="bi bi-trash fs-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}
/**
 * Show modal for adding new interpretation
 */
window.showAddInterpretationModal = function (type) {
    currentModalType = type;
    
    const title = type === 'sub-trait' 
        ? 'Add New Sub-Trait Interpretation' 
        : 'Add New Trait Interpretation';
    
    $('#interpretationModalLabel').text(title);
    $('#interpretationId').val('');
    $('#traitLevel').val('');
    $('#interpretationText').val('');
    
    // Set the type ID
    const typeKey = type === 'sub-trait' ? 'sub-trait-standard' : 'trait-standard';
    $('#interpretationTypeId').val(interpretationTypes[typeKey]);
    
    interpretationModal.show();
};

/**
 * Show modal for editing interpretation
 */
window.showEditInterpretationModal = function (id, type) {
    currentModalType = type;
    
    const title = type === 'sub-trait' 
        ? 'Edit Sub-Trait Interpretation' 
        : 'Edit Trait Interpretation';
    
    // Fetch the interpretation data
    $.ajax({
        url: `${API_BASE_URL}/${id}`,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                const data = response.data;
                
                $('#interpretationModalLabel').text(title);
                $('#interpretationId').val(data.id);
                $('#interpretationTypeId').val(data.interpretation_type_id);
                $('#traitLevel').val(data.trait_level);
                $('#interpretationText').val(data.interpretation);
                
                interpretationModal.show();
            }
        },
        error: function () {
            Common.showToast('Failed to load interpretation data.', 1);
        }
    });
};

/**
 * Save interpretation (create or update)
 */
window.saveInterpretation = function () {
    const id = $('#interpretationId').val();
    const typeId = $('#interpretationTypeId').val();
    const level = $('#traitLevel').val().trim();
    const interpretation = $('#interpretationText').val().trim();

    // Validation
    if (!typeId || !level || !interpretation) {
        Common.showToast('Please fill out all required fields.', 1);
        return;
    }

    const data = {
        interpretation_type_id: typeId,
        trait_level: level,
        interpretation: interpretation,
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    const url = id ? `${API_BASE_URL}/${id}` : API_BASE_URL;
    const method = id ? 'PUT' : 'POST';

    if (method === 'PUT') {
        data._method = 'PUT';
    }

    $.ajax({
        url: url,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        success: function (response) {
            if (response.success) {
                Common.showToast(response.message || 'Interpretation saved successfully.');
                interpretationModal.hide();
                loadInterpretations(currentModalType);
            }
        },
        error: function (xhr) {
            const message = xhr.responseJSON?.message || 'Failed to save interpretation.';
            Common.showToast(message, 1);
        }
    });
};

/**
 * Escape HTML
 */
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, m => map[m]);
}