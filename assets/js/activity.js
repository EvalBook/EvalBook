import { Api, Language } from "./api.js";

/**
 * Handle activities creation process.
 * @type {{watchActivityTheme: watchActivityTheme, init: init}}
 */
let ActivityHandler = {

    /**
     * Getting needed elements and setting empty values.
     */
    init: async function() {
        this.activityThemeDomains = document.querySelector('#activity_activityThemeDomains');
        this.knowledgesElement = document.querySelector('#activity_knowledgeType');
        this.noteTypesElement = document.querySelector('#activity_noteType');

        // Setting defaults.
        this.activityThemeDomains.selectedIndex = 0;
        this.knowledgesElement.innerHTML = '';
        this.noteTypesElement.innerHTML = '';

        // Attaching first listener to activity types element.
        this.activityThemeDomains.addEventListener('change', () => this.watchActivityTheme());
        this.noteTypesElement.addEventListener('change', () => this.watchNoteType());

        // Getting needed translations.
        this.strings = await Language.getStrings('templates', [
            "Choose an activity type to continue...",
            "Select an available note type...",
            "A bad parameter was sent to the serveur, please, try again !",
            "No knowledge for this activity, you can create one from your dashboard",
            "No available note types, you can create one from your dashboard",
            "An unexpected error occurred",
        ]);
    },


    /**
     * Watch activityTheme select and perform ajax request to fetch related data.
     */
    watchActivityTheme: async function() {
        try {
            let response = await Api.query('/api/knowledge/get', {
                activityThemeDomain: this.activityThemeDomains.value
            });

            // Handling error with sent parameters.
            if(typeof response.message !== 'undefined') {
                console.log(this.strings["A bad parameter was sent to the serveur, please, try again !"]);
                return;
            }

            // Removing the first activity type element.
            this.activityThemeDomains.removeChild(this.activityThemeDomains.firstChild);
            this.knowledgesElement.innerHTML = '';

            // Knowledges.
            if (response.knowledges.length > 0 && this.knowledgesElement) {
                // Iterate over knowledges types.
                for (let knowledge of response.knowledges) {
                    this.knowledgesElement.appendChild(this._getOption(knowledge.name, knowledge.id));
                }
                // Display knowledge type hidden parent element.
                this.knowledgesElement.parentElement.style.display = "block";


                // Note types only if knowledges types were found..
                this.noteTypesElement.innerHTML = '';
                this.noteTypesElement.appendChild(this._getOption(this.strings["Select an available note type..."], -1))
                if (response.noteTypes.length > 0 && this.noteTypesElement) {
                    // Now that knowledge is displayed, getting available notes types.
                    for(let noteType of response.noteTypes) {
                        this.noteTypesElement.appendChild(this._getOption(noteType.name, noteType.id));
                    }

                    this.noteTypesElement.parentElement.style.display = 'block';
                }
                else {
                    this.noteTypesElement.parentElement.style.display = 'none';
                    let errorMsg = this.strings["No available note types, you can create one from your dashboard"]
                    this.knowledgesElement.parentElement.querySelector('span').innerHTML = errorMsg ;
                }

            }
            else {
                this.knowledgesElement.parentElement.style.display = 'none';
                let errorMsg = this.strings["No knowledge for this activity, you can create one from your dashboard"]
                this.activityThemeDomains.parentElement.querySelector('span').innerHTML = errorMsg ;
            }
        }
        catch(error) {
            console.log(error);
            console.log(this.strings["An unexpected error occurred"]);
        }
    },



    watchNoteType: async function() {
        // Simply display the rest of the form.
        this.noteTypesElement.removeChild(this.noteTypesElement.firstChild);
        document.querySelector('#activity_name').parentElement.style.display = 'block';
        document.querySelector('#activity_submit').style.display = 'block';
    },


    /**
     * Create an options element with provided data.
     * @param text
     * @param value
     * @returns {HTMLOptionElement}
     * @private
     */
    _getOption: function(text, value) {
        let optionElement = document.createElement('option');
        optionElement.value = value;
        optionElement.innerHTML = text;
        return optionElement;
    }
};


ActivityHandler.init();