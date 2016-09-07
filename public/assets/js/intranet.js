;
(function() {
    "use strict";

    var EReAdmin = function()
    {
        var initConfirmDialog = function()
        {
            jQuery("a.delete").click(function(event)
            {
                event.preventDefault();
                var href = jQuery(this).attr("href");
                jQuery("#confirmDialog").modal({
                    show : true,
                    backdrop : "static",
                    keyboard : false
                });

                jQuery("a.dataConfirmOK").attr("href", href);

                return false;
            });
        };

        var initImgCropper = function()
        {
            jQuery(".fileinput").on("change.bs.fileinput", function(event)
            {
                var $image = jQuery("#slide-area-select > img");

                if ($image.length) {
                    var $initCrop = {
                        left: 0,
                        top: 0,
                        width: 940,
                        height: 350
                    };

                    var $cropper = $image.cropper({
                        viewMode: 1,
                        dragMode: "move",
                        rotatable: false,
                        restore: false,
                        cropBoxResizable: false,
                        crop: function(coords)
                        {
                            $("#x").val(Math.round(coords.x));
                            $("#y").val(Math.round(coords.y));
                            $("#w").val(Math.round(coords.width));
                            $("#h").val(Math.round(coords.height));
                        }
                    });

                    var $imgData = $cropper.cropper("getImageData");
                    var $canvasData = $cropper.cropper("getCanvasData");

                    var $cropBox = {
                        left: ($imgData.width / $imgData.naturalWidth) * $initCrop.left + $canvasData.left,
                        top: ($imgData.width / $imgData.naturalWidth) * $initCrop.top + $canvasData.top,
                        width: $initCrop.width * $imgData.width / $imgData.naturalWidth,
                        height: $initCrop.height * $imgData.height / $imgData.naturalHeight
                    }

                    $cropper.cropper("setCropBoxData", $cropBox);
                }
            });
        };

        var initMenu = function()
        {
            var $menu = jQuery("#nestable"),
                $output = jQuery("#content");

            var updateContent = function(e)
            {
                var $list = e.length ? e : jQuery(e.target);
                if (window.JSON) {
                    $output.text(window.JSON.stringify($list.nestable("serialize")));
                } else {
                    $output.text("{}");
                }
            };

            if ($menu.length) {
                var $maxDepth = parseInt($menu.attr("title"));

                $menu.nestable({
                    maxDepth: $maxDepth,
                }).on("change", updateContent);

                updateContent($menu.data("output", $output));
            }
        };

        return {
            init: function() {
                initConfirmDialog();
                initImgCropper();
                initMenu();
            }
        };
    }();

    jQuery(document).ready(function()
    {
        EReAdmin.init();
    });
})();
