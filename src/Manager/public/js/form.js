$(document).ready(function () {
	titleInitialize();
	tabularMenuInitialize();
	formSubmitInitialize();
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
	//find title input
	
	//update the breadcrumbs
	
	//detect if slug is set
	
	//bind input to breadcrumbs
	
	//optionally bind input to slugify
};

var saveEventCopy = function () {

};

var saveEventDelete = function () {

};

var saveEventAnother = function () {

};