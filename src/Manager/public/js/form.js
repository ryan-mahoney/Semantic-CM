$(document).ready(function () {
	titleInitialize();
	tabularMenuInitialize();
	formSubmitInitialize();
	modifiedDateInitialize();
});

var ManagerSaved = function (form, data) {
	var message = 'Information Saved.';
	if ($(form).attr('data-singular') !== "undefined") {
		message = $(form).attr('data-singular') + ' Saved.';
	}
	$('.form-notice').addClass('teal').removeClass('red');
	$('.form-notice > .container').html('<div class="header item"><h1>' + message + '</h1></div>');
	$('.form-notice').sidebar({overlay: true}).sidebar('show');
	setTimeout(function () {
		$('.form-notice').sidebar({overlay: true}).sidebar('hide');
	}, 2500);
	$('abbr.time').each(function () {
		var now = new Date();
		$(this).attr('data-livestamp', now.toISOString());
	});

	//loop over embedded documents, update
};

var FormError = function (errors) {
	$('.form-notice').addClass('red').removeClass('teal');
	$('.form-notice > .container').html('<div class="header item"><h1>Error in Form</h1></div>');
	$('.form-notice').sidebar({overlay: true}).sidebar('show');
	setTimeout(function () {
		$('.form-notice').sidebar({overlay: true}).sidebar('hide');
	}, 5000);	
};

var modifiedDateInitialize = function () {
	$('abbr.timeago').each(function () {
        var uniqid = 'moment-' + Math.random().toString(36).substr(2, 7);
        if (typeof($(this).attr('data-id')) != 'undefined') {
            return;
        }
        $(this).attr('data-id', uniqid);
		$(this).timeago();
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
            $('.ui.tab[data-tab="' + active + '"]').css({display: 'block', position: 'relative', visibility: 'visible'});
        }
    }, '.ui.tabular.menu > a');
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
		$(this).parents('form').submit();
	});
};

var titleInitialize = function () {
	var formDom = $('form');
	var titleField = $(formDom).attr('data-titlefield');
	if (titleField == '' || typeof(titleField) == 'undefined') {
		return;
	}
	var manager = $(formDom).attr('data-manager');
	var titleFieldName = manager + '[' + titleField + ']';
	var slugFieldName = manager + '[code_name]';
	var crumbDom = $('.top-container .breadcrumb a:last-child > h2');
	var titleDom = $('input[name="' + titleFieldName + '"]');
	var slugDom = $('input[name="' + slugFieldName + '"]');
	var watchedObject = {
		value: $(titleDom).val()
	};
	var mode = ($(titleDom).val().length > 0) ? 'update' : 'add';
	$(titleDom).bind("change keyup input", function () {
		watchedObject.value = $(this).val();
	});
    watch(watchedObject, "value", function () {
        $(crumbDom).html(watchedObject.value);
        if (mode == 'add') {
			$(slugDom).val(slugg(watchedObject.value, '-'));   
    	}
    });
    if (mode == 'update') {
    	$(crumbDom).html(watchedObject.value);
    }
};

var saveEventCopy = function () {

};

var saveEventDelete = function () {

};

var saveEventAnother = function () {

};