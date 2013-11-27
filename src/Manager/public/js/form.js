$(document).ready(function () {
	$('.ui.button.manager.submit').click(function () {
		console.log('hello');
		$('.manager.container form').submit();
	});
});