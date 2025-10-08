import Common from './common.js';

const API_BASE = '/api/score-matrix';

// Global variables
let likertScales = [];
let subTraits = [];
let traits = [];
let subTraitInterpretations = [];
let traitInterpretations = [];
let subTraitMatrices = [];
let traitMatrices = [];

// Bootstrap modal instances
let likertModal, subTraitMatrixModal, traitMatrixModal;

// Initialize
$(document).ready(function () {
    likertModal = new bootstrap.Modal(document.getElementById('likertScaleModal'));
    subTraitMatrixModal = new bootstrap.Modal(document.getElementById('subTraitMatrixModal'));
    traitMatrixModal = new bootstrap.Modal(document.getElementById('traitMatrixModal'));

    // Trait select change handler to show sub-traits
    $('#traitSelect').on('change', function() {
        updateTraitSubTraitsDisplay($(this).val());
    });

    // Enter key handlers for all modals
    setupEnterKeyHandlers();

    loadAllData();
});

function setupEnterKeyHandlers() {
    // Likert Scale Modal
    $('#likertScaleModal').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            saveLikertScale();
        }
    });

    // Sub-Trait Matrix Modal
    $('#subTraitMatrixModal').on('keypress', function(e) {
        if (e.which === 13 && !$(e.target).is('select')) {
            e.preventDefault();
            saveSubTraitMatrix();
        }
    });

    // Trait Matrix Modal
    $('#traitMatrixModal').on('keypress', function(e) {
        if (e.which === 13 && !$(e.target).is('select')) {
            e.preventDefault();
            saveTraitMatrix();
        }
    });

    // Handle Enter key in select elements to trigger save
    $('#subTraitSelect, #subTraitInterpretationSelect').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            saveSubTraitMatrix();
        }
    });

    $('#traitSelect, #traitInterpretationSelect').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            saveTraitMatrix();
        }
    });
}

function loadAllData() {
    loadLikertScales();
    loadSubTraits();
    loadTraits();
    loadSubTraitInterpretations();
    loadTraitInterpretations();
    loadSubTraitMatrices();
    loadTraitMatrices();
}

// ============================================================================
// LIKERT SCALE FUNCTIONS
// ============================================================================

function loadLikertScales() {
    $.ajax({
        url: `${API_BASE}/likert-scales`,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            if (response.success) {
                likertScales = response.data;
                renderLikertScales();
            }
        },
        error: function () {
            Common.showToast('Failed to load Likert scales.', 1);
        }
    });
}

function renderLikertScales() {
    const container = $('#likertScalesContainer');
    container.empty();

    if (likertScales.length === 0) {
        container.html('<p class="text-muted text-center mb-0 py-2">No rating scales defined yet.</p>');
        return;
    }

    let html = '';
    likertScales.forEach(scale => {
        html += `
            <div class="likert-scale-item">
                <div>
                    <label class="form-label small fw-semibold mb-1">Rating Value</label>
                    <div class="likert-value-box">${scale.value}</div>
                </div>
                <div>
                    <label class="form-label small fw-semibold mb-1">Rating Label</label>
                    <div class="likert-label-box">${escapeHtml(scale.label)}</div>
                </div>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary" onclick="editLikertScale(${scale.id})" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteLikertScale(${scale.id})" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    container.html(html);
}

window.showAddLikertModal = function () {
    $('#likertScaleModalLabel').text('Add New Rating');
    $('#likertScaleId').val('');
    $('#likertValue').val('');
    $('#likertLabel').val('');
    likertModal.show();
    
    // Focus on first input
    setTimeout(() => $('#likertValue').focus(), 300);
};

window.editLikertScale = function (id) {
    const scale = likertScales.find(s => s.id === id);
    if (!scale) return;

    $('#likertScaleModalLabel').text('Edit Rating');
    $('#likertScaleId').val(scale.id);
    $('#likertValue').val(scale.value);
    $('#likertLabel').val(scale.label);
    likertModal.show();
    
    // Focus on first input
    setTimeout(() => $('#likertValue').focus(), 300);
};

window.saveLikertScale = function () {
    const id = $('#likertScaleId').val();
    const value = $('#likertValue').val();
    const label = $('#likertLabel').val();

    if (!value || !label) {
        Common.showToast('Please fill out all required fields.', 1);
        return;
    }

    const url = id ? `${API_BASE}/likert-scales/${id}` : `${API_BASE}/likert-scales`;
    const type = id ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: type,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { value: value, label: label },
        success: function (response) {
            if (response.success) {
                Common.showToast(response.message || 'Likert scale saved successfully.');
                likertModal.hide();
                loadLikertScales();
            }
        },
        error: function (xhr) {
            const message = xhr.responseJSON?.message || 'Failed to save Likert scale.';
            Common.showToast(message, 1);
        }
    });
};

window.deleteLikertScale = function (id) {
    const scale = likertScales.find(s => s.id === id);
    if (!scale) return;

    Common.showDeleteConfirmation(
        id,
        'Confirm Deletion',
        `Are you sure you want to delete the rating <strong>${escapeHtml(scale.label)} (${scale.value})</strong>?`
    );

    Common.setupDeleteConfirmation(`${API_BASE}/likert-scales`, function () {
        Common.showToast('Likert scale deleted successfully.');
        loadLikertScales();
    });
};

// ============================================================================
// DATA LOADING FOR DROPDOWNS
// ============================================================================

function loadSubTraits() {
    $.ajax({
        url: `${API_BASE}/sub-traits`,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            if (response.success) {
                subTraits = response.data;
                populateSubTraitDropdown('subTraitSelect', subTraits);
            }
        },
        error: function () {
            Common.showToast('Failed to load sub-traits.', 1);
        }
    });
}

function loadTraits() {
    $.ajax({
        url: `${API_BASE}/traits`,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            if (response.success) {
                traits = response.data;
                populateTraitDropdown('traitSelect', traits);
            }
        },
        error: function () {
            Common.showToast('Failed to load traits.', 1);
        }
    });
}

function loadSubTraitInterpretations() {
    $.ajax({
        url: `${API_BASE}/sub-trait-interpretations`,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            if (response.success) {
                subTraitInterpretations = response.data;
                populateInterpretationDropdown('subTraitInterpretationSelect', subTraitInterpretations);
            }
        },
        error: function () {
            Common.showToast('Failed to load sub-trait interpretations.', 1);
        }
    });
}

function loadTraitInterpretations() {
    $.ajax({
        url: `${API_BASE}/trait-interpretations`,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            if (response.success) {
                traitInterpretations = response.data;
                populateInterpretationDropdown('traitInterpretationSelect', traitInterpretations);
            }
        },
        error: function () {
            Common.showToast('Failed to load trait interpretations.', 1);
        }
    });
}

function populateSubTraitDropdown(selectId, data) {
    const select = $(`#${selectId}`);
    select.empty();
    select.append(`<option value="">Select Sub-Trait</option>`);
    
    // Group sub-traits by parent trait
    const grouped = {};
    data.forEach(item => {
        const traitTitle = item.trait ? item.trait.title : 'No Trait';
        const traitId = item.trait ? item.trait.id : 0;
        
        if (!grouped[traitId]) {
            grouped[traitId] = {
                title: traitTitle,
                subTraits: []
            };
        }
        grouped[traitId].subTraits.push(item);
    });
    
    // Add optgroups for each trait
    Object.values(grouped).forEach(group => {
        const optgroup = $(`<optgroup label="${escapeHtml(group.title)}"></optgroup>`);
        group.subTraits.forEach(item => {
            optgroup.append(`<option value="${item.id}">${escapeHtml(item.subtrait_name)}</option>`);
        });
        select.append(optgroup);
    });
}

function populateTraitDropdown(selectId, data) {
    const select = $(`#${selectId}`);
    select.empty();
    select.append(`<option value="">Select Trait</option>`);
    data.forEach(item => {
        select.append(`<option value="${item.id}">${escapeHtml(item.title)}</option>`);
    });
}

function populateInterpretationDropdown(selectId, data) {
    const select = $(`#${selectId}`);
    select.empty();
    select.append(`<option value="">Select Interpretation</option>`);
    data.forEach(item => {
        select.append(`<option value="${item.id}">${escapeHtml(item.trait_level)}</option>`);
    });
}

function updateTraitSubTraitsDisplay(traitId) {
    const display = $('#traitSubTraitsDisplay');
    
    if (!traitId) {
        display.html('<span class="text-muted">Select a trait to view its sub-traits</span>');
        return;
    }

    const trait = traits.find(t => t.id == traitId);
    if (!trait || !trait.sub_traits || trait.sub_traits.length === 0) {
        display.html('<span class="text-muted">No sub-traits found for this trait</span>');
        return;
    }

    let html = '<div class="d-flex flex-wrap gap-2">';
    trait.sub_traits.forEach(subTrait => {
        const matrices = subTraitMatrices.filter(m => m.subtrait_id === subTrait.id);
        const configuredClass = matrices.length > 0 ? '' : 'not-configured';
        
        html += `
            <span class="subtrait-button ${configuredClass}">
                ${escapeHtml(subTrait.subtrait_name)}
            </span>
        `;
    });
    html += '</div>';
    display.html(html);
}

// ============================================================================
// SUB-TRAIT MATRIX FUNCTIONS
// ============================================================================

function loadSubTraitMatrices() {
    $.ajax({
        url: `${API_BASE}/sub-trait-matrices`,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            if (response.success) {
                subTraitMatrices = response.data;
                renderSubTraitMatrices();
            }
        },
        error: function (xhr) {
            const errorMsg = xhr.responseJSON?.error || 'Failed to load sub-trait matrices.';
            Common.showToast(errorMsg, 1);
            $('#subTraitMatrixTableBody').html('<tr><td colspan="7" class="text-center text-danger py-3">Error loading data</td></tr>');
        }
    });
}

function renderSubTraitMatrices() {
    const tbody = $('#subTraitMatrixTableBody');
    tbody.empty();

    if (subTraitMatrices.length === 0) {
        tbody.html('<tr><td colspan="7" class="text-center text-muted py-3">No sub-trait matrices defined yet.</td></tr>');
        return;
    }

    subTraitMatrices.forEach((matrix, index) => {
        const subTraitName = matrix.sub_trait ? escapeHtml(matrix.sub_trait.subtrait_name) : 'N/A';
        const parentTrait = matrix.sub_trait?.trait ? escapeHtml(matrix.sub_trait.trait.title) : 'N/A';
        const interpretationLevel = matrix.interpretation ? escapeHtml(matrix.interpretation.trait_level) : 'N/A';
        const updatedDate = matrix.updated_at ? new Date(matrix.updated_at).toLocaleDateString() : 'N/A';
        
        tbody.append(`
            <tr>
                <td class="text-center">${index + 1}</td>
                <td><strong>${subTraitName}</strong></td>
                <td>${parentTrait}</td>
                <td>${matrix.min_score} - ${matrix.max_score}</td>
                <td>${interpretationLevel}</td>
                <td class="text-muted small">${updatedDate}</td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" onclick="editSubTraitMatrix(${matrix.id})" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="deleteSubTraitMatrix(${matrix.id})" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `);
    });
}

window.showAddSubTraitMatrixModal = function () {
    $('#subTraitMatrixModalLabel').text('Add New Sub-Trait Matrix');
    $('#subTraitMatrixId').val('');
    $('#subTraitSelect').val('');
    $('#subTraitMinScore').val('');
    $('#subTraitMaxScore').val('');
    $('#subTraitInterpretationSelect').val('');
    subTraitMatrixModal.show();
    
    // Focus on first select
    setTimeout(() => $('#subTraitSelect').focus(), 300);
};

window.editSubTraitMatrix = function (id) {
    const matrix = subTraitMatrices.find(m => m.id === id);
    if (!matrix) return;

    $('#subTraitMatrixModalLabel').text('Edit Sub-Trait Matrix');
    $('#subTraitMatrixId').val(matrix.id);
    $('#subTraitSelect').val(matrix.subtrait_id);
    $('#subTraitMinScore').val(matrix.min_score);
    $('#subTraitMaxScore').val(matrix.max_score);
    $('#subTraitInterpretationSelect').val(matrix.interpretation_id);
    
    subTraitMatrixModal.show();
    
    // Focus on first select
    setTimeout(() => $('#subTraitSelect').focus(), 300);
};

window.saveSubTraitMatrix = function () {
    const id = $('#subTraitMatrixId').val();
    const subTraitId = $('#subTraitSelect').val();
    const minScore = $('#subTraitMinScore').val();
    const maxScore = $('#subTraitMaxScore').val();
    const interpretationId = $('#subTraitInterpretationSelect').val();

    if (!subTraitId || !minScore || !maxScore || !interpretationId) {
        Common.showToast('Please fill out all required fields.', 1);
        return;
    }

    const data = {
        subtrait_id: subTraitId,
        min_score: minScore,
        max_score: maxScore,
        interpretation_id: interpretationId,
    };

    const url = id ? `${API_BASE}/sub-trait-matrices/${id}` : `${API_BASE}/sub-trait-matrices`;
    const type = id ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: type,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: data,
        success: function (response) {
            if (response.success) {
                Common.showToast(response.message || 'Sub-trait matrix saved successfully.');
                subTraitMatrixModal.hide();
                loadSubTraitMatrices();
            }
        },
        error: function (xhr) {
            const message = xhr.responseJSON?.message || 'Failed to save sub-trait matrix.';
            Common.showToast(message, 1);
        }
    });
};

window.deleteSubTraitMatrix = function (id) {
    const matrix = subTraitMatrices.find(m => m.id === id);
    if (!matrix) return;

    const subTraitName = matrix.sub_trait ? matrix.sub_trait.subtrait_name : 'this sub-trait';

    Common.showDeleteConfirmation(
        id,
        'Confirm Deletion',
        `Are you sure you want to delete the configuration for <strong>${escapeHtml(subTraitName)}</strong>?`
    );

    Common.setupDeleteConfirmation(`${API_BASE}/sub-trait-matrices`, function () {
        Common.showToast('Sub-trait matrix deleted successfully.');
        loadSubTraitMatrices();
    });
};

// ============================================================================
// TRAIT MATRIX FUNCTIONS
// ============================================================================

function loadTraitMatrices() {
    $.ajax({
        url: `${API_BASE}/trait-matrices`,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            if (response.success) {
                traitMatrices = response.data;
                renderTraitMatrices();
            }
        },
        error: function () {
            Common.showToast('Failed to load trait matrices.', 1);
        }
    });
}

function renderTraitMatrices() {
    const container = $('#traitMatrixContainer');
    container.empty();

    if (traitMatrices.length === 0) {
        container.html('<p class="text-muted text-center py-3 mb-0">No trait configurations defined yet.</p>');
        return;
    }

    // Group matrices by trait
    const grouped = {};
    traitMatrices.forEach(matrix => {
        const traitId = matrix.trait_id;
        if (!grouped[traitId]) {
            grouped[traitId] = {
                trait: matrix.trait,
                matrices: []
            };
        }
        grouped[traitId].matrices.push(matrix);
    });

    let html = '';
    Object.values(grouped).forEach(group => {
        const traitName = group.trait ? escapeHtml(group.trait.title) : 'Unknown Trait';
        const subTraitsList = group.trait?.sub_traits || [];
        
         // Trait Card Header (Trait Name and Sub-Traits)
            html += `
                <div class="trait-config-card">
                    <div class="mb-3 pb-3 border-bottom border-gray-200">
                        <div class="trait-title">${traitName}</div>
                        <div class="text-muted small mt-2 d-flex flex-wrap gap-2">
                            ${subTraitsList.map(st => `<span class="sub-trait-pill">${escapeHtml(st.subtrait_name)}</span>`).join('')}
                        </div>
                    </div>
            `;

            group.matrices.forEach((matrix, index) => {
                const interpretationLevel = matrix.interpretation ? escapeHtml(matrix.interpretation.trait_level) : 'N/A';
                
                // New structured row based on the prototype image
                html += `
                   <div class="trait-score-row" data-matrix-id="${matrix.id}">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <!-- Index Number -->
                                <div class="traitscore-number me-4 flex-shrink-0">
                                    ${index + 1}
                                </div>
            
                                <!-- Score Range and Interpretation content -->
                                <div class="traitscore-content flex-grow-1">
                                    <div class="row g-3">
                                        <!-- Score Range Column (Made col-6 for small screens and allowed it to grow) -->
                                        <div class="col-12 col-md-4">
                                            <span class="trait-score-label mb-3">Score Range</span>
                                            <div class="fw-bold">
                                                <span class="trait-score-value">${matrix.min_score} - ${matrix.max_score}</span>
                                            </div> 
                                        </div>

                                        <!-- Interpretation Level Column (Made col-8 for small screens and allowed it to grow) -->
                                        <div class="col-12 col-md-8">
                                            <span class="trait-score-label mb-3">Interpretation</span>
                                            <div class="fw-bold">
                                                <span class="trait-score-value">${interpretationLevel}</span>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
            
                                <!-- Card Actions -->
                                <div class="card-actions d-flex ms-3 flex-shrink-0">
                                    <button class="btn btn-link btn-edit p-2" 
                                            onclick="editTraitMatrix(${matrix.id})" 
                                            title="Edit">
                                        <i class="bi bi-pencil-square fs-5 text-secondary"></i>
                                    </button>
                                    <button class="btn btn-link btn-delete p-2" 
                                            onclick="deleteTraitMatrix(${matrix.id})" 
                                            title="Delete">
                                        <i class="bi bi-trash fs-5 text-danger"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `</div>`;
    });

    container.html(html);
}

window.showAddTraitModal = function () {
    $('#traitMatrixModalLabel').text('Add New Trait Configuration');
    $('#traitMatrixId').val('');
    $('#traitSelect').val('').trigger('change');
    $('#traitMinScore').val('');
    $('#traitMaxScore').val('');
    $('#traitInterpretationSelect').val('');
    traitMatrixModal.show();
    
    // Focus on first select
    setTimeout(() => $('#traitSelect').focus(), 300);
};

window.editTraitMatrix = function (id) {
    const matrix = traitMatrices.find(m => m.id === id);
    if (!matrix) return;

    $('#traitMatrixModalLabel').text('Edit Trait Configuration');
    $('#traitMatrixId').val(matrix.id);
    $('#traitSelect').val(matrix.trait_id).trigger('change');
    $('#traitMinScore').val(matrix.min_score);
    $('#traitMaxScore').val(matrix.max_score);
    $('#traitInterpretationSelect').val(matrix.interpretation_id);
    
    traitMatrixModal.show();
    
    // Focus on first select
    setTimeout(() => $('#traitSelect').focus(), 300);
};

window.saveTraitMatrix = function () {
    const id = $('#traitMatrixId').val();
    const traitId = $('#traitSelect').val();
    const minScore = $('#traitMinScore').val();
    const maxScore = $('#traitMaxScore').val();
    const interpretationId = $('#traitInterpretationSelect').val();

    if (!traitId || !minScore || !maxScore || !interpretationId) {
        Common.showToast('Please fill out all required fields.', 1);
        return;
    }

    const data = {
        trait_id: traitId,
        min_score: minScore,
        max_score: maxScore,
        interpretation_id: interpretationId,
    };

    const url = id ? `${API_BASE}/trait-matrices/${id}` : `${API_BASE}/trait-matrices`;
    const type = id ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: type,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: data,
        success: function (response) {
            if (response.success) {
                Common.showToast(response.message || 'Trait matrix saved successfully.');
                traitMatrixModal.hide();
                loadTraitMatrices();
            }
        },
        error: function (xhr) {
            const message = xhr.responseJSON?.message || 'Failed to save trait matrix.';
            Common.showToast(message, 1);
        }
    });
};

window.deleteTraitMatrix = function (id) {
    const matrix = traitMatrices.find(m => m.id === id);
    if (!matrix) return;

    const traitName = matrix.trait ? matrix.trait.title : 'this trait';

    Common.showDeleteConfirmation(
        id,
        'Confirm Deletion',
        `Are you sure you want to delete this configuration for <strong>${escapeHtml(traitName)}</strong>?`
    );

    Common.setupDeleteConfirmation(`${API_BASE}/trait-matrices`, function () {
        Common.showToast('Trait matrix deleted successfully.');
        loadTraitMatrices();
    });
};

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    text = String(text);
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}