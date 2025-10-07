/**
 * UI Utilities Module
 * Contains reusable functions for displaying messages, managing modals, etc.
 */

// Global reference for where messages are displayed.
let messageDiv = document.getElementById('message');

/**
 * Displays a non-blocking, temporary message in the dedicated message area.
 * @param {string} message - The text message to display.
 * @param {('success'|'danger'|'warning'|'info')} type - The Bootstrap alert type.
 */
export function showTemporaryMessage(message, type) {
    // Ensure messageDiv is referenced. This setup is based on the assumption 
    // that the message container ID is 'message'.
    if (!messageDiv) {
        messageDiv = document.getElementById('message');
        if (!messageDiv) return;
    }

    messageDiv.innerHTML = ''; 
    const tempAlert = document.createElement('div');
    // Using Bootstrap classes for styling and animation classes for effect
    tempAlert.className = `alert alert-${type} mt-3 mb-0 p-3 rounded-3 shadow-sm animated-add`; 
    tempAlert.textContent = message;
    messageDiv.appendChild(tempAlert);
    
    // Auto-remove the message after 4 seconds
    setTimeout(() => {
        if (tempAlert.parentNode) {
            tempAlert.remove();
        }
    }, 4000); 
}
