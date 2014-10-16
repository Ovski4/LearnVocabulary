/**
 * Display buttons, trigger events to hide and display translations
 */
function handleRevisionTable() {
    localStorage.setItem('current_revision_path', window.location.pathname);

    var hiddenColumn = null;
    var tableSize = jQuery('table > tbody > tr').length;
    var hiddenWordCount = 0;

    // hide or display columns
    var $actions = jQuery('<div class="actions"></div>');
    var leftButtonText = jQuery('.translation-revision table').attr('data-left-button-text');
    var rightButtonText = jQuery('.translation-revision table').attr('data-right-button-text');
    var displayButtonText = jQuery('.translation-revision table').attr('data-display-text');
    var $leftHideButton = jQuery('<button class="hide-column-left hide-column is-active">'+leftButtonText+'</button>');
    var $rightHideButton = jQuery('<button class="hide-column-right hide-column is-active">'+rightButtonText+'</button>');
    var $resetColumnsButton = jQuery('<button class="show-columns is-active">'+displayButtonText+'</button>');

    var shuffleButtonText = '<i class="fa fa-random"></i>'
    var $shuffle = jQuery('<button class="shuffle is-active">'+shuffleButtonText+'</button>');
    var undoShuffleButtonText = '<span class="fa-stack"><i class="fa fa-random"></i><i class="fa fa-ban fa-2x text-danger"></i></span>'
    var $undoShuffle = jQuery('<button class="undo-shuffle is-active">'+undoShuffleButtonText+'</button>');

    var displayValue = localStorage.getItem('displayValue');

    if (displayValue != null) {
        if (displayValue == 'hide-left') {
            setActivity($rightHideButton, false);
            setActivity($leftHideButton, false);
            hideLeftColumn();

        } else if (displayValue == 'hide-right') {
            setActivity($leftHideButton, false);
            setActivity($rightHideButton, false);
            hideRightColumn();
        }
    } else {
        setActivity($resetColumnsButton, false);
    }

    if (localStorage.getItem('shuffle') == 'yes') {
        shuffleTranslations();
    } else {
        setActivity($undoShuffle, false);
    }

    $actions.append($leftHideButton);
    $actions.append($rightHideButton);
    $actions.append($resetColumnsButton);
    $actions.append($shuffle);
    $actions.append($undoShuffle);


    $actions.insertBefore('table');

    jQuery(document).on('click', 'button.undo-shuffle.is-active', function() {
        localStorage.setItem('shuffle', 'no');
        sortTranslations();
        setActivity($undoShuffle, false);
    });

    jQuery(document).on('click', 'button.shuffle.is-active', function() {
        localStorage.setItem('shuffle', 'yes');
        shuffleTranslations();
        setActivity($undoShuffle, true);
    });

    // hide left column on click
    jQuery(document).on('click', '.hide-column-left.is-active', function() {
        localStorage.setItem('displayValue', 'hide-left');
        hideLeftColumn();
    });

    // hide right column on click
    jQuery(document).on('click', '.hide-column-right.is-active', function() {
        localStorage.setItem('displayValue', 'hide-right');
        hideRightColumn();
    });

    // display all columns on click on reset button
    jQuery(document).on('click', '.show-columns.is-active', function() {
        localStorage.removeItem('displayValue');
        displayAllColumn();
    });

    // on click on span
    jQuery(document).on('click', 'table tr td', function() {
        if (jQuery(this).find('span').hasClass('is-invisible')) {
            jQuery(this).find('span').attr('class', 'is-visible');
            jQuery(this).removeClass('is-invisible');
            hiddenWordCount--;
            if (hiddenColumn == 'left') {
                setActivity($leftHideButton, true);
            } else {
                setActivity($rightHideButton, true);
            }
            if (hiddenWordCount == 0) {
                setActivity($resetColumnsButton, false);
                if (hiddenColumn == 'left') {
                    setActivity($rightHideButton, true);
                } else {
                    setActivity($leftHideButton, true);
                }
            }
        }
    });

    /**
     * Set a button active or inactive
     */
    function setActivity($button, boolean) {
        if (boolean) {
            $button.addClass('is-active');
            $button.removeClass('is-inactive');
        } else {
            $button.addClass('is-inactive');
            $button.removeClass('is-active');
        }
    }

    /**
     * Shuffle table rows
     */
    function shuffleTranslations() {
        var rows = document.querySelector('.translation-revision table tbody');
        for (var i = rows.children.length; i >= 0; i--) {
            rows.appendChild(rows.children[Math.random() * i | 0]);
        }
    }

    /**
     * Sort table rows
     */
    function sortTranslations() {
        jQuery("table tbody tr").sort(sortRows).appendTo('table tbody');
        function sortRows(a, b){
            return (jQuery(b).data('position')) < ($(a).data('position')) ? 1 : -1;
        }
    }

    /**
     * Hide left td of rows
     */
    function hideLeftColumn() {
        hiddenWordCount = tableSize;
        hiddenColumn = 'left';
        setActivity(jQuery('.hide-column'), false);
        setActivity($resetColumnsButton, true);
        jQuery('table > tbody > tr > td:first-child > span').each(function() {
            jQuery(this).parent().addClass('hidden-children');
            jQuery(this).attr('class', 'is-invisible');
        });
    }

    /**
     * Hide right td of rows
     */
    function hideRightColumn() {
        hiddenWordCount = tableSize;
        hiddenColumn = 'right';
        setActivity(jQuery('.hide-column'), false);
        setActivity($resetColumnsButton, true);
        jQuery('table > tbody > tr > td:nth-child(2) > span').each(function() {
            jQuery(this).parent().addClass('hidden-children');
            jQuery(this).attr('class', 'is-invisible');
        });
    }

    /**
     * Display every td of columns
     */
    function displayAllColumn() {
        hiddenColumn = null;
        setActivity($leftHideButton, true);
        setActivity($rightHideButton, true);
        setActivity($resetColumnsButton, false);
        jQuery('table > tbody > tr > td > span').each(function() {
            jQuery(this).attr('class', 'is-visible');
            jQuery(this).removeClass('is-invisible');
            jQuery(this).parent().removeClass('hidden-children');
        });
    }
}

jQuery(document).ready(function() {
    handleRevisionTable();
});