(function($){
    var responsive = function(){
        $('.oembed-instagram iframe').each(function(){
            var vertical = 114;
            var horizontal = 16;
            var height = $(this).width() - horizontal + vertical;
            $(this).css('height', height);
        });
    }

    $(window).resize(function(){
        responsive();
    });

    responsive();
})(jQuery);
