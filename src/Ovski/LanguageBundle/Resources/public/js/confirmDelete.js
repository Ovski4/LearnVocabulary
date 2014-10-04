jQuery('button.confirm-delete').on('click', function() {
    if(confirm("Are you sure you want to delete this translation")) {
        return true;
    }
    else {
        return false;
    }
});