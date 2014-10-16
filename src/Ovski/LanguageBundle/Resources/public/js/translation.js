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
    var $leftHideButton = jQuery('<button class="hide-column-left hide-column">'+leftButtonText+'</button>');
    var $rightHideButton = jQuery('<button class="hide-column-right hide-column">'+rightButtonText+'</button>');
    var $resetColumns = jQuery('<button class="show-columns">'+displayButtonText+'</button>');

    var shuffleButtonText = '<i class="fa fa-random"></i>'
    var $shuffle = jQuery('<button class="shuffle">'+shuffleButtonText+'</button>');
    var undoShuffleButtonText = '<span class="fa-stack"><i class="fa fa-random"></i><i class="fa fa-ban fa-2x text-danger"></i></span>'
    var $undoShuffle = jQuery('<button class="undo-shuffle">'+undoShuffleButtonText+'</button>');

    var displayValue = localStorage.getItem('displayValue');

    if (displayValue != null) {
        if (displayValue == 'hide-left') {
            hideLeftColumn();
            $actions.append($resetColumns);
        } else if (displayValue == 'hide-right') {
            hideRightColumn();
            $actions.append($resetColumns);
        }
    } else {
        $actions.append($leftHideButton);
        $actions.append($rightHideButton);
    }
    $actions.append($shuffle);
    console.log(localStorage.getItem('shuffle'));
    if (localStorage.getItem('shuffle') == 'yes') {
        shuffleTranslations();
        $actions.append($undoShuffle);
    }

    $actions.insertBefore('table');

    jQuery(document).on('click', 'button.undo-shuffle', function() {
        localStorage.setItem('shuffle', 'no');
        sortTranslations();
        this.remove();
    });

    jQuery(document).on('click', 'button.shuffle', function() {
        localStorage.setItem('shuffle', 'yes');
        shuffleTranslations();
        $actions.append($undoShuffle);
    });

    // hide left column on click
    jQuery(document).on('click', '.hide-column-left', function() {
        localStorage.setItem('displayValue', 'hide-left');
        hideLeftColumn();
        appendShuffleButtons();
    });

    // hide right column on click
    jQuery(document).on('click', '.hide-column-right', function() {
        localStorage.setItem('displayValue', 'hide-right');
        hideRightColumn();
        appendShuffleButtons();
    });

    // display all columns on click on reset button
    jQuery(document).on('click', '.show-columns', function() {
        localStorage.clear();
        displayAllColumn();
        appendShuffleButtons();
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

    /**
     * Append the shuffle and undo-shuffle buttons
     */
    function appendShuffleButtons() {
        $actions.append($shuffle);
        $undoShuffle.remove();
        if (localStorage.getItem('shuffle') == 'yes') {
            $actions.append($undoShuffle);
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
        console.log("todo!");
    }

    /**
     * Hide left td of rows
     */
    function hideLeftColumn() {
        hiddenWordCount = tableSize;
        hiddenColumn = 'left';
        jQuery('.hide-column').remove();
        jQuery($actions).append($resetColumns);
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
        jQuery('.hide-column').remove();
        jQuery($actions).append($resetColumns);
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
        $actions.append($leftHideButton);
        $actions.append($rightHideButton);
        $resetColumns.remove();
        jQuery('table > tbody > tr > td > span').each(function() {
            jQuery(this).attr('class', 'is-visible');
            jQuery(this).parent().removeClass();
        });
    }
}

jQuery(document).ready(function() {
    handleRevisionTable();
});