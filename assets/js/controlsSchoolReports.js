import {Api} from "./api.js";
import {ModalSchoolReport} from "./modalSchoolReport.js";

let ControlsSchoolReport = {

    /**
     * Init school report image sbuttons.
     */
    init: function() {
        this.elements = document.querySelectorAll('a[data-path]');
        for(let element of this.elements) {
            element.addEventListener('click', this.click);
        }
    },


    /**
     * Fetch school report information.
     * @param event
     * @returns {Promise<void>}
     */
    click: async function(event) {
        event.preventDefault();

        let response = await Api.query(event.target.parentElement.dataset.path, {});

        if(response.html) {
            let modalSchoolReport = new ModalSchoolReport(response.html);
            modalSchoolReport.display();
        }
    },
}

ControlsSchoolReport.init();