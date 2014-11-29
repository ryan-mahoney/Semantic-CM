define(function (require) {
    var $ = require('jquery'),
        semantic = require('semantic');

    $(document).ready(function () {
        $('#manager-dashboard-cards .clickable').click(function () {
            var link = $(this).attr('data-link');
            window.location = link;
        });

        $('#manager-dashboard-cards .dimmable.content').dimmer({
            on: 'hover'
        });
    });

    return function () {};
});