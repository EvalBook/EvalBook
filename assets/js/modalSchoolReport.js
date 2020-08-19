

let ModalSchoolReport = function(data) {

    this.data = data;

    /**
     * Display the school report.
     */
    this.display = function() {
        console.log(this.data);
    }
}

export {ModalSchoolReport};