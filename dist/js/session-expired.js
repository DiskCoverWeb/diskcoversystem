(function ($) {
    var redirectingToLogin = false;

    $(document).ajaxError(function (_event, xhr) {
        if (xhr.status !== 401 || redirectingToLogin) {
            return;
        }

        redirectingToLogin = true;
        window.location.assign(window.sessionLoginUrl);
    });
})(jQuery);
