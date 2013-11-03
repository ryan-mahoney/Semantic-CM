$(document).ready(function () {
	$('#omnisearch').typeahead({
        remote: '/Manager/api/search?q=%QUERY',
        limit: 20
    });
    $("#omnisearch").on('typeahead:selected', function(evt, data) {
        //$('#rider-value').val(data['id']);
        console.log(data);
    });

    $('.item.manager').mouseenter(function () {
       	itemIn(this);
    }).mouseleave(function () {
    	itemOut(this);
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