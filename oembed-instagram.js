(function($){
    var responsive = function(){
        $('.oembed-instagram iframe').each(function(){
            var height = $(this).width() + 90;
            $(this).css('height', height);
        });
    }

    $(window).resize(function(){
        responsive();
    });

    responsive();
})(jQuery);
