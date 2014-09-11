jQuery(document).ready(function() {
    // reset article select box on word type change
    // and hide select box on word type 'name'
    jQuery('#ovski_languagebundle_translation_wordType').change(function() {
        jQuery('.ovski-article-selectbox').prop('selectedIndex',0);
        hideOrDisplayArticle(this);
    });

    // hide or display columns
    var $HideButtons = jQuery('<div class="hide-column"></div>');
    var $leftHideButton = jQuery('<button class="hide-column-left">Hide left column</button>');
    var $rightHideButton = jQuery('<button class="hide-column-right">Hide right column</button>');
    var $resetColumns = jQuery('<button class="show-columns">Reset columns</button>');

    $HideButtons.append($leftHideButton);
    $HideButtons.append($rightHideButton);
    $HideButtons.insertBefore('table');

    // hide left column on click
    jQuery(document).on('click', '.hide-column-left', function() {
        jQuery($HideButtons).replaceWith($resetColumns);
        jQuery('table > tbody > tr > td:first-child > span').each(function() {
            jQuery(this).attr('class', 'invisible');
        });
    });

    // hide right column on click
    jQuery(document).on('click', '.hide-column-right', function() {
        jQuery($HideButtons).replaceWith($resetColumns);
        jQuery('table > tbody > tr > td:nth-child(2) > span').each(function() {
            jQuery(this).attr('class', 'invisible');
        });
    });

    // display all columns on click
    jQuery(document).on('click', '.show-columns', function() {
        jQuery($resetColumns).replaceWith($HideButtons);
        jQuery('table > tbody > tr > td > span').each(function() {
            jQuery(this).attr('class', 'visible');
        });
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