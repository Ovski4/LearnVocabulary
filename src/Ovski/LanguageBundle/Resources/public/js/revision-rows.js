jQuery(document).ready(function() {
    jQuery(document).on('click', 'ul.pagination > li > a', function(e) {
        e.preventDefault();
        var $a = jQuery(this);
        $ajaxLoaderSpan = jQuery('.ajax-ready > span');
        $ajaxLoaderSpan.toggleClass('ajax-loader');
        var url = $a.attr('href');
        console.log(url);
        jQuery.ajax({
           url: url
        }).success(function(data) {
            jQuery('.ajax-revision').html(data);
            handleRevisionTable();
            $ajaxLoaderSpan.toggleClass('ajax-loader');
        }).error(function(jqXHR, textStatus, errorThrown) {
            console.log("error here!");
            $ajaxLoaderSpan.toggleClass('ajax-loader');
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    });
});
