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

        var getFormData = function(contactForm)
        {
            var $data = "";
            contactForm.find(":input").each(function()
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

        var resetFormData = function(contactForm)
        {
            contactForm.find(":input").each(function()
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
                $height = getViewPort().height - jQuery("#footer").outerHeight(true) - jQuery("#header").outerHeight(true) - 33;

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

        var initShorten = function()
        {
            if (jQuery().shorten && (jQuery(".shorten-image").length > 0 || jQuery(".shorten-aligned").length > 0 || jQuery(".shorten-text").length > 0)) {
                jQuery(".shorten-image").shorten({showChars: 550});

                jQuery(".shorten-aligned").shorten({showChars: 1100});

                jQuery(".shorten-text").shorten({showChars: 300});
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
                        data: getFormData($contactForm)
                    }).done(function(rsp)
                    {
                        var $resultBoxElement = null;

                        if (rsp.result === true) {
                            $resultBoxElement = jQuery("#contact-notification-box-success");
                        } else if (rsp.result === false) {
                            $resultBoxElement = jQuery("#contact-notification-box-error");

                            grecaptcha.reset();
                        }

                        $resultBoxElement.css("display", "");
                        $contactFormSubmit.removeAttr("disabled", "");

                        //resetFormData();

                        if ($contactNotificationTimeout > 0) {
                            var $timer = window.setTimeout(function()
                            {
                                window.clearTimeout($timer);
                                $resultBoxElement.fadeOut("slow");
                            }, $contactNotificationTimeout);
                        }

                    });

                    return false;
                });

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
                initShorten();
                initFormContactSubmit();
            }
        };
    }();

    jQuery(document).ready(function()
    {
        ERe.init();
    });
})();
