import {Api, Language} from "./api.js";


let ModalDialog = function(parent, className, action, csrf, targetId) {

    this.labels = JSON.parse(localStorage.getItem('labels'));
    this.modalDiv = null;

    this.init = function() {
        let parentLink = document.createElement('a');
        parentLink.innerHTML = `<i class="${className}"></i>`;
        parentLink.addEventListener('click', () => this.showModal());
        parent.appendChild(parentLink);
    }


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
                <p>${this.labels['Are you sure you want to delete this implantation ?']}</p>
                <p>${this.labels['All data such as classes attached to it will be deleted too !']}</p>
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
            });

            parent.removeChild(this.modalDiv);
        });

        // Simply closing modal dialog.
        cancelButton.addEventListener('click', () => {
            parent.removeChild(this.modalDiv);
        });

        parent.appendChild(this.modalDiv);
    }


    this._actionRequestCallback = function(target) {
        let element = document.querySelector(`[data-id="${target}"]`);
        element.parentElement.removeChild(element);
    }

}

// Creating confirm implantations dialogs.
for(let element of document.querySelectorAll('[data-target="implantation"]')) {

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