// resources/js/interpretation.js
import Common from './common'; // Import Common class

/**
 * Generates the HTML string for a single score item.
 * NOTE: The delete button relies on event delegation in the DOMContentLoaded listener.
 */
function createScoreItem(id, score, level, description) {
    // This function must return pure, well-formed HTML.
    return `
        <div class="card shadow-sm bg-white mb-4" data-score-id="${id}">
            <div class="card-body p-4">
                <div class="d-flex align-items-start">
                    <div class="score-index-circle me-3">
                        ${id}
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">

                            <div class="row g-3 flex-grow-1">
                                <div class="col-sm-6">
                                    <div class="d-flex flex-column">
                                        <label for="score-${id}" class="form-label mb-1">Score Range</label>
                                        <input type="text" class="form-control" id="score-${id}" value="${score}" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="d-flex flex-column">
                                        <label for="level-${id}" class="form-label mb-1">Trait Level</label>
                                        <input type="text" class="form-control" id="level-${id}" value="${level}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex ms-4 card-actions flex-shrink-0 pt-2">
                                <button class="btn btn-link btn-edit p-0" title="Edit" data-bs-toggle="modal" data-bs-target="#scoreModal">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </button>

                                <button class="btn btn-link btn-delete p-0 ms-2" title="Delete" data-item-id="${id}">
                                    <i class="bi bi-trash-fill fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mt-4">
                                <label for="desc-${id}" class="form-label mb-1">Interpretation</label>
                                <textarea class="form-control" id="desc-${id}" rows="2" readonly>${description}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

document.addEventListener('DOMContentLoaded', () => {
    const subTraitContainer = document.getElementById('sub-trait-ranges');
    const bigFiveContainer = document.getElementById('big-five-ranges');
    const interpretationContainer = document.getElementById('interpretation');

    // --- Dynamic Data Population ---
    // Placeholder data to simulate fetching from a backend
    subTraitContainer.innerHTML += createScoreItem(1, '5-10', 'Low', 'The trait is not a prominent characteristic.');
    subTraitContainer.innerHTML += createScoreItem(2, '11-20', 'Moderate', 'A balanced and adaptable expression of the trait.');
    subTraitContainer.innerHTML += createScoreItem(3, '21-25', 'High', 'A core trait that defines and dominates an individual\'s behavior.');
    bigFiveContainer.innerHTML += createScoreItem(4, '10-20', 'Low', 'Less characteristic of the trait');
    bigFiveContainer.innerHTML += createScoreItem(5, '21-35', 'Moderate', 'Moderate expression of the trait');
    bigFiveContainer.innerHTML += createScoreItem(6, '36-50', 'High', 'Strongly characteristic of the trait');

    // --- Delete Confirmation Setup ---
    // 1. Setup the 'Yes, Delete' button logic once for this page
    const API_BASE_URL = '/api/score-interpretation'; // <<< IMPORTANT: Update this to your actual API endpoint for deletion
    
    Common.setupDeleteConfirmation(
        API_BASE_URL, 
        function(deletedId) {
            // Callback: Remove the deleted element from the DOM (assuming successful deletion)
            const deletedElement = document.querySelector(`.card[data-score-id="${deletedId}"]`);
            if (deletedElement) {
                deletedElement.remove();
            }
        }
    );

    // 2. Add Event Delegation to trigger the modal when ANY delete button is clicked
    // This is attached to the main container ('#interpretation') for efficiency.
    interpretationContainer.addEventListener('click', function(event) {
        // Use .closest() to check if the clicked element or any parent is the delete button
        const deleteButton = event.target.closest('.btn-delete'); 
        
        if (deleteButton) {
            event.preventDefault(); 
            
            const itemId = deleteButton.getAttribute('data-item-id');
            if (itemId) {
                // Show the generic confirmation modal using the Common utility
                Common.showDeleteConfirmation(
                    itemId, 
                    'Confirm Deletion', 
                    `Are you sure you want to delete this item?`
                );
            }
        }
    });
});
