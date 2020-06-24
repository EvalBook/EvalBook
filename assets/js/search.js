/**
 * Literal Javascript object provifind searches on tables elements tagged with .target-entity CSS class.
 * @type {{process: Search.process}}
 */
Search = {
    /**
     * Search for matching filter text in provided targets and hide elements that does not match search pattern.
     * @param searchText
     */
    process: function(searchText) {
        searchText = searchText.toLowerCase();

        // Searching inside tables.
        for(let tr of document.querySelectorAll('tbody tr')) {
            let searchTargets = tr.getElementsByClassName('js-search-target');
            let found = false;

            // Iterate over search target elements.
            for(let searchTarget of searchTargets) {

                let value = (searchTarget.textContent || searchTarget.innerText).toLowerCase();
                if (value.indexOf(searchText) > -1) {
                    tr.style.display = '';
                    found = true;
                    break;
                }
            }
            // Hiding tr element if nothing found matching search text.
            if(found || searchText.length === 0)
                tr.style.display = '';
            else
                tr.style.display = 'none';
        }

        // Searching inside options
        for(let select of document.querySelectorAll('.js-searchable-select')) {
            let firstFoundOptionIndex;
            for(let i = 0; i < select.options.length; i++) {
                if(select.options[i].label.indexOf(searchText) > -1) {
                    if (!firstFoundOptionIndex)
                        firstFoundOptionIndex = i;
                    select.options[i].style.display = '';
                }
                else {
                    select.options[i].style.display = 'none';
                }
            }
            // The if statement in order to select the first element ( hidden or not ) in case of nothing found.
            if(firstFoundOptionIndex)
                select.selectedIndex = firstFoundOptionIndex;
        }
    }
}


let input = document.getElementById('search-box');
if(null !== input) {
    // Empty search box when focus is lost.
    /*
    input.onblur = function () {
        input.value = '';
    }
    */


    input.addEventListener('keyup', function (event) {
        Search.process(input.value);
    })
}
