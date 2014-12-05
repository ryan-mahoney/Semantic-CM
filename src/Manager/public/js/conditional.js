if ($('body.semantic-cm').length) {
    console.log("Loading Opine Semantic CM");
    require.ensure([], function(require) {
        var $ = require('jquery');
        require('html.sortable');
        require('slugg');
        require('watch');
        require('semantic');
        require('./form.js');
        require('./manager.js');
        require('./search.js');
        require('./table.js');
        if ($('#manager-dashboard-cards').length) {
            require('./dashboard.js');
        }
        require('../css/style.css');
    });
} else {
    console.log("Skipping Loading Opine Semantic CM");
}