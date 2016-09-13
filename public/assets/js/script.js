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
            if (jQuery().UItoTop) {
                jQuery().UItoTop({
                    scrollSpeed: 500
                });
            }
        };

        var initMenu = function()
        {
            jQuery("#main-menu").menumaker({
                breakpoint: 959,
                format: "multitoggle"
            });
        };

        var initLiActive = function()
        {
            jQuery("#main-menu li").each(function()
            {
                if (jQuery(this).hasClass("active")) {
                    jQuery(this).parents("li").addClass("active");
                }
            });
        };

        var initLayout = function()
        {
            var $main = jQuery("#content");
            var $height;

            if (jQuery("#content").height() < getViewPort().height) {
                $height = getViewPort().height - jQuery("#footer").outerHeight(true) - jQuery("#header").outerHeight(true) - 38;

                $main.css("min-height", $height);
            }

            if (parseInt(getViewPort().width) >= 768) {
                jQuery("#main-menu.small-screen > ul > li")
                    .not(".hidden-lg")
                    .last()
                    .css("border-bottom", "1px solid rgba(120, 120, 120, 0.15)");
            }
        };

        var initSlider = function()
        {
            if (jQuery().flexslider) {
                jQuery(window).load(function() {
                    jQuery(".flexslider").flexslider({
                        pauseOnHover: true,
                        controlsContainer: ".flex-container",
                        slideshowSpeed: 7000,
                        animationSpeed: 600
                    });
                });
            }
        };

        var initFancybox = function()
        {
            if (jQuery().fancybox && jQuery(".fancybox").length > 0) {
                jQuery(".fancybox").fancybox({
                    openEffect: "fade",
                    closeEffect: "fade",
                    nextEffect: "fade",
                    prevEffect: "fade",
                    arrows: !isTouchDevice(),
                    helpers: {
                        title: {
                            type: "inside"
                        },
                        buttons: {},
                        media: {}
                    },
                    beforeLoad: function ()
                    {
                        this.title = "Image " + (this.index + 1) + " of " + this.group.length + (this.title ? " - " + this.title : "");
                    }
                });
            }
        };

        var initFormContactSubmit = function()
        {
            var $contactForm = jQuery("#contact-form");
            jQuery(".notification-close-success, .notification-close-error").click(function()
            {
                jQuery(this).parent().fadeOut("fast");

                return false;
            });

            if ($contactForm && $contactForm.length > 0) {
                var $contactNotificationTimeout = 8000;
                var $contactFormSubmit = $contactForm.find("#submit");

                $contactFormSubmit.bind("click", function(e)
                {
                    $contactFormSubmit.attr("disabled", "disabled");
                    jQuery.ajax({
                        type: "POST",
                        url: "/contact-submit",
                        dataType: "json",
                        data: getFormData()
                    }).done(function(rsp)
                    {
                        var $resultBoxElement = null;

                        if (rsp.result === true) {
                            $resultBoxElement = jQuery("#contact-notification-box-success");
                        } else if (rsp.result === false) {
                            $resultBoxElement = jQuery("#contact-notification-box-error");
                        }

                        $resultBoxElement.css("display", "");
                        $contactFormSubmit.removeAttr("disabled", "");

                        resetFormData();

                        if ($contactNotificationTimeout > 0) {
                            var $timer = window.setTimeout(function()
                            {
                                window.clearTimeout($timer);
                                $resultBoxElement.fadeOut("slow");
                            }, $contactNotificationTimeout);
                        }

                    });
                });

                function getFormData()
                {
                    var $data = "";
                    $contactForm.find(":input").each(function()
                    {
                        var $field = jQuery(this);
                        var $fieldName = $field.attr("name");
                        var $fieldValue = jQuery.trim($field.val());
                        if ($fieldValue !== "") {
                            $data += "&" + $fieldName + "=" + $fieldValue;
                        }
                    });

                    return $data;
                };

                function resetFormData()
                {
                    $contactForm.find(":input").each(function()
                    {
                        var $field = jQuery(this);
                        var $defaultValue = $field.prop("defaultValue");
                        if ($defaultValue) {
                            $field.val($defaultValue);
                        } else {
                            $field.val("");
                        }
                    });
                };

                return false;
            }
        };

        return {
            init: function() {
                initToTop();
                initMenu();
                initLiActive();
                initLayout();
                initSlider();
                initFancybox();
                initFormContactSubmit();
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
