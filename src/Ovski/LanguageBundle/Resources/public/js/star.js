jQuery(document).ready(function() {
    jQuery(document).on('click', 'a.star', function(e) {
        e.preventDefault();
        var $a = jQuery(this);
        $a.toggleClass('ajax-loader');
        var url = $a.attr('href');
        jQuery.ajax({
           url: url
        }).success(function(data) {
            $a.toggleClass('ajax-loader');
            if (data == "Starred") {
                $a.toggleClass('star-yes');
            } else {
                console.log(" WTF")
            }
        }).error(function(jqXHR, textStatus, errorThrown) {
            $a.toggleClass('ajax-loader');
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    });
});
