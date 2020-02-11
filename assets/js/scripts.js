(function($){

    // Toggles the sliding of the mobile menu
    var handle_menu = function() {

        var menu_burger = $('#site-nav__burger')
            site_nav = $('#site-nav'),
            site_menu_items = $('#site-nav__items');

        menu_burger.on('click', function(e){
            site_nav.toggleClass('site-nav--mobile');
            site_menu_items.toggleClass('site-nav__items--opened');
            site_menu_items.slideToggle();
        });

    };

    // Toggles the sliding of the mobilemenu's subitems
    var handle_submenu = function() {
    
        var parent_item = $('.site-nav__item-link--with-sublinks');

        parent_item.on('click', function(e){
            e.preventDefault();                
            var $this = $(this),
                submenu = $this.siblings('.site-nav__subitems'),
                chevron_icon = $this.siblings('.site-nav__chevron').find('.chevron-icon');

            $this.children().on('click', function(e){
                e.stopPropagation();
            });
            
            chevron_icon.toggleClass('rotate-180');
            
            submenu.slideToggle();
        });

    }

    handle_menu();
    handle_submenu();

})(jQuery);