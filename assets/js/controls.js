/**
 * Graphical interface related Javascript.
 * @type {HTMLElement}
 */
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

/**
 * Hiding dialog errors / dialog success after 5 seconds.
 */
window.setTimeout(function() {
    let dialogs = document.getElementsByClassName("dialog");
    for(let dialog of dialogs) {
        dialog.parentElement.removeChild(dialog);
    }
}, 4000);