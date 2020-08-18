
let ControlsSchoolReport = {

    init: function() {
        this.elements = document.querySelectorAll('a[data-path]');
        for(let element of this.elements) {
            element.addEventListener('click', this.click);
        }
    },

    click: function(event) {
        console.log(event);
        event.preventDefault();
        let path = event.getAttribute('data-path');

        console.log("Getting school report of: " + path);
    },
}

ControlsSchoolReport.init();