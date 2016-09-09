;
(function() {
    "use strict";

    var ERe = function()
    {
        var isTouchDevice = function()
        {
            try {
                return document.createEvent("TouchEvent"),
                !0;
            } catch (t) {
                return !1;
            }
        };

        var getViewPort = function()
        {
            var t = window
              , e = "inner";
            return "innerWidth" in window || (e = "client",
            t = document.documentElement || document.body),
            {
                width: t[e + "Width"],
                height: t[e + "Height"]
            }
        };

        var initToTop = function()
        {
            if ($().UItoTop) {
                $().UItoTop({
                    scrollSpeed: 500
                });
            }
        };

        var initMenu = function()
        {
            $("#main-menu").menumaker({
                breakpoint: 959,
                format: "multitoggle"
            });
        };

        var initLiActive = function()
        {
            $("#main-menu li").each(function()
            {
                if ($(this).hasClass("active")) {
                    $(this).parents("li").addClass("active");
                }
            });
        };

        var initLayout = function()
        {
            var main = $("#content");
            var height;

            if ($("#content").height() < getViewPort().height) {
                height = getViewPort().height - $("#footer").outerHeight(true) - $("#header").outerHeight(true) - 23;

                main.css("min-height", height);
            }

            if (parseInt(getViewPort().width) >= 768) {
                $("#main-menu.small-screen > ul > li")
                    .not(".hidden-lg")
                    .last()
                    .css("border-bottom", "1px solid rgba(120, 120, 120, 0.15)");
            }
        };

        var initSlider = function()
        {
            if ($().flexslider) {
                $(window).load(function() {
                    $(".flexslider").flexslider({
                        pauseOnHover: true,
                        controlsContainer: ".flex-container",
                        slideshowSpeed: 7000,
                        animationSpeed: 600
                    });
                });
            }
        };

        return {
            init: function() {
                initToTop();
                initMenu();
                initLiActive();
                initLayout();
                initSlider();
            }
        };
    }();

    jQuery(document).ready(function()
    {
        ERe.init();
    });

    jQuery(window).on("resize orientationchange webkitfullscreenchange mozfullscreenchange fullscreenchange", function()
    {
        window.location.reload();
    });
})();
