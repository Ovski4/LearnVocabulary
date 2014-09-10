jQuery(document).ready(function() {
    // reset article select box on word type change
    // and hide select box on word type 'name'
    jQuery('#ovski_languagebundle_translation_wordType').change(function(){
        jQuery('.ovski-article-selectbox').prop('selectedIndex',0);
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
        jQuery('.ovski-word div:nth-child(2)').fadeIn().css('display', 'block');
    } else {
        jQuery('.ovski-word div:nth-child(2)').fadeOut();
    }
}