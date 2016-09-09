;
(function() {
    "use strict";

    var EReAdmin = function()
    {
        var urlencode = function(str)
        {
            return encodeURIComponent(str)
                .replace(/!/g, '%21')
                .replace(/'/g, '%27')
                .replace(/\(/g, '%28')
                .replace(/\)/g, '%29')
                .replace(/\*/g, '%2A')
                .replace(/%20/g, '+')
        };

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
                $outer = jQuery("#nestable > ol"),
                $output = jQuery("#content"),
                $addP = jQuery("#add-p"),
                $addC = jQuery("#add-c"),
                $addCL = jQuery("#add-cl");

            var updateContent = function(e)
            {
                var $list = e.length ? e : jQuery(e.target);
                if (window.JSON) {
                    $output.text(window.JSON.stringify($list.nestable("serialize")));
                } else {
                    $output.text("{}");
                }
            };

            var addItem = function(item, cl = false)
            {
                var $data = '';
                if (cl) {
                    $data += 'data-url="' + item.url + '" data-title="' + item.title + '"';
                } else {
                    $data += 'data-id="' + item.id + '"';
                }

                var $element = '<li class="dd-item dd3-item" ' + $data + '>' +
                    '<div class="dd-handle dd3-handle"><i class="fa fa-arrows-alt"></i></div>' +
                    '<div class="dd3-content">' + item.title + '</div>' +
                    '<button type="button" class="dd3-delete btn btn-default btn-block" data-action="remove">' +
                    '<i class="fa fa-close"></i></button>' +
                    '</li>';

                if ($outer.length) {
                    $outer.append($element);
                } else {
                    $menu.empty();
                    $outer = $menu.append("<ol class=\"dd-list\"></ol>").find("ol");
                    $outer.append($element);
                }

                updateContent($menu.data("output", $output));
            };

            $addP.click(function()
            {
                jQuery("input.c-pages:checked").each(function()
                {
                    var $value = $(this).val().split("|");
                    var $id = parseInt($value[0]),
                        $title = $value[1];
                    var $elem = {
                        title: $title,
                        id: $id
                    }

                    addItem($elem);
                    $(this).attr("checked", false);
                });
            });

            $addC.click(function()
            {
                jQuery("input.c-categories:checked").each(function()
                {
                    var $value = $(this).val().split("|");
                    var $id = parseInt($value[0]),
                        $title = $value[1];
                    var $elem = {
                        title: $title,
                        id: $id
                    };

                    addItem($elem);
                    $(this).attr("checked", false);
                });
            });

            $addCL.click(function()
            {
                var $title = jQuery("#cl-title"),
                    $url = jQuery("#cl-url");
                var $elem = {
                    title: $title.val(),
                    url: $url.val()
                }

                addItem($elem, true);
            });

            if ($menu.length) {
                var $maxDepth = parseInt($menu.attr("title"));

                $menu.nestable({
                    maxDepth: $maxDepth,
                }).on("change", updateContent);

                updateContent($menu.data("output", $output));
            }
        };

        var initCKEditor = function()
        {
            var $content = jQuery("#content");

            if ($content.length) {
                CKEDITOR.replace("content", {
                    autoUpdateElement: true,
                    height: "450px",
                    resize_enabled: false,
                    toolbarCanCollapse: false,
                    coreStyles_strike: {element: "span", attributes: {"style": "text-decoration:line-through"}},
                    coreStyles_underline: {element: 'span', attributes: {"style": "text-decoration:underline"}},
                    forcePasteAsPlainText: true,
                    enterMode: CKEDITOR.ENTER_BR,
                    pasteFromWordPromptCleanup: true,
                    pasteFromWordRemoveFontStyles: true,
                    pasteFromWordRemoveStyles: false,
                    toolbar: [
                        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat','-','Source','Maximize','Preview','Print'],
				    	'/',
				    	['Bold','Italic','Underline','Strike','-','NumberedList','BulletedList','-','Outdent','Indent','-','Format', 'FontSize', 'TextColor','BGColor'],
				    	'/',
				    	['Link','Unlink','-','Image','-','Table','HorizontalRule','SpecialChar']
			    	],
                    filebrowserBrowseUrl: '/assets/vendor/ckeditor/filemanager/browser/default/browser.html?Connector=' + urlencode('/index.php?_rte_file_manager=1')
                });
            }
        };

        return {
            init: function() {
                initConfirmDialog();
                initImgCropper();
                initMenu();
                initCKEditor();
            }
        };
    }();

    jQuery(document).ready(function()
    {
        EReAdmin.init();
    });
})();
