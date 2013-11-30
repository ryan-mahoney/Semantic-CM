$(document).ready(function () {
	headerDropdownInitialize();
});

var headerDropdownInitialize = function () {
	$('.main.menu .ui.dropdown').each(function () {
		var uniqid = 'dropdown-' + Math.random().toString(36).substr(2, 7);
        if (typeof($(this).attr('data-id')) != 'undefined') {
            return;
        }
        $(this).attr('data-id', uniqid);
        $(this).dropdown();
	});
};