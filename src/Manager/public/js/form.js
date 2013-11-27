$(document).ready(function () {
	$('.ui.button.manager.submit').click(function () {
		$(this).parents('form').submit();
	});
});