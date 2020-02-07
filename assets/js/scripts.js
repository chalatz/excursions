(function($){

    // Toggles the sliding of the mobile menu
    var handle_menu = function() {

        var menu_header = $('#site-nav__header')
            site_nav = $('#site-nav');

        menu_header.on('click', function(e){
            site_menu.toggleClass('site-menu--mobile');
            site_menu_items.slideToggle();
        });

    };

})(jQuery);