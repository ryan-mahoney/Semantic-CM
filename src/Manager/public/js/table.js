$(document).ready(function () {
    dragInitialize();

    $(document).on({
        mouseenter: function(){
            $(this).addClass('warning').css({cursor: 'pointer'});
        },
        mouseleave: function(){
            $(this).removeClass('warning');
        }
    }, '.table.manager > tbody > tr');

    $(document).on({
        click: function(event) {
            event.stopPropagation();
            var id = $(this).attr('data-id');
            var embedded = embeddedCheck(this);
            if (embedded != 1) {
                var pathname = window.location.pathname;
                window.location = pathname.replace(/\/index\//, '/item/') + '/' + id;
            } else {
                embeddedModal(this, 'edit', id);
            }
        }
    }, '.table.manager > tbody > tr');

    $(document).on({
        click: function(event) {
            event.stopPropagation();
            var manager;
            var tr = $(this).parents('tr');
            var id = $(tr).attr('data-id');
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
                    <div class="ui positive right labeled icon confirmed manager-table delete button">Yes<i class="checkmark icon"></i></div>\
                </div>';
            $('body').append(div);
            $('.delete.modal').modal('show');
            var name = ' this item?'
            if ($(tr).find('td.name').length) {
                name = ': ' + $(tr).find('td.name').html();
            }
            $('.delete.content').html('Are you sure you want to delete ' + name);
            $('.confirmed.delete').attr('data-id', id);
            var embedded = embeddedCheck(tr);
            if (embedded == 1) {
                manager = $(tr).parents('.field').attr('data-manager');
            } else {
                manager = window.location.pathname.split('/').pop();
            }
            $('.confirmed.delete').attr('data-manager', manager);
            $('.confirmed.delete').attr('data-embedded', embedded);
        }
    }, '.table.manager > tbody > tr .manager.trash');

    $(document).on({
        click: function () {
            var pathname = '';
            var dbURI = $(this).attr('data-id');
            var manager = $(this).attr('data-manager');
            var url = '/Manager/api/' + dbURI;
            var embedded = $(this).attr('data-embedded');
            $.ajax({
                type: "DELETE",
                url: url,
                success: function (response) {
                    if (embedded == 1) {
                        url = '/Manager/index/' + manager + '?embedded&dbURI=' + dbURI;
                    } else {
                        url = '/Manager/index/' + manager + '?naked';
                    }
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function (response) {
                            if (embedded == 1) {
                                $('.field.embedded[data-manager="' + manager + '"]').html(response);
                            } else {
                                $('.manager.container').html(response);
                            }
                        },
                        error: function () {
                            console.log('Error');
                        },
                        dataType: 'html'
                    });
                },
                error: function () {
                    console.log('Error');
                },
                dataType: 'json'
            });
        }
    }, '.confirmed.manager-table.delete');

    $(document).on({
        click: function () {
        var embedded = embeddedCheck(this);
            if (embedded != 1) {
                var pathname = window.location.pathname;
                window.location = pathname.replace(/\/index\//, '/item/');
            } else {
                embeddedModal(this, 'add', false);
            }
        }
    }, '.manager.add');
});

var dragInitialize = function () {
    $('.field.embedded table.sortable, table.sortable').each(function () {
        var uniqid = 'sortable-' + Math.random().toString(36).substr(2, 7);
        if (typeof($(this).attr('data-id')) != 'undefined') {
            return;
        }
        var colspan = $(this).find('thead > tr > th').length;
        $(this).attr('data-id', uniqid);
        $(this).find('tbody').sortable({
            items: 'tr',
            handle: 'td.handle',
            placeholder : '<tr><td colspan="' + colspan + '">&nbsp;</td></tr>'
        });
        $(this).find('tbody').sortable().bind('sortupdate', function(e, ui) {
            var sorted = [];
            $(e.currentTarget).children().each(function () {
                sorted.push($(this).attr('data-id'));
            });
            var data = {};
            data.sorted = sorted;
            $.ajax({
                type: "POST",
                url: '/Manager/api/sort',
                data: data,
                success: function (response) {},
                error: function () {
                    console.log('Error');
                },
                dataType: 'json'
            });
        });
    });
};

var embeddedCheck = function (DOMnode) {
    return $(DOMnode).parents('.field').length;
};

var embeddedModal = function (DOMnode, mode, dbURI) {
    var parentField = $(DOMnode).parents('.field').attr('data-field');
    var manager = $(DOMnode).parents('.field').attr('data-manager');
    var url = '';
    var idEmbedded = '';
    var idField = '';
    var id = '';
    var idFieldEmbedded = '';
    if (mode == 'add') {
        url = '/Manager/item/' + manager;
    } else {
        url = '/Manager/item/' + manager + '/' + dbURI;
        idEmbedded = dbURI;
    }
    var uniqid = Math.random().toString(36).substr(2, 7);
    var div = document.createElement("div");
    $(div).addClass('ui embedded manager modal');
    $(div).attr('id', 'Modal-' + uniqid);
    div.innerHTML = '\
        <i class="close icon"></i>\
        <div id="Content-' + uniqid + '" class="ui content loading" style="height: 500px; padding: 0; margin: 0">\
            <div><iframe id="Iframe-' + uniqid + '" src="' + url + '?embedded" style="width: 100%; border: 0; margin: 0; padding: 0; height: 500px;"></iframe></div>\
        </div>';
    $('body').append(div);
    $('#Modal-' + uniqid).modal('setting', {
        transition: 'vertical flip',
        closable: false
    }).modal('show');
    $('#Modal-' + uniqid).attr('data-manager', manager);
    $('#Iframe-' + uniqid).bind('load', function() {
        $('#Content-' + uniqid).removeClass('loading');
      
        if (mode == 'add') {
            idFieldEmbedded = $('#Iframe-' + uniqid).contents().find('form input[name*="[id]"]');
            idField = $('form input[name*="[id]"]');
            id = $(idField).val() + ':' + parentField;
            idEmbedded = id + ':' + $(idFieldEmbedded).val().split(':')[1];
        }

        $(idFieldEmbedded).val(idEmbedded);
        $('#Iframe-' + uniqid).contents().find('.ui.embedded.close').click(function () {
            $('#Modal-' + uniqid).modal('hide');
            setTimeout(function () {
                $('#Modal-' + uniqid).remove();
            }, 5000);
        });
    });
};

var embeddedUpsert = function (form, submittedData, response) {
    var action = $(form).attr('action');
    var manager = action.split('/').pop();
    var dbURI = '';
    $(submittedData).each(function (offset, field) {
        if (field.name == null) {
            return;
        }
        if (field.name.match(/\[id\]$/) != null) {
            dbURI = field.value;
            return false;
        }
    });
    $.ajax({
        type: "GET",
        url: '/Manager/index/' + manager + '?embedded&dbURI=' + dbURI,
        success: function (response) {
            $(parent.document).find('.field.embedded[data-manager="' + manager + '"]').html(response);
            $(parent.document).find('body').removeClass('dimmed');
            $(parent.document).find('.ui.dimmer.page').removeClass('visible').removeClass('active');
            $(parent.document).find('.embedded.manager.modal[data-manager="' + manager + '"]').modal('hide');
        },
        error: function () {
            console.log('Error');
        },
        dataType: 'html'
    });
};