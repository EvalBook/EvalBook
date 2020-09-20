

let ModalWindow = function(data) {

    this.data = data;

    /**
     * Display the school report.
     */
    this.display = function() {
        // Find and close opened shool reports.
        let modalWindowContainer = document.querySelector('.modal-window-container');
        if(modalWindowContainer) {
            modalWindowContainer.parentElement.removeChild(modalWindowContainer);
        }

        // Global surrounding frame.
        let frame = document.createElement('div');
        frame.setAttribute('id',"modal-window");
        frame.setAttribute('aria-modal',"config");
        frame.classList.add('modal-window-container');
        document.querySelector('.container').appendChild(frame);

        // The wrapper element has different CSS code in order to pretty embed school report data tables.
        let wrapper = document.createElement('div');
        wrapper.setAttribute('id', 'modal-window-wrapper');
        frame.append(wrapper);

        // Closing element.
        let close = document.createElement('span');
        close.innerHTML = '<i class="far fa-times-circle"></i>';
        close.setAttribute('id','modal-window-container-close');
        frame.appendChild(close);

        // School report data tables element.
        let view = document.createElement('div');
        view.setAttribute('class',"w-100 d-flex align-items-end");
        view.setAttribute('id',"modal-window-view");
        view.innerHTML = this.data;
        wrapper.appendChild(view);

        // Attaching close event.
        document.body.addEventListener('click', (event) => {
            let modalWindowWrapper = document.querySelector('#modal-window-wrapper');

            if(event.target !== modalWindowWrapper && null !== modalWindowWrapper) {
                // Getting elements that does not trigger a close event.
                let children = [].slice.call(modalWindowWrapper.querySelectorAll('*'));
                if(!children.includes(event.target)) {
                    modalWindowWrapper.parentElement.parentElement.removeChild(document.querySelector('.modal-window-container'));
                }
            }
        })
    }
}

export {ModalWindow};