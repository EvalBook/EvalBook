import ConfirmDialog from './components/ConfirmDialog.svelte';

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
    new ConfirmDialog({
        target: element,
        class:  element.dataset.class,
        action: element.dataset.action,
    });
}