jQuery(document).ready(function() {
    jQuery(document).on('click', 'a.star', function(e) {
        e.preventDefault();
        var $a = jQuery(this);
        var url = $a.attr('href');
        jQuery.ajax({
           url: url
        }).success(function(data) {
            if (data == "Starred") {
                $a.toggleClass('star-yes');
            } else {
                console.log(" WTF")
            }
        }).error(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    });
});
