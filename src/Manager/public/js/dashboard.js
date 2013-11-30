var checkWidth = function () {
    $('.ui.items').removeClass('on two three four five six seven eight');
    var width = $(window).width();
    if (width < 400) {
        $('.ui.items').addClass('one');
    } else if (width < 550) {
        $('.ui.items').addClass('two');
    } else if (width < 850) {
        $('.ui.items').addClass('three');
    } else if (width < 1250) {
        $('.ui.items').addClass('four');
    } else if (width < 1550) {
        $('.ui.items').addClass('five');
    } else if (width < 1850) {
        $('.ui.items').addClass('six');
    } else if (width < 2100) {
        $('.ui.items').addClass('seven');
    } else {
        $('.ui.items').addClass('eight');
    }
};

$(document).ready(function() {
    checkWidth();
    $(window).resize(function() {
        checkWidth();
    });
});