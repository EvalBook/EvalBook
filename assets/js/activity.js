import {Api, Language} from "./api.js";

/**
 * 1 -> récupération de l'activité type ( ID )
 * 2-> envoi d'une requête au serveur pour la récupération des knowledge types associés à ce type d'activité.
 * 3-> mise à jour des options disponibnles dans le select.
 *
 * @type {Element}
 */



let activityTypeChildren = document.querySelector('#activity_activityTypeChildren');
activityTypeChildren.selectedIndex = 0;

activityTypeChildren.addEventListener('change', async function() {
    // Getting knowledge select element and ensure it is empty.
    let knowledgesElement = document.querySelector('#activity_knowledgeType');
    let noteTypesElement  = document.querySelector('#activity_noteType');
    knowledgesElement.innerHTML = '';
    noteTypesElement.innerHTML = '';

    try {
        let response = await Api.query('/api/knowledge/get', {
            activityTypeChild: activityTypeChildren.value
        });

        // Handling error with sent parameters.
        if(typeof response.message !== 'undefined') {
            console.log("A bad parameter was sent to the serveur, please, try again !");
            return;
        }

        // Knowledges.
        if (response.knowledges.length > 0 && knowledgesElement) {
            // Iterate over knowledges types.
            for (let knowledge of response.knowledges) {
                let optionElement = document.createElement('option');
                optionElement.value = knowledge.id;
                optionElement.innerHTML = knowledge.name;
                knowledgesElement.appendChild(optionElement);
            }

            // Display knowledge type hidden parent element.
            knowledgesElement.parentElement.style.display = "block";

        } else {
            console.log("Obtention de la chaîne de caractères: Pas de knowledge pour ce type d'activité, veuillez en créer un à partir de votre dashboard.");
        }

        // Note types.
        if (response.noteTypes.length > 0 && noteTypesElement) {
            // Now that knowledge is displayed, getting available notes types.
            for(let noteType of response.noteTypes) {
                let optionElement = document.createElement('option');
                optionElement.value = noteType.id;
                optionElement.innerHTML = noteType.name;
                noteTypesElement.appendChild(optionElement);
            }

            noteTypesElement.parentElement.style.display = 'block';
        }
    }
    catch(error) {
        console.log("An unexpected error occurred");
    }

});