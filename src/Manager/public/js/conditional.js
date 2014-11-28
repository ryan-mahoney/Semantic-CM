if ($('body.semantic-cm').length) {
    console.log("Loading Opine Semantic CM");
    require.ensure([], function(require) {
        var $ = require('jquery');
        require('html.sortable');
        require('slugg');
        require('watch');
        require('typeahead.js');
        require('semantic');
        require('manager.js');
        require('search.js');
        require('table.js');
        require('dashboard.js');
        require('../css/typeahead.js/typeahead.css');
        require('../css/zeeshan/style.css');
    });
} else {
    console.log("Skipping Loading Opine Semantic CM");
}