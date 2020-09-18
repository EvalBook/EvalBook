

let ModalSchoolReport = function(data) {

    this.data = data;

    /**
     * Display the school report.
     */
    this.display = function() {
        console.log(this.data);
        let frame = document.createElement('div');
        frame.setAttribute('id',"modal-window");
        frame.setAttribute('aria-modal',"config");
        document.body.appendChild(frame);

        let close = document.createElement('span');
        close.innerHTML = "Fermer cette fenêtre";
        close.setAttribute('id','closing-span');
        close.setAttribute('class','w-100 d-flex align-items-end');
        frame.appendChild(close);

        let view = document.createElement('iframe');
        view.setAttribute('class',"w-100 d-flex align-items-end");
        view.setAttribute('id',"report-view");
        view.src = "data:text/html;charset=utf-8," + escape(this.data);
        frame.appendChild(view);
    }
}

export {ModalSchoolReport};