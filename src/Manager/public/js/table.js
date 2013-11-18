$(document).ready(function () {
    $(document).on({
      mouseenter: function(){
        $(this).addClass('warning');
        var tdFirst = $(this).find('td:first-child');
        var tdLast = $(this).find('td:last-child');
        if ($(tdFirst).find('.button.manager').length == 0) {
          $('<div class="ui teal button small manager edit" style="margin-right: 10px">Edit</div>').prependTo(tdFirst);
          $('<div class="ui red button small manager delete" style="margin-left: 10px; float: right">Delete</div>').appendTo(tdLast);
        }
      },
      mouseleave: function(){
        $(this).removeClass('warning');
        $(this).find('.button.manager').remove();
      }
    }, '.table.manager > tbody > tr');

    $(document).on({
      click: function(event) {
        event.stopPropagation();
        var id = $(this).parents('tr').attr('data-id');
        var embedded = embeddedCheck(this);
        if (embedded != 1) {
    		  var pathname = window.location.pathname;
    		  window.location = pathname.replace(/\/list\//, '/edit/') + '/' + id;
        } else {
          embeddedModal(this, 'edit', id);
        }
      }
	}, '.table.manager > tbody > tr .manager.edit');

	$(document).on({
    click: function(event) {
      event.stopPropagation();
      var manager;
  		var tr = $(this).parents('tr');
  		var id = $(tr).attr('data-id');
  		$(tr).find('.button.manager').remove();
  		$('.small.modal').modal('show');
  		var name = $(tr).find('td:first-child').html();
  		$('.delete.content').html('Are you sure you want to delete: <br /><br /><div class="ui teal inverted segment"><p>' + name + '</p></div>');
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
	}, '.table.manager > tbody > tr .manager.delete');

	$('.confirmed.delete').click(function () {
		var pathname = '';
    var dbURI = $(this).attr('data-id');
    var manager = $(this).attr('data-manager');
    var url = '/Manager/manager/' + manager + '/' + dbURI;
    var embedded = $(this).attr('data-embedded');
		$.ajax({
		  	type: "DELETE",
		  	url: url,
		  	success: function (response) {
		  		if (embedded == 1) {
            url = '/Manager/list/' + manager + '?embedded&dbURI=' + dbURI;
          } else {
            url = '/Manager/list/' + manager + '?naked';
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
	});

	$(document).on({
    click: function () {
      var embedded = embeddedCheck(this);
      if (embedded != 1) {
  		  var pathname = window.location.pathname;
  		  window.location = pathname.replace(/\/list\//, '/add/');
      } else {
        embeddedModal(this, 'add', false);
      }
    }
	}, '.manager.add');
});

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
    url = '/Manager/' + mode + '/' + manager;
  } else {
    url = '/Manager/' + mode + '/' + manager + '/' + dbURI;
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
  $('#Iframe-' + uniqid).bind('load', function() {
    $('#Content-' + uniqid).removeClass('loading');
    
    if (mode == 'add') {
      idFieldEmbedded = $('#Iframe-' + uniqid).contents().find('form > input[name*="[id]"]');
      idField = $('form > input[name*="[id]"]');
      id = $(idField).val() + ':' + parentField;
      idEmbedded = id + ':' + $(idFieldEmbedded).val().split(':')[1];
    }

    $(idFieldEmbedded).val(idEmbedded);
    $('#Iframe-' + uniqid).contents().find('.ui.embedded.close').click(function () {
      $('#Modal-' + uniqid).modal('hide');
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
      url: '/Manager/list/' + manager + '?embedded&dbURI=' + dbURI,
      success: function (response) {
        $(parent.document).find('.field.embedded[data-manager="' + manager + '"]').html(response);
      },
      error: function () {
        console.log('Error');
      },
      dataType: 'html'
  });
};