(function($){

    // Toggles the sliding of the mobile menu
    var handle_menu = function() {

        var menu_burger = $('#site-nav__burger')
            site_nav = $('#site-nav'),
            site_menu_items = $('#site-nav__items');

        menu_burger.on('click', function(e){
            site_nav.toggleClass('site-nav--mobile');
            site_menu_items.slideToggle();
        });

    };

    handle_menu();

})(jQuery);