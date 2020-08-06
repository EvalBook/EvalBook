/**
 * Literal Javascript object provifind searches on tables elements tagged with .target-entity CSS class.
 * @type {{process: Search.process}}
 */
Search = {
    /**
     * Search for matching filter text in provided targets and hide elements that does not match search pattern.
     * @param searchText
     */
    process: function(searchText, noResultMessage) {
        searchText = searchText.toLowerCase();

        // Searching inside tables.
        let trElements = document.querySelectorAll('tbody tr');
        for(let tr of trElements) {
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

            // Removing no result elements.
            for(let nrElement of document.querySelectorAll('.js-no-result')) {
                nrElement.parentElement.removeChild(nrElement);
            }
        }

        // If tr elements len is same as hidden elements len, then there is no results.
        let hiddenTrElements = document.querySelectorAll("tbody tr[style='display: none;']");
        if(hiddenTrElements.length === trElements.length) {
            let parent = trElements.item(0).parentElement;
            let colspan = trElements.item(0).childElementCount;

            let noResults = document.createElement('tr');
            noResults.innerHTML = "<td class='text-center text-bold text-red js-no-result' colspan='" + colspan + "'>" + noResultMessage + "</td>"
            parent.appendChild(noResults);
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
    input.addEventListener('keyup', function (event) {
        Search.process(input.value, input.dataset.noResultMessage);
    })
}
