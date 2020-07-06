(function ($) {
    $('#toggle-instructions').on('click', function (e) {
        e.preventDefault();
        $('#instructions').find('section').slideToggle();
    });
})(jQuery);
