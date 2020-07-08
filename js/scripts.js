(function($){

    var waitForFinalEvent = (function () {
        var timers = {};
        return function (callback, ms, uniqueId) {
          if (!uniqueId) {
            uniqueId = "Don't call this twice without a uniqueId";
          }
          if (timers[uniqueId]) {
            clearTimeout (timers[uniqueId]);
          }
          timers[uniqueId] = setTimeout(callback, ms);
        };
      })();

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
    
        var parent_item = $('.site-nav__item--with-sublinks');
        
        if(window.innerWidth < 990) {

            parent_item.on('click', function(e){

                // e.preventDefault();

                var $this = $(this),
                    submenu = $this.find('.site-nav__subitems'),
                    chevron_icon = $this.find('.site-nav__chevron').find('.chevron-icon');

                $this.children().on('click', function(e){
                    e.stopPropagation();
                });

                chevron_icon.toggleClass('rotate-180');

                submenu.toggleClass('site-nav__subitems--opened');
                
            });            
            
        } else {
            
            parent_item.hoverIntent(function(){
                var $this = $(this),
                submenu = $this.find('.site-nav__subitems');
                
                submenu.toggleClass('site-nav__subitems--opened');                

            });

        }

    }

    var fill_year = function(){
        var current_year = (new Date()).getFullYear(),
            year_input = $('#year-input');

        for (var i = 0; i < 11; i++) {
            var the_option = current_year + i,
                the_option_text = '<option value="' + the_option + '">' + the_option + '</option>';
            year_input.append(the_option_text);
        }            

    };

    
    // $(window).resize(function () {
    //     waitForFinalEvent(function(){
    //         handle_submenu();
    //     }, 600, "some unique string");
    // });
    
    handle_menu();
    handle_submenu();

    fill_year();

})(jQuery);