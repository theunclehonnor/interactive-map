/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
const $ = require('jquery');
global.jQuery = $;
global.$ = $;
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
// require('bootstrap/js/dist/alert');
// require('bootstrap/js/dist/button');
// require('bootstrap/js/dist/collapse');
// require('bootstrap/js/dist/dropdown');
// require('bootstrap/js/dist/index');
// require('bootstrap/js/dist/popover');
// require('bootstrap/js/dist/');
// require('bootstrap/js/dist/carousel');
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
require('./raphael');
// require('./paths');
require('./init');
require('./main');



