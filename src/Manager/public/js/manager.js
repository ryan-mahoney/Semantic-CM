define(function (require) {
    var $ = require('jquery'),
        semantic = require('semantic');

    $(document).ready(function () {
        $("#hamburger").click(function () {
            $(".top-menu").toggleClass("top-animate");
            $(".mid-menu").toggleClass("mid-animate");
            $(".bottom-menu").toggleClass("bottom-animate");
            $("#manager-main-nav").fadeToggle(500);
        });

        $("#manager-header-profile").click(function () {
            $("#manager-profile-nav").fadeToggle(500);
        });

        $('#manager-profile-nav-close').click(function () {
            $("#manager-profile-nav").fadeToggle(500);
        });

        $("#manager-main-nav").appendTo("body");
        $("#manager-profile-nav").appendTo("body");
    });

    return function () {};
});