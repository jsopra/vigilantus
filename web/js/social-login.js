(function($) {
    $('[data-social-login]').on('click', function(e) {
        e.preventDefault();
        var url = socialHandlerUrl + '?authclient=' + $(this).data('socialName');
        window.open(url, 'auth', "width=900, height=700");
    });
})(jQuery);
