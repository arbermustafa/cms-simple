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

        return {
            init: function() {
                initConfirmDialog();
                initImgCropper();
            }
        };
    }();

    jQuery(document).ready(function()
    {
        EReAdmin.init();
    });
})();
