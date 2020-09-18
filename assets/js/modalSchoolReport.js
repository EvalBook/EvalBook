

let ModalSchoolReport = function(data) {

    this.data = data;

    /**
     * Display the school report.
     */
    this.display = function() {
        // Find and close opened shool reports.
        let schoolReportContainer = document.querySelector('.school-report-container');
        if(schoolReportContainer) {
            schoolReportContainer.parentElement.removeChild(schoolReportContainer);
        }

        // Global surrounding frame.
        let frame = document.createElement('div');
        frame.setAttribute('id',"modal-window");
        frame.setAttribute('aria-modal',"config");
        frame.classList.add('school-report-container');
        document.querySelector('.container').appendChild(frame);

        // The wrapper element has different CSS code in order to pretty embed school report data tables.
        let wrapper = document.createElement('div');
        wrapper.setAttribute('id', 'school-report-wrapper');
        frame.append(wrapper);

        // Closing element.
        let close = document.createElement('span');
        close.innerHTML = '<i class="far fa-times-circle"></i>';
        close.setAttribute('id','school-report-container-close');
        frame.appendChild(close);

        // School report data tables element.
        let view = document.createElement('div');
        view.setAttribute('class',"w-100 d-flex align-items-end");
        view.setAttribute('id',"report-view");
        view.innerHTML = this.data;
        wrapper.appendChild(view);

        // Attaching close event.
        document.body.addEventListener('click', (event) => {
            let schoolReportWrapper = document.querySelector('#school-report-wrapper');

            if(event.target !== schoolReportWrapper && null !== schoolReportWrapper) {
                // Getting elements that does not trigger a close event.
                let children = [].slice.call(schoolReportWrapper.querySelectorAll('*'));
                if(!children.includes(event.target)) {
                    schoolReportWrapper.parentElement.parentElement.removeChild(document.querySelector('.school-report-container'));
                }
            }
        })
    }
}

export {ModalSchoolReport};