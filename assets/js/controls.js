/**
 * Graphical interface related Javascript.
*/
let Controls = {

    /**
     * Init global behavior.
     */
    init: function() {
        // Hiding dialog errors / dialog success after 5 seconds.
        window.setTimeout(function() {
            let dialogs = document.getElementsByClassName("dialog");
            for(let dialog of dialogs) {
                dialog.parentElement.removeChild(dialog);
            }
        }, 4000);
    },


    /**
     * Hide not mobile elements.
     */
    activateMobileNavigation: function() {
        // Initialize the mobile menu.
        this.initMobileMenu();

        // Hiding unwanted elements.
        let hiddenElements = document.querySelectorAll('[data-mobile="0"]');

        for(let element of hiddenElements) {
            element.style.display = 'none';
        }

        // Adding a click event to the tr tables elements in order to show the contextual menu.
        let trElements = document.querySelectorAll('[data-trigger]');
        for(let element of trElements) {
            element.style.cursor = 'pointer';
            element.addEventListener('click', function() {
                window.location.href = element.dataset.trigger;
            });
        }
    },


    /**
     * Init mobile only behavior.
     */
    initMobileMenu: function() {
        let mobileMenuToggleButton = document.getElementById('mobile-menu-toggle').firstElementChild;
        let mobileMenu = document.getElementById('mobile-top-nav');

        mobileMenuToggleButton.addEventListener('click', function() {
            mobileMenuToggleButton.classList.toggle('fa-ellipsis-v');
            let val = window.getComputedStyle(mobileMenu).getPropertyValue('display');
            mobileMenu.style.display = (val === 'none') ? 'block' : 'none';
        });

        // Hiding mobile menu on any content section.
        window.addEventListener('click', function(event) {
            if(event.target !== mobileMenuToggleButton && event.target !== mobileMenu) {
                mobileMenuToggleButton.classList.remove('fa-ellipsis-v');
                mobileMenu.style.display = 'none';
            }
        });
    },
};

export { Controls };