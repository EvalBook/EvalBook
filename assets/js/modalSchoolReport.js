

let ModalSchoolReport = function(data) {

    this.data = data;

    /**
     * Display the school report.
     */
    this.display = function() {
        let frame = document.createElement('div');
        frame.setAttribute('id',"modal-window");
        frame.setAttribute('aria-modal',"config");
        frame.classList.add('school-report-container');
        document.querySelector('.container').appendChild(frame);

        let close = document.createElement('span');
        close.innerHTML = "Fermer cette fenêtre";
        close.setAttribute('id','closing-span');
        close.setAttribute('class','w-100 d-flex align-items-end');
        frame.appendChild(close);

        let view = document.createElement('div');
        view.setAttribute('class',"w-100 d-flex align-items-end");
        view.setAttribute('id',"report-view");
        view.innerHTML = this.data;
        frame.appendChild(view);
    }
}

export {ModalSchoolReport};