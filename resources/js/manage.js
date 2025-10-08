
import './bootstrap'; 
import Chart from 'chart.js/auto';
window.Chart = Chart;
import Common from "./common"; 


// --- GLOBAL VARIABLES (CACHE) ---
let managevent; 

// --- STATE MANAGEMENT (Unified) ---
let questions = [];
let subTraits = [];
let currentSubTraitIndex = null;
let currentQuestionIndex = null;

// --- DOM ELEMENTS (Unified) ---
const DOM = {};

function cacheDOM() {
    // General & Form
    DOM.traitForm = document.getElementById("traitForm");
    DOM.messageDiv = document.getElementById("message");
    DOM.traitTitle = document.getElementById("traitTitle");

    // Sub-trait Elements
    DOM.newSubTraitInput = document.getElementById("newSubTraitInput");
    DOM.subTraitsDisplay = document.getElementById("subTraitsDisplay"); 
    DOM.subTraitsContainer = document.getElementById("subTraitsContainer");

    // Question Input Elements
    DOM.questionSubTraitSelect = document.getElementById(
        "questionSubTraitSelect"
    );
    DOM.questionTextarea = document.getElementById("questionText");
    DOM.addedQuestionsContainer = document.getElementById(
        "addedQuestionsContainer"
    );
    DOM.questionCountSpan = document.getElementById("questionCount");
    DOM.questionsInputContainer =
        document.getElementById("questionsInputContainer") || DOM.traitForm;

    DOM.saveModalElement = document.getElementById("saveConfirmationModal");
    DOM.editSubTraitModalElement = document.getElementById("editSubTraitModal");
    DOM.editQuestionModalElement = document.getElementById("editQuestionModal");
    DOM.deleteQuestionConfirmationModalElement = document.getElementById(
        "deleteQuestionConfirmationModal"
    );

    // Modal Internal Elements
    DOM.newSubTraitNameInput = document.getElementById("newSubTraitNameInput");
    DOM.oldSubTraitNamePlaceholder = document.getElementById(
        "oldSubTraitNamePlaceholder"
    );
    DOM.confirmEditSubTraitBtn = document.getElementById(
        "confirmEditSubTraitBtn"
    );
    DOM.editQuestionSubTraitSelect = document.getElementById(
        "editQuestionSubTraitSelect"
    );
    DOM.editQuestionText = document.getElementById("editQuestionText");
    DOM.confirmEditQuestionBtn = document.getElementById(
        "confirmEditQuestionBtn"
    );
    DOM.questionToDeleteTextPlaceholder = document.getElementById(
        "questionToDeleteTextPlaceholder"
    );
    DOM.questionToDeleteIndex = document.getElementById(
        "questionToDeleteIndex"
    );
    DOM.saveTraitNamePlaceholder = document.getElementById(
        "saveTraitNamePlaceholder"
    );

    // Bootstrap Modal instances
    if (window.bootstrap) {
        if (DOM.editSubTraitModalElement)
            DOM.editSubTraitModal = new bootstrap.Modal(
                DOM.editSubTraitModalElement
            );
        if (DOM.editQuestionModalElement)
            DOM.editQuestionModal = new bootstrap.Modal(
                DOM.editQuestionModalElement
            );
        if (DOM.saveModalElement)
            DOM.saveConfirmationModal = new bootstrap.Modal(
                DOM.saveModalElement
            );
        if (DOM.deleteQuestionConfirmationModalElement)
            DOM.deleteQuestionConfirmationModal = new bootstrap.Modal(
                DOM.deleteQuestionConfirmationModalElement
            );
    }
}

function initInitialData() {
    try {
        if (typeof INITIAL_SUB_TRAITS !== "undefined" && INITIAL_SUB_TRAITS) {
            subTraits = JSON.parse(INITIAL_SUB_TRAITS) || [];
        }
        if (typeof INITIAL_QUESTIONS !== "undefined" && INITIAL_QUESTIONS) {
            questions = JSON.parse(INITIAL_QUESTIONS) || [];
        }
    } catch (e) {
        console.error("Failed to parse initial trait data:", e.message);
        subTraits = [];
        questions = [];
    }
}

/**
 * Wrapper for Common.showToast.
 * @param {string} message - The message to display.
 * @param {string} type - 'success', 'warning', or 'danger'.
 */
function showTemporaryMessage(messageText, type) {
    // 1. Check for the element using the correct ID
    const toastElement = document.getElementById('globalToast'); 
    
    // CRITICAL FIX: If the element is null, log the error and stop the function.
    if (!toastElement) {
        console.error('Toast container element not found. Message failed to display:', messageText);
        return; 
    }

    // 2. Safely find the header and body
    const toastHeader = toastElement.querySelector('.toast-header');
    const toastBodyStrong = toastElement.querySelector('.toast-body strong');

    // 3. Clear existing classes and set the new type (e.g., bg-success or bg-danger)
    toastHeader.classList.remove('bg-success', 'bg-danger', 'bg-info'); 

    if (type === 'success') {
        toastHeader.classList.add('bg-success');
    } else if (type === 'danger') {
        toastHeader.classList.add('bg-danger');
    } else { // Use info as default if type is not recognized
        toastHeader.classList.add('bg-info');
    }

    // 4. Set the message text
    if (toastBodyStrong) {
        toastBodyStrong.innerHTML = messageText;
    }

    // 5. Show the toast
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}

// --- RENDER FUNCTIONS ---

function renderSubTraits() {
    if (!DOM.subTraitsDisplay || !DOM.subTraitsContainer) { 
        console.error("DOM Error: Cannot render sub-traits. Missing required IDs: subTraitsDisplay or subTraitsContainer.");
        return;
    }

    DOM.subTraitsDisplay.innerHTML = "";
    DOM.subTraitsContainer.innerHTML = "";

    subTraits.forEach((name, index) => {
        const chip = document.createElement("span");
        chip.className = "sub-trait-tag animated-add";
        chip.dataset.name = name;
        chip.innerHTML = `
        ${name} 
             <i class="bi bi-pencil edit-tag" title="Edit Sub-section Name" onclick="editSubTrait('${name}', ${index})"></i>
             <i class="bi bi-x-lg remove-tag" aria-label="Remove" onclick="removeSubTraitChip(${index})" title="Remove Sub-section"></i>
        `;
        DOM.subTraitsDisplay.appendChild(chip); 

        // Re-add hidden input for form submission
        const subTraitInput = document.createElement("input");
        subTraitInput.type = "hidden";
        subTraitInput.name = `subTraits[]`;
        subTraitInput.value = name;
        DOM.subTraitsContainer.appendChild(subTraitInput);
    });
    updateQuestionSubTraitOptions();
    renderAddedQuestions();
}

function updateQuestionSubTraitOptions() {
    if (!DOM.questionSubTraitSelect) return;

    const optionsHtml = subTraits
        .map((name) => `<option value="${name}">${name}</option>`)
        .join("");

    // For Add Question form
    DOM.questionSubTraitSelect.innerHTML =
        `<option value="" disabled selected>Select sub-section</option>` +
        optionsHtml;

    // For Edit Question Modal
    if (DOM.editQuestionSubTraitSelect) {
        DOM.editQuestionSubTraitSelect.innerHTML = optionsHtml;
    }
}

function renderAddedQuestions() {
    if (
        !DOM.addedQuestionsContainer ||
        !DOM.questionCountSpan ||
        !DOM.questionsInputContainer
    )
        return;

    DOM.addedQuestionsContainer.innerHTML = "";
    DOM.questionCountSpan.textContent = questions.length;

    DOM.questionsInputContainer.innerHTML = "";

    if (questions.length === 0) {
        DOM.addedQuestionsContainer.innerHTML =
            '<p class="text-muted text-center py-3">No questions have been added yet.</p>';
        return;
    }

    let questionNumber = 1;
    const orderedSubTraitNames = [...subTraits];

    orderedSubTraitNames.forEach((subTraitName) => {
        const questionsInSubTrait = questions
            .map((q, originalIndex) => ({ ...q, originalIndex }))
            .filter((q) => q.subTrait === subTraitName);

        if (questionsInSubTrait.length > 0) {
            const headerContainer = document.createElement("div");
            headerContainer.className = "sub-trait-group-container";
            headerContainer.innerHTML = `
                <h6 class="fw-bold text-dark mt-3 mb-1" style="font-size: 1.1rem;">${subTraitName}</h6>
                <hr class="mt-1 mb-0" style="border-top: 2px solid #007bff; opacity: 1;">
            `;
            DOM.addedQuestionsContainer.appendChild(headerContainer);

            questionsInSubTrait.forEach((q) => {
                const originalIndex = q.originalIndex;

                const item = document.createElement("div");
                item.className =
                    "list-group-item list-group-item-action d-flex justify-content-between align-items-start py-3 animated-add";
                item.dataset.subtrait = q.subTrait;

                item.innerHTML = `
                    <span class="me-auto text-dark me-2">
                        <span class="fw-semibold text-muted me-2">${questionNumber}.</span> ${q.text}
                    </span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary border-0 fs-5" onclick="editQuestion(${originalIndex})" title="Edit Question"><i class="bi bi-pencil-square"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0 fs-5" onclick="deleteQuestion(${originalIndex})" title="Delete Question"><i class="bi bi-trash"></i></button>
                    </div>
                `;

                DOM.addedQuestionsContainer.appendChild(item);

                const subTraitInput = document.createElement("input");
                subTraitInput.type = "hidden";
                subTraitInput.name = `questions[${originalIndex}][subTrait]`;
                subTraitInput.value = q.subTrait;

                const textInput = document.createElement("input");
                textInput.type = "hidden";
                textInput.name = `questions[${originalIndex}][text]`;
                textInput.value = q.text;

                DOM.questionsInputContainer.appendChild(subTraitInput);
                DOM.questionsInputContainer.appendChild(textInput);

                questionNumber++;
            });
        }
    });
}

// --- ACTION FUNCTIONS (EXPORT REMOVED) ---

function addSubTraitFromInput() {
    const name = DOM.newSubTraitInput.value.trim();
    if (name === "") {
        showTemporaryMessage("Sub-Trait name cannot be empty.", "warning");
        return;
    }
    if (subTraits.map((s) => s.toLowerCase()).includes(name.toLowerCase())) {
        showTemporaryMessage(`Sub-Trait "${name}" already exists.`, "warning");
        return;
    }

    subTraits.push(name);
    DOM.newSubTraitInput.value = "";
    
    renderSubTraits(); 
    
    showTemporaryMessage(`Sub-Trait "${name}" added.`, "success");
}

function handleSubTraitInput(e) {
    if (e.key === "Enter") {
        e.preventDefault();
        addSubTraitFromInput();
    }
}

function editSubTrait(oldName, index = null) {
    if (!DOM.editSubTraitModalElement || !window.bootstrap) {
        showTemporaryMessage("Error: Edit Modal not found.", "danger");
        return;
    }

    currentSubTraitIndex = index;
    DOM.oldSubTraitNamePlaceholder.textContent = oldName;
    DOM.newSubTraitNameInput.value = oldName;

    const modalInstance =
        DOM.editSubTraitModal ||
        new bootstrap.Modal(DOM.editSubTraitModalElement);
    modalInstance.show();

    DOM.editSubTraitModalElement.addEventListener(
        "shown.bs.modal",
        () => {
            DOM.newSubTraitNameInput.focus();
        },
        { once: true }
    );
}

function saveEditedSubTrait() {
    const newName = DOM.newSubTraitNameInput.value;
    const trimmedNewName = newName.trim();

    const oldName = subTraits[currentSubTraitIndex];

    const modalInstance =
        DOM.editSubTraitModal ||
        bootstrap.Modal.getInstance(DOM.editSubTraitModalElement);
    if (modalInstance) modalInstance.hide();

    if (trimmedNewName === "" || trimmedNewName === oldName) {
        showTemporaryMessage("Name unchanged or invalid.", "info");
        return;
    }

    if (
        subTraits
            .map((s) => s.toLowerCase())
            .filter((_, i) => i !== currentSubTraitIndex)
            .includes(trimmedNewName.toLowerCase())
    ) {
        showTemporaryMessage(
            `Sub-Trait "${trimmedNewName}" already exists.`,
            "warning"
        );
        return;
    }

    questions = questions.map((q) => {
        if (q.subTrait === oldName) {
            q.subTrait = trimmedNewName;
        }
        return q;
    });

    subTraits[currentSubTraitIndex] = trimmedNewName;

    showTemporaryMessage(
        `Sub-Trait "${oldName}" renamed to "${trimmedNewName}".`,
        "success"
    );
    currentSubTraitIndex = null;
    renderSubTraits();
}

function removeSubTraitChip(index) {
    const nameToRemove = subTraits[index];

    const questionsRemovedCount = questions.filter(
        (q) => q.subTrait === nameToRemove
    ).length;
    questions = questions.filter((q) => q.subTrait !== nameToRemove);

    if (index > -1) {
        subTraits.splice(index, 1);

        if (DOM.questionSubTraitSelect.value === nameToRemove) {
            const defaultOption = DOM.questionSubTraitSelect.querySelector(
                "option[disabled]"
            );
            DOM.questionSubTraitSelect.value = defaultOption
                ? defaultOption.value
                : "";
        }

        showTemporaryMessage(
            `Sub-Trait "${nameToRemove}" removed. (${questionsRemovedCount} questions also removed).`,
            "warning"
        );
        renderSubTraits();
        renderAddedQuestions();
    }
}

function addQuestionToList() {
    const subTrait = DOM.questionSubTraitSelect.value;
    const text = DOM.questionTextarea.value.trim();

    // Check for all three failure conditions in one place for consistency
    if (subTraits.length === 0 || !subTrait || !text) {
        showTemporaryMessage(
            "Invalid question data. Ensure a subsection is selected and the question text is not empty.",
            "danger"
        );
        return;
    }

    // --- NEW DUPLICATE CHECK ---
    const isDuplicate = questions.some(q => q.text.trim().toLowerCase() === text.toLowerCase());

    if (isDuplicate) {
        showTemporaryMessage(
            "This question already exists. Please enter a unique question.",
            "warning"
        );
        return;
    }
    // ----------------------------

    const newQuestion = { subTrait: subTrait, text: text };
    questions.push(newQuestion);
    showTemporaryMessage("Question added successfully.", "success");

    DOM.questionTextarea.value = "";

    renderAddedQuestions();
}

function editQuestion(index) {
    if (!questions[index] || !DOM.editQuestionModalElement) {
        showTemporaryMessage(
            "Error: Question or Edit Modal not found.",
            "danger"
        );
        return;
    }

    currentQuestionIndex = index;
    const question = questions[index];

    document.getElementById("editQuestionIndex").value = index;
    DOM.editQuestionText.value = question.text;
    DOM.editQuestionSubTraitSelect.value = question.subTrait;

    const modalInstance =
        DOM.editQuestionModal ||
        new bootstrap.Modal(DOM.editQuestionModalElement);
    modalInstance.show();

    DOM.editQuestionModalElement.addEventListener(
        "shown.bs.modal",
        () => {
            DOM.editQuestionText.focus();
        },
        { once: true }
    );
}

function confirmEditQuestion() {
    const index = currentQuestionIndex;
    const newSubTrait = DOM.editQuestionSubTraitSelect.value;
    const newText = DOM.editQuestionText.value.trim(); 
    
    const oldQuestion = questions[index]; 

    // 1. ESSENTIAL VALIDATION CHECK:
    if (index === null || index < 0 || !newSubTrait || !newText) {
        showTemporaryMessage("Invalid question data or index. Ensure a subsection is selected and the question text is not empty.", "danger");
        return;
    }

    if (oldQuestion.subTrait === newSubTrait && oldQuestion.text === newText) {
    showTemporaryMessage("No changes detected.", "info");
    
    // Modal instance was re-calculated here, which is fine, but now it's done once at the start.
    const modalInstance = 
        DOM.editQuestionModal ||
        bootstrap.Modal.getInstance(DOM.editQuestionModalElement);
    // If statement added for robustness:
    if (modalInstance) modalInstance.hide();
    currentQuestionIndex = null;
    return; 
}

    // 3. APPLY CHANGE:
    oldQuestion.subTrait = newSubTrait;
    oldQuestion.text = newText;

    showTemporaryMessage(
        `Question ${index + 1} successfully updated.`,
        "success"
    );
    renderAddedQuestions();

    const modalInstance =
        DOM.editQuestionModal ||
        bootstrap.Modal.getInstance(DOM.editQuestionModalElement);
    if (modalInstance) modalInstance.hide();
    currentQuestionIndex = null;
}

function deleteQuestion(index) {
    if (!questions[index]) {
        showTemporaryMessage("Error: Question not found.", "danger");
        return;
    }

    currentQuestionIndex = index; 

    const questionText = questions[index].text;
    const snippet =
        questionText.substring(0, 70) +
        (questionText.length > 70 ? "..." : "");
    
    const $modal = document.getElementById('deleteConfirmationModal');

    if ($modal) {
        // 1. Set the Title/Message for the generic modal
        document.getElementById('delete-title').textContent = "Confirm Question Deletion";
        document.getElementById('delete-message').innerHTML = `
            Are you sure you want to delete this question?
            <strong class="d-block mt-2">${snippet}</strong>
        `;
        
        // 2. Clear the hidden ID input (optional, but clean)
        document.getElementById('delete_record_id').value = ''; 
        
        // 3. Set the 'Yes, Delete' button's handler to our local function.
        // NOTE: This relies on the function being mapped to the window object.
        document.getElementById('btn_delete_ok').onclick = window.confirmDeleteQuestion;

        // 4. Show the modal
        new bootstrap.Modal($modal).show();
    }
}


function confirmDeleteQuestion() {
    const index = currentQuestionIndex; 
    
    // Hide the generic modal using its ID
    const modalElement = document.getElementById('deleteConfirmationModal');
    if (modalElement) {
        bootstrap.Modal.getInstance(modalElement)?.hide();
    }

    if (questions[index]) {
        questions.splice(index, 1);
        renderAddedQuestions();
        showTemporaryMessage("Question deleted.", "success");
    }
    
    // Reset the index and modal content after deletion
    currentQuestionIndex = null;
    document.getElementById('delete-title').textContent = "Confirm Deletion"; 
    document.getElementById('delete-message').innerHTML = ''; 
}

function removeQuestion(index) {
    deleteQuestion(index);
}

function showSaveModal() {
    if (subTraits.length === 0) {
        showTemporaryMessage(
            "A Trait must have at least one Sub-Trait.",
            "danger"
        );
        return;
    }
    if (questions.length === 0) {
        showTemporaryMessage(
            "A Trait must have at least one Question.",
            "danger"
        );
        return;
    }

    if (DOM.saveModalElement) {
        const traitTitle = DOM.traitTitle.value.trim() || "Untitled Trait";
        DOM.saveTraitNamePlaceholder.textContent = traitTitle;

        const modalInstance =
            DOM.saveConfirmationModal ||
            new bootstrap.Modal(DOM.saveModalElement);
        modalInstance.show();
    }
}

function confirmSave() {
    const modalInstance =
        DOM.saveConfirmationModal ||
        bootstrap.Modal.getInstance(DOM.saveModalElement);
    if (modalInstance) modalInstance.hide();

    const isEditMode = typeof MANAGE_UPDATE_ROUTE !== "undefined";
    const route = isEditMode
        ? MANAGE_UPDATE_ROUTE
        : typeof MANAGE_STORE_ROUTE !== "undefined"
        ? MANAGE_STORE_ROUTE
        : DOM.traitForm.action;

    const formData = new FormData(DOM.traitForm);

    if (isEditMode) {
        formData.set("_method", "PUT");
    }

    showTemporaryMessage("Saving...", "info");

    fetch(route, {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) =>
            response
                .json()
                .then((data) => ({ status: response.status, body: data }))
        )
        .then(({ status, body }) => {
            if (status >= 200 && status < 300) {
                showTemporaryMessage(
                    body.message || "Trait saved successfully!",
                    "success"
                );
                const redirectRoute =
                    body.redirect ||
                    (typeof MANAGE_INDEX_ROUTE !== "undefined"
                        ? MANAGE_INDEX_ROUTE
                        : "/manage");
                setTimeout(() => {
                    window.location.href = redirectRoute;
                }, 1000);
            } else {
                let errorHtml = "";
                if (body.errors) {
                    errorHtml = Object.values(body.errors).flat().join("<br>");
                } else {
                    errorHtml = body.message || "A server error occurred.";
                }
                showTemporaryMessage(errorHtml, "danger");
            }
        })
        .catch((error) => {
            console.error("Fetch Error:", error);
            showTemporaryMessage(
                "An unknown network error occurred.",
                "danger"
            );
        });
}


function initFormLogic() {
    // CRITICAL FIX: Only call form-dependent logic here.
    cacheDOM();
    initInitialData();
    
    // Attach form event listeners 
    if (DOM.confirmEditSubTraitBtn) {
        DOM.confirmEditSubTraitBtn.addEventListener(
            "click",
            saveEditedSubTrait
        );
        DOM.newSubTraitNameInput?.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                saveEditedSubTrait();
            }
        });
    }

    if (DOM.confirmEditQuestionBtn) {
        DOM.confirmEditQuestionBtn.addEventListener(
            "click",
            confirmEditQuestion
        );
    }
    
    // Initial render call
    renderSubTraits(); 
}

function initManageLogic() {
    // CRITICAL FIX: Explicitly map ALL functions referenced in inline HTML to the window object
    window.handleSubTraitInput = handleSubTraitInput;
    window.addSubTraitFromInput = addSubTraitFromInput;
    window.editSubTrait = editSubTrait;
    window.saveEditedSubTrait = saveEditedSubTrait;
    window.removeSubTraitChip = removeSubTraitChip;

    window.addQuestionToList = addQuestionToList;
    window.editQuestion = editQuestion;
    window.confirmEditQuestion = confirmEditQuestion;

    window.showSaveModal = showSaveModal;
    window.confirmSave = confirmSave;
    
    // Ensure delete functions are globally available
    window.deleteQuestion = deleteQuestion; 
    window.confirmDeleteQuestion = confirmDeleteQuestion;
    window.removeSubTrait = removeSubTraitChip;
    
    // Assign the event element
    managevent = document.getElementById("manage-index") 
                 || document.getElementById("manage-create")
                 || document.getElementById("manage-edit");

    if (!managevent) {
        console.warn("Management container element not found. Event listeners may fail.");
    }
}


function initManageIndexLogic() {
    initManageLogic(); // Universal setup (mappings)

    // --- Delete Confirmation Setup (Index Page Specific) ---
    const API_BASE_URL = "/api/manage"; 
    Common.setupDeleteConfirmation(API_BASE_URL, function (deletedId) {
        const deletedElement = document.querySelector(
            `.trait-card[data-trait-id=\"${deletedId}\"]`
        );
        if (deletedElement) {
            deletedElement.remove();
            showTemporaryMessage("Trait deleted successfully.", "success");
        }
    });
    
    if (managevent) {
        managevent.addEventListener("click", function (event) {
            const deleteButton = event.target.closest(".delete-trait-btn");
            if (deleteButton) {
                event.preventDefault();
                const itemId = deleteButton.getAttribute("data-id");
                if (itemId) {
                    Common.showDeleteConfirmation(
                        itemId,
                        "Confirm Deletion",
                        `Are you sure you want to delete the trait?`
                    );
                }
            }
        });
    }
}

function initManageCreateLogic() {
    initManageLogic(); 
    initFormLogic();   
}

function initManageEditLogic() {
    initManageLogic(); 
    initFormLogic();   
}

document.addEventListener('DOMContentLoaded', () => {
    const indexContainer = document.getElementById('manage-index');
    const createContainer = document.getElementById('manage-create');
    const editContainer = document.getElementById('manage-edit');

    if (indexContainer) {
        initManageIndexLogic();
    } else if (createContainer) {
        initManageCreateLogic();
    } else if (editContainer) {
        initManageEditLogic();
    } else {
        console.warn("Manage page containers not found. No specific logic initialized.");
    }
});