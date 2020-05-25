import {Api, Language} from "./api.js";

/**
 * Create a ModalDialog object enhancing deletion process.
 * @param parent
 * @param className
 * @param action
 * @param csrf
 * @param targetId
 * @constructor
 */
let ModalDialog = function(parent, className, action, csrf, targetId) {

    this.labels = JSON.parse(localStorage.getItem('labels'));
    this.modalDiv = null;

    /**
     * Init component by providing a button to click on.
     */
    this.init = function() {
        let parentLink = document.createElement('a');
        parentLink.innerHTML = `<i class="${className}"></i>`;
        parentLink.style.cursor = 'pointer';
        parentLink.addEventListener('click', () => this.showModal());
        parent.appendChild(parentLink);
    }


    /**
     * Show the modal dialog on component click.
     */
    this.showModal = function() {
        this.modalDiv = document.createElement('div');
        let okButton = document.createElement('button');
        let cancelButton = document.createElement('button');
        okButton.classList.add('button-ok');
        cancelButton.classList.add('button-cancel');
        okButton.innerHTML = this.labels['Yes'];
        cancelButton.innerHTML = this.labels['No'];

        this.modalDiv.innerHTML = `
            <div class="dialog-modal dialog-confirm">
                <p>${this.labels['Are you sure you want to delete this element ?']}</p>
                <p>${this.labels['All attached data will be deleted or marked orphan']}</p>
                <div id="confirm"></div>
            </div>    
        `;

        let confirmDiv = this.modalDiv.querySelector('#confirm');
        confirmDiv.appendChild(okButton);
        confirmDiv.appendChild(cancelButton);

        // Sending request to delete.
        okButton.addEventListener('click', () => {
            const result = Api.query(action, {
                csrf: csrf,
            }, {
                action: this._actionRequestCallback,
                param: targetId,
                message: {
                    success: this.labels['Element deleted'],
                    error:   this.labels['Error deleting element']
                },
            });

            parent.removeChild(this.modalDiv);
        });

        // Simply closing modal dialog.
        cancelButton.addEventListener('click', () => {
            parent.removeChild(this.modalDiv);
        });

        parent.appendChild(this.modalDiv);
    }

    /**
     * Api response callback, create status dialog and delete target deleted element.
     * @param error
     * @param target
     * @param messages
     * @private
     */
    this._actionRequestCallback = function(error, target, messages) {
        let element = document.querySelector(`[data-id="${target}"]`);

        let messageDialog = document.createElement('div');
        messageDialog.classList.add('dialog');
        if(!error) {
            messageDialog.classList.add('dialog-success');
            messageDialog.innerHTML = messages['success'];
            element.parentElement.removeChild(element);
        }
        else {
            messageDialog.classList.add('dialog-error');
            messageDialog.innerHTML = messages['error'];
        }
        document.body.appendChild(messageDialog);
    }

}

// Creating confirm delete elements dialogs.
for(let element of document.querySelectorAll('[data-target]')) {

    let modal = new ModalDialog(
        element,
        element.dataset.class,
        element.dataset.action,
        element.dataset.csrf,
        element.dataset.targetid
    );

    modal.init();
}

// Getting strings labels from api.
getStrings();


/**
 * Get strings labels and store them to the local storage.
 * @returns {Promise<void>}
 */
async function getStrings() {
    const labels = await Language.getStrings('templates', [
        'Are you sure you want to delete this element ?',
        'All attached data will be deleted or marked orphan',
        'Element deleted',
        'Error deleting element',
        'Invalid csrf token',
        'Yes',
        'No'
    ]);

    // Clearing local storage to accept server new language modifications, previous fetched strings are already displayed.
    localStorage.clear();
    localStorage.setItem('labels', JSON.stringify(labels));
}