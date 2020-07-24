/**
 * Graphical interface related Javascript.
*/
let Controls = {

    /**
     * Init global behavior.
     */
    init: function() {
        setInterval(() => this._hideDialog(), 4000);
        this.initHelpMessages();
    },

    /**
     * Hide dialog messages success / error windows by completely removing them.
     * @private
     */
    _hideDialog: function() {
        // Hiding dialog errors / dialog success after 5 seconds.
        let dialogs = document.getElementsByClassName("dialog");
        for(let dialog of dialogs) {
            dialog.parentElement.removeChild(dialog);
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

            let menuItems = mobileMenu.querySelector('#mobile-menu-items');
            menuItems.style.zIndex = '100';
            // Compute menu position.
            let position = mobileMenuToggleButton.offsetLeft.toString();
            let menuWidth = parseFloat(getComputedStyle(menuItems).width);
            if(position > menuWidth)
                position -= menuWidth;

            menuItems.style.left = position.toString() + 'px';
        });

        // Hiding mobile menu on any content section.
        window.addEventListener('click', function(event) {
            if(event.target !== mobileMenuToggleButton && event.target !== mobileMenu) {
                mobileMenuToggleButton.classList.remove('fa-ellipsis-v');
                mobileMenu.style.display = 'none';
            }
            window.removeEventListener(this);
        });
    },


    /**
     * Init the user top links menu on desktop.
     */
    initDesktopUserLinks: function() {
        let userLinks = document.getElementById('user-links');
        let menu = document.getElementById('user-links-toggle');

        userLinks.addEventListener('click', function() {
            let val = window.getComputedStyle(menu).getPropertyValue('display');
            menu.style.display = (val === 'none') ? 'flex' : 'none';

        });

        // Hiding mobile menu on any content section.
        window.addEventListener('click', function(event) {
            if(event.target !== userLinks.firstElementChild) {
                menu.style.display = 'none';
            }
            window.removeEventListener(this);
        });
    },

    /**
     * Init the page help message.
     */
    initHelpMessages: function() {
        let help = document.getElementById('help-dialog');
        if(help) {
            help.addEventListener('click', function() {
                let help = document.getElementById('help-message');
                help.style.display = 'flex';
            });

            document.querySelector('#help-message button').addEventListener('click', function(event) {
                event.target.parentElement.style.display = 'none';
            });
        }
    }
};


// Init interface controls.
Controls.init();

// Activate mobile js navigation in case of screen smaller than 800px.
if(window.innerWidth <= 800) {
    Controls.initMobileMenu();
}

Controls.initDesktopUserLinks();
