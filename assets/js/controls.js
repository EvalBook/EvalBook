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
    },


    /**
     * Initialize collapsible elements.
     */
    initCollapsibleElements: function() {
        let collapsibleElements = document.querySelectorAll('.js-collapsible');

        for(let collapsible of collapsibleElements) {
            let collapsibleControl = collapsible.querySelector('.js-collapsible-control');

            let arrow = document.createElement('span');
            arrow.innerHTML = '<i class="fas fa-angle-double-down"></i>';
            arrow.dataset.state = '0';

            collapsibleControl.prepend(arrow);

            arrow.addEventListener('click', () => {
                let state = arrow.dataset.state === '1' ? '0' : '1';
                arrow.dataset.state = state;

                if(state === '1') {
                    arrow.innerHTML = '<i class="fas fa-angle-double-up"></i>';
                    collapsibleContent.style.display = 'inline-flex';
                }
                else {
                    arrow.innerHTML = '<i class="fas fa-angle-double-down"></i>';
                    collapsibleContent.style.display = 'none';
                }
            });

            let collapsibleContent = collapsible.querySelector('.js-collapsible-content');
            collapsibleContent.style.display = 'none';
            collapsibleContent.style.alignItems = 'flex-start';

        }
    },


    /**
     * Display the user global message.
     */
    globalMessageMove: function(marquee, interval) {

        let position = parseInt(marquee.style.left);
        let max = Math.abs(parseInt(window.getComputedStyle(marquee,null).getPropertyValue("right")));

        if(0 - max < position) {
            marquee.style.left = position - 6 + "px";
        }
        else {
            // Display once.
            marquee.style.display = 'none';
            window.clearInterval(interval);
        }
    },
};


// Init interface controls.
Controls.init();

// Activate mobile js navigation in case of screen smaller than 800px.
if(window.innerWidth <= 800) {
    Controls.initMobileMenu();
}

Controls.initDesktopUserLinks();
Controls.initCollapsibleElements();

let marquee = document.querySelector('.general-message');
if(marquee.innerHTML.length > 0) {
    marquee.style.width = "400%";
    marquee.style.left = window.innerWidth + "px";
    let int = window.setInterval(() => {
        Controls.globalMessageMove(marquee, int);
    }, 200)
}
