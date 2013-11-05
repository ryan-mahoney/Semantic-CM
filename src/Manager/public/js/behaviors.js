$(document).ready(function () {
	$('#omnisearch').typeahead({
        remote: '/Manager/api/search?q=%QUERY',
        limit: 20,
        template: '<div class="ui teal ribbon label">{{type}}</div>{{value}}',                                                                 
  		engine: Hogan
    });
    $("#omnisearch").on('typeahead:selected', function(evt, data) {
        $('#omnigo').attr('data-href', data['id']);
    });
    $("#omnisearch").on('typeahead:autocompleted', function(evt, data) {
        $('#omnigo').attr('data-href', data['id']);
    });
    $("#omnisearch").on('typeahead:opened', function(evt, data) {
        $('#omnigo').attr('data-href', '');
    });
    $('#omnigo').click(function () {
    	var href = $(this).attr('data-href');
    	if (href == '' || href == null) {
    		$('#omni').dimmer('toggle');
    		setTimeout(function () {
    			$('#omni').dimmer('toggle');
    		}, 3000);
    		return;
    	}
    	window.location = href;
    });

    $('.item.manager').hover(
    	function () {
       		itemIn(this);
    	},
    	function () {
    		itemOut(this);
    	}
    );

    $('.table.manager > tbody > tr').hover(
    	function () {
    		$(this).addClass('warning');
    		var tdFirst = $(this).find('td:first-child');
    		var tdLast = $(this).find('td:last-child');
    		if ($(tdFirst).find('.button.manager').length == 0) {
    			$('<div class="ui teal button small manager edit" style="margin-right: 10px">Edit</div>').prependTo(tdFirst);
    			$('<div class="ui red button small manager delete" style="margin-left: 10px; float: right">Delete</div>').appendTo(tdLast);
    		}
    	},
    	function () {
    		$(this).removeClass('warning');
    		$(this).find('.button.manager').remove();
    	}
    );

    $('.table.manager > tbody > tr').on('click', '.manager.edit', function() {
  		var id = $(this).parents('tr').attr('data-id');
  		var pathname = window.location.pathname;
  		window.location = pathname.replace(/\/list\//, '/edit/') + '/' + id;
	});

	$('.table.manager > tbody > tr').on('click', '.manager.delete', function() {
  		var tr = $(this).parents('tr');
  		var id = $(tr).attr('data-id');
  		$(tr).find('.button.manager').remove();
  		$('.small.modal').modal('show');
  		var name = $(tr).find('td:first-child').html();
  		$('.delete.content').html('Are you sure you want to delete: <br /><br /><div class="ui teal inverted segment"><p>' + name + '</p></div>');
  		$('.confirmed.delete').attr('data-id', id);
	});

	$('.confirmed.delete').click(function () {
		var id = $(this).attr('data-id');
		var table = $('tr[data-id="' + id + '"]').parents('table');
		var pathname = window.location.pathname;
		var url = pathname.replace(/\/list\//, '/manager/') + '/' + id;
		$.ajax({
		  	type: "DELETE",
		  	url: url,
		  	success: function (response) {
		  		window.location = window.location.pathname;
		  	},
		  	error: function () {
		  		console.log('Error');
		  	},
		  	dataType: 'json'
		});
	});

	$('.manager.add').click(function () {
		var pathname = window.location.pathname;
		window.location = pathname.replace(/\/list\//, '/add/');
	});
});

var itemIn = function (item) {
	$(item).find('.ui.buttons').css({display: 'none'});
	$(item).find('a.star').css({display: 'none'});
	$(item).attr('data-in', 't');
	setTimeout(function () {
		if ($(item).attr('data-in') == 'f') {
			return;
		}
		$(item).find('.ui.buttons').stop().fadeIn(600).css('display', 'inline-block');
		$(item).find('a.star').stop().fadeIn(1200);
	}, 500);
};

var itemOut = function (item) {
	$(item).attr('data-in', 'f');
	$(item).find('a.star').css({display: 'none'});
	$(item).find('.ui.buttons').stop().fadeOut(50);
};