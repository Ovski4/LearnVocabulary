jQuery(document).ready(function() {
    $selectToHide = jQuery('select.ovski-article-selectbox.empty');
    $selectToHide.hide();
    $selectToHide.parent().append(
        '<span>('+jQuery('select.ovski-article-selectbox.empty').attr('data-article')+')</span>'
    );

    hideOrDisplayArticle(jQuery('#ovski_languagebundle_translation_wordType'));

    // reset article select box on word type change
    // and hide select box on word type 'name'
    jQuery('#ovski_languagebundle_translation_wordType').change(function() {
        hideOrDisplayArticle(this);
    });
});

/**
 * Hide or display the article selectBox according to the word type selected option
 *
 * @param selectBox
 */
function hideOrDisplayArticle(selectBox) {
    if (jQuery('#ovski_languagebundle_translation_wordType').find('option:selected').val() ==
        jQuery("#ovski_languagebundle_translation_wordType option:nth-child(2)").val()) {
        jQuery('.ovski-word div:nth-child(1)').css('display', 'block');
    } else {
        jQuery('.ovski-word div:nth-child(1)').css('display', 'none');
        jQuery('.ovski-article-selectbox').prop('selectedIndex', 0);
        if ($selectToHide.length) {
            $selectToHide.prop('selectedIndex', 1);
        }
    }
}