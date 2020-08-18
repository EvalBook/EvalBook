import {Api} from "./api.js";
import {ModalSchoolReport} from "./modalSchoolReport.js";

let ControlsSchoolReport = {

    init: function() {
        this.elements = document.querySelectorAll('a[data-path]');
        for(let element of this.elements) {
            element.addEventListener('click', this.click);
        }
    },

    click: async function(event) {
        console.log(event);
        event.preventDefault();
        let response = await Api.query(event.target.parentElement.dataset.path, {});
        if(response.status === 200) {
            let modalSchoolReport = new ModalSchoolReport(data);
            modalSchoolReport.display();
        }
    },
}

ControlsSchoolReport.init();