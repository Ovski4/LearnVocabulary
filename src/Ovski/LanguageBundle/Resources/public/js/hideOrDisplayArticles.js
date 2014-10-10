jQuery(document).ready(function() {
    if (jQuery('#ovski_languagebundle_translation_wordType').find('option:selected').val() !=
        jQuery("#ovski_languagebundle_translation_wordType option:first").val()) {
        jQuery('.ovski-word div:nth-child(1)').css('display', 'none');
        jQuery('#ovski_languagebundle_translation > div:nth-child(2) > label').css('padding-top', '3px');
        jQuery('#ovski_languagebundle_translation > div:nth-child(3) > label').css('padding-top', '3px');
    }

    jQuery('select.ovski-article-selectbox.empty').replaceWith(
        '<span>('+jQuery('select.ovski-article-selectbox.empty').attr('data-article')+')</span>'
    );
    // reset article select box on word type change
    // and hide select box on word type 'name'
    jQuery('#ovski_languagebundle_translation_wordType').change(function() {
        jQuery('.ovski-article-selectbox').prop('selectedIndex', 0);
        hideOrDisplayArticle(this);
    });
});

/**
 * Hide or display the article selectBox according to the word type selected option
 *
 * @param selectBox
 */
function hideOrDisplayArticle(selectBox) {
    if (jQuery(selectBox).find('option:selected').text() == 'name') {
        jQuery('.ovski-word div:nth-child(1)').css('display', 'block');
        jQuery('#ovski_languagebundle_translation > div:nth-child(2) > label').css('padding-top', '0');
        jQuery('#ovski_languagebundle_translation > div:nth-child(3) > label').css('padding-top', '0');
    } else {
        jQuery('.ovski-word div:nth-child(1)').css('display', 'none');;
        jQuery('#ovski_languagebundle_translation > div:nth-child(2) > label').css('padding-top', '3px');
        jQuery('#ovski_languagebundle_translation > div:nth-child(3) > label').css('padding-top', '3px');
    }
}