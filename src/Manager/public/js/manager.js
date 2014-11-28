define(function (require) {
    var $ = require('jquery'),
        semantic = require('semantic');

    var headerDropdownInitialize = function () {
        $('.main.menu .ui.dropdown').each(function () {
            var uniqid = 'dropdown-' + Math.random().toString(36).substr(2, 7);
            if (typeof($(this).attr('data-id')) != 'undefined') {
                return;
            }
            $(this).attr('data-id', uniqid);
            $(this).dropdown();
        });
    };

    $(document).ready(function () {
        headerDropdownInitialize();
    });

    return function () {};
});