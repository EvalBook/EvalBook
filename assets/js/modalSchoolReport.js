

let ModalSchoolReport = function(data) {

    this.data = data;

    /**
     * Display the school report.
     */
    this.display = function() {
        let frame = document.createElement('div');
        frame.setAttribute('id',"modal-window");
        frame.setAttribute('aria-modal',"config");
        document.body.appendChild(frame);
        frame = document.getElementById('modal-window');

        let close = document.createElement('span');
        close.innerHTML = "Fermer cette fenêtre";
        close.setAttribute('id','closing-span');
        close.setAttribute('class','w-100 d-flex align-items-end');
        frame.appendChild(close);

        let view = document.createElement('div');
        view.setAttribute('class',"w-100 d-flex align-items-end");
        view.setAttribute('id',"report-view");
        frame.appendChild(view);
    }
}

export {ModalSchoolReport};