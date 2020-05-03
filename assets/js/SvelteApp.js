import { Language } from './api.js';
import ConfirmDialog from './components/ConfirmDialog.svelte';

// Getting strings labels from api.
getStrings();

// Creating confirm dialog if needed.
for(let confirm of document.querySelectorAll('[data-svelte="dialog-confirm"]')) {
    switch(confirm.dataset.svelte) {
        case 'dialog-confirm':
            confirmApp(confirm);
            break;
        default:
            break;
    }
}

/**
 * Create Svelte confirm app component.
 */
function confirmApp(element) {
    new ConfirmDialog({target: element,
        props: {
            className: element.dataset.class,
            action: element.dataset.action,
            labels: JSON.parse(localStorage.getItem('labels')),
            csrf: element.dataset.csrf,
        }
    });
}


/**
 * Get strings labels and store them to the local storage.
 * @returns {Promise<void>}
 */
async function getStrings() {
    const labels = await Language.getStrings('templates', [
        'Are you sure you want to delete this implantation ?',
        'All data such as classes attached to it will be deleted too !',
        'Implantation deleted',
        'Implantation not deleted',
        'Invalid csrf token',
        'Yes',
        'No'
    ]);

    // Clearing local storage to accept server new language modifications, previous fetched strings are already displayed.
    localStorage.clear();
    localStorage.setItem('labels', JSON.stringify(labels));
}