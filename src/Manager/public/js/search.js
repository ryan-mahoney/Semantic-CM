define(function (require) {
    var $ = require('jquery'),
        semantic = require('semantic');

    $('.ui.search')
        .search({
            apiSettings: {
                url: '/Manager/api/search/?q={query}'
            }
        });


    return function () {};
});