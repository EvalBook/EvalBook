
Search = {

    /**
     * Search for matching filter text in provided targets and hide elements that does not match search pattern.
     * @param searchText
     */
    process: function(searchText) {
        searchText = searchText.toLowerCase();

        for(let tr of document.querySelectorAll('tbody tr')) {
            let searchTargets = tr.getElementsByClassName('search-target');
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
            if(found || searchText.length === 0) {
                tr.style.display = '';
            }
            else {
                tr.style.display = 'none';
            }
        }
    }
}


let input = document.getElementById('search-box');
// Empty search box when focus is lost.
input.onblur = function() {
    input.value = '';
}

input.addEventListener('keyup', function(event) {
    Search.process(input.value);
})
