define(function (require) {
    var $ = require('jquery'),
        slugg = require('slugg'),
        watchJS = require('watch'),
        moment = require('moment');

    require('../../../../bower_components/livestampjs/livestamp.js');


    window.ManagerSaved = function (form, data) {
        var message = 'Information Saved.';
        if ($(form).attr('data-singular') !== "undefined") {
            message = $(form).attr('data-singular') + ' Saved.';
        }
        $('#manager-form-notice').css({position: 'fixed'});
        $('#manager-form-notice').addClass('positive').removeClass('error');
        $('.form-notice > .header').html(message);
        $('#manager-form-notice').transition('slide down');
        setTimeout(function () {
            $('#manager-form-notice').transition('slide down');
        }, 1500);
        $('abbr.time').each(function () {
            var now = new Date();
            $(this).attr('data-livestamp', now.toISOString());
        });

        var mode = $('body').attr('date-savemode');
        var manager = $('.manager form').attr('data-manager');
        var managerClass = $('.manager form').attr('data-class');
        var dbURI = $('input[name="' + managerClass + '\\[id\\]"]').val();
        if (mode == 'another') {
            window.location = '/Manager/item/' + manager;
            return;
        }

        //loop over embedded documents, update
        $(form).find('.field.embedded').each(function () {
            var embeddedDbURI = dbURI + ':' + $(this).attr('data-field');
            var embeddedManager = $(this).attr('data-manager');
            var embeddedContainer = this;
            $.ajax({
                type: "GET",
                url: '/Manager/index/' + embeddedManager + '?embedded&dbURI=' + embeddedDbURI,
                success: function (response) {
                    $(embeddedContainer).html(response);
                },
                error: function () {
                    console.log('Error');
                },
                dataType: 'html'
            });
        });
    };

    window.FormError = function (errors) {
        $('#manager-form-notice').css({position: 'relative'});
        $('#manager-form-notice').addClass('error').removeClass('positive');
        $('.form-notice > .header').html('Error in Form');
        $('#manager-form-notice').transition('slide down');
        setTimeout(function () {
            $('#manager-form-notice').transition('slide down');
        }, 5000);
    };

    var saveDropdownInitialize = function () {
        $('.manager .submit .ui.dropdown').each(function () {
            var uniqid = 'dropdown-' + Math.random().toString(36).substr(2, 7);
            if (typeof($(this).attr('data-id')) != 'undefined') {
                return;
            }
            $(this).attr('data-id', uniqid);
            var manager = $('.manager form').attr('data-manager');
            var idFieldName = manager + '[id]';
            var idDom = $('input[name="' + idFieldName + '"]');
            var idOld = $(idDom).val();

            $(this).dropdown({
                onChange: function(value) {
                    switch (value) {
                        case 'save-another':
                            $('body').attr('date-savemode', 'another');
                            $('.manager form').submit();
                            break;

                        case 'save-copy':
                            var idSpare = $('.manager form').attr('data-idSpare');
                            var slugFieldName = manager + '[code_name]';
                            var slugDom = $('input[name="' + slugFieldName + '"]');
                            var tmpVal = $(slugDom).val() + '-copy';
                            var idParts = idOld.split(':');
                            idParts.pop();
                            idParts.push(idSpare);
                            idSpare = idParts.join(':');
                            $(slugDom).val(tmpVal);
                            $(idDom).val(idSpare);
                            $('.manager form').submit();
                            break;

                        case 'save-delete':
                            var uniqid = Math.random().toString(36).substr(2, 7);
                            var div = document.createElement("div");
                            $('.ui.modal.delete').remove();
                            $(div).addClass('ui small modal delete');
                            $(div).attr('id', 'Modal-' + uniqid);
                            div.innerHTML = '\
                                <i class="close icon"></i>\
                                <div class="header">Confirm Delete</div>\
                                <div class="delete content"><p>Are you sure you want to delete the highlighted item?</p></div>\
                                <div class="actions">\
                                    <div class="ui negative button">No</div>\
                                    <div class="ui positive right labeled icon confirmed document delete button">Yes<i class="checkmark icon"></i></div>\
                                </div>';
                            $('body').append(div);
                            $('.delete.modal').modal('show');
                            $('.delete.content').html('Are you sure you want to delete this?</div>');
                            $('.confirmed.delete').attr('data-id', idOld);
                            $('.confirmed.delete').attr('data-manager', manager);
                            break;
                    }
                }
            });
        });
    };

    var tabularMenuInitialize = function () {
        $('body').on({
            click: function () {
                var container = $(this).parent();
                var activePrev = $(container).find('a.active').attr('data-tab');
                var active = $(this).attr('data-tab');
                $(container).find('a').removeClass('active');
                $(this).addClass('active');
                $('.ui.tab[data-tab="' + activePrev + '"]').css({display: 'block', position: 'absolute', visibility: 'hidden'});
                $('.ui.tab[data-tab="' + activePrev + '"] .redactor-toolbar').css({position: 'absolute', visibility: 'hidden'});
                $('.ui.tab[data-tab="' + active + '"]').css({display: 'block', position: 'relative', visibility: 'visible'});
                $('.ui.tab[data-tab="' + active + '"] .redactor-toolbar').css({position: 'relative', visibility: 'visible'});
            }
        }, '.ui.menu > a.item');
        $('.ui.tab').each(function () {
            if ($(this).hasClass('active')) {
                $(this).css({display: 'block', position: 'relative', visibility: 'visible'});
            } else {
                $(this).css({display: 'block', position: 'absolute', visibility: 'hidden'});
            }
        });
    };

    var formSubmitInitialize = function () {
        $('.ui.button.manager.submit').click(function () {
            console.log('clicked');
            $(this).parents('form').submit();
        });
    };

    var titleInitialize = function () {
        var $formDom = $('form');
        var titleField = $formDom.attr('data-titlefield');
        if (titleField == '' || typeof(titleField) == 'undefined') {
            return;
        }
        var managerClass = $formDom.attr('data-class');
        var titleFieldName = managerClass + '[' + titleField + ']';
        var slugFieldName = managerClass + '[code_name]';
        var crumbDom = $('.top-container .breadcrumb div');
        var titleDom = $('input[name="' + titleFieldName + '"]');
        var slugDom = $('input[name="' + slugFieldName + '"]');
        var watchedObject = {
            value: $(titleDom).val()
        };
        var mode = ($(titleDom).val().length > 0) ? 'update' : 'add';
        $(titleDom).bind("change keyup input", function () {
            watchedObject.value = $(this).val();
        });
        watchJS.watch(watchedObject, "value", function () {
            $(crumbDom).html(watchedObject.value);
            if (mode == 'add') {
                $(slugDom).val(slugg(watchedObject.value, '-'));
            }
        });
        if (mode == 'update') {
            $(crumbDom).html(watchedObject.value);
        }
    };

    var confirmedDeleteInitialize = function () {
        $(document).on({
            click: function () {
                var pathname = '';
                var dbURI = $(this).attr('data-id');
                var manager = $(this).attr('data-manager');
                var url = '/Manager/manager/' + manager + '/' + dbURI;
                $.ajax({
                    type: "DELETE",
                    url: url,
                    success: function (response) {
                        window.location = '/Manager/index/' + manager;
                    }
                });
            }
        }, '.confirmed.document.delete');
    };

    $(document).ready(function () {
        titleInitialize();
        tabularMenuInitialize();
        formSubmitInitialize();
        saveDropdownInitialize();
        confirmedDeleteInitialize();
    });

    return function () {};
});