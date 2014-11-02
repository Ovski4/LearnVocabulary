jQuery(document).on('click', 'button.confirm-delete', function() {
    if(confirm("Are you sure you want to delete this translation")) {
        return true;
    }
    else {
        return false;
    }
});