var console = require('console-polyfill');
var jQuery = require('jquery');
$ = jQuery;
window.jQuery = jQuery;

$(function() {
    require('semantic');
    require('opine-field');
    require('opine-form');
    require('opine-semantic-cm');
});