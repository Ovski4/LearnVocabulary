jQuery(document).ready(function() {

    // reset article select box on word type change
    // and hide select box on word type 'name'
    jQuery('#ovski_languagebundle_translation_wordType').change(function() {
        jQuery('.ovski-article-selectbox').prop('selectedIndex',0);
        hideOrDisplayArticle(this);
    });

    var hiddenColumn = null;
    var tableSize = jQuery('table > tbody > tr').length;
    var hiddenWordCount = 0;

    // hide or display columns
    var $actions = jQuery('<div class="actions"></div>');
    var $leftHideButton = jQuery('<button class="hide-column-left hide-column">Hide left column</button>');
    var $rightHideButton = jQuery('<button class="hide-column-right hide-column">Hide right column</button>');
    var $resetColumns = jQuery('<button class="show-columns">Display everything</button>');

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
        });
    });

    // on click on span
    jQuery(document).on('click', 'table tr td', function() {
        if (jQuery(this).find('span').hasClass('is-invisible')) {
            jQuery(this).find('span').attr('class', 'is-visible');
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

/**
 * Hide or display the article selectBox according to the word type selected option
 *
 * @param selectBox
 */
function hideOrDisplayArticle(selectBox) {
    if (jQuery(selectBox).find('option:selected').text() == 'name') {
        jQuery('.ovski-word div:nth-child(1)')./*fadeIn().*/css('display', 'block');
        jQuery('#ovski_languagebundle_translation > div:nth-child(2) > label').css('padding-top', '0');
        jQuery('#ovski_languagebundle_translation > div:nth-child(3) > label').css('padding-top', '0');
    } else {
        jQuery('.ovski-word div:nth-child(1)')./*fadeOut()*/css('display', 'none');;
        jQuery('#ovski_languagebundle_translation > div:nth-child(2) > label').css('padding-top', '3px');
        jQuery('#ovski_languagebundle_translation > div:nth-child(3) > label').css('padding-top', '3px');
    }
}