import { Api } from "./api.js";

/**
 * Handle predefined role sets.
 */
let RoleSetHandler = {

    /**
     * Initialize object.
     */
    init: async function() {
        this.roleSetElement = document.querySelector('#user_role_type');
        this.roleSetCheckboxesParent = document.querySelector('#roles-group');

        this.roleSetElement.selectedIndex = 0;
        this.roleSetElement.addEventListener('change', () => this.watchRoleSetChange());
    },


    /**
     * Check / uncheck role checkboxes.
     * @returns {Promise<void>}
     */
    watchRoleSetChange: async function() {
        // Uncheck all roles checkboxes.
        for(let checkbox of this.roleSetCheckboxesParent.querySelectorAll('input[type=checkbox]')) {
            checkbox.checked = false;
        }

        try {
            let response = await Api.query('/api/roles-predefined/get', {
                roleSetId: this.roleSetElement.value,
            });

            if (response.roles.length > 0) {
                // Checking needed roles checkboxes.
                for(let role of response.roles) {
                    let checkbox = this.roleSetCheckboxesParent.querySelector(`input[value=${role}]`);
                    if(checkbox) {
                        checkbox.checked = true;
                    }
                }
            }
        }
        catch(error) {

        }
    },
};


RoleSetHandler.init();