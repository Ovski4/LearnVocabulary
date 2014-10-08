jQuery(document).ready(function() {
    var hiddenColumn = null;
    var tableSize = jQuery('table > tbody > tr').length;
    var hiddenWordCount = 0;

    // hide or display columns
    var $actions = jQuery('<div class="actions"></div>');
    var leftButtonText = jQuery('.translation-revision table').attr('data-left-button-text');
    var rightButtonText = jQuery('.translation-revision table').attr('data-right-button-text');
    var displayButtonText = jQuery('.translation-revision table').attr('data-display-text');
    var $leftHideButton = jQuery('<button class="hide-column-left hide-column">'+leftButtonText+'</button>');
    var $rightHideButton = jQuery('<button class="hide-column-right hide-column">'+rightButtonText+'</button>');
    var $resetColumns = jQuery('<button class="show-columns">'+displayButtonText+'</button>');

    $actions.append($leftHideButton);
    $actions.append($rightHideButton);
    $actions.insertBefore('table');

    // hide left column on click
    jQuery(document).on('click', '.hide-column-left', function() {
        hiddenWordCount = tableSize;
        hiddenColumn = 'left';
        jQuery('.hide-column').remove();
        jQuery($actions).append($resetColumns);
        jQuery('table > tbody > tr > td:first-child > span').each(function() {
            jQuery(this).parent().addClass('hidden-children');
            jQuery(this).attr('class', 'is-invisible');
        });
    });

    // hide right column on click
    jQuery(document).on('click', '.hide-column-right', function() {
        hiddenWordCount = tableSize;
        hiddenColumn = 'right';
        jQuery('.hide-column').remove();
        jQuery($actions).append($resetColumns);
        jQuery('table > tbody > tr > td:nth-child(2) > span').each(function() {
            jQuery(this).parent().addClass('hidden-children');
            jQuery(this).attr('class', 'is-invisible');
        });
    });

    // display all columns on click on reset button
    jQuery(document).on('click', '.show-columns', function() {
        hiddenColumn = null;
        $actions.append($leftHideButton);
        $actions.append($rightHideButton);
        $resetColumns.remove();
        jQuery('table > tbody > tr > td > span').each(function() {
            jQuery(this).attr('class', 'is-visible');
            jQuery(this).parent().removeClass();
        });
    });

    // on click on span
    jQuery(document).on('click', 'table tr td', function() {
        if (jQuery(this).find('span').hasClass('is-invisible')) {
            jQuery(this).find('span').attr('class', 'is-visible');
            jQuery(this).removeClass();
            hiddenWordCount--;
            if (hiddenColumn == 'left') {
                jQuery('div.actions').prepend($leftHideButton);
            } else {
                jQuery('div.actions').prepend($rightHideButton);
            }
            if (hiddenWordCount == 0) {
                $resetColumns.remove();
                if (hiddenColumn == 'left') {
                    jQuery('div.actions').append($rightHideButton);
                } else {
                    jQuery('div.actions').prepend($leftHideButton);
                }
            }
        }
    });
});