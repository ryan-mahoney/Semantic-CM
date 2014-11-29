define(function (require) {
    var $ = require('jquery'),
        typeahead = require('typeahead.js'),
        Hogan = require('hogan');

    var getParameterByName = function(name) {
        name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
        var regexS = "[\\?&]"+name+"=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(window.location.href);
        if (results == null) {
            return false;
        } else {
            return decodeURIComponent(results[1].replace(/\+/g, " "));
        }
    };

    var itemIn = function (item) {
        $(item).find('.ui.buttons').css({display: 'none'});
        $(item).find('a.star').css({display: 'none'});
        $(item).attr('data-in', 't');
        if ($(item).attr('data-in') == 'f') {
            return;
        }
        $(item).find('.ui.buttons').stop().fadeIn(600).css('display', 'inline-block');
        $(item).find('a.star').stop().fadeIn(1200);
    };

    var itemOut = function (item) {
        $(item).attr('data-in', 'f');
        $(item).find('a.star').css({display: 'none'});
        $(item).find('.ui.buttons').stop().fadeOut(50);
    };

    $(document).ready(function () {
        $('#omnisearch').typeahead({
            remote: '/Manager/api/search?q=%QUERY',
            limit: 20,
            template: '<div class="ui teal label">{{type}}</div>{{value}}',
            engine: Hogan
        });
        $("#omnisearch").on('typeahead:selected', function(evt, data) {
            if (data['id'] == '' || data['id'] == null) {
                return;
            }
            window.location = data['id'] + '?s=' + data['value'];
        });
        $("#omnisearch").on('typeahead:autocompleted', function(evt, data) {
            if (data['id'] == '' || data['id'] == null) {
                return;
            }
            window.location = data['id'];
        });
        $("#omnisearch").on('typeahead:opened', function(evt, data) {
            $('#omnigo').attr('data-href', '');
        });
        $('.item.manager').hover(
            function () {
                itemIn(this);
            },
            function () {
                itemOut(this);
            }
        );
        var qs = getParameterByName('s');
        if (qs !== false) {
            $('#omnisearch').val(qs);
        }
        var tmpSearch = '';
        $('#omnisearch').focus(function () {
            if ($(this).val() != '') {
                tmpSearch = $(this).val();
                $(this).val('');
            }
        });
        $('#omnisearch').blur(function () {
           if ($(this).val() == '' && tmpSearch != '') {
                $(this).val(tmpSearch);
            }
        });

        $('#omnifilter').dropdown();
    });

    return function () {};
});