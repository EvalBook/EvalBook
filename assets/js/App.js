import { Controls } from './controls.js';

// Init interface controls.
Controls.init();

// Activate mobile js navigation in case of screen smaller than 800px.
if(window.innerWidth <= 800) {
    Controls.activateMobileNavigation();
}