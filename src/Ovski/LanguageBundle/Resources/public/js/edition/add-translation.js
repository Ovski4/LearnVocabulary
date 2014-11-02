jQuery(document).ready(function() {
    jQuery('.add-translation form').on('submit', function(e) {
        e.preventDefault();
        var $form = jQuery(this);
        $ajaxLoader = jQuery('.add-translation form .submit-button .ajax-ready');
        $ajaxLoader.toggleClass('ajax-loader');
        jQuery.ajax({
           url: $form.attr('action'),
           type: $form.attr('method'),
           data: $form.serialize()
        }).success(function(data) {
            if (data.indexOf("<div class=\"error\">") > -1) {
                jQuery('.add-translation form .error').replaceWith(data);
            } else {
                jQuery('table tbody').prepend(data);
                jQuery('table tbody tr:last-child').remove();
                resetForm('.add-translation form');
            }
            $ajaxLoader.toggleClass('ajax-loader');
        }).error(function(jqXHR, textStatus, errorThrown) {
            console.log("error here!");
            $ajaxLoader.toggleClass('ajax-loader');
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    });
});

/**
 * Reset all fields of a from
 */
function resetForm(formSlector)
{
    jQuery(':input', formSlector)
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected')
    ;
}