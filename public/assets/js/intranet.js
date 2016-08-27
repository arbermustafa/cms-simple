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

        return {
            init: function() {
                initConfirmDialog();
            }
        };
    }();

    jQuery(document).ready(function()
    {
        EReAdmin.init();
    });
})();
