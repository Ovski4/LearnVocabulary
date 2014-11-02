jQuery(document).ready(function() {
    if (window.location.pathname != localStorage.getItem('current_revision_path')) {
        localStorage.clear();
    }
});